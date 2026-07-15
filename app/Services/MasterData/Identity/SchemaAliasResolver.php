<?php

declare(strict_types=1);

namespace App\Services\MasterData\Identity;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Alias Resolver
 * ==========================================================================
 *
 * Resolves legacy schema identifiers into canonical Schema IDs.
 *
 * Responsibilities
 * ----------------
 * - Resolve legacy aliases
 * - Normalize schema IDs
 * - Support backward compatibility
 * - Hide file path differences
 *
 * This class NEVER:
 * - Reads schema files
 * - Loads schemas
 * - Builds Knowledge Graph
 * - Detects references
 *
 * ==========================================================================
 */
final class SchemaAliasResolver
{
    /**
     * Registered aliases.
     *
     * legacy => canonical
     *
     * @var array<string,string>
     */
    protected array $aliases = [];

    /**
     * Constructor.
     *
     * @param array<string,string> $aliases
     */
    public function __construct(
        array $aliases = []
    ) {
        $this->aliases = $aliases;
    }

    /**
     * =========================================================================
     * Resolve
     * =========================================================================
     *
     * Returns canonical Schema ID.
     *
     * Examples
     * --------
     *
     * Business/business_roles.php
     *      ↓
     * business_roles
     *
     * business_roles.php
     *      ↓
     * business_roles
     */
    public function resolve(
        string $identifier
    ): string
    {
        $identifier = $this->normalize(
            $identifier
        );

        return $this->aliases[$identifier]
            ?? $identifier;
    }

    /**
     * =========================================================================
     * Register
     * =========================================================================
     */
    public function register(
        string $legacy,
        string $canonical
    ): self
    {
        $this->aliases[
            $this->normalize($legacy)
        ] = $canonical;

        return $this;
    }

    /**
     * =========================================================================
     * Register Many
     * =========================================================================
     *
     * @param array<string,string> $aliases
     */
    public function registerMany(
        array $aliases
    ): self
    {
        foreach ($aliases as $legacy => $canonical) {

            $this->register(
                $legacy,
                $canonical
            );

        }

        return $this;
    }

    /**
     * =========================================================================
     * Has Alias
     * =========================================================================
     */
    public function has(
        string $identifier
    ): bool
    {
        return isset(

            $this->aliases[
                $this->normalize($identifier)
            ]

        );
    }

    /**
     * =========================================================================
     * Remove
     * =========================================================================
     */
    public function remove(
        string $identifier
    ): void
    {
        unset(

            $this->aliases[
                $this->normalize($identifier)
            ]

        );
    }

    /**
     * =========================================================================
     * Normalize
     * =========================================================================
     *
     * Converts any legacy reference into canonical lookup key.
     */
    protected function normalize(
        string $identifier
    ): string
    {
        $identifier = trim($identifier);

        $identifier = str_replace(
            '\\',
            '/',
            $identifier
        );

        /*
        |--------------------------------------------------------------------------
        | Business/business_roles.php
        |--------------------------------------------------------------------------
        */

        $identifier = pathinfo(
            $identifier,
            PATHINFO_FILENAME
        );

        return strtolower(
            $identifier
        );
    }

    /**
     * =========================================================================
     * Aliases
     * =========================================================================
     *
     * @return array<string,string>
     */
    public function aliases(): array
    {
        ksort($this->aliases);

        return $this->aliases;
    }

    /**
     * =========================================================================
     * Count
     * =========================================================================
     */
    public function count(): int
    {
        return count(
            $this->aliases
        );
    }

    /**
     * =========================================================================
     * Clear
     * =========================================================================
     */
    public function clear(): void
    {
        $this->aliases = [];
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return $this->aliases();
    }
}