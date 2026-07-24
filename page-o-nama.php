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
				<h1>Evolucija uspjeha: Tradicija u službi modernog poslovanja.</h1>
				<div class="page-hero__aside">
					<p>Upoznajte nas<br>Naša ekspertiza<br>Povezanost s korijenima<br>Mjesto susreta znanstvene i stručne zajednice<br>Suradnja s vodećim institucijama</p>
				</div>
			</div>
			<div class="page-hero__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-hero.jpg' ) ); ?>" alt="Arhivska izdanja Inženjerskog biroa">
			</div>
		</div>
	</section>

	<section class="section section--paper">
		<div class="container editorial-grid">
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Upoznajte nas' ); ?>
				<p>INŽENJERSKI BIRO d.o.o. jedna je od vodećih konzultantskih kuća u Republici Hrvatskoj sa 75 godina iskustva na izradi studija, elaborata, strategija i pravilnika za klijente iz poduzetničkog i javnog sektora, kao i jedan od vodećih poduzetnika u području LegalTecha i digitalnog izdavaštva pravnih publikacija.</p>
			</div>
			<div class="image-collage">
				<img class="image-collage__large" src="<?php echo esc_url( ingbiro_asset( 'images/about-team.jpg' ) ); ?>" alt="Tim na poslovnom sastanku">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-history.jpg' ) ); ?>" alt="Arhivska građa">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-team.jpg' ) ); ?>" alt="Stručno savjetovanje">
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container editorial-grid editorial-grid--reverse">
			<div class="gear-art" aria-hidden="true">
				<img class="gear-art__large" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-blue-large.svg' ) ); ?>" alt="">
				<img class="gear-art__small" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-soft-medium.svg' ) ); ?>" alt="">
			</div>
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Naša ekspertiza' ); ?>
				<p>Pružamo konzultantske usluge velikome broju klijenata iz različitih sektora hrvatskoga gospodarstva, iz područja brodogradnje, naftne industrije, obnovljivih izvora energije, regionalnog razvoja, turizma i sektora poduzetništva. Dio svojih poslovnih usluga obavljamo putem revizorskih kuća kojima je INŽENJERSKI BIRO d.o.o. suosnivač.</p>
				<p>U veljači 2024. lansirali smo inovativni proizvod u LegalTech niši – Pravni portal <a href="https://ling.hr/" target="_blank" rel="noopener noreferrer"><u>LING</u></a>. Portal sadrži napredne funkcionalnosti, ključne kolekcije pravnih dokumenata, izdanja i pravnih alata.</p>
			</div>
		</div>
	</section>

	<section class="section section--blue">
		<div class="container history-card">
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Povezanost s korijenima', true ); ?>
				<p>Lansiranje LING-a predstavlja nastavak duge tradicije koju Inženjerski biro ima u pravnoj publicistici, a koja seže u 1950-e godine. Tada je počela izlaziti publikacija ING Registar pravnih propisa, pravni priručnik koji je desetljećima bio nezamjenjiv u radu mnogih generacija pravnika i poslovnih stručnjaka.</p>
			</div>
			<div class="history-card__images">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-history.jpg' ) ); ?>" alt="Povijesna publikacija">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-team.jpg' ) ); ?>" alt="Arhiva i događanja">
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container editorial-grid editorial-grid--reverse">
			<div class="gear-art" aria-hidden="true">
				<img class="gear-art__large" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-blue-large.svg' ) ); ?>" alt="">
				<img class="gear-art__small" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-soft-medium.svg' ) ); ?>" alt="">
			</div>
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Mjesto susreta znanstvene i stručne zajednice' ); ?>
				<p>Redovito organiziramo savjetovanja i edukacije koje okupljaju vodeće stručnjake i znanstvenike iz raznih područja prava, javne uprave i gospodarstva. Ovlašteni smo nositelj Programa izobrazbe u području javne nabave i pružatelj edukacije iz područja osiguranja.</p>
			</div>
		</div>
	</section>

	<section class="section section--paper">
		<div class="container history-card">
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Suradnja s vodećim institucijama' ); ?>
				<p>Naša dugogodišnja suradnja s vodećim obrazovnim i strukovnim institucijama usmjerena je unapređenju ekonomske i pravne znanosti i struke u Republici Hrvatskoj.</p>
			</div>
			<div class="history-card__images">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-history.jpg' ) ); ?>" alt="">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-team.jpg' ) ); ?>" alt="">
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
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
