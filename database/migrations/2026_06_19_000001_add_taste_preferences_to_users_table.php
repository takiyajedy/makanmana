<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('preferred_cuisines')->nullable();   // contoh: Melayu, Japanese
            $table->json('dietary')->nullable();              // contoh: Halal, Vegetarian
            $table->unsignedTinyInteger('spicy_level')->nullable(); // 0=tak suka, 3=sangat pedas
            $table->string('budget')->nullable();             // jimat | sederhana | mewah
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['preferred_cuisines', 'dietary', 'spicy_level', 'budget']);
        });
    }
};
