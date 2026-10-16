<?php

namespace App\Services;

use App\Models\Application;
use App\Models\JobOffer;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Indicateurs du tableau de bord administrateur (lecture seule).
 * Utilisé par la vue et par l'export CSV pour garantir les mêmes chiffres.
 */
class DashboardStats
{
    public const PERIODS = [
        '7j'  => ['label' => '7 derniers jours',  'short' => '7 j'],
        '30j' => ['label' => '30 derniers jours', 'short' => '30 j'],
        '12m' => ['label' => '12 derniers mois',  'short' => '12 mois'],
    ];

    public string $period;
    public Carbon $end;
    public Carbon $start;
    public Carbon $prevStart;

    public function __construct(?string $period = null)
    {
        $this->period = array_key_exists($period, self::PERIODS) ? $period : '30j';
        $this->end    = now();
        $this->start  = match ($this->period) {
            '7j'    => now()->subDays(7),
            '12m'   => now()->subMonths(12),
            default => now()->subDays(30),
        };
        $this->prevStart = $this->start->copy()->sub($this->start->diff($this->end));
    }

    /** Offres visibles publiquement : statut « open » et date limite non dépassée. */
    public static function openOffers(): Builder
    {
        return JobOffer::query()->publiclyVisible();
    }

    /** Bloc « À traiter ». */
    public function todo(User $admin): array
    {
        return self::todoCounts();
    }

    /** Calculé une seule fois par requête (partagé avec les pastilles du menu). */
    private static function todoCounts(): array
    {
        return once(fn () => [
            'offers'    => JobOffer::where('status', 'pending_validation')->count(),
            'companies' => User::where('role', User::ROLE_COMPANY)->where('is_validated', false)->count(),
            'reports'   => Report::untreated()->count(), // signalements non traités
        ]);
    }

