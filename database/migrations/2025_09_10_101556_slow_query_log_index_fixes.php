<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->index(['call_id', 'deleted_at']);
        });

        Schema::table('api_request_logs', function (Blueprint $table) {
            $table->index(['created_at', 'duration'], 'api_request_logs_created_at_duration_index')
                ->descending('duration');
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropIndex(['call_id', 'deleted_at']);
        });

        Schema::table('api_request_logs', function (Blueprint $table) {
            $table->dropIndex('api_request_logs_created_at_duration_index');
        });
    }
};
