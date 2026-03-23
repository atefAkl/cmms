<?php

namespace App\Repositories;

use App\Models\AssetTemplate;

class AssetTemplateRepository
{
    /**
     * Get all root level asset templates with their immediate children.
     * Use recursion or deep loading if hierarchy depth expands past 2 levels.
     */
    public function getRootTemplates()
    {
        return AssetTemplate::whereNull('parent_id')->with('children')->get();
    }
}
