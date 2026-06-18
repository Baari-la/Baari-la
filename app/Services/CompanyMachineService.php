<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyMachine;

class CompanyMachineService
{
    public static function syncMachines(
        Company $company,
        array $machines
    ): void {

      if (empty($machines)) {
        return;
    }
        $processedIds = [];

        foreach ($machines as $machine) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($machine['machine_type']) &&
                empty($machine['machine_brand'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($machine['id'])) {

                $record = CompanyMachine::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $machine['id']
                )
                ->first();

                if ($record) {

                    $record->update([

                        'machine_category' =>
                            $machine['machine_category'] ?? null,

                        'machine_type' =>
                            $machine['machine_type'] ?? null,

                        'machine_brand' =>
                            $machine['machine_brand'] ?? null,

                        'machine_model' =>
                            $machine['machine_model'] ?? null,

                        'quantity' =>
                            $machine['quantity'] ?? 0,

                        'production_capacity' =>
                            $machine['production_capacity'] ?? null,

                        'capacity_unit' =>
                            $machine['capacity_unit'] ?? null,

                        'energy_consumption' =>
                            $machine['energy_consumption'] ?? null,

                        'energy_unit' =>
                            $machine['energy_unit'] ?? 'kwh/hour',

                        'working_width' =>
                            $machine['working_width'] ?? null,

                        'gauge_specification' =>
                            $machine['gauge_specification'] ?? null,

                        'year_installed' =>
                            $machine['year_installed'] ?? null,

                        'machine_condition' =>
                            $machine['machine_condition'] ?? 'good',

                        'automation_level' =>
                            $machine['automation_level'] ?? 'automatic',

                        'country_origin' =>
                            $machine['country_origin'] ?? null,

                        'is_active' =>
                            $machine['is_active'] ?? true,

                        'notes' =>
                            $machine['notes'] ?? null,
                    ]);

                    $processedIds[] = $record->id;

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW
            |--------------------------------------------------------------------------
            */

           $newMachine = $company->machines()->create([

    'machine_category' =>
        $machine['machine_category'] ?? null,

    'machine_type' =>
        $machine['machine_type'] ?? null,

    'machine_brand' =>
        $machine['machine_brand'] ?? null,

    'machine_model' =>
        $machine['machine_model'] ?? null,

    'quantity' =>
        $machine['quantity'] ?? 0,

    'production_capacity' =>
        $machine['production_capacity'] ?? null,

    'capacity_unit' =>
        $machine['capacity_unit'] ?? null,

    'energy_consumption' =>
        $machine['energy_consumption'] ?? null,

    'energy_unit' =>
        $machine['energy_unit'] ?? 'kwh/hour',

    'working_width' =>
        $machine['working_width'] ?? null,

    'gauge_specification' =>
        $machine['gauge_specification'] ?? null,

    'year_installed' =>
        $machine['year_installed'] ?? null,

    'machine_condition' =>
        $machine['machine_condition'] ?? 'good',

    'automation_level' =>
        $machine['automation_level'] ?? 'automatic',

    'country_origin' =>
        $machine['country_origin'] ?? null,

    'is_active' =>
        $machine['is_active'] ?? true,

    'notes' =>
        $machine['notes'] ?? null,
]);

            $processedIds[] = $newMachine->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED MACHINES
        |--------------------------------------------------------------------------
        */

        $company->machines()
            ->whereNotIn('id', $processedIds)
            ->delete();
    }
}