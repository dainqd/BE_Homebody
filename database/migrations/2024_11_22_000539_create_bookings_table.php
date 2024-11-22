<?php

use App\Enums\BookingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references(/**/ 'id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('partner_id');
            $table->foreign('partner_id')->references(/**/ 'id')->on('users')->onDelete('cascade');

            $table->decimal('total_price', 15, 0)->comment('Giá toàn bộ sản phẩm');

            $table->string('time_slot');

            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();

            $table->string('notes')->nullable();

            $table->string('reason_cancel')->nullable();

            $table->string('status')->default(BookingStatus::PENDING);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};