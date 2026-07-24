<?php
/**
 * Template Name: Prijava za posao
 *
 * @package Ingbiro
 */

get_header();

$job_id = isset( $_GET['job_id'] ) ? absint( $_GET['job_id'] ) : 0;
$job    = $job_id ? get_post( $job_id ) : null;
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
