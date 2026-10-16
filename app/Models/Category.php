<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'image_url'];

    /**
     * Icône de chaque catégorie (tracé SVG 24 × 24, trait 2 px), par slug.
     * Une catégorie créée ensuite reçoit l'icône par défaut (mallette).
     */
    public const ICONS = [
        'informatique-technologie' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'finance-comptabilite'     => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        'hotellerie-restauration'  => 'M7 3v7m-2.5-7v4.5a2.5 2.5 0 005 0V3M7 10v11m10 0V3c-1.657 0-3 2.686-3 6s1.343 5 3 5',
        'education-enseignement'   => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
        'ingenierie-industrie'     => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'marketing-communication'  => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
        'ressources-humaines'      => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        'sante-medical'            => 'M5 3v6a4 4 0 008 0V3M5 3H4m1 0h1m7 0h-1m1 0h1M9 13v2a5 5 0 0010 0v-2m0 0a2 2 0 100-4 2 2 0 000 4z',
        'transport-logistique'     => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1',
        'vente-commerce'           => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
    ];

    public const DEFAULT_ICON = 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z';

    public function iconPath(): string
    {
        return self::ICONS[$this->slug] ?? self::DEFAULT_ICON;
    }

    /**
     * URL affichable de l'image : adresse externe telle quelle, sinon chemin public
     * résolu avec asset() (fonctionne aussi quand l'application est dans un sous-dossier).
     */
    public function getImageSrcAttribute(): ?string
    {
        if (!$this->image_url) {
            return null;
        }

        return preg_match('#^https?://#', $this->image_url)
            ? $this->image_url
            : asset(ltrim($this->image_url, '/'));
    }

    /**
     * Variante allégée d'une image locale (WebP « web » 1200 px ou « thumbs » 300 px,
     * générées dans public/images/categories/<variante>/) ; à défaut, l'image d'origine.
     */
    public function imageVariant(string $variant): ?string
    {
        if (!$this->image_url || preg_match('#^https?://#', $this->image_url)) {
            return $this->image_src;
        }

        $path = ltrim($this->image_url, '/');
        $candidate = dirname($path) . '/' . $variant . '/' . pathinfo($path, PATHINFO_FILENAME) . '.webp';

        return is_file(public_path($candidate)) ? asset($candidate) : $this->image_src;
    }

    public function getThumbSrcAttribute(): ?string
    {
        return $this->imageVariant('thumbs');
    }

    public function getBannerSrcAttribute(): ?string
    {
        return $this->imageVariant('web');
    }

    public function jobOffers()
    {
        return $this->hasMany(JobOffer::class);
    }
}
