<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('belongs')->nullable();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->longText('description');
            $table->string('location')->nullable();
            $table->string('picture');
            $table->integer('role')->default('0');
            $table->string('price')->nullable();
            $table->string('gene_tokens')->nullable();
            $table->boolean('isVerified')->default(false);
            $table->boolean('funds')->default('0');
            $table->boolean('reviews')->default('0');
            $table->boolean('rooms')->default('0');
            $table->string('password');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
