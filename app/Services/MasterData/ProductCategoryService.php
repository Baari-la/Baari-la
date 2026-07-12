<?php

declare(strict_types=1);

namespace App\Services\MasterData;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Product Category Service
 * ==========================================================================
 *
 * Master Product Category Service.
 *
 * Responsible for:
 *
 * • Product category lookup
 * • Dropdown options
 * • Validation
 * • Category metadata
 *
 * Used by:
 *
 * • Company Profile
 * • Marketplace
 * • Buyer Discovery
 * • Supply Chain Recommendation
 * • Product Intelligence
 * • Executive AI
 *
 * Version:
 * 1.0
 */
class ProductCategoryService
{
    /**
     * --------------------------------------------------------------------------
     * Master Categories
     * --------------------------------------------------------------------------
     */
    protected Collection $categories;

    public function __construct()
    {
        $this->categories = collect(

    config('masterdata.product_categories', [])

);
    }

    /**
     * --------------------------------------------------------------------------
     * All Categories
     * --------------------------------------------------------------------------
     */
    public function all(): Collection
    {
        return $this->categories;
    }

    /**
     * --------------------------------------------------------------------------
     * Options
     * --------------------------------------------------------------------------
     */
    public function options(): Collection
    {
        return $this->categories->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Find
     * --------------------------------------------------------------------------
     */
    public function find(string $id): ?array
    {
        return $this->categories

            ->firstWhere(

                'id',

                strtolower($id)

            );
    }

    /**
     * --------------------------------------------------------------------------
     * Exists
     * --------------------------------------------------------------------------
     */
    public function exists(string $id): bool
    {
        return $this->find($id) !== null;
    }
        /**
     * --------------------------------------------------------------------------
     * Category Label
     * --------------------------------------------------------------------------
     */
    public function label(string $id): string
    {
        return $this->find($id)['label']

            ?? $id;
    }

    /**
     * --------------------------------------------------------------------------
     * Category IDs
     * --------------------------------------------------------------------------
     */
    public function ids(): Collection
    {
        return $this->categories

            ->pluck('id')

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Category Labels
     * --------------------------------------------------------------------------
     */
    public function labels(): Collection
    {
        return $this->categories

            ->pluck('label')

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Select Options
     * --------------------------------------------------------------------------
     *
     * Standard format for dropdowns.
     */
    public function toSelect(): Collection
    {
        return $this->categories

            ->map(function (array $category) {

                return [

                    'value' => $category['id'],

                    'label' => $category['label'],

                ];

            })

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'total_categories' =>

                $this->categories->count(),

            'active_categories' =>

                $this->categories->count(),

        ];
    }
}