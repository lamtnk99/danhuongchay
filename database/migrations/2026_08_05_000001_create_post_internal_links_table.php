<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_internal_links', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('source_post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('target_post_id')->constrained('posts')->cascadeOnDelete();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['source_post_id', 'target_post_id']);
            $table->index(['source_post_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_internal_links');
    }
};
