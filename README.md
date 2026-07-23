# Inženjerski biro WordPress tema

Prijenosna custom WordPress tema izrađena prema Figma dokumentu **UI Design Version 2** za Inženjerski biro.

## Što je uključeno

- responsive naslovnica, O nama, Konzalting, Pravni portal LING, Savjetovanja i edukacije, Arhiva, Kontakt, Newsletter i Karijera
- detalj događanja i detalj radne pozicije
- prijava na edukaciju i prijava za posao
- WordPress administracija za događanja, pozicije i zaprimljene web prijave
- kontakt i newsletter obrasci koji spremaju prijave u WordPress i šalju obavijest administratoru
- desktop i mobilna navigacija te pristupačni accordion elementi
- lokalno spremljeni originalni Figma asseti; tema ne ovisi o privremenim Figma URL-ovima

## Instalacija

1. Kopirajte mapu `ingbiro` u `wp-content/themes/`.
2. U WordPress administraciji otvorite **Izgled → Teme** i aktivirajte **Inženjerski biro**.
3. Pri prvoj aktivaciji tema kreira potrebne stranice i početni primjer događanja/pozicije.
4. U **Postavke → Čitanje** provjerite da je “Naslovnica” odabrana kao statična početna stranica.
5. U **Izgled → Izbornici** po želji kreirajte i dodijelite glavni meni.

Postojeći sadržaj se pri ponovnoj aktivaciji ne briše niti prepisuje.

## Uređivanje sadržaja

- **Događanja**: naslov, opis, istaknuta slika, datum, format, trajanje, predavač i kotizacija.
- **Pozicije**: naslov, opis, istaknuta slika i lokacija/način rada.
- **Web prijave**: privatni zapisi kontaktnih upita, newsletter pretplata, prijava na događanja i prijava za posao.
- Opći WordPress sadržaj i politika privatnosti uređuju se kroz **Stranice**.

E-mail obavijesti koriste standardni WordPress `wp_mail()`. Na produkciji je preporučena konfiguracija SMTP plugina ili transakcijskog mail servisa.

## Razvoj

Tema nema build korak ni JavaScript/CSS framework. Potrebni su WordPress 6.4+ i PHP 8.0+.

Brza PHP provjera:

```bash
find . -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Figma izvori

- [Inženjerski biro Copy — UI Design Version 2](https://www.figma.com/design/kKEePHOBf5vMKPNUX0Tt5s/Inzenjerski-biro--Copy-?node-id=16249-301&p=f&m=dev)
- [Originalni dizajnerski dokument](https://www.figma.com/design/BSH0TyUZcQb7S6UVCNrlVw/Inzenjerski-biro?node-id=16249-301&p=f)

## Licenca

GNU General Public License v2 ili novija. Pogledajte `LICENSE`.

