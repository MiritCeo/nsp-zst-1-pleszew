<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeboxRequest;
use App\Models\EntryDraft;
use App\Models\TimestampDraft;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TimeboxDraftController extends Controller
{
    public function save(TimeboxRequest $request, $timeboxDate)
    {
        $carbonToday = Carbon::createFromDate($timeboxDate)->format('Y-m-d');
        $this->timeCheck($request->validated());
        try {
            DB::beginTransaction();
            $timestamp = $this->updateOrCreateTimestamp($request->validated(), $carbonToday);
            $this->attachEntriesToTimestamp($timestamp, $request->get('fields'));
            $remember = Auth::User();
            $remember->remember_last_project = $request->input('remember_last_project');
            $remember->save();
            DB::commit();
            return redirect()->route('home');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    private function updateOrCreateTimestamp(Array $timestampData, String $parsedRequestDay): TimestampDraft
    {
        $daily_working_hours = $this->setDailyWorkingHours($timestampData);
        $timestampData = array_replace($timestampData, ["day" => $parsedRequestDay]);
        return TimestampDraft::updateOrCreate(
            ["user_id" => Auth::id()],
            array_merge($timestampData, [
                "day" => $parsedRequestDay,
                "daily_working_hours" => $daily_working_hours
            ])
        );
    }

    private function attachEntriesToTimestamp(TimestampDraft $timestamp, Array $entries)
    {
        $timestamp->entriesDraft()->delete();
        foreach ($entries as $entry) {
            EntryDraft::create([
                'work_type_id' => Arr::get($entry, 'work_type'),
                'project_id' => Arr::get($entry, 'project'),
                'working_hours' => Arr::get($entry, 'working_hours'),
                'description' => Arr::get($entry, 'description'),
                'timestamp_id' => $timestamp->id
            ]);
        }
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