    /** Les 4 indicateurs, avec écart à la période précédente et mini-courbe (6 points). */
    public function tiles(): array
    {
        // Une requête par table : période courante, période précédente et 6 tranches (mini-courbes)
        $cur    = [$this->start, $this->end];
        $prev   = [$this->prevStart, $this->start];
        $acc    = ["status = 'accepted'"];
        $dec    = ["status in ('accepted', 'rejected')"];
        $slices = $this->slices();

        // 1. Offres ouvertes (stock actuel) + offres déposées sur la période
        $openNow = self::openOffers()->count();
        $o = $this->counts(JobOffer::query(), ['cur' => $cur, 'prev' => $prev] + $this->sliceColumns('s', $slices));
        $publishedCur  = $o['cur'];
        $publishedPrev = $o['prev'];

        // 2. Candidatures sur la période ; 4. taux d'acceptation des candidatures déposées sur la période
        $a = $this->counts(Application::query(), [
            'cur'      => $cur,
            'prev'     => $prev,
            'pending'  => [...$cur, "status = 'pending'"],
            'acc_cur'  => [...$cur, ...$acc],
            'dec_cur'  => [...$cur, ...$dec],
            'acc_prev' => [...$prev, ...$acc],
            'dec_prev' => [...$prev, ...$dec],
        ] + $this->sliceColumns('s', $slices) + $this->sliceColumns('acc', $slices, $acc) + $this->sliceColumns('dec', $slices, $dec));
        $appsCur    = $a['cur'];
        $appsPrev   = $a['prev'];
        $pendingCur = $a['pending'];
        [$accCur, $decCur, $accPrev, $decPrev] = [$a['acc_cur'], $a['dec_cur'], $a['acc_prev'], $a['dec_prev']];
        $rateCur  = $decCur  ? (int) round($accCur / $decCur * 100) : null;
        $ratePrev = $decPrev ? (int) round($accPrev / $decPrev * 100) : null;

        // 3. Nouveaux inscrits (hors administrateurs)
        $u = $this->counts(User::where('role', '!=', User::ROLE_ADMIN), [
            'cur'        => $cur,
            'prev'       => $prev,
            'candidates' => [...$cur, 'role = ?', User::ROLE_CANDIDATE],
            'companies'  => [...$cur, 'role = ?', User::ROLE_COMPANY],
        ] + $this->sliceColumns('s', $slices));
        $usersCur      = $u['cur'];
        $usersPrev     = $u['prev'];
        $candidatesCur = $u['candidates'];
        $companiesCur  = $u['companies'];

        $short = self::PERIODS[$this->period]['short'];

        return [
            'open_offers' => [
                'label' => 'Offres ouvertes',
                'value' => $this->number($openNow),
                'sub'   => $publishedCur . ' déposée' . ($publishedCur > 1 ? 's' : '') . ' sur ' . $short,
                'delta' => $this->deltaPercent($publishedCur, $publishedPrev),
                'spark' => $this->series($o, 's'),
            ],
            'applications' => [
                'label' => 'Candidatures (' . $short . ')',
                'value' => $this->number($appsCur),
                'sub'   => 'dont ' . $pendingCur . ' en attente',
                'delta' => $this->deltaPercent($appsCur, $appsPrev),
                'spark' => $this->series($a, 's'),
            ],
            'new_users' => [
                'label' => 'Nouveaux inscrits (' . $short . ')',
                'value' => $this->number($usersCur),
                'sub'   => $candidatesCur . ' candidat' . ($candidatesCur > 1 ? 's' : '') . ' · '
                         . $companiesCur . ' entreprise' . ($companiesCur > 1 ? 's' : ''),
                'delta' => $this->deltaPercent($usersCur, $usersPrev),
                'spark' => $this->series($u, 's'),
            ],
            'acceptance' => [
                'label' => "Taux d'acceptation",
                'value' => $rateCur === null ? '—' : $rateCur . ' %',
                'sub'   => $accCur . ' acceptée' . ($accCur > 1 ? 's' : '') . ' sur ' . $decCur . ' traitée' . ($decCur > 1 ? 's' : ''),
                'delta' => $this->deltaPoints($rateCur, $ratePrev),
                'spark' => array_map(  // null : aucune candidature traitée (trou dans la courbe)
                    fn ($acc, $dec) => $dec ? (int) round($acc / $dec * 100) : null,
                    $this->series($a, 'acc'), $this->series($a, 'dec')
                ),
            ],
        ];
    }

    /**
     * Offres publiées et candidatures reçues, au rythme de la période choisie :
     * 7 j et 30 j → un point par jour (aujourd'hui compris), 12 mois → un point par mois.
     * Les jours ou mois sans activité valent 0.
     */
    public function activity(): array
    {
        $byMonth = $this->period === '12m';
        $count   = match ($this->period) { '7j' => 7, '12m' => 12, default => 30 };
        $first   = $byMonth ? now()->startOfMonth()->subMonths($count - 1) : now()->startOfDay()->subDays($count - 1);

        $keys = $labels = $full = [];
        for ($i = 0; $i < $count; $i++) {
            $d = $byMonth ? $first->copy()->addMonths($i) : $first->copy()->addDays($i);
            $keys[]   = $d->format($byMonth ? 'Y-m' : 'Y-m-d');
            $labels[] = $d->translatedFormat($byMonth ? 'M Y' : ($this->period === '7j' ? 'D j' : 'j M'));
            $full[]   = $d->translatedFormat($byMonth ? 'F Y' : 'l j F');
        }

        // Une requête groupée par table, puis complétée par des zéros
        $bucket = $byMonth ? "DATE_FORMAT(created_at, '%Y-%m')" : 'DATE(created_at)';
        $series = function (Builder $q) use ($bucket, $first, $keys) {
            $rows = (clone $q)->toBase()->where('created_at', '>=', $first)->where('created_at', '<=', $this->end)
                ->selectRaw("{$bucket} as k, count(*) as n")->groupBy('k')->pluck('n', 'k');

            return array_map(fn ($k) => (int) ($rows[$k] ?? 0), $keys);
        };
        // « Publiées » : offres passées par la modération (ouvertes, clôturées ou archivées)
        $offers = $series(JobOffer::whereIn('status', ['open', 'closed', 'archived']));
        $apps   = $series(Application::query());

        // 30 jours : 5 tranches égales de 6 jours (un point par jour donnait une courbe en dents de scie)
        if ($this->period === '30j') {
            $days = array_map(fn ($k) => \Illuminate\Support\Carbon::parse($k), $keys);
            $labels = $full = [];
            foreach (array_chunk($days, 6) as $chunk) {
                [$a, $b] = [$chunk[0], end($chunk)];
                $labels[] = $a->month === $b->month
                    ? $a->format('j') . '–' . $b->translatedFormat('j M')
                    : $a->translatedFormat('j M') . '–' . $b->translatedFormat('j M');
                $full[] = 'du ' . $a->translatedFormat('j F') . ' au ' . $b->translatedFormat('j F');
            }
            $offers = array_map('array_sum', array_chunk($offers, 6));
            $apps   = array_map('array_sum', array_chunk($apps, 6));
        }

        return [
            'labels'       => $labels,
            'full'         => $full,
            'offers'       => $offers,
            'applications' => $apps,
            'tick_every'   => 1,
            'empty'        => array_sum($offers) + array_sum($apps) === 0,
        ];
    }

