<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->string('focus_keyword')->nullable()->after('meta_keywords');
        });

        Schema::table('post_translations', function (Blueprint $table): void {
            $table->string('focus_keyword')->nullable()->after('meta_keywords');
            $table->char('source_hash', 64)->nullable()->after('focus_keyword');
            $table->timestamp('translated_at')->nullable()->after('source_hash');
            $table->string('translation_provider', 32)->nullable()->after('translated_at');
        });
    }

    public function down(): void
    {
        Schema::table('post_translations', function (Blueprint $table): void {
            $table->dropColumn(['focus_keyword', 'source_hash', 'translated_at', 'translation_provider']);
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn('focus_keyword');
        });
    }
};
