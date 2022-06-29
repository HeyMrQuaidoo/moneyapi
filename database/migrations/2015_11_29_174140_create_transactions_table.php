<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->uuid('id')->primary();
            $table->string('status');
            $table->string('description');
            $table->decimal('amount', 13, 4);
            $table->decimal('opening_balance', 13, 4);
            $table->decimal('closing_balance', 13, 4);
            $table->uuid('cloneable_id');
            $table->string('cloneable_type');
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
        Schema::drop('transactions');
    }
}
