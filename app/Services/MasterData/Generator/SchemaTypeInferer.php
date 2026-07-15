<?php

declare(strict_types=1);

namespace App\Services\MasterData\Generator;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Schema Type Inferer
 * ==========================================================================
 *
 * Infers data types from analyzed Master Data fields.
 *
 * Supported Types
 * ----------------
 * - string
 * - integer
 * - float
 * - boolean
 * - array
 * - object
 * - null
 * - mixed
 *
 * This class performs NO validation.
 * This class performs NO schema generation.
 *
 * Used by:
 *
 * MasterDataSchemaGenerator
 *
 * ==========================================================================
 */
class SchemaTypeInferer
{
    /**
     * =========================================================================
     * Infer Types
     * =========================================================================
     *
     * @param array<string,mixed> $analysis
     * @return array<string,string>
     */
    public function infer(array $analysis): array
    {
        $types = [];

        foreach ($analysis['examples'] as $field => $example) {

            $types[$field] = $this->inferType($example);

        }

        ksort($types);

        return $types;
    }

    /**
     * =========================================================================
     * Infer Single Type
     * =========================================================================
     */
    protected function inferType(
        mixed $value
    ): string
    {
        if (is_null($value)) {
            return 'null';
        }

        if (is_bool($value)) {
            return 'boolean';
        }

        if (is_int($value)) {
            return 'integer';
        }

        if (is_float($value)) {
            return 'float';
        }

        if (is_string($value)) {
            return 'string';
        }

        if (is_array($value)) {

            return $this->inferArrayType($value);

        }

        if (is_object($value)) {
            return 'object';
        }

        return 'mixed';
    }

    /**
     * =========================================================================
     * Infer Array Type
     * =========================================================================
     *
     * Determines whether an array is a list or associative array.
     */
    protected function inferArrayType(
        array $value
    ): string
    {
        if ($value === []) {
            return 'array';
        }

        return array_is_list($value)
            ? 'array'
            : 'object';
    }

    /**
     * =========================================================================
     * Is Scalar Type
     * =========================================================================
     */
    public function isScalar(
        string $type
    ): bool
    {
        return in_array(
            $type,
            [
                'string',
                'integer',
                'float',
                'boolean',
            ],
            true
        );
    }

    /**
     * =========================================================================
     * Is Collection Type
     * =========================================================================
     */
    public function isCollection(
        string $type
    ): bool
    {
        return in_array(
            $type,
            [
                'array',
                'object',
            ],
            true
        );
    }
}