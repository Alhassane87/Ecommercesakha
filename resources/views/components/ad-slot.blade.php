@php
    $isHomePlacement = in_array($placement, ['home_hero', 'home_mid'], true);
    $isHomeHeroPlacement = $placement === 'home_hero';
    $isHomeMidPlacement = $placement === 'home_mid';
    $wrapperClasses = $isHomePlacement
        ? 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-6 md:my-8'
        : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-8';
    $listClasses = $isHomeMidPlacement ? 'grid gap-6 lg:grid-cols-2' : 'space-y-5';
    $homeCardRadius = $isHomeHeroPlacement ? 'rounded-[2rem]' : 'rounded-[1.75rem]';
    $homeStageClasses = $isHomeHeroPlacement
        ? 'h-[360px] sm:h-[470px] lg:h-[640px] p-2 sm:p-3 lg:p-4'
        : 'h-[320px] sm:h-[390px] lg:h-[520px] p-2 sm:p-3 lg:p-4';
    $homeImageClasses = 'h-full w-auto max-w-full object-contain';
    $homeFooterLayout = $isHomeHeroPlacement
        ? 'lg:flex-row lg:items-center lg:justify-between'
        : 'sm:flex-row sm:items-center sm:justify-between';
    $homeTitleClasses = $isHomeHeroPlacement ? 'text-xl sm:text-2xl lg:text-[1.85rem]' : 'text-lg sm:text-xl lg:text-[1.45rem]';
@endphp

