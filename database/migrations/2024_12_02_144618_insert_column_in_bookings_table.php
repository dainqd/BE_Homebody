<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('name')->comment('Khách hàng')->nullable();
            $table->string('email')->comment('Email khách hàng')->nullable();
            $table->string('phone')->comment('SĐT khách hàng')->nullable();
            $table->string('time_slot')->nullable()->comment('Khung giờ (ví dụ: "10:00-11:00")')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
