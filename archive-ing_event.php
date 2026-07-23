<?php
/**
 * Event archive.
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main">
	<section class="page-hero">
		<div class="container">
			<div class="page-hero__copy">
				<h1>Arhiva savjetovanja i edukacija</h1>
				<div class="page-hero__aside"><p>Pregled održanih i najavljenih programa, webinara i stručnih savjetovanja.</p></div>
			</div>
		</div>
	</section>
	<section class="section">
		<div class="container event-list">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<article class="event-card">
						<div class="event-card__image">
							<img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: ingbiro_asset( 'images/education-event.jpg' ) ); ?>" alt="">
						</div>
						<div>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="tag-list">
								<span><?php echo esc_html( get_post_meta( get_the_ID(), 'ing_event_format', true ) ); ?></span>
								<span><?php echo esc_html( get_post_meta( get_the_ID(), 'ing_event_date', true ) ); ?></span>
							</div>
							<p><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php ingbiro_button( 'Detalji programa', get_permalink(), 'pill-button--small' ); ?>
						</div>
					</article>
				<?php endwhile; ?>
				<?php the_posts_pagination(); ?>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();

