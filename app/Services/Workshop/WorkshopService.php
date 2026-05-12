<?php

namespace App\Services\Workshop;

use App\Exceptions\Service\ServiceNotFoundException;
use App\Models\Service; // الـ Use ثانياً
use Illuminate\Database\Eloquent\Collection;

class WorkshopService
{
// public function getActiveServices(): Collection
// {
//     return Service::active()->get();
// }

public function getActiveServices(?string $searchTerm = null): Collection
{
    return Service::active()
        ->when($searchTerm, function ($query) use ($searchTerm) {
            // هنا بنقول له: لو فيه كلمة بحث، ضيف الشروط دي
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        })
        ->get();
}


public function findActiveServiceById(int $id){
    $service = Service::active()->find($id);
    if (!$service) {
        throw new ServiceNotFoundException();
    }
    return $service;
}
}
