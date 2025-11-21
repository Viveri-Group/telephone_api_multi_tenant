<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('active_calls', function (Blueprint $table) {
            $table->integer('call_id')->nullable()->change();
        });

        Schema::table('competition_winners_alt', function (Blueprint $table) {
            $table->integer('call_id')->nullable()->change();
        });

        Schema::table('max_capacity_call_logs', function (Blueprint $table) {
            $table->integer('call_id')->nullable()->change();
        });

        Schema::table('failed_entries', function (Blueprint $table) {
            $table->integer('call_id')->nullable()->change();
        });

        Schema::table('active_call_orphans', function (Blueprint $table) {
            $table->integer('call_id')->nullable()->change();
        });

        Schema::table('api_request_logs', function (Blueprint $table) {
            $table->integer('call_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('active_calls', function (Blueprint $table) {
            $table->string('call_id')->nullable()->change();
        });

        Schema::table('competition_winners_alt', function (Blueprint $table) {
            $table->string('call_id')->nullable()->change();
        });

        Schema::table('max_capacity_call_logs', function (Blueprint $table) {
            $table->string('call_id')->nullable()->change();
        });

        Schema::table('failed_entries', function (Blueprint $table) {
            $table->string('call_id')->nullable()->change();
        });

        Schema::table('active_call_orphans', function (Blueprint $table) {
            $table->string('call_id')->nullable()->change();
        });

        Schema::table('api_request_logs', function (Blueprint $table) {
            $table->string('call_id')->nullable()->change();
        });
    }
};
