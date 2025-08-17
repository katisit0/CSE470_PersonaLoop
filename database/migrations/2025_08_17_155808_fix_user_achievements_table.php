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
        Schema::table('user_achievements', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->after('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('unlocked_at')->nullable()->after('achievement_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_achievements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['achievement_id']);
            $table->dropColumn(['user_id', 'achievement_id', 'unlocked_at']);
        });
    }
};
