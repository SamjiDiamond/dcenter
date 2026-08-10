{{--
    Reusable confirmation modal. Include once in the layout.

    Usage (any <button> or <a>):
        <button type="submit"
                data-confirm
                data-confirm-title="Delete this account?"
                data-confirm-message="This will permanently remove everything."
                data-confirm-button="Delete"
                data-confirm-button-class="btn-danger"
                data-confirm-require-password="true">
            Delete
        </button>

    - For a <button type="submit"> inside a form: the form is submitted after confirmation.
    - For an <a href="..."> or data-confirm-url: the URL is followed (GET), or POSTed with the
      CSRF token and an optional _method override via data-confirm-method.
    - data-confirm-require-password shows a password field; its value is copied into the target
      form's [name="password"] input (or added as a hidden field).
    - Programmatic API: window.confirmModal({ title, message, button, buttonClass, requirePassword, form|url, method })

    NOTE: `message` is rendered as HTML (use only trusted/static content, or escape user data).
    NOTE: the URL-following path (data-confirm-url / links with POST/DELETE) requires the
          <meta name="csrf-token"> tag present in layouts/layout.blade.php.
--}}
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmModalMessage" class="mb-0"></p>
                <div id="confirmModalPasswordWrap" class="mt-3 d-none">
                    <label for="confirmModalPassword" class="form-label">Confirm your password</label>
                    <input type="password" id="confirmModalPassword" class="form-control"
                           autocomplete="current-password" placeholder="Enter your password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmModalConfirm">Yes, continue</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function ($) {
        'use strict';

        if (!$('#confirmModal').length) {
            return;
        }

        var $modal = $('#confirmModal');
        var $title = $('#confirmModalTitle');
        var $message = $('#confirmModalMessage');
        var $passwordWrap = $('#confirmModalPasswordWrap');
        var $password = $('#confirmModalPassword');
        var $confirmBtn = $('#confirmModalConfirm');
        var target = null;

        function openConfirm(options) {
            options = options || {};
            target = {
                form: options.form || null,
                url: options.url || null,
                method: options.method || 'GET'
            };

            $title.text(options.title || 'Are you sure?');
            $message.html(options.message || 'Are you sure you want to continue?');
            $confirmBtn.text(options.button || 'Yes, continue');
            $confirmBtn
                .removeClass('btn-primary btn-danger btn-warning btn-success btn-info')
                .addClass(options.buttonClass || 'btn-primary');

            var requirePassword = !!options.requirePassword;
            $passwordWrap.toggleClass('d-none', !requirePassword);
            $password.prop('required', requirePassword);
            $password.val('').removeClass('is-invalid');

            $modal.modal('show');

            if (requirePassword) {
                $modal.one('shown.bs.modal', function () {
                    $password.focus();
                });
            }
        }

        // Delegated so it also works for elements added later (e.g. AJAX-loaded feeds).
        $(document).on('click', '[data-confirm]', function (e) {
            e.preventDefault();

            var $el = $(this);
            var href = $el.attr('href');

            openConfirm({
                form: this.form || null,
                url: $el.data('confirm-url') || (href && href !== '#' ? href : null),
                method: $el.data('confirm-method') || (this.form ? (this.form.method || 'POST') : 'GET'),
                title: $el.data('confirm-title'),
                message: $el.data('confirm-message'),
                button: $el.data('confirm-button'),
                buttonClass: $el.data('confirm-button-class'),
                requirePassword: !!$el.data('confirm-require-password')
            });
        });

        $confirmBtn.on('click', function () {
            if (!target) {
                $modal.modal('hide');
                return;
            }

            var password = $password.val();
            var requirePassword = !$passwordWrap.hasClass('d-none');

            if (requirePassword && !password) {
                $password.addClass('is-invalid').focus();
                return;
            }

            $modal.modal('hide');

            // Submitting a form (the common case)
            if (target.form) {
                if (requirePassword) {
                    var pwInput = target.form.querySelector('[name="password"]');
                    if (pwInput) {
                        pwInput.value = password;
                    } else {
                        pwInput = document.createElement('input');
                        pwInput.type = 'hidden';
                        pwInput.name = 'password';
                        pwInput.value = password;
                        target.form.appendChild(pwInput);
                    }
                }
                target.form.submit();
                return;
            }

            // Following a URL (link-style confirmations)
            if (target.url) {
                if ((target.method || 'GET').toUpperCase() === 'GET') {
                    window.location.href = target.url;
                    return;
                }

                var form = document.createElement('form');
                form.method = 'POST';
                form.action = target.url;

                var meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) {
                    var token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = meta.content;
                    form.appendChild(token);
                }

                if (requirePassword) {
                    var pw = document.createElement('input');
                    pw.type = 'password';
                    pw.name = 'password';
                    pw.value = password;
                    form.appendChild(pw);
                }

                var method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = (target.method || 'POST').toUpperCase();
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }
        });

        $modal.on('hidden.bs.modal', function () {
            $password.val('').removeClass('is-invalid');
            target = null;
        });

        // Programmatic API
        window.confirmModal = openConfirm;
    })(jQuery);
</script>
