<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayRequest;
use App\Models\User;
use App\Models\UserHoliday;
use App\Services\ExcelService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    protected ExcelService $excelService;

    public function __construct(ExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    public function index(Request $request): Response
    {
        $searchValue = $request->query('search');
        return Inertia::render('Admin/User/Index', [
            'users' => User::when($searchValue, function($query, $searchValue) {
                $query->where('name', 'LIKE', '%'.$searchValue.'%');
            })->paginate(10)->appends(request()->query()),
            'searchValue' => $searchValue
        ]);
    }

    public function excelCreate(User $user): Response
    {
        return $this->excelService->userIndex($user);
    }

    public function excelExport(User $user): BinaryFileResponse
    {
        return $this->excelService->exportUser($user);
    }

    public function excelHoliday(User $user): Response
    {
        return $this->excelService->userHolidayIndex($user);
    }

    public function excelHolidayExport(User $user): BinaryFileResponse
    {
        return $this->excelService->userHolidayExport($user);
    }

    public function delete(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.user.index');
    }

    public function holidayAmount(User $user): Response
    {
        return Inertia::render('Admin/User/Holiday/Set', [
            'user' => $user,
            'previous' => url()->previous()
        ]);
    }

    public function holidaySet(User $user, HolidayRequest $request): RedirectResponse
    {
        $carbonYear = Carbon::createFromDate($request->get('year'))->addHours(3)->format('Y');
        UserHoliday::updateOrCreate(
            ['user_id' => $user->id, 'year' => $carbonYear],
            ['max_holiday' => $request->days]
        );
        return redirect($request->hidden);
    }
}
