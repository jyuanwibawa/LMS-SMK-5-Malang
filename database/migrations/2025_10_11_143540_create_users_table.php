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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id (PK)
            $table->string('name'); // Nama lengkap
            $table->string('email')->unique(); // Email unik
            $table->timestamp('email_verified_at')->nullable(); // Standar Laravel
            $table->string('password'); // Hash password

            // Foreign Key ke tabel roles
            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->onUpdate('cascade')
                ->onDelete('set null');
            // ->nullable(): Boleh kosong
            // ->constrained('roles'): Merujuk ke tabel 'roles'
            // ->onDelete('set null'): Jika role dihapus, kolom ini menjadi NULL

            $table->string('profile_picture')->nullable(); // Path foto profil, boleh kosong
            $table->rememberToken(); // Standar Laravel
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
