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

            $table->unsignedBigInteger('user_id')->comment('ID của người dùng đặt lịch (khách hàng)');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('partner_id')->comment('ID của đối tác hoặc nhân viên thực hiện');
            $table->foreign('partner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->decimal('total_price', 15, 0)->comment('Giá toàn bộ sản phẩm hoặc dịch vụ đã đặt');

            $table->string('time_slot')->comment('Khung giờ đặt lịch (ví dụ: "10:00-11:00")');

            $table->timestamp('check_in')->nullable()->comment('Thời gian khách đến check-in');
            $table->timestamp('check_out')->nullable()->comment('Thời gian khách check-out');

            $table->string('notes')->nullable()->comment('Ghi chú từ khách hàng hoặc nhân viên');

            $table->string('reason_cancel')->nullable()->comment('Lý do hủy lịch hẹn');

            $table->string('status')->default(BookingStatus::PENDING)
                ->comment('Trạng thái lịch hẹn (Pending, Approved, Canceled, etc.)');

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
