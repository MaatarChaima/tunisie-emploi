<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    /**
     * Types de contrat : libellé et icône (tracé SVG 24 × 24, trait). Source unique pour les
     * cartes, les filtres et les pages de détail.
     */
    public const TYPES = [
        'full-time' => ['label' => 'Temps plein',   'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        'part-time' => ['label' => 'Temps partiel', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        'remote'    => ['label' => 'Télétravail',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ];

    /** Libellé et icône du type de cette offre (repli : document générique). */
    public function typeMeta(): array
    {
        return self::TYPES[$this->type] ?? ['label' => ucfirst((string) $this->type), 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'];
    }

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'requirements',
        'expiration_date',
        'location',
        'type',
        'education_level',
        'experience_years',
        'salary',
        'languages',
        'keywords',
        'vacancies',
        'status',
    ];

    /**
     * Offres visibles publiquement : statut « open » et date limite non dépassée.
     * Définition unique (liste publique, hero, recherches populaires, offres similaires,
     * tableau de bord admin via DashboardStats::openOffers()).
     */
    public function scopePubliclyVisible($query)
    {
        return $query->where('job_offers.status', 'open')
            ->where(fn ($q) => $q->whereNull('job_offers.expiration_date')
                                 ->orWhereDate('job_offers.expiration_date', '>=', today()));
    }

    /** Date limite dépassée (la veille ou avant). */
    public function isExpired(): bool
    {
        return $this->expiration_date !== null
            && \Illuminate\Support\Carbon::parse($this->expiration_date)->lt(today());
    }

    /** Visible par tous : ouverte et date limite non dépassée (même règle que scopePubliclyVisible). */
    public function isPubliclyVisible(): bool
    {
        return $this->status === 'open' && !$this->isExpired();
    }

    /**
     * Qui peut ouvrir la page de l'offre :
     * - offre visible : tout le monde ;
     * - admin et entreprise propriétaire : toujours ;
     * - offre archivée, clôturée ou expirée : aussi le candidat qui y a postulé (lecture seule) ;
     * - offre en attente ou refusée (jamais publiée) : personne d'autre.
     */
    public function isViewableBy(?User $user): bool
    {
        if ($this->isPubliclyVisible()) {
            return true;
        }
        if (!$user) {
            return false;
        }
        if ($user->isAdmin() || $user->id === $this->user_id) {
            return true;
        }
        if (in_array($this->status, ['pending_validation', 'refused'], true)) {
            return false;
        }

        return $user->isCandidate()
            && $this->applications()->where('user_id', $user->id)->exists();
    }

    /** Badge de la page détail quand l'offre n'est plus ouverte aux candidatures. */
    public function closedBadge(): ?string
    {
        if (in_array($this->status, ['archived', 'closed'], true)) {
            return 'Recrutement clôturé';
        }

        return $this->isExpired() ? 'Offre expirée' : null;
    }

    /** Une candidature n'est possible que sur une offre ouverte dont la date limite n'est pas dépassée. */
    public function acceptsApplications(): bool
    {
        return $this->status === 'open' && !$this->isExpired();
    }

    /** Message affiché quand l'offre n'accepte plus de candidatures. */
    public function closedMessage(): string
    {
        return $this->isExpired()
            ? 'Cette offre a expiré le ' . \Illuminate\Support\Carbon::parse($this->expiration_date)->translatedFormat('j F Y') . ' : les candidatures sont closes.'
            : 'Cette offre n’accepte plus de candidatures.';
    }

    /** Salaire au format unique de l'application : « 2 500 TND brut / mois ». */
    public function salaryLabel(): ?string
    {
        return $this->salary
            ? number_format((float) $this->salary, 0, ',', "\u{202F}") . "\u{00A0}TND brut / mois"
            : null;
    }

    /** « 1 poste » / « 3 postes ». */
    public function vacanciesLabel(): string
    {
        $n = (int) $this->vacancies;

        return $n . "\u{00A0}poste" . ($n > 1 ? 's' : '');
    }

    /** « Date limite : 30 novembre 2026 » ou « Expirée le 12 avril 2026 ». */
    public function deadlineLabel(): ?string
    {
        if (!$this->expiration_date) {
            return null;
        }
        $date = \Illuminate\Support\Carbon::parse($this->expiration_date);

        return ($this->isExpired() ? 'Expirée le ' : 'Date limite : ') . $date->translatedFormat('j F Y');
    }

    /** Libellé de statut affiché à l'entreprise propriétaire et à l'admin (null si l'offre est visible). */
    public function statusLabel(): ?string
    {
        return match (true) {
            $this->status === 'pending_validation' => 'En attente',
            $this->status === 'refused'            => 'Refusée',
            $this->status === 'archived'           => 'Archivée',
            $this->status === 'closed'             => 'Clôturée',
            $this->status === 'open' && $this->isExpired() => 'Expirée',
            default                                => null,
        };
    }

    public function offerCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
