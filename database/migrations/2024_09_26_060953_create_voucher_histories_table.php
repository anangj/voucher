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
        Schema::create('voucher_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('voucher_detail_id');  // Foreign key
            $table->string('action');  // e.g., issued, redeemed, extended
            $table->string('performed_by');  // patient, superadmin
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('voucher_detail_id')->references('id')->on('voucher_details')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_histories');
    }
};
