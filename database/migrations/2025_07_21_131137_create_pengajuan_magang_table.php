<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuan_magang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemohon');
            $table->string('no_hp');
            $table->text('nama_anggota'); // disimpan dengan delimiter ";"
            $table->string('asal_instansi');
            $table->string('jurusan');
            $table->text('keahlian');
            $table->unsignedBigInteger('lokasi_id'); // foreign key ke master lokasi
            $table->date('mulai_magang');
            $table->date('selesai_magang');
            $table->timestamps();

            $table->foreign('lokasi_id')->references('id')->on('lokasi_magang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magang');
    }
};
