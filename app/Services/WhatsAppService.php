<?php

namespace App\Services;

use App\Models\WhatsappSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $apiVersion;
    protected ?string $accessToken;
    protected ?string $phoneNumberId;
    protected ?string $verifyToken;
    protected ?string $appSecret;
    protected bool $enabled;
    protected int $timeout;
    protected ChatbotService $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->apiUrl = rtrim((string) config('whatsapp.api_url', 'https://graph.facebook.com'), '/');
        $this->apiVersion = trim((string) config('whatsapp.api_version', 'v20.0'), '/');
        $this->accessToken = config('whatsapp.access_token');
        $this->phoneNumberId = config('whatsapp.phone_number_id');
        $this->verifyToken = config('whatsapp.verify_token');
        $this->appSecret = config('whatsapp.app_secret');
        $this->enabled = (bool) config('whatsapp.enabled', false);
        $this->timeout = (int) config('whatsapp.timeout', 20);
        $this->chatbotService = $chatbotService;
    }

    public function verifyWebhook(string $mode, string $token, string $challenge): ?string
    {
        if (!$this->enabled) {
            return null;
        }

        if ($mode === 'subscribe' && $token === $this->verifyToken) {
            return $challenge;
        }

        return null;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function isConfigured(): bool
    {
        return $this->enabled
            && filled($this->accessToken)
            && filled($this->phoneNumberId)
            && filled($this->verifyToken);
    }

    public function validateSignature(?string $signatureHeader, string $payload): bool
    {
        // If app secret is not configured, skip signature validation.
        if (!filled($this->appSecret)) {
            return true;
        }

        if (!filled($signatureHeader)) {
            return false;
        }

        if (!Str::startsWith((string) $signatureHeader, 'sha256=')) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, (string) $this->appSecret);
        $actual = Str::after((string) $signatureHeader, 'sha256=');

        return hash_equals($expected, $actual);
    }

    public function handleIncomingMessage(array $data): void
    {
        if (!$this->isConfigured()) {
            Log::warning('WhatsApp incoming message ignored: service disabled or not configured.');
            return;
        }

        try {
            $messages = $this->extractMessages($data);
            if (empty($messages)) {
                return;
            }

            foreach ($messages as $message) {
                $from = (string) ($message['from'] ?? '');
                if ($from === '') {
                    continue;
                }

                $session = $this->getOrCreateSession($from);
                $metadata = $session->metadata ?? [];
                $incomingMessageId = (string) ($message['id'] ?? '');

                // Ignore duplicate webhook retries for the same inbound message.
                if (
                    $incomingMessageId !== ''
                    && ($metadata['last_inbound_message_id'] ?? null) === $incomingMessageId
                ) {
                    continue;
                }

                $messageText = $this->extractMessageText($message);
                if ($messageText === '') {
                    $this->sendMessage(
                        $from,
                        'Je traite uniquement les messages texte pour le moment. Envoyez votre demande en texte simple.'
                    );
                    $session->update([
                        'metadata' => array_merge($metadata, [
                            'last_inbound_message_id' => $incomingMessageId,
                            'last_inbound_type' => (string) ($message['type'] ?? 'unknown'),
                        ]),
                        'last_activity_at' => now(),
                    ]);
                    continue;
                }

                $response = $this->chatbotService->processMessage(
                    $messageText,
                    null,
                    'whatsapp',
                    $from
                );

                $this->sendMessage($from, (string) ($response['response'] ?? 'Je peux vous aider a trouver un produit.'));

                $products = is_array($response['products'] ?? null) ? $response['products'] : [];
                if (!empty($products)) {
                    $this->sendMessage($from, $this->formatProductsSuggestion($products));
                }

                $session->update([
                    'metadata' => array_merge($metadata, [
                        'last_inbound_message_id' => $incomingMessageId,
                        'last_inbound_type' => (string) ($message['type'] ?? 'text'),
                    ]),
                    'last_activity_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp message handling error: ' . $e->getMessage());
        }
    }

    public function sendMessage(string $to, string $message): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('WhatsApp send skipped: service disabled or not configured.');
            return false;
        }

        $message = trim($message);
        if ($message === '') {
            return false;
        }

        // Meta limits text body length. Keep a safe limit.
        if (mb_strlen($message) > 4096) {
            $message = mb_substr($message, 0, 4093) . '...';
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->timeout($this->timeout)->post($this->messagesEndpoint(), [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ]);

            if (!$response->successful()) {
                Log::warning('WhatsApp send message API failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp send message error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendTemplateMessage(string $to, string $templateName, array $parameters = []): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('WhatsApp template send skipped: service disabled or not configured.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->timeout($this->timeout)->post($this->messagesEndpoint(), [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => 'fr',
                    ],
                    'components' => $parameters,
                ],
            ]);

            if (!$response->successful()) {
                Log::warning('WhatsApp send template API failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp send template error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendOrderConfirmation(string $phoneNumber, array $orderData): bool
    {
        $message = "Confirmation de commande Sakha\n\n";
        $message .= 'Numero: #' . ($orderData['order_number'] ?? 'N/A') . "\n";
        $message .= 'Total: ' . ($orderData['total'] ?? 'N/A') . " FCFA\n\n";
        $message .= 'Merci pour votre commande. Nous vous informerons de son avancement.';

        return $this->sendMessage($phoneNumber, $message);
    }

    public function sendOrderUpdate(string $phoneNumber, string $status, string $trackingNumber): bool
    {
        $statusMessages = [
            'processing' => 'Votre commande est en cours de traitement.',
            'shipped' => 'Votre commande a ete expediee.',
            'delivered' => 'Votre commande a ete livree.',
        ];

        $message = $statusMessages[$status] ?? 'Mise a jour de votre commande.';
        $message .= "\n\nNumero de suivi: {$trackingNumber}";

        return $this->sendMessage($phoneNumber, $message);
    }

    protected function messagesEndpoint(): string
    {
        return "{$this->apiUrl}/{$this->apiVersion}/{$this->phoneNumberId}/messages";
    }

    protected function getOrCreateSession(string $phoneNumber): WhatsappSession
    {
        $session = WhatsappSession::where('phone_number', $phoneNumber)->first();

        if (!$session) {
            $session = WhatsappSession::create([
                'phone_number' => $phoneNumber,
                'status' => 'active',
                'last_activity_at' => now(),
            ]);
        }

        return $session;
    }

    protected function extractMessages(array $data): array
    {
        $messages = [];

        foreach (($data['entry'] ?? []) as $entry) {
            foreach (($entry['changes'] ?? []) as $change) {
                $valueMessages = $change['value']['messages'] ?? [];
                if (is_array($valueMessages)) {
                    foreach ($valueMessages as $message) {
                        if (is_array($message)) {
                            $messages[] = $message;
                        }
                    }
                }
            }
        }

        return $messages;
    }

    protected function extractMessageText(array $message): string
    {
        $type = (string) ($message['type'] ?? 'text');

        if ($type === 'text') {
            return trim((string) ($message['text']['body'] ?? ''));
        }

        if ($type === 'button') {
            return trim((string) ($message['button']['text'] ?? ''));
        }

        if ($type === 'interactive') {
            $interactive = $message['interactive'] ?? [];
            $interactiveType = (string) ($interactive['type'] ?? '');

            if ($interactiveType === 'button_reply') {
                return trim((string) (($interactive['button_reply']['title'] ?? '') ?: ($interactive['button_reply']['id'] ?? '')));
            }

            if ($interactiveType === 'list_reply') {
                return trim((string) (($interactive['list_reply']['title'] ?? '') ?: ($interactive['list_reply']['id'] ?? '')));
            }
        }

        if (in_array($type, ['image', 'video', 'document'], true)) {
            return trim((string) ($message[$type]['caption'] ?? ''));
        }

        return '';
    }

    protected function formatProductsSuggestion(array $products): string
    {
        $lines = ['Suggestions produits:'];

        foreach (array_slice($products, 0, 3) as $product) {
            $name = (string) ($product['name'] ?? 'Produit');
            $price = (string) ($product['price'] ?? '');
            $url = (string) ($product['url'] ?? '');

            $line = "- {$name}";
            if ($price !== '') {
                $line .= " | {$price}";
            }
            if ($url !== '') {
                $line .= " | {$url}";
            }

            $lines[] = $line;
        }

        return implode("\n", $lines);
    }
}
