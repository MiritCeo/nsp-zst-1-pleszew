<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
class UserExport implements FromView
{
    private $user;
    private $month;
    private $year;

    public function __construct(int $user, int $month, int $year)
    {
        $this->user = $user;
        $this->month = $month;
        $this->year = $year;
    }
    public function view(): View
    {
        return view('userExport', [
            'timestamps' => DB::table('timestamps')
                ->where('user_id', $this->user)
                ->whereMonth('day', $this->month)
                ->whereYear('day', $this->year)
                ->join('entries', 'timestamps.id', '=', 'entries.timestamp_id')
                ->join('work_types', 'entries.work_type_id', '=', 'work_types.id')
                ->orderBy('day', 'asc')
                ->select('day', 'work_types.name', 'entries.working_hours', 'entries.description')
                ->get(),
            'projects' => DB::table('timestamps')
                ->join('entries', 'timestamps.id', '=', 'entries.timestamp_id')
                ->join('projects', 'entries.project_id', '=', 'projects.id')
                ->where('user_id', $this->user)
                ->whereMonth('day', $this->month)
                ->whereYear('day', $this->year)
                ->groupBy('projects.name')
                ->select('projects.name')
                ->selectRaw("SUM(entries.working_hours) as hours")
                ->distinct()
                ->get()
        ]);
    }
}
