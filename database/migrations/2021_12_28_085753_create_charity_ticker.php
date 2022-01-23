<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCharityTicker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charity_tickers', function (Blueprint $table) {
            $table->id();
            $table->uuid('charity_code')->default(DB::raw('uuid_generate_v4()'));
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('charity_organization_id');
            $table->foreign('charity_organization_id')->references('id')->on('charity_organizations');

            $table->decimal('donation_amount', 10, 2);
            $table->unsignedInteger('tick_frequency')->default(1);
            $table->string('tick_frequency_unit')->default('sec')->comment('seconds, mins, hours and days - {tick_frequency}{tick_frequency_unit}. ex: kdei,mins etc.');
            $table->string('payment_method')->default('stripe');
            $table->tinyInteger('is_subscribed')->nullable()->default(null)->comment('Keep the payment going until stopped');

            $table->timestamp('timer_start')->nullable()->comment('Timer start');
            $table->timestamp('timer_expiry_timestamp')->nullable()->comment('User added expiry timestamp');
            $table->timestamp('timer_completed_at')->nullable()->comment('If user manually stopped the ticker');
            $table->decimal('total_donation_amount', 10, 2)->default(0)->comment('Total donation amount');

            $table->string('charge');

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
        Schema::dropIfExists('charity_tickers');
    }
}
