<?php

declare(strict_types=1);

namespace App\Services\Knowledge;

use App\Models\Company;
use App\Services\MasterData\BusinessRoleService;
use App\Services\MasterData\CountryService;
use App\Services\MasterData\ProductApplicationService;
use App\Services\MasterData\ProductCategoryService;
use App\Services\MasterData\ProductStatusService;
use App\Services\MasterData\RegionService;

class KnowledgeGraphService
{
    public function __construct(

        protected CountryService $countries,

        protected RegionService $regions,

        protected BusinessRoleService $businessRoles,

        protected ProductCategoryService $productCategories,

        protected ProductApplicationService $productApplications,

        protected ProductStatusService $productStatuses,

    ) {
    }

    /**
     * ==============================================================
     * Build Company Knowledge Graph
     * ==============================================================
     */
    public function company(Company $company): array
    {
        $company->loadMissing([

            'products',

            'markets',

            'certifications',

            'machines',

            'capacities',

            'locations',

            'contacts',

            'leadTimes',

        ]);

        return [

            'company' => $this->companyNode($company),

            'country' => $this->countryNode($company),

            'region' => $this->regionNode($company),

            'business_role' => $this->businessRoleNode($company),

            'products' => $this->productNodes($company),

            'markets' => $this->marketNodes($company),

            'technologies' => $this->technologyNodes($company),

            'machineries' => $this->machineryNodes($company),

            'certifications' => $this->certificationNodes($company),

            'materials' => $this->materialNodes($company),

            'sustainability' => $this->sustainabilityNodes($company),

        ];
    }

    /**
     * ==============================================================
     * Company Node
     * ==============================================================
     */
    protected function companyNode(Company $company): array
    {
        return [

            'id' => $company->id,

            'name' => $company->nama_perusahaan,

            'slug' => $company->slug,

            'category' => $company->category,

            'membership' => $company->membership_type,

        ];
    }

    /**
     * ==============================================================
     * Country Node
     * ==============================================================
     */
    protected function countryNode(Company $company): ?array
    {
        if (!$company->country_code) {
            return null;
        }

        $country = $this->countries->find($company->country_code);

        if (!$country) {
            return null;
        }

        return [

            'code' => $country->country_code,

            'name' => $country->country_name_en,

            'region' => $country->region_code,

            'flag' => $country->flag_emoji,

        ];
    }

    /**
     * ==============================================================
     * Region Node
     * ==============================================================
     */
    protected function regionNode(Company $company): ?array
    {
        if (!$company->country_code) {
            return null;
        }

        $country = $this->countries->find($company->country_code);

        if (!$country) {
            return null;
        }

        return $this->regions->find($country->region_code);
    }
        /**
     * ==============================================================
     * Business Role Node
     * ==============================================================
     */
    protected function businessRoleNode(Company $company): ?array
    {
        if (empty($company->business_role)) {
            return null;
        }

        $role = $this->businessRoles->find($company->business_role);

        if (!$role) {
            return [

                'id' => $company->business_role,

                'label' => $company->business_role,

            ];
        }

        return [

            'id' => $role['id'] ?? null,

            'label' => $role['label'] ?? null,

            'ecosystem' => $role['ecosystem'] ?? null,

            'segment' => $role['segment'] ?? null,

            'priority' => $role['priority'] ?? null,

        ];
    }

    /**
     * ==============================================================
     * Product Nodes
     * ==============================================================
     */
    protected function productNodes(Company $company): array
    {
        return $company->products
            ->map(function ($product) {

                $category = null;

                if (!empty($product->category)) {

                    $category = $this->productCategories
                        ->find($product->category);

                }

                return [

                    'id' => $product->id,

                    'name' => $product->product_name,

                    'name_en' => $product->product_name_en,

                    'hs_code' => $product->hs_code,

                    'category' => $category,

                    'application' => $this->productApplications
                        ->find($product->application),

                    'status' => $this->productStatuses
                        ->find($product->status),

                    'is_primary' => (bool) $product->is_primary,

                ];

            })
            ->values()
            ->toArray();
    }

    /**
     * ==============================================================
     * Export Market Nodes
     * ==============================================================
     */
    protected function marketNodes(Company $company): array
    {
        return $company->markets
            ->map(function ($market) {

                $country = null;

                if (!empty($market->country_code)) {

                    $country = $this->countries
                        ->find($market->country_code);

                }

                return [

                    'id' => $market->id,

                    'country_code' => $market->country_code,

                    'country_name' => $country
                        ? $country->country_name_en
                        : null,

                    'region' => $country
                        ? $country->region_code
                        : null,

                    'flag' => $country
                        ? $country->flag_emoji
                        : null,

                    'export_value' => $market->export_value,

                    'market_share' => $market->market_share,

                    'is_primary' => (bool) $market->is_primary,

                ];

            })
            ->values()
            ->toArray();
    }
        /**
     * ==============================================================
     * Technology Nodes
     * ==============================================================
     */
    protected function technologyNodes(Company $company): array
    {
        if (!$company->relationLoaded('technologies')) {
            return [];
        }

        return $company->technologies
            ->map(function ($technology) {

                return [

                    'id' => $technology->id,

                    'code' => $technology->technology_code,

                    'name' => $technology->technology_name,

                    'category' => $technology->category,

                    'is_primary' => (bool) ($technology->is_primary ?? false),

                ];

            })
            ->values()
            ->toArray();
    }

    /**
     * ==============================================================
     * Machinery Nodes
     * ==============================================================
     */
    protected function machineryNodes(Company $company): array
    {
        return $company->machines
            ->map(function ($machine) {

                return [

                    'id' => $machine->id,

                    'category' => $machine->machine_category,

                    'brand' => $machine->machine_brand,

                    'model' => $machine->machine_model,

                    'quantity' => $machine->quantity,

                    'country_origin' => $machine->country_origin,

                    'year_installed' => $machine->year_installed,

                ];

            })
            ->values()
            ->toArray();
    }

