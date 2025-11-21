<?php

namespace App\DTO\Export;

class EntrantsExportDTO
{
    public function __construct(
        public string $call_start,
        public string $call_id,
        public string $cli,
        public string $competition_name,
        public string $confirmed,
        public string $db_created_at,
        public string $ddi,
        public string $entry_status,
        public string $uuid,
        public string $event,
        public string $station_name,
        public string $id,
        public string $call_end,
    )
    {
    }
}
