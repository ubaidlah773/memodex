<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('album_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('albums')->cascadeOnDelete();
            $table->foreignId('card_id')->constrained('cards')->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['album_id', 'card_id']);
            $table->index(['album_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('album_cards');
    }
};
