<?php

declare(strict_types=1);

namespace App\Services\Company\Identity;

use App\Models\Company;
use App\Models\CompanyIdentity;
use App\Models\CompanyIdentityMediaAsset;
use App\Models\CompanyIdentitySource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CompanyIdentityMediaAssetService
{
    /**
     * Resolve the canonical CompanyIdentity from a legacy Company.
     */
    public function resolveIdentity(Company $company): CompanyIdentity
    {
        $source = CompanyIdentitySource::query()
            ->where('company_id', $company->id)
            ->with('identity')
            ->first();

        if (!$source || !$source->identity) {
            throw new RuntimeException(
                "No canonical CompanyIdentity found for company ID {$company->id}."
            );
        }

        return $source->identity;
    }

    /**
     * Create or update canonical media assets.
     *
     * This method intentionally does NOT delete existing canonical assets.
     * A CompanyIdentity may represent multiple legacy Company records.
     */
    public function sync(
        Company $company,
        array $images
    ): void {
        if (empty($images)) {
            return;
        }

        $identity = $this->resolveIdentity($company);

        foreach ($images as $index => $image) {

            if (
                empty($image['image_url']) &&
                empty($image['image_file']) &&
                empty($image['image_path'])
            ) {
                continue;
            }

            $uploadedFile = $image['image_file'] ?? null;

            $filePath = null;
            $fileUrl = $image['image_url'] ?? null;
            $mimeType = null;
            $fileSize = null;

            /*
             * --------------------------------------------------------------
             * File upload
             * --------------------------------------------------------------
             */

            if ($uploadedFile instanceof UploadedFile) {

                $filePath = $uploadedFile->store(
                    'company-identity-media',
                    'public'
                );

                $fileUrl = Storage::disk('public')
                    ->url($filePath);

                $mimeType = $uploadedFile->getMimeType();

                $fileSize = $uploadedFile->getSize();
            }

            /*
             * --------------------------------------------------------------
             * Existing path
             * --------------------------------------------------------------
             */

            if (
                !$filePath &&
                !empty($image['image_path']) &&
                is_string($image['image_path'])
            ) {
                $filePath = $image['image_path'];
            }

            /*
             * --------------------------------------------------------------
             * Existing canonical asset
             * --------------------------------------------------------------
             */

            if (!empty($image['id'])) {

                $asset = CompanyIdentityMediaAsset::query()
                    ->where('company_identity_id', $identity->id)
                    ->where('id', $image['id'])
                    ->first();

                if ($asset) {

                    $asset->update([
                        'media_type' => $image['image_type']
                            ?? $asset->media_type,

                        'file_path' => $filePath
                            ?? $asset->file_path,

                        'disk' => 'public',

                        'file_url' => $fileUrl
                            ?? $asset->file_url,

                        'mime_type' => $mimeType
                            ?? $asset->mime_type,

                        'file_size' => $fileSize
                            ?? $asset->file_size,

                        'title' => $image['title']
                            ?? $image['caption']
                            ?? $asset->title,

                        'caption' => $image['caption']
                            ?? $asset->caption,

                        'sort_order' => $index,

                        'is_featured' => $image['is_featured']
                            ?? $asset->is_featured,
                    ]);

                    continue;
                }
            }

            /*
             * --------------------------------------------------------------
             * Create canonical asset
             * --------------------------------------------------------------
             */

            $identity->mediaAssets()->create([
                'media_type' => $image['image_type']
                    ?? 'factory',

                'file_path' => $filePath,

                'disk' => 'public',

                'file_url' => $fileUrl,

                'mime_type' => $mimeType,

                'file_size' => $fileSize,

                'title' => $image['title']
                    ?? $image['caption']
                    ?? null,

                'caption' => $image['caption']
                    ?? null,

                'sort_order' => $index,

                'is_featured' => $image['is_featured']
                    ?? false,

                'verification_status' => 'draft',

                'created_by' => auth()->id(),

                'updated_by' => auth()->id(),
            ]);
        }
    }
}