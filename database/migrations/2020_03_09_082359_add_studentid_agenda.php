<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentidAgenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->integer('student_id')->nullable()->unsigned();
            $table->foreign('student_id')->references('id')->on('student');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropForeign('agenda_student_id_foreign');
            $table->dropColumn('student_id');
        });
    }
}
