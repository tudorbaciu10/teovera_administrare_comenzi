# Avicola-Teovera — Aplicație preluare comenzi interne
**Proiect:** sistem web pentru eliminarea transcrierii manuale a comenzilor
**Status:** Demo în construcție — Pașii 1–3 finalizați, Pasul 4 în curs
**Data start:** 29.05.2026

---

## Problema rezolvată

~26 de magazine proprii în nordul și centrul Moldovei trimit comenzile spre
abator prin **Viber** (scris de mână sau foto). Un operator le transcrie manual
pe foaie / în Paint și le printează.

**Obiectiv:** vânzătoarea introduce comanda structurat de pe telefon, operatorul
o primește gata formatată și doar o printează — transcrierea dispare complet.

**Obiectiv imediat:** demo funcțional pentru a convinge directorul.

---

## Decizii tehnice (sesiunea de planificare)

| Decizie | Ales | Alternativă respinsă |
|---|---|---|
| Mediu PHP | PHP 8.5 (`C:\php`) + MySQL XAMPP | Laragon PHP 8.3 |
| Acces vânzătoare | Link unic per magazin (token) | Login user+parolă |
| Dashboard | Filament v5 (instalat automat cel mai recent) | v3/v4 |
| Bază de date | MySQL / MariaDB 10.4 (XAMPP) | SQLite |

---

## Stack tehnologic

```
Laravel 13 (PHP 8.5)
├── Frontend vânzătoare  → Blade + Livewire 4 + Tailwind CDN  [PWA - Pasul 5]
├── Dashboard operator   → Filament v5                         [Pasul 3 ✅]
├── Panou admin          → Filament v5                         [Pasul 4 ✅]
├── Printare             → Blade @media print                  [Pasul 3 ✅]
└── Notificări           → Bot Telegram                        [Pasul 6]
```

---

## Model de date (MySQL)

```
categories      id | nume | ordine_sortare
products        id | nume | unitate(kg/buc) | category_id | activ
routes          id | nume | zile_livrare(json) | zi_cutoff | ora_cutoff
stores          id | denumire | localitate | adresa | route_id | tip(magazin/angro) | token_acces
users           id | name | email | password | rol(admin/operator/vanzatoare) | store_id | token_acces
orders          id | store_id | user_id | data | route_id | status(trimisa/printata/livrata) | observatii
order_items     id | order_id | product_id | cantitate(decimal 8,3)
```

**Relații cheie:**
- `Category` → hasMany `Product`
- `Route` → hasMany `Store`, hasMany `Order`
- `Store` → belongsTo `Route`, hasMany `Order`, hasMany `User`
- `Order` → belongsTo `Store`/`User`/`Route`, hasMany `OrderItem`
- `OrderItem` → belongsTo `Order`, belongsTo `Product`

---

## Date demo (seeder)

### Categorii (ordine fixă)
1. Refrigerate  2. Congelate  3. Marinate

### Produse (13 total)
| Categorie | Produse |
|---|---|
| Refrigerate | Pulpă întreagă, Piept, Aripi, Gambe, Șolduri, Carcasă întreagă |
| Congelate | Pulpă congelată, Piept congelat, Carcasă congelată |
| Marinate | Pulpă dezosată marinată, Aripi marinate, Gambe marinate, Șolduri marinate |

### Rute (provizorii — editabile din admin)
| Rută | Localități | Cut-off demo |
|---|---|---|
| Ruta Nord-Vest (Lipcani–Edineț) | Lipcani, Briceni ×2, Edineț, Otaci | Duminică 18:00 |
| Ruta Nord (Drochia–Soroca) | Drochia ×3, Soroca ×3, Florești ×3, Dondușeni, Tîrnova ×2 | Luni 18:00 |
| Ruta Centru (Bălți–Rezina) | Bălți ×4, Rezina | Marți 18:00 |
| Ruta Centru-Sud (Orhei–Ungheni) | Orhei, Ungheni ×3 | Miercuri 18:00 |

### Utilizatori demo
- `admin@teovera.md` / `admin1234` — rol admin (Filament)
- `operator@teovera.md` / `operator1234` — rol operator (Filament)
- **32 vânzătoare** repartizate la cele 26 de magazine

---

## Structura fișierelor

