<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_space')->default(0);
            $table->string('title');
            $table->string('picture')->nullable();
            $table->string('slug')->unique();
            $table->text('body');
            $table->unsignedInteger('followers_counted')->default(0);
            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('topics');
    }
}
