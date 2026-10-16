<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Category;
use App\Models\JobOffer;
use App\Models\Report;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Données de démonstration de TunisieEmploi.
 *
 * - Entreprises et personnes FICTIVES (aucune marque ni personne réelle), e-mails en @example.tn.
 * - Comptes de démo conservés : admin@example.com, company@example.com, candidate@example.com (mot de passe : self::PASSWORD).
 * - Dates relatives à now() et réparties sur les 6 derniers mois ; aucun tirage aléatoire (démo reproductible).
 * - Salaires : montants mensuels bruts en TND.
 *
 * Les tableaux sont exposés par des méthodes statiques pour pouvoir être relus sans écrire en base.
 */
class DemoDataSeeder extends Seeder
{
    // Mot de passe de tous les comptes de démo (absent des listes de fuites : pas d'alerte du navigateur)
    public const PASSWORD = 'TunisieEmploi#2026';

    public const CATEGORIES = [
        'Informatique & Technologie', 'Marketing & Communication', 'Vente & Commerce',
        'Finance & Comptabilité', 'Ressources Humaines', 'Ingénierie & Industrie',
        'Santé & Médical', 'Éducation & Enseignement', 'Hôtellerie & Restauration',
        'Transport & Logistique',
    ];

    /** Entreprises fictives. La première est rattachée au compte de démo company@example.com. */
    public static function companies(): array
    {
        return [
            'byrsa' => [
                'name' => 'Byrsa Numérique', 'email' => 'company@example.com', 'sector' => 'Services numériques (ESN)',
                'city' => 'Tunis', 'address' => 'Immeuble Les Jasmins, rue du Lac Turkana, Les Berges du Lac, Tunis',
                'size' => '50-200 employés', 'age' => 175, 'validated' => true,
                'bio' => "Entreprise de services numériques fondée en 2014, Byrsa Numérique conçoit des applications web et mobiles pour des clients de la santé, de la distribution et des services publics. 80 % de nos projets sont menés en méthode agile avec des équipes mixtes Tunis–Europe.",
                'perks' => ['Mutuelle santé prise en charge à 100 %', 'Deux jours de télétravail par semaine', 'Budget formation et certifications'],
            ],
            'horizon' => [
                'name' => 'Horizon Talents RH', 'email' => 'contact.horizon-talents@example.tn', 'sector' => 'Conseil en recrutement',
                'city' => 'Tunis', 'address' => '12, avenue Mohamed V, Tunis',
                'size' => '10-50 employés', 'age' => 168, 'validated' => true,
                'bio' => "Cabinet de recrutement et de conseil RH accompagnant les PME tunisiennes dans leurs recrutements de cadres et de techniciens, de l'analyse du besoin à l'intégration.",
                'perks' => ['Primes sur objectifs trimestrielles', 'Ticket-restaurant', 'Horaires de la séance unique en été'],
            ],
            'elissa' => [
                'name' => 'Fiduciaire Elissa Conseil', 'email' => 'contact.elissa-conseil@example.tn', 'sector' => 'Expertise comptable et audit',
                'city' => 'Ariana', 'address' => '7, rue de l’Indépendance, Ariana Ville',
                'size' => '10-50 employés', 'age' => 160, 'validated' => true,
                'bio' => "Cabinet d'expertise comptable inscrit à l'Ordre, spécialisé dans la tenue comptable, la fiscalité et l'audit légal des PME du Grand Tunis.",
                'perks' => ['Préparation financée à l’expertise comptable', 'Assurance groupe', '13e mois'],
            ],
            'kerkennah' => [
                'name' => 'Kerkennah Logistique', 'email' => 'contact.kerkennah-logistique@example.tn', 'sector' => 'Transport et logistique',
                'city' => 'Sfax', 'address' => 'Zone industrielle Poudrière 2, route de Gabès km 4, Sfax',
                'size' => '200-500 employés', 'age' => 158, 'validated' => true,
                'bio' => "Opérateur logistique régional : transport routier national, entreposage sous douane et transit au port de Sfax, avec une flotte de 60 poids lourds.",
                'perks' => ['Prime de rendement mensuelle', 'Transport du personnel', 'Tenue de travail fournie'],
            ],
            'sirocco' => [
                'name' => 'Agence Sirocco Communication', 'email' => 'contact.sirocco-com@example.tn', 'sector' => 'Agence de communication',
                'city' => 'Sousse', 'address' => '45, boulevard du 14 Janvier, Sousse',
                'size' => '10-50 employés', 'age' => 150, 'validated' => true,
                'bio' => "Agence de communication globale : identité visuelle, campagnes digitales et événementiel pour des marques du tourisme et de l'agroalimentaire.",
                'perks' => ['Équipement Mac fourni', 'Vendredi après-midi en télétravail', 'Primes sur projets'],
            ],
            'thapsus' => [
                'name' => 'Clinique Thapsus Santé', 'email' => 'contact.thapsus-sante@example.tn', 'sector' => 'Établissement de santé privé',
                'city' => 'Monastir', 'address' => 'Route de la Corniche, Skanès, Monastir',
                'size' => '50-200 employés', 'age' => 145, 'validated' => true,
                'bio' => "Clinique pluridisciplinaire de 90 lits : chirurgie, maternité, imagerie et laboratoire d'analyses, ouverte 24 h/24.",
                'perks' => ['Primes de garde et de nuit', 'Couverture médicale pour la famille', 'Formation continue'],
            ],
            'neapolis' => [
                'name' => 'Résidence Les Orangers Beach', 'email' => 'contact.orangers-beach@example.tn', 'sector' => 'Hôtellerie',
                'city' => 'Nabeul', 'address' => 'Zone touristique, avenue Habib Bourguiba, Nabeul',
                'size' => '200-500 employés', 'age' => 140, 'validated' => true,
                'bio' => "Hôtel balnéaire de 320 chambres ouvert toute l'année, avec centre de congrès, spa et trois restaurants.",
                'perks' => ['Repas et logement pour les saisonniers', 'Prime de fin de saison', 'Transport depuis Nabeul et Hammamet'],
            ],
            'composites' => [
                'name' => 'Bizerte Composites Industrie', 'email' => 'contact.bizerte-composites@example.tn', 'sector' => 'Industrie (matériaux composites)',
                'city' => 'Bizerte', 'address' => 'Zone industrielle de Menzel Bourguiba, Bizerte',
                'size' => '200-500 employés', 'age' => 135, 'validated' => true,
                'bio' => "Fabricant de pièces en matériaux composites pour l'automobile et le ferroviaire, certifié ISO 9001 et ISO 14001, exportant 90 % de sa production.",
                'perks' => ['Prime d’assiduité', 'Restaurant d’entreprise', 'Plan de carrière technique'],
            ],
            'tacapes' => [
                'name' => 'Académie Tacapes', 'email' => 'contact.academie-tacapes@example.tn', 'sector' => 'Enseignement privé',
                'city' => 'Gabès', 'address' => '22, avenue Farhat Hached, Gabès',
                'size' => '50-200 employés', 'age' => 128, 'validated' => true,
                'bio' => "Établissement d'enseignement privé (collège et lycée) et centre de langues, accueillant 900 élèves à Gabès.",
                'perks' => ['Vacances scolaires', 'Réduction de frais de scolarité pour les enfants du personnel', 'Formation pédagogique'],
            ],
            'souk' => [
                'name' => 'Souk Express Distribution', 'email' => 'contact.souk-express@example.tn', 'sector' => 'Distribution de proximité',
                'city' => 'Ben Arous', 'address' => 'Avenue de France, Mégrine, Ben Arous',
                'size' => 'Plus de 500 employés', 'age' => 120, 'validated' => true,
                'bio' => "Enseigne de supérettes de proximité présente dans le Grand Tunis et au Sahel, avec 45 magasins et une centrale d'achat.",
                'perks' => ['Remise sur les achats en magasin', 'Évolution interne rapide', 'Prime de fin d’année'],
            ],
            'aghlabides' => [
                'name' => 'Aghlabides Énergie', 'email' => 'contact.aghlabides-energie@example.tn', 'sector' => 'Énergie solaire',
                'city' => 'Kairouan', 'address' => 'Route de Sousse km 3, Kairouan',
                'size' => '50-200 employés', 'age' => 110, 'validated' => true,
                'bio' => "Installateur et intégrateur de centrales photovoltaïques pour l'agriculture, l'industrie et les particuliers dans le centre du pays.",
                'perks' => ['Véhicule de service', 'Prime de chantier', 'Formation aux habilitations électriques'],
            ],
            // Inscription récente, en attente de validation par l'admin (aucune offre)
            'djerba' => [
                'name' => 'Atelier Djerba Céramique', 'email' => 'contact.djerba-ceramique@example.tn', 'sector' => 'Artisanat',
                'city' => 'Djerba', 'address' => 'Guellala, Djerba',
                'size' => '1-10 employés', 'age' => 3, 'validated' => false,
                'bio' => "Atelier familial de poterie et de céramique artisanale de Guellala, qui souhaite recruter pour sa nouvelle boutique.",
                'perks' => [],
            ],
        ];
    }

