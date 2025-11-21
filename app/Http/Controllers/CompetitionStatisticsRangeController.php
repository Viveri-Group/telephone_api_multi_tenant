<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompetitionStatisticsRangeRequest;
use App\Http\Resources\CompetitionStatisticsResource;
use App\Models\Competition;
use App\Models\Participant;
use Illuminate\Support\Carbon;

class CompetitionStatisticsRangeController extends Controller
{
    public function __invoke(CompetitionStatisticsRangeRequest $request, Competition $competition)
    {
        $dateFrom = Carbon::parse($request->input('date_from'));
        $dateTo = Carbon::parse($request->input('date_to'));

        $countOfParticipants = Participant::query()
            ->where('competition_id', $competition->id)
            ->whereBetween('call_start', [$dateFrom, $dateTo])
            ->count();

        return new CompetitionStatisticsResource([
            'participant_count' => $countOfParticipants,
            'competition_name' => $competition->name,
            'competition_id' => $competition->id,
        ]);
    }
}
