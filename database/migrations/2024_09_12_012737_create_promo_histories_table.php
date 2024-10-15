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
        Schema::create('promo_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('promo_id'); // FK to Promos
            $table->uuid('user_id'); // FK to Users (or Patients) who used the promo
            $table->string('action'); // E.g., 'used'
            $table->timestamp('used_at')->useCurrent();
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
        Schema::dropIfExists('promo_histories');
    }
};
