<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyUpdate extends Model
{
protected $fillable = [
    'company_id', 
    'user_id', 
    'proposed_data', 
    'status', 
    'admin_note'
];

 protected $casts = [
        'proposed_data' => 'array',
    ];
    //// Relasi agar Admin tahu perusahaan mana yang diedit
public function company() {
    return $this->belongsTo(Company::class);
}

public function user() {
    return $this->belongsTo(User::class);
}
}