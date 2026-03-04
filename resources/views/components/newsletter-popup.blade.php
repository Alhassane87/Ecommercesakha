<div id="newsletter-popup"
     data-newsletter-subscribed="{{ session('newsletter_subscribed') ? '1' : '0' }}"
     class="hidden fixed inset-0 z-[90]">
    <div data-newsletter-overlay class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative z-10 flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl border border-white/30 bg-white/95 p-7 shadow-2xl backdrop-blur-xl dark:bg-gray-900/95 dark:border-gray-700/60">
            <button type="button"
                    data-newsletter-close
                    class="absolute right-5 top-5 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200"
                    aria-label="Fermer">
                <i class="fas fa-xmark text-lg"></i>
            </button>

            <div data-newsletter-default-content>
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl text-white mb-5"
                     style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%);">
                    <i class="fas fa-envelope-open-text text-xl"></i>
                </div>

                <h3 class="text-2xl font-black text-slate-900 dark:text-white">Restez informe</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                    Recevez nos offres exclusives et nouvelles collections directement par email.
                </p>

                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="mt-6 flex flex-col sm:flex-row gap-3" data-newsletter-form>
                    @csrf
                    <input type="email"
                           name="email"
                           required
                           autocomplete="email"
                           placeholder="Votre email"
                           class="flex-1 rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <button type="submit"
                            class="rounded-xl px-5 py-3 font-bold text-white shadow-lg transition hover:scale-[1.01]"
                            style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%);">
                        S'abonner
                    </button>
                </form>

                <button type="button"
                        data-newsletter-close
                        class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500 hover:text-slate-700 dark:hover:text-slate-200">
                    Plus tard
                </button>
            </div>

            <div data-newsletter-success-content class="hidden text-center">
                <div class="mx-auto mb-5 w-28 h-28 rounded-2xl bg-gradient-to-br from-cyan-100 to-emerald-100 dark:from-cyan-900/40 dark:to-emerald-900/40 p-3 shadow-md">
                    <img src="{{ asset('sakha.png') }}"
                         alt="Abonnement confirme"
                         class="w-full h-full rounded-xl object-contain">
                </div>

                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300 mb-4">
                    <i class="fas fa-circle-check text-xl"></i>
                </div>

                <h3 class="text-2xl font-black text-slate-900 dark:text-white">Abonnement confirme &#128522;</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                    Merci, vous etes maintenant inscrit a la newsletter Sakha.
                </p>

                <button type="button"
                        data-newsletter-close
                        class="mt-6 inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-bold text-white shadow-lg transition hover:scale-[1.01]"
                        style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 45%, #10b981 100%);">
                    Continuer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const popup = document.getElementById('newsletter-popup');
        if (!popup) {
            return;
        }

        const isGuest = {{ auth()->check() ? 'false' : 'true' }};
        const isWelcomePage = {{ request()->routeIs('home') ? 'true' : 'false' }} || window.location.pathname === '/';
        const isServerSubscribed = popup.dataset.newsletterSubscribed === '1';
        const defaultContent = popup.querySelector('[data-newsletter-default-content]');
        const successContent = popup.querySelector('[data-newsletter-success-content]');
        const newsletterForm = popup.querySelector('[data-newsletter-form]');
        const popupDelayMs = 5000;
        const popupSeenKey = 'sakha-newsletter-popup-seen-v3';

        const overlay = popup.querySelector('[data-newsletter-overlay]');
        const closeButtons = popup.querySelectorAll('[data-newsletter-close]');

        const readStorage = (store, key) => {
            try {
                return store.getItem(key);
            } catch (error) {
                return null;
            }
        };

        const writeStorage = (store, key, value) => {
            try {
                store.setItem(key, value);
            } catch (error) {
                // Storage may be unavailable in private mode or blocked by browser settings.
            }
        };

        const hasSeenPopup = readStorage(window.sessionStorage, popupSeenKey) === '1';

        const hidePopup = () => {
            popup.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        const showPopup = (mode = 'default') => {
            if (defaultContent && successContent) {
                defaultContent.classList.toggle('hidden', mode === 'success');
                successContent.classList.toggle('hidden', mode !== 'success');
            }

            popup.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        closeButtons.forEach((button) => {
            button.addEventListener('click', hidePopup);
        });

        if (overlay) {
            overlay.addEventListener('click', hidePopup);
        }

        if (newsletterForm) {
            newsletterForm.addEventListener('submit', () => {
                writeStorage(window.sessionStorage, popupSeenKey, '1');
            });
        }

        if (isServerSubscribed) {
            writeStorage(window.sessionStorage, popupSeenKey, '1');
            showPopup('success');
            return;
        }

        // Show popup once per visit, only on home page.
        if (!isGuest || !isWelcomePage || hasSeenPopup) {
            return;
        }

        window.setTimeout(() => {
            writeStorage(window.sessionStorage, popupSeenKey, '1');
            showPopup('default');
        }, popupDelayMs);
    });
</script>

