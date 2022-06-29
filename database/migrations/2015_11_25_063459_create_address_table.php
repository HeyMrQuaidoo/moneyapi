<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->uuid('id')->primary();
            $table->string('street_name');
            $table->string('street_number');
            $table->string('city');
            $table->string('state');
            $table->integer('zip');
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
        Schema::drop('address');
    }
}