    /** Offres ouvertes par catégorie : 6 premières lignes + « Autres ». */
    public function byCategory(): array
    {
        $rows = self::openOffers()
            ->leftJoin('categories', 'categories.id', '=', 'job_offers.category_id')
            ->select(DB::raw("COALESCE(categories.name, 'Sans catégorie') as label"), DB::raw('count(*) as total'))
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        return $this->topWithOthers($rows);
    }

    /** Offres ouvertes par ville : 6 premières lignes + « Autres ». */
    public function byCity(): array
    {
        $rows = self::openOffers()
            ->select(DB::raw("COALESCE(NULLIF(location, ''), 'Non précisée') as label"), DB::raw('count(*) as total'))
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        return $this->topWithOthers($rows);
    }

    /** Candidatures déposées sur la période, par statut. */
    public function byStatus(): array
    {
        $counts = Application::whereBetween('created_at', [$this->start, $this->end])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $total = (int) $counts->sum();
        $meta  = [
            'pending'  => ['label' => 'En attente', 'tone' => 'warning'],
            'accepted' => ['label' => 'Acceptées',  'tone' => 'success'],
            'rejected' => ['label' => 'Refusées',   'tone' => 'danger'],
        ];

        $segments = [];
        foreach ($meta as $status => $m) {
            $n = (int) ($counts[$status] ?? 0);
            $segments[] = $m + ['status' => $status, 'count' => $n, 'pct' => $total ? round($n / $total * 100) : 0];
        }
        // Statuts inattendus éventuels, regroupés
        $other = $total - array_sum(array_column($segments, 'count'));
        if ($other > 0) {
            $segments[] = ['status' => 'other', 'label' => 'Autres', 'tone' => 'neutral', 'count' => $other, 'pct' => round($other / $total * 100)];
        }

        // Délai moyen de réponse : de la candidature à la décision (acceptation ou refus), sur la période
        $decided = Application::whereBetween('created_at', [$this->start, $this->end])
            ->whereIn('status', ['accepted', 'rejected'])->get(['created_at', 'updated_at']);
        $avgDays = $decided->isEmpty() ? null
            : $decided->avg(fn ($a) => $a->created_at->diffInMinutes($a->updated_at)) / 1440; // non arrondi : la vue décide

        return ['total' => $total, 'segments' => $segments, 'avg_response_days' => $avgDays];
    }

