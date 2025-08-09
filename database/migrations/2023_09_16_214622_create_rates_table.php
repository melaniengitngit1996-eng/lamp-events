<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('event_id');
            $table->string('category');
            $table->string('attending_option');
            $table->string('registration_type')->default('Member');
            $table->string('description');
            $table->decimal('rate', 9, 3);
            $table->decimal('can_book_rate', 9, 3);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
