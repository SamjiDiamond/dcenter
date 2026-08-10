<?php

namespace Tests\Feature\Api;

use App\Models\AuditTrail;
use App\Models\BouncerRoleModel;
use App\Models\User;
use Bouncer;

class AuditLogTest extends ApiTestCase
{
    private function createEntry(array $overrides = []): AuditTrail
    {
        return AuditTrail::create(array_merge([
            'company_id'   => 1,
            'admin_id'     => 1,
            'role'         => 'admin',
            'action'       => 'auth.login',
            'type'         => 'auth.login',
            'description'  => 'User logged in',
            'severity'     => 'info',
            'ip_address'   => '127.0.0.1',
            'user_agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36',
        ], $overrides));
    }

    public function testIndexReturnsCompanyScopedAuditEntries()
    {
        $user = $this->makeUser();

        $this->createEntry([
            'company_id' => $user->company_id,
            'admin_id'   => $user->id,
            'role'       => 'admin',
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $response = $this->getJson('/api/audit-logs')
            ->assertStatus(200)
            ->assertJsonPath('status', 1)
            ->assertJsonPath('data.data.0.action', 'auth.login')
            ->assertJsonPath('data.data.0.actor.name', 'Test User')
            ->assertJsonPath('data.data.0.actor.email', $user->email)
            ->assertJsonPath('data.data.0.actor.role', 'admin')
            ->assertJsonPath('data.data.0.description', 'User logged in')
            ->assertJsonPath('data.data.0.severity', 'info')
            ->assertJsonPath('data.data.0.ip_address', '127.0.0.1')
            ->assertJsonPath('data.data.0.browser', 'Chrome 120');

        // Relative timestamp — tolerate the 0/1 second rollover.
        $this->assertMatchesRegularExpression('/\d+ seconds? ago/', $response->json('data.data.0.created_at_human'));
    }

    public function testSubjectResolvesToHumanReadableName()
    {
        $user = $this->makeUser();
        $target = $this->makeUser(['company_id' => $user->company_id]);

        $this->createEntry([
            'company_id'   => $user->company_id,
            'admin_id'     => $user->id,
            'subject_type' => 'User',
            'subject_id'   => $target->id,
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/audit-logs')
            ->assertOk()
            ->assertJsonPath('data.data.0.subject.type', 'User')
            ->assertJsonPath('data.data.0.subject.id', $target->id)
            ->assertJsonPath('data.data.0.subject.name', 'Test User');
    }

    public function testUnknownSubjectResolvesToNullName()
    {
        $user = $this->makeUser();

        $this->createEntry([
            'company_id'   => $user->company_id,
            'admin_id'     => $user->id,
            'subject_type' => 'User',
            'subject_id'   => 999999,
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/audit-logs')
            ->assertOk()
            ->assertJsonPath('data.data.0.subject.type', 'User')
            ->assertJsonPath('data.data.0.subject.id', 999999)
            ->assertJsonPath('data.data.0.subject.name', null);
    }

    public function testSeverityFilter()
    {
        $user = $this->makeUser();

        $this->createEntry([
            'company_id' => $user->company_id,
            'admin_id'   => $user->id,
            'severity'   => 'info',
        ]);
        $this->createEntry([
            'company_id' => $user->company_id,
            'admin_id'   => $user->id,
            'action'     => 'account.delete_requested',
            'type'       => 'account.delete_requested',
            'severity'   => 'critical',
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/audit-logs?severity=critical')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data.data');
    }

    public function testActionFilter()
    {
        $user = $this->makeUser();

        $this->createEntry([
            'company_id' => $user->company_id,
            'admin_id'   => $user->id,
            'action'     => 'auth.login',
            'type'       => 'auth.login',
        ]);
        $this->createEntry([
            'company_id' => $user->company_id,
            'admin_id'   => $user->id,
            'action'     => 'settings.updated',
            'type'       => 'settings.updated',
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/audit-logs?action=settings')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.action', 'settings.updated');
    }

    public function testDateFromFilter()
    {
        $user = $this->makeUser();

        $today = $this->createEntry([
            'company_id' => $user->company_id,
            'admin_id'   => $user->id,
        ]);
        $today->forceFill(['created_at' => now()->subHour()])->save();

        $yesterday = $this->createEntry([
            'company_id' => $user->company_id,
            'admin_id'   => $user->id,
            'action'     => 'settings.updated',
            'type'       => 'settings.updated',
        ]);
        $yesterday->forceFill(['created_at' => now()->subDay()])->save();

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/audit-logs?date_from=' . now()->toDateString())
            ->assertStatus(200)
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.action', 'auth.login');
    }

    public function testPerPageIsCappedAtOneHundred()
    {
        $user = $this->makeUser();

        $this->createEntry(['company_id' => $user->company_id, 'admin_id' => $user->id]);

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/audit-logs?per_page=1000')
            ->assertOk()
            ->assertJsonPath('data.per_page', 100);
    }

    public function testLegacyRowsGetRoleFromCurrentAssignment()
    {
        $user = $this->makeUser();

        // Entry written before the role column existed — role is stored empty.
        $this->createEntry([
            'company_id' => $user->company_id,
            'admin_id'   => $user->id,
            'role'       => null,
        ]);

        // The actor currently holds the finance role.
        Bouncer::useRoleModel(BouncerRoleModel::class);
        $role = Bouncer::role()->firstOrCreate([
            'name'       => 'finance',
            'company_id' => $user->company_id,
        ]);
        $user->assign($role->id);

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/audit-logs')
            ->assertOk()
            ->assertJsonPath('data.data.0.actor.role', 'finance');
    }
}
