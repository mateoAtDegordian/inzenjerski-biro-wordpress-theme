<?php
/**
 * Single event.
 *
 * @package Ingbiro
 */

get_header();

while ( have_posts() ) :
	the_post();
	$event_id = get_the_ID();
	?>
	<main id="main" class="page-main">
		<article>
			<header class="event-single__header container">
				<div class="tag-list">
					<span><?php echo esc_html( get_post_meta( $event_id, 'ing_event_format', true ) ?: 'WEBINAR UŽIVO' ); ?></span>
					<span><?php echo esc_html( get_post_meta( $event_id, 'ing_event_date', true ) ); ?></span>
				</div>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p class="prose"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</header>

			<div class="container page-hero__image">
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $event_id, 'full' ) ?: ingbiro_asset( 'images/education-event.jpg' ) ); ?>" alt="">
			</div>

			<section class="section">
				<div class="container">
					<div class="event-info-grid">
						<div class="event-info"><strong>Datum</strong><?php echo esc_html( get_post_meta( $event_id, 'ing_event_date', true ) ); ?></div>
						<div class="event-info"><strong>Početak / trajanje</strong><?php echo esc_html( get_post_meta( $event_id, 'ing_event_hours', true ) ); ?></div>
						<div class="event-info"><strong>Predavač</strong><?php echo esc_html( get_post_meta( $event_id, 'ing_event_speaker', true ) ); ?></div>
					</div>

					<div class="prose">
						<?php the_content(); ?>

						<h2>Zašto sudjelovati na webinaru?</h2>
						<p>Izmjene i dopune ZJN 2016 u području jednostavne nabave donose niz novina koje zahtijevaju razumijevanje zakonodavnog okvira i sigurnost u njihovoj praktičnoj primjeni kroz sve faze postupka.</p>
						<p>Kroz detaljno vođene prikaze rada u EOJN RH, sudionici stječu konkretna znanja o provedbi jednostavne nabave – od pripreme postupka i slanja poziva gospodarskim subjektima do pregleda i ocjene ponuda, donošenja odluke o odabiru i objave ugovora.</p>

						<h2>Upute</h2>
						<ul>
							<li>Prijavnicu treba popuniti točnim i obveznim podacima putem ove stranice.</li>
							<li>Kotizaciju treba uplatiti u cijelosti najkasnije jedan dan prije održavanja.</li>
							<li>Poveznica na webinar dostavlja se elektroničkim putem na adresu navedenu u prijavnici.</li>
							<li>Registraciju je potrebno napraviti najkasnije 15 minuta prije početka.</li>
							<li>Za praćenje nije potrebno instalirati dodatni program; potrebna je stabilna internetska veza i uključena web kamera.</li>
						</ul>

						<h2>Kotizacija</h2>
						<p><strong><?php echo esc_html( get_post_meta( $event_id, 'ing_event_fee', true ) ?: '180,00 eura' ); ?></strong>, uplaćuje se unaprijed na žiro-račun organizatora. Naknada uključuje sudjelovanje u programu i prezentaciju predavača u elektroničkom formatu.</p>

						<h2>Prijave</h2>
						<p>Prijavu možete poslati online putem obrasca. Za dodatne informacije obratite se na <a href="mailto:prodaja@ingbiro.hr">prodaja@ingbiro.hr</a> ili nazovite 01 / 4600 888.</p>
						<?php
						ingbiro_button(
							'Prijavite se',
							add_query_arg( 'event_id', $event_id, ingbiro_page_url( 'prijava-za-edukaciju' ) )
						);
						?>
					</div>
				</div>
			</section>
		</article>
	</main>
	<?php
endwhile;

get_footer();

