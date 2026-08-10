<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * The roles table already carries company_id in existing environments
     * (added out-of-band for multi-company scoping). This migration guarantees
     * the column exists on fresh installs too.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('roles', 'company_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->nullable()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('roles', 'company_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('company_id');
            });
        }
    }
}
