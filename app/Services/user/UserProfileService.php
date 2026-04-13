<?php
namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserProfileService
{

    protected $imagePath = 'images/users';


    public function update(User $user , array $data)
    {

        return DB::transaction(function () use ($user, $data) {
 
        if(isset($data['image'])){
            $this->deleteOldImage($user);
            $data['image']= $this->uploadImage($user,$data);

        }
            $user->update($data);
            return $user->fresh();
        });
    }

    protected function uploadImage(User $user , array $data){
        
            // رفع الصورة الجديدة
        $image = $data['image'];
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path($this->imagePath), $imageName);


        return $imageName;
        
    }



    protected function deleteOldImage(User $user): void{
        $oldImage = $user->getRawOriginal('image');
        
        $fullPath = public_path($this->imagePath.'/'.$oldImage);
        if ($oldImage && file_exists($fullPath) && $oldImage != 'default.png') {
            unlink($fullPath);
        }
    }

}