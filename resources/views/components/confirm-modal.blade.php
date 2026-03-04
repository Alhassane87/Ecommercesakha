<div id="sakha-confirm-overlay" class="sakha-confirm-overlay" hidden>
    <div class="sakha-confirm-backdrop" data-role="backdrop"></div>

    <div class="sakha-confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="sakha-confirm-title">
        <div class="sakha-confirm-icon-wrap">
            <div class="sakha-confirm-icon">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
        </div>

        <div class="sakha-confirm-content">
            <h3 id="sakha-confirm-title" data-confirm-title>Confirmation</h3>
            <p data-confirm-message>Etes-vous sur de vouloir continuer ?</p>
        </div>

        <div class="sakha-confirm-actions">
            <button type="button" class="sakha-confirm-btn sakha-confirm-btn-cancel" data-confirm-cancel>
                Annuler
            </button>
            <button type="button" class="sakha-confirm-btn sakha-confirm-btn-ok" data-confirm-ok>
                Confirmer
            </button>
        </div>
    </div>
</div>

<style>
    .sakha-confirm-lock {
        overflow: hidden;
    }

    .sakha-confirm-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: grid;
        place-items: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.18s ease, visibility 0.18s ease;
    }

    .sakha-confirm-overlay.is-open {
        opacity: 1;
        visibility: visible;
    }

    .sakha-confirm-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(12, 16, 23, 0.58);
        backdrop-filter: blur(2px);
    }

    .sakha-confirm-dialog {
        position: relative;
        width: min(92vw, 500px);
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 18px 70px rgba(15, 23, 42, 0.35);
        border: 1px solid rgba(15, 23, 42, 0.08);
        padding: 24px;
        transform: translateY(12px) scale(0.985);
        transition: transform 0.18s ease;
    }

    .sakha-confirm-overlay.is-open .sakha-confirm-dialog {
        transform: translateY(0) scale(1);
    }

    .sakha-confirm-icon-wrap {
        margin-bottom: 12px;
    }

    .sakha-confirm-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f97316, #ef4444);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .sakha-confirm-content h3 {
        margin: 0;
        font-size: 22px;
        line-height: 1.2;
        color: #0f172a;
    }

    .sakha-confirm-content p {
        margin: 8px 0 0;
        font-size: 15px;
        line-height: 1.5;
        color: #475569;
        word-break: break-word;
    }

    .sakha-confirm-actions {
        margin-top: 22px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .sakha-confirm-btn {
        border: 0;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s ease, filter 0.15s ease;
    }

    .sakha-confirm-btn:hover {
        transform: translateY(-1px);
        filter: brightness(1.04);
    }

    .sakha-confirm-btn:focus-visible {
        outline: 2px solid #2563eb;
        outline-offset: 2px;
    }

    .sakha-confirm-btn-cancel {
        background: #e2e8f0;
        color: #0f172a;
    }

    .sakha-confirm-btn-ok {
        color: #fff;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
    }

    .sakha-confirm-btn-ok.variant-primary {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
    }

    .sakha-confirm-btn-ok.variant-success {
        background: linear-gradient(135deg, #047857, #059669);
    }

    @media (max-width: 520px) {
        .sakha-confirm-dialog {
            padding: 18px;
            border-radius: 16px;
        }

        .sakha-confirm-content h3 {
            font-size: 19px;
        }

        .sakha-confirm-actions {
            flex-direction: column-reverse;
        }

        .sakha-confirm-btn {
            width: 100%;
        }
    }
</style>

<script>
    (function () {
        if (window.AppConfirm) {
            return;
        }

        const overlay = document.getElementById('sakha-confirm-overlay');
        if (!overlay) {
            return;
        }

        const titleEl = overlay.querySelector('[data-confirm-title]');
        const messageEl = overlay.querySelector('[data-confirm-message]');
        const cancelBtn = overlay.querySelector('[data-confirm-cancel]');
        const okBtn = overlay.querySelector('[data-confirm-ok]');
        const backdrop = overlay.querySelector('[data-role="backdrop"]');

        let resolver = null;

        const defaults = {
            title: 'Confirmation',
            message: 'Etes-vous sur de vouloir continuer ?',
            confirmText: 'Confirmer',
            cancelText: 'Annuler',
            variant: 'danger',
        };

        function setVariant(variant) {
            okBtn.classList.remove('variant-primary', 'variant-success');
            if (variant === 'primary') {
                okBtn.classList.add('variant-primary');
            } else if (variant === 'success') {
                okBtn.classList.add('variant-success');
            }
        }

        function open(options) {
            titleEl.textContent = options.title;
            messageEl.textContent = options.message;
            okBtn.textContent = options.confirmText;
            cancelBtn.textContent = options.cancelText;
            setVariant(options.variant);

            overlay.hidden = false;
            requestAnimationFrame(function () {
                overlay.classList.add('is-open');
            });

            document.body.classList.add('sakha-confirm-lock');
            cancelBtn.focus();
        }

        function close(result) {
            overlay.classList.remove('is-open');
            document.body.classList.remove('sakha-confirm-lock');

            setTimeout(function () {
                overlay.hidden = true;
            }, 170);

            if (resolver) {
                resolver(result);
                resolver = null;
            }
        }

        window.AppConfirm = function (message, options) {
            const config = Object.assign({}, defaults, options || {});
            config.message = message || config.message;

            return new Promise(function (resolve) {
                resolver = resolve;
                open(config);
            });
        };

        okBtn.addEventListener('click', function () {
            close(true);
        });

        cancelBtn.addEventListener('click', function () {
            close(false);
        });

        backdrop.addEventListener('click', function () {
            close(false);
        });

        document.addEventListener('keydown', function (event) {
            if (overlay.hidden) {
                return;
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                close(false);
            }
        });

        document.addEventListener('submit', async function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            if (!form.hasAttribute('data-confirm')) {
                return;
            }

            if (form.dataset.confirmSubmitted === '1') {
                return;
            }

            event.preventDefault();

            const ok = await window.AppConfirm(form.getAttribute('data-confirm'), {
                title: form.getAttribute('data-confirm-title') || defaults.title,
                confirmText: form.getAttribute('data-confirm-ok') || defaults.confirmText,
                cancelText: form.getAttribute('data-confirm-cancel') || defaults.cancelText,
                variant: form.getAttribute('data-confirm-variant') || defaults.variant,
            });

            if (!ok) {
                return;
            }

            form.dataset.confirmSubmitted = '1';
            form.submit();
        });
    })();
</script>
