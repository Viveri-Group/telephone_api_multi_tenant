<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('api_request_logs', function (Blueprint $table) {
            $table->index(['duration'], 'idx_api_request_logs_duration');
        });
    }

    public function down(): void
    {
        Schema::table('api_request_logs', function (Blueprint $table) {
            $table->dropIndex('idx_api_request_logs_duration');
        });
    }
};
