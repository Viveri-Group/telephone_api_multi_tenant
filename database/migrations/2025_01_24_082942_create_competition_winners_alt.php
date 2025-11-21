<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('competition_winners_alt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('participants');
            $table->foreignId('competition_id')->constrained('competitions');
            $table->string('call_id')->nullable();
            $table->integer('number_of_entries')->nullable();
            $table->dateTime('date_from');
            $table->dateTime('date_to');
            $table->foreignId('phone_line_id')->constrained('competition_phone_lines');
            $table->string('competition_phone_number', 12)->nullable();
            $table->string('telephone', 12);
            $table->dateTime('call_start')->nullable();
            $table->dateTime('call_end')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_winners_alt');
    }
};
