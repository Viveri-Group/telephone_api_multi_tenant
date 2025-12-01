<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('file_uploads', function (Blueprint $table) {

            $table->id();
            $table->integer('external_id')->nullable();
            $table->foreignId('competition_id')->nullable()->constrained('competitions');
            $table->foreignId('competition_phone_line_id')->nullable()->constrained('competition_phone_lines');
            $table->string('type');
            $table->string('name');
            $table->string('filename');
            $table->integer('size');
            $table->string('mime_type');
            $table->string('extension');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_uploads');
    }
};
