<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->longText('file_hex')->nullable()->after('file_path');
            $table->string('file_mime')->nullable()->after('file_hex');
            $table->string('file_name')->nullable()->after('file_mime');
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['file_hex', 'file_mime', 'file_name']);
        });
    }
};
