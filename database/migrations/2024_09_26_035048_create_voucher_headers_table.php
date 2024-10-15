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
        Schema::create('voucher_headers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('paket_voucher_id');
            $table->uuid('patient_id');
            $table->date('purchase_date');
            $table->date('expiry_date');
            $table->integer('current_uses')->default(0);
            $table->timestamp('last_reminder_sent_at')->nullable();
            $table->string('status', 50);  // 'active', 'expired', etc.
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('paket_voucher_id')->references('id')->on('package_vouchers')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_headers');
    }
};
