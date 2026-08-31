<?php
/**
 * Template Name: Savjetovanja i edukacije
 *
 * @package Ingbiro
 */

$ingbiro_embedded_template = ! empty( $GLOBALS['ingbiro_embedded_template'] );
if ( ! $ingbiro_embedded_template ) {
	get_header();
}

$events = new WP_Query(
	array(
		'post_type'      => 'ing_event',
		'posts_per_page' => 6,
		'post_status'    => 'publish',
		'meta_key'       => 'ing_event_start_date',
		'meta_query'     => ingbiro_active_event_meta_query(),
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
	)
);
?>
<main id="main" class="page-main">
	<section class="page-hero page-hero--education">
		<div class="container">
			<div class="page-hero__copy">
				<h1>Savjetovanja i edukacije</h1>
				<div class="page-hero__aside">
					<p>Imamo dugu tradiciju izvođenja i organiziranja savjetovanja i edukacija za pravnike i ekonomiste.<br><br>Pogledajte što je u najavi.</p>
				</div>
			</div>
			<div class="page-hero__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/education-hero.jpg' ) ); ?>" alt="Sudionici stručnog savjetovanja">
			</div>
		</div>
	</section>

	<section class="section education-overview">
		<div class="container">
			<?php ingbiro_section_label( 'Područja djelovanja' ); ?>
			<div class="content-cards content-cards--education">
				<article class="content-card">
					<h2>Savjetovanja</h2>
					<div class="content-card__body">
						<p>Redovito organiziramo savjetovanja koja okupljaju vodeće stručnjake i znanstvenike iz raznih područja prava, javne uprave i gospodarstva, a koja je moguće pratiti uživo ili online.</p>
						<p>Izvršni smo organizator tradicionalnog godišnjeg savjetovanja Hrvatskog društva ekonomista kojem je moguće prisustvovati isključivo uživo.</p>
					</div>
					<?php if ( ! ingbiro_is_english() ) : ?>
						<?php ingbiro_button( 'Pogledajte arhivu savjetovanja', ingbiro_page_url( 'arhiva' ), 'pill-button--cream pill-button--small' ); ?>
					<?php endif; ?>
				</article>
				<article class="content-card">
					<h2>Edukacije</h2>
					<div class="content-card__body">
						<p>INŽENJERSKI BIRO d.o.o. ovlašteni je nositelj Programa izobrazbe u području javne nabave prema Rješenju o ovlaštenju Ministarstva gospodarstva, od 1. ožujka 2024. godine, izdanom na rok od tri godine.</p>
						<p>Naš tim predavača čine certificirani i renomirani stručnjaci te redovni predavači na specijalističkim programima izobrazbe, seminarima, radionicama i stručnim programima usavršavanja.</p>
						<p>Ujedno smo ovlašteni pružatelj edukacije radi kontinuiranog ispunjavanja uvjeta stručnosti distributera osiguranja i reosiguranja.</p>
					</div>
				</article>
			</div>
			<div class="education-gears" aria-hidden="true">
				<img class="education-gears__large" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-soft-education.svg' ) ); ?>" alt="">
				<img class="education-gears__small" src="<?php echo esc_url( ingbiro_asset( 'icons/figma-gear-blue-education.svg' ) ); ?>" alt="">
			</div>
		</div>
	</section>

	<section class="container content-list content-list--education">
		<article class="content-list__item">
			<div class="content-list__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/education-procurement.jpg' ) ); ?>" alt="Poslovni razgovor o javnoj nabavi">
			</div>
			<div>
				<h2>Javna nabava</h2>
				<p>Inženjerski biro d.o.o., Zagreb ovlašteni je nositelj Programa izobrazbe u području javne nabave prema rješenju Ministarstva gospodarstva izdanom u 2024. godini na rok od tri godine.</p>
			</div>
		</article>
		<article class="content-list__item">
			<div class="content-list__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/education-insurance.jpg' ) ); ?>" alt="Stručnjaci na edukaciji iz osiguranja">
			</div>
			<div>
				<h2>Osiguranje</h2>
				<p>Inženjerski biro d.o.o. ovlašteni je pružatelj edukacije radi kontinuiranog ispunjavanja uvjeta stručnosti iz članka 422. Zakona o osiguranju i u skladu s Pravilnikom o stručnosti i primjerenosti distributera osiguranja i reosiguranja.</p>
			</div>
		</article>
	</section>

	<?php if ( ! ingbiro_is_english() ) : ?>
		<section class="section event-section">
			<div class="container">
				<?php ingbiro_section_label( 'Aktualna događanja' ); ?>
				<div class="event-list">
					<?php
					while ( $events->have_posts() ) :
						$events->the_post();
						?>
						<article class="event-card">
							<a class="event-card__image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
								<img src="<?php echo esc_url( ingbiro_event_image_url( get_the_ID(), 'large' ) ); ?>" alt="">
							</a>
							<div class="event-card__content">
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
	<?php endif; ?>
</main>
<?php
if ( ! $ingbiro_embedded_template ) {
	get_footer();
}
