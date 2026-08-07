# GitHub sync i produkcijski deployment

## Preporučeni model

GitHub je jedini izvor istine za kod teme:

1. izmjena se radi lokalno ili u posebnoj GitHub grani
2. pull request pokreće provjeru i izrađuje instalacijski ZIP
3. nakon spajanja u `main`, povećana verzija automatski postaje GitHub Release
4. produkcijski WordPress preuzima novu verziju kroz Git Updater

Na serveru se tema ne uređuje kroz WordPress Theme File Editor, FTP ili hosting file manager. Takve izmjene GitHub ne može pouzdano vratiti lokalno i sljedeće ažuriranje bi ih prepisalo.

## Prvi prijenos na server

1. Izvezi Local web i uvezi ga na server uobičajenim postupkom hostinga.
2. Provjeri da je aktivna tema `ingbiro`.
3. Instaliraj i aktiviraj plugin [Git Updater](https://git-updater.com/).
4. U Git Updater postavkama provjeri da je tema povezana s repozitorijem:
   `mateoAtDegordian/inzenjerski-biro-wordpress-theme`.
5. Za javni repozitorij GitHub token nije nužan, ali ga se može dodati kako bi se izbjeglo GitHub API ograničenje.
6. U `wp-config.php` na produkciji preporučeno je dodati:

   ```php
   define( 'DISALLOW_FILE_EDIT', true );
   ```

   Time se onemogućuje slučajno uređivanje PHP/CSS datoteka iz WordPress administracije.

## Svaka sljedeća izmjena

1. Napravi novu granu iz svježeg `main`:

   ```bash
   git switch main
   git pull --ff-only
   git switch -c feature/opis-izmjene
   ```

2. Uredi temu i provjeri lokalni prikaz.
3. Povećaj verziju na oba mjesta:
   - `Version` u `style.css`
   - `INGBIRO_VERSION` u `functions.php`
4. Commitaj, pushaj i spoji pull request u `main`.
5. GitHub Actions automatski izrađuje Release ZIP naziva:
   `inzenjerski-biro-wordpress-theme-<verzija>.zip`.
6. Na produkciji otvori **Nadzorna ploča → Ažuriranja** i pokreni ažuriranje teme.

Ako Git Updater nije dostupan, isti ZIP se može preuzeti iz GitHub Releases i ručno učitati kroz **Izgled → Teme → Dodaj novu → Učitaj temu**.

## Što se sinkronizira

- PHP predlošci i funkcionalnost teme
- CSS, JavaScript, slike, SVG ikone, fontovi i video asseti u temi
- `theme.json`, Gutenberg patterns i registracija CMS modela
- paketirani Dompdf potreban za PDF izvoz

## Što se ne sinkronizira Gitom

- WordPress baza i sadržaj stranica
- Media Library i `wp-content/uploads`
- Forminator obrasci, postavke i prijave
- korisnici, lozinke i hosting konfiguracija
- produkcijski cache i SMTP/API ključevi

Za te podatke koristi se backup/migracija hostinga, ne Git repozitorij.

## Povratak prethodne verzije

1. Napravi backup baze i `wp-content` prije većih ažuriranja.
2. U GitHub Releases preuzmi ZIP prethodne stabilne verzije.
3. Uploadaj prethodni ZIP kroz WordPress ili vrati temu iz hosting backupa.
4. Problem ispravi u novoj Git grani; ne popravljaj samo produkcijsku kopiju.

## Opcionalni potpuno automatski deploy

Ako hosting daje SSH pristup, može se dodati GitHub `production` environment i ručni Actions workflow koji `rsync`-om šalje samo instalacijski paket teme. Environment može zahtijevati odobrenje i čuva server ključeve izvan repozitorija. To je dobra druga faza nakon što znamo hosting, SSH host, korisnika i točnu putanju do `wp-content/themes/ingbiro`.
