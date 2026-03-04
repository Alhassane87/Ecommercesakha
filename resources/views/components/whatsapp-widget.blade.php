@php
    $waRaw = (string) config('platform.contact_whatsapp', '');
    $waDigits = preg_replace('/\D+/', '', $waRaw) ?? '';

    // If local number (ex: 9 digits in Senegal), prepend country code.
    if ($waDigits !== '' && strlen($waDigits) === 9 && strpos($waDigits, '221') !== 0) {
        $waDigits = '221' . $waDigits;
    }

    $waText = rawurlencode('Bonjour Sakha, je veux des informations sur vos produits.');
    $waLink = $waDigits !== '' ? "https://wa.me/{$waDigits}?text={$waText}" : null;
@endphp

@if($waLink)
    <a
        href="{{ $waLink }}"
        target="_blank"
        rel="noopener noreferrer"
        class="whatsapp-widget fixed bottom-6 left-6 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-green-500 text-white shadow-lg hover:bg-green-600 transition"
        title="Discuter sur WhatsApp"
        aria-label="Discuter sur WhatsApp"
    >
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>
@endif
