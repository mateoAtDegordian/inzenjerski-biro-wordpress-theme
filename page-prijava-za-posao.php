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
			<?php ingbiro_form_status(); ?>
			<form class="ing-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="ingbiro_submit">
				<input type="hidden" name="submission_type" value="career">
				<input type="hidden" name="position" value="<?php echo esc_attr( $job ? $job->post_title : 'Otvorena prijava' ); ?>">
				<?php wp_nonce_field( 'ingbiro_submit', 'ingbiro_nonce' ); ?>
				<p class="form-honeypot"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></p>

				<div class="ing-field"><input id="career-name" name="name" type="text" placeholder=" " required><label for="career-name">Ime i prezime</label></div>
				<div class="ing-field"><input id="career-email" name="email" type="email" placeholder=" " required><label for="career-email">E-mail</label></div>
				<div class="ing-field"><input id="career-phone" name="phone" type="tel" placeholder=" "><label for="career-phone">Broj telefona</label></div>
				<div class="ing-field"><input id="career-linkedin" name="linkedin" type="url" placeholder=" "><label for="career-linkedin">LinkedIn / portfolio</label></div>
				<div class="ing-field ing-field--full"><input id="career-cv" name="cv" type="file" accept=".pdf,.doc,.docx"><label class="screen-reader-text" for="career-cv">Životopis</label></div>
				<div class="ing-field ing-field--full ing-field--textarea"><textarea id="career-message" name="message" placeholder=" " required></textarea><label for="career-message">Kratko motivacijsko pismo</label></div>
				<div class="ing-form__actions">
					<button class="pill-button" type="submit"><span>Pošaljite prijavu</span><span class="pill-button__icon" aria-hidden="true">→</span></button>
				</div>
			</form>
		</div>
	</section>
</main>
<?php
get_footer();

