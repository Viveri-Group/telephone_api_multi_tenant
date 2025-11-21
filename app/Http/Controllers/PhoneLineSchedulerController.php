<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneLineScheduleRequest;
use App\Http\Resources\PhoneLineScheduleResource;
use App\Models\PhoneLineSchedule;
use Illuminate\Http\Request;

class PhoneLineSchedulerController extends Controller
{
    public int $paginationAmount = 50;

    public function index(Request $request)
    {
        $query = PhoneLineSchedule::query()
            ->orderBy('action_at', 'desc');

        if ($request->filled('competition_phone_number')) {
            $query->where('competition_phone_number', $request->competition_phone_number);
        }

        return PhoneLineScheduleResource::collection(
            $query->paginate($this->paginationAmount)
        );
    }

    public function store(PhoneLineScheduleRequest $request)
    {
        return new PhoneLineScheduleResource(PhoneLineSchedule::create($request->validated()));
    }

    public function show(PhoneLineSchedule $phoneLineSchedule)
    {
        return new PhoneLineScheduleResource($phoneLineSchedule);
    }

    public function update(PhoneLineScheduleRequest $request, PhoneLineSchedule $phoneLineSchedule)
    {
        $phoneLineSchedule->update($request->validated());

        return new PhoneLineScheduleResource($phoneLineSchedule);
    }

    public function destroy(PhoneLineSchedule $phoneLineSchedule)
    {
        abort_if($phoneLineSchedule->processed, 417);

        $phoneLineSchedule->delete();

        return response()->noContent();
    }
}
