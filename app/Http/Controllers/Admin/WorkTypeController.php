<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkTypeRequest;
use App\Models\WorkType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkTypeController extends Controller
{
    public function index(Request $request)
    {
        $searchValue = $request->query('search');
        return Inertia::render('Admin/WorkType/Index', [
            'work_types' => WorkType::when($searchValue, function($query, $searchValue) {
                $query->where('name', 'LIKE', '%'.$searchValue.'%');
            })->paginate(10)->appends(request()->query()),
            'searchValue' => $searchValue
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/WorkType/Create');
    }

    public function store(WorkTypeRequest $request)
    {
        WorkType::create($request->validated());
        return redirect()->route('admin.work_type.index');
    }

    public function edit(WorkType $work_type)
    {
        return Inertia::render('Admin/WorkType/Edit', [
            'work_type' => $work_type
        ]);
    }

    public function update(WorkTypeRequest $request, WorkType $work_type)
    {
        $work_type->update($request->validated());
        return redirect()->route('admin.work_type.index');
    }

    public function delete(WorkType $work_type)
    {
        $work_type->delete();
        return redirect()->route('admin.work_type.index');
    }
}
