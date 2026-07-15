<?php

declare(strict_types=1);

namespace App\Services\MasterData\Identity;

use InvalidArgumentException;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Identity Resolver
 * ==========================================================================
 *
 * Resolves Master Data schema identities.
 *
 * Responsibilities
 * ----------------
 * - Build SchemaIdentity from relative path
 * - Resolve schema ID
 * - Resolve namespace
 * - Normalize paths
 * - Support backward compatibility
 *
 * This class NEVER:
 * - Reads schema files
 * - Loads schemas
 * - Detects references
 * - Builds Knowledge Graph
 *
 * ==========================================================================
 */
final class SchemaIdentityResolver
{

    /**
     * =========================================================================
     * Resolve
     * =========================================================================
     *
     * Example:
     *
     * Business/business_roles.php
     *      ↓
     * business_roles
     */
public function resolve(
    string $relativePath,
    ?string $type = null
): SchemaIdentity
{
    $path = $this->normalizePath(
        $relativePath
    );

    return new SchemaIdentity(

        id: $this->schemaId($path),

        namespace: $this->namespace($path),

        path: $path,

        type: $type ?? 'unknown',

    );
}

    /**
     * =========================================================================
     * Normalize Path
     * =========================================================================
     */
    public function normalizePath(
        string $path
    ): string
    {
        $path = str_replace(
            '\\',
            '/',
            trim($path)
        );

        $path = ltrim($path, '/');

        if ($path === '') {

            throw new InvalidArgumentException(
                'Schema path cannot be empty.'
            );

        }

        return $path;
    }

    /**
     * =========================================================================
     * Schema ID
     * =========================================================================
     *
     * Business/business_roles.php
     *      ↓
     * business_roles
     */
    public function schemaId(
        string $path
    ): string
    {
        return pathinfo(
            $this->normalizePath($path),
            PATHINFO_FILENAME
        );
    }

    /**
     * =========================================================================
     * Namespace
     * =========================================================================
     *
     * Business/business_roles.php
     *      ↓
     * Business
     */
    public function namespace(
        string $path
    ): string
    {
        $path = $this->normalizePath(
            $path
        );

        $directory = dirname($path);

        return $directory === '.'
            ? ''
            : str_replace(
                '/',
                '\\',
                $directory
            );
    }

    /**
     * =========================================================================
     * Filename
     * =========================================================================
     */
    public function filename(
        string $path
    ): string
    {
        return basename(
            $this->normalizePath($path)
        );
    }

    /**
     * =========================================================================
     * Is Same Schema
     * =========================================================================
     *
     * Compares using Schema ID, not file path.
     */
    public function isSame(
        string $left,
        string $right
    ): bool
    {
        return $this->schemaId($left)
            ===
            $this->schemaId($right);
    }

    /**
     * =========================================================================
     * Alias
     * =========================================================================
     *
     * Supports legacy references.
     *
     * Example:
     *
     * business_roles.php
     *      ↓
     * business_roles
     */
    public function alias(
        string $legacy
    ): string
    {
        return $this->schemaId(
            $legacy
        );
    }

    /**
     * =========================================================================
     * Qualified Name
     * =========================================================================
     *
     * Business::business_roles
     */
    public function qualifiedName(
        SchemaIdentity $identity
    ): string
    {
        if ($identity->namespace() === '') {

            return $identity->id();

        }

        return sprintf(

            '%s::%s',

            $identity->namespace(),

            $identity->id()

        );
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(
        SchemaIdentity $identity
    ): array
    {
        return $identity->toArray();
    }
    
}