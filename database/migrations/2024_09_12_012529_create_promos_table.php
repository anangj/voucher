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
        Schema::create('promos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('promo_code')->unique(); // Code used to apply the promo
            $table->enum('discount_type', ['percentage', 'fixed']); // Discount type
            $table->decimal('discount_value', 8, 2); // Percentage or fixed amount
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_uses')->default(1); // Maximum number of uses for this promo
            $table->integer('current_uses')->default(0); // How many times it has been used
            $table->uuid('paket_voucher_id')->nullable(); // If promo is linked to a specific paket voucher
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
        Schema::dropIfExists('promos');
    }
};
