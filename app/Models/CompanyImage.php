<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyImage extends Model
{
    use HasFactory;

    protected $table = 'company_images';

     protected $fillable = [
        'company_id',
        'image_url',
        'image_path',
        'image_type',
        'title',
        'caption',
        'sort_order',
        'is_featured',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}