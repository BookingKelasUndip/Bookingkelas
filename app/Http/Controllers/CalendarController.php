<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function getCalendarData(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $date = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeek; // 0 = Sunday, 1 = Monday

        // Adjust to make Monday the start of the week
        $startDayOfWeek = ($startDayOfWeek == 0) ? 6 : $startDayOfWeek - 1;

        return response()->json([
            'year' => $year,
            'month' => $month,
            'daysInMonth' => $daysInMonth,
            'startDayOfWeek' => $startDayOfWeek,
        ]);
    }
}