    /**
     * ==============================================================
     * Certification Nodes
     * ==============================================================
     */
    protected function certificationNodes(Company $company): array
    {
        return $company->certifications
            ->map(function ($certification) {

                return [

                    'id' => $certification->id,

                    'name' => $certification->certificate_name,

                    'number' => $certification->certificate_number,

                    'issuer' => $certification->issuer,

                    'issued_at' => $certification->issued_at,

                    'expired_at' => $certification->expired_at,

                    'status' => $certification->status,

                ];

            })
            ->values()
            ->toArray();
    }

    /**
     * ==============================================================
     * Material Nodes
     * ==============================================================
     */
    protected function materialNodes(Company $company): array
    {
        if (!property_exists($company, 'materials')) {
            return [];
        }

        return collect($company->materials)
            ->map(function ($material) {

                return [

                    'id' => $material->id ?? null,

                    'name' => $material->material_name ?? null,

                    'category' => $material->category ?? null,

                    'percentage' => $material->percentage ?? null,

                ];

            })
            ->values()
            ->toArray();
    }

    /**
     * ==============================================================
     * Sustainability Nodes
     * ==============================================================
     */
    protected function sustainabilityNodes(Company $company): array
    {
        if (!property_exists($company, 'sustainabilityTags')) {
            return [];
        }

        return collect($company->sustainabilityTags)
            ->map(function ($tag) {

                return [

                    'id' => $tag->id ?? null,

                    'tag' => $tag->tag ?? null,

                    'category' => $tag->category ?? null,

                ];

            })
            ->values()
            ->toArray();
    }
        /**
     * ==============================================================
     * Build Graph
     * ==============================================================
     */
    public function graph(Company $company): array
    {
        $graph = $this->company($company);

        return [

            'nodes' => $this->buildNodes($graph),

            'edges' => $this->buildEdges($graph),

            'summary' => $this->buildSummary($graph),

        ];
    }
    /**
     * ==============================================================
     * Build Knowledge Relationships
     * ==============================================================
     *
     * Creates relationships BETWEEN nodes,
     * not only Company -> Node.
     *
     */
    protected function buildKnowledgeEdges(array $graph): array
    {
        $edges = [];

        /*
        |--------------------------------------------------------------------------
        | Product → Certification
        |--------------------------------------------------------------------------
        */

        foreach ($graph['products'] as $product) {

            foreach ($graph['certifications'] as $certification) {

                $edges[] = [

                    'from' => $product['id'],

                    'to'   => $certification['id'],

                    'relationship' => 'certified_by',

                ];

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Product → Technology
        |--------------------------------------------------------------------------
        */

        foreach ($graph['products'] as $product) {

            foreach ($graph['technologies'] as $technology) {

                $edges[] = [

                    'from' => $product['id'],

                    'to' => $technology['id'],

                    'relationship' => 'manufactured_using',

                ];

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Technology → Machinery
        |--------------------------------------------------------------------------
        */

        foreach ($graph['technologies'] as $technology) {

            foreach ($graph['machineries'] as $machine) {

                $edges[] = [

                    'from' => $technology['id'],

                    'to' => $machine['id'],

                    'relationship' => 'implemented_by',

                ];

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Certification → Sustainability
        |--------------------------------------------------------------------------
        */

        foreach ($graph['certifications'] as $certification) {

            foreach ($graph['sustainability'] as $tag) {

                $edges[] = [

                    'from' => $certification['id'],

                    'to' => $tag['id'],

                    'relationship' => 'supports',

                ];

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Product → Market
        |--------------------------------------------------------------------------
        */

        foreach ($graph['products'] as $product) {

            foreach ($graph['markets'] as $market) {

                $edges[] = [

                    'from' => $product['id'],

                    'to' => $market['id'],

                    'relationship' => 'exported_to',

                ];

            }

        }

        return $edges;
    }

    /**
     * ==============================================================
     * Build Knowledge Nodes
     * ==============================================================
     */
    protected function buildNodes(array $graph): array
    {
        $nodes = [];

        foreach ($graph as $type => $data) {

            if (empty($data)) {
                continue;
            }

            if (array_is_list($data)) {

                foreach ($data as $item) {

                    $nodes[] = [

                        'type' => $type,

                        'id' => $item['id'] ?? uniqid(),

                        'label' =>
                            $item['name']
                            ?? $item['label']
                            ?? $item['code']
                            ?? 'Unknown',

                        'attributes' => $item,

                    ];

                }

            } else {

                $nodes[] = [

                    'type' => $type,

                    'id' => $data['id'] ?? uniqid(),

                    'label' =>
                        $data['name']
                        ?? $data['label']
                        ?? 'Unknown',

                    'attributes' => $data,

                ];

            }

        }

        return $nodes;
    }
        /**
     * ==============================================================
     * Build Knowledge Relationships
     * ==============================================================
     *
     * Creates relationships BETWEEN nodes,
     * not only Company -> Node.
     *
     */
    
        /**
     * ==============================================================
     * Evaluate Company
     * ==============================================================
     *
     * Compare actual graph against expected rules.
     *
     */
    protected function evaluate(array $graph): array
    {
        return [

            'technology'
                => $this->evaluateTechnologies($graph),

            'certification'
                => $this->evaluateCertifications($graph),

            'machinery'
                => $this->evaluateMachineries($graph),

            'sustainability'
                => $this->evaluateSustainability($graph),

            'market'
                => $this->evaluateMarkets($graph),

        ];
    }
}