@if($campaigns->isNotEmpty())
    <div class="{{ $wrapperClasses }}" data-ad-slot="{{ $placement }}">
        <div class="{{ $listClasses }}">
            @foreach($campaigns as $campaign)
                @php
                    $imageUrls = $campaign->galleryImagePaths()
                        ->map(fn (string $path) => \Illuminate\Support\Facades\Storage::url($path))
                        ->values();
                    $imageUrl = $imageUrls->first();
                    $hasGallery = $imageUrls->count() > 1;
                    $hasLink = !empty($campaign->target_url);
                    $href = $hasLink ? route('ads.click', $campaign) : null;
                    $ctaLabel = $campaign->button_text ?: ($hasLink ? "Voir l'offre" : 'Annonce en vedette');
                @endphp

                @if($isHomePlacement)
                    @if($hasLink)
                        <a href="{{ $href }}"
                           @if($campaign->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                           class="group relative block overflow-hidden {{ $homeCardRadius }} border border-slate-200/70 dark:border-slate-700/70 shadow-[0_20px_55px_rgba(15,23,42,0.16)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_28px_75px_rgba(15,23,42,0.2)] focus:outline-none focus-visible:ring-2 focus-visible:ring-white/80 focus-visible:ring-offset-4 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950"
                           style="background: {{ $campaign->background_color ?? '#0f172a' }}; color: {{ $campaign->text_color ?? '#ffffff' }};">
                            <div class="relative {{ $homeStageClasses }}">
                                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0.06),rgba(15,23,42,0.12))]"></div>
                                <div class="relative flex h-full w-full items-center justify-center overflow-hidden rounded-[1.5rem] bg-white shadow-[0_18px_40px_rgba(15,23,42,0.18)]">
                                    @if($imageUrl)
                                        <div class="absolute left-4 top-4 z-20 inline-flex items-center gap-2 rounded-full bg-slate-950/82 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.22em] text-white shadow-lg backdrop-blur-sm">
                                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                            Publicite
                                        </div>
                                        @if($hasGallery)
                                            <div class="absolute right-4 top-4 z-20 rounded-full bg-slate-950/78 px-3 py-1 text-xs font-bold text-white shadow-lg backdrop-blur-sm">
                                                <span data-ad-gallery-index>1</span>/{{ $imageUrls->count() }}
                                            </div>
                                            <div class="relative z-10 h-full w-full" data-ad-gallery data-interval="4200">
                                                @foreach($imageUrls as $galleryUrl)
                                                    <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-500 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}"
                                                         data-ad-gallery-slide>
                                                        <img src="{{ $galleryUrl }}"
                                                             alt="{{ $campaign->title }}"
                                                             class="w-full object-contain {{ $homeImageClasses }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <img src="{{ $imageUrl }}"
                                                 alt="{{ $campaign->title }}"
                                                 class="relative z-10 w-full object-contain {{ $homeImageClasses }}">
                                        @endif
                                    @else
                                        <div class="relative z-10 flex h-full w-full flex-col items-center justify-center rounded-[1.5rem] border border-dashed border-slate-300 text-center text-slate-500">
                                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                                                <i class="fas fa-image text-2xl opacity-75"></i>
                                            </div>
                                            <p class="mt-4 text-sm font-semibold uppercase tracking-[0.18em]">Image a ajouter</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-white/12 bg-slate-950/16 px-4 py-3 backdrop-blur-sm sm:px-5 sm:py-4">
                                <div class="flex flex-col gap-3 {{ $homeFooterLayout }}">
                                    <div class="min-w-0">
                                        <h3 class="font-black leading-tight {{ $homeTitleClasses }}">
                                            {{ $campaign->title }}
                                        </h3>

                                        @if(!empty($campaign->description))
                                            <p class="mt-2 max-w-3xl text-sm leading-relaxed opacity-90 sm:text-[0.95rem]">
                                                {{ $campaign->description }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2 lg:flex-nowrap">
                                        @if($campaign->open_in_new_tab)
                                            <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] opacity-85 backdrop-blur-sm">
                                                Nouvel onglet
                                            </span>
                                        @endif

                                        <span class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-900 shadow-lg shadow-slate-950/15">
                                            {{ $ctaLabel }}
                                            <i class="fas fa-arrow-right text-xs opacity-70"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="group relative block overflow-hidden {{ $homeCardRadius }} border border-slate-200/70 dark:border-slate-700/70 shadow-[0_20px_55px_rgba(15,23,42,0.16)]"
                             style="background: {{ $campaign->background_color ?? '#0f172a' }}; color: {{ $campaign->text_color ?? '#ffffff' }};">
                            <div class="relative {{ $homeStageClasses }}">
                                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0.06),rgba(15,23,42,0.12))]"></div>
                                <div class="relative flex h-full w-full items-center justify-center overflow-hidden rounded-[1.5rem] bg-white shadow-[0_18px_40px_rgba(15,23,42,0.18)]">
                                    @if($imageUrl)
                                        <div class="absolute left-4 top-4 z-20 inline-flex items-center gap-2 rounded-full bg-slate-950/82 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.22em] text-white shadow-lg backdrop-blur-sm">
                                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                            Publicite
                                        </div>
                                        @if($hasGallery)
                                            <div class="absolute right-4 top-4 z-20 rounded-full bg-slate-950/78 px-3 py-1 text-xs font-bold text-white shadow-lg backdrop-blur-sm">
                                                <span data-ad-gallery-index>1</span>/{{ $imageUrls->count() }}
                                            </div>
                                            <div class="relative z-10 h-full w-full" data-ad-gallery data-interval="4200">
                                                @foreach($imageUrls as $galleryUrl)
                                                    <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-500 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}"
                                                         data-ad-gallery-slide>
                                                        <img src="{{ $galleryUrl }}"
                                                             alt="{{ $campaign->title }}"
                                                             class="w-full object-contain {{ $homeImageClasses }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <img src="{{ $imageUrl }}"
                                                 alt="{{ $campaign->title }}"
                                                 class="relative z-10 w-full object-contain {{ $homeImageClasses }}">
                                        @endif
                                    @else
                                        <div class="relative z-10 flex h-full w-full flex-col items-center justify-center rounded-[1.5rem] border border-dashed border-slate-300 text-center text-slate-500">
                                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                                                <i class="fas fa-image text-2xl opacity-75"></i>
                                            </div>
                                            <p class="mt-4 text-sm font-semibold uppercase tracking-[0.18em]">Image a ajouter</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-white/12 bg-slate-950/16 px-4 py-3 backdrop-blur-sm sm:px-5 sm:py-4">
                                <div class="flex flex-col gap-3 {{ $homeFooterLayout }}">
                                    <div class="min-w-0">
                                        <h3 class="font-black leading-tight {{ $homeTitleClasses }}">
                                            {{ $campaign->title }}
                                        </h3>

                                        @if(!empty($campaign->description))
                                            <p class="mt-2 max-w-3xl text-sm leading-relaxed opacity-90 sm:text-[0.95rem]">
                                                {{ $campaign->description }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2 lg:flex-nowrap">
                                        <span class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-900 shadow-lg shadow-slate-950/15">
                                            {{ $ctaLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    @if($hasLink)
                        <a href="{{ $href }}"
                           @if($campaign->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                           class="block overflow-hidden rounded-3xl border border-slate-200/60 dark:border-slate-700/60 shadow-lg transition duration-300 hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-white/80 focus-visible:ring-offset-4 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950"
                           style="background: {{ $campaign->background_color ?? '#0f172a' }}; color: {{ $campaign->text_color ?? '#ffffff' }};">
                            <div class="relative overflow-hidden p-5 md:p-7">
                                <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(255,255,255,0.12),transparent_42%,rgba(15,23,42,0.14))]"></div>
                                <div class="relative grid items-center gap-4 md:grid-cols-[1fr_220px] md:gap-6">
                                    <div>
                                        <div class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.22em] backdrop-blur-sm">
                                            Publicite
                                        </div>
                                        <h3 class="mt-3 text-xl font-black leading-tight md:text-2xl">{{ $campaign->title }}</h3>
                                        @if(!empty($campaign->description))
                                            <p class="mt-2 text-sm md:text-base opacity-90">{{ $campaign->description }}</p>
                                        @endif

                                        <span class="mt-4 inline-flex items-center gap-2 rounded-xl bg-white/95 px-4 py-2 text-sm font-bold text-slate-900 shadow-lg shadow-slate-950/15">
                                            {{ $ctaLabel }}
                                            <i class="fas fa-arrow-right text-xs opacity-70"></i>
                                        </span>
                                    </div>

                                    @if($imageUrl)
                                        <div class="relative flex h-40 w-full items-center justify-center overflow-hidden rounded-[1.4rem] border border-white/20 bg-white/10 p-4">
                                            @if($hasGallery)
                                                <div class="absolute right-3 top-3 z-20 rounded-full bg-slate-950/78 px-2.5 py-1 text-[11px] font-bold text-white shadow-lg backdrop-blur-sm">
                                                    <span data-ad-gallery-index>1</span>/{{ $imageUrls->count() }}
                                                </div>
                                                <div class="relative z-10 h-full w-full" data-ad-gallery data-interval="4200">
                                                    @foreach($imageUrls as $galleryUrl)
                                                        <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-500 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}"
                                                             data-ad-gallery-slide>
                                                            <img src="{{ $galleryUrl }}"
                                                                 alt="{{ $campaign->title }}"
                                                                 class="max-h-full w-full object-contain">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="absolute inset-0 bg-center bg-no-repeat bg-contain opacity-15 scale-105" style="background-image: url('{{ $imageUrl }}');"></div>
                                                <img src="{{ $imageUrl }}"
                                                     alt="{{ $campaign->title }}"
                                                     class="relative z-10 max-h-full w-full object-contain">
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="overflow-hidden rounded-3xl border border-slate-200/60 dark:border-slate-700/60 shadow-lg"
                             style="background: {{ $campaign->background_color ?? '#0f172a' }}; color: {{ $campaign->text_color ?? '#ffffff' }};">
                            <div class="relative overflow-hidden p-5 md:p-7">
                                <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(255,255,255,0.12),transparent_42%,rgba(15,23,42,0.14))]"></div>
                                <div class="relative grid items-center gap-4 md:grid-cols-[1fr_220px] md:gap-6">
                                    <div>
                                        <div class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.22em] backdrop-blur-sm">
                                            Publicite
                                        </div>
                                        <h3 class="mt-3 text-xl font-black leading-tight md:text-2xl">{{ $campaign->title }}</h3>
                                        @if(!empty($campaign->description))
                                            <p class="mt-2 text-sm md:text-base opacity-90">{{ $campaign->description }}</p>
                                        @endif

                                        <span class="mt-4 inline-flex items-center rounded-xl bg-white/95 px-4 py-2 text-sm font-bold text-slate-900 shadow-lg shadow-slate-950/15">
                                            {{ $ctaLabel }}
                                        </span>
                                    </div>

                                    @if($imageUrl)
                                        <div class="relative flex h-40 w-full items-center justify-center overflow-hidden rounded-[1.4rem] border border-white/20 bg-white/10 p-4">
                                            @if($hasGallery)
                                                <div class="absolute right-3 top-3 z-20 rounded-full bg-slate-950/78 px-2.5 py-1 text-[11px] font-bold text-white shadow-lg backdrop-blur-sm">
                                                    <span data-ad-gallery-index>1</span>/{{ $imageUrls->count() }}
                                                </div>
                                                <div class="relative z-10 h-full w-full" data-ad-gallery data-interval="4200">
                                                    @foreach($imageUrls as $galleryUrl)
                                                        <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-500 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}"
                                                             data-ad-gallery-slide>
                                                            <img src="{{ $galleryUrl }}"
                                                                 alt="{{ $campaign->title }}"
                                                                 class="max-h-full w-full object-contain">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="absolute inset-0 bg-center bg-no-repeat bg-contain opacity-15 scale-105" style="background-image: url('{{ $imageUrl }}');"></div>
                                                <img src="{{ $imageUrl }}"
                                                     alt="{{ $campaign->title }}"
                                                     class="relative z-10 max-h-full w-full object-contain">
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    </div>
@endif

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-ad-gallery]').forEach((gallery) => {
                    const slides = Array.from(gallery.querySelectorAll('[data-ad-gallery-slide]'));
                    const counter = gallery.parentElement?.querySelector('[data-ad-gallery-index]');
                    const interval = Number(gallery.dataset.interval || 4200);

                    if (slides.length <= 1) {
                        return;
                    }

                    let currentIndex = 0;
                    let timerId = null;

                    const render = () => {
                        slides.forEach((slide, index) => {
                            slide.classList.toggle('opacity-100', index === currentIndex);
                            slide.classList.toggle('opacity-0', index !== currentIndex);
                        });

                        if (counter) {
                            counter.textContent = String(currentIndex + 1);
                        }
                    };

                    const start = () => {
                        if (timerId !== null) {
                            return;
                        }

                        timerId = window.setInterval(() => {
                            currentIndex = (currentIndex + 1) % slides.length;
                            render();
                        }, interval);
                    };

                    const stop = () => {
                        if (timerId === null) {
                            return;
                        }

                        window.clearInterval(timerId);
                        timerId = null;
                    };

                    render();
                    start();

                    gallery.addEventListener('mouseenter', stop);
                    gallery.addEventListener('mouseleave', start);
                });
            });
        </script>
    @endpush
@endonce
