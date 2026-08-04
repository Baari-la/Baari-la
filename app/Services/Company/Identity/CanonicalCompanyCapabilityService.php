<?php

declare(strict_types=1);

namespace App\Services\Company\Identity;

use App\Models\CompanyIdentity;
use App\Models\User;

class CanonicalCompanyCapabilityService
{
    /**
     * Build canonical capability profile.
     */
    public function buildFromUser(User $user): array
    {
        if (!$user->company_identity_id) {
            return $this->emptyProfile();
        }

        return $this->build($user->company_identity_id);
    }

    /**
     * Build from canonical identity.
     */
    public function build(
        CompanyIdentity|int $identity
    ): array {

        if (!$identity instanceof CompanyIdentity) {

            $identity = CompanyIdentity::query()
                ->with('capabilityProfile')
                ->find($identity);

        }

        if (!$identity) {
            return $this->emptyProfile();
        }

        $profile = $identity->capabilityProfile;

        return [

            'record_type' => 'company_capability',

            'company_identity_id' => $identity->id,

            'profile_exists' => (bool) $profile,

            /*
            |--------------------------------------------------------------------------
            | Capacity Intelligence™
            |--------------------------------------------------------------------------
            */

            // Installed Capacity
            'production_capacity'
                => $profile?->production_capacity,

            'production_capacity_unit'
                => $profile?->production_capacity_unit,

            // Current Utilized Capacity
            'current_utilized_capacity'
                => $profile?->current_utilized_capacity,

            'current_utilized_capacity_unit'
                => $profile?->current_utilized_capacity_unit,

            // Monthly / Annual
            'monthly_capacity'
                => $profile?->monthly_capacity,

            'annual_capacity'
                => $profile?->annual_capacity,

            /*
            |--------------------------------------------------------------------------
            | Auto Calculated (Accessor)
            |--------------------------------------------------------------------------
            */

            'available_capacity'
                => $profile?->available_capacity,

            'capacity_utilization'
                => $profile?->capacity_utilization,

            'factory_status'
                => $profile?->factory_status,

            /*
            |--------------------------------------------------------------------------
            | Commercial
            |--------------------------------------------------------------------------
            */

            'minimum_order_quantity'
                => $profile?->minimum_order_quantity,

            'minimum_order_unit'
                => $profile?->minimum_order_unit,

            'lead_time_days'
                => $profile?->lead_time_days,

            'sampling_service'
                => $profile?->sampling_service ?? false,

            'export_ready'
                => $profile?->export_ready ?? false,

            /*
            |--------------------------------------------------------------------------
            | Manufacturing Services
            |--------------------------------------------------------------------------
            */

            'supports_oem'
                => $profile?->supports_oem ?? false,

            'supports_odm'
                => $profile?->supports_odm ?? false,

            'supports_private_label'
                => $profile?->supports_private_label ?? false,

            'supports_full_package'
                => $profile?->supports_full_package ?? false,

            'supports_cmt'
                => $profile?->supports_cmt ?? false,

            'supports_design_support'
                => $profile?->supports_design_support ?? false,

            /*
            |--------------------------------------------------------------------------
            | Production Flexibility
            |--------------------------------------------------------------------------
            */

            'supports_small_batch'
                => $profile?->supports_small_batch ?? false,

            'supports_fast_sampling'
                => $profile?->supports_fast_sampling ?? false,

            'supports_quick_response'
                => $profile?->supports_quick_response ?? false,

            'supports_custom_development'
                => $profile?->supports_custom_development ?? false,

        ];
    }

    /**
     * Empty capability profile.
     */
    protected function emptyProfile(): array
    {
        return [

            'record_type' => 'company_capability',

            'company_identity_id' => null,

            'profile_exists' => false,

            /*
            |--------------------------------------------------------------------------
            | Capacity Intelligence™
            |--------------------------------------------------------------------------
            */

            'production_capacity' => null,

            'production_capacity_unit' => null,

            'current_utilized_capacity' => null,

            'current_utilized_capacity_unit' => null,

            'monthly_capacity' => null,

            'annual_capacity' => null,

            'available_capacity' => null,

            'capacity_utilization' => null,

            'factory_status' => null,

            /*
            |--------------------------------------------------------------------------
            | Commercial
            |--------------------------------------------------------------------------
            */

            'minimum_order_quantity' => null,

            'minimum_order_unit' => null,

            'lead_time_days' => null,

            'sampling_service' => false,

            'export_ready' => false,

            /*
            |--------------------------------------------------------------------------
            | Manufacturing Services
            |--------------------------------------------------------------------------
            */

            'supports_oem' => false,

            'supports_odm' => false,

            'supports_private_label' => false,

            'supports_full_package' => false,

            'supports_cmt' => false,

            'supports_design_support' => false,

            /*
            |--------------------------------------------------------------------------
            | Production Flexibility
            |--------------------------------------------------------------------------
            */

            'supports_small_batch' => false,

            'supports_fast_sampling' => false,

            'supports_quick_response' => false,

            'supports_custom_development' => false,

        ];
    }
}