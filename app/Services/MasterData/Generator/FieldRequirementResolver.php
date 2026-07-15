<?php

declare(strict_types=1);

namespace App\Services\MasterData\Generator;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Field Requirement Resolver
 * ==========================================================================
 *
 * Determines required and optional fields from field analysis.
 *
 * Responsibilities
 * ----------------
 * - Resolve required fields
 * - Resolve optional fields
 * - Apply global rules
 *
 * This class performs NO file reading.
 * This class performs NO schema generation.
 *
 * ==========================================================================
 */
final class FieldRequirementResolver
{
    /**
     * Global required fields.
     *
     * @var array<int,string>
     */
    protected array $required = [

        'id',

        'label',

    ];

    /**
     * =========================================================================
     * Resolve
     * =========================================================================
     *
     * @param array<string,mixed> $analysis
     *
     * @return array{
     *     required:array<int,string>,
     *     optional:array<int,string>
     * }
     */
    public function resolve(
        array $analysis
    ): array
    {
        $required = $this->requiredFields(
            $analysis
        );

        $optional = $this->optionalFields(

            $analysis,

            $required

        );

        return [

            'required' => $required,

            'optional' => $optional,

        ];
    }

    /**
     * =========================================================================
     * Required Fields
     * =========================================================================
     *
     * @param array<string,mixed> $analysis
     *
     * @return array<int,string>
     */
    public function requiredFields(
        array $analysis
    ): array
    {
        $required = [];

        foreach (
            $analysis['fields'] ?? [] as $field
        ) {

            if (
                in_array(
                    $field,
                    $this->required,
                    true
                )
            ) {

                $required[] = $field;

            }

        }

        sort($required);

        return $required;
    }

    /**
     * =========================================================================
     * Optional Fields
     * =========================================================================
     *
     * @param array<string,mixed> $analysis
     * @param array<int,string> $required
     *
     * @return array<int,string>
     */
    public function optionalFields(
        array $analysis,
        array $required
    ): array
    {
        $optional = array_values(

            array_diff(

                $analysis['fields'] ?? [],

                $required

            )

        );

        sort($optional);

        return $optional;
    }

    /**
     * =========================================================================
     * Register Required Field
     * =========================================================================
     */
    public function addRequiredField(
        string $field
    ): void
    {
        if (! in_array(
            $field,
            $this->required,
            true
        )) {

            $this->required[] = $field;

            sort($this->required);

        }
    }

    /**
     * =========================================================================
     * Required Rules
     * =========================================================================
     *
     * @return array<int,string>
     */
    public function requiredRules(): array
    {
        return $this->required;
    }
}