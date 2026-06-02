<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfqFile extends Model
{
    protected $fillable = [

        'rfq_id',

        'file_name',
        'file_path',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }
}