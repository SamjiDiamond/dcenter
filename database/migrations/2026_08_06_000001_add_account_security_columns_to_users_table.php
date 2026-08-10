<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountSecurityColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'email_2fa_enabled')) {
                $table->boolean('email_2fa_enabled')->default(false)->after('two_factor_secret');
            }
            if (! Schema::hasColumn('users', 'deletion_requested_at')) {
                $table->timestamp('deletion_requested_at')->nullable();
            }
            if (! Schema::hasColumn('users', 'deletion_scheduled_for')) {
                $table->timestamp('deletion_scheduled_for')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_2fa_enabled',
                'deletion_requested_at',
                'deletion_scheduled_for',
            ]);
        });
    }
}
