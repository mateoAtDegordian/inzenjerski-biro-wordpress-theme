<?php
/**
 * Single event.
 *
 * @package Ingbiro
 */

get_header();

while ( have_posts() ) :
	the_post();
	$event_id            = get_the_ID();
	$registration_active = (bool) get_post_meta( $event_id, 'ing_event_registration_enabled', true );
	$related_events      = new WP_Query(
		array(
			'post_type'      => 'ing_event',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'post__not_in'   => array( $event_id ),
			'meta_key'       => 'ing_event_start_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
		)
	);
	?>
	<main id="main" class="page-main">
		<article class="event-single">
			<header class="event-single__hero">
				<div class="container">
					<a class="back-link" href="<?php echo esc_url( ingbiro_page_url( 'savjetovanja-i-edukacije' ) ); ?>">← <span>Natrag</span></a>
					<h1><?php the_title(); ?></h1>
					<div class="event-single__meta-row">
						<div class="tag-list">
							<span><?php echo esc_html( get_post_meta( $event_id, 'ing_event_format', true ) ?: 'WEBINAR UŽIVO' ); ?></span>
							<span><?php echo esc_html( get_post_meta( $event_id, 'ing_event_date', true ) ); ?></span>
						</div>
						<?php if ( $registration_active ) : ?>
							<?php ingbiro_button( 'Prijavite se', '#prijava', 'pill-button--blue' ); ?>
						<?php endif; ?>
					</div>
					<div class="event-single__image">
						<img src="<?php echo esc_url( ingbiro_event_image_url( $event_id ) ); ?>" alt="">
					</div>
				</div>
			</header>

			<section class="event-intro section section--paper">
				<div class="container">
					<?php ingbiro_section_label( get_post_meta( $event_id, 'ing_event_format', true ) ?: 'Edukacija uživo' ); ?>
					<?php if ( has_excerpt() ) : ?><p class="event-intro__lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
					<div class="event-info-grid">
						<div class="event-info">
							<strong>Predavač</strong>
							<b><?php echo esc_html( get_post_meta( $event_id, 'ing_event_speaker', true ) ); ?></b>
							<span><?php echo esc_html( get_post_meta( $event_id, 'ing_event_speaker_role', true ) ); ?></span>
						</div>
						<div class="event-info">
							<strong>Datum</strong>
							<b><?php echo esc_html( get_post_meta( $event_id, 'ing_event_date', true ) ); ?></b>
							<span><?php echo esc_html( get_post_meta( $event_id, 'ing_event_location', true ) ); ?></span>
						</div>
						<div class="event-info">
							<strong>Početak</strong>
							<b><?php echo esc_html( get_post_meta( $event_id, 'ing_event_start', true ) ?: get_post_meta( $event_id, 'ing_event_hours', true ) ); ?></b>
							<span><?php echo esc_html( get_post_meta( $event_id, 'ing_event_hours', true ) ); ?></span>
						</div>
					</div>
				</div>
			</section>

			<section class="event-content section">
				<div class="container">
					<div class="event-content__body prose gutenberg-content">
						<?php the_content(); ?>
					</div>
					<aside class="event-content__aside">
						<div class="event-facts">
							<?php ingbiro_section_label( 'Brze informacije' ); ?>
							<dl>
								<div><dt>Format</dt><dd><?php echo esc_html( get_post_meta( $event_id, 'ing_event_format', true ) ); ?></dd></div>
								<div><dt>Trajanje</dt><dd><?php echo esc_html( get_post_meta( $event_id, 'ing_event_hours', true ) ); ?></dd></div>
								<div><dt>Kotizacija</dt><dd><?php echo esc_html( get_post_meta( $event_id, 'ing_event_fee', true ) ); ?></dd></div>
							</dl>
							<?php if ( $registration_active ) : ?>
								<?php ingbiro_button( 'Prijavite se', '#prijava', 'pill-button--blue' ); ?>
							<?php endif; ?>
						</div>
					</aside>
				</div>
			</section>

			<section class="event-download">
				<div class="container event-download__inner">
					<div>
						<?php ingbiro_section_label( 'Dokument događanja' ); ?>
						<h2>Sve informacije u uređenom PDF dokumentu</h2>
						<p>PDF se generira iz aktualnih podataka i Gutenberg sadržaja ovog događanja.</p>
					</div>
					<?php ingbiro_button( 'Preuzmite informacije kao PDF', ingbiro_event_pdf_url( $event_id ), 'pill-button--blue pill-button--download' ); ?>
				</div>
			</section>

			<?php if ( $registration_active ) : ?>
				<section id="prijava" class="event-registration section section--paper">
					<div class="container">
						<div class="event-registration__heading">
							<?php ingbiro_section_label( 'Prijava' ); ?>
							<h2>Prijavite se za edukaciju</h2>
							<p>Polja i poruke ove forme uređuju se u Forminatoru, a prijave ostaju dostupne u WordPress dashboardu.</p>
						</div>
						<?php ingbiro_render_event_form( $event_id ); ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if ( $related_events->have_posts() ) : ?>
				<section class="section related-events">
					<div class="container">
						<?php ingbiro_section_label( 'Ostala događanja' ); ?>
						<div class="event-list">
							<?php while ( $related_events->have_posts() ) : $related_events->the_post(); ?>
								<article class="event-card">
									<a class="event-card__image" href="<?php the_permalink(); ?>">
										<img src="<?php echo esc_url( ingbiro_event_image_url( get_the_ID(), 'large' ) ); ?>" alt="">
									</a>
									<div class="event-card__content">
										<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
										<div class="tag-list">
											<span><?php echo esc_html( get_post_meta( get_the_ID(), 'ing_event_format', true ) ); ?></span>
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
		</article>
	</main>
	<?php
endwhile;

get_footer();
