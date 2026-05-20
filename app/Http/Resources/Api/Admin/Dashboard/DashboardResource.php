<?php

namespace App\Http\Resources\Admin\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * كل اللي بنعمله هنا إننا بنرتب شكل الداتا اللي رايحة للفرونت إند
     */
    public function toArray(Request $request): array
    {
        return [
            // بناخد المفاتيح اللي إنت عرفتها في السيرفس ونعرضها هنا مباشرة
            'users'            => $this['users'],
            'orders'           => $this['orders'],
            'revenue'          => $this['revenue'],
            'popular_services' => $this['popular_services'],
            'weekly_chart'     => $this['weekly_chart'] ?? [],
            'last_updated'     => $this['last_updated'],
        ];
    }
}
