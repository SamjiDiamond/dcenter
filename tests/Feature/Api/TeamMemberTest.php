<?php

namespace Tests\Feature\Api;

use App\Models\BouncerRoleModel;
use App\Models\User;
use Bouncer;

class TeamMemberTest extends ApiTestCase
{
    private function assignRole(User $user, $roleName)
    {
        Bouncer::useRoleModel(BouncerRoleModel::class);
        $role = Bouncer::role()->firstOrCreate([
            'name'       => $roleName,
            'company_id' => $user->company_id,
        ]);
        $user->assign($role->id);
    }

    public function testListReturnsCompanyMembersWithRoles()
    {
        $user = $this->makeUser();
        $this->assignRole($user, 'admin');
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/team-members')
            ->assertStatus(200)
            ->assertJsonPath('status', 1)
            ->assertJsonStructure(['data' => [['id', 'email', 'role']], 'roles']);
    }

    public function testInviteCreatesUserAndAssignsRole()
    {
        $user = $this->makeUser();
        $this->assignRole($user, 'admin');
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/team-members/invite', [
            'email' => 'newmember' . uniqid() . '@example.com',
            'role'  => 'finance',
        ])
            ->assertStatus(200)
            ->assertJsonPath('status', 1);

        $this->assertDatabaseHas('users', [
            'company_id' => $user->company_id,
        ]);
        $this->assertDatabaseHas('assigned_roles', [
            'entity_id' => User::where('email', 'LIKE', 'newmember%@example.com')->first()->id,
        ]);
    }

    public function testNonAdminCannotInvite()
    {
        $user = $this->makeUser();
        $this->assignRole($user, 'viewer');
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/team-members/invite', [
            'email' => 'someone' . uniqid() . '@example.com',
            'role'  => 'finance',
        ])
            ->assertStatus(200)
            ->assertJsonPath('status', 0);
    }

    public function testInviteRejectsUnknownRole()
    {
        $user = $this->makeUser();
        $this->assignRole($user, 'admin');
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/team-members/invite', [
            'email' => 'someone' . uniqid() . '@example.com',
            'role'  => 'superadmin',
        ])
            ->assertStatus(422);
    }

    public function testInviteRefusesEmailFromAnotherCompany()
    {
        $admin = $this->makeUser();
        $this->assignRole($admin, 'admin');

        $other = $this->makeUser(); // belongs to a different company

        \Laravel\Sanctum\Sanctum::actingAs($admin);

        $this->postJson('/api/team-members/invite', [
            'email' => $other->email,
            'role'  => 'finance',
        ])
            ->assertStatus(200)
            ->assertJsonPath('status', 0);

        $this->assertNotEquals($admin->company_id, $other->fresh()->company_id);
    }

    public function testCannotRemoveSelf()
    {
        $user = $this->makeUser();
        $this->assignRole($user, 'admin');
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->deleteJson('/api/team-members/' . $user->id)
            ->assertStatus(200)
            ->assertJsonPath('status', 0);
    }

    public function testCanRemoveAnotherMember()
    {
        $user = $this->makeUser();
        $this->assignRole($user, 'admin');
        $member = $this->makeUser(['company_id' => $user->company_id]);
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->deleteJson('/api/team-members/' . $member->id)
            ->assertStatus(200)
            ->assertJsonPath('status', 1);

        $this->assertEquals(0, (int) $member->fresh()->company_id);
        $this->assertDatabaseMissing('assigned_roles', ['entity_id' => $member->id]);
    }
}
