<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ExcelService;

class ProjectController extends Controller
{
    protected ExcelService $excelService;
    public function __construct(ExcelService $excelService)
    {
        $this->excelService = $excelService;
    }
    public function index(Request $request)
    {
        $searchValue = $request->query('search');
        return Inertia::render('Admin/Project/Index', [
            'projects' => Project::when($searchValue, function($query, $searchValue) {
                $query->where('name', 'LIKE', '%'.$searchValue.'%');
            })->paginate(10)->appends(request()->query()),
            'searchValue' => $searchValue
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Project/Create');
    }

    public function store(ProjectRequest $request)
    {
        Project::create($request->validated());
        return redirect()->route('admin.project.index');
    }

    public function edit(Project $project)
    {
        return Inertia::render('Admin/Project/Edit', [
            'project' => $project
        ]);
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return redirect()->route('admin.project.index');
    }

    public function delete(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.project.index');
    }
    public function excelCreate(Project $project)
    {
        return $this->excelService->projectIndex($project);
    }
    public function excelExport(Project $project)
    {
        return $this->excelService->exportProject($project);
    }
}
