<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_admin', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('name_admin', 50);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->enum('role', ['superadmin', 'admin', 'readstaf'])->default('admin');
            $table->timestamps(); // created_at & updated_at otomatis
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_admin');
    }
};