    /**
     * 5 derniers événements de la période : candidature, signalement, offre déposée, inscription.
     * Chaque événement : type (icône et couleur), titre, détail, lien, date.
     */
    public function recentActivity(): array
    {
        $events = collect();
        $inPeriod = fn ($q) => $q->where('created_at', '>', $this->start)->where('created_at', '<=', $this->end)->latest()->take(5);

        $inPeriod(Application::with(['jobOffer', 'user']))->get()->each(function ($a) use ($events) {
            $events->push([
                'type'   => 'application',
                'title'  => 'Nouvelle candidature',
                'detail' => implode(' · ', array_filter([$a->jobOffer->title ?? 'Offre supprimée', $a->user->name ?? null])),
                'url'    => $a->jobOffer ? route('admin.offers.show', $a->jobOffer) : null,
                'at'     => $a->created_at,
            ]);
        });

        $inPeriod(Report::with(['user', 'reportedUser']))->get()->each(function ($r) use ($events) {
            $events->push([
                'type'   => 'report',
                'title'  => 'Signalement',
                'detail' => ($r->reportedUser->name ?? 'Compte supprimé') . ' · signalé par ' . ($r->user->name ?? 'compte supprimé'),
                'url'    => route('admin.reports.show', $r),
                'at'     => $r->created_at,
            ]);
        });

        $inPeriod(JobOffer::with('company'))->get()->each(function ($o) use ($events) {
            $events->push([
                'type'   => 'offer',
                'title'  => 'Offre déposée',
                'detail' => implode(' · ', array_filter([$o->title, $o->company->name ?? null])),
                'url'    => route('admin.offers.show', $o),
                'at'     => $o->created_at,
            ]);
        });

        $inPeriod(User::where('role', '!=', User::ROLE_ADMIN))->get()->each(function ($u) use ($events) {
            $events->push([
                'type'   => 'user',
                'title'  => $u->isCompany() ? 'Nouvelle entreprise' : 'Nouveau candidat',
                'detail' => implode(' · ', array_filter([$u->name, $u->city])),
                'url'    => route('admin.users.show', $u),
                'at'     => $u->created_at,
            ]);
        });

        return $events->sortByDesc('at')->take(5)->values()->all();
    }

    /** Données de l'export CSV (mêmes calculs que la vue). */
    public function csvRows(User $admin): array
    {
        $p     = self::PERIODS[$this->period]['label'];
        $todo  = $this->todo($admin);
        $tiles = $this->tiles();
        $rows  = [
            ['Tableau de bord TunisieEmploi'],
            ['Période', $p . ' (du ' . $this->start->translatedFormat('j F Y') . ' au ' . $this->end->translatedFormat('j F Y') . ')'],
            ['Exporté le', now()->translatedFormat('j F Y à H:i')],
            [],
            ['À traiter'],
            ['Offres en attente de modération', $todo['offers']],
            ['Entreprises en attente de validation', $todo['companies']],
            ['Signalements à traiter', $todo['reports']],
            [],
            ['Indicateurs'],
        ];
        foreach ($tiles as $t) {
            $rows[] = [$t['label'], $t['value'], $t['sub'] ?? '', $t['delta']['text'] ?? ''];
        }
        $rows[] = [];
        $rows[] = ['Candidatures par statut (période)', 'Nombre', 'Part'];
        foreach ($this->byStatus()['segments'] as $s) {
            $rows[] = [$s['label'], $s['count'], $s['pct'] . ' %'];
        }
        foreach (['Offres ouvertes par catégorie' => $this->byCategory(), 'Offres ouvertes par ville' => $this->byCity()] as $title => $list) {
            $rows[] = [];
            $rows[] = [$title, 'Nombre'];
            foreach ($list['rows'] as $r) {
                $rows[] = [$r['label'], $r['total']];
            }
        }
        $a = $this->activity();
        $rows[] = [];
        $rows[] = ['Activité (' . $p . ')', 'Offres publiées', 'Candidatures'];
        foreach ($a['full'] as $i => $label) {
            $rows[] = [$label, $a['offers'][$i], $a['applications'][$i]];
        }

        return $rows;
    }

    // ── Outils ──────────────────────────────────────────────────────────

