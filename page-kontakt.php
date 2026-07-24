<?php
/**
 * Template Name: Kontakt
 *
 * @package Ingbiro
 */

$ingbiro_embedded_template = ! empty( $GLOBALS['ingbiro_embedded_template'] );
if ( ! $ingbiro_embedded_template ) {
	get_header();
}
?>
<main id="main" class="page-main">
	<section class="form-page">
		<div class="container">
			<h1>Kontaktirajte nas</h1>
			<?php ingbiro_render_form( 'contact', 'ing-forminator--page' ); ?>
		</div>
	</section>
	<?php ingbiro_building_banner(); ?>
</main>
<?php
if ( ! $ingbiro_embedded_template ) {
	get_footer();
}
