<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_magang', 'lokasi_id')) {
                $table->unsignedBigInteger('lokasi_id')->nullable()->after('jurusan');
                $table->foreign('lokasi_id')->references('id')->on('lokasi')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_magang', 'lokasi_id')) {
                $table->dropForeign(['lokasi_id']);
                $table->dropColumn('lokasi_id');
            }
        });
    }
}; 