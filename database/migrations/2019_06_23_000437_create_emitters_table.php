<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emitters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('auth_token')->nullable();
            $table->string('certificate_file_name');
            $table->string('certificate_password');
            $table->integer('user_id');
            $table->integer('contributor_id')->nullable();
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
        Schema::dropIfExists('emitters');
    }
}
