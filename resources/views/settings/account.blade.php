@extends('layouts.layout')

@section('title', 'Account Settings')

@section('content')
    @if($user->deletion_scheduled_for)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div style="text-align: center">
                <i class="mdi mdi-alert-outline mr-1"></i>
                Your account is scheduled for deletion on
                <b>{{ $user->deletion_scheduled_for->format('jS F, Y') }}</b>. You can cancel this anytime before then.
            </div>
        </div>
    @endif

    <div class="row">
        <!-- ============ Profile card ============ -->
        <div class="col-xl-4">
            <div class="card m-b-30">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ $user->profile_photo_url }}"
                             class="rounded-circle img-thumbnail"
                             style="width:120px; height:120px; object-fit:cover;"
                             alt="{{ $user->first_name }} {{ $user->last_name }}">
                    </div>
                    <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    <p class="mb-3">
                        @if($twoFactorEnabled)
                            <span class="badge badge-success"><i class="mdi mdi-shield-check mr-1"></i>2FA Enabled</span>
                        @else
                            <span class="badge badge-secondary"><i class="mdi mdi-shield-outline mr-1"></i>2FA Off</span>
                        @endif
                    </p>

                    <hr>

                    <form method="POST" action="{{ route('account.settings.photo.upload') }}" enctype="multipart/form-data" class="mb-2">
                        @csrf
                        <div class="custom-file text-left mb-2">
                            <input type="file" class="custom-file-input" id="photoInput" name="image" accept="image/*" required>
                            <label class="custom-file-label" for="photoInput">Choose image…</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block waves-effect waves-light">
                            <i class="mdi mdi-upload mr-1"></i> Upload photo
                        </button>
                    </form>

                    @if($user->profile_photo_path)
                        <form method="POST" action="{{ route('account.settings.photo.remove') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-block waves-effect waves-light"
                                    data-confirm
                                    data-confirm-title="Remove profile photo?"
                                    data-confirm-message="Your profile photo will be removed from your account."
                                    data-confirm-button="Remove photo"
                                    data-confirm-button-class="btn-danger">
                                <i class="mdi mdi-delete-outline mr-1"></i> Remove photo
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card m-b-30 border-primary">
                <div class="card-body">
                    <h5 class="card-title font-16 mt-0"><i class="mdi mdi-wallet mr-1 text-primary"></i>Account</h5>
                    <p class="mb-1"><b>Customer ref:</b> <code>{{ $user->reference }}</code></p>
                    <p class="mb-1"><b>Company ID:</b> {{ $user->company_id }}</p>
                    <p class="mb-1"><b>Phone:</b> {{ $user->phoneno ?? '—' }}</p>
                    <p class="mb-0"><b>Member since:</b> {{ optional($user->created_at)->format('jS M, Y') ?? '—' }}</p>
                </div>
            </div>
        </div>
        <!-- ============ /Profile card ============ -->

        <div class="col-xl-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#profile-tab" role="tab">
                                <i class="mdi mdi-account-circle-outline mr-1"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#security-tab" role="tab">
                                <i class="mdi mdi-shield-account-outline mr-1"></i> Security
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#team-tab" role="tab">
                                <i class="mdi mdi-account-multiple-outline mr-1"></i> Team members
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- ============ Profile ============ -->
                        <div class="tab-pane active" id="profile-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Profile information</h4>
                                            <p class="text-muted font-14">
                                                Update your account's profile information and email address.
                                            </p>
                                            <form method="POST" action="{{ route('account.settings.profile.update') }}">
                                                @csrf
                                                <div class="form-row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>First name</label>
                                                        <input type="text" name="first_name" class="form-control"
                                                               value="{{ old('first_name', $user->first_name) }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Last name</label>
                                                        <input type="text" name="last_name" class="form-control"
                                                               value="{{ old('last_name', $user->last_name) }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Email address</label>
                                                        <input type="email" name="email" class="form-control"
                                                               value="{{ old('email', $user->email) }}" required>
                                                        @if($user->email_verified_at)
                                                            <small class="text-success"><i class="mdi mdi-check-circle mr-1"></i>Verified</small>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Phone number</label>
                                                        <input type="tel" name="phoneno" class="form-control"
                                                               value="{{ old('phoneno', $user->phoneno) }}" required>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                    <i class="mdi mdi-content-save-outline mr-1"></i> Save changes
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Account details</h4>
                                            <p class="text-muted font-14">Your customer reference and account information.</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1"><b>Customer reference:</b></p>
                                                    <p class="mb-2"><code>{{ $user->uuid }}</code></p>
                                                    <p class="mb-1"><b>Company:</b> {{ optional($user->company)->name ?? $user->company_id }}</p>
                                                    <p class="mb-1"><b>Account type:</b> {{ $user->account_type }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1"><b>Email:</b> {{ $user->email }}</p>
                                                    <p class="mb-1"><b>Phone:</b> {{ $user->phoneno ?? '—' }}</p>
                                                    <p class="mb-0"><b>Member since:</b> {{ optional($user->created_at)->format('jS M, Y') ?? '—' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============ /Profile ============ -->

                        <!-- ============ Security ============ -->
                        <div class="tab-pane" id="security-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Two-Factor Authentication</h4>
                                            <p class="text-muted font-14">
                                                Add an extra layer of security. When enabled, a one-time verification code
                                                is emailed to you whenever you sign in.
                                            </p>

                                            @if($twoFactorEnabled)
                                                <div class="alert alert-success py-2">
                                                    <i class="mdi mdi-shield-check mr-1"></i>
                                                    Two-factor authentication is currently <b>enabled</b>.
                                                </div>
                                                <form method="POST" action="{{ route('account.settings.two-factor.disable') }}" class="mt-3">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-warning waves-effect waves-light"
                                                            data-confirm
                                                            data-confirm-title="Turn off two-factor authentication?"
                                                            data-confirm-message="You will no longer receive a verification code when you sign in. Your account will be less secure."
                                                            data-confirm-button="Turn off 2FA"
                                                            data-confirm-button-class="btn-warning"
                                                            data-confirm-require-password="true">
                                                        <i class="mdi mdi-shield-off-outline mr-1"></i> Turn off 2FA
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-primary waves-effect waves-light" id="openTwoFactorModal">
                                                    <i class="mdi mdi-shield-lock-outline mr-1"></i> Enable 2FA
                                                </button>
                                                @if($pendingCode)
                                                    <p class="text-muted font-14 mt-2 mb-0">
                                                        A verification code was emailed to <b>{{ $user->email }}</b> —
                                                        click above to open the dialog and enter it.
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Danger zone -->
                                    <div class="card m-b-30 border-danger">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title text-danger">
                                                <i class="mdi mdi-alert-octagon-outline mr-1"></i> Danger Zone
                                            </h4>
                                            <p class="text-muted font-14">
                                                Deleting your account schedules it for permanent removal in
                                                <b>7 days</b>. All your data will be permanently deleted and cannot be
                                                recovered.
                                            </p>

                                            @if($user->deletion_scheduled_for)
                                                <div class="alert alert-warning py-2">
                                                    <i class="mdi mdi-clock-outline mr-1"></i>
                                                    Deletion scheduled for
                                                    <b>{{ $user->deletion_scheduled_for->format('jS F, Y') }}</b>.
                                                </div>
                                                <form method="POST" action="{{ route('account.settings.delete.cancel') }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success waves-effect waves-light">
                                                        <i class="mdi mdi-undo mr-1"></i> Cancel deletion
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('account.settings.delete.request') }}">
                                                    @csrf
                                                    <p class="text-muted font-14">
                                                        Clicking below will ask you to confirm your password and schedule
                                                        the account for deletion.
                                                    </p>
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light"
                                                            data-confirm
                                                            data-confirm-title="Delete this account?"
                                                            data-confirm-message="Your account will be scheduled for permanent deletion in <b>7 days</b>. All your data will be permanently removed and cannot be recovered. You can cancel anytime before then."
                                                            data-confirm-button="Schedule deletion"
                                                            data-confirm-button-class="btn-danger"
                                                            data-confirm-require-password="true">
                                                        <i class="mdi mdi-delete-forever mr-1"></i> Delete account
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============ /Security ============ -->

                        <!-- ============ Team ============ -->
                        <div class="tab-pane" id="team-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card m-b-30">
                                        <div class="card-body text-center py-5">
                                            <i class="mdi mdi-account-multiple-outline display-4 text-primary"></i>
                                            <h4 class="mt-3 mb-1">Team members</h4>
                                            <p class="text-muted mb-3">
                                                {{ $memberCount }} {{ Str::plural('person', $memberCount) }} have access to this account.
                                            </p>
                                            <a href="{{ route('team.index') }}" class="btn btn-primary waves-effect waves-light">
                                                <i class="mdi mdi-account-group-outline mr-1"></i> Manage team members
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============ /Team ============ -->
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- Two-factor email-OTP modal --}}
<div class="modal fade" id="twoFactorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enable two-factor authentication</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted" id="twoFactorModalHint">
                    A verification code is on its way to <b>{{ $user->email }}</b>. Enter the 6-digit code
                    below to enable two-factor authentication.
                    <span id="twoFactorExpiryHint" class="{{ $pendingCode && $pendingCode->expires_at ? '' : 'd-none' }}">
                        <br><small class="text-danger">Code expires at
                            {{ $pendingCode && $pendingCode->expires_at ? $pendingCode->expires_at->format('g:i A') : '' }}.</small>
                    </span>
                    <small id="twoFactorSentHint" class="text-success d-none mt-1">
                        <i class="mdi mdi-check-circle-outline mr-1"></i><span id="twoFactorSentHintText"></span>
                    </small>
                    <small id="twoFactorErrorHint" class="text-danger d-none mt-1">
                        <i class="mdi mdi-alert-circle-outline mr-1"></i><span id="twoFactorErrorHintText"></span>
                    </small>
                </p>

                <form method="POST" action="{{ route('account.settings.two-factor.confirm') }}">
                    @csrf
                    <input type="text" name="code" id="twoFactorCode"
                           class="form-control text-center code-input mb-3"
                           maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                           autocomplete="one-time-code" placeholder="• • • • • •"
                           aria-label="6-digit verification code" required>
                    <button type="submit" class="btn btn-success btn-block waves-effect waves-light">
                        <i class="mdi mdi-check mr-1"></i> Verify &amp; enable
                    </button>
                </form>

                {{-- The code is sent automatically when the modal opens; this button is disabled
                     until the 60s cooldown has elapsed. --}}
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-outline-primary btn-sm px-3 resend-code-btn"
                            id="twoFactorResendBtn" disabled>
                        <i class="mdi mdi-email-sync-outline mr-1"></i>
                        <span id="twoFactorResendLabel">Resend code</span>
                    </button>
                    <div class="mt-1 d-none" id="twoFactorCooldown">
                        <small class="text-muted">You can request a new code in
                            <span id="twoFactorCooldownCount">1:00</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('after-scripts')
    <script>
        // Show the chosen file name on the custom file input
        $('#photoInput').on('change', function () {
            var file = this.files[0];
            if (file) {
                $(this).next('.custom-file-label').text(file.name);
            }
        });

        // ---- Two-factor email-OTP modal ----
        // Opening the modal automatically sends the verification code. The 'Resend code'
        // button is disabled for 60s (the server-side throttle window) and counts down.
        var $twoFactorModal = $('#twoFactorModal');
        var $resendBtn = $('#twoFactorResendBtn');
        var $cooldownBox = $('#twoFactorCooldown');
        var $cooldownCount = $('#twoFactorCooldownCount');

        // Matches the 60s window in AccountSettingsController::resendTwoFactor.
        var TWO_FACTOR_COOLDOWN = 60;
        // Short window before a manual retry after a genuine send failure.
        var TWO_FACTOR_RETRY_COOLDOWN = 15;

        // When the last code was sent / when it expires (ms since epoch). Seeded from
        // the pending code so a page reload mid-cooldown still counts down correctly.
        var lastCodeSentAt = {{ $pendingCode ? $pendingCode->created_at->timestamp * 1000 : 0 }};
        var codeExpiresAt = {{ $pendingCode && $pendingCode->expires_at ? $pendingCode->expires_at->timestamp * 1000 : 0 }};
        // First send uses the 'enable' endpoint (no throttle check); later ones 'resend'.
        var firstSend = {{ $pendingCode ? 'false' : 'true' }};
        var cooldownTimer = null;
        var modalOpened = false;

        $('#openTwoFactorModal').on('click', function () {
            $twoFactorModal.modal('show');
        });

        $twoFactorModal.on('shown.bs.modal', function () {
            $('#twoFactorCode').focus();

            // Auto-send on first open, or when the previous code has expired (10 min).
            var codeExpired = codeExpiresAt > 0 && Date.now() >= codeExpiresAt;

            if (!modalOpened || codeExpired) {
                modalOpened = true;
                var secondsSinceLast = lastCodeSentAt
                    ? Math.floor((Date.now() - lastCodeSentAt) / 1000)
                    : TWO_FACTOR_COOLDOWN;

                if (secondsSinceLast >= TWO_FACTOR_COOLDOWN) {
                    sendTwoFactorCode(); // auto-send the code
                } else {
                    // A code was already emailed recently — don't resend, just count down.
                    showTwoFactorHint('A verification code has already been sent to your email.');
                    startTwoFactorCooldown(TWO_FACTOR_COOLDOWN - secondsSinceLast);
                }
            }
        });

        $twoFactorModal.on('hidden.bs.modal', function () {
            $('#twoFactorCode').val('');
        });

        // Note: these toggle d-block on show because .d-block beats .d-none in
        // bootstrap.min.css (same specificity + !important, later declaration wins).
        function showTwoFactorHint(msg) {
            $('#twoFactorErrorHint').addClass('d-none').removeClass('d-block');
            $('#twoFactorSentHintText').text(msg);
            $('#twoFactorSentHint').removeClass('d-none').addClass('d-block');
        }

        function showTwoFactorError(msg) {
            $('#twoFactorSentHint').addClass('d-none').removeClass('d-block');
            $('#twoFactorErrorHintText').text(msg);
            $('#twoFactorErrorHint').removeClass('d-none').addClass('d-block');
        }

        function setTwoFactorResendEnabled(enabled) {
            $resendBtn.prop('disabled', !enabled);
            if (enabled) {
                $cooldownBox.addClass('d-none');
            }
        }

        function setTwoFactorResendBusy(busy) {
            if (busy) {
                $resendBtn.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm mr-1"></span> Sending…');
            } else {
                $resendBtn.html('<i class="mdi mdi-email-sync-outline mr-1"></i>' +
                                '<span id="twoFactorResendLabel">Resend code</span>');
            }
        }

        function formatTwoFactorCooldown(totalSeconds) {
            var m = Math.floor(totalSeconds / 60);
            var s = totalSeconds % 60;
            return m + ':' + (s < 10 ? '0' : '') + s;
        }

        function startTwoFactorCooldown(seconds) {
            stopTwoFactorCooldown();
            setTwoFactorResendEnabled(false);
            $cooldownBox.removeClass('d-none');
            var remaining = Math.max(0, seconds);

            function tick() {
                if (remaining <= 0) {
                    stopTwoFactorCooldown();
                    setTwoFactorResendEnabled(true);
                    return;
                }
                $cooldownCount.text(formatTwoFactorCooldown(remaining));
                remaining--;
                cooldownTimer = setTimeout(tick, 1000);
            }
            tick();
        }

        function stopTwoFactorCooldown() {
            if (cooldownTimer) {
                clearTimeout(cooldownTimer);
                cooldownTimer = null;
            }
        }

        function sendTwoFactorCode() {
            var url = firstSend
                ? '{{ route('account.settings.two-factor.enable') }}'
                : '{{ route('account.settings.two-factor.resend') }}';

            setTwoFactorResendBusy(true);

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    if (res && res.status === 1) {
                        firstSend = false;
                        lastCodeSentAt = Date.now();

                        $('#twoFactorExpiryHint').removeClass('d-none');
                        if (res.expires_at) {
                            $('#twoFactorExpiryHint').html(
                                '<br><small class="text-danger">Code expires at ' + res.expires_at + '.</small>'
                            );
                        }
                        showTwoFactorHint(res.message || 'Check your email for the code.');
                        startTwoFactorCooldown(TWO_FACTOR_COOLDOWN);
                        $('#twoFactorCode').focus();
                    } else if (res && res.throttled) {
                        // Server-side cooldown still active; keep counting down from what we know.
                        var elapsed = lastCodeSentAt ? Math.floor((Date.now() - lastCodeSentAt) / 1000) : 0;
                        startTwoFactorCooldown(Math.max(0, TWO_FACTOR_COOLDOWN - elapsed));
                        showTwoFactorError(res.message || 'Please wait a moment before requesting a new code.');
                    } else {
                        showTwoFactorError((res && res.message) || 'Could not send the code. Please try again.');
                        startTwoFactorCooldown(TWO_FACTOR_RETRY_COOLDOWN);
                    }

                    // Global toast feedback for the AJAX outcome (top-right, auto-hides).
                    if (window.showToastFromResponse) {
                        showToastFromResponse(res);
                    }
                },
                error: function (xhr) {
                    showTwoFactorError('Could not send the code. Please try again.');
                    startTwoFactorCooldown(TWO_FACTOR_RETRY_COOLDOWN);
                    if (window.showToastAjaxError) {
                        showToastAjaxError(xhr, 'Could not send the code. Please try again.');
                    }
                },
                complete: function () {
                    setTwoFactorResendBusy(false);
                }
            });
        }

        $resendBtn.on('click', function () {
            if (!$(this).prop('disabled')) {
                sendTwoFactorCode();
            }
        });
    </script>
