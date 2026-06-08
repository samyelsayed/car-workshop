<?php

namespace App\Services\Admin;

use App\Exceptions\Service\ServiceNotFoundException;
use App\Http\Traits\HandlesImageUpload;
use App\Models\Service;


class AdminService
{
    use HandlesImageUpload; // استخدام التريت هنا أيضاً

    // تحديد الفولدر الخاص بصور الخدمات
    protected string $folder = 'images/services';
    
    public function getAllServices(array $filters, int $perPage = 10)
    {

      return Service::query()
      ->when(filled($filters['is_active'] ?? null) , function ($query) use($filters){
       $active = filter_var($filters['is_active'],FILTER_VALIDATE_BOOLEAN);
       $query->where('is_active',$active);
      })
      ->latest()->paginate($perPage);

    }


    public function toggleServiceStatus(int $id): Service
{
    $service = $this->getServiceById($id);

    $service->update([
        'is_active' => !$service->is_active
    ]);
    $service->refresh();
    return $service;
}

    // public function storeService(array $data)
    // {

    //     if(isset($data['image'])){
    //         $data['image'] = $this->uploadImage($data['image']);
    //     }else{
    //         $data['image'] = 'default.png';
    //     }
    //     return Service::create($data);
    // }

    /**
     * إنشاء خدمة جديدة
     */
    public function storeService(array $data): Service
    {
        if (isset($data['image'])) {
            // استخدام التريت لرفع الصورة
            $data['image'] = $this->uploadImage($data['image'], $this->folder);
        } else {
            // المسار الافتراضي المتوافق مع هيكلة التريت
            $data['image'] = 'images/services/default.png'; 
        }

        return Service::create($data);
    }

    public function getServiceById(int $id): Service
    {

        $service = Service::find($id);
       if (!$service) {
            throw new ServiceNotFoundException();
        }
        return $service;
    }


    // public function updateService(int $id, array $data)
    // {
    //      $service = $this->getServiceById($id);

    //     if(isset($data['image'])){
    //         $this->deleteOldImage($service->getRawOriginal('image'));
    //     $data['image'] = $this->uploadImage($data['image']);
    //   }
    //    $service->update($data);
    //     return $service;
    // }



public function updateService(int $id, array $data): Service
    {
        $service = $this->getServiceById($id);

        if (isset($data['image'])) {
            // التريت هيهندل مسح الصورة القديمة ورفع الجديدة أوتوماتيك بـ سطر واحد!
            $data['image'] = $this->uploadImage($data['image'],$this->folder,$service->image ); // الصورة القديمة للـ Smart Delete
        }

        $service->update($data);
        return $service->fresh();
    }


    public function deleteService(int $id): void
    {
        $service = $this->getServiceById($id);
     
        $service->delete();
    }

    public function forceDeleteService(int $id): void
     {
            $service = Service::withTrashed()->findOrFail($id);
            
            // هنا بقى نمسح الصورة بأمان لأن الخدمة هتموت للأبد
            $this->deleteImage($service->image);
            
            $service->forceDelete();
    }

//    private function uploadImage($image)
//     {
//         $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
//         $image->move(public_path('images/services'), $imageName);
//         return $imageName;
//     }



    // private function deleteOldImage(string $imageName)
    // {
    //    if($imageName && $imageName !=='default.png'){
    //     $path = public_path('images/services/' . $imageName);
    //     if(File::exists($path)){
    //         File::delete($path);
    //     }
    //    }
    // }

    public function restoreService(int $id): Service
    {
        $service = Service::withTrashed()->find($id);

        if (!$service) {
            throw new ServiceNotFoundException();
        }

        $service->restore();

        return $service->fresh();
    }


}
