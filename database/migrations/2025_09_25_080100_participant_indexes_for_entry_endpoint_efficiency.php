<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $indexes = $this->getIndexes();

            if (!$indexes->pluck('INDEX_NAME')->contains('idx_participants_competition_id_id')) {
                $table->index(['competition_id', 'id'], 'idx_participants_competition_id_id');
            }

            if (!$indexes->pluck('INDEX_NAME')->contains('idx_participants_competition_id_telephone')) {
                $table->index(['competition_id', 'telephone'], 'idx_participants_competition_id_telephone');
            }

            if (!$indexes->pluck('INDEX_NAME')->contains('idx_participants_competition_call_deleted')) {
                $table->index(['competition_id', 'call_start', 'deleted_at'], 'idx_participants_competition_call_deleted');
            }
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $indexes = $this->getIndexes();

            if ($indexes->pluck('INDEX_NAME')->contains('idx_participants_competition_id_id')) {
                $table->dropIndex('idx_participants_competition_id_id');
            }

            if ($indexes->pluck('INDEX_NAME')->contains('idx_participants_competition_id_telephone')) {
                $table->dropIndex('idx_participants_competition_id_telephone');
            }
        });
    }

    protected function getIndexes(): Collection
    {
        return collect(DB::select("
        SELECT
            INDEX_NAME,
            GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) AS columns,
            NON_UNIQUE
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'participants'
        GROUP BY INDEX_NAME, NON_UNIQUE
    "));
    }
};