```
app/
├── Filament/
│   └── Resources/
│       ├── CategoryResource.php           # Pasul 4
│       ├── CategoryResource/Pages/…
│       ├── OrderResource.php              # Pasul 3
│       ├── OrderResource/Pages/…
│       ├── ProductResource.php            # Pasul 4
│       ├── ProductResource/Pages/…
│       ├── RouteResource.php              # Pasul 4
│       ├── RouteResource/Pages/…
│       ├── StoreResource.php              # Pasul 4
│       ├── StoreResource/Pages/…
│       ├── UserResource.php               # Pasul 4
│       └── UserResource/Pages/…
├── Http/Controllers/
│   └── PrintController.php                # Pasul 3
├── Livewire/
│   └── StoreOrder.php                     # Pasul 2
├── Models/
│   ├── Category.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   ├── Route.php
│   ├── Store.php
│   └── User.php
└── Providers/Filament/
    └── AdminPanelProvider.php             # Pasul 3

database/
├── migrations/                            # Pasul 1 (10 fișiere)
└── seeders/                               # Pasul 1 (5 seedere)

resources/views/
├── filament/modals/order-detail.blade.php # Pasul 3
├── layouts/vanzatoare.blade.php           # Pasul 2
├── livewire/store-order.blade.php         # Pasul 2
└── print/
    ├── order.blade.php                    # Pasul 3
    └── bulk.blade.php                     # Pasul 3

routes/
└── web.php
```

---

## Pasul 1 — Proiect + bază de date ✅

### Ce s-a făcut
1. Activat extensiile `intl` și `gd` în `C:\php\php.ini`
2. Pornit MySQL XAMPP (MariaDB 10.4.32, port 3306)
3. Creat baza de date `teovera_comenzi`
4. `composer create-project laravel/laravel` → Laravel 13
5. `.env` configurat: MySQL, sesii în fișier, queue sync
6. 10 migrații rulate (`migrate:fresh --seed`)
7. Seeder complet: 3 categorii, 13 produse, 4 rute, 26 magazine, 34 utilizatori

### Note tehnice migrații
Ordinea FK-urilor în migrații:
`users` (fără FK) → `categories` → `routes` → `products` → `stores`
→ `add_store_fk_to_users` → `orders` → `order_items`

### Verificare
```bash
php artisan tinker
>>> Category::count()   # 3
>>> Product::count()    # 13
>>> Route::count()      # 4
>>> Store::count()      # 26
>>> User::where('rol','vanzatoare')->count()  # 32
```

---

## Pasul 2 — Interfața vânzătoarei (Livewire) ✅

### URL de acces
```
http://localhost:8000/comanda/{token_acces_magazin}
```
Exemplu: `http://localhost:8000/comanda/xf8dMKPIFSuxjP1K5duwu6A21U5RSfie`

### Ce face interfața
- Catalog grupat: **Refrigerate → Congelate → Marinate** (ordine fixă), sortat alfabetic
- Butoane `−` / `+` (step 0.5 kg sau 1 buc) + input numeric direct
- Input colorat roșu când cantitatea > 0
- Dropdown vânzătoare (per magazin), textarea observații
- Buton „Trimite" cu spinner, ecran confirmare cu rezumat
- Buton „Modifică" (dacă nu a expirat cut-off-ul)
- Ecran blocat după cut-off cu mesaj clar

### Reguli implementate
- Catalog închis — niciun câmp text liber
- Magazinul recunoscut automat din token (fără login)
- Editare blocată după cut-off-ul rutei
- Comandă existentă pentru azi → preîncărcată
- Actualizare comandă existentă (nu duplicat)

### Bug rezolvat
**MethodNotFoundException: Public method [float] not found**
- Cauza: `wire:click` cu expresii ca `(float)` — Livewire parsează `float` ca
  nume de metodă PHP
- Fix: metode explicite `incrementQty($productId, $step)` și
  `decrementQty($productId, $step)` în `StoreOrder.php`

---

## Pasul 3 — Dashboard operator (Filament) ✅

### Acces
```
http://localhost:8000/admin/login
```
- `admin@teovera.md` / `admin1234`
- `operator@teovera.md` / `operator1234`

### Dashboard comenzi
- Tabel grupat după rută (colapsibil)
- Filtre: **Doar azi** (activ implicit), Rută, Status
- Coloane: Data, Magazin, Localitate, Vânzătoare, Nr. produse, Status (badge), Ora
- Acțiuni per rând:
  - **Detalii** → modal cu tabel produse + observații + buton print
  - **Printează** → deschide pagina A4 în tab nou
  - **Printată ✓** → schimbă status (vizibil doar pe `trimisa`)
  - **Livrată ✓** → schimbă status
- Bulk actions: **Printează selectate**, **Marchează ca printate**

### Printare A4
- URL: `/print/order/{id}` (o comandă) · `/print/orders?ids=1,2,3` (multiple)
- Header Avicola-Teovera (roșu), bloc info magazin/rută/vânzătoare
- Tabel produse sortat pe categorii, rând total
- Câmpuri semnătură: Operator / Șofer / Vânzătoare
- `@media print`: butonul de print dispare, culori exacte păstrate
- Print multiplu: câte o pagină A4 per comandă (`page-break-after: always`)

