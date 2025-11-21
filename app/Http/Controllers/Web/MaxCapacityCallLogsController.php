<?php

namespace App\Http\Controllers\Web;

use App\Action\GetPaginatedResourceAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Web\WebMaxCapacityCallLogsResource;
use App\Models\MaxCapacityCallLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class MaxCapacityCallLogsController extends Controller
{
    const RESULTS_PER_PAGE = 50;

    public function index(Request $request)
    {
        if (empty($request->all())) {
            return redirect()->route('web.max-capacity-call-logs.index', [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
            ]);
        };

        $maxCapacityCallLogs = $this->filter($request)->paginate(self::RESULTS_PER_PAGE)->withQueryString();

        return Inertia::render(
            'Auth/MaxCapacityCallLogs/Index',
            [
                'maxCapacityCallLogs' => (new GetPaginatedResourceAction())->handle(
                    $maxCapacityCallLogs,
                    WebMaxCapacityCallLogsResource::collection($maxCapacityCallLogs->items())
                ),
                'defaultSearchFormOptions' => [
                    'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                    'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                ]
            ]);
    }


    protected function filter(Request $request): Builder
    {
        $dateFrom = Carbon::now()->subDays(30);
        $dateTo = Carbon::now();

        if ($request->input('date_from')) {
            $dateFrom = Carbon::parse($request->input('date_from'));
        }

        if ($request->input('date_to')) {
            $dateTo = Carbon::parse($request->input('date_to'));
        }

        $maxCapacityCallLogs = MaxCapacityCallLog::query()
            ->where('created_at', '>=', $dateFrom)
            ->where('created_at', '<=', $dateTo)
            ->orderByDesc('id');

        if ($call_id = $request->input('call_id')) {
            $maxCapacityCallLogs->whereLike('call_id', '%'.$call_id.'%');
        }

        return $maxCapacityCallLogs;
    }
}
