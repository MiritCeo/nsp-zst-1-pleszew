<?php

namespace App\Services;

use App\Exports\HolidayExport;
use App\Exports\UserExport;
use App\Exports\ProjectExport;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelService
{
    protected ?int $selectedMonth;
    protected ?int $selectedYear;
    protected ?string $searchValue;
    protected ?string $dateValue;

    public function __construct(Request $request)
    {
        $this->searchValue = $request?->query('search');
        $this->dateValue = $request?->query('date');
        $this->setDate();
        $this->selectedMonth = Carbon::create($this->dateValue)->month;
        $this->selectedYear = Carbon::create($this->dateValue)->year;
    }

    public function userIndex(User $user): Response
    {
        $timestamps = DB::table('timestamps')
            ->where('user_id', $user->id)
            ->whereMonth('day', $this->selectedMonth)
            ->whereYear('day', $this->selectedYear)
            ->join('entries', 'timestamps.id', '=', 'entries.timestamp_id')
            ->join('work_types', 'entries.work_type_id', '=', 'work_types.id')
            ->join('projects', 'entries.project_id', '=', 'projects.id')
            ->orderBy('day', 'asc')
            ->select('day', 'entries.working_hours', 'entries.description', 'work_types.name', 'projects.name as project_name')
            ->get();

        $projects = DB::table('timestamps')
            ->join('entries', 'timestamps.id', '=', 'entries.timestamp_id')
            ->join('projects', 'entries.project_id', '=', 'projects.id')
            ->where('user_id', $user->id)
            ->whereMonth('day', $this->selectedMonth)
            ->whereYear('day', $this->selectedYear)
            ->groupBy('projects.name')
            ->select('projects.name')
            ->selectRaw("SUM(entries.working_hours) as hours")
            ->distinct()
            ->get();

        return Inertia::render('Admin/User/ExcelCreate', [
            'user' => $user,
            'timestamps' => $timestamps,
            'dateValue' => $this->dateValue,
            'projects' => $projects
        ]);
    }

    public function exportUser(User $user): BinaryFileResponse
    {
        return Excel::download(new UserExport($user->id, $this->selectedMonth, $this->selectedYear), 'Raport_'.$user->name."_".$this->selectedMonth."_".$this->selectedYear.'.xlsx');
    }

    public function projectIndex(Project $project): Response
    {
        $users = DB::table('entries')
            ->where('project_id', $project->id)
            ->where('users.name', 'LIKE', '%' . $this->searchValue . '%')
            ->whereMonth('timestamps.day', $this->selectedMonth)
            ->whereYear('timestamps.day', $this->selectedYear)
            ->join('timestamps', 'timestamps.id', '=', 'entries.timestamp_id')
            ->join('users', 'timestamps.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name')
            ->select('users.id', 'users.name')
            ->selectRaw("SUM(entries.working_hours) as hours")
            ->get();

        $timestamps = DB::table('entries')
            ->where('project_id', $project->id)
            ->where('users.name', 'LIKE', '%' . $this->searchValue . '%')
            ->whereMonth('timestamps.day', $this->selectedMonth)
            ->whereYear('timestamps.day', $this->selectedYear)
            ->join('timestamps', 'timestamps.id', '=', 'entries.timestamp_id')
            ->join('work_types', 'entries.work_type_id', '=', 'work_types.id')
            ->join('users', 'timestamps.user_id', '=', 'users.id')
            ->orderBy('timestamps.day', 'asc')
            ->select('work_types.name as work_type', 'working_hours as hour', 'timestamps.day as day', 'users.name as user', )
            ->paginate(25)
            ->appends(request()->query());

        return Inertia::render('Admin/Project/ExcelCreate', [
            'timestamps' => $timestamps,
            'project' => $project,
            'searchValue' => $this->searchValue,
            'dateValue' => $this->dateValue,
            'users' => $users
        ]);
    }

    public function exportProject(Project $project): BinaryFileResponse
    {
        return Excel::download(new ProjectExport($project->id, $this->selectedMonth, $this->selectedYear), 'Raport_'.$project->name."_".$this->selectedMonth."_".$this->selectedYear.'.xlsx');
    }

    public function setDate(): void
    {
        if ($this->dateValue === null || $this->dateValue === 'Invalid date') {
            $this->dateValue = Carbon::now()->format('Y-m');
        }
    }

    public function userHolidayIndex(User $user): Response
    {
        $holidayMax = $this->setMaxHoliday($user);
        $holidayLeft = $this->setLeftHoliday($user);

        $timestamps = DB::table('timestamps')
            ->where('user_id', $user->id)
            ->where('work_types.is_holiday', true)
            ->whereYear('timestamps.day', $this->selectedYear)
            ->join('entries', 'timestamps.id', '=', 'entries.timestamp_id')
            ->join('work_types', 'entries.work_type_id', '=', 'work_types.id')
            ->orderBy('timestamps.day', 'desc')
            ->select('work_types.name as work_type', 'timestamps.day as day', 'entries.working_hours as hours')
            ->paginate(25)
            ->appends(request()->query());

        return Inertia::render('Admin/User/Holiday/ExcelCreate', [
            'timestamps' => $timestamps,
            'user' => $user,
            'dateValue' => $this->dateValue,
            'holidayMax' => $holidayMax,
            'holidayLeft' => $holidayLeft
        ]);
    }

    public function userHolidayExport(User $user): BinaryFileResponse
    {
        return Excel::download(new HolidayExport($user->id, $this->selectedYear), 'Urlopy_'.$user->name."_".$this->selectedYear.'.xlsx');
    }

    public function setMaxHoliday(User $user)
    {
        if(DB::table('user_holidays')->where('user_id', $user->id)->where('year', $this->selectedYear)->exists()){
            $holidayMax = DB::table('user_holidays')
                ->where('user_id', $user->id)
                ->where('year', $this->selectedYear)
                ->select('max_holiday')
                ->first();
            $holidayVal = $holidayMax->max_holiday;
        }else{
            $holidayVal = 0;
        }
        return $holidayVal;
    }

    public function setLeftHoliday(User $user)
    {
        $holidayUsed = DB::table('timestamps')
            ->where('user_id', $user->id)
            ->where('work_types.is_holiday', '1')
            ->whereYear('timestamps.day', $this->selectedYear)
            ->join('entries', 'timestamps.id', '=', 'entries.timestamp_id')
            ->join('work_types', 'entries.work_type_id', '=', 'work_types.id')
            ->select('timestamps.id')
            ->distinct()
            ->get();
        $holidayUsed = count($holidayUsed);
        $holidayMax = $this->setMaxHoliday($user);
        $holidayLeft = $holidayMax - $holidayUsed;
        return $holidayLeft;
    }
}
