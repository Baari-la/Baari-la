<?php

declare(strict_types=1);

namespace App\Services\MasterData\Repository;

use Illuminate\Support\Collection;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Alias Repository
 * ==========================================================================
 *
 * Loads Master Data reference aliases from:
 *
 * config/masterdata/aliases.php
 *
 * Responsibilities
 * ----------------
 * - Load aliases
 * - Resolve alias
 * - Register alias
 * - Expose alias collection
 *
 * This class performs NO validation.
 * This class performs NO detection.
 *
 * ==========================================================================
 */
class SchemaAliasRepository
{
    /**
     * Alias definitions.
     *
     * @var array<string,string>
     */
    protected array $aliases;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $aliases = config('masterdata.aliases');

        $this->aliases = is_array($aliases)
            ? $aliases
            : [];
    }

    /**
     * =========================================================================
     * Resolve
     * =========================================================================
     */
    public function resolve(
        string $alias
    ): ?string
    {
        return $this->aliases[
            $this->normalize($alias)
        ] ?? null;
    }

    /**
     * =========================================================================
     * Has
     * =========================================================================
     */
    public function has(
        string $alias
    ): bool
    {
        return isset(
            $this->aliases[
                $this->normalize($alias)
            ]
        );
    }

    /**
     * =========================================================================
     * Register
     * =========================================================================
     */
    public function register(
        string $alias,
        string $target
    ): void
    {
        $this->aliases[
            $this->normalize($alias)
        ] = $target;

        ksort($this->aliases);
    }

    /**
     * =========================================================================
     * All
     * =========================================================================
     *
     * @return array<string,string>
     */
    public function all(): array
    {
        ksort($this->aliases);

        return $this->aliases;
    }

    /**
     * =========================================================================
     * Collection
     * =========================================================================
     */
    public function collection(): Collection
    {
        return collect(
            $this->all()
        );
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
     * Normalize
     * =========================================================================
     */
    protected function normalize(
        string $alias
    ): string
    {
        return strtolower(
            trim($alias)
        );
    }
}