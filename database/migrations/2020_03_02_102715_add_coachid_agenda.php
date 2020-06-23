<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoachidAgenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->integer('coach_id')->unsigned();
            $table->foreign('coach_id')->references('id')->on('user');
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
            $table->dropForeign('agenda_coach_id_foreign');
            $table->dropColumn('coach_id');
        });
    }
}
