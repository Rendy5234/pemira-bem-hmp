<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_kandidat', function (Blueprint $table) {
            $table->id('id_kandidat');
            $table->unsignedBigInteger('id_kategori'); // FK ke tb_kategori_pemilihan
            $table->integer('nomor_urut');
            
            // Data Ketua
            $table->string('nama_ketua');
            $table->string('nim_ketua', 20);
            $table->string('foto_ketua')->nullable();
            
            // Data Wakil
            $table->string('nama_wakil');
            $table->string('nim_wakil', 20);
            $table->string('foto_wakil')->nullable();
            
            // Visi Misi
            $table->text('visi');
            $table->text('misi');
            
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('tb_kategori_pemilihan')
                ->onDelete('cascade');
            
            // Unique: nomor urut per kategori
            $table->unique(['id_kategori', 'nomor_urut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kandidat');
    }
};