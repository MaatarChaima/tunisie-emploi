<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'bio',
        'website',
        'birth_date',
        'city',
        'domain',
        'education_level',
        'experience_years',
        'linkedin_url',
        'cv_path',
        'sector',
        'address',
        'company_size',
        'tax_id',
        'logo_path',
        'is_validated',
        'is_blocked',
        'reports_seen_at',
        'users_seen_at',
        'offers_seen_at',
        'my_offers_seen_at',
        'my_apps_seen_at',
    ];

    const ROLE_CANDIDATE = 'candidate';
    const ROLE_COMPANY = 'company';
    const ROLE_ADMIN = 'admin';

    public function isCandidate()
    {
        return $this->role === self::ROLE_CANDIDATE;
    }

    public function isCompany()
    {
        return $this->role === self::ROLE_COMPANY;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isValidated()
    {
        return $this->is_validated;
    }

    public function isBlocked()
    {
        return $this->is_blocked;
    }

    public function receivedReports()
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    // Relationships for Companies
    public function jobOffers()
    {
        return $this->hasMany(JobOffer::class);
    }

    // Relationships for Candidates
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }

    public function submittedReports()
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo_path) {
            return null;
        }

        // Logos de démonstration du dépôt (public/images/…) ou logo téléversé (storage)
        return str_starts_with($this->logo_path, 'images/') ? asset($this->logo_path) : asset('storage/' . $this->logo_path);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Initiales de l'avatar (2 lettres) : prénom + nom pour une personne,
     * deux premiers mots pour une entreprise (même règle que <x-company-logo>).
     */
    public function getInitialsAttribute(): string
    {
        $words = array_values(array_filter(preg_split('/\s+/', trim(preg_replace('/[^\p{L}\p{N}\s]/u', ' ', (string) $this->name)))));
        if (!$words) {
            return '?';
        }
        // « Nour Ben Youssef » → NB : la particule fait partie du nom
        return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1] ?? '', 0, 1));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'reports_seen_at'    => 'datetime',
            'users_seen_at'      => 'datetime',
            'offers_seen_at'     => 'datetime',
            'my_offers_seen_at'  => 'datetime',
            'my_apps_seen_at'    => 'datetime',
        ];
    }
}
