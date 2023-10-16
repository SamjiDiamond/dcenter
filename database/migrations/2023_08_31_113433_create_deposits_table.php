<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('InitialDeposit', 10, 2);
            $table->enum('LodgementType', ['payment system', 'card', 'bank transfer']);
            $table->decimal('Amount', 10, 2);
            $table->decimal('Balance', 10, 2);
            $table->string('Phone')->nullable();
            $table->string('Address')->nullable();
            $table->string('transaction_id')->nullable(); 
            $table->dateTime('DepositDate');
            $table->string('Action')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}
