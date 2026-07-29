<?php

declare(strict_types=1);

namespace App\Services\Company\Classification;

final class BusinessRoleResolver
{
    /**
     * Exact aliases that can safely be converted
     * into canonical DIGESTEX business role IDs.
     */
    private const ALIASES = [

        /*
        |--------------------------------------------------------------------------
        | Fiber
        |--------------------------------------------------------------------------
        */

        'fiber_supplier' => 'fiber_manufacturer',
        'fiber_processor' => 'fiber_manufacturer',
        'filament_producer' => 'filament_fiber_manufacturer',

        /*
        |--------------------------------------------------------------------------
        | Yarn / Spinning
        |--------------------------------------------------------------------------
        */

        'spinner' => 'yarn_spinner',
        'thread_supplier' => 'sewing_thread_manufacturer',

        /*
        |--------------------------------------------------------------------------
        | Fabric
        |--------------------------------------------------------------------------
        */

        'knitted_fabric_manufacturer' => 'knitting_mill',
        'flat_knitting_manufacturer' => 'knitting_mill',
        'woven_fabric_manufacturer' => 'weaving_mill',

        /*
        |--------------------------------------------------------------------------
        | Dyeing / Printing / Finishing
        |--------------------------------------------------------------------------
        */

        'dyeing_finishing' => 'dyeing_finishing_mill',
        'textile_printer' => 'printing_mill',
        'digital_printing' => 'digital_printing_company',
        'finishing' => 'dyeing_finishing_mill',
        'fabric_finishing' => 'dyeing_finishing_mill',

        /*
        |--------------------------------------------------------------------------
        | Garment
        |--------------------------------------------------------------------------
        */

        'cmt_manufacturer' => 'garment_manufacturer',
        'oem_manufacturer' => 'garment_manufacturer',
        'odm_manufacturer' => 'garment_manufacturer',
        'private_label_manufacturer' => 'garment_manufacturer',
        'obm_manufacturer' => 'fashion_manufacturer',

        /*
        |--------------------------------------------------------------------------
        | Accessories
        |--------------------------------------------------------------------------
        */

        'accessories_manufacturer' => 'accessories_supplier',
        'trim_supplier' => 'accessories_supplier',
        'label_supplier' => 'accessories_supplier',

        /*
        |--------------------------------------------------------------------------
        | Supporting Industry
        |--------------------------------------------------------------------------
        */

        'chemical_supplier' => 'textile_chemical_supplier',
        'machinery_supplier' => 'textile_machinery_supplier',
        'machinery_distributor' => 'textile_machinery_supplier',

        /*
        |--------------------------------------------------------------------------
        | Testing / Certification
        |--------------------------------------------------------------------------
        */

        'testing_service_provider' => 'testing_laboratory',
        'certification_provider' => 'certification_body',

        /*
        |--------------------------------------------------------------------------
        | Logistics
        |--------------------------------------------------------------------------
        */

        'shipping_provider' => 'logistics_provider',
        'air_logistics_provider' => 'logistics_provider',
        'customs_service_provider' => 'logistics_provider',
        'warehouse_service_provider' => 'warehouse_provider',
        'third_party_logistics' => 'logistics_provider',

        /*
        |--------------------------------------------------------------------------
        | Trading
        |--------------------------------------------------------------------------
        */

        'trader' => 'trading_company',
    ];

    /**
     * Resolve any role ID into the canonical
     * DIGESTEX Business Role taxonomy.
     *
     * Returns null when the role cannot safely
     * be resolved.
     */
    public function resolve(?string $role): ?string
    {
        $role = $this->normalize($role);

        if ($role === null) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Already Canonical
        |--------------------------------------------------------------------------
        */

        if ($this->isCanonical($role)) {
            return $role;
        }

        /*
        |--------------------------------------------------------------------------
        | Exact Alias
        |--------------------------------------------------------------------------
        */

        return self::ALIASES[$role] ?? null;
    }

    /**
     * Determine whether role exists in the
     * canonical Business Role Knowledge Base.
     */
    public function isCanonical(string $role): bool
    {
        return in_array(
            $role,
            $this->canonicalRoles(),
            true
        );
    }

    /**
     * Return canonical role IDs from master data.
     */
    public function canonicalRoles(): array
    {
        return collect(
            config('masterdata.Business.business_roles', [])
        )
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Return known exact aliases.
     */
    public function aliases(): array
    {
        return self::ALIASES;
    }

    /**
     * Normalize incoming role vocabulary.
     */
    private function normalize(?string $role): ?string
    {
        if ($role === null) {
            return null;
        }

        $role = strtolower(trim($role));

        if ($role === '') {
            return null;
        }

        $role = preg_replace('/[^a-z0-9]+/', '_', $role);

        $role = trim((string) $role, '_');

        return $role !== ''
            ? $role
            : null;
    }
}