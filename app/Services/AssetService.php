<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use Exception;

class AssetService
{
    /**
     * Create a new asset within a refrigeration system.
     */
    public function createAsset(array $data)
    {
        if (!empty($data['parent_id'])) {
            $parent = Asset::findOrFail($data['parent_id']);
            if ($parent->refrigeration_system_id != $data['refrigeration_system_id']) {
                throw new Exception("Parent asset belongs to a different refrigeration system.");
            }
        }

        return Asset::create($data);
    }

    /**
     * Update an asset, preventing circular hierarchy references.
     */
    public function updateAsset(Asset $asset, array $data)
    {
        // Nullify parent_id explicitely if empty string passed from forms
        if (empty($data['parent_id'])) {
            $data['parent_id'] = null;
        }

        if (!empty($data['parent_id'])) {
            if ($asset->id == $data['parent_id']) {
                throw new Exception("An asset cannot be its own parent.");
            }

            if ($this->wouldCreateCircularReference($asset->id, (int)$data['parent_id'])) {
                throw new Exception("Circular reference detected. Cannot assign a descendant as a parent.");
            }

            $parent = Asset::findOrFail($data['parent_id']);
            if ($parent->refrigeration_system_id != $data['refrigeration_system_id']) {
                throw new Exception("Parent asset belongs to a different refrigeration system.");
            }
        }

        $asset->update($data);
        return $asset;
    }

    /**
     * Delete an asset and cascade/remove children if necessary.
     */
    public function deleteAsset(Asset $asset)
    {
        DB::transaction(function () use ($asset) {
            $this->deleteRecursive($asset);
        });
    }

    protected function deleteRecursive(Asset $asset)
    {
        // Load children safely
        foreach ($asset->children as $child) {
            $this->deleteRecursive($child);
        }
        $asset->delete();
    }

    /**
     * Check for circular dependencies implicitly.
     */
    protected function wouldCreateCircularReference($assetId, $newParentId)
    {
        $currentParentId = $newParentId;
        
        while ($currentParentId) {
            if ($currentParentId == $assetId) {
                return true;
            }
            $parent = Asset::find($currentParentId);
            $currentParentId = $parent ? $parent->parent_id : null;
        }
        
        return false;
    }
}
