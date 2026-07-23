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
			<?php ingbiro_form_status(); ?>
			<form class="ing-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="ingbiro_submit">
				<input type="hidden" name="submission_type" value="newsletter">
				<?php wp_nonce_field( 'ingbiro_submit', 'ingbiro_nonce' ); ?>
				<p class="form-honeypot"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></p>

				<div class="ing-field"><input id="nl-last-name" name="last_name" type="text" placeholder=" "><label for="nl-last-name">Prezime</label></div>
				<div class="ing-field"><input id="nl-name" name="name" type="text" placeholder=" " required><label for="nl-name">Ime</label></div>
				<div class="ing-field"><input id="nl-email" name="email" type="email" placeholder=" " required><label for="nl-email">E-mail</label></div>
				<div class="ing-field"><input id="nl-birthdate" name="birthdate" type="text" placeholder=" "><label for="nl-birthdate">Datum rođenja</label></div>
				<div class="ing-field"><input id="nl-city" name="city" type="text" placeholder=" "><label for="nl-city">Grad</label></div>
				<div class="ing-field"><input id="nl-phone" name="phone" type="tel" placeholder=" "><label for="nl-phone">Broj telefona</label></div>
				<div class="ing-field"><input id="nl-country" name="country" type="text" placeholder=" "><label for="nl-country">Država</label></div>
				<div class="ing-field"><input id="nl-company" name="company" type="text" placeholder=" "><label for="nl-company">Tvrtka</label></div>
				<div class="ing-form__actions">
					<button class="pill-button" type="submit"><span>Pretplatite se</span><span class="pill-button__icon" aria-hidden="true">→</span></button>
				</div>
			</form>
		</div>
	</section>
	<?php ingbiro_building_banner(); ?>
</main>
<?php
get_footer();

