<?php

namespace App\Jobs;

use App\Action\Competition\CreateParticipantFromActiveCallDTOAction;
use App\DTO\ActiveCall\ActiveCallDTO;
use App\Models\ActiveCall;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleCompetitionClearDownSuccessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ActiveCallDTO $activeCallDTO)
    {
    }

    public function handle(): void
    {
        if (is_null($this->activeCallDTO->participant_id)) {
            (new CreateParticipantFromActiveCallDTOAction())->handle($this->activeCallDTO);
        };

        // updates paid AND free entries
        Participant::where('call_id', $this->activeCallDTO->call_id)
            ->update([
                'call_end' => $this->activeCallDTO->call_end,
                'audio_file_number' => $this->activeCallDTO->audioFileNumber ?: null,
            ]);

        ActiveCall::find($this->activeCallDTO->id)?->delete();
    }


    public function tags(): array
    {
        return ['Handle Clear Down Success'];
    }
}
