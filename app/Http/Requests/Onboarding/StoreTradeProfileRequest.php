<?php

declare(strict_types=1);

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreTradeProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            'trade_roles' => [
                'nullable',
                'array',
            ],

            'trade_roles.*' => [
                'string',
                'max:100',
            ],

            'export_experience' => [
                'nullable',
                'string',
                'max:100',
            ],

            'export_since' => [
                'nullable',
                'digits:4',
            ],

            'export_countries' => [
                'nullable',
                'array',
            ],

            'export_countries.*' => [
                'string',
            ],

            'import_countries' => [
                'nullable',
                'array',
            ],

            'import_countries.*' => [
                'string',
            ],

            'main_industries' => [
                'nullable',
                'array',
            ],

            'main_industries.*' => [
                'string',
            ],

            'domestic_markets' => [
                'nullable',
                'array',
            ],

            'domestic_markets.*' => [
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Phase 2
            |--------------------------------------------------------------------------
            */

            'export_products' => [
                'nullable',
                'array',
            ],

            'import_products' => [
                'nullable',
                'array',
            ],

            'trade_notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

        ];
    }
}