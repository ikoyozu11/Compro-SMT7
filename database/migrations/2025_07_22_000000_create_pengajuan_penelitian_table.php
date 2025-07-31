<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuan_penelitian', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('instansi');
            $table->string('jurusan');
            $table->string('judul_penelitian');
            $table->string('metode');
            $table->string('surat_izin'); // path file
            $table->string('proposal'); // path file
            $table->string('daftar_pertanyaan'); // path file
            $table->string('ktp'); // path file
            $table->enum('status', ['Pengajuan', 'Diterima', 'Ditolak'])->default('Pengajuan');
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->string('surat_selesai')->nullable(); // path file
            $table->text('keterangan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_penelitian');
    }
}; 