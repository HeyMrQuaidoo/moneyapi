<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geocodes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('_id')->index();
            $table->uuid('id')->index();
            $table->uuid('atm_id')->references('id')->on('atms');
            $table->decimal('latitude', 18, 12);
            $table->decimal('longitude', 18, 12);
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
        Schema::drop('geocodes');
    }
}
