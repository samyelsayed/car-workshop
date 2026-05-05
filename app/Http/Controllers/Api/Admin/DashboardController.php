<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiTrait;
use App\Services\Admin\DashboardService;
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

        return $this->Data($stats, 'Dashboard statistics retrieved successfully');
    }
}
