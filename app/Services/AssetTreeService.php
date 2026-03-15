<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Support\Collection;

class AssetTreeService
{
    /**
     * Build a nested tree structure for assets of a specific system.
     */
    public function buildTree(int $systemId): Collection
    {
        $assets = Asset::where('refrigeration_system_id', $systemId)->get();

        return $this->formatTree($assets);
    }

    /**
     * Format a collection of assets into a tree structure.
     */
    public function formatTree(Collection $assets, $parentId = null): Collection
    {
        $tree = collect();

        foreach ($assets->where('parent_id', $parentId) as $asset) {
            $asset->children = $this->formatTree($assets, $asset->id);
            $tree->push($asset);
        }

        return $tree;
    }

    /**
     * Prevent circular references by checking if the potential parent is a child of the current asset.
     */
    public function wouldCreateCircularReference(int $assetId, int $potentialParentId): bool
    {
        if ($assetId === $potentialParentId) {
            return true;
        }

        $parent = Asset::find($potentialParentId);
        
        while ($parent) {
            if ($parent->id === $assetId) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
    }

    /**
     * Get the full hierarchy path of an asset.
     */
    public function getAssetPath(Asset $asset): Collection
    {
        $path = collect();
        $current = $asset;

        while ($current) {
            $path->prepend($current);
            $current = $current->parent;
        }

        return $path;
    }
}
