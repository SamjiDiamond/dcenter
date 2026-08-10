{{-- ============================================================
     Toast notifications — reusable, global
     ------------------------------------------------------------
     Fixed top-right, slides in, dismissible, auto-hides.
     Server-side session flashes are converted automatically:
         error/code → danger · warning → warning · success/message → success
     A view can add its own toast anywhere in the page:
         <template class="toast-flash" data-type="success" data-message="Done!"></template>
     JS API (available after this partial loads):
         window.showToast(message, type, durationMs)
         window.showToastFromResponse(jsonResponse)   // toast from an AJAX response
         window.showToastAjaxError(xhr, fallbackMsg)  // toast from an AJAX error
     ============================================================ --}}
<div id="toast-container" aria-live="polite" aria-atomic="false"></div>

@php
    $__toastFlashMap = [
        'error'   => 'danger',
        'code'    => 'danger',
        'warning' => 'warning',
        'success' => 'success',
        'message' => 'success',
    ];
    $__toastQueue = [];

    // Structured helper flash: redirect()->withToast('Saved!', 'success')
    if (session()->has('toast')
        && is_array($__toastFlash = session('toast'))
        && ! empty($__toastFlash['message'])) {
        $__toastQueue[] = [
            'type'    => in_array($__toastFlash['type'] ?? 'success', ['success', 'danger', 'warning', 'info'], true)
                ? $__toastFlash['type']
                : 'success',
            'message' => (string) $__toastFlash['message'],
        ];
    }

    foreach ($__toastFlashMap as $__toastKey => $__toastType) {
        if (session()->has($__toastKey) && ($__toastValue = session($__toastKey))) {
            $__toastQueue[] = ['type' => $__toastType, 'message' => (string) $__toastValue];
        }
    }

    // Laravel validation errors (redirect()->back()->withErrors(...) / failed
    // FormRequests) surface as danger toasts so users see what went wrong.
    if (isset($errors) && $errors->any()) {
        foreach ($errors->all() as $__toastError) {
            $__toastQueue[] = ['type' => 'danger', 'message' => (string) $__toastError];
        }
    }
@endphp

@foreach ($__toastQueue as $__toast)
    <template class="toast-flash" data-type="{{ $__toast['type'] }}" data-message="{{ $__toast['message'] }}"></template>
@endforeach

@php
    // Keep the including view's scope clean.
    unset($__toastFlashMap, $__toastQueue, $__toastKey, $__toastType, $__toastValue, $__toastFlash, $__toast, $__toastError);
@endphp

