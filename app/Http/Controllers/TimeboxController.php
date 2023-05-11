<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeboxRequest;
use App\Models\Entry;
use App\Models\Project;
use App\Models\Timestamp;
use App\Models\WorkType;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\HolidayService;

class TimeboxController extends Controller
{
    protected HolidayService $holidayService;
    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function index(): Response
    {
        return Inertia::render('Welcome', [
            'last_loged' => Auth::user()->timestamps()->with('entries', 'entries.project', 'entries.workType')->latest('day')->first(),
            'timestamps' => Auth::user()->timestamps()->with('entries', 'entries.project', 'entries.workType')->get()->groupBy('day'),
            'work_types' => WorkType::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'remember_last_project' => Auth::user()->remember_last_project,
            'holiday_left' => $this->holidayService->setLeftHoliday(Auth::id()),
            'timestamp_draft' => Auth::user()->timestampDraft()->with('entriesDraft', 'entriesDraft.project', 'entriesDraft.workType')->first()
        ]);
    }

    public function save(TimeboxRequest $request, $timeboxDate)
    {
        $carbonToday = Carbon::createFromDate($timeboxDate)->format('Y-m-d');
        $this->timeCheck($request->validated());
        try {
            if (!Timestamp::where('day', $carbonToday)->where('user_id', Auth::id())->first()) {
                DB::beginTransaction();
                $timestamp = $this->updateOrCreateTimestamp($request->validated(), $carbonToday);
                $this->attachEntriesToTimestamp($timestamp, $request->get('fields'));
                Auth::user()->timestampDraft()->delete();
                $remember = Auth::User();
                $remember->remember_last_project = $request->input('remember_last_project');
                $remember->save();
                DB::commit();
                return redirect()->route('home');
            } else {
                throw ValidationException::withMessages(['day' => 'Nie można dodać więcej niż 1 wpisu na dany dzień.']);
            }
        } catch (ValidationException $validationError) {
            DB::rollBack();
            return back()->withErrors(['day' => $validationError->getMessage()]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    private function updateOrCreateTimestamp(Array $timestampData, String $parsedRequestDay): Timestamp
    {
        $daily_working_hours = $this->setDailyWorkingHours($timestampData);
        $timestampData = array_replace($timestampData, ["day" => $parsedRequestDay]);
        return Timestamp::updateOrCreate(
            ["day" => $parsedRequestDay, "user_id" => Auth::id()],
            array_merge($timestampData, [
                "daily_working_hours" => $daily_working_hours
            ])
        );
    }

    private function attachEntriesToTimestamp(Timestamp $timestamp, Array $entries)
    {
        foreach ($entries as $entry) {
            Entry::create([
                'work_type_id' => Arr::get($entry, 'work_type'),
                'project_id' => Arr::get($entry, 'project'),
                'working_hours' => Arr::get($entry, 'working_hours'),
                'description' => Arr::get($entry, 'description'),
                'timestamp_id' => $timestamp->id
            ]);
        }
    }

    public function edit($timeboxDate)
    {
        if (strtotime($timeboxDate) === false) {
            return redirect()->route('home')->with('message', 'Podana data do edycji nie istnieje');
        }
        $carbonToday = Carbon::createFromDate($timeboxDate)->format('Y-m-d');
        if (Timestamp::where('day', $carbonToday)->where('user_id', Auth::id())->first()) {
            return Inertia::render('Edit', [
                'timestamp' => Auth::user()->timestamps()->with('entries', 'entries.project', 'entries.workType')->where('day', $carbonToday)->first(),
                'work_types' => WorkType::orderBy('name')->get(),
                'projects' => Project::orderBy('name')->get()
            ]);
        } else {
        return redirect()->route('home')->with('message', 'Tej daty nie można edytować');
        }
    }

    public function update($timeboxDate, TimeboxRequest $request)
    {
        $carbonToday = Carbon::createFromDate($timeboxDate)->format('Y-m-d');
        $this->timeCheck($request->validated());
        try {
            if (Timestamp::where('day', $carbonToday)->where('user_id', Auth::id())->first()) {
                DB::beginTransaction();
                $timestamp = $this->updateOrCreateTimestamp($request->validated(), $carbonToday);
                $this->updateEntries($timestamp, $request->get('fields'));
                DB::commit();
                return redirect()->route('home');
            } else {
                throw ValidationException::withMessages(['day' => 'Nie można edytować tej daty.']);
            }
        } catch (ValidationException $validationError) {
            DB::rollBack();
            return back()->withErrors(['day' => $validationError->getMessage()]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function delete(Timestamp $timestamp)
    {
        $timestamp->delete();
        return redirect()->route('home');
    }

    private function updateEntries(Timestamp $timestamp, Array $entries)
    {
        $timestamp->entries()->delete();
        $this->attachEntriesToTimestamp($timestamp, $entries);
    }

    private function timeCheck(Array $timestampData)
    {
        //Sprawdzenie czy ilość godzin się zgadza
        $summedHours = 0;
        $fields = Arr::get($timestampData, 'fields');
        foreach($fields as $field) {
            $hours = Arr::get($field, 'working_hours');
            $summedHours += $hours;
        }
        $dailyWorkingHours = $this->setDailyWorkingHours($timestampData);
        if ($summedHours != $dailyWorkingHours) {
            throw ValidationException::withMessages(['start_time' => 'Oblicz ponownie godziny']);
        }
    }

    private function setDailyWorkingHours(Array $timestampData)
    {
        // Sprawdzamy ile godzin zostało wpisane patrząc na początek i koniec.
        $start_hour = Carbon::createFromDate(Arr::get($timestampData, 'start_time'))->format('H');
        $start_minute = Carbon::createFromDate(Arr::get($timestampData, 'start_time'))->format('i');
        $start = Carbon::create(2023, 1, 1, $start_hour, $start_minute, 00);
        $end_hour = Carbon::createFromDate(Arr::get($timestampData, 'end_time'))->format('H');
        $end_minute = Carbon::createFromDate(Arr::get($timestampData, 'end_time'))->format('i');
        $end = Carbon::create(2023, 1, 1, $end_hour, $end_minute, 00);
        return ($start->diffInMinutes($end))/60;
    }
}
