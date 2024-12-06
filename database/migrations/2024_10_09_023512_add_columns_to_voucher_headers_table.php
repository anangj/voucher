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
        Schema::table('voucher_headers', function (Blueprint $table) {
            $table->string('voucher_header_no')->nullable()->after('patient_id');
            $table->boolean('isExtend')->default(false)->after('status');
            $table->string('reason')->after('isExtend')->nullable();
            $table->boolean('reminder_1')->default(0)->after('current_uses');
            $table->boolean('reminder_2')->default(0)->after('reminder_1');
            $table->boolean('reminder_3')->default(0)->after('reminder_2');
            $table->string('qr_code_header')->after('voucher_header_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucher_headers', function (Blueprint $table) {
            $table->dropColumn('voucher_header_no');
            $table->dropColumn('isExtend');
            $table->dropColumn('reason');
            $table->dropColumn('reminder_1');
            $table->dropColumn('reminder_2');
            $table->dropColumn('reminder_3');
            $table->dropColumn('qr_code_header');
        });
    }
};
