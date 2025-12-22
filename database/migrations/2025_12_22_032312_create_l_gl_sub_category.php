<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLGlSubCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_gl_sub_category', function (Blueprint $table) {
            $table->id();
            $table->string('gl_sub_category_name');
            $table->unsignedBigInteger('gl_category_id');
            $table->integer('created_by')->nullable();
            $table->timestamps();

            $table->foreign('gl_category_id')
                ->references('id')
                ->on('l_gl_category')
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
        Schema::dropIfExists('l_gl_sub_category');
    }
}
