<?php

namespace App\Support;

/**
 * Libellé de l'expérience, identique partout :
 * 0 → « Débutant accepté » (offre) ou « Débutant » (candidat), 1 → « 1 an », n → « n ans ».
 */
class Experience
{
    public static function years(int $n): string
    {
        return $n . ' an' . ($n > 1 ? 's' : '');
    }

    /** $for : 'offer' ou 'candidate' ; $suffix ajouté seulement après un nombre (ex. « d’expérience »). */
    public static function label(?int $n, string $for = 'offer', string $suffix = ''): ?string
    {
        if ($n === null) {
            return null;
        }
        if ($n === 0) {
            return $for === 'offer' ? 'Débutant accepté' : 'Débutant';
        }

        return self::years($n) . ($suffix !== '' ? ' ' . $suffix : '');
    }
}
