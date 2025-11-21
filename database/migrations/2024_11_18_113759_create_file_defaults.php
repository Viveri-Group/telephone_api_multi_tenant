<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('file_defaults', function (Blueprint $table) {
            $table->id();
            $table->integer('external_id');
            $table->string('type');
            $table->string('filename')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('extension')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_defaults');
    }
};
