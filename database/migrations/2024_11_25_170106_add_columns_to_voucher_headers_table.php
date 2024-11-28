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
            $table->dropColumn('qr_code_header');
        });
    }
};
