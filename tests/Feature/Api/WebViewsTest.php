<?php

namespace Tests\Feature\Api;

use App\Mail\TeamInviteMail;
use App\Mail\TwoFactorCodeMail;
use App\Notifications\WalletFundingNotification;
use App\Models\AuditTrail;
use App\Models\BouncerRoleModel;
use App\Models\Settings;
use App\Models\TwoFactorCode;
use App\Models\User;
use App\Notifications\CompanyNotification;
use App\Notifications\UserNotification;
use Bouncer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebViewsTest extends ApiTestCase
{
    private function verifiedUser(array $attributes = [])
    {
        $user = $this->makeUser($attributes);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function grantRole($user, $roleName)
    {
        Bouncer::useRoleModel(BouncerRoleModel::class);
        $role = Bouncer::role()->firstOrCreate([
            'name'       => $roleName,
            'company_id' => $user->company_id,
        ]);

        // Mirror production: roles arrive with their default abilities (seeder
        // / invite), so granted roles carry the abilities @can guards rely on.
        app(\App\Services\TeamMemberService::class)->ensureDefaultAbilities($role);

        $user->assign($role->id);

        return $user;
    }

    private function grantAdmin($user)
    {
        return $this->grantRole($user, 'admin');
    }

    public function testAccountSettingsPageRenders()
    {
        $user = $this->grantAdmin($this->verifiedUser());

        $response = $this->actingAs($user)->get('/account-settings');

        $response->assertOk();
        $response->assertSee('Account Settings');
        $response->assertSee('Two-Factor Authentication');
        $response->assertSee('Enable 2FA');

        // The team tab is now a link to the dedicated, full-width team page.
        $response->assertSee('Team members');
        $response->assertSee('Manage team members');
        $response->assertSee(route('team.index'));

        // Reusable confirmation modal + password-required delete wiring are present
        $response->assertSee('id="confirmModal"', false);
        $response->assertSee('data-confirm-require-password="true"', false);

        // 2FA email-OTP modal is present
        $response->assertSee('id="twoFactorModal"', false);
        $response->assertSee('Enable two-factor authentication', false);

        // The page is now the full profile: editable profile info + account details
        $response->assertSee('Profile information');
        $response->assertSee('Save changes');
        $response->assertSee('Account details');
        $response->assertSee($user->uuid, false);
    }

    public function testTwoFactorEnableWebFlowEmailsCode()
    {
        Mail::fake();

        $user = $this->verifiedUser();

        $this->actingAs($user)->post('/account-settings/two-factor/enable')->assertRedirect();

        Mail::assertSent(TwoFactorCodeMail::class);

        $this->actingAs($user)->get('/account-settings')
            ->assertOk()
            ->assertSee('A verification code has been sent to your email');
    }

    public function testAuditTrailPageRendersWithNewColumns()
    {
        $user = $this->verifiedUser();

        AuditTrail::create([
            'company_id'  => $user->company_id,
            'admin_id'    => $user->id,
            'role'        => 'admin',
            'action'      => 'two_factor.enabled',
            'type'        => 'two_factor.enabled',
            'description' => 'Two-factor authentication enabled (email OTP)',
            'severity'    => 'warning',
            'ip_address'  => '127.0.0.1',
            'user_agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
        ]);

        $response = $this->actingAs($user)->get('/report_audit_trail');

        $response->assertOk();
        $response->assertSee('Severity');
        $response->assertSee('IP Address');
        $response->assertSee('Browser');
        $response->assertSee('Chrome');
        $response->assertSee('Two-factor authentication enabled');

        // Audit Trail is reachable from the nav (the Reports submenu link uses
        // the named route, which also backs the page's search form action).
        $response->assertSee(route('audit.trail.index'));

        // The filter sidebar is collapsible so the table can take full width.
        $response->assertSee('id="filterToggle"', false);
        $response->assertSee('id="filterBody"', false);
        $response->assertSee('id="showFiltersBtn"', false);
    }

    public function testTeamInviteWebFlow()
    {
        Mail::fake();

        $admin = $this->grantAdmin($this->verifiedUser());

        $this->actingAs($admin)
            ->post('/account-settings/team/invite', [
                'email'      => 'newmember' . uniqid() . '@example.com',
                'role'       => 'viewer',
            ])
            ->assertRedirect()
            ->assertSessionHas('toast');

        Mail::assertSent(TeamInviteMail::class);
    }

    public function testTeamInviteRequiresAdminRole()
    {
        Mail::fake();

        $user = $this->verifiedUser(); // no role assigned

        $this->actingAs($user)
            ->post('/account-settings/team/invite', [
                'email' => 'someone' . uniqid() . '@example.com',
                'role'  => 'viewer',
            ])
            ->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'Only admins can manage team members.', 'type' => 'danger']);

        Mail::assertNothingSent();
    }

    public function testDatabaseNotificationsAreStoredViaArrayRepresentation()
    {
        $user = $this->verifiedUser();

        $user->notify(new WalletFundingNotification(5000, 'REF-123'));

        // The database channel persists the toArray() payload into the notifications table.
        $this->assertSame(1, $user->notifications()->count());

        $data = $user->notifications()->first()->data;
        $this->assertSame('wallet_funding', $data['module']);
        $this->assertStringContainsString('5,000.00', $data['text']);
        $this->assertSame('REF-123', $data['reference']);
    }

    public function testUserAndCompanyNotificationsSaveToDatabase()
    {
        $user = $this->verifiedUser();

        $user->notify(new UserNotification('A user-level message'));
        $user->notify(new CompanyNotification('A company-level message'));

        $this->assertSame(2, $user->notifications()->count());

        $modules = $user->notifications()->pluck('data')->map(fn ($data) => $data['module'])->sort()->values()->all();
        $this->assertSame(['company', 'user'], $modules);
    }

    public function testFundWalletCreatesDatabaseNotification()
    {
        Mail::fake();

        $companyId = DB::table('company')->insertGetId([
            'name'         => 'Notif Co',
            'email'        => 'notif' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $admin = $this->verifiedUser(['company_id' => $companyId]);
        $customer = $this->verifiedUser(['company_id' => $companyId]);

        $this->actingAs($admin)->post('/fundwallet', [
            'user_name' => $customer->email,
            'amount'    => 5000,
        ])->assertRedirect();

        // The customer gets a database notification via the array representation.
        $this->assertSame(1, $customer->notifications()->count());

        $data = $customer->notifications()->first()->data;
        $this->assertSame('wallet_funding', $data['module']);
        $this->assertStringContainsString('5,000.00', $data['text']);
    }

    public function testNotificationIndexFiltersUnreadOnly()
    {
        $user = $this->verifiedUser();

        DB::table('notifications')->insert([
            'id'              => (string) Str::uuid(),
            'type'            => UserNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => json_encode(['module' => 'general', 'text' => 'Unread one']),
            'read_at'         => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        DB::table('notifications')->insert([
            'id'              => (string) Str::uuid(),
            'type'            => UserNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => json_encode(['module' => 'general', 'text' => 'Read one']),
            'read_at'         => now(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Default view shows everything, including the type badge column.
        $this->actingAs($user)->get('/notifications')
            ->assertOk()
            ->assertSee('Unread one')
            ->assertSee('Read one')
            ->assertSee('General', false);

        // The unread filter hides read ones.
        $this->actingAs($user)->get('/notifications?unread=1')
            ->assertOk()
            ->assertSee('Unread one')
            ->assertDontSee('Read one');
    }

    public function testTeamInviteCreatesDatabaseNotification()
    {
        Mail::fake();

        $admin = $this->grantAdmin($this->verifiedUser());

        $this->actingAs($admin)
            ->post('/account-settings/team/invite', [
                'email' => 'invitee' . uniqid() . '@example.com',
                'role'  => 'viewer',
            ])
            ->assertRedirect()
            ->assertSessionHas('toast');

        $invitee = User::where('email', 'LIKE', 'invitee%@example.com')->first();
        $this->assertNotNull($invitee);
        $this->assertSame(1, $invitee->notifications()->count());
        $this->assertSame('team', $invitee->notifications()->first()->data['module']);
    }

    public function testPhotoUploadAndRemoveWebFlow()
    {
        Storage::fake('public');

        $user = $this->verifiedUser();

        $this->actingAs($user)->post('/account-settings/photo', [
            'image' => UploadedFile::fake()->image('avatar.png', 100, 100),
        ])->assertRedirect()->assertSessionHas('toast');

        $this->assertNotNull($user->fresh()->profile_photo_path);

        $this->actingAs($user)->post('/account-settings/photo/remove')
            ->assertRedirect()
            ->assertSessionHas('toast');

        $this->assertNull($user->fresh()->profile_photo_path);
    }

    public function testBillingPageShowsLiveFundingFee()
    {
        $user = $this->verifiedUser();

        $this->actingAs($user)->get('/billing')
            ->assertOk()
            ->assertSee('80.00')
            ->assertSee('live from system settings');
    }

    public function testDepositReportDoesNotSpamNotifications()
    {
        // Regression: DepositController::index() used to send a placeholder
        // maintenance notification to EVERY user on EVERY page load, which is
        // why the bell badge never cleared. Visiting the page must not create
        // any new notifications for the viewer (scoped to the user — the table
        // may legitimately hold pre-existing rows).
        $user = $this->verifiedUser();

        $this->actingAs($user)->get('/report_deposit')->assertOk();

        $this->assertSame(0, $user->unreadNotifications()->count());
    }

    public function testNotificationCountEndpoint()
    {
        $user = $this->verifiedUser();

        $this->actingAs($user)->get('/notifications/count')
            ->assertOk()
            ->assertJson(['count' => 0]);

        DB::table('notifications')->insert([
            'id'              => (string) Str::uuid(),
            'type'            => UserNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => json_encode(['text' => 'New deposit received']),
            'read_at'         => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $this->actingAs($user)->get('/notifications/count')
            ->assertOk()
            ->assertJson(['count' => 1]);
    }

    public function testNotificationCountCapsAtOneHundred()
    {
        $user = $this->verifiedUser();

        $now = now();
        foreach (range(1, 105) as $i) {
            DB::table('notifications')->insert([
                'id'              => (string) Str::uuid(),
                'type'            => UserNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode(['text' => 'Bulk notification ' . $i]),
                'read_at'         => null,
                'created_at'      => $now->copy()->addSeconds($i),
                'updated_at'      => $now->copy()->addSeconds($i),
            ]);
        }

        // The badge count never reports more than 100 (the UI renders it as '99+').
        $this->actingAs($user)->get('/notifications/count')
            ->assertOk()
            ->assertJson(['count' => 100]);
    }

    public function testNotificationIndexOnlyPaginatesFirstHundred()
    {
        $user = $this->verifiedUser();

        $now = now();
        foreach (range(1, 105) as $i) {
            DB::table('notifications')->insert([
                'id'              => (string) Str::uuid(),
                'type'            => UserNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode(['text' => 'Bulk notification ' . $i]),
                'read_at'         => null,
                'created_at'      => $now->copy()->addSeconds($i),
                'updated_at'      => $now->copy()->addSeconds($i),
            ]);
        }

        // Page 1 holds the 20 newest.
        $this->actingAs($user)->get('/notifications')
            ->assertOk()
            ->assertSee('Bulk notification 105');

        // Exactly 5 pages exist (100 rows @ 20/page) — page 5 shows the oldest
        // browsable rows (newest is #105, so offset 80 lands on #25).
        $this->actingAs($user)->get('/notifications?page=5')
            ->assertOk()
            ->assertSee('Bulk notification 25');

        // The 101st+ notification is unreachable — page 6 is empty.
        $this->actingAs($user)->get('/notifications?page=6')
            ->assertOk()
            ->assertSee('No notifications yet.');
    }

    public function testNotificationFeedEndpoint()
    {
        $user = $this->verifiedUser();

        DB::table('notifications')->insert([
            'id'              => (string) Str::uuid(),
            'type'            => UserNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => json_encode(['text' => 'New deposit received']),
            'read_at'         => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $this->actingAs($user)->get('/notifications/feed')
            ->assertOk()
            ->assertSee('New deposit received')
            ->assertSee('new');
    }

    public function testNotificationFeedShowsOnlyUnread()
    {
        $user = $this->verifiedUser();
        $unreadId = (string) Str::uuid();

        DB::table('notifications')->insert([
            [
                'id'              => $unreadId,
                'type'            => UserNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode(['text' => 'Unread feed item']),
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => (string) Str::uuid(),
                'type'            => UserNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode(['text' => 'Read feed item should be hidden']),
                'read_at'         => now(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);

        // The bell dropdown lists unread only — a read notification must not appear.
        $this->actingAs($user)->get('/notifications/feed')
            ->assertOk()
            ->assertSee('Unread feed item')
            ->assertDontSee('Read feed item should be hidden');

        // Once the unread one is marked read, it disappears from the feed too.
        $this->actingAs($user)->get('/notification-read/' . $unreadId);

        $this->actingAs($user)->get('/notifications/feed')
            ->assertOk()
            ->assertDontSee('Unread feed item')
            ->assertSee('No unread notifications.');
    }

    public function testValidationErrorsRenderAsDangerToasts()
    {
        $user = $this->verifiedUser();

        // A failed form submit redirects back with the $errors bag flashed.
        $this->actingAs($user)
            ->from('/account-settings')
            ->post('/account-settings/profile', [
                'first_name' => '',
                'last_name'  => '',
                'email'      => 'not-an-email',
                'phoneno'    => '',
            ])
            ->assertSessionHasErrors();

        // The next page render surfaces the validation errors as danger toasts.
        $this->actingAs($user)->get('/account-settings')
            ->assertOk()
            ->assertSee('data-type="danger"', false)
            ->assertSee('The first name field is required.')
            ->assertSee('The email must be a valid email address.');
    }

    public function testNotificationIndexPageRenders()
    {
        $user = $this->verifiedUser();

        DB::table('notifications')->insert([
            'id'              => (string) Str::uuid(),
            'type'            => UserNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => json_encode(['text' => 'Full page notification text']),
            'read_at'         => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $this->actingAs($user)->get('/notifications')
            ->assertOk()
            ->assertSee('Full page notification text')
            ->assertSee('Mark all as read')
            ->assertSee('View All')
            ->assertSee('badge-success', false);
    }

    public function testNotificationIndexPageEmptyState()
    {
        $user = $this->verifiedUser();

        $this->actingAs($user)->get('/notifications')
            ->assertOk()
            ->assertSee('No notifications yet.');
    }

    public function testMarkAllNotificationsAsReadWeb()
    {
        $user = $this->verifiedUser();

        foreach (range(1, 2) as $i) {
            DB::table('notifications')->insert([
                'id'              => (string) Str::uuid(),
                'type'            => UserNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode(['text' => 'Unread notification ' . $i]),
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        $this->actingAs($user)->post('/notifications/read-all')
            ->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'All notifications marked as read.', 'type' => 'success']);

        $this->assertSame(0, $user->unreadNotifications()->count());
    }

    public function testMarkAllNotificationsAsReadAjax()
    {
        $user = $this->verifiedUser();

        DB::table('notifications')->insert([
            'id'              => (string) Str::uuid(),
            'type'            => UserNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => json_encode(['text' => 'Ajax unread']),
            'read_at'         => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $this->actingAs($user)
            ->post('/notifications/read-all', [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJson(['status' => 1]);

        $this->assertSame(0, $user->unreadNotifications()->count());
    }

    public function testTwoFactorEnableEndpointSupportsAjax()
    {
        Mail::fake();

        $user = $this->verifiedUser();

        $this->actingAs($user)
            ->post('/account-settings/two-factor/enable', [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJson(['status' => 1])
            ->assertJsonPath('toast.message', 'A verification code has been sent to your email.')
            ->assertJsonPath('toast.type', 'success');

        Mail::assertSent(TwoFactorCodeMail::class);
    }

    public function testTwoFactorEnableAjaxWhenAlreadyEnabledCarriesDangerToast()
    {
        $user = $this->verifiedUser();
        $user->forceFill(['email_2fa_enabled' => true])->save();

        $this->actingAs($user)
            ->post('/account-settings/two-factor/enable', [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJson(['status' => 0])
            ->assertJsonPath('toast.message', 'Two-factor authentication is already enabled.')
            ->assertJsonPath('toast.type', 'danger');
    }

    public function testTwoFactorResendEndpointSupportsAjax()
    {
        Mail::fake();

        $user = $this->verifiedUser();

        // Seed a pending code older than the 60s throttle window so the resend succeeds.
        $record = TwoFactorCode::create([
            'user_id'    => $user->id,
            'code'       => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);
        $record->forceFill(['created_at' => now()->subMinutes(5)])->save();

        $this->actingAs($user)
            ->post('/account-settings/two-factor/resend', [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJson(['status' => 1])
            ->assertJsonPath('toast.message', 'A new verification code has been sent to your email.')
            ->assertJsonPath('toast.type', 'success');

        Mail::assertSent(TwoFactorCodeMail::class);
    }

    public function testTwoFactorResendThrottledForAjax()
    {
        Mail::fake();

        $user = $this->verifiedUser();

        $this->actingAs($user)
            ->post('/account-settings/two-factor/enable', [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJson(['status' => 1]);

        // An immediate resend falls inside the 60s throttle window.
        $this->actingAs($user)
            ->post('/account-settings/two-factor/resend', [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJson(['status' => 0])
            ->assertJsonPath('toast.type', 'danger')
            ->assertJsonPath('throttled', true);
    }

    public function testDeleteAccountWebFlowRequiresPassword()
    {
        Mail::fake();

        $user = $this->verifiedUser();

        // Missing password → validation error
        $this->actingAs($user)->post('/account-settings/delete-request', [])
            ->assertSessionHasErrors('password');

        // Wrong password → rejected
        $this->actingAs($user)->post('/account-settings/delete-request', ['password' => 'wrongpass'])
            ->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'Incorrect password.', 'type' => 'danger']);

        // Correct password → deletion scheduled
        $this->actingAs($user)->post('/account-settings/delete-request', ['password' => 'password123'])
            ->assertRedirect()
            ->assertSessionHas('toast');

        $this->assertNotNull($user->fresh()->deletion_scheduled_for);
    }

    public function testSystemSettingsUpdateRequiresAdmin()
    {
        $user = $this->verifiedUser(); // no admin role

        $this->actingAs($user)->get('/settings/system')
            ->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'Only admins can manage system settings.', 'type' => 'danger']);

        $this->actingAs($user)->post('/settings/system', ['funding_fee' => 125])
            ->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'Only admins can manage system settings.', 'type' => 'danger']);

        $this->assertSame('80.00', Settings::find(1)->funding_fee);
    }

    public function testSystemSettingsUpdate()
    {
        $admin = $this->grantAdmin($this->verifiedUser());

        $this->actingAs($admin)->get('/settings/system')
            ->assertOk()
            ->assertSee('Wallet top-up funding fee');

        $this->actingAs($admin)->post('/settings/system', ['funding_fee' => 125])
            ->assertRedirect()
            ->assertSessionHas('toast');

        $this->assertSame('125.00', Settings::find(1)->funding_fee);
    }

    public function testGuestErrorPagesRenderWithoutCrash()
    {
        // Guests hitting a missing route used to crash the 404 page because the
        // nav dropdown read Auth::user()->name while unauthenticated, and the
        // layout referenced $header/$slot that @extends pages never provide.
        $this->get('/definitely-not-a-route-' . Str::random(8))
            ->assertNotFound()
            ->assertSee('Sorry, page not found');
    }

    public function testAuthenticatedErrorPagesRenderWithoutCrash()
    {
        $user = $this->verifiedUser();

        $this->actingAs($user)->get('/definitely-not-a-route-' . Str::random(8))
            ->assertNotFound()
            ->assertSee('Sorry, page not found');
    }

    public function testWithToastMacroFlashesStructuredToast()
    {
        redirect()->route('dashboard')->withToast('Saved successfully', 'success');

        $this->assertSame('Saved successfully', session('toast.message'));
        $this->assertSame('success', session('toast.type'));

        redirect()->route('dashboard')->withToast('Something failed', 'danger');
        $this->assertSame('danger', session('toast.type'));

        // Unsupported types fall back to success.
        redirect()->route('dashboard')->withToast('Done', 'bogus');
        $this->assertSame('success', session('toast.type'));

        // Works on redirect()->back() too (macro lives on RedirectResponse).
        redirect()->back()->withToast('Via back', 'info');
        $this->assertSame('Via back', session('toast.message'));
        $this->assertSame('info', session('toast.type'));
    }

    public function testStructuredToastFlashRendersAsToast()
    {
        $user = $this->verifiedUser();

        session()->flash('toast', ['message' => 'Saved successfully', 'type' => 'success']);

        $this->actingAs($user)->get('/account-settings')
            ->assertOk()
            ->assertSee('Saved successfully');
    }

    public function testDashboardShowsOnlyTodaysActivityWithSeeMoreLink()
    {
        $user = $this->verifiedUser();

        AuditTrail::create([
            'company_id'  => $user->company_id,
            'admin_id'    => $user->id,
            'type'        => 'auth.login',
            'action'      => 'auth.login',
            'description' => 'User logged in today',
            'severity'    => 'info',
        ]);

        $old = AuditTrail::create([
            'company_id'  => $user->company_id,
            'admin_id'    => $user->id,
            'type'        => 'settings.updated',
            'action'      => 'settings.updated',
            'description' => 'An old activity from yesterday',
            'severity'    => 'info',
        ]);
        $old->forceFill(['created_at' => now()->subDay()])->save();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Recent Activity');
        $response->assertSee('auth.login');
        $response->assertDontSee('settings.updated'); // yesterday's activity is hidden
        $response->assertSee('See more');
        $response->assertSee(route('audit.trail.index'));
    }

    public function testDashboardEmptyActivityIsNotRenderedAsFeedItem()
    {
        $user = $this->verifiedUser(); // no audit trail at all

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('No activity today');

        // The empty state must NOT look like an activity entry (no feed item markup).
        $response->assertDontSee('feed-item', false);
    }

    public function testUserRoutesUseUuidInsteadOfNumericId()
    {
        $companyId = DB::table('company')->insertGetId([
            'name'         => 'Uuid Test Co',
            'email'        => 'uuidtest' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyId]);
        $target = $this->verifiedUser(['company_id' => $companyId]);

        $this->assertNotNull($target->uuid);
        $this->assertNotEquals((string) $target->id, (string) $target->uuid);

        // Numeric ids no longer resolve users on the web routes.
        $this->actingAs($viewer)->get('/user/' . $target->id)->assertNotFound();

        // The public uuid resolves the user and the numeric id is not displayed.
        $this->actingAs($viewer)->get('/user/' . $target->uuid)
            ->assertOk()
            ->assertSee($target->last_name . ' ' . $target->first_name) // card title renders last name first
            ->assertSee('Customer Ref')
            ->assertDontSee('Customer ID');

        // List pages link via uuid.
        $this->actingAs($viewer)->get('/users')
            ->assertOk()
            ->assertSee('/user/' . $target->uuid);
    }

    public function testProfileUpdateWebFlow()
    {
        $user = $this->verifiedUser();

        $this->actingAs($user)->post('/account-settings/profile', [
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
            'email'      => 'jane' . uniqid() . '@example.com',
            'phoneno'    => '08012345678',
        ])->assertRedirect()->assertSessionHas('toast');

        $fresh = $user->fresh();
        $this->assertSame('Jane', $fresh->first_name);
        $this->assertSame('Doe', $fresh->last_name);
        $this->assertSame('08012345678', $fresh->phoneno);

        // The account stays verified — every admin route requires 'verified' and this
        // app has no verification.verify route, so invalidating it would lock the user out.
        $this->assertNotNull($fresh->email_verified_at);
    }

    public function testProfileUpdateRequiresValidInput()
    {
        $user = $this->verifiedUser();

        $this->actingAs($user)->post('/account-settings/profile', [
            'first_name' => '',
            'last_name'  => '',
            'email'      => 'not-an-email',
            'phoneno'    => '',
        ])->assertSessionHasErrors(['first_name', 'last_name', 'email', 'phoneno']);
    }

    public function testUserDisableEnableWebFlowUpdatesStatus()
    {
        $companyId = DB::table('company')->insertGetId([
            'name'         => 'Status Test Co',
            'email'        => 'statustest' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyId]);
        $target = $this->verifiedUser(['company_id' => $companyId]);

        $this->actingAs($viewer)->get('/user-disable/' . $target->uuid)
            ->assertRedirect()
            ->assertSessionHas('toast');

        $this->assertSame('disable', $target->fresh()->status);

        $this->actingAs($viewer)->get('/user-enable/' . $target->uuid)
            ->assertRedirect()
            ->assertSessionHas('toast');

        $this->assertSame('active', $target->fresh()->status);
    }

    public function testAdminDisableEnableWebFlowUpdatesStatus()
    {
        $companyId = DB::table('company')->insertGetId([
            'name'         => 'Status Admin Co',
            'email'        => 'statusadmin' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyId]);
        $target = $this->verifiedUser(['company_id' => $companyId]);

        $this->actingAs($viewer)->get('/admin-disable/' . $target->uuid)
            ->assertRedirect()
            ->assertSessionHas('toast');

        $this->assertSame('disable', $target->fresh()->status);

        $this->actingAs($viewer)->get('/admin-enable/' . $target->uuid)
            ->assertRedirect()
            ->assertSessionHas('toast');

        $this->assertSame('active', $target->fresh()->status);
    }

    public function testUserUpdateAllowsBrandNewEmail()
    {
        $companyId = DB::table('company')->insertGetId([
            'name'         => 'Update Test Co',
            'email'        => 'updatetest' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyId]);
        $target = $this->verifiedUser(['company_id' => $companyId]);
        $newEmail = 'brandnew' . uniqid() . '@example.com';

        $this->actingAs($viewer)->post('/user-update/' . $target->uuid, [
            'last_name'  => 'Newname',
            'first_name' => 'First',
            'email'      => $newEmail,
            'phoneno'    => '08012345678',
        ])->assertRedirect()->assertSessionHas('toast');

        $fresh = $target->fresh();
        $this->assertSame('Newname', $fresh->last_name);
        $this->assertSame($newEmail, $fresh->email);
    }

    public function testUserUpdateRejectsEmailBelongingToAnotherUser()
    {
        $companyId = DB::table('company')->insertGetId([
            'name'         => 'Update Conflict Co',
            'email'        => 'updateconflict' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyId]);
        $target = $this->verifiedUser(['company_id' => $companyId]);
        $other = $this->verifiedUser(['company_id' => $companyId]);

        $this->actingAs($viewer)->post('/user-update/' . $target->uuid, [
            'last_name'  => 'Newname',
            'first_name' => 'First',
            'email'      => $other->email,
            'phoneno'    => '08012345678',
        ])->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'Email Address belongs to another user', 'type' => 'danger']);

        $this->assertNotEquals($other->email, $target->fresh()->email);
    }

    public function testApiLoginAllowsActiveUser()
    {
        $user = $this->makeUser();

        $this->postJson('/api/login', [
            'username'    => $user->email,
            'password'    => 'password123',
            'device_name' => 'test-device',
        ])->assertStatus(200)
            ->assertJsonPath('status', 1)
            ->assertJsonStructure(['token']);
    }

    public function testApiLoginRejectsDisabledUser()
    {
        $user = $this->makeUser(['status' => 'disable']);

        $this->postJson('/api/login', [
            'username'    => $user->email,
            'password'    => 'password123',
            'device_name' => 'test-device',
        ])->assertStatus(200)
            ->assertJsonPath('status', 0)
            ->assertJsonPath('message', 'User disable, kindly contact support');
    }

    public function testAdminCreateRejectsExistingAdminEmail()
    {
        $admin = $this->grantAdmin($this->verifiedUser());
        $existing = $this->verifiedUser(['company_id' => $admin->company_id]);

        $this->actingAs($admin)->post('/admin-create', [
            'last_name'  => 'New',
            'first_name' => 'Admin',
            'email'      => $existing->email,
            'phoneno'    => '08011112222',
            'role_id'    => 1,
        ])->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'Admin already exist', 'type' => 'danger']);
    }

    public function testStatusBadgeRendersOnUserPages()
    {
        $companyId = DB::table('company')->insertGetId([
            'name'         => 'Badge Test Co',
            'email'        => 'badgetest' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyId]);
        $target = $this->verifiedUser(['company_id' => $companyId]);

        // Active user shows the Active pill on the list and detail pages.
        $this->actingAs($viewer)->get('/users')
            ->assertOk()
            ->assertSee('badge-success', false)
            ->assertSee('>Active<', false);

        $this->actingAs($viewer)->get('/user/' . $target->uuid)
            ->assertOk()
            ->assertSee('badge-success', false)
            ->assertSee('>Active<', false);

        // Disabled user shows the Disabled pill.
        $target->forceFill(['status' => 'disable'])->save();

        $this->actingAs($viewer)->get('/users')
            ->assertOk()
            ->assertSee('badge-danger', false)
            ->assertSee('>Disabled<', false);

        $this->actingAs($viewer)->get('/user/' . $target->uuid)
            ->assertOk()
            ->assertSee('badge-danger', false)
            ->assertSee('>Disabled<', false);
    }

    public function testDisableEnableWritesAuditTrailEntries()
    {
        $companyId = DB::table('company')->insertGetId([
            'name'         => 'Audit Toggle Co',
            'email'        => 'audittoggle' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyId]);
        $target = $this->verifiedUser(['company_id' => $companyId]);

        $this->actingAs($viewer)->get('/user-disable/' . $target->uuid)->assertRedirect();

        $this->assertDatabaseHas('audit_trail', [
            'company_id'  => $companyId,
            'admin_id'    => $viewer->id,
            'action'      => 'user.disabled',
            'description' => 'User ' . $target->email . ' disabled',
        ]);

        $this->actingAs($viewer)->get('/user-enable/' . $target->uuid)->assertRedirect();

        $this->assertDatabaseHas('audit_trail', [
            'company_id'  => $companyId,
            'admin_id'    => $viewer->id,
            'action'      => 'user.enabled',
            'description' => 'User ' . $target->email . ' enabled',
        ]);

        $this->actingAs($viewer)->get('/admin-disable/' . $target->uuid)->assertRedirect();
        $this->assertDatabaseHas('audit_trail', ['action' => 'admin.disabled']);

        $this->actingAs($viewer)->get('/admin-enable/' . $target->uuid)->assertRedirect();
        $this->assertDatabaseHas('audit_trail', ['action' => 'admin.enabled']);
    }

    public function testDisableEnableBlockedForOtherCompany()
    {
        $companyA = DB::table('company')->insertGetId([
            'name'         => 'Company A Co',
            'email'        => 'companya' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);
        $companyB = DB::table('company')->insertGetId([
            'name'         => 'Company B Co',
            'email'        => 'companyb' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyA]);
        $other = $this->verifiedUser(['company_id' => $companyB]);

        $this->actingAs($viewer)->get('/user-disable/' . $other->uuid)
            ->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'You cannot manage users from another company.', 'type' => 'danger']);

        $this->assertSame('active', $other->fresh()->status);

        $this->actingAs($viewer)->get('/admin-disable/' . $other->uuid)
            ->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'You cannot manage users from another company.', 'type' => 'danger']);

        $this->assertSame('active', $other->fresh()->status);
    }

    public function testUserUpdateBlockedForOtherCompany()
    {
        $companyA = DB::table('company')->insertGetId([
            'name'         => 'Update Block A',
            'email'        => 'updateblocka' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);
        $companyB = DB::table('company')->insertGetId([
            'name'         => 'Update Block B',
            'email'        => 'updateblockb' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyA]);
        $target = $this->verifiedUser(['company_id' => $companyB]);

        $this->actingAs($viewer)->post('/user-update/' . $target->uuid, [
            'last_name'  => 'Hacked',
            'first_name' => 'Hacker',
            'email'      => 'hacked' . uniqid() . '@example.com',
            'phoneno'    => '08099999999',
        ])->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'You cannot manage users from another company.', 'type' => 'danger']);

        $this->assertSame('Test', $target->fresh()->first_name);
        $this->assertNotEquals('Hacked', $target->fresh()->last_name);
    }

    public function testAdminUpdateBlockedForOtherCompany()
    {
        $companyA = DB::table('company')->insertGetId([
            'name'         => 'Admin Update Block A',
            'email'        => 'adminupdatea' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);
        $companyB = DB::table('company')->insertGetId([
            'name'         => 'Admin Update Block B',
            'email'        => 'adminupdateb' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyA]);
        $target = $this->verifiedUser(['company_id' => $companyB]);

        $this->actingAs($viewer)->post('/admin-update/' . $target->uuid, [
            'last_name'  => 'Hacked',
            'first_name' => 'Hacker',
            'phoneno'    => '08099999999',
            'role_id'    => 1,
        ])->assertRedirect()
            ->assertSessionHas('toast', ['message' => 'You cannot manage users from another company.', 'type' => 'danger']);

        $this->assertSame('Test', $target->fresh()->first_name);
    }

    public function testReportPagesRenderCollapsibleFilters()
    {
        $user = $this->verifiedUser();

        // Every report page renders the shared collapsible filter component,
        // with its own search form and table flowing through the component's
        // slots (asserted per page so a broken slot can't pass silently).
        $pages = [
            '/report_audit_trail'    => ['name="severity"', 'IP Address'],
            '/report_deposit'        => ['name="customer_id"', 'Initial Deposit'],
            '/new_account'           => ['name="introducer"', 'Phoneno'],
            '/account_ledger'        => ['name="customerId"', 'Narration'],
            '/report_service_charge' => ['name="start_date"', 'Amount'],
            '/bank_transfer_deposit' => ['name="transaction_id"', 'Lodgement'],
            '/atm_deposit'           => ['name="transaction_id"', 'Lodgement'],
        ];

        foreach ($pages as $page => [$formField, $tableHeader]) {
            $response = $this->actingAs($user)->get($page);

            $response->assertOk();
            // A toggle in the filter card header, a collapsible form body, and
            // a "Show filters" button that appears when the panel is hidden.
            $response->assertSee('id="filterToggle"', false);
            $response->assertSee('id="filterBody"', false);
            $response->assertSee('id="showFiltersBtn"', false);
            // The page's own form fields and table actually render through the slots.
            $response->assertSee($formField, false);
            $response->assertSee($tableHeader);
        }
    }

    public function testAuditTrailCompanyIdFilterScopedToOwnCompany()
    {
        $companyA = DB::table('company')->insertGetId([
            'name'         => 'Audit Scope A',
            'email'        => 'auditscopea' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);
        $companyB = DB::table('company')->insertGetId([
            'name'         => 'Audit Scope B',
            'email'        => 'auditscopeb' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $viewer = $this->verifiedUser(['company_id' => $companyA]);

        AuditTrail::create([
            'company_id'  => $companyA,
            'admin_id'    => $viewer->id,
            'type'        => 'auth.login',
            'action'      => 'auth.login',
            'description' => 'Company A private entry',
            'severity'    => 'info',
        ]);
        AuditTrail::create([
            'company_id'  => $companyB,
            'admin_id'    => $viewer->id,
            'type'        => 'settings.updated',
            'action'      => 'settings.updated',
            'description' => 'Company B secret entry',
            'severity'    => 'info',
        ]);

        // Trying to read company B's trail via the companyId filter is ignored.
        $this->actingAs($viewer)->get('/report_audit_trail?companyId=' . $companyB)
            ->assertOk()
            ->assertSee('Company A private entry')
            ->assertDontSee('Company B secret entry');
    }

    public function testTeamPageRenders()
    {
        $admin = $this->grantAdmin($this->verifiedUser());
        $member = $this->verifiedUser(['company_id' => $admin->company_id]);

        $this->actingAs($admin)->get('/account-settings/team')
            ->assertOk()
            ->assertSee('Invite a team member')
            ->assertSee('Search name or email')
            // The role-filter pill group renders with per-role count badges.
            ->assertSee('aria-label="Filter by role"', false)
            ->assertSee('badge-light ml-1', false)
            ->assertSee($member->email)
            ->assertSee('people on this account')
            // The admin's role badge renders; members without a role get a clear
            // "No role" badge instead of a bare dash.
            ->assertSee('>Admin<', false)
            ->assertSee('>No role<', false);
    }

    public function testTeamPageSearchFiltersByNameOrEmail()
    {
        $admin = $this->grantAdmin($this->verifiedUser());
        $this->verifiedUser(['company_id' => $admin->company_id, 'first_name' => 'Zebedee']);
        $this->verifiedUser(['company_id' => $admin->company_id, 'first_name' => 'Alice']);

        $this->actingAs($admin)->get('/account-settings/team?search=Zebedee')
            ->assertOk()
            ->assertSee('Zebedee')
            ->assertDontSee('Alice');

        // Searching by email works too.
        $needle = $this->verifiedUser(['company_id' => $admin->company_id, 'first_name' => 'Find']);

        $this->actingAs($admin)->get('/account-settings/team?search=' . urlencode($needle->email))
            ->assertOk()
            ->assertSee($needle->email)
            ->assertDontSee('Alice');
    }

    public function testTeamPageRoleFilter()
    {
        $admin = $this->grantAdmin($this->verifiedUser());
        $viewer = $this->grantRole(
            $this->verifiedUser(['company_id' => $admin->company_id]),
            'viewer'
        );

        // The Admin pill shows the admin but hides the plain viewer.
        $this->actingAs($admin)->get('/account-settings/team?role=admin')
            ->assertOk()
            ->assertSee($admin->email)
            ->assertDontSee($viewer->email);

        // The Viewer pill shows the viewer and hides the admin.
        $this->actingAs($admin)->get('/account-settings/team?role=viewer')
            ->assertOk()
            ->assertSee($viewer->email)
            ->assertSee('>Viewer<', false)
            ->assertDontSee($admin->email);
    }

    public function testTeamPagePaginates()
    {
        $admin = $this->grantAdmin($this->verifiedUser());

        foreach (range(1, 12) as $i) {
            $member = $this->verifiedUser([
                'company_id' => $admin->company_id,
                'first_name' => sprintf('Page%02d', $i),
            ]);
            // Force a deterministic, descending join order (Page12 newest … Page01 oldest).
            $member->forceFill(['created_at' => now()->subMinutes(100 - $i)])->save();
        }

        // Page 1 holds the 10 newest members.
        $this->actingAs($admin)->get('/account-settings/team')
            ->assertOk()
            ->assertSee('Page12')
            ->assertDontSee('Page01');

        // Page 2 holds the oldest members.
        $this->actingAs($admin)->get('/account-settings/team?page=2')
            ->assertOk()
            ->assertSee('Page01')
            ->assertDontSee('Page12');
    }

    public function testTeamInviteGrantsRoleDefaultAbilities()
    {
        Mail::fake();

        $admin = $this->grantAdmin($this->verifiedUser());

        // Finance members get the money-posting abilities out of the box.
        $this->actingAs($admin)
            ->post('/account-settings/team/invite', [
                'email' => 'finance' . uniqid() . '@example.com',
                'role'  => 'finance',
            ])->assertRedirect()->assertSessionHas('toast');

        $financeMember = User::where('email', 'LIKE', 'finance%@example.com')->first();
        $this->assertNotNull($financeMember);
        $this->assertTrue($financeMember->can('fund_wallet'));
        $this->assertTrue($financeMember->can('reversal'));
        // ...but no role/user management abilities.
        $this->assertFalse($financeMember->can('role-create'));
        $this->assertFalse($financeMember->can('user-disable'));

        // Viewers stay read-only.
        $this->actingAs($admin)
            ->post('/account-settings/team/invite', [
                'email' => 'viewer' . uniqid() . '@example.com',
                'role'  => 'viewer',
            ])->assertRedirect();

        $viewerMember = User::where('email', 'LIKE', 'viewer%@example.com')->first();
        $this->assertNotNull($viewerMember);
        $this->assertFalse($viewerMember->can('fund_wallet'));

        // Team admins get everything, mirroring the ceo role.
        $this->actingAs($admin)
            ->post('/account-settings/team/invite', [
                'email' => 'teamadmin' . uniqid() . '@example.com',
                'role'  => 'admin',
            ])->assertRedirect();

        $teamAdmin = User::where('email', 'LIKE', 'teamadmin%@example.com')->first();
        $this->assertNotNull($teamAdmin);
        $this->assertTrue($teamAdmin->can('fund_wallet'));
        $this->assertTrue($teamAdmin->can('role-create'));
        $this->assertTrue($teamAdmin->can('user-disable'));
    }

    public function testAuditTrailCompanyIdFilterAllowedForSuperAdmin()
    {
        $companyB = DB::table('company')->insertGetId([
            'name'         => 'Audit Super B',
            'email'        => 'auditsuperb' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        $super = $this->verifiedUser(['company_id' => 1]);

        AuditTrail::create([
            'company_id'  => $companyB,
            'admin_id'    => $super->id,
            'type'        => 'settings.updated',
            'action'      => 'settings.updated',
            'description' => 'Super admin can see company B entry',
            'severity'    => 'info',
        ]);

        $this->actingAs($super)->get('/report_audit_trail?companyId=' . $companyB)
            ->assertOk()
            ->assertSee('Super admin can see company B entry');
    }
}
