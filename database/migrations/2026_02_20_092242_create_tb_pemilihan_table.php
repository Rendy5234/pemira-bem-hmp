<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pemilihan', function (Blueprint $table) {
            $table->id('id_pemilihan');
            $table->unsignedBigInteger('id_event');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_kandidat');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('nim', 20);
            $table->string('nama_pemilih');
            $table->timestamp('waktu_pemilihan');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_event')->references('id_event')->on('tb_event')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('tb_kategori_pemilihan')->onDelete('cascade');
            $table->foreign('id_kandidat')->references('id_kandidat')->on('tb_kandidat')->onDelete('cascade');
            
            $table->index('id_event');
            $table->index('id_kategori');
            $table->index('id_kandidat');
            $table->index('nim');
            $table->index('waktu_pemilihan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pemilihan');
    }
};