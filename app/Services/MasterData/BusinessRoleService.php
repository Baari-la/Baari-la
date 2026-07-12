<?php

declare(strict_types=1);

namespace App\Services\MasterData;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Business Role Service
 * ==========================================================================
 *
 * Master Business Roles.
 *
 * Single Source of Truth
 * for all business roles used by DIGESTEX.
 *
 * Used by:
 *
 * • Company Intelligence
 * • Company Passport
 * • Recommendation Engine
 * • Supply Chain Intelligence
 * • Buyer Discovery
 * • Executive AI
 *
 * Version:
 * 1.0
 */
class BusinessRoleService
{
    /**
     * --------------------------------------------------------------------------
     * Master Roles
     * --------------------------------------------------------------------------
     */
    protected Collection $roles;

    public function __construct()
    {
        $this->roles = collect(

            config('masterdata.business_roles', [])

        );
    }

    /**
     * --------------------------------------------------------------------------
     * All
     * --------------------------------------------------------------------------
     */
    public function all(): Collection
    {
        return $this->roles;
    }

    /**
     * --------------------------------------------------------------------------
     * Options
     * --------------------------------------------------------------------------
     */
    public function options(): Collection
    {
        return $this->roles->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Find
     * --------------------------------------------------------------------------
     */
    public function find(string $id): ?array
    {
        return $this->roles

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
     * Role Label
     * --------------------------------------------------------------------------
     */
    public function label(string $id): string
    {
        return $this->find($id)['label']

            ?? $id;
    }

    /**
     * --------------------------------------------------------------------------
     * Role Color
     * --------------------------------------------------------------------------
     */
    public function color(string $id): string
    {
        return $this->find($id)['color']

            ?? '#64748B';
    }

    /**
     * --------------------------------------------------------------------------
     * Role Icon
     * --------------------------------------------------------------------------
     */
    public function icon(string $id): string
    {
        return $this->find($id)['icon']

            ?? '🏭';
    }

    /**
     * --------------------------------------------------------------------------
     * Priority
     * --------------------------------------------------------------------------
     */
    public function priority(string $id): int
    {
        return (int) (

            $this->find($id)['priority']

            ?? 999

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Upstream Roles
     * --------------------------------------------------------------------------
     */
    public function upstream(string $id): Collection
    {
        return collect(

            $this->find($id)['upstream']

            ?? []

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Downstream Roles
     * --------------------------------------------------------------------------
     */
    public function downstream(string $id): Collection
    {
        return collect(

            $this->find($id)['downstream']

            ?? []

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Select Options
     * --------------------------------------------------------------------------
     */
    public function toSelect(): Collection
    {
        return $this->roles

            ->map(function (array $role) {

                return [

                    'value' => $role['id'],

                    'label' => $role['label'],

                ];

            })

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * IDs
     * --------------------------------------------------------------------------
     */
    public function ids(): Collection
    {
        return $this->roles

            ->pluck('id')

            ->values();
    }

    /**
     * --------------------------------------------------------------------------
     * Labels
     * --------------------------------------------------------------------------
     */
    public function labels(): Collection
    {
        return $this->roles

            ->pluck('label')

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

            'total_roles' =>

                $this->roles->count(),

            'highest_priority' =>

                $this->roles

                    ->min('priority'),

            'lowest_priority' =>

                $this->roles

                    ->max('priority'),

        ];
    }
}