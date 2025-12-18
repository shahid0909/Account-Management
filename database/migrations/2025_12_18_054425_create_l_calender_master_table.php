<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLCalenderMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_calender_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fiscal_period_id');
            $table->integer('fiscal_beg_year');
            $table->integer('fiscal_end_year');
            $table->string('fiscal_year');
            $table->string('calender_status')->default('I');
            $table->string('deleted_yn')->default('N');
            $table->integer('created_by')->nullable();
            $table->timestamps();

            $table->foreign('fiscal_period_id')
                ->references('id')
                ->on('l_fiscal_period')
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
        Schema::dropIfExists('l_calender_master');
    }
}
