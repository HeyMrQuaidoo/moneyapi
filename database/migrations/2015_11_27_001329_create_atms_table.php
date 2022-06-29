<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atms', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            // $table->increments('_id')->index();
            $table->uuid('id')->primary();
            $table->string('language_id')->references('type')->on('languages');
            $table->string('alias');
            $table->decimal('atm_balance', 13, 4);
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
        Schema::table('atms', function ($table) {
            // $table->dropForeign('atms_language_id_foreign');
        });
        
        Schema::dropIfExists('atms');
        Schema::dropIfExists('languages');
    }
}
