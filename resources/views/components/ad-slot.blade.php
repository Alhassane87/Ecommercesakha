@if($campaigns->isNotEmpty())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-8" data-ad-slot="{{ $placement }}">
        <div class="space-y-4">
            @foreach($campaigns as $campaign)
                @php
                    $imageUrl = $campaign->image_path ? \Illuminate\Support\Facades\Storage::url($campaign->image_path) : null;
                    $hasLink = !empty($campaign->target_url);
                    $href = $hasLink ? route('ads.click', $campaign) : null;
                @endphp

                @if($hasLink)
                    <a href="{{ $href }}"
                       @if($campaign->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                       class="block rounded-3xl border border-slate-200/60 dark:border-slate-700/60 shadow-lg overflow-hidden hover:scale-[1.01] transition-transform"
                       style="background: {{ $campaign->background_color ?? '#0f172a' }}; color: {{ $campaign->text_color ?? '#ffffff' }};">
                        <div class="p-5 md:p-7">
                            <div class="grid md:grid-cols-[1fr_auto] gap-4 md:gap-6 items-center">
                                <div>
                                    <h3 class="text-xl md:text-2xl font-black leading-tight">{{ $campaign->title }}</h3>
                                    @if(!empty($campaign->description))
                                        <p class="mt-2 text-sm md:text-base opacity-90">{{ $campaign->description }}</p>
                                    @endif

                                    <span class="mt-4 inline-flex items-center rounded-xl bg-white/90 text-slate-900 px-4 py-2 font-bold text-sm">
                                        {{ $campaign->button_text ?: "Voir l'offre" }}
                                    </span>
                                </div>

                                @if($imageUrl)
                                    <div class="w-full md:w-48">
                                        <img src="{{ $imageUrl }}" alt="{{ $campaign->title }}" class="w-full h-32 md:h-28 object-cover rounded-2xl border border-white/30">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @else
                    <div class="rounded-3xl border border-slate-200/60 dark:border-slate-700/60 shadow-lg overflow-hidden"
                         style="background: {{ $campaign->background_color ?? '#0f172a' }}; color: {{ $campaign->text_color ?? '#ffffff' }};">
                        <div class="p-5 md:p-7">
                            <div class="grid md:grid-cols-[1fr_auto] gap-4 md:gap-6 items-center">
                                <div>
                                    <h3 class="text-xl md:text-2xl font-black leading-tight">{{ $campaign->title }}</h3>
                                    @if(!empty($campaign->description))
                                        <p class="mt-2 text-sm md:text-base opacity-90">{{ $campaign->description }}</p>
                                    @endif
                                </div>

                                @if($imageUrl)
                                    <div class="w-full md:w-48">
                                        <img src="{{ $imageUrl }}" alt="{{ $campaign->title }}" class="w-full h-32 md:h-28 object-cover rounded-2xl border border-white/30">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
