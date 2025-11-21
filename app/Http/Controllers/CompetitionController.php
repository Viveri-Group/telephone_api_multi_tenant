<?php

namespace App\Http\Controllers;

use App\Action\Competition\CompetitionCheckForConflictingPhoneLines;
use App\Http\Requests\CompetitionRequest;
use App\Http\Resources\CompetitionResource;
use App\Models\Competition;
use Illuminate\Support\Carbon;

class CompetitionController extends Controller
{
    public int $paginationAmount = 50;

    public function index()
    {
        return CompetitionResource::collection(Competition::orderBy('start', 'desc')->paginate($this->paginationAmount));
    }

    public function store(CompetitionRequest $request)
    {
        return new CompetitionResource(Competition::create($request->validated()));
    }

    public function show(Competition $competition)
    {
        return new CompetitionResource($competition);
    }

    public function update(CompetitionRequest $request, Competition $competition)
    {
        abort_if($this->hasBreachedCompStartedUpdateRules($request, $competition), 409, 'Unable to update competition start_date after the competition has started.');

        $newStart = Carbon::parse($request->get('start'));
        $newEnd = Carbon::parse($request->get('end'));

        abort_if($conflictingCompMessage = (new CompetitionCheckForConflictingPhoneLines($competition, $newStart, $newEnd))->handle(), 409, $conflictingCompMessage);

        $competition->update($request->validated());

        return new CompetitionResource($competition);
    }

    public function destroy(Competition $competition)
    {
        $competition->delete();

        return response()->noContent();
    }

    protected function hasBreachedCompStartedUpdateRules(CompetitionRequest $request, Competition $competition)
    {
        // if the competition has started then don't allow updating of comp start_date nor comp type
        if($competition->start->gte(now())){
            return false;
        }

        $newStart = Carbon::parse($request->get('start'));

        if($competition->start->ne($newStart)){
            return true;
        }
    }
}
