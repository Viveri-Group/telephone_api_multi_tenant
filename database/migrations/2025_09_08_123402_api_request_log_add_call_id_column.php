<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('api_request_logs', function (Blueprint $table) {
            $table->string('call_id')->index()->nullable()->after('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('api_request_logs', function (Blueprint $table) {
            $table->dropIndex(['call_id']);
            $table->dropColumn(['call_id']);
        });
    }
};
