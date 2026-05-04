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
            ->count(),
            'new_users_this_month' => User::where('role','user')
            ->whereDate('created_at','>=',now()->startMonth())
            ->count(),

        ];
    }



        protected function getOrdersStats(): array
    {
        return [
            'total'=> Order::count(),
         'today' => Order::whereDate('created_at', today())
                ->count(),

            'this_week' => Order::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count(),
            'this_month' =>Order::where('created_at','>=', now()->startOfMonth())
            ->count(),

            'by_status'=>[
                'pending'=> Order::where('status','pending')->count(),
                'in_progress'=> Order::where('status','in_progress')->count(),
                'completed'=> Order::where('status','completed')->count(),
                'cancelled'=> Order::where('status','cancelled')->count(),
            ],

        ];
    }

   protected function getRevenueStats(): array
    {
        $completedOrders = Order::where('status','completed');
        return [
        'total'=> (float)(clone $completedOrders)
            ->sum('total_cost'),
        'today'=> (float)(clone $completedOrders)
             ->whereDate('created_at', today())
            ->sum('total_cost'),
        'this_week'=>(float)(clone $completedOrders)
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('total_cost'),
        'this_month'=> (float)(clone $completedOrders)
            ->where('created_at','>=',now()->statOFMonth())
            ->sum('total_cost'),
        ];
    }

    protected function getPopularServices(int $limit = 5): array
    {
        return OrderItem::select(
                'service_id',
                DB::raw('COUNT(*) as orders_count')
            )
            ->with('service:id,name,base_price')
            ->groupBy('service_id')
            ->orderByDesc('orders_count')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'service_id' => $item->service_id,
                    'service_name' => $item->service->name ?? 'Unknown',
                    'base_price' => (float) ($item->service->base_price ?? 0),
                    'orders_count' => $item->orders_count,
                ];
            })
            ->toArray();
    }

    protected function getWeeklyOrdersChart(): array
{
    return Order::selectRaw('date(created_at) as date, count(*) as count')
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('count', 'date')
        ->toArray();
}

}