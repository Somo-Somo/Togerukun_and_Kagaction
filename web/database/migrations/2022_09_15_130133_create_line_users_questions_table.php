<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineUsersQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_users_questions', function (Blueprint $table) {
            $table->id();
            $table->string('line_user_id')->unique()->nullable(false);
            $table->string('question_number');
            $table->string('parent_uuid')->nullable();
            $table->timestamps();

            $table->foreign('line_user_id')->references('line_user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_users_questions');
    }
}
