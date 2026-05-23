<?php
namespace App\Services\User;

use App\Exceptions\User\mobile\MobileNotFoundException;
use App\Models\User;
use App\Models\UserMobile;
use Illuminate\Database\Eloquent\Collection;

class UserMobileService
{
    public function getMobiles(User $user): Collection
    {
        return $user->mobiles()->get();
    }

        public function getMobileById(User $user, int $mobileId): UserMobile
        {
            return $this->findUserMobileOrFail($mobileId, $user);
        }

    public function createMobile(User $user, array $data): UserMobile {

        return $user->mobiles()->create($data);
     }

    public function deleteMobile(User $user, int $mobileId): void {
        $mobile = $this->findUserMobileOrFail($mobileId, $user);
        $mobile->delete();
     }

    protected function findUserMobileOrFail(int $mobileId, User $user): UserMobile {
        $mobile = $user->mobiles()->find($mobileId);
        if (!$mobile) {
           throw new MobileNotFoundException();        }
        return $mobile;
     }
}
