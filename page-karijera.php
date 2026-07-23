<?php
/**
 * Template Name: Karijera
 *
 * @package Ingbiro
 */

get_header();

$jobs = new WP_Query(
	array(
		'post_type'      => 'ing_job',
		'posts_per_page' => 10,
		'post_status'    => 'publish',
	)
);
?>
<main id="main" class="page-main">
	<section class="page-hero">
		<div class="container">
			<div class="page-hero__copy">
				<h1>Pridružite se našem timu</h1>
				<div class="page-hero__aside">
					<p>75 godina iskustva na izradi studija, elaborata, strategija i pravilnika za klijente iz poduzetničkog i javnog sektora.</p>
					<?php ingbiro_button( 'Pročitajte više', '#otvorene-pozicije' ); ?>
				</div>
			</div>
			<div class="page-hero__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/careers-hero.jpg' ) ); ?>" alt="Suradnici Inženjerskog biroa">
			</div>
		</div>
	</section>

	<section id="otvorene-pozicije" class="section">
		<div class="container">
			<?php ingbiro_section_label( 'Otvorene pozicije' ); ?>
			<?php if ( $jobs->have_posts() ) : ?>
				<div class="jobs-layout">
					<div class="jobs-layout__image">
						<img src="<?php echo esc_url( ingbiro_asset( 'images/careers-position.jpg' ) ); ?>" alt="Radno mjesto u Inženjerskom birou">
					</div>
					<div class="accordion">
						<?php
						$number = 0;
						while ( $jobs->have_posts() ) :
							$jobs->the_post();
							++$number;
							?>
							<article class="accordion-item <?php echo 1 === $number ? 'is-open' : ''; ?>">
								<button class="accordion-item__button" type="button" aria-expanded="<?php echo 1 === $number ? 'true' : 'false'; ?>">
									<span class="accordion-item__number"><?php echo esc_html( str_pad( (string) $number, 2, '0', STR_PAD_LEFT ) ); ?></span>
									<span class="accordion-item__title"><?php the_title(); ?></span>
									<span class="accordion-item__toggle" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/chevron.svg' ) ); ?>" alt=""></span>
								</button>
								<div class="accordion-item__panel">
									<p><?php echo esc_html( get_the_excerpt() ?: wp_trim_words( get_the_content(), 32 ) ); ?></p>
									<?php
									ingbiro_button(
										'Pošaljite prijavu',
										add_query_arg( 'job_id', get_the_ID(), ingbiro_page_url( 'prijava-za-posao' ) ),
										'pill-button--small'
									);
									?>
								</div>
							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
			<?php else : ?>
				<div class="empty-state">
					<h2>Trenutno nemamo otvorenih pozicija</h2>
					<p>Uvijek rado upoznajemo stručne i motivirane ljude. Pošaljite nam otvorenu prijavu.</p>
					<?php ingbiro_button( 'Otvorena prijava', ingbiro_page_url( 'prijava-za-posao' ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
