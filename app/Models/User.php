<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/*
|--------------------------------------------------------------------------
| Models
|--------------------------------------------------------------------------
*/

use App\Models\Company;
use App\Models\CompanyClaim;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDispute;
use App\Models\SupplierReview;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'name',
        'email',
        'password',

        /*
        |--------------------------------------------------------------------------
        | Membership
        |--------------------------------------------------------------------------
        */

        'member_number',
        'role',
        'access_level',
        'member_type',
        'is_premium',

        /*
        |--------------------------------------------------------------------------
        | Company
        |--------------------------------------------------------------------------
        */

        'company_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [

        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',
            'password'          => 'hashed',

            /*
            |--------------------------------------------------------------------------
            | Membership
            |--------------------------------------------------------------------------
            */

            'is_premium'        => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Purchase Orders
    |--------------------------------------------------------------------------
    */

    public function purchaseOrders()
    {
        return $this->hasMany(
            PurchaseOrder::class,
            'buyer_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Purchase Order Disputes
    |--------------------------------------------------------------------------
    */

    public function purchaseOrderDisputes()
    {
        return $this->hasMany(
            PurchaseOrderDispute::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Supplier Reviews
    |--------------------------------------------------------------------------
    */

    public function supplierReviews()
    {
        return $this->hasMany(
            SupplierReview::class,
            'buyer_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Company Claims
    |--------------------------------------------------------------------------
    */

    public function companyClaims()
    {
        return $this->hasMany(
            CompanyClaim::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Company
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(
            Company::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Membership Helpers
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isPremium(): bool
    {
        return (bool) $this->is_premium;
    }

    public function isApiMember(): bool
    {
        return $this->member_type === 'api_member';
    }

    public function isExecutive(): bool
    {
        return $this->member_type === 'executive';
    }

    public function isFoundingPartner(): bool
    {
        return $this->member_type === 'founding_partner';
    }

    /*
    |--------------------------------------------------------------------------
    | Access Helpers
    |--------------------------------------------------------------------------
    */

    public function hasPremiumAccess(): bool
    {
        return in_array(
            $this->access_level,
            [
                'premium',
            ]
        );
    }

    public function hasApiAccess(): bool
    {
        return in_array(
            $this->access_level,
            [
                'api',
                'premium',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Display Helpers
    |--------------------------------------------------------------------------
    */

    public function getDisplayRoleAttribute(): string
    {
        return match ($this->role) {

            'super_admin' => 'Super Admin',
            'admin'       => 'Administrator',

            default       => 'User',
        };
    }

    public function getDisplayMembershipAttribute(): string
    {
        return match ($this->member_type) {

            'premium'          => 'Premium Member',
            'api_member'       => 'API Member',
            'executive'        => 'Executive Member',
            'founding_partner' => 'Founding Partner',

            default            => 'Free Member',
        };
    }
}