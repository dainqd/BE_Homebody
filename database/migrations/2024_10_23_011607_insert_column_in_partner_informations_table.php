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
        Schema::table('partner_informations', function (Blueprint $table) {
            $table->string('province_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('commune_id')->nullable();

            $table->string('tax_code')->comment("mã số thuế")->nullable();
            $table->string('passport')->comment('hộ chiếu')->nullable();

            $table->string('time_working')->nullable()->comment('Thời gian làm việc (từ giờ nào - tới giờ nào)');
            $table->string('day_working')->nullable()->comment('Chọn ngày làm việc');

            $table->string('specialty')->nullable()->comment('Chuyên môn');
            $table->string('specialty_en')->nullable()->comment('Chuyên môn = tiếng anh');
            $table->string('specialty_cn')->nullable()->comment('Chuyên môn = tiếng trung');
            $table->string('specialty_vi')->nullable()->comment('Chuyên môn = tiếng việt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_informations', function (Blueprint $table) {
            //
        });
    }
};