### Note tehnice Filament v5
Filament v5 are API-uri diferite față de v3/v4:

| Element | v3/v4 | v5 |
|---|---|---|
| Form | `Filament\Forms\Form` | `Filament\Schemas\Schema` |
| Action | `Filament\Tables\Actions\Action` | `Filament\Actions\Action` |
| BulkAction | `Filament\Tables\Actions\BulkAction` | `Filament\Actions\BulkAction` |
| `$navigationGroup` | `?string` | `UnitEnum\|string\|null` |
| `$navigationIcon` | `?string` | `BackedEnum\|string\|null` |

Proprietățile cu tip nou nu pot fi redeclarate în PHP 8.5 — soluție: override
prin metode statice `getNavigationGroup()` / `getNavigationIcon()` folosind
tipurile globale `\UnitEnum` / `\BackedEnum` (cu backslash prefix).

---

## Pasul 4 — Panou admin CRUD (Filament) ✅

### Resurse create

**CategoryResource** — grup „Catalog"
- Listă cu `ordine_sortare` și număr produse asociate
- Creare/editare: `nume`, `ordine_sortare`

**ProductResource** — grup „Catalog"
- Listă cu categorie (badge colorat), unitate, status activ/inactiv
- Creare/editare: `nume`, `categorie` (select), `unitate` (kg/buc), `activ` (toggle)
- Filtru: după categorie, după status activ
- Acțiune rapidă: toggle activ/inactiv direct din tabel

**RouteResource** — grup „Configurare"
- Listă cu zile de livrare și cut-off
- Creare/editare: `nume`, `zile_livrare` (checkboxuri luni–duminică),
  `zi_cutoff` (select), `ora_cutoff` (time picker)
- Coloană „Magazine" cu numărul de magazine per rută

**StoreResource** — grup „Configurare"
- Listă cu localitate, rută, tip (badge magazin/angro), token (truncat)
- Creare/editare: `denumire`, `localitate`, `adresa`, `route_id` (select), `tip`
- Token generat automat la creare, afișat read-only la editare
- Acțiune: „Copiază link comandă" → copiază URL-ul vânzătoarei în clipboard

**UserResource** — grup „Configurare"
- Listă cu rol (badge colorat), magazin asociat, email
- Creare/editare: `name`, `email`, `password` (hash automat), `rol`,
  `store_id` (select, vizibil doar pentru vânzătoare)
- Filtru: după rol, după magazin

### Grupuri de navigare Filament
```
Comenzi       → OrderResource
Catalog       → CategoryResource, ProductResource
Configurare   → RouteResource, StoreResource, UserResource
```

### Permisiuni pe rol
| Resursă | Admin | Operator |
|---|---|---|
| Orders (vizualizare, status) | ✅ | ✅ |
| Orders (ștergere) | ✅ | ❌ |
| Products / Categories | ✅ | ❌ |
| Routes / Stores / Users | ✅ | ❌ |

---

## Pași următori

| Pas | Descriere | Status |
|---|---|---|
| **5** | PWA: `manifest.json` + service worker, instalabilă pe telefon | ⬜ |
| **6** | Notificări bot Telegram la primirea comenzii | ⬜ |

---

## Comenzi utile

```bash
# Repornire completă bază de date + date demo
php artisan migrate:fresh --seed

# Pornire server dev
php artisan serve --port=8000

# Pornire MySQL XAMPP (dacă nu rulează)
Start-Process "C:\xampp\mysql\bin\mysqld.exe" `
  -ArgumentList "--defaults-file=`"C:\xampp\mysql\bin\my.ini`"" `
  -WindowStyle Hidden

# Listare rute înregistrate
php artisan route:list
php artisan route:list --path=admin
php artisan route:list --path=print
```

---

## Note tehnice generale

- **PHP 8.5** e foarte recent — dacă apar incompatibilități cu pachete noi,
  fallback: PHP 8.3 din Laragon (`C:\laragon\bin\php\php-8.3.28`)
- **Livewire 4**: `wire:click` nu acceptă expresii PHP inline — orice logică
  merge în metode explicite ale componentei
- **Filament v5** schimbă semnificativ namespace-urile față de v3/v4 (vezi tabel)
- **Tailwind CDN** în layout-ul vânzătoarei — suficient pentru demo,
  de înlocuit cu build Vite înainte de producție
- Rutele de livrare și cut-off-urile sunt **provizorii** — se ajustează
  din panoul admin după confirmarea cu directorul
- Token-ul fiecărui magazin: tabela `stores` → coloana `token_acces`
