<?php

namespace App\Support;

/**
 * Niveaux d'études : valeur enregistrée en base → libellé affiché.
 * Les valeurs ne changent pas (données existantes) ; seul le dernier libellé diffère.
 */
class Education
{
    public const LEVELS = [
        'Inférieur au baccalauréat' => 'Inférieur au baccalauréat',
        'Bac'                       => 'Bac',
        'Bac+3'                     => 'Bac+3',
        'Bac+5'                     => 'Bac+5',
        'Plus que Bac +5'           => 'Supérieur à Bac+5',
    ];

    /**
     * Une icône par niveau (tracé SVG 24 × 24, trait 2 px), même taille et même couleur partout :
     * aucune notion de plus ou de moins. NEUTRAL_ICON : « Tous les niveaux » et libellé du filtre.
     */
    public const ICONS = [
        'Inférieur au baccalauréat' => 'M12 14a5 5 0 100-10 5 5 0 000 10zM8.5 13l-1.5 8 5-3 5 3-1.5-8',
        'Bac'                       => 'M7 4h11a2 2 0 012 2 2 2 0 01-2 2H7m0-4a2 2 0 00-2 2v12a2 2 0 002 2h11a2 2 0 002-2V8M7 4a2 2 0 012 2 2 2 0 01-2 2M10 12h6m-6 4h4',
        'Bac+3'                     => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
        'Bac+5'                     => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zM19.5 2.5l.6 1.3 1.4.2-1 1 .2 1.4-1.2-.7-1.2.7.2-1.4-1-1 1.4-.2z',
        'Plus que Bac +5'           => 'M9 3h6M10 3v6.5L4.4 18.8A1.5 1.5 0 005.7 21h12.6a1.5 1.5 0 001.3-2.2L14 9.5V3M7.5 15h9',
    ];

    public const NEUTRAL_ICON = 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253';

    public static function icon(?string $value): string
    {
        return self::ICONS[$value] ?? self::NEUTRAL_ICON;
    }

    public static function label(?string $value): ?string
    {
        return $value === null ? null : (self::LEVELS[$value] ?? $value);
    }

}
