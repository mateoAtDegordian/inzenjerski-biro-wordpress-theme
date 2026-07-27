<?php
/**
 * Template Name: Prijava za posao
 *
 * @package Ingbiro
 */

get_header();

$job = ingbiro_get_career_application_job();
?>
<main id="main" class="page-main">
	<section class="form-page">
		<div class="container">
			<h1><?php echo esc_html( $job ? 'Prijava: ' . $job->post_title : 'Otvorena prijava' ); ?></h1>
			<?php ingbiro_render_form( 'career', 'ing-forminator--page' ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
