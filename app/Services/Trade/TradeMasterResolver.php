<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Models\Country;
use App\Models\Province;
use App\Models\TradePoint;
use App\Models\TradePointType;
use App\Models\TextileSector;
use App\Models\HsCode;

class TradeMasterResolver
{
    /**
     * Resolve HS master.
     *
     * HS code is the primary identity.
     * Description is treated as source metadata, not identity.
     */
    public function resolveHs(
        string $hsCode,
        ?string $description = null
    ): ?HsCode {
        $hsCode = $this->normalizeHs($hsCode);

        if ($hsCode === '') {
            return null;
        }

        $hs = HsCode::query()
            ->where('hs_code', $hsCode)
            ->first();

        return $hs;
    }

    /**
     * Resolve country against existing mst_countries master.
     *
     * Prefer country code, then ISO3, then normalized names.
     */
    public function resolveCountry(
        ?string $countryCode,
        ?string $countryName
    ): ?Country {
        $countryCode = $this->normalizeCode($countryCode);
        $countryName = $this->normalizeText($countryName);

        $query = Country::query();

        if ($countryCode !== '') {
            $country = (clone $query)
                ->where(function ($q) use ($countryCode) {
                    $q->whereRaw(
                        'UPPER(country_code) = ?',
                        [$countryCode]
                    )->orWhereRaw(
                        'UPPER(iso3) = ?',
                        [$countryCode]
                    );
                })
                ->first();

            if ($country) {
                return $country;
            }
        }

        if ($countryName !== '') {
            $country = (clone $query)
                ->where(function ($q) use ($countryName) {
                    $q->whereRaw(
                        'UPPER(TRIM(country_name_en)) = ?',
                        [$countryName]
                    )->orWhereRaw(
                        'UPPER(TRIM(country_name_id)) = ?',
                        [$countryName]
                    )->orWhereRaw(
                        'UPPER(TRIM(official_name)) = ?',
                        [$countryName]
                    );
                })
                ->first();

            if ($country) {
                return $country;
            }
        }

        return null;
    }

    /**
     * Resolve Indonesian province.
     */
    public function resolveProvince(
        ?string $provinceName
    ): ?Province {
        $provinceName = $this->normalizeText(
            $provinceName
        );

        if ($provinceName === '') {
            return null;
        }

        return Province::query()
            ->whereRaw(
                'UPPER(TRIM(name)) = ?',
                [$provinceName]
            )
            ->orWhereRaw(
                'UPPER(TRIM(name_en)) = ?',
                [$provinceName]
            )
            ->first();
    }

    /**
     * Resolve trade point by name.
     *
     * Type is resolved separately because the source column
     * contains the raw trade-point name.
     */
    public function resolveTradePoint(
        ?string $tradePointName
    ): ?TradePoint {
        $tradePointName = $this->normalizeText(
            $tradePointName
        );

        if ($tradePointName === '') {
            return null;
        }

        return TradePoint::query()
            ->whereRaw(
                'UPPER(TRIM(name)) = ?',
                [$tradePointName]
            )
            ->orWhereRaw(
                'UPPER(TRIM(name_en)) = ?',
                [$tradePointName]
            )
            ->first();
    }

    /**
     * Resolve trade-point type from existing master mapping.
     */
    public function resolveTradePointType(
        ?string $typeCode
    ): ?TradePointType {
        $typeCode = $this->normalizeCode(
            $typeCode
        );

        if ($typeCode === '') {
            return null;
        }

        return TradePointType::query()
            ->whereRaw(
                'UPPER(code) = ?',
                [$typeCode]
            )
            ->first();
    }

    /**
     * Normalize HS.
     */
    protected function normalizeHs(
        ?string $value
    ): string {
        $value = trim((string) $value);

        return preg_replace(
            '/\D+/',
            '',
            $value
        ) ?? '';
    }

    /**
     * Normalize general text.
     */
    protected function normalizeText(
        ?string $value
    ): string {
        $value = trim((string) $value);

        $value = preg_replace(
            '/\s+/',
            ' ',
            $value
        ) ?? '';

        return mb_strtoupper($value);
    }

    /**
     * Normalize code.
     */
    protected function normalizeCode(
        ?string $value
    ): string {
        return $this->normalizeText($value);
    }
}