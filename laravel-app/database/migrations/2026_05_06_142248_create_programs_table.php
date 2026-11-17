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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('description')->nullable();
            $table->unsignedInteger('price_adult')->nullable();
            $table->unsignedInteger('price_child')->nullable();
            $table->unsignedTinyInteger('child_age_min')->nullable();
            $table->unsignedTinyInteger('child_age_max')->nullable();
            $table->json('duration')->nullable();
            $table->json('available_days')->nullable();
            $table->unsignedSmallInteger('group_size')->nullable();
            $table->string('youtube_embed')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
