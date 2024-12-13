<?php

namespace App\Http\Controllers;

use App\Models\ProcessLog;
use Illuminate\View\View;

class ProcessLogController extends Controller
{
    /**
     * Wyświetla listę logów procesów.
     *
     * @return View
     */
    public function index(): View
    {
        $processLogList = ProcessLog::all();

        return view('processLog.index', [
            'processLogList' => $processLogList,
        ]);
    }
}
