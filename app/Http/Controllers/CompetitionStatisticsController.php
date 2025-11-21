<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompetitionStatisticsResource;
use App\Models\Competition;
use App\Models\Participant;
use Illuminate\Http\Request;

class CompetitionStatisticsController extends Controller
{
    public function __invoke(Request $request, Competition $competition)
    {
        $countOfParticipants = Participant::query()
            ->where('competition_id', $competition->id)
            ->whereNull('drawn_at')
            ->count();

        return new CompetitionStatisticsResource([
            'participant_count' => $countOfParticipants,
            'competition_name' => $competition->name,
            'competition_id' => $competition->id,
        ]);
    }
}
