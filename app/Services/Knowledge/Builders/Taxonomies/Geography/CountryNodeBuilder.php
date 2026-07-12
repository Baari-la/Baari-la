<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders;

use App\Models\Country;
use App\Services\Knowledge\Contracts\NodeBuilderInterface;
use App\Services\Knowledge\KnowledgeNode;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Country Node Builder
 * ==========================================================================
 *
 * Responsible for creating Country Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

class CountryNodeBuilder implements NodeBuilderInterface
{
    /**
     * Build Country Knowledge Node.
     *
     * @param mixed $source
     */
    public function build(mixed $source): KnowledgeNode
    {
        /** @var Country $country */
        $country = $source;

        return new KnowledgeNode(

            id: $country->country_code,

            type: 'country',

            label: $country->country_name_en,

            attributes: [

                /*
                |--------------------------------------------------------------------------
                | Identity
                |--------------------------------------------------------------------------
                */

                'country_code' => $country->country_code,

                'iso3' => $country->iso3,

                'official_name' => $country->official_name,

                /*
                |--------------------------------------------------------------------------
                | Localized Name
                |--------------------------------------------------------------------------
                */

                'name_en' => $country->country_name_en,

                'name_id' => $country->country_name_id,

                /*
                |--------------------------------------------------------------------------
                | Geography
                |--------------------------------------------------------------------------
                */

                'region_code' => $country->region_code,

                'region_en' => $country->region_en,

                'region_id' => $country->region_id,

                'sub_region_en' => $country->sub_region_en,

                'sub_region_id' => $country->sub_region_id,

                /*
                |--------------------------------------------------------------------------
                | Display
                |--------------------------------------------------------------------------
                */

                'flag' => $country->flag_emoji,

                'display_name' => trim(

                    $country->flag_emoji .
                    ' ' .
                    $country->country_name_en

                ),

                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                'active' => (bool) $country->is_active,

            ]

        );
    }
}