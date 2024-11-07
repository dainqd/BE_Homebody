<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('name_en');
            $table->string('name_cn');
            $table->string('name_vi');

            $table->text('description_en')->nullable();
            $table->text('description_cn')->nullable();
            $table->text('description_vi')->nullable();

            $table->decimal('max_discount_en', 15, 0)->nullable();
            $table->decimal('max_discount_cn', 15, 0)->nullable();
            $table->decimal('max_discount_vi', 15, 0)->nullable();

            $table->decimal('min_total_en', 15, 0)->default(0);
            $table->decimal('min_total_cn', 15, 0)->default(0);
            $table->decimal('min_total_vi', 15, 0)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
        });
    }
};
