<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLCalenderDetailsActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_calender_details_active', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calender_master_id');
            $table->unsignedBigInteger('calender_details_id');
            $table->date('posting_period_beg_date');
            $table->date('posting_period_end_date');
            $table->string('posting_period_display_name');
            $table->string('posting_period_status');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('calender_master_id')
                ->references('id')
                ->on('l_calender_master')
                ->onDelete('restrict')   // or cascade / set null
                ->onUpdate('cascade');

            $table->foreign('calender_details_id')
                ->references('id')
                ->on('l_calender_details')
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
        Schema::dropIfExists('l_calender_details_active');
    }
}
