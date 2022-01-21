<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RevampCharityTickers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('charity_tickers')) {
            Schema::table('charity_tickers', function (Blueprint $table) {
                $table->string('charge');

                $table->renameColumn('hasSubscribed', 'is_subscribed');
                $table->tinyInteger('is_subscribed')->comment('Keep the payment going until stopped')->nullable()->default(null)->change();

                $table->string('tick_frequency_unit')->default('sec')->comment('seconds, mins, hours and days - {tick_frequency}{tick_frequency_unit}')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('charity_tickers')) {
            Schema::table('charity_tickers', function (Blueprint $table) {
                $table->string('tick_frequency_unit')->default('kdei')->comment('Minimum 3 seconds-kdei, mins, hours and days - {tick_frequency}{tick_frequency_unit}. ex: kdei,mins etc.')->change();

                $table->tinyInteger('is_subscribed')->comment('Keep the payment going until stopped')->nullable(false)->default(1)->change();
                $table->renameColumn('is_subscribed', 'hasSubscribed');
            });
        }
    }
}
