<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyCapacity;

class CompanyCapacityService
{
    public static function syncCapacities(
        Company $company,
        array $capacities
    ): void {

 if (empty($capacities)) {
        return;
    }
    
        $processedIds = [];

        foreach ($capacities as $capacity) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($capacity['capacity_type']) &&
                empty($capacity['item_name'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($capacity['id'])) {

                $record = CompanyCapacity::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $capacity['id']
                )
                ->first();
                if ($record) {

                    $record->update([

                        'capacity_type' =>
                            $capacity['capacity_type'] ?? null,

                        'item_name' =>
                            $capacity['item_name'] ?? null,

                        'capacity_value' =>
                            $capacity['capacity_value'] ?? null,

                        'capacity_unit' =>
                            $capacity['capacity_unit'] ?? null,

                        'capacity_category' =>
                            $capacity['capacity_category'] ?? null,

                        'machine_count' =>
                            $capacity['machine_count'] ?? null,

                        'shift_info' =>
                            $capacity['shift_info'] ?? null,

                        'notes' =>
                            $capacity['notes'] ?? null,
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

            $newCapacity = $company->capacities()->create([
    'capacity_type' =>
        $capacity['capacity_type'] ?? null,
    'capacities.*.id' =>'nullable|integer',
    'item_name' =>
        $capacity['item_name'] ?? null,

    'capacity_value' =>
        $capacity['capacity_value'] ?? null,

    'capacity_unit' =>
        $capacity['capacity_unit'] ?? null,

    'capacity_category' =>
        $capacity['capacity_category'] ?? null,

    'machine_count' =>
        $capacity['machine_count'] ?? null,

    'shift_info' =>
        $capacity['shift_info'] ?? null,

    'notes' =>
        $capacity['notes'] ?? null,
]);

            $processedIds[] = $newCapacity->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED RECORDS
        |--------------------------------------------------------------------------
        */

        if (!empty($processedIds)) {

    $company->capacities()
        ->whereNotIn('id', $processedIds)
        ->delete();

} 
    }     
}