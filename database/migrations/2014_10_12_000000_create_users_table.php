<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('picture')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('credentials')->nullable();
            $table->string('location')->nullable();
            $table->text('about')->nullable();
            $table->integer('points')->default(0);
            $table->tinyInteger('is_admin')->default(0);
            /**
             * User statuses:
             * 0 - disabled (by user, when disabling his account)
             * 1 - active (by default)
             * 2 - banned (by admin, when restrict access to website)
             */
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('email_notifications')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
