<?php

declare(strict_types=1);

namespace App\Services\Company\Classification;

use Illuminate\Support\Collection;

class BusinessRoleSemanticService
{
    /**
     * ==========================================================================
     * DIGESTEX BUSINESS ROLE SEMANTIC SERVICE
     * ==========================================================================
     *
     * Semantic layer on top of the Digestex Master Data Framework (DMF)
     * Business Role Knowledge Base.
     *
     * Canonical source:
     *
     * config/masterdata/Business/business_roles.php
     *
     * Responsibilities:
     *
     * - Retrieve canonical role metadata
     * - Resolve role category
     * - Resolve upstream relationships
     * - Resolve downstream relationships
     * - Interpret relationship semantics
     * - Identify potential supplier roles
     * - Identify potential buyer roles
     *
     * IMPORTANT:
     *
     * This service does NOT classify companies.
     *
     * Company classification remains the responsibility of:
     *
     * BusinessRoleService
     * BusinessRoleResolver
     *
     * This service interprets relationships between canonical roles.
     *
     * Future consumers:
     *
     * - BusinessRelationshipService
     * - Smart Business Matching
     * - Build My Supply Chain
     * - Company Intelligence
     * - Knowledge Graph
     * - Supplier Discovery
     * - Buyer Discovery
     *
     * ==========================================================================
     */

    protected Collection $roles;

    protected Collection $rolesById;

    /**
     * Categories representing physical textile value-chain stages.
     */
    protected const MATERIAL_FLOW_CATEGORIES = [
        'raw_material',
        'fiber',
        'yarn',
        'fabric',
        'finished_product',
    ];

    /**
     * Semantic relationship types.
     */
    public const RELATIONSHIP_MATERIAL_FLOW = 'material_flow';

    public const RELATIONSHIP_MARKET_CHANNEL = 'market_channel';

    public const RELATIONSHIP_SOLUTION_SUPPLY = 'solution_supply';

    public const RELATIONSHIP_BUSINESS_SERVICE = 'business_service';

    public const RELATIONSHIP_TECHNOLOGY_SOLUTION = 'technology_solution';

    public const RELATIONSHIP_SUSTAINABILITY_SOLUTION = 'sustainability_solution';

    public const RELATIONSHIP_UNKNOWN = 'unknown';

    public function __construct()
    {
        $roles = config('masterdata.Business.business_roles', []);

        $this->roles = collect(
            is_array($roles) ? $roles : []
        )
            ->filter(
                fn ($role) =>
                    is_array($role) &&
                    filled($role['id'] ?? null)
            )
            ->values();

        $this->rolesById = $this->roles
            ->keyBy(
                fn (array $role) =>
                    (string) $role['id']
            );
    }

    /**
     * ==========================================================================
     * ROLE
     * ==========================================================================
     *
     * Return complete canonical metadata for a role.
     */
    public function role(string $role): ?array
    {
        $role = $this->normalizeRole($role);

        if ($role === '') {
            return null;
        }

        $data = $this->rolesById->get($role);

        return is_array($data)
            ? $data
            : null;
    }

    /**
     * Check whether a role exists in the canonical taxonomy.
     */
    public function exists(string $role): bool
    {
        return $this->role($role) !== null;
    }

    /**
     * ==========================================================================
     * CATEGORY
     * ==========================================================================
     */
    public function category(string $role): ?string
    {
        $data = $this->role($role);

        if ($data === null) {
            return null;
        }

        $category = $data['category'] ?? null;

        return filled($category)
            ? (string) $category
            : null;
    }

    /**
     * Determine whether a role belongs to the physical textile value chain.
     */
    public function isMaterialFlowRole(string $role): bool
    {
        $category = $this->category($role);

        return $category !== null &&
            in_array(
                $category,
                self::MATERIAL_FLOW_CATEGORIES,
                true
            );
    }

