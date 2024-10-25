<?php

use App\Enums\ServiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_cn')->nullable();
            $table->string('name_vi')->nullable();

            $table->longText('description')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_cn')->nullable();
            $table->longText('description_vi')->nullable();

            $table->decimal('price', 10, 2)->nullable()->comment('Giá dịch vụ');
            $table->decimal('price_en', 10, 2)->nullable()->comment('Giá dịch vụ');
            $table->decimal('price_cn', 10, 2)->nullable()->comment('Giá dịch vụ');
            $table->decimal('price_vi', 10, 2)->nullable()->comment('Giá dịch vụ');

            $table->decimal('discount_price', 10, 2)->nullable()->comment('Giá dịch vụ ưu đãi');
            $table->decimal('discount_price_en', 10, 2)->nullable()->comment('Giá dịch vụ ưu đãi');
            $table->decimal('discount_price_cn', 10, 2)->nullable()->comment('Giá dịch vụ ưu đãi');
            $table->decimal('discount_price_vi', 10, 2)->nullable()->comment('Giá dịch vụ ưu đãi');

            $table->integer('time_execution')->comment('Thời gian thực hiện(phút)')->nullable();

            $table->integer('display_priority')->default(0)->comment('Hiển thị mức độ ưu tiên (cao hơn có nghĩa là mức độ ưu tiên cao hơn)'); // Hiển thị mức độ ưu tiên (cao hơn có nghĩa là mức độ ưu tiên cao hơn)

            $table->text('usage_conditions')->nullable()->comment('Điều kiện sử dụng dịch vụ (nếu có)'); // Điều kiện sử dụng dịch vụ (nếu có)
            $table->text('usage_conditions_en')->nullable()->comment('Điều kiện sử dụng dịch vụ (nếu có)'); // Điều kiện sử dụng dịch vụ (nếu có)
            $table->text('usage_conditions_cn')->nullable()->comment('Điều kiện sử dụng dịch vụ (nếu có)'); // Điều kiện sử dụng dịch vụ (nếu có)
            $table->text('usage_conditions_vi')->nullable()->comment('Điều kiện sử dụng dịch vụ (nếu có)'); // Điều kiện sử dụng dịch vụ (nếu có)

            $table->integer('number_of_sessions')->default(0)->comment('Số lượng phiên hoặc số lần dịch vụ đã được thực hiện'); // Số lượng phiên hoặc số lần dịch vụ đã được thực hiện

            $table->decimal('average_rating', 3, 2)->comment('Đánh giá trung bình của khách hàng (từ 1 đến 5 sao)')->default(0); // Đánh giá trung bình của khách hàng (từ 1 đến 5 sao)

            $table->timestamp('promotion_start')->comment('Ngày bắt đầu chương trình khuyến mãi (nếu có)')->nullable(); // Ngày bắt đầu chương trình khuyến mãi (nếu có)
            $table->timestamp('promotion_end')->comment('Ngày kết thúc chương trình khuyến mại (nếu có)')->nullable(); // Ngày kết thúc chương trình khuyến mại (nếu có)

            $table->string('thumbnail')->nullable();
            $table->longText('gallery')->nullable();

            $table->string('status')->default(ServiceStatus::ACTIVE);

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references(/**/ 'id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references(/**/ 'id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
