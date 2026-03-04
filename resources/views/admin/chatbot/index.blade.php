@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion Chatbot</h1>
        <a href="{{ route('admin.chatbot.stats') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Statistiques
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Conversations Web</h2>
            
            <div class="space-y-3">
                @forelse($conversations as $conversation)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.chatbot.show', $conversation->id) }}'">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">
                                    {{ $conversation->user ? $conversation->user->name : 'Invité' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $conversation->messages->count() }} messages
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Dernière activité: {{ $conversation->updated_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded {{ $conversation->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($conversation->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucune conversation web</p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $conversations->links() }}
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Sessions WhatsApp</h2>
            
            <div class="space-y-3">
                @forelse($whatsappSessions as $session)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-medium">{{ $session->phone_number }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Dernière activité: {{ $session->last_activity_at ? $session->last_activity_at->diffForHumans() : 'N/A' }}
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded {{ $session->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </div>

                        <form action="{{ route('admin.chatbot.whatsapp') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="phone_number" value="{{ $session->phone_number }}">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="message" 
                                    placeholder="Envoyer un message..."
                                    class="flex-1 border rounded px-3 py-1 text-sm"
                                    required
                                >
                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                    Envoyer
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucune session WhatsApp</p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $whatsappSessions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
