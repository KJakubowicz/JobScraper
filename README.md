# Opis aplikacji

Aplikacja odpowiada za pobieranie danych ze stron internetowych, ich przetwarzanie oraz zapis do bazy danych. Cały proces wymiany danych obsługiwany jest przez aplikację konsolową. Dzięki temu rozwiązaniu możliwe jest zaplanowanie uruchamiania komend w CRONie, co pozwala na automatyzację procesu bez konieczności ręcznej interwencji.

## Instalacja

Aby zainstalować aplikację, należy wykonać poniższe kroki:

1. Sklonuj repozytorium:
    ```bash
    git clone <repo-url>
    ```

2. Zainstaluj zależności za pomocą Composer:
    ```bash
    composer install
    ```

3. Skonfiguruj plik `.env` zgodnie z wymaganiami projektu (np. dane do bazy danych, inne ustawienia).

4. Uruchom migracje bazy danych:
    ```bash
    php artisan migrate
    ```

5. Uruchom testy: (następuje czyszczenie bazy)
    ```bash
    php artisan test
    ```

## Użycie

Aplikacja konsolowa umożliwia pobieranie i przetwarzanie danych ze strony internetowej. Do uruchomienia aplikacji służy poniższa komenda:
```bash
php artisan app:web-scraper {provider}
```
Parametr `{provider}` wskazuje konfigurację danej strony, którą aplikacja powinna przetworzyć.

Na końcu działania komendy aplikacja zwróci liczbę rekordów, które udało się pobrać i zapisać do bazy danych.

Aktualnie jest obsłużony provider = workable dla strony https://apply.workable.com/testronic/

## Funkcjonalności

- Główna komenda aplikacji konsolowej (`php artisan app:web-scraper {provider}`) umożliwia automatyczne pobieranie i zapisywanie danych do bazy.

- Przeglądanie wyników:
    - `/` – wyświetla listę ogłoszeń, umożliwia filtrowanie wyników.
    - `/{jobOfferId}/history` – sprawdza historię aktualizacji danego ogłoszenia.
    - `/process-logs` – podgląd procesów związanych z przetwarzaniem danych.
    - `/version/active/{versionId}` – ustawia wersję ogłoszenia jako aktywną.
    - `/version/delete/{versionId}` – usuwa wybraną wersję ogłoszenia.

## Wersje ofert

Każda oferta posiada wersje, które zmieniają się podczas działania aplikacji konsolowej. Dzięki temu możliwe jest śledzenie zmian w danych w trakcie przetwarzania. Wersję można usuwać oraz ustawiać jako aktywną. Aktywna wersja pokazuje się na głownej liście ofert. Jeżeli oferta nie ma aktywnej wersji, to nie pojawia się na liście.

## Rozszerzalność
Dzięki zastosowanej fabryce aplikacja jest łatwa do rozszerzenia o nowe źródła danych (providerów), co pozwala na zachowanie spójności i elastyczności aplikacji.
