<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        $result = $this->chatbotService->processMessage(
            $request->message,
            $userId,
            'web',
            $sessionId
        );

        return response()->json([
            'success' => true,
            'response' => $result['response'],
            'conversation_id' => $result['conversation_id'],
            'products' => $result['products'] ?? [],
        ]);
    }

    public function getHistory(Request $request)
    {
        $conversationId = $request->get('conversation_id');

        if (!$conversationId) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation ID required',
            ], 400);
        }

        $history = $this->chatbotService->getConversationHistory($conversationId);

        return response()->json([
            'success' => true,
            'messages' => $history['messages'],
        ]);
    }
}
