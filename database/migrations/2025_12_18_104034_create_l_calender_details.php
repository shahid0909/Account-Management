<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLCalenderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_calender_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calender_master_id');
            $table->timestamps();

            $table->foreign('calender_master_id')
                ->references('id')
                ->on('l_calender_master')
                ->onDelete('restrict')   // or cascade / set null
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('l_calender_details');
    }
}
