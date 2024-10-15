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
        Schema::create('family_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id');  // Foreign key
            $table->string('name');
            $table->date('birthday')->nullable();
            $table->string('relationship')->nullable();  // e.g., father, mother
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            // Foreign key constraint
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
        Schema::dropIfExists('family_members');
    }
};
