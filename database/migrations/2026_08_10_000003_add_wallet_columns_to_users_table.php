<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletColumnsToUsersTable extends Migration
{
    /**
     * The money-balance columns the wallet flows write to. They were created
     * out-of-band in production but are missing on fresh/dev schemas, which
     * breaks fund_wallet, charge_customer, airtime, recharge and withdrawals.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'wallet')) {
                $table->decimal('wallet', 15, 2)->default(0);
            }

            if (! Schema::hasColumn('users', 'bonus')) {
                $table->decimal('bonus', 15, 2)->default(0);
            }

            if (! Schema::hasColumn('users', 'agent_commision')) {
                $table->decimal('agent_commision', 15, 2)->default(0);
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
        if (! Schema::hasTable('users')) {
            return;
        }

        foreach (['wallet', 'bonus', 'agent_commision'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
