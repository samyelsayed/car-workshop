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
