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
            $table->string('name_en')->nullable()->comment('Tên = tiếng anh');
            $table->string('name_cn')->nullable()->comment('Tên = tiếng trung');
            $table->string('name_vi')->nullable()->comment('Tên = tiếng việt');
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
