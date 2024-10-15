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
        Schema::create('voucher_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('voucher_header_id');
            $table->string('voucher_no')->nullable();
            $table->mediumText('qr_code')->nullable();
            $table->boolean('is_used')->default(false);
            $table->date('using_date')->nullable();
            $table->boolean('issued_to_family_member')->nullable();
            $table->timestamps();

            $table->foreign('voucher_header_id')->references('id')->on('voucher_headers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_details');
    }
};
