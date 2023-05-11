<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SummaryService;

class SummaryController extends Controller
{
    protected SummaryService $summaryService;

    public function __construct(SummaryService $summaryService)
    {
        $this->summaryService = $summaryService;
    }

    public function user()
    {
        return $this->summaryService->user();
    }

    public function project()
    {
        return $this->summaryService->project();
    }
}
