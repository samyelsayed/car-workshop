<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Service\ServiceRequest;
use App\Http\Requests\Api\Admin\Service\UpdateServiceRequest;
use App\Http\Resources\Admin\ServiceResource;
use App\Http\Traits\ApiTrait;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    use ApiTrait;

    public function index(Request $request){

      if($request->is_active == 'true')
        {
            $services = Service::where('is_active', true)->latest()->paginate(10);
        }elseif($request->is_active == 'false'){
            $services = Service::where('is_active', false)->latest()->paginate(10);
        }else{
            $services = Service::latest()->paginate(10);
        }

      $resourceCollection = ServiceResource::collection($services);
      return $this->Data([$resourceCollection], 'Services retrieved successfully', 200);

    }




    public function store(ServiceRequest $request){
        $imageName = 'default.png';
         if($request->hasFile('image')){
            $image = $request->file('image');
            // $imageName = time(). '-'.$image->getClientOriginalName();
            $imageName =  Str::uuid().'.'.$image->getClientOriginalExtension();

            $image->move(public_path('images/services'),$imageName);

        }
    $addService = Service::create([
        'name'        => $request->name,
        'description' => $request->description,
        'base_price'  => $request->base_price,
        'is_active'   => $request->is_active?? false,
        'image'       => $imageName,
    ]);

      return $this->Data(['addService'=>new ServiceResource($addService)],'add Service Successful',201);

    }



      public function update(UpdateServiceRequest $request, $id){
        $service = Service::findOrFail($id);
        $oldImage = $service ->getRawOriginal('image'); // Get the original image name from the database
            if($request->hasFile('image')){
                if($oldImage && file_exists(public_path('images/services/'.$oldImage))&& $oldImage != 'default.png'){
                    // unLink(public_path('images/services/'.$oldImage));
                    Storage::disk('public')->delete($oldImage);
                    }
                $image = $request->file('image');
                // $imageName = time(). '-'.$image->getClientOriginalName();
                $imageName =  Str::uuid().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('images/services'),$imageName);
            }

          $service->update([
                'name'        => $request->name,
                'description' => $request->description,
                'base_price'  => $request->base_price,
                'is_active'   => $request->is_active?? false,
                'image'       => $imageName,
             ]);
        return $this->Data(['service'=>new ServiceResource($service)],'Service Updated Successfully',200);
    }

 public function destroy(Request $request, $id){
    $service = Service::findOrFail($id);
    $oldImage = $service->getRawOriginal('image');
    if($oldImage && $oldImage != 'default.png'){
        $path = public_path('images/services/' . $oldImage);
        if(file_exists($path)) {
            unlink($path);
        }
    }
   $service->delete();
           return $this->SuccessMessage('Service delated Successfully',200);

}


}
