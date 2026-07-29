<?php

declare(strict_types=1);

namespace App\Services\Company\Intelligence;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Company Profile Service
 * ==========================================================================
 *
 * Business Purpose
 * --------------------------------------------------------------------------
 * Provide standardized company profile intelligence used across
 * the DIGESTEX Global Textile Intelligence Ecosystem.
 *
 * Company Profile is the single source of truth (SSOT)
 * describing the identity, business profile and core
 * information of a company.
 *
 * Future implementation will retrieve data from Company model
 * and related profile tables.
 *
 * This service NEVER:
 *
 * • Calculates readiness score
 * • Performs matching
 * • Generates AI recommendation
 *
 * Used by:
 *
 * - Company Intelligence
 * - Trade Intelligence
 * - Matching Engine
 * - Business Opportunity
 * - Executive Dashboard
 */
class CompanyProfileService
{
    /**
     * --------------------------------------------------------------------------
     * Basic Identity
     * --------------------------------------------------------------------------
     */
    public function identity(Company $company): array
    {
        return [

            'company_id' => $company->id,

            'company_name' => $company->nama_perusahaan,

            'slug' => $company->slug,

            'membership_type' => $company->membership_type,

            'verification_status' => $company->status_verifikasi,

            'last_verified_at' => $company->last_verified_at,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Business Information
     * --------------------------------------------------------------------------
     */
    public function business(Company $company): array
    {
        return [

            'sector' => $company->sektor,

            'category' => $company->category,

            'products' => $company->produk,

            'export_market' => $company->pasar_ekspor,

            'employees' => $company->tenaga_kerja,

            'director' => $company->pimpinan,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Contact Information
     * --------------------------------------------------------------------------
     */
    public function contact(Company $company): array
    {
        return [

            'phone' => $company->telepon,

            'email' => $company->email_web,

            'address' => $company->alamat_lengkap,

            'city' => $company->city,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Geographic Information
     * --------------------------------------------------------------------------
     */
    public function location(Company $company): array
    {
        return [

            'city' => $company->city,

            'province' => $company->wilayah,

            'country' => 'Indonesia',

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Membership
     * --------------------------------------------------------------------------
     */
    public function membership(Company $company): array
    {
        return [

            'membership_type' => $company->membership_type,

            'member_number' => $company->nomor_anggota,

            'verified' => $company->status_verifikasi,

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    public function summary(Company $company): array
    {
        return [

            'identity' => $this->identity($company),

            'business' => $this->business($company),

            'contact' => $this->contact($company),

            'location' => $this->location($company),

            'membership' => $this->membership($company),

        ];
    }

 /**
 * --------------------------------------------------------------------------
 * 01 — Identity Intelligence
 * --------------------------------------------------------------------------
 */
public function identityIntelligence(Company $company): array
{
    return [
        'company_id' => $company->id,
        'company_name' => $company->nama_perusahaan,
        'slug' => $company->slug,

        'country_code' => $company->country_code,
        'country_name' => $company->country_name,

        'sector' => $company->sektor,
        'category' => $company->category,

        'director' => $company->pimpinan,
        'employees' => $company->tenaga_kerja,
        'established_year' => $company->tahun_berdiri,

        'membership_type' => $company->membership_type,
        'member_number' => $company->nomor_anggota,

        'verification_status' => $company->status_verifikasi,
        'last_verified_at' => $company->last_verified_at,
        'last_updated_at' => $company->last_updated_at,
        'data_source' => $company->data_source,
    ];
}

/**
 * --------------------------------------------------------------------------
 * 02 — Facilities Intelligence
 * --------------------------------------------------------------------------
 */
public function facilities(Company $company): array
{
    return $company->locations
        ->map(fn ($location) => [
            'id' => $location->id,

            'name' => $location->location_name,
            'type' => $location->location_type,

            'country_code' => $location->country_code,
            'country_name' => $location->country_name,

            'province' => $location->province_name,
            'city' => $location->city_name,
            'address' => $location->address,

            'contact_person' => $location->contact_person,
            'phone' => $location->phone,
            'email' => $location->email,

            'is_primary' => (bool) $location->is_primary,
        ])
        ->values()
        ->all();
}

/**
 * --------------------------------------------------------------------------
 * 03 — Product Intelligence
 * --------------------------------------------------------------------------
 */
public function products(Company $company): array
{
    return $company->products
        ->map(fn ($product) => [
            'id' => $product->id,

            'name' => $product->product_name,
            'name_en' => $product->product_name_en,

            'hs_code' => $product->hs_code,
            'category' => $product->category,
            'application' => $product->application,
            'description' => $product->description,

            'is_primary' => (bool) $product->is_primary,
            'status' => $product->status,
        ])
        ->values()
        ->all();
}

/**
 * --------------------------------------------------------------------------
 * 04 — Production Capacity Intelligence
 * --------------------------------------------------------------------------
 */
public function capacities(Company $company): array
{
    return $company->capacities
        ->map(fn ($capacity) => [
            'id' => $capacity->id,

            'type' => $capacity->capacity_type,
            'item_name' => $capacity->item_name,

           'value' => $capacity->capacity_value !== null
                ? (float) $capacity->capacity_value
                : null,

            'unit' => $capacity->capacity_unit,

            'category' => $capacity->capacity_category,
            'shift_info' => $capacity->shift_info,

            'machine_count' => $capacity->machine_count !== null
                ? (int) $capacity->machine_count
                : null,

            'notes' => $capacity->notes,
        ])
        ->values()
        ->all();
}

/**
 * --------------------------------------------------------------------------
 * 05 — Machinery Intelligence
 * --------------------------------------------------------------------------
 */
public function machinery(Company $company): array
{
    return $company->machines
        ->map(fn ($machine) => [
            'id' => $machine->id,

            'category' => $machine->machine_category,
            'type' => $machine->machine_type,

            'brand' => $machine->machine_brand,
            'model' => $machine->machine_model,
            
            'quantity' => $machine->quantity !== null
                ? (int) $machine->quantity
                : null,

            'year_installed' => $machine->year_installed !== null
                ? (int) $machine->year_installed
                : null,

            'country_origin' => $machine->country_origin,

            'production_capacity' => $machine->production_capacity !== null
                ? (float) $machine->production_capacity
                : null,

            'capacity_unit' => $machine->capacity_unit,

            'energy_consumption' => $machine->energy_consumption !== null
                ? (float) $machine->energy_consumption
                : null,

            'energy_unit' => $machine->energy_unit,

            'working_width' => $machine->working_width,
            'gauge_specification' => $machine->gauge_specification,

            'condition' => $machine->machine_condition,
            'automation_level' => $machine->automation_level,

            'is_active' => (bool) $machine->is_active,

            'notes' => $machine->notes,
        ])
        ->values()
        ->all();
}

/**
 * --------------------------------------------------------------------------
 * 06 — Commercial Intelligence
 * --------------------------------------------------------------------------
 */
public function commercial(Company $company): array
{
    return [

        'moqs' => $company->moqs
            ->map(fn ($moq) => [
                'id' => $moq->id,

                'product_name' => $moq->product_name,
                'minimum_quantity' => $moq->minimum_quantity,
                'unit' => $moq->unit,

                'notes' => $moq->notes,
            ])
            ->values()
            ->all(),

        'lead_times' => $company->leadTimes
            ->map(fn ($leadTime) => [
                'id' => $leadTime->id,

                'type' => $leadTime->lead_time_type,
                'days' => $leadTime->days,

                'notes' => $leadTime->notes,
            ])
            ->values()
            ->all(),
    ];
}

/**
 * --------------------------------------------------------------------------
 * 07 — Market Intelligence
 * --------------------------------------------------------------------------
 */
public function markets(Company $company): array
{
    return $company->markets
        ->map(fn ($market) => [
            'id' => $market->id,

            'country_name' => $market->country_name,
            'market_type' => $market->market_type,
        ])
        ->values()
        ->all();
}

/**
 * --------------------------------------------------------------------------
 * 08 — Compliance Intelligence
 * --------------------------------------------------------------------------
 */
public function compliance(Company $company): array
{
    return $company->certifications
        ->map(fn ($certification) => [
            'id' => $certification->id,

            'name' => $certification->certification_name,
            'category' => $certification->category,

            'code' => $certification->certification_code,
            'issuer' => $certification->issuer,
            'certificate_number' => $certification->certificate_number,

            'description' => $certification->description,

            'certificate_file' => $certification->certificate_file,
            'logo_url' => $certification->logo_url,
            
            'issued_at' => $certification->issued_at?->toDateString(),
            'valid_until' => $certification->valid_until?->toDateString(),

            'is_verified' => (bool) $certification->is_verified,
            'is_featured' => (bool) $certification->is_featured,

            'status' => $certification->status,
            'sort_order' => $certification->sort_order,
        ])
        ->values()
        ->all();
}

/**
 * --------------------------------------------------------------------------
 * 09 — Contacts & Digital Presence Intelligence
 * --------------------------------------------------------------------------
 */
public function connectivity(Company $company): array
{
    return [

        'contacts' => $company->contacts
            ->map(fn ($contact) => [
                'id' => $contact->id,

                'name' => $contact->contact_name,
                'position' => $contact->position,

                'phone' => $contact->phone,
                'whatsapp' => $contact->whatsapp,
                'email' => $contact->email,

                'photo_url' => $contact->photo_url,

                'is_primary' => (bool) $contact->is_primary,
            ])
            ->values()
            ->all(),

        'links' => $company->links
            ->map(fn ($link) => [
                'id' => $link->id,

                'type' => $link->link_type,
                'url' => $link->url,
            ])
            ->values()
            ->all(),
    ];
}

/**
 * --------------------------------------------------------------------------
 * 10 — Media Intelligence
 * --------------------------------------------------------------------------
 */
public function media(Company $company): array
{
    return $company->images
        ->map(fn ($image) => [
            'id' => $image->id,

            'image_url' => $image->image_url,
            'image_path' => $image->image_path,

            'type' => $image->image_type,

            'title' => $image->title,
            'caption' => $image->caption,

            'sort_order' => $image->sort_order,
            'is_featured' => (bool) $image->is_featured,
        ])
        ->values()
        ->all();
}

/**
 * --------------------------------------------------------------------------
 * Digital Company Passport
 * --------------------------------------------------------------------------
 *
 * Canonical company intelligence representation.
 *
 * This method only structures verified company information.
 * Scoring, matching and AI analysis belong to separate services.
 */
public function passport(Company $company): array
{
    $company->loadMissing([
        'locations',
        'products',
        'capacities',
        'machines',
        'moqs',
        'leadTimes',
        'markets',
        'certifications',
        'contacts',
        'links',
        'images',
    ]);

    return [

        '01_identity' =>
            $this->identityIntelligence($company),

        '02_facilities' =>
            $this->facilities($company),

        '03_products' =>
            $this->products($company),

        '04_capacity' =>
            $this->capacities($company),

        '05_machinery' =>
            $this->machinery($company),

        '06_commercial' =>
            $this->commercial($company),

        '07_markets' =>
            $this->markets($company),

        '08_compliance' =>
            $this->compliance($company),

        '09_contacts' =>
            $this->connectivity($company),

        '10_media' =>
            $this->media($company),
    ];
}

public function all(Company $company): array
{
    return [

        'passport' =>
            $this->passport($company),

        'summary' =>
            $this->summary($company),

        'statistics' =>
            $this->statistics($company),

    ];
}
    /**
     * --------------------------------------------------------------------------
     * Profile Statistics
     * --------------------------------------------------------------------------
     */
    public function statistics(Company $company): array
    {
        return [

            'framework' => 'Company Profile Intelligence',

            'company_id' => $company->id,

            'generated_at' => now()->toDateTimeString(),

        ];
    }

   
    
}