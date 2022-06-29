<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('accounts', function($table) {
            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade');
        });
        
        Schema::table('geocodes', function($table) {
            $table->foreign('atm_id')
                  ->references('id')
                  ->on('atms')
                  ->onDelete('cascade');
        });
        
        Schema::table('atms', function($table) {
            $table->foreign('language_id')
                  ->references('type')
                  ->on('languages')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
