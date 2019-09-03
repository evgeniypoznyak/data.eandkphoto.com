<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->increments('id');
            $table->text('src'); // maybe binary string
            $table->string('header')->nullable();
            $table->string('headerAnimation')->default('default');
            $table->string('paragraph')->nullable();
            $table->string('paragraphAnimation')->default('default');
            $table->string('button')->nullable();
            $table->string('buttonAnimation')->default('default');
            $table->string('pictureAnimation')->default('default');
            $table->timestamps();
        });

        Schema::create('picture_slider', function (Blueprint $table) {
            $table->integer('picture_id');
            $table->integer('slider_id');
            $table->primary(['picture_id', 'slider_id' ]);
            //$table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pictures');
        Schema::dropIfExists('picture_slider');
    }
}
