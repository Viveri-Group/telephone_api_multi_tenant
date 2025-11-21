<?php

namespace App\Http\Controllers;

use App\Action\ClearDown\LogClearDownAction;
use Illuminate\Support\Facades\App;

class LogClearDownController extends Controller
{
    public function __invoke()
    {
        abort_if(App::isProduction(), 404);

        (new LogClearDownAction())->handle();

        return redirect()->back()->with('flash-data', [
            'type' => 'SUCCESS',
            'message' => 'Logs Successfully Removed'
        ]);
    }
}
