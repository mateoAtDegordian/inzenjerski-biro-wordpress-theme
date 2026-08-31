<?php
/**
 * Template Name: Arhiva događanja
 *
 * @package Ingbiro
 */

$ingbiro_embedded_template = ! empty( $GLOBALS['ingbiro_embedded_template'] );
if ( ! $ingbiro_embedded_template ) {
	get_header();
}

$legacy_posts = get_posts(
	array(
		'post_type'      => 'ing_archive',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	)
);
$archived_events = get_posts(
	array(
		'post_type'      => 'ing_event',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => 'ing_event_archived',
		'meta_value'     => '1',
	)
);

$archive_posts = array_merge( $legacy_posts, $archived_events );
usort(
	$archive_posts,
	static function ( $left, $right ) {
		$left_key  = 'ing_event' === $left->post_type ? 'ing_event_start_date' : 'ing_archive_date';
		$right_key = 'ing_event' === $right->post_type ? 'ing_event_start_date' : 'ing_archive_date';
		$left_date = (string) get_post_meta( $left->ID, $left_key, true );
		$right_date = (string) get_post_meta( $right->ID, $right_key, true );

		return strcmp( $right_date, $left_date );
	}
);

$archive_by_year = array();
foreach ( $archive_posts as $archive_post ) {
	$date_key = 'ing_event' === $archive_post->post_type ? 'ing_event_start_date' : 'ing_archive_date';
	$date     = (string) get_post_meta( $archive_post->ID, $date_key, true );
	$year     = $date ? substr( $date, 0, 4 ) : get_the_date( 'Y', $archive_post );
	$archive_by_year[ $year ][] = $archive_post;
}

$years = array_keys( $archive_by_year );
rsort( $years, SORT_NUMERIC );
$default_open_year = reset( $years );
?>
<main id="main" class="page-main">
	<section class="archive-hero">
		<div class="container">
			<a class="back-link" href="<?php echo esc_url( ingbiro_page_url( 'savjetovanja-i-edukacije' ) ); ?>">← <span><?php echo esc_html( ingbiro_l( 'Natrag', 'Back' ) ); ?></span></a>
			<h1><?php echo esc_html( ingbiro_l( 'Arhiva održanih savjetovanja', 'Conference archive' ) ); ?></h1>
			<div class="archive-hero__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/education-hero.jpg' ) ); ?>" alt="<?php echo esc_attr( ingbiro_l( 'Arhiva održanih stručnih savjetovanja', 'Past professional conferences' ) ); ?>">
			</div>
		</div>
	</section>

	<section class="container archive-layout">
		<div><?php ingbiro_section_label( ingbiro_l( 'Arhiva', 'Archive' ) ); ?></div>
		<div class="accordion archive-accordion" data-single="false">
			<?php if ( ! $years ) : ?>
				<p class="archive-empty"><?php echo esc_html( ingbiro_l( 'Arhiva trenutačno nema unesenih zapisa.', 'There are currently no archive entries.' ) ); ?></p>
			<?php endif; ?>
			<?php foreach ( $years as $year ) : ?>
				<?php $is_open = (string) $year === (string) $default_open_year; ?>
				<article class="accordion-item <?php echo $is_open ? 'is-open' : ''; ?>">
					<button class="accordion-item__button" type="button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
						<span class="accordion-item__title"><?php echo esc_html( $year ); ?></span>
						<span class="accordion-item__toggle" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/chevron.svg' ) ); ?>" alt=""></span>
					</button>
					<div class="accordion-item__panel">
						<ul class="archive-links">
							<?php foreach ( $archive_by_year[ $year ] as $archive_post ) : ?>
								<?php
								$date_label = ingbiro_archive_entry_date_label( $archive_post->ID );
								$location   = ingbiro_archive_entry_location( $archive_post->ID );
								?>
								<li>
									<a href="<?php echo esc_url( ingbiro_archive_entry_url( $archive_post ) ); ?>"><?php echo esc_html( get_the_title( $archive_post ) ); ?></a>
									<?php if ( $date_label || $location ) : ?>
										<span class="archive-links__meta">
											<?php echo esc_html( implode( ' · ', array_filter( array( $date_label, $location ) ) ) ); ?>
										</span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
</main>
<?php
if ( ! $ingbiro_embedded_template ) {
	get_footer();
}
