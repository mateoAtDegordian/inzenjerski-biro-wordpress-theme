# Changelog

## 1.8.0 — 2026-08-05

- Ugrađene su lokalno licencirane Helvetica Regular, Bold i Oblique web varijante, uz siguran sistemski fallback i bez javnog distribuiranja binarnih fontova.
- Ugrađeni su potvrđeni klijentski tekstovi za O nama te osam optimiziranih povijesnih fotografija u postojeću animiranu galeriju.
- Dodani su responzivni pravni accordioni za Politiku privatnosti, Politiku korištenja kolačića i Opće uvjete poslovanja.
- Kontakt, otvorena prijava, newsletter i prijava na događanje koriste tražene privole te potpune HR/EN oznake i validacijske poruke.
- Engleski web dobio je povezane stranice Careers, Career application i Newsletter, dok arhiva događanja ostaje dostupna samo na hrvatskom.
- Building SVG dobio je pouzdan reveal preko neizrezanog wrappera, a video CTA-ovi koriste zajednički pristupačni modal.
- PDF događanja ostaje uredan A4 dokument s aktualnim CMS podacima, slikom događanja i bez prazne obojene kartice.
- Provjeren je prikaz bez horizontalnog overflowa na 390, 768 i 1440 px.

## 1.6.2 — 2026-07-27

- Legacy raspored je postao adaptivan: uvodni blokovi s kratkom lijevom stranom ostaju u jednom stupcu, dok kotizacija i lokacija koriste dvije ravnopravne kolone kada za njih postoji sadržaj.
- Predavači se na desktopu prikazuju u responzivnom redu, a na mobitelu se uredno slažu jedan ispod drugoga.
- Dugi arhivski naslovi automatski koriste manju tipografsku skalu prema broju znakova kako više ne bi dominirali cijelim viewportom.
- Lokalni detalji arhive dobili su čitljive SEO slugove izvedene iz naslova i datuma, uz trajne redirecte sa starih filename URL-ova.
- URL-ovi i struktura potpuno lokalnih izvornih HTML kopija ostali su nepromijenjeni.

## 1.6.1 — 2026-07-27

- Svih 91 internih legacy zapisa dobilo je potpuno lokalnu statičnu kopiju, lokalne slike, stilove, skripte i fontove; poveznica **Otvori izvorni zapis** više ne ovisi o staroj domeni.
- Svaki lokalni detalj arhive prikazuje naslovnu sliku izdvojenu iz izvornog zapisa, a dostupne fotografije predavača ostaju uz pripadajući sadržaj.
- Typewriter animacija isključena je samo na detaljima arhive kako bi dugi povijesni naslovi odmah bili čitljivi.
- Program, predavači, lokacija i kotizacija dobili su preglednije širine, tipografiju i razmake; sadržaj teče u jednom stupcu kroz pune sekcije s izmjeničnim pozadinama, bez vanjskog okvira.
- Iz aktualnih i statičnih arhivskih prikaza uklonjeni su obrasci i gumbi za prijavu, a međusobne poveznice starih zapisa vode na lokalnu arhivu.

## 1.6.0 — 2026-07-27

- Arhiva je popunjena sa 96 stvarnih zapisa iz postojeće arhive za razdoblje 2021.–2026.
- Interni legacy HTML sadržaj prenesen je na lokalne `/arhiva/.../` rute u aktualnom dizajnu, uz lokalno spremljene dostupne slike i poveznicu na izvor.
- Događanja su dobila opciju **Prikaži u arhivi** koja ih automatski uklanja iz aktualnih edukacija, gasi prijavu i dodaje u arhivu bez dupliciranja sadržaja.
- Hrvatska i engleska arhivska stranica koriste isti predložak, strukturu, godine i pristupačni accordion.
- Dodan je ponovljiv importer koji koristi izvorni link, datum i naslov kao stabilni identitet zapisa.

## 1.5.12 — 2026-07-27

- Elementi vidljivi u početnom viewportu izuzeti su iz scroll reveal sustava kako bi prvi paint ostao potpuno vidljiv.
- Glide i blur sada se primjenjuju samo na sekcije do kojih korisnik stvarno dolazi skrolanjem.

## 1.5.11 — 2026-07-27

- Scroll reveal sada čeka dva animation framea prije promatranja, čime je uklonjen instantni pop bez tranzicije.
- Sekcije naizmjenično klize slijeva, zdesna i odozdo kroz eksplicitnu 1,28 s keyframe animaciju s izraženim blur/glide međustanjem.
- Web Animations API osigurava da reveal ne bude skraćen globalnim `Reduce Motion` transition pravilom, uz CSS keyframe fallback.
- Horizontalni pomak automatski je manji na mobitelu, a animacija ostaje vidljiva neovisno o sistemskoj motion postavci.

## 1.5.7 — 2026-07-27

- Typewriter koristi adaptivnu brzinu: kratki naslovi tipkaju sporije, a dugi ostaju vremenski kontrolirani.
- Scroll ulazi dobili su izraženiji pomak, blagi scale i blur te mekši, dulji easing.
- Elementi se aktiviraju nešto ranije u viewportu i koriste uočljiviji stagger.

## 1.5.5 — 2026-07-27

- Ubrzano je početno tipkanje glavnih naslova i skraćene su stanke na interpunkciji.
- Dodani su suptilni jednokratni ease-in prijelazi sadržaja pri skrolanju.
- Ujednačen je vertikalni ritam edukacija na desktopu i mobitelu, posebno razmak ispod informacijskih kartica.
- Mobilni meni sada se otvara glatko, zauzima dostupan ekran i može se skrolati bez rezanja sadržaja.
- Arhiva po defaultu otvara najnoviju, prvu prikazanu godinu.

## 1.5.4 — 2026-07-24

- Building banner zamijenjen je novom SVG animacijom s blagim zoom efektom i njihanjem grana.
- URL asseta sada uključuje verziju teme kako bi se nakon budućih zamjena izbjegao zastarjeli browser cache.

## 1.5.3 — 2026-07-24

- Video banner zamijenjen je izvornim animiranim SVG-om od 1470 × 630 px.
- Uklonjeni su video markup, MP4 asset i JavaScript namijenjen isključivo reprodukciji videa.
- Zadržani su postojeći full-bleed wrapper, omjer, responsive ponašanje i pozadina `#FEF4E5`.

## 1.5.2 — 2026-07-24

- Uklonjen je poster i svako cropanje videa; banner se prikazuje u izvornom omjeru 1470 × 630.
- Autoplay se više ne zaustavlja zbog sistemske `Reduce Motion` postavke.
- Video se proaktivno pokreće nakon učitavanja i pri povratku na karticu preglednika.

## 1.5.1 — 2026-07-24

- Donji banner zgrade zamijenjen je optimiziranim autoplay/loop videom bez kontrola.
- Video zadržava full-bleed prikaz i responzivni crop, uz postojeću sliku kao poster i fallback.
- Posjetitelji s uključenim `Reduce Motion` postavkama vide mirni poster umjesto animacije.

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