@stop

@section('after-styles')
    <style>
        /* Size and style the 2FA code input like the template's regular inputs (e.g. the login page)
           but keep a white background so the 6-digit code is easy to read.
           Note: .form-control.code-input (instead of just .code-input) is needed so these rules beat
           the theme's .form-control:focus rule (style.css), which otherwise forces light text while
           the input is focused (i.e. while typing). */
        .form-control.code-input {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.4em;
            text-indent: 0.4em;
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            color: #000000;
            -webkit-text-fill-color: #000000;
            caret-color: #5985ee;
        }
        .form-control.code-input::placeholder {
            color: #b6c2d0;
            font-weight: 400;
            letter-spacing: 0.35em;
            text-indent: 0.35em;
        }
        .form-control.code-input:focus {
            background-color: #ffffff;
            color: #000000;
            -webkit-text-fill-color: #000000;
            border-color: #5985ee;
            box-shadow: 0 0 0 0.15rem rgba(89, 133, 238, 0.25);
        }
        /* Keep the resend action clearly visible (theme link colors blend into the modal) */
        .resend-code-btn {
            color: #556ee6;
            border-color: #556ee6;
            background-color: transparent;
            font-weight: 600;
        }
        .resend-code-btn:hover,
        .resend-code-btn:focus {
            color: #ffffff;
            background-color: #556ee6;
            border-color: #556ee6;
        }
    </style>
@stop
