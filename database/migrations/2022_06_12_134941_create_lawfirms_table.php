<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawfirms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('email');
            $table->string('price')->nullable();
            $table->string('phone_number')->unique();
            $table->string('image')->nullable();
            $table->string('type')->default(0);
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
        Schema::dropIfExists('lawfirms');
    }
}
