<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotService
{
    protected ?string $apiKey;
    protected ?string $apiUrl;
    protected string $provider;

    public function __construct()
    {
        $this->provider = (string) config('chatbot.provider', 'gemini');
        $this->apiUrl = null;
        $this->apiKey = null;

        if ($this->provider === 'openai') {
            $this->apiKey = (string) config('chatbot.openai_key');
            $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
        } else {
            $this->apiKey = (string) config('chatbot.gemini_key');
            $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
        }
    }

    public function processMessage(string $message, ?int $userId = null, string $channel = 'web', ?string $externalId = null): array
    {
        $conversation = $this->getOrCreateConversation($userId, $channel, $externalId);

        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $message,
            'is_ai' => false,
        ]);

        $context = $this->buildContext($conversation);
        $aiResponse = $this->getAIResponse($message, $context);
        $suggestedProducts = $this->buildSuggestedProducts($message);

        if (!empty($suggestedProducts)) {
            $aiResponse .= "\n\nJe vous ai aussi affiche des suggestions produit ci-dessous.";
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $aiResponse,
            'metadata' => [
                'products' => $suggestedProducts,
            ],
            'is_ai' => true,
        ]);

        return [
            'response' => $aiResponse,
            'conversation_id' => $conversation->id,
            'products' => $suggestedProducts,
        ];
    }

    protected function getOrCreateConversation(?int $userId, string $channel, ?string $externalId): Conversation
    {
        $query = Conversation::where('status', 'active')->where('channel', $channel);

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($externalId) {
            $query->where('external_id', $externalId);
        }

        $conversation = $query->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $userId,
                'channel' => $channel,
                'external_id' => $externalId,
                'status' => 'active',
                'context' => [
                    'products_discussed' => [],
                    'intent' => null,
                ],
            ]);
        }

        return $conversation;
    }

    protected function buildContext(Conversation $conversation): array
    {
        $messages = $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->limit((int) config('chatbot.max_history_messages', 10))
            ->get()
            ->reverse()
            ->values();

        return [
            'system' => $this->getSystemPrompt(),
            'history' => $messages->map(function ($msg) {
                return [
                    'role' => $msg->role,
                    'content' => $msg->content,
                ];
            })->toArray(),
        ];
    }

    protected function getSystemPrompt(): string
    {
        $categories = $this->getActiveCategories();
        $categoryList = $categories->isNotEmpty()
            ? $categories->map(function ($category) {
                return sprintf(
                    '- %s (%d produits actifs)',
                    (string) $category->name,
                    (int) $category->active_products_count
                );
            })->join("\n")
            : '- Aucune categorie active';

        $products = Product::with('category')->where('is_active', true)->orderByDesc('updated_at')->get();
        $productList = $products->map(function ($p) {
            return sprintf(
                '- %s (%s) - Prix: %s FCFA - Stock: %d',
                (string) $p->name,
                (string) ($p->category->name ?? 'N/A'),
                number_format((float) $p->price, 0, ',', ' '),
                (int) ($p->stock ?? 0)
            );
        })->join("\n");

        return <<<PROMPT
Tu es un assistant virtuel pour Sakha, une boutique e-commerce.
Ton role est d'aider les clients avec leurs questions sur les produits, commandes et navigation.

CATEGORIES DISPONIBLES:
{$categoryList}

PRODUITS DISPONIBLES:
{$productList}

INSTRUCTIONS:
1. Reponds en francais.
2. Sois clair, utile et professionnel.
3. Propose des produits actifs en stock quand c'est pertinent.
4. Oriente vers le suivi de commande /track si besoin.
5. Oriente vers la boutique /shop pour parcourir les articles.
6. Si information inconnue, propose de contacter le support.
7. Utilise les categories et produits ci-dessus comme source de verite. Ces donnees se mettent a jour automatiquement.
PROMPT;
    }

    protected function getAIResponse(string $message, array $context): string
    {
        if (!config('chatbot.enabled', true)) {
            return $this->getLocalResponse($message);
        }

        if ($this->apiKey === null || trim($this->apiKey) === '') {
            Log::warning('Chatbot API key missing. Falling back to local response.', [
                'provider' => $this->provider,
            ]);
            return $this->getLocalResponse($message);
        }

        try {
            if ($this->provider === 'openai') {
                return $this->getOpenAIResponse($message, $context);
            }

            return $this->getGeminiResponse($message, $context);
        } catch (\Throwable $e) {
            Log::error('Chatbot AI Error: ' . $e->getMessage(), [
                'provider' => $this->provider,
            ]);
            return $this->getLocalResponse($message);
        }
    }

    protected function getOpenAIResponse(string $message, array $context): string
    {
        $messages = [
            ['role' => 'system', 'content' => $context['system']],
        ];

        foreach ($context['history'] as $msg) {
            $messages[] = $msg;
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post((string) $this->apiUrl, [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => 500,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return (string) ($data['choices'][0]['message']['content'] ?? 'Je peux vous aider a choisir un produit dans la boutique.');
        }

        throw new \RuntimeException('OpenAI API error: ' . $response->body());
    }

    protected function getGeminiResponse(string $message, array $context): string
    {
        $fullPrompt = $context['system'] . "\n\n";

        foreach ($context['history'] as $msg) {
            $fullPrompt .= strtoupper((string) $msg['role']) . ': ' . (string) $msg['content'] . "\n";
        }

        $fullPrompt .= 'USER: ' . $message . "\nASSISTANT:";

        $response = Http::timeout(30)->post((string) $this->apiUrl . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $fullPrompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 500,
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return (string) ($data['candidates'][0]['content']['parts'][0]['text'] ?? 'Je peux vous aider a trouver des produits dans la boutique.');
        }

        throw new \RuntimeException('Gemini API error: ' . $response->body());
    }

    protected function getLocalResponse(string $message): string
    {
        $normalized = $this->normalizeText($message);

        if ($normalized === '') {
            return 'Je suis la pour vous aider. Que recherchez-vous aujourd hui ?';
        }

        if (preg_match('/\b(bonjour|salut|bonsoir|hello)\b/u', $normalized)) {
            return 'Bonjour. Je peux vous aider a trouver des produits, suivre une commande (/track) ou acceder a la boutique (/shop).';
        }

        if (preg_match('/\b(suivi|track|commande|order)\b/u', $normalized)) {
            return 'Pour suivre votre commande, allez sur /track et entrez votre numero de commande.';
        }

        if (preg_match('/\b(livraison|delai|expedition)\b/u', $normalized)) {
            return 'Les delais dependent des produits et de la zone. Vous pouvez finaliser votre commande puis suivre son statut via /track.';
        }

        if (preg_match('/\b(paiement|payer|carte|mobile money)\b/u', $normalized)) {
            return 'Les moyens de paiement sont proposes a l etape checkout. Je peux aussi vous guider vers les produits avant paiement.';
        }

        if (preg_match('/\b(categorie|categories|catalogue|rayon)\b/u', $normalized)) {
            $categories = $this->getActiveCategories();
            if ($categories->isEmpty()) {
                return 'Aucune categorie active pour le moment. Vous pouvez quand meme parcourir /shop.';
            }

            $categoryLines = $categories->take(8)->map(function ($category) {
                return sprintf(
                    '- %s (%d produits)',
                    (string) $category->name,
                    (int) $category->active_products_count
                );
            })->implode("\n");

            return "Voici nos categories principales:\n{$categoryLines}\n\nAllez sur /shop pour voir tous les produits.";
        }

        $keywords = $this->extractKeywords($normalized);
        $products = $this->searchProductsByKeywords($keywords);

        if ($products->isNotEmpty()) {
            $lines = $products->map(function ($product) {
                return sprintf(
                    '- %s (%s FCFA)',
                    (string) $product->name,
                    number_format((float) $product->price, 0, ',', ' ')
                );
            })->implode("\n");

            return "Voici quelques produits qui peuvent vous interesser:\n{$lines}\n\nVous pouvez les consulter dans /shop.";
        }

        $supportEmail = (string) config('platform.contact_email', 'sakha2228@gmail.com');
        $supportPhone = (string) config('platform.contact_phone', '762080009');

        return "Je n ai pas assez d informations pour repondre precisement.\nEssayez avec le nom du produit ou la categorie, sinon contactez le support: {$supportEmail} / {$supportPhone}.";
    }

    protected function extractKeywords(string $text): array
    {
        $clean = $this->normalizeText($text);
        $parts = preg_split('/\s+/u', trim($clean)) ?: [];

        $stopWords = [
            'je', 'tu', 'il', 'elle', 'nous', 'vous', 'ils', 'elles',
            'le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'et', 'ou', 'a', 'au',
            'pour', 'avec', 'sur', 'dans', 'par', 'mon', 'ma', 'mes',
            'cherche', 'recherche', 'veux', 'voudrais', 'bonjour', 'salut', 'svp', 'stp',
        ];

        $typoMap = [
            'samrphone' => 'smartphone',
            'smarphone' => 'smartphone',
            'smartpone' => 'smartphone',
            'telepone' => 'telephone',
            'telphone' => 'telephone',
            'telephonee' => 'telephone',
            'cellulairee' => 'cellulaire',
        ];

        $keywords = [];
        foreach ($parts as $part) {
            $word = trim($part);
            if ($word === '') {
                continue;
            }

            $word = $typoMap[$word] ?? $word;
            $word = $this->singularizeKeyword($word);

            if (strlen($word) < 3) {
                continue;
            }
            if (in_array($word, $stopWords, true)) {
                continue;
            }
            $keywords[] = $word;
        }

        return array_slice($this->expandKeywordSynonyms(array_values(array_unique($keywords))), 0, 12);
    }

    protected function searchProductsByKeywords(array $keywords)
    {
        if (empty($keywords)) {
            return collect();
        }

        $query = Product::where('is_active', true)->with(['category', 'images']);
        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $kw) {
                $q->orWhere('name', 'like', '%' . $kw . '%')
                    ->orWhere('description', 'like', '%' . $kw . '%')
                    ->orWhere('slug', 'like', '%' . $kw . '%')
                    ->orWhere('sku', 'like', '%' . $kw . '%')
                    ->orWhereHas('category', function ($cq) use ($kw) {
                        $cq->where('name', 'like', '%' . $kw . '%');
                    });
            }
        });

        return $query->where('stock', '>', 0)->orderByDesc('created_at')->limit(4)->get();
    }

    protected function expandKeywordSynonyms(array $keywords): array
    {
        $synonymsMap = [
            'telephone' => ['smartphone', 'portable', 'mobile', 'phone', 'cellulaire', 'tel'],
            'smartphone' => ['telephone', 'portable', 'mobile', 'phone', 'tel', 'samrphone'],
            'mobile' => ['smartphone', 'telephone', 'phone'],
            'portable' => ['smartphone', 'telephone', 'mobile'],
            'samrphone' => ['smartphone', 'telephone', 'mobile'],
            'ordinateur' => ['pc', 'laptop', 'informatique'],
            'pc' => ['ordinateur', 'laptop', 'informatique'],
            'laptop' => ['ordinateur', 'pc'],
            'casque' => ['headphone', 'audio', 'ecouteur', 'ecouteurs'],
            'ecouteur' => ['casque', 'audio'],
            'tv' => ['televiseur', 'television', 'ecran'],
            'televiseur' => ['tv', 'television', 'ecran'],
            'basket' => ['chaussure', 'sneaker'],
            'chaussure' => ['basket', 'sneaker'],
        ];

        $expanded = $keywords;
        foreach ($keywords as $keyword) {
            if (isset($synonymsMap[$keyword])) {
                foreach ($synonymsMap[$keyword] as $synonym) {
                    $expanded[] = $synonym;
                }
            }
        }

        return array_values(array_unique($expanded));
    }

    protected function singularizeKeyword(string $word): string
    {
        if (strlen($word) <= 4) {
            return $word;
        }

        if (Str::endsWith($word, 'es') && strlen($word) > 5) {
            return substr($word, 0, -2);
        }

        if (Str::endsWith($word, 's') && strlen($word) > 4) {
            return substr($word, 0, -1);
        }

        return $word;
    }

    protected function normalizeText(string $text): string
    {
        $ascii = Str::of($text)->ascii()->lower()->toString();
        $clean = preg_replace('/[^a-z0-9\s]/', ' ', $ascii) ?? $ascii;
        return trim(preg_replace('/\s+/', ' ', $clean) ?? $clean);
    }

    protected function buildSuggestedProducts(string $message): array
    {
        if (!$this->shouldSuggestProducts($message)) {
            return [];
        }

        $keywords = $this->extractKeywords($message);
        $products = empty($keywords)
            ? collect()
            : $this->searchProductsByKeywords($keywords);

        if ($products->isEmpty()) {
            $products = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->with(['category', 'images'])
                ->orderByDesc('created_at')
                ->limit(3)
                ->get();
        }

        return $products->map(function (Product $product) {
            return [
                'id' => $product->id,
                'name' => (string) $product->name,
                'slug' => (string) $product->slug,
                'category' => (string) ($product->category->name ?? ''),
                'price' => number_format((float) $product->getFinalPrice(), 0, ',', ' ') . ' FCFA',
                'image_url' => $product->getMainImageUrl(),
                'url' => url('/product/' . $product->slug),
            ];
        })->values()->toArray();
    }

    protected function shouldSuggestProducts(string $message): bool
    {
        $normalized = $this->normalizeText($message);

        if ($normalized === '') {
            return true;
        }

        return !preg_match('/\b(track|suivi|commande|order|livraison|expedition|paiement|payer|support|sav|retour|remboursement)\b/', $normalized);
    }

    protected function getActiveCategories()
    {
        return Category::query()
            ->where('is_active', true)
            ->withCount(['products as active_products_count' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();
    }

    public function getConversationHistory(int $conversationId): array
    {
        $conversation = Conversation::with('messages')->findOrFail($conversationId);

        return [
            'conversation' => $conversation,
            'messages' => $conversation->messages()->orderBy('created_at', 'asc')->get(),
        ];
    }
}
