<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'job_offer_id',
        'status',
        'message',
        'cv_path',
        'cover_letter_path',
        'company_comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }
}
