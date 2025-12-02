<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->boolean('sms_offers_enabled')->default(false)->after('end')->comment('');
        });
    }

    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn(['sms_offers_enabled']);
        });
    }
};
