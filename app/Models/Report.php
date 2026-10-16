<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'reported_user_id',
        'reason',
        'treated_at',
        'treated_by',
    ];

    protected function casts(): array
    {
        return ['treated_at' => 'datetime'];
    }

    public function isTreated(): bool
    {
        return $this->treated_at !== null;
    }

    public function scopeUntreated($query)
    {
        return $query->whereNull('reports.treated_at');
    }

    public function treatedBy()
    {
        return $this->belongsTo(User::class, 'treated_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}
