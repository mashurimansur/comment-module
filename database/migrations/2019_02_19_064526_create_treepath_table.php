<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreepathTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treepath', function (Blueprint $table) {
            $table->integer('ancestor')->unsigned();
            $table->integer('offspring')->unsigned();
            $table->integer('depth');
            $table->foreign('ancestor')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('offspring')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treepath');
    }
}
