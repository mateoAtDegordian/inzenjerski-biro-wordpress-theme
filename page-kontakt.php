<?php
/**
 * Template Name: Kontakt
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main">
	<section class="form-page">
		<div class="container">
			<h1>Kontaktirajte nas</h1>
			<?php ingbiro_form_status(); ?>
			<form class="ing-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="ingbiro_submit">
				<input type="hidden" name="submission_type" value="contact">
				<?php wp_nonce_field( 'ingbiro_submit', 'ingbiro_nonce' ); ?>
				<p class="form-honeypot"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></p>

				<div class="ing-field">
					<input id="contact-name" name="name" type="text" placeholder=" " required>
					<label for="contact-name">Ime i prezime</label>
				</div>
				<div class="ing-field">
					<input id="contact-email" name="email" type="email" placeholder=" " required>
					<label for="contact-email">Vaš e-mail</label>
				</div>
				<div class="ing-field">
					<input id="contact-phone" name="phone" type="tel" placeholder=" ">
					<label for="contact-phone">Broj telefona</label>
				</div>
				<div class="ing-field">
					<input id="contact-company" name="company" type="text" placeholder=" ">
					<label for="contact-company">Tvrtka / organizacija</label>
				</div>
				<div class="ing-field ing-field--full ing-field--textarea">
					<textarea id="contact-message" name="message" placeholder=" " required></textarea>
					<label for="contact-message">Vaša poruka</label>
				</div>
				<div class="ing-form__actions">
					<button class="pill-button" type="submit"><span>Pošaljite upit</span><span class="pill-button__icon" aria-hidden="true">→</span></button>
				</div>
			</form>
		</div>
	</section>
	<?php ingbiro_building_banner(); ?>
</main>
<?php
get_footer();

