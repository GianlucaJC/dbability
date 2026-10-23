# DbAbility

Piattaforma digitale **Daniela Belli Abilità** per la vendita di risorse PDF dedicate a genitori e bambini. Il progetto usa Laravel 10, Vue 3/Inertia, MySQL e Stripe Checkout.

## Funzioni implementate

- Registrazione e accesso dei genitori.
- Catalogo di PDF con prezzo in euro.
- Pagamento Stripe Checkout: carte e gli ulteriori metodi abilitati nel pannello Stripe (ad esempio Apple Pay e Google Pay).
- Ordini registrati lato server e confermati esclusivamente tramite webhook Stripe firmato.
- Download riservato al genitore autenticato che ha completato il pagamento.
- Area personale con le risorse acquistate.

## Requisiti

- PHP 8.1 o superiore (XAMPP 8.1.6 è compatibile).
- Composer 2.
- MySQL/MariaDB.
- Node.js 18 o superiore.
- Account Stripe.

## Avvio in locale con XAMPP

1. Creare un database MySQL vuoto, ad esempio `dbability`.
2. Copiare `.env.example` in `.env` e compilare almeno:

   ```dotenv
   APP_NAME=DbAbility
   APP_URL=http://dbability.test
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dbability
   DB_USERNAME=root
   DB_PASSWORD=
   STRIPE_SECRET=sk_test_...
   STRIPE_WEBHOOK_SECRET=whsec_...
   ```

3. Installare dipendenze, creare tabelle e catalogo iniziale:

   ```powershell
   composer install
   php artisan key:generate
   php artisan migrate --seed
   npm.cmd install
   npm.cmd run build
   ```

4. Durante lo sviluppo eseguire `npm.cmd run dev`. Con Herd configurare il sito sulla cartella del progetto; con XAMPP il VirtualHost deve puntare a `C:\progetti\dbability\public`, con `AllowOverride All` e `mod_rewrite` attivo.

## Caricamento dei PDF

I file non devono essere pubblici. Copiarli in `storage\app\private\pdfs`, poi impostare il relativo percorso nel campo `file_path` del prodotto, ad esempio `pdfs\giochi-movimento.pdf`. In produzione usare barre `/` nel valore memorizzato nel database (`pdfs/giochi-movimento.pdf`).

I tre prodotti creati dal seeder sono segnaposto: aggiornare titoli, descrizioni, prezzi e percorsi quando saranno disponibili i materiali definitivi.

## Configurazione Stripe

1. Inserire la chiave segreta appropriata in `STRIPE_SECRET`; non inserirla mai nel frontend o nel repository.
2. Nel pannello Stripe abilitare i metodi di pagamento desiderati per EUR.
3. Creare un endpoint webhook `https://tuo-dominio.it/stripe/webhook`, selezionando l’evento `checkout.session.completed`.
4. Copiare il signing secret dell’endpoint in `STRIPE_WEBHOOK_SECRET`.

Il webhook è indispensabile: il ritorno del browser alla pagina di ringraziamento non abilita il download.

## Modalità demo

Nell'ambiente locale `DEMO_CHECKOUT=true` è attivo: il pulsante **Prova il checkout** funziona senza registrazione, mostra il riepilogo della risorsa e permette di aprire/scaricare un PDF di prova. Non contatta Stripe, non addebita nulla e non crea ordini.

Prima della pubblicazione impostare obbligatoriamente `DEMO_CHECKOUT=false`; a quel punto i visitatori dovranno accedere e saranno inviati al checkout Stripe reale.

## Pubblicazione su produzione

- Il document root del server deve essere esclusivamente `public`.
- Impostare `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://tuo-dominio.it` e una `APP_KEY` distinta.
- Configurare HTTPS e un database MySQL con backup.
- Eseguire `composer install --no-dev --optimize-autoloader`, `php artisan migrate --force`, `php artisan config:cache`, `php artisan route:cache` e `npm.cmd run build`.
- Verificare il webhook Stripe in modalità test prima di usare chiavi live.

Prima della pubblicazione occorre inoltre predisporre testi legali, privacy/cookie policy, condizioni di vendita, politica di rimborso e dati fiscali, con supporto professionale appropriato.