    /**
     * Offres. age = ancienneté en jours (date de publication), days = durée de validité.
     * Statut « open » avec age > days ⇒ offre expirée (sert à tester les badges et les filtres).
     */
    public static function offers(): array
    {
        $f = 'full-time'; $p = 'part-time'; $r = 'remote';
        $it = 'Informatique & Technologie'; $mk = 'Marketing & Communication'; $ve = 'Vente & Commerce';
        $fi = 'Finance & Comptabilité'; $rh = 'Ressources Humaines'; $in = 'Ingénierie & Industrie';
        $sa = 'Santé & Médical'; $ed = 'Éducation & Enseignement'; $ho = 'Hôtellerie & Restauration'; $tr = 'Transport & Logistique';

        return [
            // ── Byrsa Numérique (Tunis) ──
            ['byrsa', 'Développeur Laravel (H/F)', $it, 'Tunis', $f, 2400, 2, 'Bac+5', 2, 'open', 12, 60,
                ['Développer de nouvelles fonctionnalités sur nos applications Laravel (API REST, back-office)', 'Écrire des tests automatisés et participer aux revues de code', 'Collaborer avec les équipes produit et front-end en sprints de deux semaines'],
                ['Diplôme d’ingénieur ou master en informatique', 'Deux ans d’expérience sur PHP 8 et Laravel', 'Bonne maîtrise de MySQL et de Git', 'Français courant, anglais technique'],
                'PHP, Laravel, MySQL, API REST', 'Français, Anglais'],
            ['byrsa', 'Développeur front-end Vue.js (H/F)', $it, 'Tunis', $r, 2200, 2, 'Bac+5', 1, 'open', 20, 60,
                ['Intégrer des interfaces responsives à partir de maquettes Figma', 'Développer des composants Vue 3 réutilisables et accessibles', 'Optimiser les performances et l’accessibilité des applications'],
                ['Deux ans d’expérience sur Vue.js ou un framework équivalent', 'Maîtrise de HTML, CSS et TypeScript', 'Sensibilité à l’accessibilité (RGAA/WCAG)'],
                'Vue.js, TypeScript, CSS, Figma', 'Français, Anglais'],
            ['byrsa', 'Ingénieur DevOps (H/F)', $it, 'Tunis', $f, 3200, 4, 'Bac+5', 1, 'open', 34, 75,
                ['Industrialiser les chaînes d’intégration et de déploiement continus', 'Administrer les environnements conteneurisés (Docker, Kubernetes)', 'Mettre en place la supervision et les sauvegardes'],
                ['Quatre ans d’expérience en administration système ou DevOps', 'Maîtrise de Linux, Docker et d’un outil de CI/CD', 'Connaissance d’un cloud public appréciée'],
                'DevOps, Docker, Kubernetes, CI/CD, Linux', 'Français, Anglais'],
            ['byrsa', 'Testeur QA (H/F)', $it, 'Tunis', $f, 1700, 1, 'Bac+3', 1, 'pending_validation', 2, 60,
                ['Rédiger les plans et cas de test fonctionnels', 'Automatiser les tests de non-régression', 'Suivre les anomalies avec les développeurs'],
                ['Licence en informatique', 'Première expérience en test logiciel', 'Rigueur et sens du détail'],
                'Tests, QA, Cypress', 'Français'],
            ['byrsa', 'Chef de projet digital (H/F)', $it, 'Tunis', $f, 3400, 6, 'Bac+5', 1, 'open', 150, 60,
                ['Piloter des projets web de la conception à la mise en production', 'Animer les ateliers avec les clients et suivre le budget', 'Coordonner une équipe de cinq à huit personnes'],
                ['Six ans d’expérience dont trois en gestion de projet', 'Maîtrise des méthodes agiles (Scrum)', 'Excellent relationnel'],
                'Gestion de projet, Scrum, Web', 'Français, Anglais'],
            ['byrsa', 'Technicien support informatique (H/F)', $it, 'Tunis', $f, 1300, 0, 'Bac+3', 2, 'archived', 120, 45,
                ['Assurer le support de niveau 1 et 2 des collaborateurs', 'Préparer et maintenir les postes de travail', 'Gérer l’inventaire du matériel'],
                ['Licence en informatique ou BTS', 'Débutant accepté', 'Sens du service'],
                'Support, Windows, Réseau', 'Français'],

            // ── Horizon Talents RH (Tunis) ──
            ['horizon', 'Chargé de recrutement (H/F)', $rh, 'Tunis', $f, 1600, 2, 'Bac+5', 2, 'open', 9, 50,
                ['Rédiger et diffuser les annonces', 'Présélectionner les candidatures et mener les entretiens', 'Accompagner les clients jusqu’à l’intégration du candidat'],
                ['Master en ressources humaines ou en psychologie du travail', 'Deux ans d’expérience en recrutement', 'Aisance relationnelle'],
                'Recrutement, Sourcing, Entretien', 'Français, Arabe'],
            ['horizon', 'Assistant ressources humaines (H/F)', $rh, 'Tunis', $p, 700, 0, 'Bac+3', 1, 'open', 27, 60,
                ['Tenir les dossiers administratifs du personnel', 'Préparer les éléments variables de paie', 'Organiser les sessions de formation'],
                ['Licence en gestion des ressources humaines', 'Débutant accepté', 'Maîtrise d’Excel'],
                'Administration du personnel, Paie, Excel', 'Français, Arabe'],
            ['horizon', 'Responsable ressources humaines (H/F)', $rh, 'Tunis', $f, 2900, 6, 'Bac+5', 1, 'refused', 60, 60,
                ['Définir la politique RH d’un client industriel de 300 salariés', 'Piloter la paie, les relations sociales et la formation', 'Conseiller la direction sur l’organisation'],
                ['Six ans d’expérience en RH généraliste', 'Bonne connaissance du Code du travail tunisien', 'Leadership'],
                'RH, Droit social, Management', 'Français, Arabe'],

            // ── Fiduciaire Elissa Conseil (Ariana) ──
            ['elissa', 'Comptable (H/F)', $fi, 'Ariana', $f, 1500, 2, 'Bac+3', 1, 'open', 15, 60,
                ['Tenir la comptabilité d’un portefeuille de PME', 'Préparer les déclarations fiscales mensuelles', 'Participer à l’établissement des bilans'],
                ['Licence en comptabilité', 'Deux ans d’expérience en cabinet', 'Maîtrise d’un logiciel comptable'],
                'Comptabilité, Fiscalité, Bilan', 'Français, Arabe'],
            ['elissa', 'Auditeur junior (H/F)', $fi, 'Ariana', $f, 1700, 1, 'Bac+5', 2, 'open', 40, 75,
                ['Participer aux missions d’audit légal et contractuel', 'Réaliser les contrôles des comptes et la revue des procédures', 'Rédiger les notes de synthèse'],
                ['Master en comptabilité, contrôle et audit', 'Un an d’expérience ou stage en audit', 'Esprit d’analyse'],
                'Audit, Contrôle interne, Comptabilité', 'Français, Anglais'],
            ['elissa', 'Assistant comptable (H/F)', $fi, 'Ariana', $p, 1050, 0, 'Bac', 1, 'open', 160, 60,
                ['Saisir les pièces comptables', 'Classer et archiver les justificatifs', 'Préparer les rapprochements bancaires'],
                ['Baccalauréat économie et gestion', 'Débutant accepté', 'Rigueur'],
                'Saisie comptable, Classement', 'Français, Arabe'],
            ['elissa', 'Contrôleur de gestion (H/F)', $fi, 'Ariana', $f, 2600, 4, 'Bac+5', 1, 'pending_validation', 1, 60,
                ['Construire les budgets et les tableaux de bord des clients', 'Analyser les écarts et proposer des actions correctives', 'Fiabiliser le reporting mensuel'],
                ['Quatre ans d’expérience en contrôle de gestion', 'Excellente maîtrise d’Excel et d’un ERP', 'Capacité de synthèse'],
                'Contrôle de gestion, Budget, Reporting', 'Français, Anglais'],

            // ── Kerkennah Logistique (Sfax) ──
            ['kerkennah', 'Chauffeur poids lourd (H/F)', $tr, 'Sfax', $f, 1200, 3, 'Inférieur au baccalauréat', 4, 'open', 7, 45,
                ['Assurer les livraisons nationales au départ de Sfax', 'Contrôler le chargement et les documents de transport', 'Entretenir le véhicule au quotidien'],
                ['Permis C et CE en cours de validité', 'Trois ans d’expérience en transport routier', 'Ponctualité et respect des règles de sécurité'],
                'Permis CE, Transport routier', 'Arabe'],
            ['kerkennah', 'Magasinier cariste (H/F)', $tr, 'Sfax', $f, 1000, 1, 'Bac', 3, 'open', 22, 45,
                ['Réceptionner et contrôler les marchandises', 'Préparer les commandes et charger les camions', 'Tenir les stocks à jour dans le logiciel de gestion'],
                ['Certificat de cariste (CACES ou équivalent)', 'Un an d’expérience en entrepôt', 'Esprit d’équipe'],
                'Cariste, Entrepôt, Stock', 'Arabe, Français'],
            ['kerkennah', 'Responsable d’exploitation logistique (H/F)', $tr, 'Sfax', $f, 2500, 5, 'Bac+5', 1, 'open', 50, 90,
                ['Piloter l’activité de l’entrepôt (40 personnes)', 'Optimiser les flux et suivre les indicateurs de performance', 'Garantir la sécurité et la qualité de service'],
                ['Cinq ans d’expérience en logistique dont deux en management', 'Maîtrise d’un WMS', 'Sens de l’organisation'],
                'Logistique, Management, WMS', 'Français, Arabe'],
            ['kerkennah', 'Agent de transit (H/F)', $tr, 'Sfax', $f, 1300, 2, 'Bac+3', 1, 'open', 140, 60,
                ['Préparer les dossiers de dédouanement import et export', 'Suivre les expéditions avec les transporteurs maritimes', 'Informer les clients de l’avancement'],
                ['Licence en commerce international ou logistique', 'Deux ans d’expérience en transit', 'Connaissance des procédures douanières'],
                'Transit, Douane, Commerce international', 'Français, Anglais, Arabe'],

            // ── Agence Sirocco Communication (Sousse) ──
            ['sirocco', 'Community manager (H/F)', $mk, 'Sousse', $f, 1300, 1, 'Bac+3', 1, 'open', 5, 45,
                ['Animer les réseaux sociaux de nos clients', 'Créer des contenus (visuels, textes, vidéos courtes)', 'Suivre les statistiques et proposer des améliorations'],
                ['Licence en communication ou en marketing', 'Un an d’expérience en animation de communauté', 'Créativité et aisance rédactionnelle'],
                'Réseaux sociaux, Contenu, Canva', 'Français, Arabe, Anglais'],
            ['sirocco', 'Chargé de communication (H/F)', $mk, 'Sousse', $f, 1600, 2, 'Bac+5', 1, 'open', 31, 60,
                ['Concevoir les plans de communication des clients', 'Rédiger communiqués et supports', 'Organiser des événements professionnels'],
                ['Master en communication', 'Deux ans d’expérience en agence', 'Excellente orthographe'],
                'Communication, Rédaction, Événementiel', 'Français, Anglais'],
            ['sirocco', 'Graphiste (H/F)', $mk, 'Sousse', $r, 1400, 2, 'Bac+3', 1, 'open', 125, 60,
                ['Créer des identités visuelles et des supports imprimés', 'Décliner les campagnes sur le web et les réseaux', 'Préparer les fichiers pour l’impression'],
                ['Diplôme en arts graphiques', 'Maîtrise de la suite Adobe ou de Figma', 'Portfolio exigé'],
                'Graphisme, Adobe, Figma', 'Français'],
            ['sirocco', 'Responsable marketing digital (H/F)', $mk, 'Sousse', $f, 2700, 5, 'Bac+5', 1, 'closed', 100, 45,
                ['Définir la stratégie d’acquisition des clients de l’agence', 'Piloter les campagnes payantes et le référencement', 'Encadrer une équipe de trois personnes'],
                ['Cinq ans d’expérience en marketing digital', 'Maîtrise de Google Ads et des outils d’analyse', 'Esprit analytique'],
                'Marketing digital, SEO, SEA', 'Français, Anglais'],

            // ── Clinique Thapsus Santé (Monastir) ──
            ['thapsus', 'Infirmier diplômé d’État (H/F)', $sa, 'Monastir', $f, 1400, 1, 'Bac+3', 4, 'open', 4, 60,
                ['Réaliser les soins prescrits et assurer la surveillance des patients', 'Participer à l’accueil et à l’éducation thérapeutique', 'Assurer la traçabilité dans le dossier de soins'],
                ['Diplôme d’infirmier (licence en sciences infirmières)', 'Un an d’expérience en service hospitalier', 'Disponibilité pour les gardes'],
                'Soins infirmiers, Hospitalisation', 'Arabe, Français'],
            ['thapsus', 'Aide-soignant (H/F)', $sa, 'Monastir', $f, 950, 0, 'Bac', 3, 'open', 18, 45,
                ['Assister les patients dans les gestes de la vie quotidienne', 'Participer à l’hygiène et au confort des chambres', 'Transmettre les observations à l’équipe infirmière'],
                ['Formation d’aide-soignant', 'Débutant accepté', 'Bienveillance et patience'],
                'Aide-soignant, Hygiène', 'Arabe'],
            ['thapsus', 'Technicien de laboratoire (H/F)', $sa, 'Monastir', $f, 1300, 2, 'Bac+3', 1, 'open', 45, 60,
                ['Réaliser les analyses de biologie médicale', 'Assurer la maintenance de premier niveau des automates', 'Respecter les procédures qualité du laboratoire'],
                ['Licence en biologie médicale', 'Deux ans d’expérience en laboratoire', 'Rigueur'],
                'Biologie médicale, Analyses', 'Français, Arabe'],
            ['thapsus', 'Secrétaire médical (H/F)', $sa, 'Monastir', $p, 1000, 1, 'Bac', 1, 'open', 165, 60,
                ['Accueillir les patients et gérer les rendez-vous', 'Mettre à jour les dossiers médicaux', 'Préparer les comptes rendus'],
                ['Formation de secrétariat médical', 'Un an d’expérience', 'Maîtrise de la terminologie médicale'],
                'Secrétariat médical, Accueil', 'Arabe, Français'],

            // ── Résidence Les Orangers Beach (Nabeul) ──
            ['neapolis', 'Chef de cuisine (H/F)', $ho, 'Nabeul', $f, 3000, 8, 'Bac+3', 1, 'open', 25, 60,
                ['Diriger la brigade du restaurant gastronomique (12 personnes)', 'Élaborer les cartes et maîtriser les coûts matière', 'Garantir l’hygiène selon les normes HACCP'],
                ['Huit ans d’expérience dont trois comme chef ou second', 'Expérience en hôtellerie haut de gamme', 'Management d’équipe'],
                'Cuisine, Management, HACCP', 'Arabe, Français'],
            ['neapolis', 'Réceptionniste bilingue (H/F)', $ho, 'Nabeul', $f, 1100, 1, 'Bac+3', 2, 'open', 11, 45,
                ['Accueillir la clientèle et gérer les arrivées et départs', 'Traiter les réservations et les demandes particulières', 'Encaisser et tenir la caisse'],
                ['Diplôme en tourisme ou en hôtellerie', 'Un an d’expérience en réception', 'Anglais et allemand appréciés'],
                'Réception, Accueil, Opera PMS', 'Français, Anglais, Allemand'],
            ['neapolis', 'Serveur en restauration (H/F)', $ho, 'Nabeul', $p, 900, 0, 'Inférieur au baccalauréat', 6, 'open', 3, 45,
                ['Accueillir et servir les clients au restaurant et au bar', 'Dresser et débarrasser les tables', 'Conseiller sur les plats et les boissons'],
                ['Débutant accepté', 'Bonne présentation', 'Disponibilité les soirs et week-ends'],
                'Service, Restauration', 'Arabe, Français'],
            ['neapolis', 'Gouvernant d’étage (H/F)', $ho, 'Nabeul', $f, 1300, 3, 'Bac', 1, 'open', 130, 60,
                ['Encadrer les femmes et valets de chambre', 'Contrôler la propreté et la présentation des chambres', 'Gérer le linge et les produits d’accueil'],
                ['Trois ans d’expérience en hébergement', 'Sens du détail', 'Capacité d’encadrement'],
                'Hébergement, Encadrement', 'Arabe, Français'],

            // ── Bizerte Composites Industrie (Bizerte) ──
            ['composites', 'Ingénieur qualité (H/F)', $in, 'Bizerte', $f, 2600, 3, 'Bac+5', 1, 'open', 14, 60,
                ['Animer le système qualité ISO 9001', 'Traiter les non-conformités et les réclamations clients', 'Mener les audits internes et fournisseurs'],
                ['Diplôme d’ingénieur (génie industriel ou mécanique)', 'Trois ans d’expérience en qualité industrielle', 'Maîtrise des outils 8D, AMDEC'],
                'Qualité, ISO 9001, AMDEC', 'Français, Anglais'],
            ['composites', 'Technicien de maintenance (H/F)', $in, 'Bizerte', $f, 1400, 2, 'Bac+3', 2, 'open', 37, 60,
                ['Réaliser la maintenance préventive et curative des lignes', 'Diagnostiquer les pannes électriques et mécaniques', 'Proposer des améliorations'],
                ['BTS ou licence en maintenance industrielle', 'Deux ans d’expérience', 'Habilitation électrique appréciée'],
                'Maintenance, Électromécanique', 'Français, Arabe'],
            ['composites', 'Opérateur de production (H/F)', $in, 'Bizerte', $f, 900, 0, 'Inférieur au baccalauréat', 10, 'open', 8, 30,
                ['Conduire une presse de moulage', 'Contrôler visuellement les pièces produites', 'Respecter les consignes de sécurité'],
                ['Débutant accepté, formation assurée', 'Travail en équipes (3×8)', 'Rigueur'],
                'Production, Industrie', 'Arabe'],
            ['composites', 'Responsable HSE (H/F)', $in, 'Bizerte', $f, 2800, 5, 'Bac+5', 1, 'refused', 75, 60,
                ['Piloter la politique hygiène, sécurité et environnement', 'Mettre à jour le document unique et les plans d’action', 'Former le personnel aux règles de sécurité'],
                ['Cinq ans d’expérience en HSE industriel', 'Connaissance de la norme ISO 14001', 'Pédagogie'],
                'HSE, Sécurité, ISO 14001', 'Français, Arabe'],

            // ── Académie Tacapes (Gabès) ──
            ['tacapes', 'Enseignant de mathématiques (H/F)', $ed, 'Gabès', $f, 1500, 2, 'Bac+5', 1, 'open', 16, 60,
                ['Enseigner les mathématiques au collège et au lycée', 'Préparer les élèves aux examens nationaux', 'Assurer le suivi individuel des élèves'],
                ['Master en mathématiques', 'Deux ans d’expérience en enseignement', 'Patience et pédagogie'],
                'Mathématiques, Enseignement', 'Arabe, Français'],
            ['tacapes', 'Professeur d’anglais (H/F)', $ed, 'Gabès', $p, 800, 1, 'Bac+3', 1, 'open', 29, 60,
                ['Animer des cours d’anglais pour adolescents et adultes', 'Préparer aux certifications internationales', 'Concevoir des supports interactifs'],
                ['Licence en anglais', 'Un an d’expérience', 'Niveau C1 minimum'],
                'Anglais, Enseignement, Certifications', 'Anglais, Arabe, Français'],
            ['tacapes', 'Conseiller pédagogique (H/F)', $ed, 'Gabès', $f, 1900, 5, 'Bac+5', 1, 'open', 155, 60,
                ['Accompagner les enseignants dans leurs pratiques', 'Organiser les conseils de classe', 'Suivre les résultats et proposer des actions de soutien'],
                ['Cinq ans d’expérience en enseignement', 'Master en sciences de l’éducation apprécié', 'Sens de l’écoute'],
                'Pédagogie, Accompagnement', 'Arabe, Français'],

            // ── Souk Express Distribution (Ben Arous) ──
            ['souk', 'Conseiller de vente (H/F)', $ve, 'Ben Arous', $f, 950, 0, 'Bac', 5, 'open', 6, 45,
                ['Accueillir et conseiller la clientèle', 'Mettre en rayon et assurer la propreté du magasin', 'Tenir la caisse'],
                ['Débutant accepté', 'Sens du service client', 'Disponibilité le week-end'],
                'Vente, Caisse, Mise en rayon', 'Arabe, Français'],
            ['souk', 'Chef de rayon (H/F)', $ve, 'Ben Arous', $f, 1500, 3, 'Bac+3', 2, 'open', 43, 60,
                ['Gérer l’approvisionnement et la présentation du rayon', 'Encadrer une équipe de quatre employés', 'Suivre le chiffre d’affaires et les marges'],
                ['Trois ans d’expérience en grande distribution', 'Capacité d’encadrement', 'Maîtrise des indicateurs de vente'],
                'Grande distribution, Management', 'Arabe, Français'],
            ['souk', 'Commercial terrain B2B (H/F)', $ve, 'Sousse', $f, 1700, 2, 'Bac+3', 1, 'open', 135, 60,
                ['Développer le réseau de revendeurs au Sahel', 'Négocier les contrats et suivre les commandes', 'Assurer la veille concurrentielle'],
                ['Deux ans d’expérience en vente terrain', 'Permis B obligatoire', 'Goût du challenge'],
                'Vente B2B, Négociation, Prospection', 'Arabe, Français'],

            // ── Aghlabides Énergie (Kairouan) ──
            ['aghlabides', 'Ingénieur photovoltaïque (H/F)', $in, 'Kairouan', $f, 2700, 3, 'Bac+5', 1, 'open', 19, 60,
                ['Dimensionner les installations photovoltaïques', 'Réaliser les études techniques et les dossiers de raccordement', 'Suivre la mise en service des centrales'],
                ['Diplôme d’ingénieur en énergétique ou en électricité', 'Trois ans d’expérience dans le solaire', 'Maîtrise de PVsyst'],
                'Photovoltaïque, Énergie, PVsyst', 'Français, Arabe'],
            ['aghlabides', 'Technicien installateur solaire (H/F)', $in, 'Kairouan', $f, 1300, 1, 'Bac', 3, 'open', 10, 45,
                ['Installer les panneaux et les onduleurs', 'Réaliser les raccordements électriques', 'Assurer la maintenance des installations'],
                ['Formation en électricité', 'Un an d’expérience', 'Travail en hauteur'],
                'Électricité, Installation solaire', 'Arabe'],
            ['aghlabides', 'Commercial en solutions solaires (H/F)', $ve, 'Kairouan', $f, 1600, 2, 'Bac+3', 1, 'open', 52, 75,
                ['Prospecter les exploitants agricoles et les industriels', 'Établir les devis avec le bureau d’études', 'Suivre la relation client après installation'],
                ['Deux ans d’expérience commerciale', 'Intérêt pour les énergies renouvelables', 'Permis B'],
                'Vente, Énergie solaire, Prospection', 'Arabe, Français'],
        ];
    }

