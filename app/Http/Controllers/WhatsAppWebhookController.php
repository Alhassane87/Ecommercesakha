<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsAppWebhookController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function verify(Request $request)
    {
        // Meta may send keys as hub.mode / hub.verify_token / hub.challenge.
        $mode = (string) ($request->query('hub_mode') ?: $request->query('hub.mode', ''));
        $token = (string) ($request->query('hub_verify_token') ?: $request->query('hub.verify_token', ''));
        $challenge = (string) ($request->query('hub_challenge') ?: $request->query('hub.challenge', ''));

        $result = $this->whatsappService->verifyWebhook($mode, $token, $challenge);

        if ($result !== null) {
            return response($result, 200);
        }

        return response('Verification failed', 403);
    }

    public function webhook(Request $request)
    {
        try {
            if (!$this->whatsappService->isEnabled()) {
                return response()->json(['status' => 'disabled'], 200);
            }

            $payload = (string) $request->getContent();
            $signature = (string) $request->header('X-Hub-Signature-256', '');

            if (!$this->whatsappService->validateSignature($signature, $payload)) {
                Log::warning('WhatsApp webhook rejected: invalid signature.', [
                    'signature_prefix' => Str::substr($signature, 0, 20),
                ]);

                return response()->json(['status' => 'invalid_signature'], 403);
            }

            $data = json_decode($payload, true);
            if (!is_array($data)) {
                return response()->json(['status' => 'invalid_payload'], 400);
            }

            if (($data['object'] ?? null) !== 'whatsapp_business_account') {
                return response()->json(['status' => 'ignored'], 200);
            }

            $this->whatsappService->handleIncomingMessage($data);

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('WhatsApp webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}
