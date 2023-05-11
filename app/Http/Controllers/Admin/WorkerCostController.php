<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerCostRequest;
use App\Models\User;
use App\Models\UserHoliday;
use App\Models\WorkerCost;
use Carbon\Carbon;
use Inertia\Inertia;

class WorkerCostController extends Controller
{
    public function index(User $user)
    {
        $holiday = UserHoliday::select('max_holiday')
            ->where('user_id', $user->id)
            ->where('year', Carbon::now()->format('Y'))
            ->first();
        $holidayValue = 0;
        if ($holiday !== null) {
            $holidayValue = $holiday->max_holiday;
        }
        return Inertia::render('Admin/User/Cost/Index', [
            'user' => $user,
            'holiday' => $holidayValue,
            'workerCost' => WorkerCost::where('user_id', $user->id)->first()
        ]);
    }

    public function save(User $user, WorkerCostRequest $request)
    {
        WorkerCost::updateOrCreate(
            ['user_id' => $user->id],
            [
                'hours' => $request->hours,
                'hourly_wage' => $request->wage,
                'tax' => $request->tax,
                'total_cost' => $request->cost
            ]
        );
        return redirect()->route('admin.user.cost', $user);
    }
}