    /** Candidats fictifs. Le premier est rattaché au compte de démo candidate@example.com. */
    public static function candidates(): array
    {
        return [
            ['Amine Trabelsi',    'candidate@example.com',            'Tunis',     'Informatique & Technologie', 'Bac+5', 3, 1996, 170],
            ['Mariem Jebali',     'mariem.jebali@example.tn',         'Sfax',      'Finance & Comptabilité',     'Bac+3', 2, 1998, 165],
            ['Oussama Ferchichi', 'oussama.ferchichi@example.tn',     'Sousse',    'Hôtellerie & Restauration',  'Bac',   4, 1995, 150],
            ['Ines Karoui',       'ines.karoui@example.tn',           'Ariana',    'Marketing & Communication',  'Bac+3', 1, 2000, 120],
            ['Skander Mhiri',     'skander.mhiri@example.tn',         'Bizerte',   'Ingénierie & Industrie',     'Bac+5', 3, 1997, 100],
            ['Rania Gharbi',      'rania.gharbi@example.tn',          'Monastir',  'Santé & Médical',            'Bac+3', 2, 1999, 80],
            ['Hamza Chaabane',    'hamza.chaabane@example.tn',        'Nabeul',    'Vente & Commerce',           'Bac',   1, 2001, 60],
            ['Nour Ben Youssef',  'nour.benyoussef@example.tn',       'Gabès',     'Éducation & Enseignement',   'Bac+5', 5, 1993, 40],
            ['Wassim Haddad',     'wassim.haddad@example.tn',         'Ben Arous', 'Transport & Logistique',     'Bac',   6, 1990, 20],
            ['Sarra Mejri',       'sarra.mejri@example.tn',           'Kairouan',  'Ressources Humaines',        'Bac+5', 0, 2002, 9],
        ];
    }

