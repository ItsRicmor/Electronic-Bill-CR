<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tributors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100);
            $table->string('trade_name', 100);
            $table->string('email',100);
            $table->string('id_type', 2);
            $table->string('id_number', 15);
            $table->integer('address_id')->unsigned();
            $table->string('code_country')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fax_number')->nullable();
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
        Schema::dropIfExists('tributors');
    }
}
