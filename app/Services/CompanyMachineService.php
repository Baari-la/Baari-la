<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyMachine;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Company Machine Service
 * ==========================================================================
 *
 * Synchronizes company machinery information.
 *
 * Responsibilities:
 *
 * • Create new machines
 * • Update existing machines
 * • Delete removed machines
 * • Normalize machine payload
 *
 * Used by:
 *
 * • Company Controller
 * • Company Intelligence Engine
 * • Digital Company Passport
 * • Factory Intelligence
 *
 * Version:
 * 2.0
 */
class CompanyMachineService
{
    /**
     * --------------------------------------------------------------------------
     * Synchronize Company Machines
     * --------------------------------------------------------------------------
     */
    public function syncMachines(
        Company $company,
        array $machines,
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Nothing To Process
        |--------------------------------------------------------------------------
        */

        if (empty($machines)) {
            return;
        }

        $processedIds = [];

        /*
        |--------------------------------------------------------------------------
        | Synchronize Machines
        |--------------------------------------------------------------------------
        */

        foreach ($machines as $machine) {

            /*
            |--------------------------------------------------------------------------
            | Skip Empty Row
            |--------------------------------------------------------------------------
            */

            if ($this->isEmptyMachine($machine)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Update Existing Machine
            |--------------------------------------------------------------------------
            */

            if (! empty($machine['id'])) {

                $record = CompanyMachine::query()

                    ->where('company_id', $company->id)

                    ->where('id', $machine['id'])

                    ->first();

                if ($record) {

                    $this->updateMachine(

                        machine: $record,

                        payload: $machine,

                    );

                    $processedIds[] = $record->id;

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Create New Machine
            |--------------------------------------------------------------------------
            */

            $created = $this->createMachine(

                company: $company,

                payload: $machine,

            );

            $processedIds[] = $created->id;
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Removed Machines
        |--------------------------------------------------------------------------
        */

        $this->deleteRemovedMachines(

            company: $company,

            processedIds: $processedIds,

        );

    }

        /**
     * --------------------------------------------------------------------------
     * Update Existing Machine
     * --------------------------------------------------------------------------
     */
    protected function updateMachine(
        CompanyMachine $machine,
        array $payload,
    ): void {

        $machine->update(

            $this->machinePayload($payload)

        );

    }

    /**
     * --------------------------------------------------------------------------
     * Create New Machine
     * --------------------------------------------------------------------------
     */
    protected function createMachine(
        Company $company,
        array $payload,
    ): CompanyMachine {

        return $company

            ->machines()

            ->create(

                $this->machinePayload($payload)

            );

    }

    /**
     * --------------------------------------------------------------------------
     * Machine Payload
     * --------------------------------------------------------------------------
     *
     * Normalizes machine payload for both create and update.
     */
    protected function machinePayload(
        array $machine,
    ): array {

        return [

            /*
            |--------------------------------------------------------------------------
            | Machine Identity
            |--------------------------------------------------------------------------
            */

            'machine_category' =>
                $machine['machine_category'] ?? null,

            'machine_type' =>
                $machine['machine_type'] ?? null,

            'machine_brand' =>
                $machine['machine_brand'] ?? null,

            'machine_model' =>
                $machine['machine_model'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Production
            |--------------------------------------------------------------------------
            */

            'quantity' =>
                (int) ($machine['quantity'] ?? 0),

            'production_capacity' =>
                $machine['production_capacity'] ?? null,

            'capacity_unit' =>
                $machine['capacity_unit'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Energy
            |--------------------------------------------------------------------------
            */

            'energy_consumption' =>
                $machine['energy_consumption'] ?? null,

            'energy_unit' =>
                $machine['energy_unit'] ?? 'kwh/hour',

            /*
            |--------------------------------------------------------------------------
            | Technical Specification
            |--------------------------------------------------------------------------
            */

            'working_width' =>
                $machine['working_width'] ?? null,

            'gauge_specification' =>
                $machine['gauge_specification'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Installation
            |--------------------------------------------------------------------------
            */

            'year_installed' =>
                $machine['year_installed'] ?? null,

            'country_origin' =>
                $machine['country_origin'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'machine_condition' =>
                $machine['machine_condition'] ?? 'good',

            'automation_level' =>
                $machine['automation_level'] ?? 'automatic',

            'is_active' =>
                (bool) ($machine['is_active'] ?? true),

            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            'notes' =>
                $machine['notes'] ?? null,
        ];
    }

        /**
     * --------------------------------------------------------------------------
     * Determine Empty Machine Row
     * --------------------------------------------------------------------------
     *
     * Prevents empty rows from being stored.
     */
    protected function isEmptyMachine(
        array $machine,
    ): bool {

        return

            empty($machine['machine_type'])

            &&

            empty($machine['machine_brand']);

    }

    /**
     * --------------------------------------------------------------------------
     * Delete Removed Machines
     * --------------------------------------------------------------------------
     *
     * Removes machines that no longer exist in the submitted payload.
     */
    protected function deleteRemovedMachines(
        Company $company,
        array $processedIds,
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Nothing Processed
        |--------------------------------------------------------------------------
        */

        if (empty($processedIds)) {

            return;
        }

        $company

            ->machines()

            ->whereNotIn(

                'id',

                $processedIds,
            )
            ->delete();
    }
}