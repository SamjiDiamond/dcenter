<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Services\TeamMemberService;
use Illuminate\Database\Seeder;

class TeamRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = app(TeamMemberService::class);

        foreach (Company::all() as $company) {
            foreach (TeamMemberService::ROLES as $roleName) {
                $role = $service->resolveRole($roleName, $company->id);
                // resolveRole() only seeds brand-new roles; this explicit call
                // backfills default abilities on roles that predate the feature.
                $service->ensureDefaultAbilities($role);
            }
        }

        $this->command->info('Team roles (admin, finance, viewer) with default abilities ensured for all companies.');
    }
}
