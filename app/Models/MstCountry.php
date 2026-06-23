<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstCountry extends Model
{
    protected $table = 'mst_countries';

    protected $fillable = [
        'country_code',
        'country_name',
        'region',
        'sub_region',
        'flag_emoji',
        'is_active',
    ];
}