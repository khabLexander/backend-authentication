<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthenticationPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env('DB_CONNECTION'))->create('phones', function (Blueprint $table) {
            $table->id();
            $table->morphs('phoneable');
            $table->foreignId('operator_id')->nullable()->constrained('authentication.catalogues');
            $table->foreignId('type_id')->nullable()->constrained('authentication.catalogues');
            $table->foreignId('location_id')->nullable()->constrained('authentication.locations');
            $table->string('number');
            $table->boolean('main');
            $table->softDeletes();
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
        Schema::connection(env('DB_CONNECTION'))->dropIfExists('phones');
    }
}
