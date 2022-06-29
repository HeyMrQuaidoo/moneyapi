<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('_id')->index();
            $table->uuid('id')->index();
            $table->uuid('client_id')->references('id')->on('clients');
            $table->string('type');
            $table->string('alias');
            $table->decimal('balance', 13, 4);
            $table->string('account_number');
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
        Schema::dropIfExists('accounts');
    }
}
