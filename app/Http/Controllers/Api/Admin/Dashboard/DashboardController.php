<?php

namespace App\Http\Controllers\Api\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiTrait;

    public function __construct(protected DashboardService $dashboardService) {}

    /**
     * جلب كافة إحصائيات لوحة التحكم
     */
    public function index(): JsonResponse
    {
        $stats = $this->dashboardService->getDashboardStats();

        return $this->Data($stats, __('messages.dashboard_stats_retrieved'));
    }
}
