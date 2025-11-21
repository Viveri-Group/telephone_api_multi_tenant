<?php

namespace App\DTO\ItvSqsDto;

class ItvSqsDto
{
    public function __construct(
        public string $skylight_id, // name of competition
        public string $dialed_number, // comp number
        public string $call_datetime,
        public string $call_status, // status code or description of call outcome
        public string $msisdn,
        public string $charging_date,
        public string $entry_id, // participant id / failed entry id
        public float $call_duration
    )
    {
    }

    public function toArray(): array
    {
        return [
            'skylight_id'           => $this->skylight_id,
            'dialed_number'         => $this->dialed_number,
            'call_datetime'         => $this->call_datetime,
            'call_status'           => $this->call_status,
            'msisdn'                => $this->msisdn,
            'charging_date'         => $this->charging_date,
            'entry_id'              => $this->entry_id,
            'chargeable_duration'   =>  $this->call_duration,
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
