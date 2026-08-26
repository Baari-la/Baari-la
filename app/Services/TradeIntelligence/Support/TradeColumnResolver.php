<?php

namespace App\Services\TradeIntelligence\Support;

final class TradeColumnResolver
{
    /*
    |--------------------------------------------------------------------------
    | Resolve Database Columns
    |--------------------------------------------------------------------------
    |
    | Central definition of the trade_statistics column mapping.
    |
    | Business / intelligence components should use these
    | canonical aliases instead of hard-coding database
    | column names throughout the application.
    |
    */

    public function resolve(): array
    {
        return [
            'year' =>
                'year',

            'month' =>
                'month',

            'hs_code' =>
                'hs_code',

            'hs_description' =>
                'hs_description',

            'trade_value' =>
                'trade_value',

            'trade_volume' =>
                'trade_volume',

            'flow' =>
                'trade_flow',

            'country' =>
                'country_name',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Individual Column Accessors
    |--------------------------------------------------------------------------
    */

    public function year(): string
    {
        return $this->resolve()['year'];
    }


    public function month(): string
    {
        return $this->resolve()['month'];
    }


    public function hsCode(): string
    {
        return $this->resolve()['hs_code'];
    }


    public function hsDescription(): string
    {
        return $this->resolve()['hs_description'];
    }


    public function tradeValue(): string
    {
        return $this->resolve()['trade_value'];
    }


    public function tradeVolume(): string
    {
        return $this->resolve()['trade_volume'];
    }


    public function flow(): string
    {
        return $this->resolve()['flow'];
    }


    public function country(): string
    {
        return $this->resolve()['country'];
    }
}