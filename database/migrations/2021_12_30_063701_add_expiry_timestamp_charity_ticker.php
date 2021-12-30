<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiryTimestampCharityTicker extends Migration
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
                $table->timestamp('timer_start')->nullable()->before('created_at')->comment('Timer start');
                $table->timestamp('timer_expiry_timestamp')->nullable()->after('timer_start')->comment('User added expiry timestamp');
                $table->timestamp('timer_completed_at')->nullable()->after('timer_expiry_timestamp')->comment('If user manually stopped the ticker');
                $table->decimal('total_donation_amount', 10, 2)->default(0)->after('timer_completed_at')->comment('Total donation amount');
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
                $table->dropColumn('timer_start');
                $table->dropColumn('timer_expiry_timestamp');
                $table->dropColumn('timer_completed_at');
                $table->dropColumn('total_donation_amount');
            });
        }
    }
}
