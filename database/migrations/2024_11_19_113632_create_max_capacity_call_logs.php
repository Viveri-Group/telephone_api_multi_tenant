<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('max_capacity_call_logs', function (Blueprint $table) {
            $table->id();
            $table->string('call_id')->nullable();
            $table->integer('allowed_capacity');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('max_capacity_call_logs');
    }
};
