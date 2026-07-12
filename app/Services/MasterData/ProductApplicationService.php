<?php

declare(strict_types=1);

namespace App\Services\MasterData;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Product Application Service
 * ==========================================================================
 *
 * Master Product Application Service.
 *
 * Responsible for:
 *
 * • Product application lookup
 * • Dropdown options
 * • Validation
 * • Application metadata
 *
 * Used by:
 *
 * • Company Profile
 * • Marketplace
 * • Buyer Discovery
 * • Product Intelligence
 * • Supply Chain Recommendation
 * • Executive AI
 *
 * Version:
 * 1.0
 */
class ProductApplicationService
{
    /**
     * --------------------------------------------------------------------------
     * Master Applications
     * --------------------------------------------------------------------------
     */
    protected Collection $applications;

    public function __construct()
    {
        $this->applications = collect(

    config('masterdata.product_applications', [])

);
    }

    /**
     * --------------------------------------------------------------------------
     * All Applications
     * --------------------------------------------------------------------------
     */
    public function all(): Collection
    {
        return $this->applications;
    }

    /**
     * --------------------------------------------------------------------------
     * Dropdown Options
     * --------------------------------------------------------------------------
     */
    public function options(): Collection
    {
        return $this->applications->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Find Application
     * --------------------------------------------------------------------------
     */
    public function find(string $id): ?array
    {
        return $this->applications

            ->firstWhere(

                'id',

                strtolower($id)

            );
    }

    /**
     * --------------------------------------------------------------------------
     * Application Exists
     * --------------------------------------------------------------------------
     */
    public function exists(string $id): bool
    {
        return $this->find($id) !== null;
    }
        /**
     * --------------------------------------------------------------------------
     * Application Label
     * --------------------------------------------------------------------------
     */
    public function label(string $id): string
    {
        return $this->find($id)['label']

            ?? $id;
    }

    /**
     * --------------------------------------------------------------------------
     * Application IDs
     * --------------------------------------------------------------------------
     */
    public function ids(): Collection
    {
        return $this->applications

            ->pluck('id')

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Application Labels
     * --------------------------------------------------------------------------
     */
    public function labels(): Collection
    {
        return $this->applications

            ->pluck('label')

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Select Options
     * --------------------------------------------------------------------------
     *
     * Standard dropdown format.
     */
    public function toSelect(): Collection
    {
        return $this->applications

            ->map(function (array $application) {

                return [

                    'value' => $application['id'],

                    'label' => $application['label'],

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

            'total_applications' =>

                $this->applications->count(),

            'active_applications' =>

                $this->applications->count(),

        ];
    }
}