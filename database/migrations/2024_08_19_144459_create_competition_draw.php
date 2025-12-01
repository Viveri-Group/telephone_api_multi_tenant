<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('competition_draws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions');
            $table->string('competition_type')->nullable()->comment('eg WEEKLY, DAILY etc');
            $table->date('round_from');
            $table->date('round_to');
            $table->string('round_hash')->unique();
            $table->string('drawn_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_draws');
    }
};
