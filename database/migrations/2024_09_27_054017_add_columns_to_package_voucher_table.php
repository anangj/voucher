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
        Schema::table('package_vouchers', function (Blueprint $table) {
            $table->string('image')->after('max_sharing');
            $table->decimal('amount', 10, 2)->after('image');
            $table->longText('tnc')->after('amount');
            $table->string('logo_unit')->after('tnc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_vouchers', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('amount');
            $table->dropColumn('tnc');
            $table->dropColumn('logo_unit');
        });
    }
};
