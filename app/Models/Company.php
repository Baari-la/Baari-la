<?php
namespace App\Models; 
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\SupplierReview;
use App\Models\User;
use App\Models\CompanyUpdate;
use App\Models\MstCountry;
use App\Models\CompanyIdentity;

class Company extends Model
{
protected $appends = [
    'verification_status',
    'data_source_label',
    'data_source_badge',
    'is_claimed',
];

protected $fillable = [
    'nama_perusahaan', 'sektor', 'wilayah', 'alamat_lengkap', 'country_code',
'country_name', 'city', 
    'telepon', 'email_web', 'pimpinan', 'pimpinan_2', 'tenaga_kerja', 
    'pasar_ekspor', 'produk', 'category', 'membership_type', 
    'nomor_anggota', 'photo_url', 'photo_pimpinan', 'photo_pimpinan_2', 'catalog_url','last_verified_at', 'status_verifikasi', 'stock_ready_caption',
    'stock_qty','company_role','data_source', 'company_identity_id', 'claimed_by_user_id', 'last_updated_at',
    'stock_unit', 'price'
];
 // GABUNGKAN SEMUA CASTS DI SINI (Hanya boleh ada satu blok ini)
    protected $casts = [
        'last_verified_at' => 'datetime',
        'last_updated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

public function country()
{
    return $this->belongsTo(
        MstCountry::class,
        'country_code',
        'country_code'
    );
}

public function claims()
{
    return $this->hasMany(
        CompanyClaim::class
    );
}

public function pendingClaims()
{
    return $this->hasMany(
        CompanyClaim::class
    )->where(
        'status',
        'pending'
    );
}

public function approvedClaims()
{
    return $this->hasMany(
        CompanyClaim::class
    )->where(
        'status',
        'approved'
    );
}

    
public function getIsClaimedAttribute()
{
    return !is_null(
        $this->claimed_by_user_id
    );
}

public function getDataSourceLabelAttribute()
{
    return match (
        $this->data_source
    ) {

        'company_updated' =>
            'Company Managed',

        'verified_by_admin' =>
            'Verified Profile',

        default =>
            'Legacy Directory',
    };
}

public function getDataSourceBadgeAttribute()
{
    return match (
        $this->data_source
    ) {

        'company_updated' =>
            '👤',

        'verified_by_admin' =>
            '✅',

        default =>
            '📚',
    };
}

public function isClaimed(): bool
{
    return !is_null(
        $this->claimed_by_user_id
    );
}

public function isLegacyProfile(): bool
{
    return $this->data_source ===
        'legacy_directory';
}

public function isCompanyManaged(): bool
{
    return $this->data_source ===
        'company_updated';
}

public function isVerifiedProfile(): bool
{
    return $this->data_source ===
        'verified_by_admin';
}

public function owner()
{
    return $this->belongsTo(
        User::class,
        'claimed_by_user_id'
    );
}

public function getVerificationStatusAttribute()
{
    if (
        $this->data_source ===
        'verified_by_admin'
    ) {

        if (
            $this->last_verified_at &&
            $this->last_verified_at
                ->diffInMonths(now()) > 12
        ) {

            return 'Needs Update';
        }

        return 'Verified';
    }

    if (
        $this->data_source ===
        'company_updated'
    ) {

        return 'Company Managed';
    }

    return 'Legacy Data';
}

// app/Models/Company.php
public function getIsExpiringAttribute()
{
    if (!$this->last_verified_at) return false;
    
    // Cek apakah sudah 11 bulan sejak verifikasi terakhir
    return $this->last_verified_at->diffInMonths(now()) >= 11 
           && $this->last_verified_at->diffInMonths(now()) < 12;
}

/*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */
public function locations()
{
    return $this->hasMany(
        CompanyLocation::class,
        'company_id'
    );
}

    // Products
    public function products()
    {
        return $this->hasMany(CompanyProduct::class);
    }

    public function identitySource()
{
    return $this->hasOne(
        CompanyIdentitySource::class,
        'company_id'
    );
}

/*
|--------------------------------------------------------------------------
| Canonical Company Identity
|--------------------------------------------------------------------------
|
| Links this legacy company record to its canonical identity.
|
*/

public function companyIdentity()
{
    return $this->belongsTo(
        CompanyIdentity::class,
        'company_identity_id',
        'id'
    );
}
  
/*
|--------------------------------------------------------------------------
| Company Capabilities
|--------------------------------------------------------------------------
|
| Structured business capabilities for a single company identity.
|
| One company may operate across multiple textile sectors/capabilities.
|
| Examples:
| - fiber_manufacturer
| - yarn_spinner
| - weaving_mill
| - knitting_mill
| - dyeing_finishing_mill
| - printing_mill
| - garment_manufacturer
| - textile_machinery_supplier
|
*/
public function capabilities()
{
    return $this->hasMany(
        CompanyCapability::class,
        'company_id'
    );
}


    // Export / Import Markets
    public function markets()
    {
        return $this->hasMany(CompanyMarket::class);
    }

    // Certifications
    public function certifications()
    {
        return $this->hasMany(CompanyCertification::class);
    }

    // Contacts
    public function contacts()
    {
        return $this->hasMany(CompanyContact::class);
    }

    // Links
    public function links()
    {
        return $this->hasMany(CompanyLink::class);
    }
    
    public function updates()
{
    return $this->hasMany(
        CompanyUpdate::class
    );
}

    // Images
    public function images()
    {
        return $this->hasMany(CompanyImage::class);
    }
    public function capacities()
{
    return $this->hasMany(CompanyCapacity::class);
}

public function machines()
{
    return $this->hasMany(CompanyMachine::class);
}

public function moqs()
{
    return $this->hasMany(CompanyMoq::class);
}

public function leadTimes()
{
    return $this->hasMany(CompanyLeadTime::class);
}
public function quotations()
{
    return $this->hasMany(
        Quotation::class
    );
}
public function purchaseOrders()
{
    return $this->hasMany(
        PurchaseOrder::class,
        'supplier_company_id'
    );
}
public function supplierReviews()
{
    return $this->hasMany(
        SupplierReview::class,
        'supplier_company_id'
    );
}

public function reviews()
{
    return $this->hasMany(
        SupplierReview::class,
        'supplier_company_id'
    );
}

public function industryPartner()
{
    return $this->hasOne(
        IndustryPartner::class
    );
}


public function socialCompliances()
{
    return $this->hasMany(
        CompanySocialCompliance::class
    );
}

public function environmentalCompliances()
{
    return $this->hasMany(
        CompanyEnvironmentalCompliance::class
    );
}

public function traceabilityRecords()
{
    return $this->hasMany(
        // CompanyTraceability::class
    );
}

public function audits()
{
    return $this->hasMany(
        CompanyAudit::class
    );
}

/**
 * Passport summary for dashboard cards.
 */
public function passportSummary(): array
{
    return [
        'products' => $this->products()->count(),
        'markets' => $this->markets()->count(),
        'machines' => $this->machines()->count(),
        'certifications' => $this->certifications()->count(),
        'contacts' => $this->contacts()->count(),
        'images' => $this->images()->count(),
        'capacities' => $this->capacities()->count(),
        'moqs' => $this->moqs()->count(),
        'lead_times' => $this->leadTimes()->count(),
    ];
}
public function scopeManaged($query)
{
    return $query->where(
        'data_source',
        'company_updated'
    );
}

public function scopeVerified($query)
{
    return $query->where(
        'data_source',
        'verified_by_admin'
    );
}

public function scopeClaimed($query)
{
    return $query->whereNotNull(
        'claimed_by_user_id'
    );
}
/**
     * Relations required by Digital Company Passport.
     */
    public const PASSPORT_RELATIONS = [
        'products',
        'markets',
        'machines',
        'capacities',
        'moqs',
        'leadTimes',
        'certifications',
        'contacts',
        'links',
        'images',
    ];
    public function loadPassportRelations(): self
    {
        return $this->load(self::PASSPORT_RELATIONS);
    }

    
}