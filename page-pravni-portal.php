<?php
/**
 * Template Name: Pravni portal
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main">
	<section class="page-hero">
		<div class="container">
			<div class="page-hero__copy">
				<h1>Pravni portal<br>LING</h1>
				<div class="page-hero__aside">
					<p>Pravni portal nove generacije, dizajniran da zadovolji sve potrebe modernih pravnih profesionalaca, od sudaca, odvjetnika i javnih bilježnika preko pravnika u gospodarstvu, do djelatnika državnih i lokalnih institucija.</p>
					<?php ingbiro_button( 'Posjetite portal', 'https://ling.hr/' ); ?>
				</div>
			</div>
			<div class="portal-video">
				<video controls playsinline preload="metadata" poster="<?php echo esc_url( ingbiro_asset( 'images/ling-video-poster.jpg' ) ); ?>">
					<source src="<?php echo esc_url( ingbiro_asset( 'video/ling-promo.mp4' ) ); ?>" type="video/mp4">
					Vaš preglednik ne podržava HTML5 video.
				</video>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container portal-use">
			<div class="gear-art" aria-hidden="true"></div>
			<div class="editorial-copy">
				<?php ingbiro_section_label( 'Korištenje portala' ); ?>
				<p>Prijava na pravni portal LING omogućuje neograničen pristup svim kolekcijama unutar portala i korištenje alatima koji olakšavaju svakodnevni rad naših korisnika, a odabir vlastitih interesnih područja prava omogućuje personalizaciju sustava obavijesti i jamči pravovremenu informiranost o izmjenama propisa, objavama novih sentenci, stručnih članaka i mišljenja iz odabranih područja.</p>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<?php ingbiro_section_label( 'Sadržaj pravnog portala' ); ?>
			<div class="portal-cards">
				<article class="portal-card portal-card--primary">
					<h3>Kolekcije pravnog portala LING</h3>
					<ul>
						<li>pročišćeni tekstovi propisa</li>
						<li>objave Narodnih novina</li>
						<li>sudska praksa svih sudova RH, Suda Europske unije i Europskog suda za ljudska prava</li>
						<li>sentencije sudskih odluka</li>
						<li>stručni članci eminentnih autora</li>
						<li>odluke DKOM-a</li>
						<li>međunarodni ugovori</li>
						<li>mišljenja i odluke javnopravnih tijela</li>
						<li>primjeri i obrasci</li>
					</ul>
				</article>
				<article class="portal-card">
					<h3>Pomoćni alati</h3>
					<p>Za potrebe jednostavnog rješavanja pravnih, administrativnih i računovodstvenih zadataka u svakodnevnoj praksi, pravni portal LING sadržava i pomoćne alate:</p>
					<ul>
						<li><strong>LINGBot (CHATBot)</strong> – odgovori iz kolekcija sudske prakse</li>
						<li><strong>Odvjetničku tarifu</strong> – evidencije predmeta i izračun radnji</li>
						<li><strong>Kalkulatore</strong> – zakonske zatezne kamate i sudske pristojbe</li>
					</ul>
				</article>
			</div>
			<p class="portal-ribbon">Sav sadržaj portala LING je međusobno povezan, što znatno utječe na uštedu vremena naših korisnika.</p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="ling-cta">
				<div>
					<h2>Pretplatite se na pravni portal LING</h2>
					<p>Budite u tijeku s najnovijim zakonskim promjenama i poslovnim propisima.</p>
					<?php ingbiro_button( 'Saznajte više', 'https://ling.hr/', 'pill-button--small' ); ?>
				</div>
				<img class="ling-cta__logo" src="<?php echo esc_url( ingbiro_asset( 'images/ling.png' ) ); ?>" alt="LING legal engineering">
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
