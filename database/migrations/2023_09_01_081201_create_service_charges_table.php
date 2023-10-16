<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_charges', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // A descriptive name for the service charge
            $table->decimal('amount', 10, 2); // The amount of the service charge (assuming two decimal places)
            $table->unsignedBigInteger('user_id')->nullable(); // User or company associated with the service charge (nullable for system-wide charges)
            $table->unsignedBigInteger('transaction_id')->nullable(); // Transaction associated with the service charge (nullable for system-wide charges)
            $table->date('charge_date');
            $table->timestamps();
            
            // Define foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); // Adjust 'users' to your actual users table name
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_charges');
    }
}
