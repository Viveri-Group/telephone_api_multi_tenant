<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Only add the critical call_id index if it doesn't exist
        $indexExists = DB::select("SHOW INDEX FROM participants WHERE Key_name = 'participants_call_id_only_idx'");

        if (empty($indexExists)) {
            Schema::table('participants', function (Blueprint $table) {
                $table->index(['call_id'], 'participants_call_id_only_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $indexExists = DB::select("SHOW INDEX FROM participants WHERE Key_name = 'participants_call_id_only_idx'");
            if (!empty($indexExists)) {
                $table->dropIndex('participants_call_id_only_idx');
            }
        });
    }
};
