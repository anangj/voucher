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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // $table->uuid('id')->primary();
            // $table->string('voucher_no')->unique();
            // $table->mediumText('qr_code');
            // $table->uuid('paket_voucher_id');  // Foreign key
            // $table->uuid('patient_id')->nullable();  // Foreign key
            // $table->boolean('issued_to_family_member')->default(false);
            // $table->date('purchase_date')->nullable();
            // $table->date('expiry_date');
            // $table->string('status');  // active, expired, redeemed
            // $table->integer('max_uses');
            // $table->integer('current_uses')->default(0);
            // $table->timestamp('last_reminder_sent_at')->nullable();
            // $table->boolean('is_expired')->nullable();
            // $table->timestamps();

            // // Foreign key constraints
            // $table->foreign('paket_voucher_id')->references('id')->on('package_vouchers')->onDelete('cascade');
            // $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
