<?php
/**
 * Template Name: O nama
 *
 * @package Ingbiro
 */

$ingbiro_embedded_template = ! empty( $GLOBALS['ingbiro_embedded_template'] );
if ( ! $ingbiro_embedded_template ) {
	get_header();
}
?>
<main id="main" class="page-main">
	<section class="page-hero">
		<div class="container">
			<div class="page-hero__copy">
				<h1 class="typewriter-title" data-typewriter>Evolucija uspjeha:<br>Tradicija u službi modernog poslovanja</h1>
				<div class="page-hero__aside">
					<nav class="about-quicklinks" aria-label="Brzi linkovi">
						<a href="#upoznajte-nas">Upoznajte nas</a>
						<a href="#nasa-ekspertiza">Naša ekspertiza</a>
						<a href="#povezanost-s-korijenima">Povezanost s korijenima</a>
						<a href="#mjesto-susreta">Mjesto susreta znanstvene i stručne zajednice</a>
						<a href="#suradnja">Suradnja s vodećim institucijama</a>
					</nav>
				</div>
			</div>
			<div class="page-hero__image page-hero__image--about">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-hero.jpg' ) ); ?>" alt="Arhivska izdanja Inženjerskog biroa">
			</div>
		</div>
	</section>

	<section id="upoznajte-nas" class="section section--paper about-intro">
		<div class="container editorial-grid">
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Upoznajte nas' ); ?>
				<p>INŽENJERSKI BIRO d.o.o. jedna je od vodećih konzultantskih kuća u Republici Hrvatskoj sa 75 godina iskustva na izradi studija, elaborata, strategija i pravilnika za klijente iz poduzetničkog i javnog sektora, kao i jedan od vodećih poduzetnika u području LegalTecha i digitalnog izdavaštva pravnih publikacija.</p>
			</div>
			<div class="image-collage">
				<img class="image-collage__large" src="<?php echo esc_url( ingbiro_asset( 'images/about-collage-main.jpg' ) ); ?>" alt="Povijesni upravljački centar Inženjerskog biroa">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-collage-top.jpg' ) ); ?>" alt="Povijesni prikaz rada u Inženjerskom birou">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-collage-bottom.jpg' ) ); ?>" alt="Stručno savjetovanje Inženjerskog biroa">
			</div>
		</div>
	</section>

	<section id="nasa-ekspertiza" class="section section--gears">
		<div class="container editorial-grid editorial-grid--reverse">
			<div class="gear-art" aria-hidden="true">
				<img class="gear-art__large" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-blue-large.svg' ) ); ?>" alt="">
				<img class="gear-art__small" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-soft-medium.svg' ) ); ?>" alt="">
			</div>
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Naša ekspertiza' ); ?>
				<p>Pružamo konzultantske usluge izuzetno velikome broju klijenata iz različitih sektora hrvatskoga gospodarstva, iz područja brodogradnje, naftne industrije, obnovljivih izvora energije, regionalnog razvoja, turizma i sektora poduzetništva. Dio svojih poslovnih usluga obavljamo putem revizorskih kuća kojima je INŽENJERSKI BIRO d.o.o. suosnivač. One su organizirane kao društva s ograničenom odgovornošću sa sjedištima u Rijeci i Zadru.</p>
				<p>U veljači 2024. lansirali smo inovativni proizvod u LegalTech niši – Pravni portal <a href="https://ling.hr/" target="_blank" rel="noopener noreferrer"><u>LING</u></a>. Radi se o pravnom portalu nove generacije, koji sadrži napredne funkcionalnosti i ključne kolekcije pravnih dokumenata, izdanja i pravnih alata. LING sadrži i napredni sustav obavještavanja o promjenama u propisima te objavi novih sentenci, stručnih članaka i mišljenja iz odabranog područja prava.</p>
			</div>
		</div>
	</section>

	<section id="povezanost-s-korijenima" class="section section--blue">
		<div class="container history-card">
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Povezanost s korijenima', true ); ?>
				<p>Lansiranje LING-a predstavlja nastavak duge tradicije koju Inženjerski biro ima u pravnoj publicistici, a koja seže u 1950-e godine. Tada je počela izlaziti publikacija ING Registar pravnih propisa, pravni priručnik koji je desetljećima bio nezamjenjiv u radu mnogih generacija pravnika, odvjetnika i drugih poslovnih ljudi i stručnjaka te koji se izdavao na nekoliko jezika. S vremenom smo počeli izdavati i druge cijenjene pravne publikacije, među ostalim, časopis Hrvatsku pravnu reviju, Hrvatsku gospodarsku reviju, publikaciju ING Pregled sudske prakse, zbornike, specijalističke priručnike i druge publikacije.</p>
			</div>
			<div class="history-card__images history-card__images--collage">
				<img class="history-card__image--large" src="<?php echo esc_url( ingbiro_asset( 'images/about-history.jpg' ) ); ?>" alt="Povijesno izdanje ING Registra iz 1978. godine">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-gallery/slika28.webp' ) ); ?>" alt="Suradnici Inženjerskog biroa na stručnom susretu">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-gallery/slika25.webp' ) ); ?>" alt="Sudionici povijesnog stručnog sastanka">
			</div>
		</div>
	</section>

	<section id="mjesto-susreta" class="section section--gears">
		<div class="container editorial-grid editorial-grid--reverse">
			<div class="gear-art" aria-hidden="true">
				<img class="gear-art__large" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-blue-large.svg' ) ); ?>" alt="">
				<img class="gear-art__small" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-soft-medium.svg' ) ); ?>" alt="">
			</div>
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Mjesto susreta znanstvene i stručne zajednice' ); ?>
				<p>Redovito organiziramo savjetovanja i edukacije koje okupljaju vodeće stručnjake i znanstvenike iz raznih područja prava, javne uprave i gospodarstva. Ovlašteni smo nositelj Programa izobrazbe u području javne nabave i pružatelj edukacije iz područja osiguranja. Izvršni smo organizator tradicionalnog godišnjeg savjetovanja Hrvatskog društva ekonomista od njegovih začetaka.</p>
			</div>
		</div>
	</section>

	<section id="suradnja" class="section section--paper">
		<div class="container history-card">
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Suradnja s vodećim institucijama' ); ?>
				<p>Naša dugogodišnja suradnja s vodećim obrazovnim i strukovnim institucijama, kao što su Ekonomski fakultet Sveučilišta u Zagrebu, Sveučilišta u Splitu i Sveučilišta u Osijeku, Fakultet za menadžment u turizmu i ugostiteljstvu Opatija, Pravni fakultet Sveučilišta u Rijeci i Sveučilišta u Zagrebu, Hrvatsko društvo ekonomista, Udruga hrvatskih sudaca, Hrvatska revizorska komora i dr., usmjerena je unapređenju ekonomske i pravne znanosti i struke u Republici Hrvatskoj.</p>
			</div>
			<div class="history-card__images history-card__images--collage">
				<img class="history-card__image--large" src="<?php echo esc_url( ingbiro_asset( 'images/about-history.jpg' ) ); ?>" alt="Povijesno izdanje ING Registra iz 1978. godine">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-gallery/slika28.webp' ) ); ?>" alt="Suradnici Inženjerskog biroa na stručnom susretu">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-gallery/slika25.webp' ) ); ?>" alt="Sudionici povijesnog stručnog sastanka">
			</div>
		</div>
	</section>

	<section class="about-motion" aria-hidden="true">
		<div class="about-motion__wordmark-track">
			<img class="about-motion__wordmark" src="<?php echo esc_url( ingbiro_asset( 'icons/about-motion-wordmark.svg' ) ); ?>" alt="">
			<img class="about-motion__wordmark" src="<?php echo esc_url( ingbiro_asset( 'icons/about-motion-wordmark.svg' ) ); ?>" alt="">
		</div>
		<div class="about-motion__stack" data-image-stack data-interval="2800">
			<figure class="about-motion__card is-active">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-motion-1.jpg' ) ); ?>" alt="">
			</figure>
			<figure class="about-motion__card">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-motion-2.jpg' ) ); ?>" alt="">
			</figure>
			<?php
			$gallery_images = array(
				'e-ludviger.webp',
				'j-franko.webp',
				'slika14.webp',
				'slika25.webp',
				'slika12.webp',
				'slika23.webp',
				'slika26.webp',
				'slika28.webp',
			);
			foreach ( $gallery_images as $gallery_image ) :
				?>
				<figure class="about-motion__card"><img src="<?php echo esc_url( ingbiro_asset( 'images/about-gallery/' . $gallery_image ) ); ?>" alt=""></figure>
			<?php endforeach; ?>
		</div>
	</section>

	<section id="kontakt-podaci" class="section">
		<div class="container">
			<div class="company-data company-data--contact">
				<h2>Kontakt</h2>
				<p><strong>INŽENJERSKI BIRO d.o.o. za poslovne i računalne usluge</strong><br><br>Adresa: Ulica Vjekoslava Heinzela 4A, 10000 Zagreb, Hrvatska<br>Telefon: <a href="tel:+38514600888">+385/1 46 00 888</a> / Faks: +385/1 46 00 876<br>Opći kontakt: <a href="mailto:ingbiro@ingbiro.hr">ingbiro@ingbiro.hr</a> / Prodaja: <a href="mailto:prodaja@ingbiro.hr">prodaja@ingbiro.hr</a><br>Web: <a href="https://www.ingbiro.hr/">www.ingbiro.hr</a> / Pravni portal: <a href="https://ling.hr/">ling.hr</a></p>
			</div>
			<div class="company-data">
				<h2>Podaci o poduzeću</h2>
				<p>IBAN kod Privredne banke: HR2323400091100205049<br><br>SWIFT: PBZGHR2X<br><br>OIB: 84170114747</p>
				<p>INŽENJERSKI BIRO d.o.o. upisan je u registru Trgovačkog suda u Zagrebu pod MBS 080008032. Temeljni kapital je u cijelosti uplaćen i iznosi 809.760,00 eura.<br><br>Uprava društva: dr. sc. Mladen Mlinarević, glavni direktor</p>
			</div>
		</div>
	</section>
</main>
<?php
if ( ! $ingbiro_embedded_template ) {
	get_footer();
}
