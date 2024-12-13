<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOfferVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_offer_id',
        'job_title',
        'description',
        'work_mode',
        'location',
        'work_type',
        'process_log_id',
        'is_active',
    ];

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }

    public function processLogs()
    {
        return $this->belongsTo(ProcessLog::class);
    }
}