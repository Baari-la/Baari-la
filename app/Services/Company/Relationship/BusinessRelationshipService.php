<?php

declare(strict_types=1);

namespace App\Services\Company\Relationship;

use App\Services\Company\Classification\BusinessRoleSemanticService;

/**
 * ==========================================================================
 * DIGESTEX BUSINESS RELATIONSHIP SERVICE
 * ==========================================================================
 *
 * Version:
 * 4.2.1
 *
 * Translates canonical business-role relationships from the DIGESTEX
 * Master Data Framework (DMF) into business relationship intelligence.
 *
 * Architecture:
 *
 * BusinessRoleService
 *      ↓
 * Canonical Business Role
 *      ↓
 * BusinessRoleSemanticService V4.1
 *      ↓
 * BusinessRelationshipService V4.2.1
 *      ↓
 * Candidate Eligibility
 *      ↓
 * Recommendation Intelligence
 *
 * V4.2.1 additions:
 *
 * - Direct relationship lookup
 * - Reverse relationship lookup
 * - Source / target perspective
 * - Discovery mode
 * - Resolution provenance
 * - Backward-compatible relationship fields
 *
 * IMPORTANT:
 *
 * source_relationship always describes SOURCE relative to TARGET.
 *
 * target_relationship always describes TARGET relative to SOURCE.
 *
 * ==========================================================================
 */
class BusinessRelationshipService
{
    /*
    |--------------------------------------------------------------------------
    | Material Flow Vocabulary
    |--------------------------------------------------------------------------
    */

    public const SUPPLIER = 'supplier';

    public const BUYER = 'buyer';

    public const POTENTIAL_BUYER = 'potential_buyer';

    public const DOWNSTREAM_PROCESSOR = 'downstream_processor';

    public const UPSTREAM_SUPPLIER = 'upstream_supplier';

    /*
    |--------------------------------------------------------------------------
    | Solution Vocabulary
    |--------------------------------------------------------------------------
    */

    public const SOLUTION_PARTNER = 'solution_partner';

    public const SOLUTION_CONSUMER = 'solution_consumer';

    public const NEEDS_SOLUTION_PARTNER = 'needs_solution_partner';

    /*
    |--------------------------------------------------------------------------
    | Technology Vocabulary
    |--------------------------------------------------------------------------
    */

    public const TECHNOLOGY_PARTNER = 'technology_partner';

    public const TECHNOLOGY_CONSUMER = 'technology_consumer';

    public const NEEDS_TECHNOLOGY_PARTNER = 'needs_technology_partner';

    /*
    |--------------------------------------------------------------------------
    | Service Vocabulary
    |--------------------------------------------------------------------------
    */

    public const SERVICE_PARTNER = 'service_partner';

    public const SERVICE_CONSUMER = 'service_consumer';

    public const NEEDS_SERVICE_PARTNER = 'needs_service_partner';

    /*
    |--------------------------------------------------------------------------
    | Sustainability Vocabulary
    |--------------------------------------------------------------------------
    */

    public const SUSTAINABILITY_PARTNER = 'sustainability_partner';

    public const SUSTAINABILITY_CONSUMER = 'sustainability_consumer';

    public const NEEDS_SUSTAINABILITY_PARTNER =
        'needs_sustainability_partner';

    /*
    |--------------------------------------------------------------------------
    | Market Vocabulary
    |--------------------------------------------------------------------------
    */

    public const MARKET_CHANNEL = 'market_channel';

    public const MARKET_ACCESS_PARTNER = 'market_access_partner';

    public const MARKET_PARTICIPANT = 'market_participant';

    /*
    |--------------------------------------------------------------------------
    | Unknown
    |--------------------------------------------------------------------------
    */

    public const UNKNOWN = 'unknown';

    /*
    |--------------------------------------------------------------------------
    | Discovery Modes
    |--------------------------------------------------------------------------
    */

    public const DISCOVERY_SUPPLIER =
        'supplier_discovery';

    public const DISCOVERY_BUYER =
        'buyer_discovery';

    public const DISCOVERY_SOLUTION =
        'solution_partner_discovery';

    public const DISCOVERY_TECHNOLOGY =
        'technology_partner_discovery';

    public const DISCOVERY_SERVICE =
        'service_partner_discovery';

    public const DISCOVERY_SUSTAINABILITY =
        'sustainability_partner_discovery';

