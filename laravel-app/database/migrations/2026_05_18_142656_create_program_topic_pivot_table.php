<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_topic', function (Blueprint $table) {
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->primary(['program_id', 'topic_id']);
        });

        // Meglévő topic_id adatok átmásolása a pivot táblába
        DB::table('program_topic')->insertUsing(
            ['program_id', 'topic_id'],
            DB::table('programs')->whereNotNull('topic_id')->select('id', 'topic_id')
        );

        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn('topic_id');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
        });

        // Visszaállítás: az első topic kerül vissza topic_id-ba
        DB::table('program_topic')
            ->orderBy('program_id')
            ->get()
            ->groupBy('program_id')
            ->each(function ($rows, $programId) {
                DB::table('programs')
                    ->where('id', $programId)
                    ->update(['topic_id' => $rows->first()->topic_id]);
            });

        Schema::dropIfExists('program_topic');
    }
};
