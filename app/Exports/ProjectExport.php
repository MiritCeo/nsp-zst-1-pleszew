<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
class ProjectExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $project;
    private $month;
    private $year;

    public function __construct(int $project, int $month, int $year)
    {
        $this->project = $project;
        $this->month = $month;
        $this->year = $year;
    }
    public function view(): View
    {
        return view('projectExport', [
            'timestamps' => DB::table('entries')
                ->where('project_id', $this->project)
                ->whereMonth('timestamps.day', $this->month)
                ->whereYear('timestamps.day', $this->year)
                ->join('timestamps', 'timestamps.id', '=', 'entries.timestamp_id')
                ->join('work_types', 'entries.work_type_id', '=', 'work_types.id')
                ->join('users', 'timestamps.user_id', '=', 'users.id')
                ->orderBy('timestamps.day', 'asc')
                ->select('timestamps.day as day', 'users.name as user', 'work_types.name as work_type', 'working_hours as hour')
                ->get(),
            'users' => DB::table('entries')
                ->where('project_id', $this->project)
                ->whereMonth('timestamps.day', $this->month)
                ->whereYear('timestamps.day', $this->year)
                ->join('timestamps', 'timestamps.id', '=', 'entries.timestamp_id')
                ->join('users', 'timestamps.user_id', '=', 'users.id')
                ->groupBy('users.name')
                ->select('users.name')
                ->selectRaw("SUM(entries.working_hours) as hours")
                ->get()
        ]);
    }
}
