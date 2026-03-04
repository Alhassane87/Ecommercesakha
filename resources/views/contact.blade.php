@extends('layouts.app')

@section('content')
@php
    $platformEmail = (string) config('platform.contact_email', 'sakha2228@gmail.com');
    $platformPhone = (string) config('platform.contact_phone', '762080009');
    $statusParam = (string) request()->query('contact_status', '');

    $successMsg = session('success');
    $errorMsg = session('error');
    $warningMsg = session('warning');

    if (!$successMsg && $statusParam === 'success') {
        $successMsg = 'Votre message a ete envoye avec succes.';
    }
    if (!$warningMsg && $statusParam === 'warning') {
        $warningMsg = 'Votre message a ete enregistre, mais l envoi email est temporairement indisponible.';
    }
@endphp

<div class="text-white py-12 px-6 rounded-lg shadow-lg mb-8" style="background: var(--primary-gradient)">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center space-x-4">
            <i class="fas fa-envelope text-white text-4xl"></i>
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">Nous Contacter</h1>
                <p class="text-white/90 text-lg">Une question ? Envoyez-nous un message, nous vous repondrons au plus vite</p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto py-12 px-4">
    <div class="bg-white dark:bg-[#161615] rounded-lg shadow p-8">
        <h1 class="text-2xl font-bold mb-4">Contactez-nous</h1>
        <p class="mb-4 text-gray-700 dark:text-gray-300">Pour toute question, envoyez-nous un email ou utilisez le formulaire ci-dessous.</p>

        @if($successMsg)
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $successMsg }}
            </div>
        @endif

        @if($errorMsg)
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errorMsg }}
            </div>
        @endif

        @if($warningMsg)
            <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                {{ $warningMsg }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold">Coordonnees</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Email : <a href="mailto:{{ $platformEmail }}" class="text-primary-600">{{ $platformEmail }}</a>
                </p>
                <p class="text-sm text-gray-600">Telephone : {{ $platformPhone }}</p>
            </div>

            <div>
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer')
    <div class="fixed bottom-6 right-6 z-50">
        @php
            $waRaw = (string) config('platform.contact_whatsapp', '762080009');
            $waDigits = preg_replace('/\D+/', '', $waRaw) ?? '';
            if (strlen($waDigits) === 9) {
                $waDigits = '221' . $waDigits;
            }
            $waUrl = 'https://wa.me/' . $waDigits;
        @endphp
        <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-full shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.82 11.82 0 0012 .5C6.21.5 1.5 5.21 1.5 11c0 1.95.51 3.86 1.48 5.56L.5 23.5l6.86-2.04A11.47 11.47 0 0012 22.5c5.79 0 10.5-4.71 10.5-10.5 0-1.92-.51-3.72-1.98-5.02zM12 20a9 9 0 01-4.62-1.19l-.33-.19-4.08 1.22 1.22-3.99-.2-.33A9 9 0 1112 20zm4.2-6.3l-1.2-.34c-.32-.09-.66-.04-.9.13-.35.25-.76.42-1.2.5-.28.05-.55-.01-.76-.16l-.49-.36c-.22-.16-.5-.25-.79-.25-.27 0-.54.05-.78.15l-.1.04-.08.03c-.25.09-.45.25-.6.45-.19.26-.36.52-.53.8-.13.2-.26.4-.4.6l-.26.39c-.07.1-.11.21-.12.33l-.02.2c-.03.4.09.78.33 1.1.18.25.44.46.75.6.45.19.95.25 1.43.16.38-.07.73-.28 1.02-.58.16-.17.3-.36.41-.56l.26-.47c.11-.21.33-.36.57-.38.28-.03.54.04.73.2l1.55 1.28c.28.24.64.35.99.31.31-.04.6-.19.82-.44.24-.27.4-.61.45-.98.06-.41-.02-.82-.26-1.17l-.64-1.02c-.25-.39-.64-.66-1.08-.76z"/></svg>
            <span>Contact Whatsapp</span>
        </a>
    </div>
@endpush
