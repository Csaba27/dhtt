# DHTT – Dél‑Hargita Teljesítménytúra Webalkalmazás

A **DHTT** egy Laravel Livewire alapú webalkalmazás, amelynek célja túrák és események professzionális menedzselése. A webalkalmazás a **Laravel**, **Livewire** és **Tailwind CSS** technológiákra épül, felhasználva a [Laravel AdminTW](https://laraveladmintw.com/v5) starter kitet és több kiegészítő csomagot.

## Főbb funkciók

- Túra események kezelése
- Felhasználói regisztráció nyitott eseményekre
- Korábbi események archívuma
- Admin felület események, túrák, jelentkezők menedzselésére
- Statisztikák megtekintése
- Képek feltöltése eseményekhez
- Beállítások kezelése
- Mindezen felül a Laravel AdminTW v5 biztosítja a rendszer alapvető funkcióit

## Technológiák

- Laravel 12.x
- Livewire 3.x
- Alpine.js
- Tailwind CSS 4.x
- Vite
- Laravel AdminTW (v5) [Dokumentáció](https://laraveladmintw.com)
- PHP ^8.2


## Használt csomagok

- [Spatie Laravel-permission](https://spatie.be/docs/laravel-permission/v6/introduction) – jogosultságkezelés
- [Spatie Laravel-medialibrary](https://spatie.be/docs/laravel-medialibrary/v11/introduction) – képfeltöltés és galéria
- [maatwebsite/excel](https://laravel-excel.com/) – export és import Excel fájlokkal
- [Laravel Livewire Wizard](https://github.com/spatie/laravel-livewire-wizard) – multi-step űrlapok
- [Jodit rich text editor](https://github.com/xdan/jodit) – szöveges tartalmak szerkesztéséhez


## Telepítés

A projekt elindításához az alábbi parancsokat kell futtatni:

```bash
# 1. Klónozd a repót
git clone https://github.com/pallaszlo/dhtt.git
cd dhtt
````

```bash
# 2. PHP függőségek telepítése
composer install
````

```bash
# 3. Környezeti változók beállítása
cp .env.example .env
# Szerkeszd az .env fájlt (adatbázis, levélküldés stb.)
````

```bash
# 4. Node.js függőségek telepítése és assetek buildelése
npm install && npm run build
````

```bash
# 5. Alkalmazás kulcs generálása
php artisan key:generate
````

```bash
# 6. Storage symlink létrehozása
php artisan storage:link
````

```bash
# 7. Adatbázis migráció futtatása
php artisan migrate
````

```bash
# 8. Adatbázis feltöltése
php artisan db:seed
````

## Opcionális parancsok
```bash
# Faker adatok feltöltése
php artisan db:seed --class=DhttFakerSeeder
````

```bash
# Optimalizáció - éles oldalhoz elengedhetetlen
php artisan optimize
````

```bash
# Fejlesztői szerver indítása
php artisan serve
````


## AdminTW témáról

Ez a projekt a **Laravel AdminTW v5** starterkitet használja, amely egy teljes admin felületet biztosít.

Funkciók:
- Felhasználók és jogosultságok kezelése
- Kétfaktoros hitelesítés
- Audit naplók
- Light & Dark mód
- Pest PHP alapú tesztek

Részletes dokumentáció: [https://laraveladmintw.com](https://laraveladmintw.com)
