<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddUuidToUsersTable extends Migration
{
    /**
     * Add a public-facing UUID to users so the numeric auto-increment id is no
     * longer used in web URLs or shown to users.
     */
    public function up()
    {
        if (! Schema::hasColumn('users', 'uuid')) {
            Schema::table('users', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // Backfill existing rows with a fresh UUID each.
        DB::table('users')->whereNull('uuid')->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                DB::table('users')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
            }
        });

        // Enforce uniqueness.
        Schema::table('users', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'uuid')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('uuid');
            });
        }
    }
}
