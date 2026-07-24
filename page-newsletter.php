<?php
/**
 * Template Name: Newsletter
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main">
	<section class="form-page">
		<div class="container">
			<h1>Pretplatite se na naš newsletter</h1>
			<?php ingbiro_render_form( 'newsletter', 'ing-forminator--page' ); ?>
		</div>
	</section>
	<?php ingbiro_building_banner(); ?>
</main>
<?php
get_footer();
