<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JobOfferVersion;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = ['url'];

    public function versions()
    {
        return $this->hasMany(JobOfferVersion::class);
    }
}