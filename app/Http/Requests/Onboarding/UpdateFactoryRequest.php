<?php

declare(strict_types=1);

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFactoryRequest extends FormRequest
{
    /**
     * Determine whether the authenticated user
     * can update a factory.
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
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'factory_type' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],

            'is_headquarters' => [
                'sometimes',
                'boolean',
            ],

            'is_main_factory' => [
                'sometimes',
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
                'sometimes',
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
                'sometimes',
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
                'sometimes',
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
        return (new StoreFactoryRequest())->messages();
    }

    /**
     * Human readable attribute names.
     */
    public function attributes(): array
    {
        return (new StoreFactoryRequest())->attributes();
    }
}