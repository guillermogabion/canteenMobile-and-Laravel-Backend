<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionBuildersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_builders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exam_builders')->onDelete('cascade');
            $table->string('question_title');
            $table->string('question_body');
            $table->enum('question_type', ['boolean', 'enumeration', 'multiple']);
            $table->string('label1')->nullable();
            $table->string('label2')->nullable();
            $table->string('label3')->nullable();
            $table->string('correct');
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
        Schema::dropIfExists('question_builders');
    }
}
