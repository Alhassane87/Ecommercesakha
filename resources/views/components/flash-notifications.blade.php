@php
    $flashItems = [];

    if (session('success')) {
        $flashItems[] = ['type' => 'success', 'message' => session('success')];
    }

    if (session('status')) {
        $flashItems[] = ['type' => 'success', 'message' => session('status')];
    }

    if (session('warning')) {
        $flashItems[] = ['type' => 'warning', 'message' => session('warning')];
    }

    if (session('error')) {
        $flashItems[] = ['type' => 'error', 'message' => session('error')];
    }

    if ($errors->any()) {
        $count = $errors->count();
        $first = $errors->first();
        $suffix = $count > 1 ? ' (+'.($count - 1).' autres)' : '';
        $flashItems[] = ['type' => 'error', 'message' => 'Le formulaire contient des erreurs: '.$first.$suffix];
    }

    $flashStyles = [
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-700 dark:text-emerald-200',
        'error' => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-700 dark:text-red-200',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-900/30 dark:border-amber-700 dark:text-amber-200',
    ];

    $flashIcons = [
        'success' => 'fa-circle-check',
        'error' => 'fa-circle-exclamation',
        'warning' => 'fa-triangle-exclamation',
    ];
@endphp

@if(!empty($flashItems))
    <div id="flash-stack" class="fixed top-20 right-4 z-[80] w-[calc(100%-2rem)] max-w-md space-y-3">
        @foreach($flashItems as $item)
            @php
                $type = $item['type'];
                $style = $flashStyles[$type] ?? $flashStyles['success'];
                $icon = $flashIcons[$type] ?? $flashIcons['success'];
            @endphp
            <div data-flash-item class="rounded-xl border px-4 py-3 shadow-xl backdrop-blur transition-all duration-300 {{ $style }}">
                <div class="flex items-start gap-3">
                    <i class="fas {{ $icon }} mt-0.5"></i>
                    <p class="text-sm font-semibold leading-5 flex-1">{{ $item['message'] }}</p>
                    <button type="button"
                            data-flash-close
                            class="text-current/70 hover:text-current transition"
                            aria-label="Fermer la notification">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = document.querySelectorAll('[data-flash-item]');
            items.forEach((item, index) => {
                const closeBtn = item.querySelector('[data-flash-close]');

                const closeItem = () => {
                    item.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => item.remove(), 250);
                };

                if (closeBtn) {
                    closeBtn.addEventListener('click', closeItem);
                }

                setTimeout(closeItem, 5500 + (index * 350));
            });
        });
    </script>
@endif
