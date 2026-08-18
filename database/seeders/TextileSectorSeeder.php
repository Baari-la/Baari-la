<?php

namespace Database\Seeders;

use App\Models\TextileSector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TextileSectorSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | Root
            |--------------------------------------------------------------------------
            */

            $textile = TextileSector::updateOrCreate(
                ['slug' => 'textile'],
                [
                    'name'       => 'Textile',
                    'name_en'    => 'Textile',
                    'level'      => 1,
                    'parent_id'  => null,
                    'icon'       => 'layers',
                    'is_active'  => true,
                    'sort_order' => 1,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Helper
            |--------------------------------------------------------------------------
            */

            $createChild = function (
                string $slug,
                string $name,
                string $nameEn,
                int $parentId,
                int $level = 2,
                int $sortOrder = 1
            ) {
                return TextileSector::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name'       => $name,
                        'name_en'    => $nameEn,
                        'level'      => $level,
                        'parent_id'  => $parentId,
                        'is_active'  => true,
                        'sort_order' => $sortOrder,
                    ]
                );
            };

            /*
            |--------------------------------------------------------------------------
            | FIBER
            |--------------------------------------------------------------------------
            */

            $fiber = $createChild(
                'fiber',
                'Fiber',
                'Fiber',
                $textile->id,
                2,
                1
            );

            $naturalFiber = $createChild(
                'natural-fibers',
                'Natural Fibers',
                'Natural Fibers',
                $fiber->id,
                3,
                1
            );

            foreach ([
                ['cotton', 'Cotton', 'Cotton'],
                ['wool', 'Wool', 'Wool'],
                ['silk', 'Silk', 'Silk'],
                ['flax-linen', 'Flax / Linen', 'Flax / Linen'],
                ['other-natural-fibers', 'Other Natural Fibers', 'Other Natural Fibers'],
            ] as $i => [$slug, $name, $nameEn]) {
                $createChild(
                    $slug,
                    $name,
                    $nameEn,
                    $naturalFiber->id,
                    4,
                    $i + 1
                );
            }

            $manMadeFiber = $createChild(
                'man-made-fibers',
                'Man-Made Fibers',
                'Man-Made Fibers',
                $fiber->id,
                3,
                2
            );

            foreach ([
                ['polyester', 'Polyester', 'Polyester'],
                ['polyamide-nylon', 'Polyamide / Nylon', 'Polyamide / Nylon'],
                ['acrylic', 'Acrylic', 'Acrylic'],
                ['viscose-rayon', 'Viscose / Rayon', 'Viscose / Rayon'],
                ['modal', 'Modal', 'Modal'],
                ['lyocell', 'Lyocell', 'Lyocell'],
                ['other-man-made-fibers', 'Other Man-Made Fibers', 'Other Man-Made Fibers'],
            ] as $i => [$slug, $name, $nameEn]) {
                $createChild(
                    $slug,
                    $name,
                    $nameEn,
                    $manMadeFiber->id,
                    4,
                    $i + 1
                );
            }

            /*
            |--------------------------------------------------------------------------
            | YARN
            |--------------------------------------------------------------------------
            */

            $yarn = $createChild(
                'yarn',
                'Yarn',
                'Yarn',
                $textile->id,
                2,
                2
            );

            foreach ([
                ['spun-yarn', 'Spun Yarn', 'Spun Yarn'],
                ['filament-yarn', 'Filament Yarn', 'Filament Yarn'],
                ['cotton-yarn', 'Cotton Yarn', 'Cotton Yarn'],
                ['polyester-yarn', 'Polyester Yarn', 'Polyester Yarn'],
                ['polyamide-nylon-yarn', 'Polyamide / Nylon Yarn', 'Polyamide / Nylon Yarn'],
                ['viscose-rayon-yarn', 'Viscose / Rayon Yarn', 'Viscose / Rayon Yarn'],
                ['other-yarn', 'Other Yarn', 'Other Yarn'],
            ] as $i => [$slug, $name, $nameEn]) {
                $createChild(
                    $slug,
                    $name,
                    $nameEn,
                    $yarn->id,
                    3,
                    $i + 1
                );
            }

            /*
            |--------------------------------------------------------------------------
            | FABRIC
            |--------------------------------------------------------------------------
            */

            $fabric = $createChild(
                'fabric',
                'Fabric',
                'Fabric',
                $textile->id,
                2,
                3
            );

            foreach ([
                ['woven', 'Woven', 'Woven'],
                ['knitted', 'Knitted', 'Knitted'],
                ['denim', 'Denim', 'Denim'],
                ['nonwoven', 'Nonwoven', 'Nonwoven'],
                ['coated-laminated', 'Coated / Laminated', 'Coated / Laminated'],
                ['technical-functional-fabric', 'Technical / Functional Fabric', 'Technical / Functional Fabric'],
            ] as $i => [$slug, $name, $nameEn]) {
                $createChild(
                    $slug,
                    $name,
                    $nameEn,
                    $fabric->id,
                    3,
                    $i + 1
                );
            }

            /*
            |--------------------------------------------------------------------------
            | TECHNICAL TEXTILE
            |--------------------------------------------------------------------------
            */

            $technical = $createChild(
                'technical-textile',
                'Technical Textile',
                'Technical Textile',
                $textile->id,
                2,
                4
            );

            foreach ([
                ['industrial-textile', 'Industrial Textile', 'Industrial Textile'],
                ['medical-hygiene-textile', 'Medical / Hygiene Textile', 'Medical / Hygiene Textile'],
                ['automotive-textile', 'Automotive Textile', 'Automotive Textile'],
                ['filtration-textile', 'Filtration Textile', 'Filtration Textile'],
                ['other-technical-textile', 'Other Technical Textile', 'Other Technical Textile'],
            ] as $i => [$slug, $name, $nameEn]) {
                $createChild(
                    $slug,
                    $name,
                    $nameEn,
                    $technical->id,
                    3,
                    $i + 1
                );
            }

            /*
            |--------------------------------------------------------------------------
            | APPAREL
            |--------------------------------------------------------------------------
            */

            $apparel = $createChild(
                'apparel',
                'Apparel',
                'Apparel',
                $textile->id,
                2,
                5
            );

            foreach ([
                ['t-shirts', 'T-Shirts', 'T-Shirts'],
                ['shirts-blouses', 'Shirts / Blouses', 'Shirts / Blouses'],
                ['trousers-pants', 'Trousers / Pants', 'Trousers / Pants'],
                ['jackets-outerwear', 'Jackets / Outerwear', 'Jackets / Outerwear'],
                ['underwear', 'Underwear', 'Underwear'],
                ['sportswear', 'Sportswear', 'Sportswear'],
                ['childrens-wear', "Children's Wear", "Children's Wear"],
                ['other-apparel', 'Other Apparel', 'Other Apparel'],
            ] as $i => [$slug, $name, $nameEn]) {
                $createChild(
                    $slug,
                    $name,
                    $nameEn,
                    $apparel->id,
                    3,
                    $i + 1
                );
            }

            /*
            |--------------------------------------------------------------------------
            | MADE-UP TEXTILE
            |--------------------------------------------------------------------------
            */

            $madeUp = $createChild(
                'made-up-textile',
                'Made-up Textile',
                'Made-up Textile',
                $textile->id,
                2,
                6
            );

            foreach ([
                ['home-textile', 'Home Textile', 'Home Textile'],
                ['bed-linen', 'Bed Linen', 'Bed Linen'],
                ['towels', 'Towels', 'Towels'],
                ['curtains', 'Curtains', 'Curtains'],
                ['bags-textile-articles', 'Bags / Textile Articles', 'Bags / Textile Articles'],
                ['other-made-up-textile', 'Other Made-up Textile', 'Other Made-up Textile'],
            ] as $i => [$slug, $name, $nameEn]) {
                $createChild(
                    $slug,
                    $name,
                    $nameEn,
                    $madeUp->id,
                    3,
                    $i + 1
                );
            }
        });
    }
}