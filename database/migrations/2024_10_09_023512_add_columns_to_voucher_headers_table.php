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
        });
    }
};
