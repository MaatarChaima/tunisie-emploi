<?php

namespace App\Support;

/**
 * Fil d'Ariane : la hiérarchie est fixe et définie par chaque page.
 * Le lien vers la page parente conserve ses filtres (tri, recherche, page)
 * uniquement si l'on vient directement de cette page parente.
 */
class Breadcrumbs
{
    public static function parent(string $route, array $params = []): string
    {
        $base     = route($route, $params);
        $previous = url()->previous();

        return strtok($previous, '?') === $base ? $previous : $base;
    }
}
