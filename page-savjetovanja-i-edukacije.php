<?php
/**
 * Template Name: Savjetovanja i edukacije
 *
 * @package Ingbiro
 */

get_header();

$events = new WP_Query(
	array(
		'post_type'      => 'ing_event',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
	)
);
?>
<main id="main" class="page-main">
	<section class="page-hero">
		<div class="container">
			<div class="page-hero__copy">
				<h1>Savjetovanja i edukacije</h1>
				<div class="page-hero__aside">
					<p>Imamo dugu tradiciju izvođenja i organiziranja savjetovanja i edukacija za pravnike i ekonomiste.<br><br>Pogledajte što je u najavi.</p>
				</div>
			</div>
			<div class="page-hero__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/education-hero.jpg' ) ); ?>" alt="Stručno savjetovanje">
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<?php ingbiro_section_label( 'Područja djelovanja' ); ?>
			<div class="content-cards">
				<article class="content-card">
					<h2>Savjetovanja</h2>
					<p>Redovito organiziramo savjetovanja koja okupljaju vodeće stručnjake i znanstvenike iz raznih područja prava, javne uprave i gospodarstva, a koja je moguće pratiti uživo ili online.</p>
					<p>Izvršni smo organizator tradicionalnog godišnjeg savjetovanja Hrvatskog društva ekonomista kojem je moguće prisustvovati isključivo uživo.</p>
					<?php ingbiro_button( 'Pogledajte arhivu savjetovanja', ingbiro_page_url( 'arhiva' ), 'pill-button--outline pill-button--small' ); ?>
				</article>
				<article class="content-card">
					<h2>Edukacije</h2>
					<p>INŽENJERSKI BIRO d.o.o. ovlašteni je nositelj Programa izobrazbe u području javne nabave prema Rješenju o ovlaštenju Ministarstva gospodarstva izdanom u 2024. godini na rok od tri godine.</p>
					<p>Naš tim predavača čine certificirani i renomirani stručnjaci te redovni predavači na specijalističkim programima, seminarima i radionicama.</p>
					<p>Ujedno smo ovlašteni pružatelj edukacije radi kontinuiranog ispunjavanja uvjeta stručnosti u području osiguranja.</p>
				</article>
			</div>
		</div>
	</section>

	<section class="container content-list">
		<article class="content-list__item">
			<div class="content-list__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/consulting-hero.jpg' ) ); ?>" alt="">
			</div>
			<div>
				<h2>Javna nabava</h2>
				<p>Inženjerski biro d.o.o., Zagreb ovlašteni je nositelj Programa izobrazbe u području javne nabave prema rješenju Ministarstva gospodarstva izdanom u 2024. godini na rok od tri godine.</p>
			</div>
		</article>
		<article class="content-list__item">
			<div class="content-list__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/about-team.jpg' ) ); ?>" alt="">
			</div>
			<div>
				<h2>Osiguranje</h2>
				<p>Inženjerski biro d.o.o. ovlašteni je pružatelj edukacije radi kontinuiranog ispunjavanja uvjeta stručnosti distributera osiguranja i reosiguranja.</p>
			</div>
		</article>
	</section>

	<section class="section">
		<div class="container">
			<?php ingbiro_section_label( 'Aktualna događanja' ); ?>
			<div class="event-list">
				<?php
				while ( $events->have_posts() ) :
					$events->the_post();
					?>
					<article class="event-card">
						<div class="event-card__image">
							<img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: ingbiro_asset( 'images/education-event.jpg' ) ); ?>" alt="">
						</div>
						<div>
							<h2><?php the_title(); ?></h2>
							<div class="tag-list">
								<span><?php echo esc_html( get_post_meta( get_the_ID(), 'ing_event_format', true ) ?: 'UŽIVO · WEBINAR' ); ?></span>
								<span><?php echo esc_html( get_post_meta( get_the_ID(), 'ing_event_date', true ) ); ?></span>
							</div>
							<p><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php ingbiro_button( 'Pročitajte više', get_permalink(), 'pill-button--small' ); ?>
						</div>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();

