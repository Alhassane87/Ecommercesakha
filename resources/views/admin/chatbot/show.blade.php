@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.chatbot.index') }}" class="text-blue-600 hover:underline">&larr; Retour aux conversations</a>
        <h1 class="text-3xl font-bold text-gray-800 mt-2">Conversation #{{ $conversation->id }}</h1>
        <p class="text-gray-600">
            {{ $conversation->user ? $conversation->user->name : 'Invité' }} - 
            {{ $conversation->channel }} - 
            {{ $conversation->created_at->format('d/m/Y H:i') }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="space-y-4 max-h-[600px] overflow-y-auto">
            @forelse($conversation->messages as $message)
                <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="{{ $message->role === 'user' 
                        ? 'bg-blue-600 text-white rounded-lg rounded-br-none px-4 py-3 max-w-[70%]' 
                        : 'bg-gray-100 text-gray-800 rounded-lg rounded-bl-none px-4 py-3 max-w-[70%]' }}">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-sm">
                                {{ $message->role === 'user' ? 'Utilisateur' : 'Assistant IA' }}
                            </span>
                            @if($message->is_ai)
                                <span class="bg-green-500 text-white text-xs px-2 py-0.5 rounded">IA</span>
                            @endif
                        </div>
                        <p class="text-sm whitespace-pre-wrap">{{ $message->content }}</p>
                        <p class="text-xs opacity-70 mt-2">
                            {{ $message->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">Aucun message dans cette conversation</p>
            @endforelse
        </div>
    </div>

    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="font-semibold mb-3">Informations de la conversation</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Canal:</span>
                <span class="font-medium ml-2">{{ ucfirst($conversation->channel) }}</span>
            </div>
            <div>
                <span class="text-gray-600">Statut:</span>
                <span class="font-medium ml-2">{{ ucfirst($conversation->status) }}</span>
            </div>
            <div>
                <span class="text-gray-600">Messages totaux:</span>
                <span class="font-medium ml-2">{{ $conversation->messages->count() }}</span>
            </div>
            <div>
                <span class="text-gray-600">Messages IA:</span>
                <span class="font-medium ml-2">{{ $conversation->messages->where('is_ai', true)->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
