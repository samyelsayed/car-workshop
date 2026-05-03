<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Dashboard Statistics
     */
    public function getDashboardStats(): array
    {
        return Cache::remember('admin_dashboard_stats', 600, function () {
            return [
                'users' => $this->getUsersStats(),
                'orders' => $this->getOrdersStats(),
                'revenue' => $this->getRevenueStats(),
                'popular_services' => $this->getPopularServices(),
                'last_updated' => now()->toDateTimeString(),
            ];
        });
    }

      protected function getUsersStats(): array
    {
        return [
            'total_users' => User::where('role','user')->count(),
            'total_admin' => User::where('role','admin')->count(),
            'total_all' => User::count(),
            'new_users_today' => User::where('role','user')
            ->whereDate('created_at',today())
        ];
    }
}