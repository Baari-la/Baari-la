<?php

declare(strict_types=1);

namespace App\Services\Support;

/**
 * DIGESTEX CORE
 * --------------------------------------------------------------------------
 * Unit Converter
 * --------------------------------------------------------------------------
 * Generic unit conversion.
 *
 * Later this class will use mst_units.
 */
class UnitConverter
{
    /**
     * Convert Value
     */
    public static function convert(
        float $value,
        float $factor
    ): float {

        return $value * $factor;
    }

    /**
     * KG -> Ton
     */
    public static function kgToTon(float $kg): float
    {
        return $kg / 1000;
    }

    /**
     * Ton -> KG
     */
    public static function tonToKg(float $ton): float
    {
        return $ton * 1000;
    }

    /**
     * Gram -> KG
     */
    public static function gramToKg(float $gram): float
    {
        return $gram / 1000;
    }

    /**
     * KG -> Gram
     */
    public static function kgToGram(float $kg): float
    {
        return $kg * 1000;
    }

    /**
     * Meter -> Yard
     */
    public static function meterToYard(float $meter): float
    {
        return $meter * 1.09361;
    }

    /**
     * Yard -> Meter
     */
    public static function yardToMeter(float $yard): float
    {
        return $yard * 0.9144;
    }
}