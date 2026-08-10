<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ConvertCompanyTableToInnodb extends Migration
{
    /**
     * The company table was created as MyISAM (no transaction support), which
     * breaks DB::beginTransaction() flows that write to it and prevents test
     * rollbacks. Converting to InnoDB preserves all data and is non-destructive.
     *
     * InnoDB rejects the MyISAM-era "0000-00-00 00:00:00" values/defaults on
     * updated_at (and strict mode refuses to even touch them), so sql_mode is
     * relaxed for this migration while those values are normalized.
     *
     * @return void
     */
    public function up()
    {
        $mode = DB::selectOne('SELECT @@SESSION.sql_mode AS mode');

        try {
            DB::statement("SET SESSION sql_mode = ''");

            DB::statement("UPDATE `company` SET `updated_at` = CURRENT_TIMESTAMP WHERE `updated_at` = '0000-00-00 00:00:00'");
            DB::statement('ALTER TABLE `company` MODIFY `updated_at` TIMESTAMP NULL DEFAULT NULL');
            DB::statement('ALTER TABLE `company` ENGINE = InnoDB');
        } finally {
            if ($mode && $mode->mode !== null) {
                DB::statement("SET SESSION sql_mode = '" . addslashes($mode->mode) . "'");
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `company` ENGINE = MyISAM');
    }
}
