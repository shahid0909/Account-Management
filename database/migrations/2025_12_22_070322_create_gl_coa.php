<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlCoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gl_coa', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gl_acc_id');
            $table->string('gl_acc_name');
            $table->string('gl_acc_code');
            $table->integer('gl_acc_level');
            $table->string('currency')->default('BDT');
            $table->string('economic_code')->nullable();
            $table->string('gl_dr_cr_flag');
            $table->unsignedBigInteger('gl_type_id');
            $table->string('postable_yn');
            $table->bigInteger('gl_parent_id')->nullable();
            $table->string('active_yn')->default('Y');
            $table->integer('created_by')->nullable();
            $table->timestamps();

            $table->foreign('gl_type_id')
                ->references('id')
                ->on('l_gl_type')
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
        Schema::dropIfExists('gl_coa');
    }
}
