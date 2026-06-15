<?php

namespace App\Http\Controllers;

use App\Models\IndustryPartner;
use Inertia\Inertia;

class IndustrySolutionController extends Controller
{
    public function index()
    {
        $partners = IndustryPartner::query()
            ->where('is_active', true)
            ->orderBy('partner_category')
            ->orderByDesc('is_featured')
            ->get();

        return Inertia::render(
            'IndustrySolutions/Index',
            [
                'partners' => $partners,
            ]
        );
    }

   public function show(string $category)
{
    $categories = [

        'testing-certification' => [
            'db' => 'testing_certification',
            'title' => 'Testing & Certification',
            'icon' => 'fa-shield-halved',
            'description' => 'Quality assurance, laboratory testing, certification, and compliance solutions.',
            'cta_title' =>   'Become A Testing & Certification Partner',
             ],

        'industrial-machinery' => [
            'db' => 'machinery',
            'title' => 'Industrial Machinery',
            'icon' => 'fa-gears',
            'description' =>
                'Knitting, weaving, dyeing, finishing, and textile manufacturing technologies.',
        'cta_title' =>
        'Become An Industrial Machinery Partner',
        ],

        'technology-solutions' => [
            'db' => 'technology',
            'title' => 'Technology Solutions',
            'icon' => 'fa-microchip',
            'description' =>
                'ERP, PLM, AI, Industry 4.0, and digital transformation solutions.',
        'cta_title' =>
        'Become A Technology Partner',
                ],

        'raw-materials' => [
            'db' => 'raw_material',
            'title' => 'Raw Materials',
            'icon' => 'fa-boxes-stacked',
            'description' =>
                'Fiber, yarn, fabrics, chemicals, and textile materials.',
        ],

        'logistics-supply-chain' => [
            'db' => 'logistics',
            'title' => 'Logistics & Supply Chain',
            'icon' => 'fa-truck',
            'description' =>
                'Domestic and international logistics, warehousing, and trade support.',
        'cta_title' => 'Become A Logistics & Supply Chain Partner',
                ],

        'trade-finance' => [
            'db' => 'finance',
            'title' => 'Trade Finance',
            'icon' => 'fa-building-columns',
            'description' =>
                'Financing solutions supporting industrial growth and export activities.',
       'cta_title' => 'Become A Trade Finance Partner',
                ],

        'exhibitions-events' => [
            'db' => 'association',
            'title' => 'Exhibitions & Events',
            'icon' => 'fa-calendar-days',
            'description' =>
            'Trade fairs, business matching, networking, and industry events.',
            'cta_title' => 'Become An Exhibitions & Events Partner',
        ],

        'research-education' => [
            'db' => 'institution',
            'title' => 'Research & Education',
            'icon' => 'fa-graduation-cap',
            'description' =>
            'Universities, research institutions, training centers, and workforce development.',
       'cta_title' => 'Become A Research & Education Partner',
            ],

    ];

    abort_unless(
        isset($categories[$category]),
        404
    );

    $config = $categories[$category];

    $partners = IndustryPartner::query()
        ->where(
            'partner_category',
            $config['db']
        )
        ->where('is_active', true)
        ->get();

    return Inertia::render(
        'IndustrySolutions/Show',
        [
            'category' => $config,
            'partners' => $partners,
        ]
    );
}
}