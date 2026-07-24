<?php
/**
 * Template Name: Prijava za edukaciju
 *
 * @package Ingbiro
 */

get_header();

$event_id = isset( $_GET['event_id'] ) ? absint( $_GET['event_id'] ) : 0;
$event    = $event_id ? get_post( $event_id ) : null;
?>
<main id="main" class="page-main">
	<section class="form-page">
		<div class="container">
			<h1>Prijava za edukaciju</h1>
			<?php if ( $event ) : ?>
				<p class="form-page__context"><?php echo esc_html( $event->post_title ); ?></p>
			<?php endif; ?>
			<?php ingbiro_render_form( 'event', 'ing-forminator--page' ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
