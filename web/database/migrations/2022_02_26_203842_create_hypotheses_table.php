<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHypothesesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('hypotheses', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('uuid')->unique();
        //     $table->string('parent_uuid');
        //     $table->string('status');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hypotheses');
    }
}
