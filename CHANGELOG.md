# Changelog

## 1.5.0 — 2026-07-24

- Tema je povezana s GitHub Release distribucijom i Git Updater ažuriranjima iz WordPress administracije.
- Dodani su automatski CI, provjera PHP/JavaScript sintakse i instalacijski ZIP artefakt za svaki pull request.
- Svaki novi `main` commit s povećanom verzijom automatski objavljuje verzionirani GitHub Release ZIP.
- Dokumentiran je preporučeni local → GitHub → server workflow i jasna granica između koda teme i WordPress sadržaja.

## 1.4.3 — 2026-07-24

- Typewriter se sada automatski primjenjuje na prvi H1 svakog glavnog predloška, uključujući HR i EN stranice.
- Svi dekorativni zupčanici, uključujući male oznake sekcija i modularne Gutenberg zupčanike, sada se rotiraju.
- `Reduce Motion` više ne gasi sve efekte: koristi sporiju rotaciju, kraći typewriter i fade izmjenu fotografija.
- Zupčanici na stranici Savjetovanja i edukacije pomaknuti su u slobodan prostor ispod kraće lijeve kartice.

## 1.4.2 — 2026-07-24

- O nama uvod sada koristi tri izvorne Figma fotografije i njihove točne omjere, cropove i poravnanja.
- Parovi zupčanika imaju ujednačen razmak i jasno vidljivu rotaciju u suprotnim smjerovima.
- Foto-stack vidljivo izmjenjuje slike, a natpis Inženjerski biro kontinuirano prolazi iza njega preko cijelog viewporta.
- Smanjen je višak praznog prostora između kartica savjetovanja, zupčanika i popisa edukacija.

## 1.4.1 — 2026-07-24

- Footer sada koristi izvorne Figma SVG ikone za lokaciju, e-mail, telefon i LinkedIn.
- Dodani su hover/focus stateovi te se aktivni jezik ispravno mijenja između HR i EN stranica.
- Adresa, e-mail adrese, telefon i faks sada su stvarne poveznice.
- Gutenberg sekcije na detalju edukacije renderiraju boju preko pune širine viewporta, uz sadržaj poravnan na glavni grid.
- Programski zupčanici pozicionirani su uz stvarni rub ekrana, a prijavna forma proširena je i centrirana.

## 1.4.0 — 2026-07-24

- Dodane typewriter animacije na naslovnici i O nama prema komentarima dizajnerice.
- Dodan animirani foto-stack iz dostavljenih Figma slika, spreman za ukupno četiri fotografije.
- Zupčanici se kontinuirano rotiraju u suprotnim smjerovima, uz `prefers-reduced-motion` alternativu.
- Figma SVG asseti očišćeni su od pozadinskih pravokutnika i internih clipova; sada se režu samo na stvarnom rubu viewporta.
- Povećani su razmaci između zupčanika, sadržaja i sljedećih sekcija na desktopu i mobitelu.

## 1.3.0 — 2026-07-24

- CTA komponente usklađene s Figma dimenzijama: 40 × 40 px standardni i 32 × 32 px mali krug, bez rastezanja u flex/grid prikazima.
- Zamijenjene aproksimacije stvarnim Figma zupčanicima, strelicom i faviconom izdvojenim iz izvornog logotipa.
- Detalj edukacije proširen potpunim modularnim Gutenberg sekcijama za opis, slike, program, kotizaciju i upute.
- Hrvatski i engleski prikaz koriste zajedničke page predloške kako bi buduće dizajnerske promjene ostale sinkronizirane; engleska događanja su izostavljena.
- PDF događanja sada linearizira Gutenberg sekcije u čitljiv višestranični A4 dokument s istom glavnom slikom kao detalj događanja.
- LING cjenik otvara službenu stranicu `ling.hr/price-list` u novoj kartici.

## 1.2.0 — 2026-07-24

- Sve javne forme prebačene na Forminator, uključujući CV upload, analytics event i serverski integration hook.
- Dodane uređive engleske Gutenberg stranice iz dostavljenih DOCX dokumenata i povezani HR/EN jezični linkovi.
- Optimiziran i ugrađen dostavljeni LING promo video na hrvatsku i englesku stranicu portala.
- Dodana Gutenberg pattern biblioteka za modularno proširivanje stranica bez code changes.
- Ispravljeni hover kartica, segmentirani gear asseti, kružni gumbi i full-bleed crop zgrade.
- Dodan favicon iz kotača logotipa.
- Ujednačena slika događanja u karticama, detalju i generiranom PDF-u.

## 1.1.0 — 2026-07-23

- Ispravljeni Figma asseti, hover stanja, accordion sadržaj, newsletter gumb i crop zgrade.
- Dodani CMS modeli za konzultantske usluge i arhivu savjetovanja.
- Proširena događanja Gutenberg sadržajem, dodatnim poljima, inline Forminator prijavom i povezanim događanjima.
- Dodan stvarni PDF export događanja preko paketiranog Dompdfa.
- Dodan drugi primjer edukacije i responsive prikaz arhive, edukacija i karijera.

## 1.0.0 — 2026-07-23

- Prvo izdanje teme prema Figma dokumentu UI Design Version 2.
- Dodani svi glavni page templateovi, događanja, pozicije i web prijave.
- Dodani responsive desktop/mobile stilovi i pristupačne interakcije.
