<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToUsersTable extends Migration
{
    /**
     * The app has always referenced users.status ('active'/'disable') — the
     * Enable/Disable buttons, the admin/user list views, and the API login
     * guard all read and write it — but the column never existed in the
     * database. Add it with a sensible default so existing accounts are
     * treated as active.
     */
    public function up()
    {
        if (! Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('status', 20)->default('active')->after('account_type');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
