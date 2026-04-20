<?php

namespace App\Services\Admin;

use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminService
{
    public function getAllServices(array $filters, int $perPage = 10)
    {

      return Service::query()
      ->when(filled($filters['is_active'] ?? null) , function ($query) use($filters){
       $active = filter_var($filters['is_active'],FILTER_VALIDATE_BOOLEAN);
       $query->where('is_active',$active);
      })
      ->latest()->paginate($perPage);

    }


    public function toggleServiceStatus(int $id)
{
    $service = $this->getServiceById($id);

    // عكس القيمة الحالية
    $service->update([
        'is_active' => !$service->is_active
    ]);

    return $service;
}

    public function storeService(array $data)
    {

        if(isset($data['image'])){
            $data['image'] = $this->uploadImage($data['image']);
        }else{
            $data['image'] = 'default.png';
        }
        return Service::create($data);
    }


    public function getServiceById(int $id)
    {

        $service = Service::find($id);
        if(!$service){
            throw new \Exception('Service not found');
        }
        return $service;
    }


    public function updateService(int $id, array $data)
    {
          $service = Service::find($id);
        if(!$service){
            throw new \Exception('Service not found');
        }
        if(isset($data['image'])){
            $this->deleteOldImage($service->getRawOriginal('image'));
        $data['image'] = $this->uploadImage($data['image']);
      }
       $service->update($data);
        return $service;
    }


    public function deleteService(int $id)
    {
        $service = $this->getServiceById($id);
        $this->deleteOldImage($service->getRawOriginal('image'));
        $service->delete();
    }


private function uploadImage($image)
    {
        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/services'), $imageName);
        return $imageName;
    }



    private function deleteOldImage($imageName)
    {
       if($imageName && $imageName !=='default.png'){
        $path = public_path('images/services/' . $imageName);
        if(File::exists($path)){
            File::delete($path);
        }
       }
    }


    public function toggleServiceStatus(int $id)
{
    $service = $this->getServiceById($id);

    $service->update([
        'is_active' => !$service->is_active
    ]);
    $service->refresh();
    return $service;
}

}
