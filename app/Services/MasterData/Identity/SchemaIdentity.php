<?php

declare(strict_types=1);

namespace App\Services\MasterData\Identity;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Identity
 * ==========================================================================
 *
 * Immutable identity for a Master Data schema.
 *
 * Responsibilities
 * ----------------
 * - Represent a unique schema identity
 * - Store schema namespace
 * - Store physical file path
 * - Provide serialization
 *
 * This class NEVER:
 * - Reads files
 * - Resolves paths
 * - Loads schemas
 * - Detects references
 * - Modifies state
 *
 * ==========================================================================
 */
final readonly class SchemaIdentity
{
    /**
     * Constructor.
     */
    public function __construct(

        /**
         * Stable schema identifier.
         *
         * Example:
         * business_roles
         * product_categories
         */
        protected string $id,

        /**
         * Logical namespace.
         *
         * Example:
         * Business
         * Products
         * Certification
         */
        protected string $namespace,

        /**
         * Physical relative path.
         *
         * Example:
         * Business/business_roles.php
         */
        protected string $path,

        /**
         * Schema type.
         *
         * Example:
         * lookup
         * knowledge_node
         * relationship
         */
        protected string $type,

        /**
         * Schema version.
         */
        protected int $version = 2,
    ) {
    }

    /**
     * =========================================================================
     * ID
     * =========================================================================
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * =========================================================================
     * Namespace
     * =========================================================================
     */
    public function namespace(): string
    {
        return $this->namespace;
    }

    /**
     * =========================================================================
     * Path
     * =========================================================================
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * =========================================================================
     * Type
     * =========================================================================
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * =========================================================================
     * Version
     * =========================================================================
     */
    public function version(): int
    {
        return $this->version;
    }

    /**
     * =========================================================================
     * Filename
     * =========================================================================
     */
    public function filename(): string
    {
        return basename($this->path);
    }

    /**
     * =========================================================================
     * Directory
     * =========================================================================
     */
    public function directory(): string
    {
        $directory = dirname($this->path);

        return $directory === '.'
            ? ''
            : str_replace('\\', '/', $directory);
    }

    /**
     * =========================================================================
     * Qualified Name
     * =========================================================================
     *
     * Example:
     * Business::business_roles
     */
    public function qualifiedName(): string
    {
        if ($this->namespace === '') {
            return $this->id;
        }

        return sprintf(
            '%s::%s',
            $this->namespace,
            $this->id
        );
    }

    /**
     * =========================================================================
     * Equals
     * =========================================================================
     */
    public function equals(
        self $identity
    ): bool
    {
        return $this->id === $identity->id;
    }

    /**
     * =========================================================================
     * To Array
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [

            'id' => $this->id,

            'namespace' => $this->namespace,

            'path' => $this->path,

            'type' => $this->type,

            'version' => $this->version,

        ];
    }

    /**
     * =========================================================================
     * From Array
     * =========================================================================
     *
     * @param array<string,mixed> $data
     */
    public static function fromArray(
        array $data
    ): self
    {
        return new self(

            id: (string) ($data['id'] ?? ''),

            namespace: (string) ($data['namespace'] ?? ''),

            path: (string) ($data['path'] ?? ''),

            type: (string) ($data['type'] ?? 'unknown'),

            version: (int) ($data['version'] ?? 2),

        );
    }

    /**
     * =========================================================================
     * String Representation
     * =========================================================================
     */
    public function __toString(): string
    {
        return $this->id;
    }
}