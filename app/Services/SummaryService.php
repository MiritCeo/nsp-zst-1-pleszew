<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Arr;

class SummaryService
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

    public function user(): Response
    {
        $users = User::with(['workerCosts', 'holidays' => function ($query) {
                $query->where('year', $this->selectedYear)
                ->select('user_id', 'max_holiday');
            }])
            ->where('users.name', 'LIKE', '%' . $this->searchValue . '%')
            ->whereMonth('day', $this->selectedMonth)
            ->whereYear('day', $this->selectedYear)
            ->where('work_types.is_workday', true)
            ->join('timestamps', 'users.id', '=', 'timestamps.user_id')
            ->join('entries', 'entries.timestamp_id', '=', 'timestamps.id')
            ->join('work_types', 'work_types.id', '=', 'entries.work_type_id')
            ->groupBy('users.id', 'users.name')
            ->select('users.name', 'users.id')
            ->selectRaw("SUM(timestamps.daily_working_hours) as hours")
            ->get();

        return Inertia::render('Admin/Summary/User', [
            'users' => $users,
            'dateValue' => $this->dateValue,
            'searchValue' => $this->searchValue
        ]);
    }

    public function setDate(): void
    {
        if ($this->dateValue === null || $this->dateValue === 'Invalid date') {
            $this->dateValue = Carbon::now()->format('Y-m');
        }
    }

    public function project(): Response
    {
        $projects = Project::where('projects.name', 'LIKE', '%' . $this->searchValue . '%')
            ->whereMonth('timestamps.day', $this->selectedMonth)
            ->whereYear('timestamps.day', $this->selectedYear)
            ->join('entries', 'entries.project_id', '=', 'projects.id')
            ->join('timestamps', 'timestamps.id', '=', 'entries.timestamp_id')
            ->groupBy('projects.id', 'projects.name')
            ->select('projects.name', 'projects.id')
            ->selectRaw("SUM(entries.working_hours) as hours")
            ->get();

        $users = User::whereMonth('timestamps.day', $this->selectedMonth)
            ->whereYear('timestamps.day', $this->selectedYear)
            ->join('timestamps', 'timestamps.user_id', '=', 'users.id')
            ->join('entries', 'entries.timestamp_id', '=', 'timestamps.id')
            ->join('worker_costs', 'worker_costs.user_id', '=', 'users.id')
            ->select('users.id', 'project_id', 'working_hours', 'hourly_wage')
            ->get();

        foreach ($projects as $project) {
            $hours = 0;
            $earnings = 0;
            foreach ($users as $user) {
                if ($project->id === $user->project_id) {
                    $hours += $user->working_hours;
                    $earnings += $user->hourly_wage*$user->working_hours;
                }
            }
            $project = Arr::add($project, 'checkHours', $hours);
            $project = Arr::add($project, 'earnings', $earnings);
        }

        return Inertia::render('Admin/Summary/Project', [
            'projects' => $projects,
            'dateValue' => $this->dateValue,
            'searchValue' => $this->searchValue
        ]);
    }
}