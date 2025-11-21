<?php

namespace App\Http\Controllers;

use App\Action\GetPaginatedResourceAction;
use App\Http\Requests\EntrantsDownloadRequest;
use App\Http\Resources\Download\DownloadParticipantResource;
use App\Models\Competition;
use App\Models\Participant;
use Illuminate\Support\Carbon;

class EntrantsDownloadController extends Controller
{
    public function __invoke(EntrantsDownloadRequest $request, Competition $competition)
    {
        $dateFrom = Carbon::parse($request->input('date_from'))->utc();
        $dateTo = Carbon::parse($request->input('date_to'))->utc();

        $participants = Participant::query()
            ->where('competition_id', $competition->id)
            ->where('call_start', '>=', $dateFrom)
            ->where('call_start', '<=', $dateTo)
            ->orderByDesc('id')
            ->paginate(config('system.DOWNLOAD.ENTRIES_DOWNLOAD_RESULTS_PER_PAGE'))
            ->withQueryString();

        return response()->json([
            'participants' => (new GetPaginatedResourceAction())->handle(
                $participants,
                DownloadParticipantResource::collection($participants->items())
            ),
        ]);
    }
}
