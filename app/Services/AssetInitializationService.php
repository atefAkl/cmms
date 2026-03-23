<?php

namespace App\Services;

use App\Models\RefrigerationSystem;
use App\Models\AssetTemplate;
use App\Models\Asset;
use App\Repositories\AssetTemplateRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class AssetInitializationService
{
    protected $templateRepo;

    public function __construct(AssetTemplateRepository $templateRepo)
    {
        $this->templateRepo = $templateRepo;
    }

    /**
     * Bootstraps an empty refrigeration system with assets from base templates.
     * 
     * @param RefrigerationSystem $system
     * @param array $config (e.g., ['compressors_count' => 3, 'evaporators_count' => 2])
     */
    public function initialize(RefrigerationSystem $system, array $config)
    {
        // Safety Catch: Do NOT allow initialization if system already has assets.
        if ($system->assets()->count() > 0) {
            throw new Exception('This refrigeration system has already been initialized with assets.');
        }

        $rootTemplates = $this->templateRepo->getRootTemplates();

        DB::transaction(function () use ($rootTemplates, $system, $config) {
            foreach ($rootTemplates as $template) {
                // Determine if this template is repeatable (like compressors) rather than singleton (like a control panel)
                $isRepeatable = $template->metadata['is_repeatable'] ?? false;
                
                if ($isRepeatable) {
                    // Extract exact count mapping to config (e.g. 'compressor' -> 'compressors_count')
                    $configKey = strtolower($template->type) . 's_count'; 
                    $count = $config[$configKey] ?? 1; // Default to at least 1 if not provided
                    
                    for ($i = 1; $i <= $count; $i++) {
                        $this->createAssetHierarchy($template, $system->id, null, " #$i");
                    }
                } else {
                    // Singular components attached once per system
                    $this->createAssetHierarchy($template, $system->id);
                }
            }
        });
    }

    /**
     * Recursively walks the template tree to clone structures into literal records.
     */
    protected function createAssetHierarchy(AssetTemplate $template, $systemId, $parentId = null, $suffix = "")
    {
        // 1. Map template -> localized asset
        $asset = Asset::create([
            'name' => $template->name . $suffix, // Append numbering ONLY to the root parent template explicitly
            'type' => $template->type,
            'parent_id' => $parentId,
            'refrigeration_system_id' => $systemId,
            'status' => 'operational' // Initial default state
        ]);

        // 2. Map direct sub-components recursively
        foreach ($template->children as $childTemplate) {
            // Child components don't receive the "#1" suffix, they inherently belong to "Compressor #1"
            $this->createAssetHierarchy($childTemplate, $systemId, $asset->id);
        }

        return $asset;
    }
}
