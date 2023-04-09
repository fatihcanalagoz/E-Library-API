<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('depart_id')->nullable();
           
            $table->string('name');
            $table->string('author');
            $table->string('publisher');
            $table->string('category');
            $table->integer('page');
            $table->integer('isbn');
            $table->timestamp('borrowed_at')->nullable();
            $table->timestamps();
     


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('depart_id')->references('id')->on('departs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
