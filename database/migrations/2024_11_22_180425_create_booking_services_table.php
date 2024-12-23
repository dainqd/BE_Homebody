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
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')->references(/**/ 'id')->on('bookings')->onDelete('cascade');

            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

            $table->integer('quantity')->nullable()->default(1);

            $table->decimal('price', 15, 0)->comment('Giá sản phẩm');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};
