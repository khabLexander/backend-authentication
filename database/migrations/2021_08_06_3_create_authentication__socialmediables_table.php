<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthenticationSocialmediablesTable extends Migration
{
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('socialmediables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socialmedia_id')->constrained('authentication.socialmedia');
            $table->morphs('socialmediables');
            $table->string('user');
            $table->string('url');
            $table->timestamps();
            $table->unique(['socialmedia_id', 'user']);
        });
    }

    public function down()
    {
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('socialmediables');
    }
}
