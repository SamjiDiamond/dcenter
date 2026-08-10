<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create a user (and a company to belong to) for testing.
     */
    protected function makeUser(array $attributes = []): User
    {
        $companyId = DB::table('company')->insertGetId([
            'name'         => 'Test Company',
            'email'        => 'company' . uniqid() . '@example.com',
            'bank_code'    => '000',
            'bank_account' => '0000000000',
        ]);

        return User::create(array_merge([
            'email'        => 'user' . uniqid() . '@example.com',
            'password'     => Hash::make('password123'),
            'first_name'   => 'Test',
            'last_name'    => 'User',
            'phoneno'      => '0' . random_int(1000000000, 9999999999),
            'company_id'   => $companyId,
            'status'       => 'active',
            'account_type' => 'admin',
        ], $attributes));
    }
}
