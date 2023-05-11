<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timestamp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $searchValue = $request->query('search');
        $dayValue = $request->query('day');
        if ($dayValue === null || $dayValue === 'Invalid date') {
            $dayValue = Carbon::now()->format('Y-m-d');
        }
        return Inertia::render('Admin/Index', [
            "timestamps" => Timestamp::where('day', $dayValue)->with(['entries.workType', 'entries.project', 'entries', 'user'])
            ->when($searchValue, function ($query, $searchValue) {
                $query->whereHas('user', function ($userQuery) use ($searchValue) {
                    $userQuery->where('name', 'LIKE', '%' . $searchValue . '%');
                })->get();
            })->paginate(10)->appends(request()->query()),
            "searchValue" => $searchValue,
            "dayValue" => $dayValue
        ]);
    }
}
