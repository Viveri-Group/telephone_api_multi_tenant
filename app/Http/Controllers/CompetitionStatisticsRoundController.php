<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompetitionStatisticsResource;
use App\Models\CompetitionDraw;
use Illuminate\Http\Request;

class CompetitionStatisticsRoundController extends Controller
{
    public function __invoke(Request $request, CompetitionDraw $competitionDraw)
    {
        return new CompetitionStatisticsResource([
            'participant_count' => $competitionDraw->participants->count(),
            'competition_name' => $competitionDraw->competition->name,
            'competition_id' => $competitionDraw->competition->id,
        ]);
    }
}
