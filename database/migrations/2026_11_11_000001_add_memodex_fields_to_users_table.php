<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('avatar_path')->nullable()->after('password');
            $table->unsignedBigInteger('storage_used_bytes')->default(0)->after('avatar_path');
            $table->unsignedBigInteger('storage_quota_bytes')->default(524288000)->after('storage_used_bytes'); // 500 MB default
            $table->boolean('is_admin')->default(false)->after('storage_quota_bytes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'avatar_path',
                'storage_used_bytes',
                'storage_quota_bytes',
                'is_admin',
            ]);
        });
    }
};
