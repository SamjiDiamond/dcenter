<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixTransactionsTableSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * The transactions table was created out-of-band without AUTO_INCREMENT on
     * id (and no primary key), which makes every Transaction::create() fail
     * with "Field 'id' doesn't have a default value". The app also never writes
     * wallet_id or reference_id on several posting flows, so both are relaxed
     * to NULL to match reality. All statements are guarded/no-op safe.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('transactions')) {
            return;
        }

        $idColumn = DB::selectOne("SHOW COLUMNS FROM transactions WHERE Field = 'id'");

        if ($idColumn && stripos($idColumn->Extra, 'auto_increment') === false) {
            // Note: SHOW statements don't reliably accept PDO bind placeholders.
            $hasPrimary = count(DB::select("SHOW INDEX FROM transactions WHERE Key_name = 'PRIMARY'")) > 0;

            DB::statement(
                'ALTER TABLE transactions MODIFY id INT NOT NULL AUTO_INCREMENT' . ($hasPrimary ? '' : ', ADD PRIMARY KEY (id)')
            );
        }

        DB::statement('ALTER TABLE transactions MODIFY wallet_id INT NULL');
        DB::statement('ALTER TABLE transactions MODIFY reference_id varchar(80) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Not reversible safely: the table is out-of-band and may already hold
        // rows with NULL wallet_id/reference_id (and an auto-increment id).
    }
}
