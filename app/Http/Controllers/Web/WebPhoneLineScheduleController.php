<?php

namespace App\Http\Controllers\Web;

use App\Action\PhoneBook\PhoneBookEntryExistsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Web\WebPhoneLineScheduleResource;
use App\Models\PhoneLineSchedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WebPhoneLineScheduleController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!(new PhoneBookEntryExistsAction())->handle($request->competitionPhoneNumber), 404);

        $processed = $request->filled('processed') && $request->boolean('processed');

        $schedules = PhoneLineSchedule::query()
            ->where('competition_phone_number', $request->competitionPhoneNumber)
            ->where('processed', $processed);

        return Inertia::render(
            'Auth/PhoneLineSchedule/Index',
            [
                'schedules' => WebPhoneLineScheduleResource::collection($schedules->get()),
                'competitionPhoneNumber' => $request->competitionPhoneNumber,
                'processed' => $processed
            ]
        );
    }

    public function show(Request $request, PhoneLineSchedule $phoneLineSchedule)
    {
        return Inertia::render(
            'Auth/PhoneLineSchedule/Show',
            [
                'schedule' => new WebPhoneLineScheduleResource($phoneLineSchedule),
            ]
        );
    }
}
