<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voucher_histories', function (Blueprint $table) {
            $table->string('voucher_no')->nullable()->after('voucher_detail_id');
            $table->string('bill_no')->nullable()->after('action');
            $table->date('bill_date')->nullable()->after('bill_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucher_histories', function (Blueprint $table) {
            $table->dropColumn('voucher_no');
            $table->dropColumn('bill_no');
            $table->dropColumn('bill_date');
        });
    }
};
