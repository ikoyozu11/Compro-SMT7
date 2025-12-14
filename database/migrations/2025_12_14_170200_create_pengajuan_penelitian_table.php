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
        Schema::create('pengajuan_penelitian', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('instansi');
            $table->string('jurusan');
            $table->string('judul_penelitian');
            $table->string('metode');
            $table->string('surat_izin');
            $table->string('proposal');
            $table->string('daftar_pertanyaan');
            $table->string('ktp');
            $table->string('status')->nullable();
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->string('surat_selesai')->nullable();
            $table->text('keterangan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_penelitian');
    }
};
