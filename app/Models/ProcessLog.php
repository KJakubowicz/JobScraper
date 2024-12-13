<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'records_processed',
    ];

    public function jobOfferVersion()
    {
        return $this->hasMany(JobOfferVersion::class);
    }
}