    /**
     * Plusieurs comptages en une seule requête (agrégation conditionnelle).
     * $columns : clé => [début, fin, condition SQL facultative, valeurs liées…], intervalle ]début, fin] ;
     * avec $range = false : clé => [condition SQL, valeurs liées…].
     */
    private function counts(Builder $q, array $columns, bool $range = true): array
    {
        $select = $bindings = [];
        foreach ($columns as $key => $col) {
            if ($range) {
                $sql  = 'created_at > ? and created_at <= ?' . (isset($col[2]) ? ' and ' . $col[2] : '');
                $vals = [$col[0], $col[1], ...array_slice($col, 3)];
            } else {
                $sql  = array_shift($col);
                $vals = $col;
            }
            $select[] = "sum(case when {$sql} then 1 else 0 end) as `{$key}`";
            array_push($bindings, ...$vals);
        }
        $row = (array) (clone $q)->toBase()->selectRaw(implode(', ', $select), $bindings)->first();

        return array_map(fn ($v) => (int) $v, $row);
    }

    /** Colonnes « préfixe0 » à « préfixe5 » : une par tranche, avec une condition facultative. */
    private function sliceColumns(string $prefix, array $slices, array $cond = []): array
    {
        $out = [];
        foreach ($slices as $i => $s) {
            $out[$prefix . $i] = [...$s, ...$cond];
        }

        return $out;
    }

    private function series(array $counts, string $prefix): array
    {
        return array_map(fn ($i) => $counts[$prefix . $i], range(0, 5));
    }

    /** 6 tranches égales de la période courante. */
    private function slices(): array
    {
        $seconds = $this->end->getTimestamp() - $this->start->getTimestamp();
        $out = [];
        for ($i = 0; $i < 6; $i++) {
            $out[] = [$this->start->copy()->addSeconds(intdiv($seconds * $i, 6)), $this->start->copy()->addSeconds(intdiv($seconds * ($i + 1), 6))];
        }

        return $out;
    }

    /** Une seule ligne de comparaison, même format pour les 4 indicateurs. */
    private function deltaPercent(int $cur, int $prev): array
    {
        if ($cur === $prev) {
            return ['text' => 'Stable vs période précédente', 'trend' => 0];
        }
        // Base trop faible pour un pourcentage parlant : écart absolu, avec la valeur précédente
        if ($prev < 10) {
            $d = $cur - $prev;

            return ['text' => ($d > 0 ? '+' : '−') . abs($d) . " vs période précédente ({$prev})", 'trend' => $d <=> 0];
        }
        $pct = (int) round(($cur - $prev) / $prev * 100);
        if ($pct === 0) {
            return ['text' => 'Stable vs période précédente', 'trend' => 0];
        }

        return ['text' => ($pct > 0 ? '+' : '−') . abs($pct) . ' % vs période précédente', 'trend' => $pct <=> 0];
    }

    private function deltaPoints(?int $cur, ?int $prev): array
    {
        if ($cur === null && $prev === null) {
            return ['text' => 'Stable vs période précédente', 'trend' => 0];
        }
        if ($cur === null || $prev === null) {
            return ['text' => 'Aucune candidature traitée sur ' . ($cur === null ? 'cette période' : 'la période précédente'), 'trend' => 0];
        }
        $d = $cur - $prev;

        return $d === 0
            ? ['text' => 'Stable vs période précédente', 'trend' => 0]
            : ['text' => ($d > 0 ? '+' : '−') . abs($d) . ' pts vs période précédente', 'trend' => $d <=> 0];
    }

    private function number(int $n): string
    {
        return number_format($n, 0, ',', "\u{202F}");
    }

    private function topWithOthers($rows): array
    {
        $top    = $rows->take(6)->map(fn ($r) => ['label' => $r->label, 'total' => (int) $r->total])->values()->all();
        $others = (int) $rows->slice(6)->sum('total');
        if ($others > 0) {
            $top[] = ['label' => 'Autres', 'total' => $others, 'others' => true];
        }

        return ['rows' => $top, 'max' => max(array_column($top, 'total') ?: [1]), 'total' => (int) $rows->sum('total')];
    }
}
