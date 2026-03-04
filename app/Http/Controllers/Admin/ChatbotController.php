<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\WhatsappSession;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function index()
    {
        $conversations = Conversation::with('user', 'messages')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        $whatsappSessions = WhatsappSession::orderBy('last_activity_at', 'desc')
            ->paginate(20);

        return view('admin.chatbot.index', compact('conversations', 'whatsappSessions'));
    }

    public function show($id)
    {
        $conversation = Conversation::with(['user', 'messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])->findOrFail($id);

        return view('admin.chatbot.show', compact('conversation'));
    }

    public function sendWhatsApp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        if (!$this->whatsappService->isConfigured()) {
            return back()->with('error', 'WhatsApp n est pas configure. Verifiez WHATSAPP_ENABLED, WHATSAPP_ACCESS_TOKEN et WHATSAPP_PHONE_NUMBER_ID.');
        }

        $success = $this->whatsappService->sendMessage(
            $request->phone_number,
            $request->message
        );

        if ($success) {
            return back()->with('success', 'Message WhatsApp envoyé avec succès');
        }

        return back()->with('error', 'Erreur lors de l\'envoi du message WhatsApp');
    }

    public function stats()
    {
        $totalConversations = Conversation::count();
        $activeConversations = Conversation::where('status', 'active')->count();
        $totalMessages = Message::count();
        $aiMessages = Message::where('is_ai', true)->count();
        $whatsappSessions = WhatsappSession::where('status', 'active')->count();

        $recentConversations = Conversation::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.chatbot.stats', compact(
            'totalConversations',
            'activeConversations',
            'totalMessages',
            'aiMessages',
            'whatsappSessions',
            'recentConversations'
        ));
    }
}
