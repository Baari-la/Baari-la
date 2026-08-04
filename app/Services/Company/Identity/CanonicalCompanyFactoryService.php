<?php

declare(strict_types=1);

namespace App\Services\Company\Identity;

use App\Models\Company;
use App\Models\CompanyFactory;
use App\Models\CompanyIdentity;
use App\Models\CompanyMachine;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;

class CanonicalCompanyFactoryService
{
    /*
    |--------------------------------------------------------------------------
    | Build Factory Passport
    |--------------------------------------------------------------------------
    */

    public function buildFromUser(User $user): array
{
    $identity = CompanyIdentity::query()
        ->with([
            'factories.primaryMachine',
        ])
        ->find($user->company_identity_id);
        
    if (! $identity) {
        return [
            'record_type' => 'factory',
            'factory_exists' => false,
        ];
    }

    $factory = $identity
    ->factories()
    ->orderByDesc('is_main_factory')
    ->orderByDesc('is_headquarters')
    ->orderBy('display_order')
    ->orderByDesc('id')
    ->first();

    if (! $factory) {
        return [
            'record_type' => 'factory',
            'factory_exists' => false,
        ];
    }

    $machine = $factory->primaryMachine;


    return [

        'record_type' => 'factory',

        'factory_exists' => true,

        /*
        |--------------------------------------------------------------------------
        | Factory
        |--------------------------------------------------------------------------
        */

        'factory' => [

            'id' => $factory->id,

            'uuid' => $factory->uuid,

            'factory_code' => $factory->factory_code,

            'factory_slug' => $factory->factory_slug,

            'factory_name' => $factory->factory_name,

            'factory_type' => $factory->factory_type,

            'factory_status' => $factory->factory_status,

            'country' => $factory->country,

            'province' => $factory->province,

            'city' => $factory->city,

            'address' => $factory->address,

            'production_lines' => $factory->production_lines,

            'number_of_shifts' => $factory->number_of_shifts,

            'quality_control_system' => $factory->quality_control_system,

            'compliance_standards' => $factory->compliance_standards,

            'is_headquarters' => $factory->is_headquarters,

            'is_main_factory' => $factory->is_main_factory,

            'visibility_status' => $factory->visibility_status,

            'verification_status' => $factory->verification_status,

        ],

        /*
        |--------------------------------------------------------------------------
        | Primary Machine
        |--------------------------------------------------------------------------
        */

        'primary_machine' => [

            'machine_category' => $machine?->machine_category,

            'machine_type' => $machine?->machine_type,

            'machine_brand' => $machine?->machine_brand,

            'machine_model' => $machine?->machine_model,

            'quantity' => $machine?->quantity,

            'year_installed' => $machine?->year_installed,

            'country_origin' => $machine?->country_origin,

            'production_capacity' => $machine?->production_capacity,

            'capacity_unit' => $machine?->capacity_unit,

            'automation_level' => $machine?->automation_level,

        ],

    ];
}

    /*
    |--------------------------------------------------------------------------
    | Get Or Create Default Factory
    |--------------------------------------------------------------------------
    */

