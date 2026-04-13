
<?php




// // --- الميثودز المجمعة (s) ---

// // ميثود العرض: تنادي getUserAddresses
// public function getAddresses(User $user) : array
//  {
//     return $user->addresses()->get();
//     // $addresses = $this->getUserAddresses($user);
//     // return $addresses;


//  }

// // ميثود الإضافة: تنادي (resetOtherDefaults لو محتاج) + StoreAddress
// public function createAddress(User $user, array $data) {
//     return::transaction(function () use ($user, $data) {
//         if ($data['is_default']) {
//             $this->resetOtherDefaults($user, $data);
//         }
//        $address= $this->StoreAddress($user, $data);
//         return [
//     'address' => $address,
//     'user' => $user];
//     });
//  }

// // ميثود التعديل: تنادي (findUserAddressOrFail) + (resetOtherDefaults لو محتاج) + UpdateAddress
// public function updateAddress(User $user, $addressId, array $data) {
//     return::transaction(function () use ($user, $data ,$addressId) {

//         $oldAddress =$this->findUserAddressOrFail($addressId, $user);
//         if ($data['is_default']) {
//             $this->UpdateDefaultAddress($user, $data);
//         }
//         $address= $this->UpdateAddress($oldAddress, $data);
//         return [
//     'address' => $address,
//     'user' => $user];
//     });
//  }

// // ميثود الحذف: تنادي (findUserAddressOrFail) + DeleteAddress
// public function deleteAddress(User $user, $addressId) {
//     return::transaction(function () use ($user, $addressId) {
//         $address = $this->findUserAddressOrFail($addressId, $user);
//         $this->DeleteAddress($address);

//     });

//  }
// }







// // --- الميثودز الصغيرة (Actions) ---

// // 1. تجيب كل عناوين المستخدم
// // protected function getUserAddresses($user) {
// //     $addresses =$user->addresses()->get();
// //     return $addresses;
// //  }

// // 2. تجيب عنوان واحد بالـ ID وتتأكد إنه موجود (وإنه يخص المستخدم)
// protected function findUserAddressOrFail($addressId, $user) {
//     $address =$user->addresses()->find($addressId);
//     if (!$address) {
//         throw new \Exception('Address not found', 404);
//     }
//     return $address;
//  }

// // 3. لو العنوان الجديد ديفولت، تخلي باقي عناوين المستخدم مش ديفولت
// protected function resetOtherDefaults($user, $data) {
//             if ($data['is_default']) {
//             $user->addresses()->update(['is_default' => false]);
//         }
//  }

// // 4. الحفظ الفعلي (Create)
// protected function StoreAddress($user, array $data) {

//     $newAddress = $user->addresses()->create($data);
//     return $newAddress;
//  }

// // 5. التعديل الفعلي (Update)
// protected function UpdateAddress($address, array $data) {
//     $address->update($data);
//     return $address;
//  }

// // 6. الحذف الفعلي (Delete)
// protected function DeleteAddress($address) {
//     $address->delete();
//  }


//  // 5. التعديل الديفولت لو الي هيتحذف كلن ديفولت (Update)
// protected function UpdateDefaultAddress($user,$address, array $data) {
//            if($address->is_default){
//             $nextAddress = $user->addresses()->where('id', '!=' , $address->id)->first();
//             if($nextAddress){
//                 $nextAddress->update(['is_default' => true]);
//             }
//         }
//  }



<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserAddressService
{
    /**
     * Get all user addresses
     */
    public function getAddresses(User $user): Collection
    {
        return $user->addresses()->get();
    }

    /**
     * Create new address
     */
    public function createAddress(User $user, array $data): UserAddress
    {
        return DB::transaction(function () use ($user, $data) {
            // Reset other defaults if needed
            if ($data['is_default'] ?? false) {
                $this->resetOtherDefaults($user);
            }

            // Create address
            return $user->addresses()->create($data);
        });
    }

    /**
     * Update address
     */
    public function updateAddress(User $user, int $addressId, array $data): UserAddress
    {
        return DB::transaction(function () use ($user, $addressId, $data) {
            // Find address
            $address = $this->findUserAddressOrFail($addressId, $user);

            // Reset other defaults if needed
            if ($data['is_default'] ?? false) {
                $this->resetOtherDefaults($user);
            }

            // Update
            $address->update($data);

            return $address->fresh();
        });
    }

    /**
     * Delete address
     */
    public function deleteAddress(User $user, int $addressId): void
    {
        DB::transaction(function () use ($user, $addressId) {
            // Find address
            $address = $this->findUserAddressOrFail($addressId, $user);

            // If default, set next as default
            if ($address->is_default) {
                $this->setNextAddressAsDefault($user, $address->id);
            }

            // Delete
            $address->delete();
        });
    }

    /**
     * Find user address or fail
     */
    protected function findUserAddressOrFail(int $addressId, User $user): UserAddress
    {
        $address = $user->addresses()->find($addressId);

        if (!$address) {
            throw new \Exception('Address not found', 404);
        }

        return $address;
    }

    /**
     * Reset other addresses to non-default
     */
    protected function resetOtherDefaults(User $user): void
    {
        $user->addresses()->update(['is_default' => false]);
    }

    /**
     * Set next address as default (when deleting default address)
     */
    protected function setNextAddressAsDefault(User $user, int $excludeId): void
    {
        $nextAddress = $user->addresses()
            ->where('id', '!=', $excludeId)
            ->first();

        if ($nextAddress) {
            $nextAddress->update(['is_default' => true]);
        }
    }
}
