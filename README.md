# A test task for the P****A

Refactoring of a technical dept rich code piece.
- Read JSON encoded lines from a given file
- Extract amount, currency and BIN
- Resolve BIN to a Country using BinList service provider
- Exchange amount to EUR using ExchangeRate service provider
- Pick and apply a Country dependent fee

Topics covered:
- Using Symfony Components without the Symfony Framework (DI, Cache, Validator, Serializer, HttpClient)
- Memory-efficient line by line iteration through a file
- JSON and JSON Schema
- Integration of a third-party API (BinList, ExchageRate)
- Money Math
- Docker environment
- Testing (PHPUnit)

# Installation

- Clone a repo
- run `docker-composer build`
- create the `/.env.local` and assign your `exchangeratesapi.io` access key to the `EXCHANGE_RATES_API_KEY`
- run `php app.php input.txt`