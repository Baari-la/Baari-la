<?php

declare(strict_types=1);

namespace App\Services\Company\Facility;

use Illuminate\Support\Collection;

class FacilityAddressSegmenter
{
    /**
     * Split one legacy location record into possible physical-address evidence.
     *
     * This service intentionally DOES NOT infer facility type.
     */
    public function segment(array $record): Collection
    {
        $address = trim((string) ($record['address'] ?? ''));

        if ($address === '') {
            return collect();
        }

        $parts = collect(
            preg_split('/\s*\|\s*/u', $address) ?: []
        )
            ->map(fn ($part) => trim((string) $part))
            ->filter()
            ->values();

        if ($parts->isEmpty()) {
            return collect();
        }

        $segments = [];
        $current = '';

        foreach ($parts as $part) {
            /*
             * A new street marker starts another physical-address candidate.
             *
             * Example:
             *
             * JL. KH. WAHID HASYIM 119, JAKARTA
             * | 10240
             * | JL. RAYA RANCAEKEK KM. 23/25
             * | SUMEDANG, WEST JAVA
             *
             * becomes two candidates rather than four fragments.
             */
            if ($this->startsNewAddress($part)) {
                if ($current !== '') {
                    $segments[] = $this->cleanAddress($current);
                }

                $current = $part;

                continue;
            }

            /*
             * A fragment without a street marker belongs to the current
             * address candidate.
             *
             * Examples:
             *
             * JL. RANCAEKEK MAJALAYA NO. 389
             * | DS SELOKAM JERUKK MAJALAYA BANDUNG, WEST JAVA
             *
             * and:
             *
             * JL. RAYA RANCAEKEK KM. 23/25
             * | SUMEDANG, WEST JAVA
             */
            if ($current === '') {
                $current = $part;

                continue;
            }

            $current .= ', ' . $part;
        }

        if ($current !== '') {
            $segments[] = $this->cleanAddress($current);
        }

        return collect($segments)
            ->filter()
            ->values()
            ->map(
                fn (string $segment, int $index): array => [
                    'company_id' =>
                        $record['company_id'] ?? null,

                    'location_id' =>
                        $record['location_id'] ?? null,

                    'segment_index' =>
                        $index,

                    'address' =>
                        $segment,

                    'phone' =>
                        $record['phone'] ?? null,

                    'city' =>
                        $record['city'] ?? null,

                    'sector' =>
                        $record['sector'] ?? null,

                    'capabilities' =>
                        $record['capabilities'] ?? [],

                    'source' =>
                        'legacy_address_segmentation',

                    'facility_type' =>
                        null,

                    'facility_type_status' =>
                        'unverified',
                ]
            );
    }

    /**
     * Detect strong evidence that a new street address begins.
     */
    private function startsNewAddress(string $value): bool
    {
        return (bool) preg_match(
            '/^(?:JL\.?|JALAN|JLN\.?)\s*/iu',
            trim($value)
        );
    }

    /**
     * Normalize formatting without changing address meaning.
     */
    private function cleanAddress(string $value): string
    {
        $value = preg_replace(
            '/\s+/u',
            ' ',
            trim($value)
        );

        $value = preg_replace(
            '/\s*,\s*,+\s*/u',
            ', ',
            (string) $value
        );

        $value = preg_replace(
            '/\s+,/u',
            ',',
            (string) $value
        );

        return trim(
            (string) $value,
            " \t\n\r\0\x0B,"
        );
    }
}