<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlAccMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gl_acc_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedbiginteger('gl_coa_id');
            $table->double('regular_opening_ccy')->default(0);
            $table->double('regular_opening_lcy')->default(0);
            $table->double('regular_dr_amount_ccy')->default(0);
            $table->double('regular_dr_amount_lcy')->default(0);
            $table->double('regular_cr_amount_ccy')->default(0);
            $table->double('regular_cr_amount_lcy')->default(0);
            $table->double('regular_closing_ccy')->default(0);
            $table->double('regular_closing_lcy')->default(0);
            $table->double('current_balance_ccy')->default(0);
            $table->double('current_balance_lcy')->default(0);
            $table->double('special_dr_amount_ccy')->default(0);
            $table->double('special_dr_amount_lcy')->default(0);
            $table->double('ledger_closing_ccy')->default(0);
            $table->double('ledger_closing_lcy')->default(0);
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->foreign('gl_coa_id')
                ->references('id')
                ->on('gl_coa')
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
        Schema::dropIfExists('gl_acc_master');
    }
}
