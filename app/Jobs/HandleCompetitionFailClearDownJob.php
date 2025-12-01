<?php

namespace App\Jobs;

use App\Action\Competition\CompetitionClearDownFailAction;
use App\DTO\ActiveCall\ActiveCallDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleCompetitionFailClearDownJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ActiveCallDTO $activeCallDTO, public string $reason)
    {
    }

    public function handle(): void
    {
        (new CompetitionClearDownFailAction())->handle($this->activeCallDTO, $this->reason);
    }

    public function tags(): array
    {
        return ['Handle Clear Down Fail'];
    }
}
