<?php

namespace App\Http\Controllers\Web;

use App\Action\GetPaginatedResourceAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Web\WebApiRequestLogsResource;
use App\Models\APIRequestLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class ApiRequestLogsController extends Controller
{
    const RESULTS_PER_PAGE = 50;

    protected array $sortableColumns = ["Created" => "created_at", 'Request Duration' => 'duration'];

    public function index(Request $request)
    {
        if (empty($request->all())) {
            return redirect()->route('web.api-request-logs.index', [
                'date_from' => now('UTC')->startOfDay()->format('Y-m-d\TH:i:s'),
                'date_to' => now('UTC')->endOfDay()->format('Y-m-d\TH:i:s'),
                'order_by' => 'id',
                'order_by_direction' => 'desc',
            ]);
        };

        $apiRequestLogs = $this->filter($request)->paginate(self::RESULTS_PER_PAGE)->withQueryString();

        return Inertia::render(
            'Auth/APIRequestLogs/Index',
            [
                'apiRequestLogs' => (new GetPaginatedResourceAction())->handle(
                    $apiRequestLogs,
                    WebApiRequestLogsResource::collection($apiRequestLogs->items())
                ),
                'defaultSearchFormOptions' => [
                    'orderBy' => [
                        'availableColumns' => $this->sortableColumns,
                        'column' => 'created_at',
                        'direction' => 'desc'
                    ],
                    'date_from' => now('UTC')->startOfDay()->format('Y-m-d\TH:i:s'),
                    'date_to' => now('UTC')->endOfDay()->format('Y-m-d\TH:i:s'),
                    'users' => collect(User::get())->map(fn($user) => ['label' => $user->name, 'value' => (string) $user->id])->toArray()
                ]
            ]);
    }

    public function show(Request $request, APIRequestLog $apiRequestLog)
    {
        return Inertia::render(
            'Auth/APIRequestLogs/Show', [
                'apiRequestLog' => new WebApiRequestLogsResource($apiRequestLog),
            ]
        );
    }

    protected function filter(Request $request): Builder
    {
        $dateFrom = Carbon::now('UTC')->subDays(30)->format('Y-m-d\TH:i:s');
        $dateTo = Carbon::now('UTC')->format('Y-m-d\TH:i:s');
        $orderByColumn = 'created_at';
        $orderDirection = 'desc';

        if ($request->input('order_by') && in_array($request->input('order_by'), array_values($this->sortableColumns))) {
            $orderByColumn = $request->input('order_by');
        }

        if ($request->input('order_by_direction') && in_array($request->input('order_by_direction'), ['asc', 'desc'])) {
            $orderDirection = $request->input('order_by_direction');
        }

        if ($request->input('date_from')) {
            $dateFrom = Carbon::parse($request->input('date_from'), 'Europe/London')->setTimezone('UTC');
        }

        if ($request->input('date_to')) {
            $dateTo = Carbon::parse($request->input('date_to'), 'Europe/London')->setTimezone('UTC');
        }

        $apiRequestLogs = APIRequestLog::query()
            ->where('created_at', '>=', $dateFrom)
            ->where('created_at', '<=', $dateTo)
            ->orderBy($orderByColumn, $orderDirection);

        if ($uuid = $request->input('uuid')) {
            $apiRequestLogs->where('uuid', $uuid);
        }

        if ($user_id = $request->input('user_id')) {
            $apiRequestLogs->where('user_id', $user_id);
        }

        if ($duration = $request->input('duration')) {
            match ($duration){
                '1' => $apiRequestLogs->whereBetween('duration', [0, 500]),
                '2' => $apiRequestLogs->whereBetween('duration', [501, 1000]),
                '3' => $apiRequestLogs->where('duration', '>', 1000),
            };
        }

        if ($request_type = $request->input('request_type')) {
            $apiRequestLogs->whereLike('request_type', '%'.$request_type.'%');
        }

        if ($request_input = $request->input('request_input')) {
            $apiRequestLogs->whereLike('request_input', '%'.$request_input.'%');
        }

        if ($request_output = $request->input('request_output')) {
            $apiRequestLogs->whereLike('response_data', '%'.$request_output.'%');
        }

        if ($call_id = $request->input('call_id')) {
            $apiRequestLogs->where('call_id', $call_id);
        }

        if ($caller_number = $request->input('caller_number')) {
            $apiRequestLogs->whereLike('request_input->caller_phone_number', "%{$caller_number}%");
        }

        if ($competition_phone_number = $request->input('competition_phone_number')) {
            $apiRequestLogs->whereLike('request_input->phone_number', "%{$competition_phone_number}%");
        }

        if ($response_status = $request->input('response_status')) {
            $apiRequestLogs->whereLike('response_status', '%'.$response_status.'%');
        }

        return $apiRequestLogs;
    }
}
