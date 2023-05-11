<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

class HolidayService
{
    protected ?int $year;

    public function __construct()
    {
        $this->year = Carbon::now()->year;
    }

    public function setMaxHoliday($id)
    {
        if(DB::table('user_holidays')->where('user_id', $id)->where('year', $this->year)->exists()){
            $holidayMax = DB::table('user_holidays')
                ->where('user_id', $id)
                ->where('year', $this->year)
                ->select('max_holiday')
                ->first();
            $holidayVal = $holidayMax->max_holiday;
        }else{
            $holidayVal = 0;
        }
        return $holidayVal;
    }

    public function setLeftHoliday($id)
    {
        $holidayUsed = DB::table('timestamps')
            ->where('user_id', $id)
            ->where('work_types.is_holiday', '1')
            ->whereYear('timestamps.day', $this->year)
            ->join('entries', 'timestamps.id', '=', 'entries.timestamp_id')
            ->join('work_types', 'entries.work_type_id', '=', 'work_types.id')
            ->select('timestamps.id')
            ->distinct()
            ->get();
        $holidayUsed = count($holidayUsed);
        $holidayMax = $this->setMaxHoliday($id);
        $holidayLeft = $holidayMax - $holidayUsed;
        return $holidayLeft;
    }
}