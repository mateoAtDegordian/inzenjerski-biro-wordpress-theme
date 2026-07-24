# Inženjerski biro WordPress tema

Prijenosna custom WordPress tema izrađena prema Figma dokumentu **UI Design Version 2** za Inženjerski biro.

## Što je uključeno

- responsive naslovnica, O nama, Konzalting, Pravni portal LING, Savjetovanja i edukacije, Arhiva, Kontakt, Newsletter i Karijera
- detalj događanja i detalj radne pozicije
- Forminator obrasci za kontakt, newsletter, brzu newsletter prijavu, edukacije i posao
- WordPress administracija za događanja, savjetovanja u arhivi, konzultantske usluge, pozicije i zaprimljene web prijave
- Gutenberg sadržaj za edukacije i karijere, uz dodatna strukturirana polja za datum, format, predavača, lokaciju i kotizaciju
- svi obrasci preko Forminatora, s poljima, obavijestima, webhooks i integracijama koje se mogu mijenjati bez izmjene koda
- pravi PDF dokument događanja generiran iz aktualnih CMS podataka i Gutenberg sadržaja
- isti izvor slike za karticu događanja, detalj i PDF dokument
- uređive engleske Gutenberg stranice importirane iz dostavljenih DOCX dokumenata
- biblioteka Gutenberg patterns za kartice, tekst, sliku s tekstom i CTA sekcije
- desktop i mobilna navigacija te pristupačni accordion elementi
- lokalno spremljeni originalni Figma asseti; tema ne ovisi o privremenim Figma URL-ovima

## Instalacija

1. Kopirajte mapu `ingbiro` u `wp-content/themes/`.
2. U WordPress administraciji otvorite **Izgled → Teme** i aktivirajte **Inženjerski biro**.
3. Pri prvoj aktivaciji tema kreira potrebne stranice i početni primjer događanja/pozicije.
4. U **Postavke → Čitanje** provjerite da je “Naslovnica” odabrana kao statična početna stranica.
5. U **Izgled → Izbornici** po želji kreirajte i dodijelite glavni meni.
6. Instalirajte i aktivirajte besplatni plugin **Forminator**. Tema automatski priprema obrasce za kontakt, newsletter, edukacije i karijere; svaki se dalje uređuje u **Forminator → Forms**.

Postojeći sadržaj se pri ponovnoj aktivaciji ne briše niti prepisuje.

## Uređivanje sadržaja

- **Događanja**: naslov, Gutenberg sadržaj, istaknuta slika, strojni i prikazni datum, format, trajanje, početak, lokacija, predavač, kotizacija i obrazac za prijavu.
- **Arhiva savjetovanja**: naziv, datum i link na preneseni HTML ili vanjski zapis. Prikaz se automatski grupira po godinama.
- **Konzultantske usluge**: naslov, redoslijed i puni Gutenberg sadržaj svakog accordion panela.
- **Pozicije**: naslov, Gutenberg sadržaj, istaknuta slika, lokacija/način rada i opcionalni Forminator obrazac.
- **Forminator → Submissions**: kontaktni upiti, newsletter pretplate, prijave na događanja i prijave za posao.
- **Stranice → English**: uređive engleske stranice složene od standardnih Gutenberg blokova.
- **Umetanje blokova → Patterns → Inženjerski biro sekcije**: gotove modularne sekcije za nove stranice.
- Opći WordPress sadržaj i politika privatnosti uređuju se kroz **Stranice**.

E-mail obavijesti koristi Forminator preko standardnog WordPress `wp_mail()`. Na produkciji je preporučena konfiguracija SMTP plugina ili transakcijskog mail servisa. Analytics/API priključci dokumentirani su u [FORM-INTEGRATIONS.md](FORM-INTEGRATIONS.md).

## Razvoj

Tema namjerno nema Node/Tailwind build korak: koristi Gutenberg, `theme.json` i centralizirane CSS komponente, pa je instalacija prenosiva kao obična WordPress tema. Potrebni su WordPress 6.4+ i PHP 8.0+. Paketirani Dompdf 3.1.5 nalazi se u `vendor/dompdf/dompdf` kako bi PDF radio odmah nakon instalacije teme.

Brza PHP provjera:

```bash
find . -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Figma izvori

- [Inženjerski biro Copy — UI Design Version 2](https://www.figma.com/design/kKEePHOBf5vMKPNUX0Tt5s/Inzenjerski-biro--Copy-?node-id=16249-301&p=f&m=dev)
- [Originalni dizajnerski dokument](https://www.figma.com/design/BSH0TyUZcQb7S6UVCNrlVw/Inzenjerski-biro?node-id=16249-301&p=f)

## Licenca

GNU General Public License v2 ili novija. Pogledajte `LICENSE`.