    public const DISCOVERY_MARKET =
        'market_access_discovery';

    public const DISCOVERY_RELATED =
        'related_business_discovery';

    /*
    |--------------------------------------------------------------------------
    | Resolution Provenance
    |--------------------------------------------------------------------------
    */

    public const RESOLVED_DIRECT = 'direct';

    public const RESOLVED_REVERSE = 'reverse';

    public function __construct(
        protected BusinessRoleSemanticService $semantic,
    ) {
    }

    /**
     * ==========================================================================
     * RESOLVE
     * ==========================================================================
     *
     * Resolve a business relationship from SOURCE perspective.
     *
     * Strategy:
     *
     * 1. Try source -> target directly.
     * 2. If absent, try target -> source.
     * 3. If reverse exists, invert the perspective.
     * 4. If neither exists, return null.
     */
    public function resolve(
        string $sourceRole,
        string $targetRole
    ): ?array {

        /*
        |--------------------------------------------------------------------------
        | Direct Lookup
        |--------------------------------------------------------------------------
        */

        $direct = $this->semantic->relationshipBetween(
            $sourceRole,
            $targetRole
        );

        if ($direct !== null) {
            return $this->buildDirectRelationship(
                $direct
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Reverse Lookup
        |--------------------------------------------------------------------------
        */

        $reverse = $this->semantic->relationshipBetween(
            $targetRole,
            $sourceRole
        );

        if ($reverse !== null) {
            return $this->buildReverseRelationship(
                requestedSourceRole: $sourceRole,
                requestedTargetRole: $targetRole,
                reverseRelationship: $reverse,
            );
        }

        return null;
    }

    /**
     * ==========================================================================
     * BUILD DIRECT RELATIONSHIP
     * ==========================================================================
     */
    protected function buildDirectRelationship(
        array $relationship
    ): array {

        $sourceRole =
            (string) $relationship['source_role'];

        $targetRole =
            (string) $relationship['target_role'];

        $semanticType =
            (string) (
                $relationship['relationship_type']
                ?? BusinessRoleSemanticService::RELATIONSHIP_UNKNOWN
            );

        $direction =
            (string) (
                $relationship['direction']
                ?? ''
            );

        [$sourceRelationship, $targetRelationship] =
            $this->perspectives(
                semanticType: $semanticType,
                direction: $direction,
            );

        return $this->buildResult(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            semanticType: $semanticType,
            sourceRelationship: $sourceRelationship,
            targetRelationship: $targetRelationship,
            direction: $direction,
            resolvedVia: self::RESOLVED_DIRECT,
        );
    }

    /**
     * ==========================================================================
     * BUILD REVERSE RELATIONSHIP
     * ==========================================================================
     *
     * Example:
     *
     * Taxonomy contains:
     *
     * plm_provider -> garment_manufacturer
     *
     * Requested:
     *
     * garment_manufacturer -> plm_provider
     *
     * We resolve the known edge and invert its business perspective.
     */
    protected function buildReverseRelationship(
        string $requestedSourceRole,
        string $requestedTargetRole,
        array $reverseRelationship
    ): array {

        $semanticType =
            (string) (
                $reverseRelationship['relationship_type']
                ?? BusinessRoleSemanticService::RELATIONSHIP_UNKNOWN
            );

        $reverseDirection =
            (string) (
                $reverseRelationship['direction']
                ?? ''
            );

       /*
|--------------------------------------------------------------------------
| Requested Direction
|--------------------------------------------------------------------------
|
| The taxonomy relationship was found in reverse:
|
| TARGET -> SOURCE
|
| We are answering:
|
| SOURCE -> TARGET
|
| Therefore the graph direction must first be inverted.
|
*/

$requestedDirection =
    $this->invertDirection(
        $reverseDirection
    );

/*
|--------------------------------------------------------------------------
| Requested Business Perspective
|--------------------------------------------------------------------------
|
| IMPORTANT:
|
| Business perspective must be derived from the REQUESTED direction,
| not hard-coded as consumer/provider.
|
| This keeps:
|
| downstream:
|     source = supplier
|     target = buyer
|
| upstream:
|     source = buyer
|     target = supplier
|
| consistent with direct relationship resolution.
|
*/

[$sourceRelationship, $targetRelationship] =
    $this->perspectives(
        semanticType: $semanticType,
        direction: $requestedDirection,
    );

/*
|--------------------------------------------------------------------------
| Build Result
|--------------------------------------------------------------------------
*/

return $this->buildResult(
    sourceRole: $requestedSourceRole,
    targetRole: $requestedTargetRole,
    semanticType: $semanticType,
    sourceRelationship: $sourceRelationship,
    targetRelationship: $targetRelationship,
    direction: $requestedDirection,
    resolvedVia: self::RESOLVED_REVERSE,
);
    }

    /**
     * ==========================================================================
     * PERSPECTIVES
     * ==========================================================================
     *
     * Return:
     *
     * [
     *     source_relationship,
     *     target_relationship,
     * ]
     */
    protected function perspectives(
        string $semanticType,
        string $direction
    ): array {

        return match ($semanticType) {

            /*
            |--------------------------------------------------------------------------
            | Material Flow
            |--------------------------------------------------------------------------
            */

            BusinessRoleSemanticService::RELATIONSHIP_MATERIAL_FLOW =>
                match ($direction) {

                    'downstream' => [
                        self::SUPPLIER,
                        self::BUYER,
                    ],

                    'upstream' => [
                        self::BUYER,
                        self::SUPPLIER,
                    ],

                    default => [
                        self::UNKNOWN,
                        self::UNKNOWN,
                    ],
                },

            /*
            |--------------------------------------------------------------------------
            | Solution Supply
            |--------------------------------------------------------------------------
            */

            BusinessRoleSemanticService::RELATIONSHIP_SOLUTION_SUPPLY =>
                match ($direction) {

                    'downstream' => [
                        self::SOLUTION_PARTNER,
                        self::SOLUTION_CONSUMER,
                    ],

                    'upstream' => [
                        self::SOLUTION_CONSUMER,
                        self::SOLUTION_PARTNER,
                    ],

                    default => [
                        self::UNKNOWN,
                        self::UNKNOWN,
                    ],
                },

            /*
            |--------------------------------------------------------------------------
            | Technology
            |--------------------------------------------------------------------------
            */

            BusinessRoleSemanticService::RELATIONSHIP_TECHNOLOGY_SOLUTION =>
                match ($direction) {

                    'downstream' => [
                        self::TECHNOLOGY_PARTNER,
                        self::TECHNOLOGY_CONSUMER,
                    ],

                    'upstream' => [
                        self::TECHNOLOGY_CONSUMER,
                        self::TECHNOLOGY_PARTNER,
                    ],

                    default => [
                        self::UNKNOWN,
                        self::UNKNOWN,
                    ],
                },

            /*
            |--------------------------------------------------------------------------
            | Business Service
            |--------------------------------------------------------------------------
            */

            BusinessRoleSemanticService::RELATIONSHIP_BUSINESS_SERVICE =>
                match ($direction) {

                    'downstream' => [
                        self::SERVICE_PARTNER,
                        self::SERVICE_CONSUMER,
                    ],

                    'upstream' => [
                        self::SERVICE_CONSUMER,
                        self::SERVICE_PARTNER,
                    ],

                    default => [
                        self::UNKNOWN,
                        self::UNKNOWN,
                    ],
                },

            /*
            |--------------------------------------------------------------------------
            | Sustainability
            |--------------------------------------------------------------------------
            */

            BusinessRoleSemanticService::RELATIONSHIP_SUSTAINABILITY_SOLUTION =>
                match ($direction) {

                    'downstream' => [
                        self::SUSTAINABILITY_PARTNER,
                        self::SUSTAINABILITY_CONSUMER,
                    ],

                    'upstream' => [
                        self::SUSTAINABILITY_CONSUMER,
                        self::SUSTAINABILITY_PARTNER,
                    ],

                    default => [
                        self::UNKNOWN,
                        self::UNKNOWN,
                    ],
                },

            /*
            |--------------------------------------------------------------------------
            | Market Channel
            |--------------------------------------------------------------------------
            */

            BusinessRoleSemanticService::RELATIONSHIP_MARKET_CHANNEL =>
                match ($direction) {

                    'downstream' => [
                        self::MARKET_CHANNEL,
                        self::MARKET_PARTICIPANT,
                    ],

                    'upstream' => [
                        self::MARKET_PARTICIPANT,
                        self::MARKET_CHANNEL,
                    ],

                    default => [
                        self::UNKNOWN,
                        self::UNKNOWN,
                    ],
                },

            default => [
                self::UNKNOWN,
                self::UNKNOWN,
            ],
        };
    }

    /**
     * ==========================================================================
     * PROVIDER PERSPECTIVE
     * ==========================================================================
     */
    protected function providerPerspective(
        string $semanticType,
        string $fallback = self::UNKNOWN
    ): string {

        return match ($semanticType) {

            BusinessRoleSemanticService::RELATIONSHIP_MATERIAL_FLOW =>
                self::SUPPLIER,

            BusinessRoleSemanticService::RELATIONSHIP_SOLUTION_SUPPLY =>
                self::SOLUTION_PARTNER,

            BusinessRoleSemanticService::RELATIONSHIP_TECHNOLOGY_SOLUTION =>
                self::TECHNOLOGY_PARTNER,

            BusinessRoleSemanticService::RELATIONSHIP_BUSINESS_SERVICE =>
                self::SERVICE_PARTNER,

            BusinessRoleSemanticService::RELATIONSHIP_SUSTAINABILITY_SOLUTION =>
                self::SUSTAINABILITY_PARTNER,

            BusinessRoleSemanticService::RELATIONSHIP_MARKET_CHANNEL =>
                self::MARKET_CHANNEL,

            default =>
                $fallback,
        };
    }

    /**
     * ==========================================================================
     * CONSUMER PERSPECTIVE
     * ==========================================================================
     */
    protected function consumerPerspective(
        string $semanticType,
        string $fallback = self::UNKNOWN
    ): string {

        return match ($semanticType) {

            BusinessRoleSemanticService::RELATIONSHIP_MATERIAL_FLOW =>
                self::BUYER,

            BusinessRoleSemanticService::RELATIONSHIP_SOLUTION_SUPPLY =>
                self::SOLUTION_CONSUMER,

            BusinessRoleSemanticService::RELATIONSHIP_TECHNOLOGY_SOLUTION =>
                self::TECHNOLOGY_CONSUMER,

            BusinessRoleSemanticService::RELATIONSHIP_BUSINESS_SERVICE =>
                self::SERVICE_CONSUMER,

            BusinessRoleSemanticService::RELATIONSHIP_SUSTAINABILITY_SOLUTION =>
                self::SUSTAINABILITY_CONSUMER,

            BusinessRoleSemanticService::RELATIONSHIP_MARKET_CHANNEL =>
                self::MARKET_PARTICIPANT,

            default =>
                $fallback,
        };
    }

    /**
     * ==========================================================================
     * BUILD RESULT
     * ==========================================================================
     */
    protected function buildResult(
        string $sourceRole,
        string $targetRole,
        string $semanticType,
        string $sourceRelationship,
        string $targetRelationship,
        string $direction,
        string $resolvedVia
    ): array {

        return [
            'source_role' =>
                $sourceRole,

            'target_role' =>
                $targetRole,

            'semantic_type' =>
                $semanticType,

            /*
            |--------------------------------------------------------------------------
            | V4.2.1 Explicit Perspective
            |--------------------------------------------------------------------------
            */

            'source_relationship' =>
                $sourceRelationship,

            'target_relationship' =>
                $targetRelationship,

            /*
            |--------------------------------------------------------------------------
            | Backward Compatibility
            |--------------------------------------------------------------------------
            |
            | Existing V4.2 consumers may still read:
            |
            | business_relationship
            | inverse_relationship
            |
            | These now mirror the explicit source/target perspective.
            |
            */

            'business_relationship' =>
                $sourceRelationship,

            'inverse_relationship' =>
                $targetRelationship,

            /*
            |--------------------------------------------------------------------------
            | Graph Intelligence
            |--------------------------------------------------------------------------
            */

            'direction' =>
                $direction,

            'discovery_mode' =>
                $this->discoveryMode(
                    semanticType: $semanticType,
                    sourceRelationship: $sourceRelationship,
                    targetRelationship: $targetRelationship,
                ),

            'resolved_via' =>
                $resolvedVia,

            'confidence' =>
                $this->relationshipConfidence(
                    semanticType: $semanticType,
                    sourceRelationship: $sourceRelationship,
                    targetRelationship: $targetRelationship,
                ),
        ];
    }

    /**
     * ==========================================================================
     * DISCOVERY MODE
     * ==========================================================================
     *
     * Discovery mode describes what TARGET represents from the perspective
     * of SOURCE.
     *
     * Example:
     *
     * yarn_spinner -> fiber_manufacturer
     *
     * target is supplier
     *
     * discovery_mode = supplier_discovery
     *
     * garment_manufacturer -> plm_provider
     *
     * target is technology partner
     *
     * discovery_mode = technology_partner_discovery
     */
    protected function discoveryMode(
        string $semanticType,
        string $sourceRelationship,
        string $targetRelationship
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Target is Supplier
        |--------------------------------------------------------------------------
        */

        if ($targetRelationship === self::SUPPLIER) {
            return self::DISCOVERY_SUPPLIER;
        }

        /*
        |--------------------------------------------------------------------------
        | Target is Buyer
        |--------------------------------------------------------------------------
        */

        if ($targetRelationship === self::BUYER) {
            return self::DISCOVERY_BUYER;
        }

        /*
        |--------------------------------------------------------------------------
        | Solution
        |--------------------------------------------------------------------------
        */

        if ($targetRelationship === self::SOLUTION_PARTNER) {
            return self::DISCOVERY_SOLUTION;
        }

        /*
        |--------------------------------------------------------------------------
        | Technology
        |--------------------------------------------------------------------------
        */

        if ($targetRelationship === self::TECHNOLOGY_PARTNER) {
            return self::DISCOVERY_TECHNOLOGY;
        }

        /*
        |--------------------------------------------------------------------------
        | Service
        |--------------------------------------------------------------------------
        */

        if ($targetRelationship === self::SERVICE_PARTNER) {
            return self::DISCOVERY_SERVICE;
        }

        /*
        |--------------------------------------------------------------------------
        | Sustainability
        |--------------------------------------------------------------------------
        */

        if ($targetRelationship === self::SUSTAINABILITY_PARTNER) {
            return self::DISCOVERY_SUSTAINABILITY;
        }

        /*
        |--------------------------------------------------------------------------
        | Market
        |--------------------------------------------------------------------------
        */

        if ($targetRelationship === self::MARKET_CHANNEL) {
            return self::DISCOVERY_MARKET;
        }

        /*
        |--------------------------------------------------------------------------
        | Semantic Fallback
        |--------------------------------------------------------------------------
        */

        return match ($semanticType) {

            BusinessRoleSemanticService::RELATIONSHIP_MATERIAL_FLOW =>
                $sourceRelationship === self::SUPPLIER
                    ? self::DISCOVERY_BUYER
                    : self::DISCOVERY_SUPPLIER,

            BusinessRoleSemanticService::RELATIONSHIP_SOLUTION_SUPPLY =>
                self::DISCOVERY_SOLUTION,

            BusinessRoleSemanticService::RELATIONSHIP_TECHNOLOGY_SOLUTION =>
                self::DISCOVERY_TECHNOLOGY,

            BusinessRoleSemanticService::RELATIONSHIP_BUSINESS_SERVICE =>
                self::DISCOVERY_SERVICE,

            BusinessRoleSemanticService::RELATIONSHIP_SUSTAINABILITY_SOLUTION =>
                self::DISCOVERY_SUSTAINABILITY,

            BusinessRoleSemanticService::RELATIONSHIP_MARKET_CHANNEL =>
                self::DISCOVERY_MARKET,

            default =>
                self::DISCOVERY_RELATED,
        };
    }

    /**
     * ==========================================================================
     * INVERT DIRECTION
     * ==========================================================================
     */
    protected function invertDirection(
        string $direction
    ): string {

        return match ($direction) {

            'downstream' =>
                'upstream',

            'upstream' =>
                'downstream',

            default =>
                'reverse',
        };
    }

    /**
     * ==========================================================================
     * RELATIONSHIP CONFIDENCE
     * ==========================================================================
     *
     * This confidence describes confidence in the canonical taxonomy
     * relationship only.
     *
     * It is NOT company classification confidence.
     */
    protected function relationshipConfidence(
        string $semanticType,
        string $sourceRelationship,
        string $targetRelationship
    ): float {

        if (
            $semanticType ===
                BusinessRoleSemanticService::RELATIONSHIP_UNKNOWN ||
            $sourceRelationship === self::UNKNOWN ||
            $targetRelationship === self::UNKNOWN
        ) {
            return 0.0;
        }

        return 1.0;
    }

    /**
     * ==========================================================================
     * HAS RELATIONSHIP
     * ==========================================================================
     */
    public function hasRelationship(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->resolve(
            $sourceRole,
            $targetRole
        ) !== null;
    }

    /**
     * ==========================================================================
     * RELATIONSHIP IS
     * ==========================================================================
     *
     * Checks SOURCE relationship toward TARGET.
     */
    public function relationshipIs(
        string $sourceRole,
        string $targetRole,
        string $relationship
    ): bool {

        $resolved = $this->resolve(
            $sourceRole,
            $targetRole
        );

        if ($resolved === null) {
            return false;
        }

        return
            ($resolved['source_relationship'] ?? null)
            === $relationship;
    }

    /**
     * ==========================================================================
     * TARGET RELATIONSHIP IS
     * ==========================================================================
     *
     * Checks TARGET relationship toward SOURCE.
     */
    public function targetRelationshipIs(
        string $sourceRole,
        string $targetRole,
        string $relationship
    ): bool {

        $resolved = $this->resolve(
            $sourceRole,
            $targetRole
        );

        if ($resolved === null) {
            return false;
        }

        return
            ($resolved['target_relationship'] ?? null)
            === $relationship;
    }

    /**
     * ==========================================================================
     * IS SUPPLIER
     * ==========================================================================
     *
     * Is SOURCE a supplier to TARGET?
     */
    public function isSupplier(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->relationshipIs(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            relationship: self::SUPPLIER,
        );
    }

    /**
     * ==========================================================================
     * IS BUYER
     * ==========================================================================
     *
     * Is SOURCE a buyer of TARGET?
     */
    public function isBuyer(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->relationshipIs(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            relationship: self::BUYER,
        );
    }

    /**
     * ==========================================================================
     * TARGET IS SUPPLIER
     * ==========================================================================
     */
    public function targetIsSupplier(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->targetRelationshipIs(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            relationship: self::SUPPLIER,
        );
    }

    /**
     * ==========================================================================
     * TARGET IS BUYER
     * ==========================================================================
     */
    public function targetIsBuyer(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->targetRelationshipIs(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            relationship: self::BUYER,
        );
    }

    /**
     * ==========================================================================
     * IS POTENTIAL BUYER
     * ==========================================================================
     *
     * Backward-compatible helper.
     *
     * In V4.2.1 BUYER is the explicit source perspective.
     */
    public function isPotentialBuyer(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->isBuyer(
            $sourceRole,
            $targetRole
        );
    }

    /**
     * ==========================================================================
     * IS SOLUTION PARTNER
     * ==========================================================================
     */
    public function isSolutionPartner(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->relationshipIs(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            relationship: self::SOLUTION_PARTNER,
        );
    }

    /**
     * ==========================================================================
     * IS TECHNOLOGY PARTNER
     * ==========================================================================
     */
    public function isTechnologyPartner(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->relationshipIs(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            relationship: self::TECHNOLOGY_PARTNER,
        );
    }

    /**
     * ==========================================================================
     * IS SERVICE PARTNER
     * ==========================================================================
     */
    public function isServicePartner(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->relationshipIs(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            relationship: self::SERVICE_PARTNER,
        );
    }

    /**
     * ==========================================================================
     * IS SUSTAINABILITY PARTNER
     * ==========================================================================
     */
    public function isSustainabilityPartner(
        string $sourceRole,
        string $targetRole
    ): bool {

        return $this->relationshipIs(
            sourceRole: $sourceRole,
            targetRole: $targetRole,
            relationship: self::SUSTAINABILITY_PARTNER,
        );
    }

    /**
     * ==========================================================================
     * DISCOVERY MODE IS
     * ==========================================================================
     */
    public function discoveryModeIs(
        string $sourceRole,
        string $targetRole,
        string $mode
    ): bool {

        $resolved = $this->resolve(
            $sourceRole,
            $targetRole
        );

        if ($resolved === null) {
            return false;
        }

        return
            ($resolved['discovery_mode'] ?? null)
            === $mode;
    }

    /**
     * ==========================================================================
     * DESCRIBE
     * ==========================================================================
     */
    public function describe(
        string $sourceRole,
        string $targetRole
    ): ?array {

        return $this->resolve(
            $sourceRole,
            $targetRole
        );
    }
}