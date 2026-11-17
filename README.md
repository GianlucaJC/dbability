# DbAbility

Piattaforma digitale **Daniela Belli Abilità** per la vendita di risorse PDF dedicate a genitori e bambini. Il progetto usa Laravel 10, Vue 3/Inertia, MySQL e Stripe Checkout.

## Funzioni implementate

- Registrazione e accesso dei genitori (facoltativi: l'acquisto funziona anche da ospiti).
- Catalogo di PDF con prezzo in euro.
- Pagamento Stripe Checkout: carte e gli ulteriori metodi abilitati nel pannello Stripe (ad esempio PayPal, Apple Pay e Google Pay, senza account separati né codice aggiuntivo).
- Acquisto ospite (guest checkout): Stripe raccoglie l'email e il cliente riceve un link di download firmato via email, riutilizzabile.
- Ordini registrati lato server e confermati esclusivamente tramite webhook Stripe firmato; se l'email dell'acquirente ospite corrisponde a un account esistente, l'ordine viene agganciato automaticamente a quell'account.
- Email di conferma ordine: link di download per il PDF, riepilogo spedizione per il cartaceo.
- Download del PDF tramite link firmato (ospiti) o area personale (utenti registrati).
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

Se l'applicazione è pubblicata in una sottocartella (ad esempio `https://dominio.it:8013/dbability/public`), impostare sia `APP_URL` sia `ASSET_URL` con quell'URL completo e senza slash finale, quindi eseguire `php artisan optimize:clear` e `php artisan config:cache`.

## Caricamento dei PDF

I file non devono essere pubblici. Copiarli in `storage\app\private\pdfs`, poi impostare il relativo percorso nel campo `file_path` del prodotto, ad esempio `pdfs\giochi-movimento.pdf`. In produzione usare barre `/` nel valore memorizzato nel database (`pdfs/giochi-movimento.pdf`).

I tre prodotti creati dal seeder sono segnaposto: aggiornare titoli, descrizioni, prezzi e percorsi quando saranno disponibili i materiali definitivi.

## Configurazione Stripe

### Account: sviluppatore o cliente?

- **Sviluppo (modalità test)**: lo sviluppatore crea un account Stripe gratuito su <https://dashboard.stripe.com/register>. La modalità test è attiva subito, senza verifica aziendale: bastano le chiavi `sk_test_...` e le carte di prova (es. `4242 4242 4242 4242`).
- **Produzione (modalità live)**: l'account deve appartenere a chi incassa (il cliente finale), con i propri dati legali, IBAN e P.IVA. Il cliente invita lo sviluppatore come membro del team con ruolo "Sviluppatore". Al go-live si sostituiscono solo `STRIPE_SECRET` e `STRIPE_WEBHOOK_SECRET` nel `.env`: nessuna modifica al codice.

### Passi

1. Inserire la chiave segreta appropriata in `STRIPE_SECRET`; non inserirla mai nel frontend o nel repository.
2. Nel pannello Stripe abilitare i metodi di pagamento desiderati per EUR (PayPal, Apple Pay e Google Pay si attivano con un click, senza account separati).
3. Creare un endpoint webhook `https://tuo-dominio.it/stripe/webhook`, selezionando l’evento `checkout.session.completed`.
4. Copiare il signing secret dell’endpoint in `STRIPE_WEBHOOK_SECRET`.

Il webhook è indispensabile: il ritorno del browser alla pagina di ringraziamento non abilita il download.

### Webhook in locale (Stripe CLI)

In locale Stripe non può raggiungere `localhost`: usare la [Stripe CLI](https://docs.stripe.com/stripe-cli):

```powershell
stripe login
stripe listen --forward-to http://localhost/stripe/webhook
```

Il comando `listen` stampa un `whsec_...` da copiare in `STRIPE_WEBHOOK_SECRET`. Per simulare un pagamento completo usare il checkout con la carta di test `4242 4242 4242 4242` (qualsiasi scadenza futura e CVC).

### Email di conferma

Dopo il pagamento il sito invia un'email con il link di download (PDF) o il riepilogo spedizione (cartaceo). In locale configurare `MAIL_*` nel `.env` (ad esempio [Mailpit](https://mailpit.axllent.org/) oppure `MAIL_MAILER=log` per scrivere le email in `storage/logs/laravel.log`). In produzione usare un provider SMTP reale e impostare `MAIL_FROM_ADDRESS` con il dominio del sito.

## Modalità demo

Nell'ambiente locale `DEMO_CHECKOUT=true` è attivo: il pulsante **Prova il checkout** funziona senza registrazione, mostra il riepilogo della risorsa e permette di aprire/scaricare un PDF di prova. Non contatta Stripe, non addebita nulla e non crea ordini.

Prima della pubblicazione impostare obbligatoriamente `DEMO_CHECKOUT=false`; a quel punto i visitatori saranno inviati al checkout Stripe reale, anche senza registrarsi (guest checkout).

## Pubblicazione su produzione

- Il document root del server deve essere esclusivamente `public`.
- Impostare `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://tuo-dominio.it` e una `APP_KEY` distinta.
- Configurare HTTPS e un database MySQL con backup.
- Eseguire `composer install --no-dev --optimize-autoloader`, `php artisan migrate --force`, `php artisan config:cache`, `php artisan route:cache` e `npm.cmd run build`.
- Verificare il webhook Stripe in modalità test prima di usare chiavi live.

Prima della pubblicazione occorre inoltre predisporre testi legali, privacy/cookie policy, condizioni di vendita, politica di rimborso e dati fiscali, con supporto professionale appropriato.
