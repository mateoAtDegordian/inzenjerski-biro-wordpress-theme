<?php
/**
 * Template Name: Arhiva događanja
 *
 * @package Ingbiro
 */

get_header();

$archive_posts = get_posts(
	array(
		'post_type'      => 'ing_archive',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => 'ing_archive_date',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
	)
);

$archive_by_year = array();
foreach ( $archive_posts as $archive_post ) {
	$date = get_post_meta( $archive_post->ID, 'ing_archive_date', true );
	$year = $date ? substr( $date, 0, 4 ) : get_the_date( 'Y', $archive_post );
	$archive_by_year[ $year ][] = $archive_post;
}

$years = array_unique( array_merge( array( gmdate( 'Y' ), '2025', '2024', '2023' ), array_keys( $archive_by_year ) ) );
rsort( $years, SORT_NUMERIC );
$default_open_year = reset( $years );
?>
<main id="main" class="page-main">
	<section class="archive-hero">
		<div class="container">
			<a class="back-link" href="<?php echo esc_url( ingbiro_page_url( 'savjetovanja-i-edukacije' ) ); ?>">← <span>Natrag</span></a>
			<h1>Arhiva održanih savjetovanja</h1>
			<div class="archive-hero__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/education-hero.jpg' ) ); ?>" alt="Arhiva održanih stručnih savjetovanja">
			</div>
		</div>
	</section>

	<section class="container archive-layout">
		<div><?php ingbiro_section_label( 'Arhiva' ); ?></div>
		<div class="accordion archive-accordion" data-single="false">
			<?php foreach ( $years as $year ) : ?>
				<?php $is_open = (string) $year === (string) $default_open_year; ?>
				<article class="accordion-item <?php echo $is_open ? 'is-open' : ''; ?>">
					<button class="accordion-item__button" type="button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
						<span class="accordion-item__title"><?php echo esc_html( $year ); ?></span>
						<span class="accordion-item__toggle" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/chevron.svg' ) ); ?>" alt=""></span>
					</button>
					<div class="accordion-item__panel">
						<?php if ( ! empty( $archive_by_year[ $year ] ) ) : ?>
							<ul class="archive-links">
								<?php foreach ( $archive_by_year[ $year ] as $archive_post ) : ?>
									<?php $url = get_post_meta( $archive_post->ID, 'ing_archive_url', true ); ?>
									<li>
										<?php if ( $url ) : ?>
											<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( get_the_title( $archive_post ) ); ?></a>
										<?php else : ?>
											<span><?php echo esc_html( get_the_title( $archive_post ) ); ?></span>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php else : ?>
							<p class="archive-empty">Za ovu godinu trenutačno nema unesenih zapisa.</p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
</main>
<?php
get_footer();
