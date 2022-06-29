<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauth2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
		Schema::create('oauth_clients', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->string('client_id', 80)->primary();
            $table->string('client_secret', 80);
            $table->string('grant_types', 80)->nullable();
            $table->string('redirect_uri', 2000);
            $table->string('scope', 100)->nullable();
            $table->string('user_id', 80)->nullable();
        });
        
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->string('access_token', 40)->primary();
            $table->string('client_id', 80);
            $table->string('user_id', 255)->nullable();
            $table->timestamp('expires');
            $table->string('scope', 2000)->nullable();
        });
        
        Schema::create('oauth_authorization_codes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->string('authorization_code', 40)->primary();
            $table->string('client_id', 80);
            $table->string('user_id', 255)->nullable();
            $table->string('redirect_uri', 2000)->nullable();
            $table->timestamp('expires');
            $table->string('scope', 2000)->nullable();
        });
        
        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->string('refresh_token', 40)->primary();
            $table->string('client_id', 80);
            $table->string('user_id', 255)->nullable();
            $table->timestamp('expires');
            $table->string('scope', 2000)->nullable();
        });
        
        Schema::create('oauth_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->string('username', 255)->primary();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('password', 2000)->nullable();
        });
        
        Schema::create('oauth_scopes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->text('scope', 255)->nullable();
            $table->tinyInteger('is_default')->nullable();
        });
        
        Schema::create('oauth_jwt', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->string('client_id', 80)->primary();
            $table->string('subject', 80)->nullable();
            $table->string('public_key', 2000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oauth_clients');
        Schema::drop('oauth_access_tokens');
        Schema::drop('oauth_authorization_codes');
        Schema::drop('oauth_refresh_tokens');
        Schema::drop('oauth_users');
        Schema::drop('oauth_jwt');
        Schema::drop('oauth_scopes');
    }
}
