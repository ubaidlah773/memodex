<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->foreignId('frame_id')->nullable()->after('photo_id')->constrained('card_frames')->nullOnDelete();
            $table->string('framed_image_path')->nullable()->after('name');
            $table->string('name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropForeign(['frame_id']);
            $table->dropColumn(['frame_id', 'framed_image_path']);
        });
    }
};
