<?php

namespace App\Jobs;

use App\Action\Competition\RemoveFailedEntryParticipantsAction;
use App\Action\PhoneBook\PhoneBookLookupAction;
use App\DTO\ActiveCall\ActiveCallDTO;
use App\Models\ActiveCall;
use App\Models\FailedEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleCompetitionFailClearDownJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ActiveCallDTO $activeCallDTO, public string $callStatus)
    {
    }

    public function handle(): void
    {
        // 1. remove any participants that have already been created and any entry counts.
        app(RemoveFailedEntryParticipantsAction::class)->handle($this->activeCallDTO);

        if(FailedEntry::where('call_id', $this->activeCallDTO->call_id)->doesntExist()) {
            $phoneBookEntry = (new PhoneBookLookupAction())->handle($this->activeCallDTO->competition_phone_number);

            // 2. create failed entry
            FailedEntry::create([
                'competition_id' => $this->activeCallDTO->competition_id,
                'call_id' => $this->activeCallDTO->call_id,
                'phone_number' => $this->activeCallDTO->competition_phone_number,
                'caller_phone_number' => $this->activeCallDTO->caller_phone_number,
                'reason' => $this->callStatus,
                'call_start' => $this->activeCallDTO->created_at,
                'call_end' => now(),
                'round_start' => $this->activeCallDTO->round_start,
                'round_end' => $this->activeCallDTO->round_end,
                'station_name' => $phoneBookEntry?->name,
            ]);
        }

        ActiveCall::find($this->activeCallDTO->id)?->delete();
    }

    public function tags(): array
    {
        return ['Handle Clear Down Fail'];
    }
}
