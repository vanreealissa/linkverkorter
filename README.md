# Linkverkorter

Maak lange links kort en zie hoe vaak erop geklikt wordt. Gebouwd met Laravel,
met een webinterface én een JSON-API.

## Functies

- Lange link invoeren; `https://` wordt automatisch toegevoegd als het ontbreekt
- Willekeurige code van 6 tekens (zonder verwarrende tekens als 0/O en 1/l) of een eigen code
- Doorsturen via `/{code}` en elke klik registreren
- Statistiekenpagina met een staafdiagram van de kliks per dag en de belangrijkste verwijzers
- JSON-API om links aan te maken en op te vragen, met rate limiting

## API

```bash
curl -X POST http://linkverkorter.test/api/links \
  -H "Accept: application/json" \
  -d url=https://laravel.com/docs \
  -d code=docs
```

```json
{
  "data": {
    "code": "docs",
    "url": "https://laravel.com/docs",
    "short_url": "http://linkverkorter.test/docs",
    "clicks": 0,
    "created_at": "2026-09-28T12:00:00+02:00"
  }
}
```

`GET /api/links/{code}` geeft dezelfde gegevens terug, met het actuele aantal kliks.
Bij een ongeldige invoer krijg je een `422` met de validatiefouten.

## Wat laat dit project zien?

| Onderdeel | Waar |
| --- | --- |
| Route model binding op een eigen kolom (`{link:code}`) | `routes/web.php`, `routes/api.php` |
| Eén Form Request voor web én API | `app/Http/Requests/StoreLinkRequest.php` |
| API Resources | `app/Http/Resources/LinkResource.php` |
| Rate limiting met `throttle` | `routes/api.php` |
| Veilige validatie (alleen http/https, gereserveerde codes) | `StoreLinkRequest` |
| Feature tests voor web en API | `tests/Feature/LinkTest.php` |

## Installeren

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Gebruik je Laravel Herd? Zet de map in `~/Herd` en open http://linkverkorter.test.

## Testen

```bash
php artisan test
```
