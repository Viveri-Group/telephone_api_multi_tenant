<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid()->index();
            $table->foreignId('user_id')->constrained('users');
            $table->string('ip_address')->nullable();
            $table->integer('duration')->nullable();
            $table->string('request_type');
            $table->json('request_headers');
            $table->json('request_input');
            $table->string('response_status');
            $table->json('response_data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_request_logs');
    }
};
