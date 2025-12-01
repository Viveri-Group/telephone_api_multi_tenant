<?php

namespace App\Jobs;

use App\Action\Competition\CompetitionClearDownFailAction;
use App\Action\Competition\CompetitionClearDownSuccessAction;
use App\Action\EntrantRoundCount\GetEntrantRoundCountAction;
use App\DTO\ActiveCall\ActiveCallDTO;
use App\Models\Competition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleCompetitionClearDownSuccessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ActiveCallDTO $activeCallDTO, public bool $smsOfferAccepted)
    {
    }

    public function handle(): void
    {
        $entriesCount = (new GetEntrantRoundCountAction())->handle($this->activeCallDTO);

        $competition = Competition::find($this->activeCallDTO->competition_id);

        if ($entriesCount >= $competition->max_entries) {
            (new CompetitionClearDownFailAction())->handle($this->activeCallDTO, 'TOO_MANY');
        }else {
            (new CompetitionClearDownSuccessAction())
                ->handle(
                    $this->activeCallDTO,
                    $this->smsOfferAccepted
                );
        }
    }

    public function tags(): array
    {
        return ['Handle Clear Down Success'];
    }
}
