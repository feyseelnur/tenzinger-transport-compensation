# Compensation Report Generation

Dit project genereert een compensatieverslag voor werknemers op basis van hun woon-werkverkeer. Het maakt gebruik van Laravel, queues, en CSV-bestanden voor het rapporteren.

## Installatie

Volg deze stappen om het project lokaal op te zetten.

### Vereisten

- PHP >= 8.2
- Composer
- MySQL of een andere database naar keuze

### Stappen

1. **Kloon de repository:**
   Kloon de repository naar je lokale machine en navigeer naar de projectdirectory.

2. **Installeer de afhankelijkheden:**
   Voer het commando `composer install` uit om alle benodigde PHP-pakketten te installeren.

3. **Kopieer het `.env.example` bestand naar `.env`:**
   Maak een kopie van het `.env.example` bestand en noem het `.env`.

4. **Configureer je `.env` bestand:**
   Vul de database-instellingen in je `.env` bestand in, zoals de databaseverbinding, host, poort, database naam, gebruikersnaam en wachtwoord.

5. **Genereer de applicatiesleutel:**
   Voer het commando `php artisan key:generate` uit om een unieke applicatiesleutel te genereren.

6. **Voer de migraties en seeders uit:**
   Voer het commando `php artisan migrate --seed` uit om de database te migreren en te vullen met voorbeeldgegevens.

## Testen

Zorg ervoor dat je een `.env.testing` bestand maakt en configureert voor je testdatabase. Een voorbeeld configuratie voor MySQL:

```env
APP_ENV=testing
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tenzinger_transport_compensation_test
DB_USERNAME=root
DB_PASSWORD=secret
```

### Tests uitvoeren

Voer het commando `php artisan test` uit om de tests te draaien. Dit zorgt ervoor dat de functionaliteit van de applicatie grondig wordt getest.

## Gebruik

### API Endpoints

1. **Genereer compensatieverslag**
    - Endpoint: `POST /api/generate-report`
    - Parameters:
        - `year`: Het jaar waarvoor het verslag gegenereerd moet worden (vereist).
        - `debug`: Boolean om debug-modus in te schakelen (optioneel).
        - `wait_for_result`: Boolean om te wachten op het resultaat (optioneel).

2. **Haal compensatieverslag op**
    - Endpoint: `GET /api/retrieve/{filename}`
    - Parameters:
        - `filename`: De naam van het te downloaden bestand (vereist).

### Queue Configuratie

Standaard wordt de `sync` queue driver gebruikt. Voor productie kun je de queue driver configureren naar bijvoorbeeld `redis` of `database` in je `.env` bestand. Om de queues te verwerken, moet je het volgende commando uitvoeren:

```bash
php artisan queue:work
```

## Directory Structuur

- **Controllers:**
    - Behandelt API-verzoeken voor het genereren en ophalen van compensatieverslagen.

- **Jobs:**
    - Bevat de logica voor het genereren van het compensatieverslag in de achtergrond.

- **Requests:**
    - Valideert inkomende API-verzoeken.

- **Seeders:**
    - Vul de database met voorbeeldgegevens voor testing en development.

- **Factories:**
    - Genereert model records voor testing.

- **Migrations:**
    - Beheert de database schema wijzigingen.

## Licentie

Dit project is gelicentieerd onder de MIT-licentie. Zie het `LICENSE`-bestand voor meer informatie.

---

Door deze stappen te volgen en de configuratie te voltooien, kun je de applicatie lokaal draaien en testen. Neem contact op via de repository issues voor eventuele vragen of hulp.
