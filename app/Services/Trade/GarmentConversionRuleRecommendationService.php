<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Services\Trade\Taxonomy\TextileTaxonomyService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GarmentConversionRuleRecommendationService
{
    private const BENCHMARK_HS = '61091020';
    private const BENCHMARK_FACTOR = 0.190000;

    public function __construct(
        private readonly TextileTaxonomyService $taxonomy,
    ) {
    }

    /**
     * Produce READ-ONLY rule-based recommendations.
     *
     * No database write.
     * No factor activation.
     * No modification of existing ACTIVE factors.
     */
    public function recommend(string $hsCode): array
    {
        $hsCode = $this->normalizeHsCode($hsCode);

        $row = DB::table('mst_hscode')
            ->where('hs_code', $hsCode)
            ->where('is_apparel', 1)
            ->where('is_active', 1)
            ->first([
                'hs_code',
                'uraian_hs_en',
                'product_family',
            ]);

        if ($row === null) {
            return [
                'status' => 'NOT_FOUND',
                'hs_code' => $hsCode,
                'recommended_factor' => null,
                'reason' => 'HS-8 is not an active canonical garment HS.',
            ];
        }

        /*
         * Existing ACTIVE factor always has priority.
         * Recommendation engine must never overwrite it.
         */
        $activeFactor = DB::table('garment_conversion_factors')
            ->where('hs_code', $hsCode)
            ->where('status', 'ACTIVE')
            ->orderByDesc('effective_from')
            ->first();

        if ($activeFactor !== null) {
            return [
                'status' => 'EXISTING_ACTIVE',
                'hs_code' => $hsCode,
                'product_family' => $row->product_family,
                'garment_type' => null,
                'material' => null,
                'wearer' => null,
                'weight_class' => null,
                'recommended_factor' => (float) $activeFactor->kg_per_pcs,
                'factor_unit' => 'KG_PER_PCS',
                'source' => 'ACTIVE_FACTOR',
                'reason' => 'Existing ACTIVE factor is authoritative.',
            ];
        }

        /*
         * DIGESTEX benchmark lock.
         */
        if ($hsCode === self::BENCHMARK_HS) {
            return [
                'status' => 'BENCHMARK_LOCKED',
                'hs_code' => $hsCode,
                'product_family' => $row->product_family,
                'garment_type' => 'T_SHIRT',
                'material' => 'COTTON',
                'wearer' => $this->detectWearer(
                    (string) $row->uraian_hs_en
                ),
                'weight_class' => 'LIGHT',
                'recommended_factor' => self::BENCHMARK_FACTOR,
                'factor_unit' => 'KG_PER_PCS',
                'source' => 'DIGESTEX_BENCHMARK',
                'reason' => 'Locked DIGESTEX benchmark.',
            ];
        }

        $description = mb_strtolower(
            trim((string) $row->uraian_hs_en)
        );

        $characteristics = $this->classifyCharacteristics(
            $description,
            (string) $row->product_family,
        );

        /*
         * Some methodologies should not receive an automatic
         * direct factor.
         */
        if ($characteristics['direct_factor'] === false) {
            return [
                'status' => 'REVIEW',
                'hs_code' => $hsCode,
                'product_family' => $row->product_family,
                'garment_type' => $characteristics['garment_type'],
                'material' => $characteristics['material'],
                'wearer' => $characteristics['wearer'],
                'weight_class' => $characteristics['weight_class'],
                'recommended_factor' => null,
                'factor_unit' => 'KG_PER_PCS',
                'source' => 'DIGESTEX_RULE_ENGINE',
                'reason' => $characteristics['reason'],
            ];
        }

        $factor = $this->calculateFactor(
            $characteristics
        );

        return [
            'status' => 'RECOMMENDED',
            'hs_code' => $hsCode,
            'product_family' => $row->product_family,
            'garment_type' => $characteristics['garment_type'],
            'material' => $characteristics['material'],
            'wearer' => $characteristics['wearer'],
            'weight_class' => $characteristics['weight_class'],
            'recommended_factor' => round($factor, 6),
            'factor_unit' => 'KG_PER_PCS',
            'source' => 'DIGESTEX_RULE_ENGINE',
            'reason' => 'Rule-based industry estimation.',
        ];
    }

    /**
     * Generate preview for all canonical Garment HS-8.
     */
    public function recommendAll(): Collection
    {
        $hsCodes = $this->taxonomy->hsCodesForSector('garment');

        return collect($hsCodes)
            ->map(
                fn (string $hsCode): array =>
                    $this->recommend($hsCode)
            )
            ->values();
    }

    private function classifyCharacteristics(
        string $description,
        string $productFamily,
    ): array {
        $garmentType = $this->detectGarmentType(
            $description,
            $productFamily
        );

        $material = $this->detectMaterial($description);

        $wearer = $this->detectWearer($description);

        $weightClass = $this->detectWeightClass(
            $garmentType,
            $description
        );

        /*
         * Broad/residual descriptions should remain REVIEW.
         */
        if (
            $this->containsAny(
                $description,
                [
                    'other textile materials',
                    'other textile products',
                    'other articles',
                    'other garments',
                ]
            )
        ) {
            return [
                'direct_factor' => false,
                'garment_type' => $garmentType,
                'material' => $material,
                'wearer' => $wearer,
                'weight_class' => $weightClass,
                'reason' => 'Broad/residual HS description requires review.',
            ];
        }

        return [
            'direct_factor' => true,
            'garment_type' => $garmentType,
            'material' => $material,
            'wearer' => $wearer,
            'weight_class' => $weightClass,
            'reason' => 'Direct rule-based classification.',
        ];
    }

    private function calculateFactor(array $c): float
    {
        /*
         * IMPORTANT:
         * These are rule parameters, NOT yet final DIGESTEX
         * standards. They are intentionally isolated here so
         * they can be calibrated after the 352-HS preview.
         */
        $base = match ($c['garment_type']) {
            'T_SHIRT' => 0.190000,
            'UNDERWEAR' => 0.090000,
            'SOCKS' => 0.055000,
            'SHIRT' => 0.220000,
            'TROUSERS' => 0.350000,
            'DRESS' => 0.320000,
            'SWEATER' => 0.400000,
            'JACKET' => 0.550000,
            'SWIMWEAR' => 0.100000,
            default => 0.250000,
        };

        $material = match ($c['material']) {
            'COTTON' => 1.000000,
            'SYNTHETIC' => 0.900000,
            'WOOL' => 1.250000,
            'SILK' => 0.750000,
            'FLAX' => 1.050000,
            default => 1.000000,
        };

        $wearer = match ($c['wearer']) {
            'BABY' => 0.550000,
            'CHILDREN' => 0.750000,
            'WOMEN' => 0.950000,
            'MEN' => 1.050000,
            default => 1.000000,
        };

        $weight = match ($c['weight_class']) {
            'VERY_LIGHT' => 0.750000,
            'LIGHT' => 0.900000,
            'MEDIUM' => 1.000000,
            'HEAVY' => 1.250000,
            'VERY_HEAVY' => 1.500000,
            default => 1.000000,
        };

        return $base * $material * $wearer * $weight;
    }

    private function detectGarmentType(
        string $description,
        string $productFamily,
    ): string {
        if ($this->containsAny(
            $description,
            ['t-shirt', 't shirt']
        )) {
            return 'T_SHIRT';
        }

        if ($this->containsAny(
            $description,
            ['underwear', 'underpants', 'brief', 'panties']
        )) {
            return 'UNDERWEAR';
        }

        if ($this->containsAny(
            $description,
            ['sock', 'stocking', 'tights']
        )) {
            return 'SOCKS';
        }

        if ($this->containsAny(
            $description,
            ['trouser', 'pants', 'shorts']
        )) {
            return 'TROUSERS';
        }

        if (str_contains($description, 'dress')) {
            return 'DRESS';
        }

        if ($this->containsAny(
            $description,
            ['shirt', 'blouse']
        )) {
            return 'SHIRT';
        }

        if ($this->containsAny(
            $description,
            ['sweater', 'pullover', 'cardigan']
        )) {
            return 'SWEATER';
        }

        if ($this->containsAny(
            $description,
            ['jacket', 'coat', 'overcoat', 'anorak']
        )) {
            return 'JACKET';
        }

        if ($this->containsAny(
            $description,
            ['swimwear', 'bathing']
        )) {
            return 'SWIMWEAR';
        }

        return match ($productFamily) {
            'T-Shirts' => 'T_SHIRT',
            'Underwear' => 'UNDERWEAR',
            'Trousers / Pants' => 'TROUSERS',
            'Shirts / Blouses' => 'SHIRT',
            'Jackets / Outerwear' => 'JACKET',
            'Sportswear' => 'SPORTSWEAR',
            default => 'OTHER_APPAREL',
        };
    }

    private function detectMaterial(string $description): string
    {
        return match (true) {
            str_contains($description, 'cotton') => 'COTTON',
            str_contains($description, 'wool') => 'WOOL',
            str_contains($description, 'silk') => 'SILK',
            str_contains($description, 'linen'),
            str_contains($description, 'flax') => 'FLAX',
            str_contains($description, 'synthetic fibre'),
            str_contains($description, 'synthetic fibres'),
            str_contains($description, 'man-made fibre'),
            str_contains($description, 'man-made fibres') => 'SYNTHETIC',
            default => 'OTHER_TEXTILE',
        };
    }

    private function detectWearer(string $description): string
    {
        return match (true) {
            $this->containsAny(
                $description,
                ['baby', 'infant']
            ) => 'BABY',

            $this->containsAny(
                $description,
                ['children', 'child', 'boys', 'girls']
            ) => 'CHILDREN',

            $this->containsAny(
                $description,
                ['women', 'ladies']
            ) => 'WOMEN',

            str_contains($description, 'men') => 'MEN',

            default => 'UNISEX',
        };
    }

    private function detectWeightClass(
        string $garmentType,
        string $description,
    ): string {
        if (
            in_array(
                $garmentType,
                ['UNDERWEAR', 'SWIMWEAR'],
                true
            )
        ) {
            return 'VERY_LIGHT';
        }

        if (
            in_array(
                $garmentType,
                ['T_SHIRT', 'SOCKS'],
                true
            )
        ) {
            return 'LIGHT';
        }

        if (
            in_array(
                $garmentType,
                ['JACKET'],
                true
            )
        ) {
            if (
                $this->containsAny(
                    $description,
                    ['overcoat', 'cloak', 'heavy']
                )
            ) {
                return 'VERY_HEAVY';
            }

            return 'HEAVY';
        }

        if (
            in_array(
                $garmentType,
                ['TROUSERS', 'DRESS', 'SWEATER'],
                true
            )
        ) {
            return 'MEDIUM';
        }

        return 'MEDIUM';
    }

    private function containsAny(
        string $text,
        array $terms
    ): bool {
        foreach ($terms as $term) {
            if (str_contains($text, $term)) {
                return true;
            }
        }

        return false;
    }

    private function normalizeHsCode(string $hsCode): string
    {
        return preg_replace(
            '/\D/',
            '',
            trim($hsCode)
        ) ?? '';
    }
}