<style>
    #toast-container {
        position: fixed;
        top: 16px;
        right: 16px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        align-items: flex-end; /* toasts size to their content, hugging the right edge */
        gap: 10px;
        max-width: calc(100vw - 32px);
        max-height: calc(100vh - 32px);
        overflow-x: hidden; /* never allow a toast to push a horizontal scrollbar */
        overflow-y: auto;
        pointer-events: none;
    }

    .toast-item {
        position: relative;
        pointer-events: auto;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        /* Fluid sizing: width follows the message exactly, capped so long text wraps. */
        width: fit-content;
        max-width: min(340px, calc(100vw - 32px));
        background: #ffffff;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-left: 4px solid var(--toast-accent, #3b82f6);
        border-radius: 10px;
        padding: 10px 12px 12px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.16);
        font-size: 13px;
        line-height: 1.4;
        overflow: hidden;
        transform: translateX(calc(100% + 24px));
        opacity: 0;
        transition: transform .3s ease, opacity .3s ease;
        will-change: transform, opacity;
    }

    .toast-item.toast-visible {
        transform: translateX(0);
        opacity: 1;
    }

    .toast-item.toast-hiding {
        transform: translateX(calc(100% + 24px));
        opacity: 0;
    }

    .toast-success { --toast-accent: #10b981; }
    .toast-danger  { --toast-accent: #ef4444; }
    .toast-warning { --toast-accent: #f59e0b; }
    .toast-info    { --toast-accent: #3b82f6; }

    .toast-icon {
        flex: 0 0 auto;
        width: 18px;
        height: 18px;
        margin-top: 1px;
        color: var(--toast-accent, #3b82f6);
    }

    .toast-icon svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    .toast-body {
        flex: 1 1 auto;
        min-width: 0; /* let flex children shrink so long text wraps instead of overflowing */
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .toast-close {
        flex: 0 0 auto;
        border: 0;
        background: transparent;
        color: #9ca3af;
        font-size: 18px;
        line-height: 1;
        padding: 0 2px;
        margin: -2px -2px 0 0;
        cursor: pointer;
        border-radius: 4px;
        transition: color .15s ease;
    }

    .toast-close:hover {
        color: #111827;
    }

    .toast-progress {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 3px;
        background: var(--toast-accent, #3b82f6);
        opacity: .8;
        transform-origin: left center;
        animation-name: toast-progress;
        animation-timing-function: linear;
        animation-fill-mode: forwards;
    }

    @keyframes toast-progress {
        from { transform: scaleX(1); }
        to   { transform: scaleX(0); }
    }

    @media (prefers-reduced-motion: reduce) {
        .toast-item {
            transition: none;
            transform: none;
            opacity: 1;
        }
        .toast-progress {
            animation: none;
        }
    }
</style>

<script>
    (function () {
        'use strict';

        var container = document.getElementById('toast-container');
        if (!container) return;

        var DEFAULT_DURATION = 5000;

        var ICONS = {
            success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            danger:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
            warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
        };

        var TYPES = ['success', 'danger', 'warning', 'info'];

        function createToast(message, type, duration) {
            if (TYPES.indexOf(type) === -1) type = 'info';
            duration = (typeof duration === 'number' && duration > 0) ? duration : DEFAULT_DURATION;

            var el = document.createElement('div');
            el.className = 'toast-item toast-' + type;
            el.setAttribute('role', 'alert');

            var icon = document.createElement('div');
            icon.className = 'toast-icon';
            icon.innerHTML = ICONS[type];

            var body = document.createElement('div');
            body.className = 'toast-body';
            body.textContent = message;

            var close = document.createElement('button');
            close.type = 'button';
            close.className = 'toast-close';
            close.setAttribute('aria-label', 'Close');
            close.innerHTML = '&times;';

            var progress = document.createElement('span');
            progress.className = 'toast-progress';
            progress.style.animationDuration = duration + 'ms';

            el.appendChild(icon);
            el.appendChild(body);
            el.appendChild(close);
            el.appendChild(progress);

            container.appendChild(el);

            requestAnimationFrame(function () {
                el.classList.add('toast-visible');
            });

            var startTime = Date.now();
            var remaining = duration;
            var hidden = false;
            var timer = setTimeout(hide, duration);

            close.addEventListener('click', function () {
                clearTimeout(timer);
                hide();
            });

            // Pausing the auto-hide while the user hovers keeps it readable.
            el.addEventListener('mouseenter', function () {
                clearTimeout(timer);
                remaining = Math.max(0, duration - (Date.now() - startTime));
                progress.style.animationPlayState = 'paused';
            });

            el.addEventListener('mouseleave', function () {
                progress.style.animationPlayState = 'running';
                timer = setTimeout(hide, remaining);
            });

            function remove() {
                if (el.parentNode) el.parentNode.removeChild(el);
            }

            function hide() {
                if (hidden) return;
                hidden = true;
                clearTimeout(timer);
                el.classList.remove('toast-visible');
                el.classList.add('toast-hiding');
                el.addEventListener('transitionend', remove);
                // Fallback for reduced-motion environments where no transition fires.
                setTimeout(remove, 400);
            }
        }

        function init() {
            var templates = document.querySelectorAll('template.toast-flash');
            for (var i = 0; i < templates.length; i++) {
                var tpl = templates[i];
                var message = tpl.getAttribute('data-message') || '';
                var type = tpl.getAttribute('data-type') || 'info';
                if (tpl.parentNode) tpl.parentNode.removeChild(tpl);
                if (message) createToast(message, type);
            }
        }

        window.showToast = createToast;

        // ----------------------------------------------------------------
        // AJAX helpers — toast feedback from a JSON response, no reload.
        // Usage from any page script:
        //   success: function (res) { showToastFromResponse(res); }
        //   error:   function (xhr) { showToastAjaxError(xhr, 'Fallback msg'); }
        //
        // Understood response shapes (server returns status 1|0/success 1|0):
        //   { toast: { message, type } }            → explicit (preferred)
        //   { status: 1, message: '...' }            → success
        //   { status: 0, message: '...' }            → danger
        //   { success: 1|0, message: '...' }         → success | danger
        //   { status: 2, message: '...' }            → info (e.g. 2FA challenge)
        //   { errors: { field: ['msg'] } }           → danger (Laravel validation)
        // ----------------------------------------------------------------
        // Infer success/failure from the response's status convention.
        function inferResponseType(data) {
            // 2FA-style challenge / intermediate state → informational.
            if (data.status === 2) return 'info';

            var hasFlag = data.status !== undefined || data.success !== undefined;
            var ok = !hasFlag || data.status === 1 || data.status === true || data.success === 1 || data.success === true;

            return ok ? 'success' : 'danger';
        }

        function toastTypeFromResponse(data) {
            if (!data || typeof data !== 'object') return null;

            // Explicit structured payload wins.
            if (data.toast && typeof data.toast === 'object' && data.toast.message) {
                return {
                    message: data.toast.message,
                    type: TYPES.indexOf(data.toast.type) !== -1 ? data.toast.type : inferResponseType(data)
                };
            }

            var message = data.message;

            // Laravel validation error bag → surface the first message.
            if (!message && data.errors && typeof data.errors === 'object') {
                for (var key in data.errors) {
                    if (Object.prototype.hasOwnProperty.call(data.errors, key)) {
                        var err = data.errors[key];
                        message = Array.isArray(err) ? err[0] : err;
                        break;
                    }
                }
            }

            if (!message) return null;

            return { message: message, type: inferResponseType(data) };
        }

        window.showToastFromResponse = function (data) {
            var toast = toastTypeFromResponse(data);
            if (toast) createToast(toast.message, toast.type);
        };

        window.showToastAjaxError = function (xhr, fallback) {
            var message = fallback || 'Something went wrong. Please try again.';
            var type = 'danger';
            if (xhr && xhr.responseJSON) {
                var toast = toastTypeFromResponse(xhr.responseJSON);
                if (toast) {
                    message = toast.message;
                    type = toast.type;
                }
            }
            createToast(message, type);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
