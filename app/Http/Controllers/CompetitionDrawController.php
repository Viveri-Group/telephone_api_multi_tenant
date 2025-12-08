<?php

namespace App\Http\Controllers;

use App\Action\HandleWholeCompetitionDrawAction;
use App\DTO\CompetitionDraw\CompetitionDrawFailedDTO;
use App\Http\Requests\MarkAsDrawnRequest;
use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionDrawController extends Controller
{
    public function __invoke(MarkAsDrawnRequest $request, Competition $competition)
    {
        $drawResponse = (new HandleWholeCompetitionDrawAction($competition, $request->input('drawn_by')))->handle();

        abort_if($drawResponse instanceof CompetitionDrawFailedDTO, $drawResponse->code, $drawResponse->message);

        return response()->noContent();
    }
}
