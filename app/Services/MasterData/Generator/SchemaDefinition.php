<?php

declare(strict_types=1);

namespace App\Services\MasterData\Generator;
use App\Services\MasterData\Reference\ReferenceDefinition;
use App\Services\MasterData\Identity\SchemaIdentity;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Definition
 * ==========================================================================
 *
 * Represents one generated Master Data schema.
 *
 * Responsibilities
 * ----------------
 * - Store schema metadata
 * - Store field definitions
 * - Store references
 * - Convert to/from array
 *
 * ==========================================================================
 */
final class SchemaDefinition
{
    /**
     * =========================================================================
     * Constructor
     * =========================================================================
     */
    public function __construct(
        protected string $type
    ) {
    }

    /**
     * @var array<int,string>
     */
    protected array $required = [];

    /**
     * @var array<int,string>
     */
    protected array $optional = [];

    /**
     * @var array<string,string>
     */
    protected array $types = [];

    /**
 * =========================================================================
 * Schema Identity
 * =========================================================================
 */
    protected ?SchemaIdentity $identity = null;

    /**
     * @var array<string,ReferenceDefinition>
     */
    protected array $references = [];

    /**
     * @var array<string,mixed>
     */
    protected array $validation = [];

    /*
    |--------------------------------------------------------------------------
    | Factory
    |--------------------------------------------------------------------------
    */

    /**
     * =========================================================================
     * From Array
     * =========================================================================
     *
     * @param array<string,mixed> $schema
     */
    public static function fromArray(
        array $schema
    ): self
    {
        $definition = new self(
            (string) ($schema['type'] ?? 'lookup')
        );

        $definition->setRequired(
            $schema['required'] ?? []
        );

        $definition->setOptional(
            $schema['optional'] ?? []
        );

        $definition->setTypes(
            $schema['types'] ?? []
        );

        $references = [];

        foreach (($schema['references'] ?? []) as $field => $reference) {

            if ($reference instanceof ReferenceDefinition) {

                $references[$field] = $reference;

                continue;

            }

            if (is_array($reference)) {

                $references[$field] = ReferenceDefinition::fromArray(
                    $reference
                );

            }

        }

        $definition->setReferences(
            $references
        );

        $definition->setValidation(
            $schema['validation'] ?? []
        );
if (
    isset($data['identity']) &&
    is_array($data['identity'])
) {

    $definition->setIdentity(

        SchemaIdentity::fromArray(
            $data['identity']
        )

    );

}

        return $definition;
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    public function setRequired(
        array $required
    ): self
    {
        sort($required);

        $this->required = array_values($required);

        return $this;
    }

    public function setOptional(
        array $optional
    ): self
    {
        sort($optional);

        $this->optional = array_values($optional);

        return $this;
    }

    public function setTypes(
        array $types
    ): self
    {
        ksort($types);

        $this->types = $types;

        return $this;
    }

    /**
     * @param array<string,ReferenceDefinition> $references
     */
    public function setReferences(
        array $references
    ): self
    {
        ksort($references);

        $this->references = $references;

        return $this;
    }

    public function setValidation(
        array $validation
    ): self
    {
        $this->validation = $validation;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return array<int,string>
     */
    public function required(): array
    {
        return $this->required;
    }

    /**
     * @return array<int,string>
     */
    public function optional(): array
    {
        return $this->optional;
    }

    /**
     * @return array<string,string>
     */
    public function types(): array
    {
        return $this->types;
    }

    /**
     * @return array<string,ReferenceDefinition>
     */
    public function references(): array
    {
        return $this->references;
    }

    /**
     * @return array<string,mixed>
     */
    public function validation(): array
    {
        return $this->validation;
    }

    /*
    |--------------------------------------------------------------------------
    | Export
    |--------------------------------------------------------------------------
    */

    /**
     * =========================================================================
     * Array Representation
     * =========================================================================
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $references = [];

        foreach ($this->references as $field => $reference) {

            $references[$field] = $reference->toArray();

        }

        return [

        'identity' =>
        $this->identity?->toArray(),
            'type' => $this->type,

            'required' => $this->required,

            'optional' => $this->optional,

            'types' => $this->types,

            'references' => $references,

            'validation' => $this->validation,

        ];
    }
    /**
 * =========================================================================
 * Identity
 * =========================================================================
 */
public function identity(): ?SchemaIdentity
{
    return $this->identity;
}

/**
 * =========================================================================
 * Set Identity
 * =========================================================================
 */
public function setIdentity(
    SchemaIdentity $identity
): self
{
    $this->identity = $identity;

    return $this;
}
/**
 * =========================================================================
 * Schema ID
 * =========================================================================
 */
public function schemaId(): string
{
    return $this->identity?->id() ?? '';
}

/**
 * =========================================================================
 * Namespace
 * =========================================================================
 */
public function namespace(): string
{
    return $this->identity?->namespace() ?? '';
}

/**
 * =========================================================================
 * Relative Path
 * =========================================================================
 */
public function path(): string
{
    return $this->identity?->path() ?? '';
}

/**
 * =========================================================================
 * Has Identity
 * =========================================================================
 */
public function hasIdentity(): bool
{
    return $this->identity !== null;
}
}