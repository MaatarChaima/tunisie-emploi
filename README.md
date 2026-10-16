# TunisieEmploi

**A job board for the Tunisian market: candidates find and apply to offers, companies publish and screen, an administrator moderates.**

Human–Computer Interaction (HCI) course project, IIT Sfax, 2025/2026.
Built with Laravel 12, Blade, Alpine.js and Tailwind CSS. The interface is in French.

![TunisieEmploi overview](docs/demos/apercu.gif)

---

## Contents

- [Features](#features)
- [Screenshots](#screenshots)
- [Demo videos](#demo-videos)
- [Design documents (UML & specifications)](#design-documents)
- [HCI design choices](#hci-design-choices)
- [Tech stack](#tech-stack)
- [Getting started](#getting-started)
- [Demo accounts](#demo-accounts)
- [Authors](#authors)

---

## Features

| Candidate | Company | Administrator |
|---|---|---|
| Search offers by keyword, city, field, contract type and education level | Publish, edit, archive and re-activate job offers | Dashboard: KPIs, activity over 7 days / 30 days / 12 months, CSV export |
| Sort by date, salary or deadline | Review received applications, download CVs | Approve or reject offers, directly from the offer page |
| Save offers to favourites | Accept or reject with a comment sent to the candidate | Validate or block company accounts |
| Apply with the profile CV or a new one, plus an optional cover letter | Search candidates by city, field, education and experience | Manage categories (name and image) |
| Track application status and company feedback | Report a candidate | Handle reports and block accounts |
| Notifications (badge updates without reloading), report a company | Notifications for new applications | Notifications for new reports |

Visitors can browse and search offers without an account. Every page works in **light and dark mode** and on **mobile**.

---

## Screenshots

### Job offers (visitor)

| Light | Dark |
|---|---|
| ![Offers – light](docs/screenshots/01-offres-clair.png) | ![Offers – dark](docs/screenshots/02-offres-sombre.png) |

| Filters with icons | Offer details |
|---|---|
| ![Field filter open](docs/screenshots/02b-domaine-ouvert.png) | ![Offer details](docs/screenshots/03a-detail-offre.png) |

### Mobile

![Mobile views: offers, menu, filters](docs/screenshots/20-mobile.png)

### Administrator dashboard

| Light – 12 months | Dark – 30 days |
|---|---|
| ![Dashboard – light](docs/screenshots/16a-dashboard-clair.png) | ![Dashboard – dark](docs/screenshots/17a-dashboard-sombre.png) |

<details>
<summary><b>Candidate</b> (applying, applications, favourites, notifications, profile)</summary>

| | |
|---|---|
| ![Apply](docs/screenshots/07-candidater.png) | ![My applications](docs/screenshots/08-mes-candidatures.png) |
| ![Favourites](docs/screenshots/10-favoris.png) | ![Notifications](docs/screenshots/11-notifications.png) |
| ![Profile](docs/screenshots/22-profil.png) | ![Sign-up](docs/screenshots/05a-inscription.png) |

</details>

<details>
<summary><b>Company</b> (offers, received applications, candidate search, new offer)</summary>

| | |
|---|---|
| ![My offers](docs/screenshots/12-mes-offres.png) | ![Received applications](docs/screenshots/13-candidatures-recues.png) |
| ![Candidate search – filtered](docs/screenshots/14a-recherche-candidats.png) | ![Candidate search – dark](docs/screenshots/14b-candidats-sombre.png) |
| ![New offer – top](docs/screenshots/15a-publier-offre.png) | ![New offer – bottom](docs/screenshots/15b-publier-offre.png) |

</details>

<details>
<summary><b>Administrator</b> (dashboard, moderation, reports, categories)</summary>

| | |
|---|---|
| ![Dashboard – bottom, light](docs/screenshots/16b-dashboard-clair.png) | ![Dashboard – bottom, dark](docs/screenshots/17b-dashboard-sombre.png) |
| ![Moderation](docs/screenshots/18-moderation.png) | ![Moderating an offer](docs/screenshots/18b-moderation-offre.png) |
| ![Reports](docs/screenshots/19-signalements.png) | ![Categories](docs/screenshots/21-categories.png) |

</details>

---

Short, silent recordings of the main user journeys.

**1. Browse, filter, sort and switch theme** (visitor)

https://github.com/user-attachments/assets/9986851e-a46a-4c91-bdac-f93f2e3bceb2

**2. Apply to an offer and follow the application** (candidate)

https://github.com/user-attachments/assets/35082507-52e5-4528-a000-2faf09900965

**3. Review an application and accept it** (company)

https://github.com/user-attachments/assets/82a6c3f8-a9f1-4468-ab4f-179ff8f61a52

**4. Publish an offer, then approve it** (company → administrator)

https://github.com/user-attachments/assets/81117f07-97ac-4ba1-9aed-c18811761145

**5. Dashboard: periods, tooltips, CSV export, dark mode** (administrator)

https://github.com/user-attachments/assets/4df75c78-0762-43ad-9f4e-f0362eba243c

**6. Handle a report** (administrator)

https://github.com/user-attachments/assets/a9412520-630d-443e-bfcb-3cf58f522770

**7. Mobile: menu, theme switch, filters** (visitor)

https://github.com/user-attachments/assets/f8188e66-79c6-4c37-9628-ca11cf4fb086

---

## Design documents

The project was specified and modelled before development. The full documents (in French) are in [`docs/`](docs):

- **Requirements specification** (actors, functional requirements, data dictionary, business rules, non-functional requirements): [cahier-des-charges.pdf](docs/cahier-des-charges.pdf)
- **Design document** (use-case diagrams, refinements, class diagram): [conception.pdf](docs/conception.pdf)
- **Editable sources** of every diagram (draw.io): [`docs/diagrams/drawio/`](docs/diagrams/drawio)

### Global use-case diagram

![Global use-case diagram](docs/diagrams/uc_global.png)

### Class diagram

![Class diagram](docs/diagrams/classes.png)

<details>
<summary><b>Use-case refinements</b> (7 diagrams)</summary>

| | |
|---|---|
| **Search offers** ![](docs/diagrams/r1_recherche_offres.png) | **Search candidates** ![](docs/diagrams/r2_recherche_candidats.png) |
| **Create an offer** ![](docs/diagrams/r3_creer_offre.png) | **Candidate sign-up** ![](docs/diagrams/r4_inscription_candidat.png) |
| **Company sign-up** ![](docs/diagrams/r7_inscription_entreprise.png) | **Apply to an offer** ![](docs/diagrams/r5_postuler.png) |
| **View statistics** ![](docs/diagrams/r6_statistiques.png) | |

</details>

---

## HCI design choices

- **Consistency**: one set of design tokens (colours, spacing, radii) shared by every page, in both themes. The default theme follows the operating system setting, and the user's choice is remembered.
- **Accessibility**: WCAG AA contrast in light and dark mode, a visible focus on every control, labels on every field, `aria-label` on icon-only buttons, keyboard-operable custom lists (arrows, Home/End, first-letter search, Escape).
- **Recognition rather than recall**: icons for categories, contract types and education levels; counts next to each filter option; active filters shown as removable chips.
- **Neutral visual language**: each education level has its own icon of equal weight, with no gauge or ranking that would make one profile look "lower" than another.
- **Feedback and error prevention**: French error messages next to the field, confirmation before destructive actions, coloured status banners (pending, refused, archived), live notification badges.
- **Responsive layout**: filters collapse into a panel with a "Show N offers" button on mobile, cards reflow so titles stay on two lines, and the theme switch is also in the mobile menu.
- **Readable data**: the dashboard uses simple line and bar charts, a single stacked bar for application statuses, and groups 30-day activity into 6-day buckets instead of a noisy daily line.

---

## Tech stack

| Layer | Technology |
|---|---|
| Back end | PHP 8.2, Laravel 12 (Eloquent, policies, notifications, form requests) |
| Front end | Blade, Alpine.js, Tailwind CSS 3, Vite 7 |
| Charts | Chart.js |
| Database | MySQL / MariaDB |
| Authentication | Laravel Breeze |

---

## Getting started

### Requirements

PHP 8.2+, Composer, Node.js 18+ and MySQL (XAMPP works).

### Installation

```bash
git clone https://github.com/chaimamaatar/tunisie-emploi.git
cd tunisie-emploi

composer install
npm install
npm run build

cp .env.example .env
php artisan key:generate
```

Create an empty MySQL database (for example `offre_emploi`), then set it in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=offre_emploi
DB_USERNAME=root
DB_PASSWORD=
```

Create the tables, load the demo data and start the server:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open http://127.0.0.1:8000.

---

## Demo accounts

The seeder creates fictional companies, offers and candidates. All names, companies, logos and e-mail addresses are invented.

| Role | E-mail | Password |
|---|---|---|
| Administrator | `admin@example.com` | `TunisieEmploi#2026` |
| Company (Byrsa Numérique) | `company@example.com` | `TunisieEmploi#2026` |
| Candidate (Amine Trabelsi) | `candidate@example.com` | `TunisieEmploi#2026` |

To reset the demo data at any time: `php artisan migrate:fresh --seed`.

---

## Authors

HCI course project, IIT Sfax, 2025/2026:

- **Chaima Maatar**
- **Salha Hentati**
- **Bassem Wali**

Category photos come from free-licence image libraries; authors and links are listed in [docs/CREDITS.md](docs/CREDITS.md). Company logos are original illustrations made for the demo.
