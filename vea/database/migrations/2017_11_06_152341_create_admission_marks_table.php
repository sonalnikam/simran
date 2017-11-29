<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmissionMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_marks', function (Blueprint $table) {
            $table->primary(['admission_id','mark_id']);
            $table->integer('admission_id')->unsigned()->index();
            $table->foreign('admission_id')->references('id')->on('admission')->onDelete('cascade');
            $table->integer('mark_id')->unsigned()->index();
            $table->foreign('mark_id')->references('id')->on('marks')->onDelete('cascade');
            $table->string('marks_obtained');
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
        Schema::dropIfExists('admission_marks');
    }
}