    public function getOrCreateDefaultFactory(
        CompanyIdentity $identity
    ): CompanyFactory {

        return $identity
            ->factories()
            ->first() ??
            $this->createDefaultFactory($identity);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Default Factory
    |--------------------------------------------------------------------------
    */

    public function createDefaultFactory(
        CompanyIdentity $identity,
        array $attributes = []
    ): CompanyFactory {

        return CompanyFactory::firstOrCreate(

            [
                'company_identity_id' => $identity->id,
                'is_main_factory' => true,
            ],

            [
                'uuid' => (string) Str::uuid(),

                'factory_code' => $this->generateFactoryCode(),

                'factory_name' => $attributes['factory_name']
                    ?? $identity->canonical_name,

                'factory_slug' => $this->generateSlug(
                    $attributes['factory_name']
                        ?? $identity->canonical_name
                ),

                'factory_type' => $attributes['factory_type']
                    ?? 'MANUFACTURING',

                'factory_status' => 'ACTIVE',

                'visibility_status' => 'VISIBILITY_PRIVATE',

                'verification_status' => 'VERIFICATION_PENDING',

                'is_headquarters' => true,

                'is_main_factory' => true,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Factory
    |--------------------------------------------------------------------------
    */

    public function updateFactory(
        CompanyFactory $factory,
        array $attributes
    ): CompanyFactory {

        $factory->fill($attributes);

        if (
            empty($factory->factory_slug)
            && ! empty($factory->factory_name)
        ) {
            $factory->factory_slug = $this->generateSlug(
                $factory->factory_name
            );
        }

        $factory->save();

        return $factory->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Factory Code
    |--------------------------------------------------------------------------
    */

    protected function generateFactoryCode(): string
    {
        return 'DGX-FAC-'
            . now()->format('Y')
            . '-'
            . str_pad(
                (string) random_int(1, 999999),
                6,
                '0',
                STR_PAD_LEFT
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Factory Slug
    |--------------------------------------------------------------------------
    */

    protected function generateSlug(
        string $factoryName
    ): string {

        return Str::slug($factoryName);
    }
/**
 * Create the primary machine for a factory.
 */
/**
 * Create the primary machine for a factory.
 */
public function createPrimaryMachine(
    CompanyFactory $factory,
    array $attributes
): CompanyMachine {

    /*
    |--------------------------------------------------------------------------
    | Resolve Legacy Company
    |--------------------------------------------------------------------------
    |
    | During the transition period, CompanyMachine still requires company_id.
    | Resolve it automatically from the linked Company Identity.
    |
    */

    $company = Company::query()
        ->where(
            'company_identity_id',
            $factory->company_identity_id
        )
        ->first();

      
    if (! $company) {
        throw new \RuntimeException(
            'No Company found for Company Identity ID: '
            . $factory->company_identity_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Primary Machine
    |--------------------------------------------------------------------------
    */

    return $factory
        ->machines()
        ->create([

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            'company_id' => $company->id,

            'company_identity_id' => $factory->company_identity_id,

            /*
            |--------------------------------------------------------------------------
            | Factory Passport
            |--------------------------------------------------------------------------
            */

            'is_primary' => true,

            'is_active' => true,

            /*
            |--------------------------------------------------------------------------
            | Machine Identity
            |--------------------------------------------------------------------------
            */

            'machine_category' => $attributes['machine_category'] ?? null,

            'machine_type' => $attributes['machine_type'] ?? null,

            'machine_brand' => $attributes['machine_brand'] ?? null,

            'machine_model' => $attributes['machine_model'] ?? null,

            'quantity' => $attributes['quantity'] ?? 1,

            'year_installed' => $attributes['year_installed'] ?? null,

            'country_origin' => $attributes['country_origin'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Production
            |--------------------------------------------------------------------------
            */

            'production_capacity' => $attributes['production_capacity'] ?? null,

            'capacity_unit' => $attributes['capacity_unit'] ?? null,

            'working_width' => $attributes['working_width'] ?? null,

            'gauge_specification' => $attributes['gauge_specification'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Operations
            |--------------------------------------------------------------------------
            */

            'machine_condition' => $attributes['machine_condition'] ?? 'GOOD',

            'automation_level' => $attributes['automation_level'] ?? 'SEMI_AUTOMATIC',

            'energy_consumption' => $attributes['energy_consumption'] ?? null,

            'energy_unit' => $attributes['energy_unit'] ?? null,

            'notes' => $attributes['notes'] ?? null,

        ]);
}

/**
 * Create Digital Factory Passport.
 */
public function saveFactoryPassport(
    CompanyIdentity $identity,
    array $factoryData,
    array $machineData
): CompanyFactory {

    return DB::transaction(function () use (

        $identity,
        $factoryData,
        $machineData

    ) {

        /*
        |--------------------------------------------------------------------------
        | Save Factory
        |--------------------------------------------------------------------------
        */

        $factory = $this->saveFactory(
            $identity,
            $factoryData
        );

        /*
        |--------------------------------------------------------------------------
        | Save Primary Machine
        |--------------------------------------------------------------------------
        */

        $this->savePrimaryMachine(
            $factory,
            $machineData
        );

        /*
        |--------------------------------------------------------------------------
        | Refresh Relationships
        |--------------------------------------------------------------------------
        */

        return $factory->fresh([

            'machines',

            'primaryMachine',

        ]);

    });

}

private function buildFactoryPayload(
    CompanyIdentity $identity,
    array $attributes
): array {

    return [

        /*
        |--------------------------------------------------------------------------
        | Identity
        |--------------------------------------------------------------------------
        */

        'company_identity_id' => $identity->id,

        /*
        |--------------------------------------------------------------------------
        | Factory Information
        |--------------------------------------------------------------------------
        */

        'factory_name' => $attributes['factory_name']
            ?? $identity->canonical_name,

        'factory_type' => $attributes['factory_type']
            ?? 'MANUFACTURING',

        'factory_status' => 'ACTIVE',

        /*
        |--------------------------------------------------------------------------
        | Location
        |--------------------------------------------------------------------------
        */

        'country' => $attributes['country'] ?? null,

        'province' => $attributes['province'] ?? null,

        'city' => $attributes['city'] ?? null,

        'address' => $attributes['address'] ?? null,

        /*
        |--------------------------------------------------------------------------
        | Manufacturing
        |--------------------------------------------------------------------------
        */

        'production_lines' => $attributes['production_lines'] ?? null,

        'number_of_shifts' => $attributes['number_of_shifts'] ?? null,

        'quality_control_system' => $attributes['quality_control_system'] ?? null,

        'compliance_standards' => $attributes['compliance_standards'] ?? null,

    ];

}

public function saveFactory(
    CompanyIdentity $identity,
    array $attributes
): CompanyFactory {

    $factory = CompanyFactory::query()

        ->where(
            'company_identity_id',
            $identity->id
        )

        ->where(
            'is_main_factory',
            true
        )

        ->first();

    $payload = $this->buildFactoryPayload(
        $identity,
        $attributes
    );

    if ($factory) {

    
        $factory->fill($payload);

        $factory->save();

        return $factory;
    }

    return CompanyFactory::create($payload);
}

public function savePrimaryMachine(
    CompanyFactory $factory,
    array $attributes
): CompanyMachine {

    $company = Company::query()

        ->where(
            'company_identity_id',
            $factory->company_identity_id
        )

        ->firstOrFail();

    $machine = $factory
        ->primaryMachine()
        ->first();

    $payload = $this->buildMachinePayload(

        $factory,

        $company,

        $attributes

    );

    if ($machine) {

        $machine->fill($payload);

        $machine->save();

        return $machine;
    }

    return $factory
        ->machines()
        ->create($payload);
}

private function buildMachinePayload(
    CompanyFactory $factory,
    Company $company,
    array $attributes
): array {

    return [

        /*
        |--------------------------------------------------------------------------
        | Relationships
        |--------------------------------------------------------------------------
        */

        'company_id' => $company->id,

        'company_identity_id' => $factory->company_identity_id,

        /*
        |--------------------------------------------------------------------------
        | Factory Passport
        |--------------------------------------------------------------------------
        */

        'is_primary' => true,

        'is_active' => true,

        /*
        |--------------------------------------------------------------------------
        | Machine Identity
        |--------------------------------------------------------------------------
        */

        'machine_category' => $attributes['machine_category'] ?? null,

        'machine_type' => $attributes['machine_type'] ?? null,

        'machine_brand' => $attributes['machine_brand'] ?? null,

        'machine_model' => $attributes['machine_model'] ?? null,

        'quantity' => $attributes['quantity'] ?? 1,

        'year_installed' => $attributes['year_installed'] ?? null,

        'country_origin' => $attributes['country_origin'] ?? null,

        /*
        |--------------------------------------------------------------------------
        | Production
        |--------------------------------------------------------------------------
        */

        'production_capacity' => $attributes['production_capacity'] ?? null,

        'capacity_unit' => $attributes['capacity_unit'] ?? null,

        'working_width' => $attributes['working_width'] ?? null,

        'gauge_specification' => $attributes['gauge_specification'] ?? null,

        /*
        |--------------------------------------------------------------------------
        | Operations
        |--------------------------------------------------------------------------
        */

        'machine_condition' => $attributes['machine_condition'] ?? 'GOOD',

        'automation_level' => $attributes['automation_level'] ?? 'SEMI_AUTOMATIC',

        'energy_consumption' => $attributes['energy_consumption'] ?? null,

        'energy_unit' => $attributes['energy_unit'] ?? null,

        'notes' => $attributes['notes'] ?? null,

    ];

}
    }