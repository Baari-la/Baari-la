<?php

namespace App\Services;

use App\Models\Company;

class CompanyProfileVisibilityService
{
    public static function calculate(
        Company $company
    ): array {

        $profileItems = [

            'Upload Company Logo' =>
            !empty($company->photo_url),

                'Upload Company Brochure' =>
            !empty($company->brochure_url),
            
            'Upload Company Images' =>
            $company->images->count() > 0,
            'Company Description' =>
                !empty($company->alamat_lengkap),

            'Contact Information' =>
                $company->contacts->count() > 0,

            'Website / Email' =>
                !empty($company->email_web),

            
        ];

        $roleItems = self::roleItems(
            $company
        );
$weights = [

    'Upload Company Logo' => 1,
    'Upload Company Brochure' => 1,
    'Upload Company Images' => 1,
    'Company Description' => 1,

    'Contact Information' => 3,

    'Website / Email' => 2,

    'Products & Services' => 3,

    'Certifications' => 3,

    'Production Capacity' => 2,
    'Machinery Information' => 2,
    'Export Markets' => 2,

    'Business Links' => 2,

    'Upload Service Catalog' => 1,
    'Upload Machinery Catalog' => 1,

    'Machinery Specialization' => 3,

    'Add Products' => 3,
    'Add Machinery Information' => 2,
    'Add Production Capacity' => 2,
    'Add Export Markets' => 2,
    'Add Certifications' => 3,
];
        $profileItems = array_merge(
            $profileItems,
            $roleItems
        );

$totalWeight = 0;
$completedWeight = 0;

foreach (
    $profileItems as
    $label => $status
) {

    $weight =
        $weights[$label] ?? 1;

    $totalWeight += $weight;

    if ($status) {
        $completedWeight += $weight;
    }
}

$total = count(
    $profileItems
);

$completed = collect(
    $profileItems
)->filter()->count();


$percentage =
    $totalWeight > 0
        ? round(
            ($completedWeight / $totalWeight)
            * 100
        )
        : 0;
$completedItems = [];
        $missingItems = [];

        foreach (
    $profileItems as $label => $status
) {

    if ($status) {

        $completedItems[] = $label;

    } else {

        $missingItems[] = $label;
    }
}
        return [

           'percentage' => $percentage,

    'completed' => $completed,

    'total' => $total,

    'completed_items' =>
        $completedItems,

    'missing_items' =>
        $missingItems,

    'status' =>
        self::visibilityStatus(
            $percentage
        ),
];
    }

    protected static function visibilityStatus(
        int $score
    ): string {

        if ($score >= 90) {
            return 'Excellent Visibility';
        }

        if ($score >= 75) {
            return 'High Visibility';
        }

        if ($score >= 50) {
            return 'Good Visibility';
        }

        if ($score >= 25) {
            return 'Growing Visibility';
        }

        return 'Getting Started';
    }

    protected static function roleItems(
        Company $company
    ): array {

        switch (
            $company->company_role
        ) {

       case 'testing_certification':

    return [

        'Products & Services' =>
            $company->products()->exists(),

        'Certifications' =>
            $company->certifications()->exists(),

        'Business Links' =>
            $company->links()->exists(),

        'Upload Service Catalog' =>
            !empty($company->catalog_url),
    ];

           case 'machinery_supplier':

    return [

        'Products & Services' =>
    $company->products()->exists(),

        'Upload Machinery Catalog' =>
            !empty($company->catalog_url),

        'Business Links' =>
            $company->links->count() > 0,

        'Website / Email' =>
            !empty($company->email_web),
    ];
    
   case 'dyeing_finishing':

   return [

       'Products & Services' =>
    $company->products()->exists(),
    
        'Machinery Information' =>
            $company->machines->count() > 0,

        'Production Capacity' =>
            $company->capacities->count() > 0,

        'Export Markets' =>
            $company->markets->count() > 0,

        'Certifications' =>
            $company->certifications->count() > 0,
    ];

            case 'fabric_manufacturer':

            case 'yarn_manufacturer':

            case 'garment_manufacturer':

                return [

                    'Add Products' =>
                        $company->products->count() > 0,

                    'Add Machinery Information' =>
                        $company->machines->count() > 0,

                    'Add Production Capacity' =>
                        $company->capacities->count() > 0,

                    'Add Export Markets' =>
                        $company->markets->count() > 0,

                    'Add Certifications' =>
                        $company->certifications->count() > 0,
                ];

            default:

                return [];
        }
    }
}