<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlTransDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::create('gl_trans_details', function (blueprint $table) {
            $table->id();
            $table->unsignedbiginteger('trans_master_id');
            $table->biginteger('gl_acc_id');
            $table->string('dr_cr');
            $table->double('amount_ccy');
            $table->double('amount_lcy');
            $table->biginteger('created_by');
            $table->timestamps();

            $table->foreign('trans_master_id')
                ->references('id')
                ->on('gl_trans_master')
                ->ondelete('restrict')   // or cascade / set null
                ->onupdate('cascade');
            

        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gl_trans_details');
    }
}
