<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_process', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_master_id');
            $table->integer('module_id');
            $table->unsignedBigInteger('auth_key_id');
            $table->unsignedBigInteger('auth_user_id');
            $table->unsignedBigInteger('auth_step');
            $table->string('approval_status')->default('P');
            $table->string('process_yn');
            $table->timestamps();
            $table->foreign('auth_key_id')
                ->references('id')
                ->on('auth_key')
                ->ondelete('restrict')   // or cascade / set null
                ->onupdate('cascade');
            $table->foreign('auth_user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('auth_process');
    }
}
