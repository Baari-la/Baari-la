<?php

declare(strict_types=1);

namespace App\Services\Trade\Executive;

use Illuminate\Support\Collection;

class SupplyChainIntelligenceService
{
    /**
     * --------------------------------------------------------------------------
     * Build Supply Chain Intelligence
     * --------------------------------------------------------------------------
     */
    public function build(
        string $sector = 'textile'
    ): array {

        return [

            'sector' => $sector,

            'upstream' =>

                $this->upstream(
                    $sector
                ),

            'midstream' =>

                $this->midstream(
                    $sector
                ),

            'downstream' =>

                $this->downstream(
                    $sector
                ),

            'supporting' =>

                $this->supporting(),

            'digital' =>

                $this->digital(),

            'sustainability' =>

                $this->sustainability(),

            'ai_insight' =>

                $this->insight(
                    $sector
                ),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Upstream
     * --------------------------------------------------------------------------
     */
    protected function upstream(
        string $sector
    ): array {

        return match ($sector) {

            'fiber' =>

                $this->ecosystem([
                    'raw_materials',
                ]),

            'yarn' =>

                $this->ecosystem([
                    'raw_materials',
                    'fiber',
                ]),

            'fabric' =>

                $this->ecosystem([
                    'raw_materials',
                    'fiber',
                    'yarn',
                ]),

            'apparel' =>

                $this->ecosystem([
                    'raw_materials',
                    'fiber',
                    'yarn',
                ]),

            default =>

                $this->ecosystem([
                    'raw_materials',
                    'fiber',
                    'yarn',
                ]),
        };
    }

    /**
     * --------------------------------------------------------------------------
     * Midstream
     * --------------------------------------------------------------------------
     */
    protected function midstream(
        string $sector
    ): array {

        return match ($sector) {

            'fiber' =>

                $this->ecosystem([
                    'fiber',
                ]),

            'yarn' =>

                $this->ecosystem([
                    'yarn',
                ]),

            'fabric' =>

                $this->ecosystem([
                    'fabric',
                    'dyeing_printing_finishing',
                ]),

            'apparel' =>

                $this->ecosystem([
                    'fabric',
                    'dyeing_printing_finishing',
                ]),

            default =>

                $this->ecosystem([
                    'fiber',
                    'yarn',
                    'fabric',
                ]),
        };
    }
      /**
     * --------------------------------------------------------------------------
     * Downstream
     * --------------------------------------------------------------------------
     */
    protected function downstream(
        string $sector
    ): array {

        return match ($sector) {

            'apparel' =>

                $this->ecosystem([
                    'garment',
                ]),

            default => [],
        };
    }

    /**
     * --------------------------------------------------------------------------
     * Supporting Industries
     * --------------------------------------------------------------------------
     */
    protected function supporting(): array
    {
        return $this->ecosystem([
            'machinery',
            'chemicals',
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Digital Solutions
     * --------------------------------------------------------------------------
     */
    protected function digital(): array
    {
        return $this->ecosystem([
            'digital_solutions',
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Sustainability
     * --------------------------------------------------------------------------
     */
    protected function sustainability(): array
    {
        return $this->ecosystem([
            'sustainability',
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Ecosystem Helper
     * --------------------------------------------------------------------------
     */
    protected function ecosystem(
        array $ecosystems
    ): array {

        return collect(
            config(
                'masterdata.industry_segments'
            )
        )

        ->whereIn(
            'ecosystem',
            $ecosystems
        )

        ->sortBy(
            'priority'
        )

        ->map(function ($item) {

            return [

                'id' =>
                    $item['id'],

                'label' =>
                    $item['label'],

                'description' =>
                    $item['description'],

            ];
        })

        ->values()

        ->toArray();
    }

    /**
     * --------------------------------------------------------------------------
     * AI Insight
     * --------------------------------------------------------------------------
     */
    protected function insight(
        string $sector
    ): string {

        return match ($sector) {

            'fiber' =>

                'Fiber remains the foundation of the textile industry and presents opportunities for upstream investment.',

            'yarn' =>

                'The yarn sector serves as a strategic bridge between upstream raw materials and downstream manufacturing.',

            'fabric' =>

                'Fabric manufacturing is a critical midstream activity supporting both domestic and export-oriented industries.',

            'apparel' =>

                'Apparel remains Indonesia\'s strongest export-oriented segment while maintaining dependency on imported upstream materials.',

            default =>

                'Indonesia\'s textile ecosystem continues to evolve across upstream, midstream, and downstream industries.',
        };
    }
}  