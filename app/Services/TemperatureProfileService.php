<?php

namespace App\Services;

use App\Models\TemperatureProfile;
use Illuminate\Pagination\LengthAwarePaginator;

class TemperatureProfileService
{
    public function paginate(int $perPage = 15, ?string $search = null, ?string $productType = null): LengthAwarePaginator
    {
        $query = TemperatureProfile::query()->withCount('assignments');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($productType) {
            $query->where('product_type', $productType);
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data): TemperatureProfile
    {
        return TemperatureProfile::create($data);
    }

    public function update(TemperatureProfile $profile, array $data): TemperatureProfile
    {
        $profile->update($data);
        return $profile;
    }

    public function delete(TemperatureProfile $profile): bool
    {
        if ($profile->assignments()->count() > 0) {
            throw new \Exception("Cannot delete a profile that is actively used or has historical assignments.");
        }
        
        return $profile->delete();
    }
}
