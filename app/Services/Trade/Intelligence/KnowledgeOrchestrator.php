<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Knowledge;

use App\Services\Trade\Intelligence\Knowledge\Industry\IndustryKnowledgeService;
use App\Services\Trade\Intelligence\Knowledge\Market\MarketKnowledgeService;
use App\Services\Trade\Intelligence\Knowledge\Seasonality\SeasonalityKnowledgeService;
use App\Services\Trade\Intelligence\Knowledge\Buyer\BuyerKnowledgeService;
use App\Services\Trade\Intelligence\Knowledge\Brand\BrandKnowledgeService;
use App\Services\Trade\Intelligence\Knowledge\Commercial\CommercialKnowledgeService;
use App\Services\Trade\Intelligence\Knowledge\SupplyChain\SupplyChainKnowledgeService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Knowledge Orchestrator
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Central orchestrator for all reusable business knowledge used by
 * the DIGESTEX Intelligence Platform.
 *
 * Responsibilities
 * --------------------------------------------------------------------------
 * • Aggregate all Knowledge Services
 * • Provide a unified knowledge dataset
 * • Eliminate duplicate service dependencies
 * • Supply reusable business knowledge
 *
 * This service NEVER performs:
 *
 * • Database Query
 * • Analytics
 * • Forecast
 * • AI Generation
 *
 * Used by:
 *
 * - TradeRadarService
 * - EarlyWarningService
 * - OpportunityService
 * - RiskAnalysisService
 * - RecommendationService
 * - ExecutiveSummaryService
 * - AIExecutiveSummaryService
 * - Development Intelligence
 */
class KnowledgeOrchestrator
{
    public function __construct(

        protected IndustryKnowledgeService $industry,

        protected MarketKnowledgeService $market,

        protected SeasonalityKnowledgeService $seasonality,

        protected BuyerKnowledgeService $buyer,

        protected BrandKnowledgeService $brand,

        protected CommercialKnowledgeService $commercial,

        protected SupplyChainKnowledgeService $supplyChain,

    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Complete Knowledge Dataset
     * --------------------------------------------------------------------------
     */
    public function all(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Industry
            |--------------------------------------------------------------------------
            */

            'industry' => $this->industry->all(),

            /*
            |--------------------------------------------------------------------------
            | Market
            |--------------------------------------------------------------------------
            */

            'market' => $this->market->all(),

            /*
            |--------------------------------------------------------------------------
            | Seasonality
            |--------------------------------------------------------------------------
            */

            'seasonality' => $this->seasonality->all(),

            /*
            |--------------------------------------------------------------------------
            | Buyer
            |--------------------------------------------------------------------------
            */

            'buyer' => $this->buyer->all(),

            /*
            |--------------------------------------------------------------------------
            | Brand
            |--------------------------------------------------------------------------
            */

            'brand' => $this->brand->all(),

            /*
            |--------------------------------------------------------------------------
            | Commercial
            |--------------------------------------------------------------------------
            */

            'commercial' => $this->commercial->all(),

            /*
            |--------------------------------------------------------------------------
            | Supply Chain
            |--------------------------------------------------------------------------
            */

            'supplyChain' => $this->supplyChain->all(),

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'generated_at' => now()->toDateTimeString(),

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Knowledge Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(): array
    {
        return [

            'industry' => count($this->industry->all()),

            'market' => count($this->market->all()),

            'seasonality' => count($this->seasonality->all()),

            'buyer' => count($this->buyer->all()),

            'brand' => count($this->brand->all()),

            'commercial' => count($this->commercial->all()),

            'supplyChain' => count($this->supplyChain->all()),

            'total_domains' => 7,

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}