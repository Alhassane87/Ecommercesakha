<div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
    <h1 class="mb-1 font-bold text-2xl">Bienvenue sur {{ config('app.name', 'Sakha') }} — votre boutique locale</h1>
    <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">Trouvez des produits de qualité, commandez sans créer de compte, et suivez votre livraison facilement.</p>

    <div class="flex flex-col mb-4 lg:mb-6">
        <div class="flex items-center gap-4 py-2">
            <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]"></span>
            <span>Parcourez la boutique et ajoutez au panier en quelques clics.</span>
        </div>
        <div class="flex items-center gap-4 py-2">
            <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]"></span>
            <span>Paiements locaux : Wave, Orange Money, carte ou liquide.</span>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
        <div class="p-3 bg-white/50 rounded shadow-sm">
            <h3 class="font-semibold">Acheter sans compte</h3>
            <p class="text-xs text-gray-600">Ajoutez au panier et payez — vous recevrez un lien pour suivre votre commande.</p>
        </div>
        <div class="p-3 bg-white/50 rounded shadow-sm">
            <h3 class="font-semibold">Paiements locaux</h3>
            <p class="text-xs text-gray-600">Wave, Orange Money, paiement par carte ou en liquide à la livraison.</p>
        </div>
    </div>
    
</div>

<div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
    <div class="p-6 lg:p-10 text-center">
        <div class="mx-auto w-32 h-32 rounded-full bg-white flex items-center justify-center shadow-md">
            <x-application-logo class="w-16 h-16" />
        </div>
        <p class="mt-4 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">Boutique — sélection de produits</p>
    </div>

    @if (!empty($products) && $products->count())
        <div class="p-4 grid grid-cols-2 gap-2">
            @foreach($products as $p)
                <a href="{{ route('product.show', ['slug' => $p->slug ?? $p->id]) }}" data-touch-zoom class="block bg-white rounded p-2 shadow-sm">
                    @if ($p->images && $p->images->first())
                        @php
                            $welcomeCardImage = Storage::url($p->images->first()->path);
                        @endphp
                        <div class="relative h-24 rounded overflow-hidden bg-gray-100">
                            <div class="absolute inset-0 bg-cover bg-center scale-105 blur-sm opacity-35" style="background-image: url('{{ $welcomeCardImage }}');"></div>
                            <img src="{{ $welcomeCardImage }}" alt="{{ $p->name }}" class="relative w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-full h-24 bg-gray-100 flex items-center justify-center rounded text-xs">Image</div>
                    @endif
                    <div class="mt-2 text-xs font-medium text-[#1b1b18]">{{ Illuminate\Support\Str::limit($p->name, 30) }}</div>
                    <div class="text-sm text-[#706f6c]">{{ number_format($p->price ?? 0, 2) }} {{ config('app.currency', 'EUR') }}</div>
                </a>
            @endforeach
        </div>
    @else
        <div class="p-4 text-center text-xs text-[#706f6c]">Aucun produit disponible pour le moment</div>
    @endif

</div>
