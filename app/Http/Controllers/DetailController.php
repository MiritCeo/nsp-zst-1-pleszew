<?php

namespace App\Http\Controllers;

use App\Models\Timestamp;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DetailController extends Controller
{
    protected ?int $selectedMonth;
    protected ?int $selectedYear;
    protected ?string $dateValue;

    public function __construct(Request $request)
    {
        $this->dateValue = $request?->query('date');
        $this->setDate();
        $this->selectedMonth = Carbon::create($this->dateValue)->month;
        $this->selectedYear = Carbon::create($this->dateValue)->year;
    }

    public function details(): Response
    {
        return Inertia::render('Detail', [
            'timestamps' => Timestamp::where('user_id', Auth::id())->whereMonth('day', $this->selectedMonth)->whereYear('day', $this->selectedYear)->with(['entries.workType', 'entries.project', 'entries', 'user'])->orderBy('day', 'asc')->get(),
            'dateValue' => $this->dateValue
        ]);
    }

    public function setDate(): void
    {
        if ($this->dateValue === null || $this->dateValue === 'Invalid date' || strtotime($this->dateValue) === false) {
            $this->dateValue = Carbon::now()->format('Y-m');
        }
    }
}