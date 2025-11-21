<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shout_server_request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('identifier');
            $table->string('status_code');
            $table->text('url');
            $table->string('http_method');
            $table->string('request_type');
            $table->json('request_input');
            $table->string('response_class');
            $table->json('request');
            $table->json('response')->nullable();
            $table->dateTime('request_start')->nullable();
            $table->dateTime('request_end')->nullable();
            $table->integer('response_time')->nullable();
            $table->integer('attempts')->nullable();
            $table->string('tracing_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shout_server_request_logs');
    }
};