    /**
     * ==========================================================================
     * UPSTREAM
     * ==========================================================================
     *
     * Roles located upstream from the requested role according to DMF.
     */
    public function upstream(string $role): array
    {
        return $this->relationshipList(
            $role,
            'upstream'
        );
    }

    /**
     * ==========================================================================
     * DOWNSTREAM
     * ==========================================================================
     *
     * Roles located downstream from the requested role according to DMF.
     */
    public function downstream(string $role): array
    {
        return $this->relationshipList(
            $role,
            'downstream'
        );
    }

    /**
     * ==========================================================================
     * RELATIONSHIP TYPE
     * ==========================================================================
     *
     * Interpret the semantic nature of relationships originating from a role.
     *
     * Examples:
     *
     * yarn_spinner
     *     -> material_flow
     *
     * textile_machinery_supplier
     *     -> solution_supply
     *
     * testing_laboratory
     *     -> business_service
     *
     * plm_provider
     *     -> technology_solution
     *
     * esg_consultant
     *     -> sustainability_solution
     */
    public function relationshipType(string $role): string
    {
        return match ($this->category($role)) {

            'raw_material',
            'fiber',
            'yarn',
            'fabric',
            'finished_product'
                => self::RELATIONSHIP_MATERIAL_FLOW,

            'market'
                => self::RELATIONSHIP_MARKET_CHANNEL,

            'supporting_industry'
                => self::RELATIONSHIP_SOLUTION_SUPPLY,

            'service'
                => self::RELATIONSHIP_BUSINESS_SERVICE,

            'digital'
                => self::RELATIONSHIP_TECHNOLOGY_SOLUTION,

            'sustainability'
                => self::RELATIONSHIP_SUSTAINABILITY_SOLUTION,

            default
                => self::RELATIONSHIP_UNKNOWN,
        };
    }

    /**
     * ==========================================================================
     * SUPPLIERS FOR
     * ==========================================================================
     *
     * Return canonical roles that may supply the requested role.
     *
     * For material-flow roles, the direct upstream list represents the
     * immediate supply-chain candidates.
     *
     * Example:
     *
     * suppliersFor('yarn_spinner')
     *
     * may return:
     *
     * fiber_manufacturer
     * staple_fiber_manufacturer
     * recycled_fiber_producer
     * bio_based_fiber_producer
     */
    public function suppliersFor(string $role): array
    {
        if (!$this->exists($role)) {
            return [];
        }

        return $this->upstream($role);
    }

    /**
     * ==========================================================================
     * BUYERS FOR
     * ==========================================================================
     *
     * Return canonical roles that may consume, process, distribute or receive
     * the output of the requested role.
     *
     * IMPORTANT:
     *
     * "Buyer" is a business interpretation of a downstream relationship.
     * It does not necessarily mean a legal purchasing entity.
     *
     * BusinessRelationshipService will later provide more precise semantics.
     */
    public function buyersFor(string $role): array
    {
        if (!$this->exists($role)) {
            return [];
        }

        return $this->downstream($role);
    }

    /**
     * ==========================================================================
     * RELATED ROLES
     * ==========================================================================
     *
     * Return unique direct upstream + downstream relationships.
     */
    public function relatedRoles(string $role): array
    {
        if (!$this->exists($role)) {
            return [];
        }

        return collect([
            ...$this->upstream($role),
            ...$this->downstream($role),
        ])
            ->unique()
            ->values()
            ->all();
    }

    /**
     * ==========================================================================
     * SUPPORTS
     * ==========================================================================
     *
     * Determine whether role A directly points downstream to role B.
     *
     * Particularly useful for:
     *
     * textile_machinery_supplier -> yarn_spinner
     * plm_provider               -> garment_manufacturer
     * water_treatment_provider   -> dyeing_finishing_mill
     */
    public function supports(
        string $providerRole,
        string $targetRole
    ): bool {

        $providerRole = $this->normalizeRole($providerRole);
        $targetRole = $this->normalizeRole($targetRole);

        if (
            $providerRole === '' ||
            $targetRole === ''
        ) {
            return false;
        }

        return in_array(
            $targetRole,
            $this->downstream($providerRole),
            true
        );
    }

