<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->enum('level', ['X','XI','XII'])->nullable()->after('name');
            $table->enum('major', ['RPL','TKJ','Multimedia'])->nullable()->after('level');
            $table->boolean('is_active')->default(true)->after('academic_year');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['level', 'major', 'is_active']);
        });
    }
};
