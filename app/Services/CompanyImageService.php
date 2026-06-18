<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyImage;
use Illuminate\Http\UploadedFile;

class CompanyImageService
{
    public static function syncImages(
        Company $company,
        array $images
    ): void {

        /*
        |--------------------------------------------------------------------------
        | EMPTY PAYLOAD
        |--------------------------------------------------------------------------
        */

        if (empty($images)) {
            return;
        }

        $processedIds = [];

        foreach ($images as $index => $image) {

            /*
            |--------------------------------------------------------------------------
            | SKIP EMPTY ROW
            |--------------------------------------------------------------------------
            */

            if (
                empty($image['image_url']) &&
                empty($image['image_file'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | FILE UPLOAD
            |--------------------------------------------------------------------------
            */

            $imagePath = null;

            if (
                isset($image['image_file']) &&
                $image['image_file'] instanceof UploadedFile
            ) {

                $imagePath = $image['image_file']->store(
                    'company-images',
                    'public'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | KEEP EXISTING IMAGE
            |--------------------------------------------------------------------------
            */

            if (
                !$imagePath &&
                !empty($image['image_path']) &&
                is_string($image['image_path'])
            ) {

                $imagePath = $image['image_path'];
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE EXISTING
            |--------------------------------------------------------------------------
            */

            if (!empty($image['id'])) {

                $record = CompanyImage::where(
                    'company_id',
                    $company->id
                )
                ->where(
                    'id',
                    $image['id']
                )
                ->first();

                if ($record) {

                    $record->update([

                        'image_url' =>
                            $image['image_url'] ?? null,

                        'image_path' =>
                            $imagePath,

                        'image_type' =>
                            $image['image_type'] ?? 'factory',

                        'caption' =>
                            $image['caption'] ?? null,

                        'title' =>
                            $image['title']
                                ?? $image['caption']
                                ?? null,

                        'sort_order' =>
                            $index,

                        'is_featured' =>
                            $image['is_featured'] ?? false,
                    ]);

                    $processedIds[] = $record->id;

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW
            |--------------------------------------------------------------------------
            */

            $newImage = $company->images()->create([

                'image_url' =>
                    $image['image_url'] ?? null,

                'image_path' =>
                    $imagePath,

                'image_type' =>
                    $image['image_type'] ?? 'factory',

                'caption' =>
                    $image['caption'] ?? null,

                'title' =>
                    $image['title']
                        ?? $image['caption']
                        ?? null,

                'sort_order' =>
                    $index,

                'is_featured' =>
                    $image['is_featured'] ?? false,
            ]);

            $processedIds[] = $newImage->id;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED RECORDS
        |--------------------------------------------------------------------------
        */

        if (!empty($processedIds)) {

            $company->images()
                ->whereNotIn(
                    'id',
                    $processedIds
                )
                ->delete();
        }
    }
}