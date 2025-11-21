<?php

namespace App\Export;

use App\Models\Participant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EntrantsExport implements FromCollection, WithHeadings, WithMapping, WithBatchInserts, WithChunkReading
{
    public function __construct(public LazyCollection $query)
    {
    }

    public function collection(): Collection|LazyCollection
    {
        return $this->query;
    }

    public function map($item): array
    {
        $callStartInMicroseconds = (string) $item->call_start->timestamp * 100;
        $isParticipant = $item instanceof Participant;

        return [
            $item->call_start->format('Y/m/d H:i:s'),
            "{$callStartInMicroseconds}.{$item->call_id}",
            $item->caller_phone_number,
            "{$item->competition?->name} | {$item->round_start?->format('D d M y H:i')} - {$item->round_end?->format('D d M y H:i')} | N/A",
            $isParticipant ? 'TRUE' : 'FALSE',
            $item->call_start->toISOString(),
            preg_replace('/^44/', '', $item->competition_phone_number),
            $isParticipant ? 'CONFIRMED' : 'PENDING',
            $item->uuid,
            $isParticipant ? 'UPDATED' : 'CREATED',
            $item->station_name,
            $item->id,
            $item->call_end->toISOString()
        ];
    }

    public function headings(): array
    {
        return [
            'CALL DATETIME UTC', 'CALL ID', 'CLI', 'COMPETITION NAME', 'CONFIRMED', 'DATABASE CREATED AT',
            'DDI', 'ENTRY STATUS', 'ENTRY UUID', 'EVENT', 'STATION NAME', 'SYSTEM ID', 'UPDATED AT'
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
