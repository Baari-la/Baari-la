<?php

declare(strict_types=1);

namespace App\Services\Knowledge\Builders;

use App\Models\CompanyProduct;
use App\Services\Knowledge\Contracts\NodeBuilderInterface;
use App\Services\Knowledge\KnowledgeNode;
use App\Services\MasterData\ProductApplicationService;
use App\Services\MasterData\ProductCategoryService;
use App\Services\MasterData\ProductStatusService;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Product Node Builder
 * ==========================================================================
 *
 * Responsible for creating Product Knowledge Nodes.
 *
 * Used by:
 *
 * • GraphBuilder
 * • KnowledgeGraphService
 * • Executive AI
 *
 */

class ProductNodeBuilder implements NodeBuilderInterface
{
    public function __construct(

        protected ProductCategoryService $categories,

        protected ProductApplicationService $applications,

        protected ProductStatusService $statuses,

    ) {
    }

    /**
     * Build Product Node.
     */
    public function build(mixed $source): KnowledgeNode
    {
        /** @var CompanyProduct $product */
        $product = $source;

        $category = null;
        $application = null;
        $status = null;

        if (!empty($product->category)) {
            $category = $this->categories->find(
                $product->category
            );
        }

        if (!empty($product->application)) {
            $application = $this->applications->find(
                $product->application
            );
        }

        if (!empty($product->status)) {
            $status = $this->statuses->find(
                $product->status
            );
        }

        return new KnowledgeNode(

            id: $product->id,

            type: 'product',

            label: $product->product_name,

            attributes: [

                /*
                |--------------------------------------------------------------------------
                | Identity
                |--------------------------------------------------------------------------
                */

                'product_id' => $product->id,

                'product_name' => $product->product_name,

                'product_name_en' => $product->product_name_en,

                'slug' => $product->slug,

                /*
                |--------------------------------------------------------------------------
                | Trade
                |--------------------------------------------------------------------------
                */

                'hs_code' => $product->hs_code,

                /*
                |--------------------------------------------------------------------------
                | Category
                |--------------------------------------------------------------------------
                */

                'category' => $category,

                /*
                |--------------------------------------------------------------------------
                | Application
                |--------------------------------------------------------------------------
                */

                'application' => $application,

                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                'status' => $status,

                /*
                |--------------------------------------------------------------------------
                | Business
                |--------------------------------------------------------------------------
                */

                'is_primary' => (bool) $product->is_primary,

                'minimum_order_quantity'
                    => $product->minimum_order_quantity,

                'lead_time'
                    => $product->lead_time,

                /*
                |--------------------------------------------------------------------------
                | Metadata
                |--------------------------------------------------------------------------
                */

                'source' => 'Company',

                'version' => config('app.version'),

            ]

        );
    }
}