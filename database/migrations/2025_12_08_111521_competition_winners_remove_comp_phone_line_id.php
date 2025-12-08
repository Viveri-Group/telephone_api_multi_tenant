<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('competition_winners', function (Blueprint $table) {
            $table->dropForeign(['phone_line_id']);
            $table->dropColumn(['phone_line_id']);
        });
    }

    public function down(): void
    {
        Schema::table('competition_winners', function (Blueprint $table) {
            $table->foreignId('phone_line_id')->constrained('competition_phone_lines');
        });
    }
};
