<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorize_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auth_key_id');
            $table->unsignedBigInteger('auth_user_id');
            $table->unsignedBigInteger('auth_step');

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
        Schema::dropIfExists('authorize_user');
    }
}
