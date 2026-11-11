<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('photo_id')->constrained('photos')->restrictOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('card_templates')->nullOnDelete();
            $table->string('template_version')->default('1.0.0');
            $table->json('template_snapshot')->nullable();
            $table->string('card_number');
            $table->string('name');
            $table->integer('hp')->nullable();
            $table->string('type')->nullable();
            $table->string('rarity')->default('BASIC');
            $table->string('skill_name')->nullable();
            $table->text('skill_description')->nullable();
            $table->text('flavor_text')->nullable();
            $table->string('render_path')->nullable();
            $table->json('custom_data')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'card_number']);
            $table->index(['user_id', 'is_favorite']);
            $table->index(['user_id', 'rarity']);
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