    /**
     * Candidatures : [candidat (index), offre (titre), statut, ancienneté en jours, commentaire de l'entreprise].
     * L'ancienneté est toujours inférieure à celle de l'offre.
     */
    public static function applications(): array
    {
        return [
            [0, 'Développeur Laravel (H/F)', 'pending', 3, null],
            [0, 'Ingénieur DevOps (H/F)', 'rejected', 25, 'Profil intéressant mais nous recherchons davantage d’expérience sur Kubernetes.'],
            [0, 'Développeur front-end Vue.js (H/F)', 'accepted', 14, 'Entretien technique validé, nous vous contactons pour la suite.'],
            [0, 'Chef de projet digital (H/F)', 'rejected', 140, null],
            [3, 'Développeur front-end Vue.js (H/F)', 'pending', 6, null],
            [4, 'Ingénieur DevOps (H/F)', 'pending', 20, null],
            [1, 'Comptable (H/F)', 'accepted', 10, 'Vous pouvez passer au cabinet pour signer votre contrat.'],
            [1, 'Auditeur junior (H/F)', 'pending', 30, null],
            [1, 'Assistant comptable (H/F)', 'accepted', 150, null],
            [9, 'Chargé de recrutement (H/F)', 'pending', 5, null],
            [9, 'Assistant ressources humaines (H/F)', 'rejected', 20, 'Le poste a été pourvu en interne.'],
            [8, 'Chauffeur poids lourd (H/F)', 'pending', 4, null],
            [8, 'Magasinier cariste (H/F)', 'accepted', 15, null],
            [8, 'Responsable d’exploitation logistique (H/F)', 'rejected', 40, null],
            [8, 'Agent de transit (H/F)', 'pending', 120, null],
            [3, 'Community manager (H/F)', 'pending', 2, null],
            [3, 'Chargé de communication (H/F)', 'accepted', 24, 'Bienvenue dans l’équipe !'],
            [3, 'Graphiste (H/F)', 'rejected', 110, null],
            [5, 'Infirmier diplômé d’État (H/F)', 'pending', 2, null],
            [5, 'Technicien de laboratoire (H/F)', 'rejected', 35, null],
            [5, 'Secrétaire médical (H/F)', 'accepted', 155, null],
            [2, 'Chef de cuisine (H/F)', 'pending', 18, null],
            [2, 'Réceptionniste bilingue (H/F)', 'rejected', 8, 'Nous recherchons un profil germanophone.'],
            [2, 'Gouvernant d’étage (H/F)', 'accepted', 125, null],
            [6, 'Serveur en restauration (H/F)', 'pending', 1, null],
            [4, 'Ingénieur qualité (H/F)', 'accepted', 9, 'Entretien prévu sur le site de Menzel Bourguiba.'],
            [4, 'Technicien de maintenance (H/F)', 'pending', 28, null],
            [4, 'Ingénieur photovoltaïque (H/F)', 'pending', 12, null],
            [7, 'Enseignant de mathématiques (H/F)', 'accepted', 11, null],
            [7, 'Conseiller pédagogique (H/F)', 'pending', 145, null],
            [7, 'Professeur d’anglais (H/F)', 'rejected', 22, null],
            [6, 'Conseiller de vente (H/F)', 'pending', 4, null],
            [6, 'Chef de rayon (H/F)', 'rejected', 36, null],
            [6, 'Commercial terrain B2B (H/F)', 'accepted', 128, null],
            [6, 'Commercial en solutions solaires (H/F)', 'pending', 45, null],
            [5, 'Aide-soignant (H/F)', 'pending', 10, null],
        ];
    }

