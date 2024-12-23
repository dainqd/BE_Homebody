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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')->references(/**/ 'id')->on('coupons')->onDelete('cascade');

            $table->decimal('discount_price', 15, 0)->comment('Giá ưu đãi sản phẩm hoặc dịch vụ đã đặt');
            $table->decimal('vat', 15, 0)->default(0)->nullable()->comment('Thuế VAT');

            $table->char('homemade')->default('N')->nullable()->comment('Làm tại nhà');
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
