<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
class HolidayExport implements FromView
{
    private $user;
    private $year;

    public function __construct(int $user, int $year)
    {
        $this->user = $user;
        $this->year = $year;
    }

    public function view(): View
    {
        return view('holidayExport', [
            'timestamps' => DB::table('timestamps')
                ->where('user_id', $this->user)
                ->where('work_types.is_holiday', '1')
                ->whereYear('timestamps.day', $this->year)
                ->join('entries', 'timestamps.id', '=', 'entries.timestamp_id')
                ->join('work_types', 'entries.work_type_id', '=', 'work_types.id')
                ->orderBy('timestamps.day', 'desc')
                ->select('timestamps.day as day', 'work_types.name as work_type', 'entries.working_hours as hours')
                ->get(),
            'today' => Carbon::now()->format('Y-m-d')
        ]);
    }
}