    /** Offres sauvegardées : [candidat (index), titre de l'offre]. */
    public static function savedJobs(): array
    {
        return [
            [0, 'Développeur Laravel (H/F)'], [0, 'Ingénieur DevOps (H/F)'], [0, 'Ingénieur photovoltaïque (H/F)'],
            [1, 'Contrôleur de gestion (H/F)'], [1, 'Auditeur junior (H/F)'],
            [3, 'Responsable marketing digital (H/F)'], [3, 'Graphiste (H/F)'],
            [4, 'Responsable HSE (H/F)'], [5, 'Aide-soignant (H/F)'], [7, 'Professeur d’anglais (H/F)'],
            [8, 'Chauffeur poids lourd (H/F)'], [9, 'Chargé de recrutement (H/F)'],
        ];
    }

    /**
     * Signalements : [auteur (e-mail), signalé (e-mail), motif, détail, ancienneté en jours, traité il y a N jours (null = à traiter)].
     * Règles de l'application : un candidat ne signale qu'une entreprise à laquelle il a postulé,
     * une entreprise ne signale qu'un candidat ayant postulé à l'une de ses offres.
     */
    public static function reports(): array
    {
        return [
            ['hamza.chaabane@example.tn', 'contact.souk-express@example.tn', 'Fausses informations',
                'Le salaire indiqué pendant l’entretien était inférieur à celui de l’annonce.', 30, 27],
            ['company@example.com', 'ines.karoui@example.tn', 'Spam',
                'Plusieurs candidatures identiques envoyées le même jour avec des CV différents.', 5, 4],
            ['ines.karoui@example.tn', 'contact.sirocco-com@example.tn', 'Contenu inapproprié',
                'Des questions personnelles sans rapport avec le poste ont été posées lors de l’entretien.', 1, null],
        ];
    }

