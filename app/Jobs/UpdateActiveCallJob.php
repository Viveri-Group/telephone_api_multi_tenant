<?php

namespace App\Jobs;

use App\Models\ActiveCall;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateActiveCallJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $data)
    {
    }

    public function handle(): void
    {
        if ($this->data['active_call_id'] && $activeCall = ActiveCall::find($this->data['active_call_id'])) {
            $data = [];

            if(!$activeCall->competition_id){
                $data['competition_id'] =  $this->data['competition_id'];
            }

            $activeCall->update([
                'status' => $this->data['status'],
                ...$data
            ]);
        }
    }

    public function tags(): array
    {
        return ['Update Active Call Log'];
    }
}
