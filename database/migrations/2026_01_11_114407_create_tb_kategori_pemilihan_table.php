<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_kategori_pemilihan', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->unsignedBigInteger('id_event');
            $table->string('nama_kategori'); // BEM, HMP Informatika, HMP Sipil
            $table->enum('jenis', ['BEM', 'HMP']); // Untuk filtering
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('id_event')->references('id_event')->on('tb_event')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kategori_pemilihan');
    }
};