    // ────────────────────────────────────────────────────────────────────

    public function run(): void
    {
        Model::unguarded(function () {
            $categories = $this->seedCategories();
            $admin      = $this->seedAdmin();
            $companies  = $this->seedCompanies();
            $offers     = $this->seedOffers($companies, $categories);
            $candidates = $this->seedCandidates();
            $this->seedApplications($candidates, $offers);
            $this->seedSavedJobs($candidates, $offers);
            $this->seedReports();

            $admin->forceFill(['reports_seen_at' => now()->subDays(3)])->save();
        });

        $this->call(CategoryImageSeeder::class);
    }

    private function at(int $daysAgo, int $hour = 10): \Illuminate\Support\Carbon
    {
        return now()->subDays($daysAgo)->setTime($hour, ($daysAgo * 7) % 60);
    }

    private function seedCategories(): array
    {
        $out = [];
        foreach (self::CATEGORIES as $name) {
            $out[$name] = Category::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }

        return $out;
    }

    private function seedAdmin(): User
    {
        return User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Administrateur', 'password' => Hash::make(self::PASSWORD), 'role' => User::ROLE_ADMIN,
            'phone' => null, 'bio' => 'Compte d’administration de la plateforme.',
            'is_validated' => true, 'is_blocked' => false, 'email_verified_at' => $this->at(180),
            'created_at' => $this->at(180), 'updated_at' => $this->at(180),
        ]);
    }

    private function seedCompanies(): array
    {
        $out = [];
        $i = 0;
        foreach (self::companies() as $key => $c) {
            $i++;
            $out[$key] = User::updateOrCreate(['email' => $c['email']], [
                'name' => $c['name'], 'password' => Hash::make(self::PASSWORD), 'role' => User::ROLE_COMPANY,
                'phone' => sprintf('+216 70 000 %03d', 100 + $i), 'bio' => $c['bio'],
                'website' => 'https://' . Str::slug($c['name']) . '.example.tn',
                'sector' => $c['sector'], 'city' => $c['city'], 'address' => $c['address'],
                'company_size' => $c['size'], 'tax_id' => sprintf('%07d/A/M/000', 1000000 + $i * 7919),
                // Logo SVG du dépôt (public/images/companies/<slug>.svg) s'il existe
                'logo_path' => is_file(public_path('images/companies/' . Str::slug($c['name']) . '.svg'))
                    ? 'images/companies/' . Str::slug($c['name']) . '.svg' : null,
                'is_validated' => $c['validated'], 'is_blocked' => false,
                'email_verified_at' => $this->at($c['age']),
                'created_at' => $this->at($c['age'], 9), 'updated_at' => $this->at($c['age'], 9),
            ]);
        }

        return $out;
    }

    private function seedOffers(array $companies, array $categories): array
    {
        $meta = self::companies();
        $out  = [];
        foreach (self::offers() as [$co, $title, $cat, $city, $type, $salary, $exp, $edu, $vac, $status, $age, $days, $missions, $profile, $keywords, $languages]) {
            $company = $meta[$co];
            $description = "Poste basé à {$city}, au sein de {$company['name']}.\n\n"
                . "Vos missions :\n- " . implode("\n- ", $missions) . "\n\n"
                . "Ce que nous offrons :\n- " . implode("\n- ", array_merge(
                    ['Salaire de ' . number_format($salary, 0, ',', ' ') . ' TND brut par mois', 'Contrat ' . ['full-time' => 'à temps plein', 'part-time' => 'à temps partiel', 'remote' => 'en télétravail'][$type]],
                    $company['perks']
                ));
            $out[$title] = JobOffer::create([
                'user_id' => $companies[$co]->id, 'category_id' => $categories[$cat]->id,
                'title' => $title, 'description' => $description, 'requirements' => '- ' . implode("\n- ", $profile),
                'location' => $city, 'type' => $type, 'salary' => $salary, 'experience_years' => $exp,
                'education_level' => $edu, 'vacancies' => $vac, 'status' => $status,
                'keywords' => $keywords, 'languages' => $languages,
                'expiration_date' => $this->at($age)->addDays($days)->toDateString(),
                'created_at' => $this->at($age, 11), 'updated_at' => $this->at(max(0, $age - 1), 11),
            ]);
        }

        return $out;
    }

    private function seedCandidates(): array
    {
        $out = [];
        foreach (self::candidates() as [$name, $email, $city, $domain, $edu, $exp, $birthYear, $age]) {
            $cvPath = $this->demoCv($name, $domain, $city, $edu, $exp);
            $out[] = User::updateOrCreate(['email' => $email], [
                'name' => $name, 'password' => Hash::make(self::PASSWORD), 'role' => User::ROLE_CANDIDATE,
                'phone' => null, 'city' => $city, 'domain' => $domain, 'education_level' => $edu,
                'experience_years' => $exp, 'birth_date' => "{$birthYear}-0" . (1 + strlen($name) % 9) . '-1' . (strlen($city) % 9),
                'bio' => $exp === 0
                    ? "Jeune diplômée en {$domain}, je recherche une première expérience à {$city} ou en télétravail."
                    : "{$exp} an" . ($exp > 1 ? 's' : '') . " d’expérience en {$domain}. Disponible rapidement, mobile dans la région de {$city}.",
                'linkedin_url' => null, 'cv_path' => $cvPath,
                'is_validated' => true, 'is_blocked' => false, 'email_verified_at' => $this->at($age),
                'created_at' => $this->at($age, 14), 'updated_at' => $this->at($age, 14),
            ]);
        }

        return $out;
    }

    private function seedApplications(array $candidates, array $offers): void
    {
        foreach (self::applications() as [$ci, $title, $status, $age, $comment]) {
            $candidate = $candidates[$ci];
            Application::create([
                'user_id' => $candidate->id, 'job_offer_id' => $offers[$title]->id, 'status' => $status,
                'message' => null, 'cv_path' => $candidate->cv_path, 'cover_letter_path' => null,
                'company_comment' => $comment,
                'created_at' => $this->at($age, 15), 'updated_at' => $this->at(max(0, $age - 2), 16),
            ]);
        }
    }

    private function seedSavedJobs(array $candidates, array $offers): void
    {
        foreach (self::savedJobs() as $i => [$ci, $title]) {
            SavedJob::create([
                'user_id' => $candidates[$ci]->id, 'job_offer_id' => $offers[$title]->id,
                'created_at' => $this->at(2 + $i), 'updated_at' => $this->at(2 + $i),
            ]);
        }
    }

    private function seedReports(): void
    {
        $adminId = User::where('email', 'admin@example.com')->value('id');
        foreach (self::reports() as [$from, $to, $type, $detail, $age, $treatedAgo]) {
            Report::create([
                'treated_at' => $treatedAgo === null ? null : $this->at($treatedAgo, 11),
                'treated_by' => $treatedAgo === null ? null : $adminId,
                'user_id' => User::where('email', $from)->value('id'),
                'reported_user_id' => User::where('email', $to)->value('id'),
                'reason' => $type . "\n\n" . $detail,
                'created_at' => $this->at($age, 17), 'updated_at' => $this->at($age, 17),
            ]);
        }
    }

    /** Petit CV PDF fictif (une page) dans storage/app/public/cvs/demo/. */
    private function demoCv(string $name, string $domain, string $city, string $edu, int $exp): string
    {
        $path  = 'cvs/demo/' . Str::slug($name) . '.pdf';
        $lines = [
            Str::ascii($name), 'CV de demonstration - TunisieEmploi', '',
            'Domaine : ' . Str::ascii($domain), 'Ville : ' . Str::ascii($city),
            'Niveau d\'etudes : ' . $edu, 'Experience : ' . $exp . ' an(s)', '',
            'Document fictif genere pour la demonstration.',
        ];
        $text = "BT /F1 20 Tf 60 780 Td 26 TL\n";
        foreach ($lines as $i => $l) {
            $text .= ($i === 1 ? "/F1 12 Tf\n" : '') . '(' . addcslashes($l, '()\\') . ") Tj T*\n";
        }
        $text .= "ET";
        $objs = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length ' . strlen($text) . " >>\nstream\n{$text}\nendstream",
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [];
        foreach ($objs as $i => $o) {
            $offsets[] = strlen($pdf);
            $pdf .= ($i + 1) . " 0 obj\n{$o}\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objs) + 1) . "\n0000000000 65535 f \n";
        foreach ($offsets as $off) {
            $pdf .= sprintf("%010d 00000 n \n", $off);
        }
        $pdf .= "trailer\n<< /Size " . (count($objs) + 1) . " /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";
        Storage::disk('public')->put($path, $pdf);

        return $path;
    }
}