    /**
     * ==========================================================================
     * RELATIONSHIP BETWEEN
     * ==========================================================================
     *
     * Describe the direct relationship between two canonical roles.
     *
     * This is intentionally conservative.
     *
     * V4.2 BusinessRelationshipService can later expand this into:
     *
     * supplier
     * buyer
     * technology_partner
     * service_partner
     * sustainability_partner
     * distribution_channel
     * etc.
     */
    public function relationshipBetween(
        string $sourceRole,
        string $targetRole
    ): ?array {

        $sourceRole = $this->normalizeRole($sourceRole);
        $targetRole = $this->normalizeRole($targetRole);

        if (
            !$this->exists($sourceRole) ||
            !$this->exists($targetRole)
        ) {
            return null;
        }

        if (
            in_array(
                $targetRole,
                $this->downstream($sourceRole),
                true
            )
        ) {
            return [
                'source_role' => $sourceRole,
                'target_role' => $targetRole,
                'direction' => 'downstream',
                'relationship_type' =>
                    $this->relationshipType($sourceRole),
            ];
        }

        if (
            in_array(
                $targetRole,
                $this->upstream($sourceRole),
                true
            )
        ) {
            return [
                'source_role' => $sourceRole,
                'target_role' => $targetRole,
                'direction' => 'upstream',
                'relationship_type' =>
                    $this->relationshipType($sourceRole),
            ];
        }

        return null;
    }

    /**
     * ==========================================================================
     * ROLE SUMMARY
     * ==========================================================================
     *
     * Useful for Tinker tests, APIs and future intelligence services.
     */
    public function summary(string $role): ?array
    {
        $data = $this->role($role);

        if ($data === null) {
            return null;
        }

        return [
            'id' => $data['id'] ?? $role,

            'label' => $data['label'] ?? null,

            'description' => $data['description'] ?? null,

            'category' => $this->category($role),

            'relationship_type' =>
                $this->relationshipType($role),

            'priority' => $data['priority'] ?? null,

            'upstream' => $this->upstream($role),

            'downstream' => $this->downstream($role),

            'active' => (bool) ($data['active'] ?? true),
        ];
    }

    /**
     * ==========================================================================
     * ALL ROLES
     * ==========================================================================
     */
    public function all(): array
    {
        return $this->roles
            ->values()
            ->all();
    }

    /**
     * Return all canonical role IDs.
     */
    public function roleIds(): array
    {
        return $this->rolesById
            ->keys()
            ->values()
            ->all();
    }

    /**
     * Return roles belonging to a category.
     */
    public function rolesByCategory(
        string $category
    ): array {

        $category = trim($category);

        if ($category === '') {
            return [];
        }

        return $this->roles
            ->filter(
                fn (array $role) =>
                    ($role['category'] ?? null) === $category
            )
            ->values()
            ->all();
    }

    /**
     * ==========================================================================
     * INTERNAL: RELATIONSHIP LIST
     * ==========================================================================
     */
    protected function relationshipList(
        string $role,
        string $direction
    ): array {

        $data = $this->role($role);

        if ($data === null) {
            return [];
        }

        $relationships = $data[$direction] ?? [];

        if (!is_array($relationships)) {
            return [];
        }

        return collect($relationships)
            ->filter(
                fn ($relatedRole) =>
                    is_string($relatedRole) &&
                    trim($relatedRole) !== ''
            )
            ->map(
                fn (string $relatedRole) =>
                    $this->normalizeRole($relatedRole)
            )
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Normalize canonical role identifier.
     */
    protected function normalizeRole(
        string $role
    ): string {

        return strtolower(
            trim($role)
        );
    }
}