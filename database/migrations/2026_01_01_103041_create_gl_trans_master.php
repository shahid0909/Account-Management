<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlTransMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::create('gl_trans_master', function (blueprint $table) {
            $table->id();
            $table->integer('module_id');
            $table->integer('function_id');
            $table->integer('fiscal_year_id');
            $table->integer('trans_period_id');
            $table->string('trans_batch_id');
            $table->date('trans_date');
            $table->date('document_date');
            $table->string('document_no');
            $table->string('document_ref')->nullable();
            $table->text('narration');
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->integer('challan_type_id')->nullable();
            $table->string('challan_no')->nullable();
            $table->date('challan_date')->nullable();
            $table->biginteger('party_account_id')->nullable();
            $table->biginteger('gl_subsidiary_id')->nullable();
            $table->biginteger('vendor_id')->nullable();
            $table->biginteger('customer_id')->nullable();
            $table->string('authorize_status')->default('P');
            $table->biginteger('authorize_by')->nullable();
            $table->date('authorize_date')->nullable();
            $table->biginteger('created_by');
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
        Schema::dropIfExists('gl_trans_master');
    }
}
