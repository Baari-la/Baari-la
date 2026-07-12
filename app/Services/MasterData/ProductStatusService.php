<?php

declare(strict_types=1);

namespace App\Services\MasterData;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Product Status Service
 * ==========================================================================
 *
 * Master Product Status Service.
 *
 * Responsible for:
 *
 * • Product status lookup
 * • Dropdown options
 * • Validation
 * • Status metadata
 *
 * Used by:
 *
 * • Company Profile
 * • Marketplace
 * • Product Intelligence
 * • Buyer Discovery
 * • Executive AI
 *
 * Version:
 * 1.0
 */
class ProductStatusService
{
    /**
     * --------------------------------------------------------------------------
     * Master Statuses
     * --------------------------------------------------------------------------
     */
    protected Collection $statuses;

    public function __construct()
    {
        $this->statuses = collect(

    config('masterdata.product_statuses', [])

);
    }

    /**
     * --------------------------------------------------------------------------
     * All Statuses
     * --------------------------------------------------------------------------
     */
    public function all(): Collection
    {
        return $this->statuses;
    }

    /**
     * --------------------------------------------------------------------------
     * Dropdown Options
     * --------------------------------------------------------------------------
     */
    public function options(): Collection
    {
        return $this->statuses->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Find Status
     * --------------------------------------------------------------------------
     */
    public function find(string $id): ?array
    {
        return $this->statuses

            ->firstWhere(

                'id',

                strtolower($id)

            );
    }

    /**
     * --------------------------------------------------------------------------
     * Status Exists
     * --------------------------------------------------------------------------
     */
    public function exists(string $id): bool
    {
        return $this->find($id) !== null;
    }
    /**
     * --------------------------------------------------------------------------
     * Status Label
     * --------------------------------------------------------------------------
     */
    public function label(string $id): string
    {
        return $this->find($id)['label']

            ?? $id;
    }

    /**
     * --------------------------------------------------------------------------
     * Status IDs
     * --------------------------------------------------------------------------
     */
    public function ids(): Collection
    {
        return $this->statuses

            ->pluck('id')

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Status Labels
     * --------------------------------------------------------------------------
     */
    public function labels(): Collection
    {
        return $this->statuses

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
        return $this->statuses

            ->map(function (array $status) {

                return [

                    'value' => $status['id'],

                    'label' => $status['label'],

                ];

            })

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Default Status
     * --------------------------------------------------------------------------
     */
    public function default(): array
    {
        return $this->find('active')

            ?? [

                'id' => 'active',

                'label' => 'Active',

            ];
    }

    /**
     * --------------------------------------------------------------------------
     * Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'total_statuses' =>

                $this->statuses->count(),

            'active_statuses' =>

                $this->statuses->count(),

            'default_status' =>

                $this->default()['id'],

        ];
    }
}
    