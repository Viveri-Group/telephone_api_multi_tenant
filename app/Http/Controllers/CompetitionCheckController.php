<?php

namespace App\Http\Controllers;

use App\Action\CapacityCheck\MaxLinesExceeded;
use App\Action\Competition\CapacityCheckExceptionHandlerAction;
use App\Action\Competition\CompetitionPreCheckAction;
use App\Action\PhoneLine\PhoneNumberCleanupAction;
use App\DTO\Competition\CompetitionPreCheckRequestDTO;
use App\Exceptions\CustomCompetitionHttpException;
use App\Http\Requests\CompetitionCheckRequest;
use App\Http\Resources\CompetitionCapacityCheckWithActivePhoneLineResource;
use App\Models\ActiveCall;
use App\Models\CompetitionPhoneLine;
use App\Models\MaxCapacityCallLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CompetitionCheckController extends Controller
{
    protected string $callerPhoneNumber;

    protected string $competitionPhoneNumber;

    protected int $httpResponseCode = 200;

    protected string $httpResponseStatus = 'OPEN';

    public function __invoke(CompetitionCheckRequest $request)
    {
//        abort_if($this->maxNumberOfLinesExceeded($request), 412, 'Active lines allowance exceeded.');

        $this->setNumbers($request);

        $phoneLine = Cache::remember(
            "competition_phone_line_{$this->competitionPhoneNumber}",
            60, // seconds
            function () {
                return CompetitionPhoneLine::where('phone_number', $this->competitionPhoneNumber)->first();
            }
        );

        try {
            $response = (new CompetitionPreCheckAction())->handle(
                new CompetitionPreCheckRequestDTO(
                    $this->callerPhoneNumber,
                    $this->competitionPhoneNumber,
                    $request->input('call_id'),
                    $request->input('cli_presentation', 2),
                ),
                $phoneLine,
                CompetitionCapacityCheckWithActivePhoneLineResource::class
            );
        } catch (CustomCompetitionHttpException $e) {
            list($this->httpResponseCode, $this->httpResponseStatus, $errorType, $response) = (new CapacityCheckExceptionHandlerAction())->handle($e);
        }

        return response()->json($response, $this->httpResponseCode);
    }

    protected function setNumbers(Request $request): void
    {
        $this->callerPhoneNumber = (new PhoneNumberCleanupAction())->handle($request->input('caller_phone_number'));
        $this->competitionPhoneNumber = (new PhoneNumberCleanupAction())->handle($request->input('phone_number'));
    }

    protected function maxNumberOfLinesExceeded(Request $request): bool
    {
        $linesHaveBeenExceeded = (new MaxLinesExceeded())->handle(ActiveCall::count());

        if ($linesHaveBeenExceeded) {
            MaxCapacityCallLog::create([
                'call_id' => $request->input('call_id'),
                'allowed_capacity' => config('system.MAX_NUMBER_OF_LINES')
            ]);
        }

        return $linesHaveBeenExceeded;
    }
}
