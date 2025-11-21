<?php

namespace App\Http\Controllers\Web;

use App\Action\GetPaginatedResourceAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Web\WebShoutRequestLogsResource;
use App\Models\ShoutServerRequestLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class ShoutRequestLogsController extends Controller
{
    const RESULTS_PER_PAGE = 50;

    public function index(Request $request)
    {
        if (empty($request->all())) {
            return redirect()->route('web.shout-request-logs.index', [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
            ]);
        };

        $shoutRequestLogs = $this->filter($request)->paginate(self::RESULTS_PER_PAGE)->withQueryString();

        return Inertia::render(
            'Auth/ShoutRequestLogs/Index',
            [
                'shoutRequestLogs' => (new GetPaginatedResourceAction())->handle(
                    $shoutRequestLogs,
                    WebShoutRequestLogsResource::collection($shoutRequestLogs->items())
                ),
                'defaultSearchFormOptions' => [
                    'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                    'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                ]
            ]);
    }

    public function show(Request $request, ShoutServerRequestLog $shoutRequestLog)
    {
        return Inertia::render(
            'Auth/ShoutRequestLogs/Show', [
                'shoutRequestLog' => new WebShoutRequestLogsResource($shoutRequestLog),
            ]
        );
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

        $shoutRequestLogs = ShoutServerRequestLog::query()
            ->where('created_at', '>=', $dateFrom)
            ->where('created_at', '<=', $dateTo)
            ->orderByDesc('id');

        if ($identifier = $request->input('identifier')) {
            $shoutRequestLogs->whereLike('identifier', '%'.$identifier.'%');
        }

        if ($http_method = $request->input('http_method')) {
            $shoutRequestLogs->where('http_method', $http_method);
        }

        if ($url = $request->input('url')) {
            $shoutRequestLogs->whereLike('url', '%'.$url.'%');
        }

        if ($status_code = $request->input('status_code')) {
            $shoutRequestLogs->whereLike('status_code', '%'.$status_code.'%');
        }

        if ($request_type = $request->input('request_type')) {
            $shoutRequestLogs->whereLike('request_type', '%'.$request_type.'%');
        }

        if ($attempts = $request->input('attempts')) {
            $shoutRequestLogs->where('attempts', $attempts < 2 ? '=' : '>=', $attempts);
        }

        return $shoutRequestLogs;
    }
}
