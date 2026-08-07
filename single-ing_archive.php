<?php
/**
 * Local detail page for an imported legacy archive entry.
 *
 * @package Ingbiro
 */

get_header();

while ( have_posts() ) :
	the_post();
	$archive_id = get_the_ID();
	$date       = ingbiro_archive_entry_date_label( $archive_id );
	$location   = ingbiro_archive_entry_location( $archive_id );
	$title      = get_the_title();
	$title_size = mb_strlen( wp_strip_all_tags( $title ) );
	$title_class = '';
	if ( $title_size > 145 ) {
		$title_class = ' archive-entry__title--very-long';
	} elseif ( $title_size > 85 ) {
		$title_class = ' archive-entry__title--long';
	}
	$hero_url   = (string) get_post_meta( $archive_id, 'ing_archive_hero_url', true );
	$legacy_url = (string) get_post_meta( $archive_id, 'ing_archive_legacy_url', true );
	$source_url = ingbiro_archive_original_url( $archive_id );
	if ( ! $source_url ) {
		$legacy_host = strtolower( (string) wp_parse_url( $legacy_url, PHP_URL_HOST ) );
		$source_url  = str_ends_with( $legacy_host, 'ingbiro.hr' ) ? '' : $legacy_url;
	}
	if ( ! $source_url ) {
		$manual_url = (string) get_post_meta( $archive_id, 'ing_archive_url', true );
		if ( untrailingslashit( $manual_url ) !== untrailingslashit( get_permalink( $archive_id ) ) ) {
			$source_url = $manual_url;
		}
	}
	?>
	<main id="main" class="page-main">
		<article class="archive-entry">
			<header class="archive-entry__hero">
				<div class="container">
					<a class="back-link" href="<?php echo esc_url( ingbiro_page_url( 'arhiva' ) ); ?>">← <span><?php echo esc_html( ingbiro_l( 'Natrag u arhivu', 'Back to archive' ) ); ?></span></a>
					<?php ingbiro_section_label( ingbiro_l( 'Arhiva', 'Archive' ) ); ?>
					<h1 class="archive-entry__title<?php echo esc_attr( $title_class ); ?>" data-no-typewriter><?php echo esc_html( $title ); ?></h1>
					<?php if ( $date || $location ) : ?>
						<div class="archive-entry__meta">
							<?php if ( $date ) : ?><span><?php echo esc_html( $date ); ?></span><?php endif; ?>
							<?php if ( $location ) : ?><span><?php echo esc_html( $location ); ?></span><?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $hero_url ) : ?>
						<div class="archive-entry__image">
							<img src="<?php echo esc_url( $hero_url ); ?>" alt="">
						</div>
					<?php endif; ?>
				</div>
			</header>

			<section class="archive-entry__content">
				<?php if ( trim( (string) get_the_content() ) ) : ?>
					<div class="archive-entry__content-inner prose gutenberg-content">
						<?php the_content(); ?>
					</div>
				<?php else : ?>
					<div class="container">
						<p><?php echo esc_html( ingbiro_l( 'Za ovaj zapis dostupni su osnovni podaci i izvorna poveznica.', 'Basic details and the original source link are available for this entry.' ) ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( $source_url ) : ?>
					<div class="container">
						<div class="archive-entry__source">
							<?php ingbiro_button( ingbiro_l( 'Otvori izvorni zapis', 'Open original entry' ), $source_url, 'pill-button--small', array( 'target' => '_blank', 'rel' => 'noopener noreferrer' ) ); ?>
						</div>
					</div>
				<?php endif; ?>
			</section>
		</article>
	</main>
	<?php
endwhile;

get_footer();
