<?php
namespace App\Models; 
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Company extends Model
{
protected $appends = ['verification_status'];
protected $fillable = [
    'nama_perusahaan', 'sektor', 'wilayah', 'alamat_lengkap', 'city', 
    'telepon', 'email_web', 'pimpinan', 'pimpinan_2', 'tenaga_kerja', 
    'pasar_ekspor', 'produk', 'category', 'membership_type', 
    'nomor_anggota', 'photo_url', 'photo_pimpinan', 'photo_pimpinan_2', 'catalog_url','last_verified_at', 'status_verifikasi', 'stock_ready_caption',
    'stock_qty',
    'stock_unit',
    'price'
];
 // GABUNGKAN SEMUA CASTS DI SINI (Hanya boleh ada satu blok ini)
    protected $casts = [
        'last_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

public function getVerificationStatusAttribute()
{
    // 1. Jika belum pernah verifikasi sama sekali
    if (is_null($this->last_verified_at)) {
        return 'Legacy Data';
    }

    // 2. Jika verifikasi terakhir sudah lebih dari 12 bulan (1 tahun)
    if ($this->last_verified_at->diffInMonths(now()) > 12) {
        return 'Needs Update';
    }

    // 3. Jika data masih segar
    return 'Verified';
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

    // Products
    public function products()
    {
        return $this->hasMany(CompanyProduct::class);
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
}