<?php
/**
 * Template Name: Konzalting
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main">
	<section class="page-hero">
		<div class="container">
			<div class="page-hero__copy">
				<h1>Konzalting</h1>
				<div class="page-hero__aside">
					<p>Usmjereni smo ka iznalaženju sigurnih, visokokvalitetnih i dugoročnih rješenja i rezultata koji našim klijentima donose konkurentnu prednost i jačanje tržišne pozicije.</p>
				</div>
			</div>
			<div class="page-hero__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/consulting-hero.jpg' ) ); ?>" alt="Poslovno savjetovanje">
			</div>
		</div>
	</section>

	<section class="container accordion-layout">
		<div><?php ingbiro_section_label( 'Naše usluge' ); ?></div>
		<div class="accordion">
			<article class="accordion-item is-open">
				<button class="accordion-item__button" type="button" aria-expanded="true">
					<span class="accordion-item__number">01</span>
					<span class="accordion-item__title">Ekonomski konzalting</span>
					<span class="accordion-item__toggle">⌃</span>
				</button>
				<div class="accordion-item__panel">
					<p><strong>Savjetovanje pri spajanju i preuzimanju kompanija (M&amp;A):</strong></p>
					<ul>
						<li>Izrada informacijskog memoranduma i analiza poslovanja</li>
						<li>Financijski, komercijalni i porezni due diligence</li>
						<li>Procjena vrijednosti poduzeća, udjela i dionica</li>
						<li>Pronalaženje investitora i strukturiranje transakcija</li>
						<li>Pravno-formalna priprema prodaje</li>
					</ul>
					<p><strong>Strateško i financijsko planiranje:</strong></p>
					<ul>
						<li>Financijsko, operativno i vlasničko restrukturiranje</li>
						<li>Razvojne studije za potrebe restrukturiranja i novih zaduživanja</li>
						<li>Investicijske studije i studije gospodarske opravdanosti</li>
						<li>Priprema projekata za financiranje iz EU fondova</li>
					</ul>
				</div>
			</article>

			<article class="accordion-item">
				<button class="accordion-item__button" type="button" aria-expanded="false">
					<span class="accordion-item__number">02</span>
					<span class="accordion-item__title">Pravni konzalting</span>
					<span class="accordion-item__toggle">⌄</span>
				</button>
				<div class="accordion-item__panel">
					<p>Pružamo podršku u korporativnom pravu, usklađivanju poslovanja s propisima, pravnoj analizi poslovnih odluka, statusnim promjenama i ugovornim odnosima.</p>
				</div>
			</article>

			<article class="accordion-item">
				<button class="accordion-item__button" type="button" aria-expanded="false">
					<span class="accordion-item__number">03</span>
					<span class="accordion-item__title">Organizacijski konzalting</span>
					<span class="accordion-item__toggle">⌄</span>
				</button>
				<div class="accordion-item__panel">
					<p>Projektiramo organizacijske modele, optimiziramo poslovne procese i pomažemo upravama u provedbi promjena, restrukturiranja i unapređenja učinkovitosti.</p>
				</div>
			</article>
		</div>
	</section>

	<?php ingbiro_building_banner(); ?>

	<div class="support-note container">
		INŽENJERSKI BIRO d.o.o. ima stručnu potporu ovlaštenih revizorskih kuća, društava-kćeri:<br>
		<strong>HLB Adria Inženjerski biro d.o.o.</strong>, Strossmayerova 11, 51000 Rijeka.<br>
		<strong>INŽENJERSKI BIRO-REVIZIJA d.o.o.</strong>, Poljana Plankit 1, 23000 Zadar.
	</div>
</main>
<?php
get_footer();

