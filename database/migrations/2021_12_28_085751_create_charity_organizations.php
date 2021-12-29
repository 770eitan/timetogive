<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharityOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charity_organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('isAddedByUser')->comment('1->Yes,0->No')->default(0);
            $table->tinyInteger('status')->comment('1->Active,0->Deactive')->default(1);
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
        Schema::dropIfExists('charity_organizations');
    }
}
