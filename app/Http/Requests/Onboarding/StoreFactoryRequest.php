<?php

declare(strict_types=1);

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactoryRequest extends FormRequest
{
    /**
     * Determine whether the authenticated user
     * can create a factory.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Factory Identity
            |--------------------------------------------------------------------------
            */

            'factory_name' => [
                'required',
                'string',
                'max:255',
            ],

            'factory_type' => [
                'required',
                'string',
                'max:100',
            ],

            'is_headquarters' => [
                'nullable',
                'boolean',
            ],

            'is_main_factory' => [
                'nullable',
                'boolean',
            ],

            'factory_established_year' => [
                'nullable',
                'integer',
                'min:1800',
                'max:' . now()->year,
            ],

            /*
            |--------------------------------------------------------------------------
            | Factory Location
            |--------------------------------------------------------------------------
            */

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'province' => [
                'nullable',
                'string',
                'max:150',
            ],

            'city' => [
                'required',
                'string',
                'max:150',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Factory Facilities
            |--------------------------------------------------------------------------
            */

            'land_area_sqm' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'building_area_sqm' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'number_of_buildings' => [
                'nullable',
                'integer',
                'min:1',
            ],

            /*
            |--------------------------------------------------------------------------
            | Manufacturing Operations
            |--------------------------------------------------------------------------
            */

            'production_lines' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'number_of_shifts' => [
                'nullable',
                'integer',
                'min:1',
                'max:5',
            ],

            /*
            |--------------------------------------------------------------------------
            | Primary Machinery
            |--------------------------------------------------------------------------
            */

            'machine_category' => [
                'required',
                'string',
                'max:255',
            ],

            'machine_brand' => [
                'nullable',
                'string',
                'max:255',
            ],

            'quantity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'year_installed' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . now()->year,
            ],

            'country_origin' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Manufacturing Excellence
            |--------------------------------------------------------------------------
            */

            'quality_control_system' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'compliance_standards' => [
                'nullable',
                'array',
            ],

            'compliance_standards.*' => [
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Factory Identity
            |--------------------------------------------------------------------------
            */

            'factory_name.required' =>
                'Factory name is required.',

            'factory_type.required' =>
                'Please select a factory type.',

            /*
            |--------------------------------------------------------------------------
            | Factory Location
            |--------------------------------------------------------------------------
            */

            'country.required' =>
                'Please select your factory country.',

            'city.required' =>
                'Please enter your factory city.',

            /*
            |--------------------------------------------------------------------------
            | Primary Machinery
            |--------------------------------------------------------------------------
            */

            'machine_category.required' =>
                'Please select your primary machinery category.',

        ];
    }

    /**
     * Human readable attribute names.
     */
    public function attributes(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Factory Identity
            |--------------------------------------------------------------------------
            */

            'factory_name' => 'factory name',
            'factory_type' => 'factory type',
            'factory_established_year' => 'factory established year',

            /*
            |--------------------------------------------------------------------------
            | Factory Location
            |--------------------------------------------------------------------------
            */

            'country' => 'country',
            'province' => 'province',
            'city' => 'city',
            'postal_code' => 'postal code',
            'address' => 'factory address',

            /*
            |--------------------------------------------------------------------------
            | Factory Facilities
            |--------------------------------------------------------------------------
            */

            'land_area_sqm' => 'factory land area',
            'building_area_sqm' => 'building area',
            'number_of_buildings' => 'number of buildings',
            'production_lines' => 'production lines',
            'number_of_shifts' => 'number of shifts',

            /*
            |--------------------------------------------------------------------------
            | Primary Machinery
            |--------------------------------------------------------------------------
            */

            'machine_category' => 'machinery category',
            'machine_brand' => 'machinery brand',
            'quantity' => 'machine quantity',
            'year_installed' => 'year installed',
            'country_origin' => 'country of origin',

            /*
            |--------------------------------------------------------------------------
            | Manufacturing Excellence
            |--------------------------------------------------------------------------
            */

            'quality_control_system' => 'quality control system',
            'compliance_standards' => 'compliance standards',
        ];
    }
}