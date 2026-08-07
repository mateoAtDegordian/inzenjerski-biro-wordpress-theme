# Inženjerski biro WordPress tema

Prijenosna custom WordPress tema izrađena prema Figma dokumentu **UI Design Version 2** za Inženjerski biro.

## Što je uključeno

- responsive naslovnica, O nama, Konzalting, Pravni portal LING, Savjetovanja i edukacije, Arhiva, Kontakt, Newsletter i Karijera
- detalj događanja i detalj radne pozicije
- Forminator obrasci za kontakt, newsletter, brzu newsletter prijavu, edukacije i posao
- WordPress administracija za događanja, lokaliziranu arhivu savjetovanja, konzultantske usluge, pozicije i zaprimljene web prijave
- Gutenberg sadržaj za edukacije i karijere, uz dodatna strukturirana polja za datum, format, predavača, lokaciju i kotizaciju
- svi obrasci preko Forminatora, s poljima, obavijestima, webhooks i integracijama koje se mogu mijenjati bez izmjene koda
- pravi PDF dokument događanja generiran iz aktualnih CMS podataka i Gutenberg sadržaja
- isti izvor slike za karticu događanja, detalj i PDF dokument
- zajednički HR/EN predlošci: engleske stranice prate istu strukturu i dizajnerske ispravke kao hrvatske, uz vlastiti prevedeni sadržaj
- biblioteka Gutenberg patterns za kartice, tekst, sliku s tekstom i CTA sekcije
- desktop i mobilna navigacija te pristupačni accordion elementi
- typewriter naslovi, animirani povijesni foto-stack i rotirajući Figma zupčanici uz podršku za smanjeno kretanje
- lokalno spremljeni originalni Figma asseti; tema ne ovisi o privremenim Figma URL-ovima
- opcionalne lokalne Helvetica webfont datoteke koje se učitavaju kada postoji licencirani paket; javni repozitorij sadrži samo upute i sistemske fallbackove

## Instalacija

1. Kopirajte mapu `ingbiro` u `wp-content/themes/`.
2. U WordPress administraciji otvorite **Izgled → Teme** i aktivirajte **Inženjerski biro**.
3. Pri prvoj aktivaciji tema kreira potrebne stranice i početni primjer događanja/pozicije.
4. U **Postavke → Čitanje** provjerite da je “Naslovnica” odabrana kao statična početna stranica.
5. U **Izgled → Izbornici** po želji kreirajte i dodijelite glavni meni.
6. Instalirajte i aktivirajte besplatni plugin **Forminator**. Tema automatski priprema obrasce za kontakt, newsletter, edukacije i karijere; svaki se dalje uređuje u **Forminator → Forms**.

Postojeći sadržaj se pri ponovnoj aktivaciji ne briše niti prepisuje.

## Uređivanje sadržaja

- **Događanja**: naslov, Gutenberg sadržaj, istaknuta slika, strojni i prikazni datum, format, trajanje, početak, lokacija, predavač, kotizacija i obrazac za prijavu. Opcija **Prikaži u arhivi** uklanja događanje iz aktualnog popisa, gasi prijavu i prikazuje njegovu postojeću lokalnu stranicu u arhivi.
- **Arhiva savjetovanja**: uvezeni stari zapisi imaju lokalne detaljne stranice pod čitljivim, naslovnim `/arhiva/.../` URL-ovima, zadržan program i lokalno spremljene dostupne slike. Stari filename URL-ovi trajno se preusmjeravaju na nove SEO slugove. Poveznica **Otvori izvorni zapis** otvara potpuno lokalnu statičnu kopiju s nepromijenjenim legacy URL-om, bez obrazaca i gumba za prijavu, pa arhiva ne ovisi o starom webu. Prikaz automatski spaja stare zapise i arhivirana nova događanja te ih grupira po godinama.
- **Konzultantske usluge**: naslov, redoslijed i puni Gutenberg sadržaj svakog accordion panela.
- **Pozicije**: naslov, Gutenberg sadržaj, istaknuta slika, lokacija/način rada i opcionalni Forminator obrazac.
- **Forminator → Submissions**: kontaktni upiti, newsletter pretplate, prijave na događanja i prijave za posao.
- **Stranice → English**: engleska verzija koristi iste predloške kao hrvatska, dok su engleska događanja namjerno izostavljena.
- **Engleska Karijera i Newsletter**: koriste iste shared predloške i Forminator obrasce, s engleskim poljima, privolama i validacijama.
- **Umetanje blokova → Patterns → Inženjerski biro sekcije**: gotove modularne sekcije za nove stranice.
- Opći WordPress sadržaj i politika privatnosti uređuju se kroz **Stranice**.

E-mail obavijesti koristi Forminator preko standardnog WordPress `wp_mail()`. Na produkciji je preporučena konfiguracija SMTP plugina ili transakcijskog mail servisa. Analytics/API priključci dokumentirani su u [FORM-INTEGRATIONS.md](FORM-INTEGRATIONS.md).

## Razvoj

Tema namjerno nema Node/Tailwind build korak: koristi Gutenberg, `theme.json` i centralizirane CSS komponente, pa je instalacija prenosiva kao obična WordPress tema. Potrebni su WordPress 6.4+ i PHP 8.0+. Paketirani Dompdf 3.1.5 nalazi se u `vendor/dompdf/dompdf` kako bi PDF radio odmah nakon instalacije teme.

Licencirani Helvetica webfontovi na serveru se drže u `wp-content/uploads/ingbiro-fonts/`, izvan javnog release ZIP-a. Tema ih automatski učitava i ta lokacija ostaje sačuvana pri Git Updater nadogradnjama.

Brza PHP provjera:

```bash
find . -name '*.php' -print0 | xargs -0 -n1 php -l
```

Ponovljivi uvoz ili osvježavanje stare arhive u lokalnoj instalaciji:

```bash
php scripts/import-legacy-archive.php
```

Uvoz koristi kombinaciju izvornog linka, datuma i naslova kao stabilni identitet zapisa, pa je naredbu sigurno ponovno pokrenuti. Statične kopije i njihovi asseti spremaju se u `wp-content/uploads/ingbiro-archive/`, zato ih pri prijenosu na produkciju treba prenijeti zajedno s bazom podataka.

## GitHub sync i produkcija

GitHub je jedini izvor istine za kod teme. Pull requestovi automatski provjeravaju PHP i JavaScript te izrađuju instalacijski ZIP, a svaki novi `main` commit s povećanom verzijom automatski objavljuje GitHub Release.

Na produkciji je preporučen plugin **Git Updater**. Tema već sadrži potrebne GitHub headere pa WordPress može ponuditi novu verziju kroz standardni ekran **Nadzorna ploča → Ažuriranja**. Baza, uploadi, Forminator prijave i sadržaj stranica nisu dio Git repozitorija i ostaju odvojeni po okruženju.

Detaljan postupak prvog prijenosa, budućih izmjena i povrata verzije nalazi se u [DEPLOYMENT.md](DEPLOYMENT.md).

## Figma izvori

- [Inženjerski biro Copy — UI Design Version 2](https://www.figma.com/design/kKEePHOBf5vMKPNUX0Tt5s/Inzenjerski-biro--Copy-?node-id=16249-301&p=f&m=dev)
- [Originalni dizajnerski dokument](https://www.figma.com/design/BSH0TyUZcQb7S6UVCNrlVw/Inzenjerski-biro?node-id=16249-301&p=f)

## Licenca

GNU General Public License v2 ili novija. Pogledajte `LICENSE`.
