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
			<?php ingbiro_form_status(); ?>
			<form class="ing-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="ingbiro_submit">
				<input type="hidden" name="submission_type" value="event">
				<input type="hidden" name="event" value="<?php echo esc_attr( $event ? $event->post_title : '' ); ?>">
				<?php wp_nonce_field( 'ingbiro_submit', 'ingbiro_nonce' ); ?>
				<p class="form-honeypot"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></p>

				<div class="ing-field"><input id="event-name" name="name" type="text" placeholder=" " required><label for="event-name">Ime i prezime</label></div>
				<div class="ing-field"><input id="event-email" name="email" type="email" placeholder=" " required><label for="event-email">E-mail</label></div>
				<div class="ing-field"><input id="event-phone" name="phone" type="tel" placeholder=" " required><label for="event-phone">Broj telefona</label></div>
				<div class="ing-field"><input id="event-company" name="company" type="text" placeholder=" " required><label for="event-company">Tvrtka / institucija</label></div>
				<div class="ing-field"><input id="event-oib" name="oib" type="text" placeholder=" "><label for="event-oib">OIB</label></div>
				<div class="ing-field"><input id="event-address" name="address" type="text" placeholder=" "><label for="event-address">Adresa</label></div>
				<div class="ing-field ing-field--full ing-field--textarea"><textarea id="event-message" name="message" placeholder=" "></textarea><label for="event-message">Napomena</label></div>
				<div class="ing-form__actions">
					<button class="pill-button" type="submit"><span>Pošaljite prijavu</span><span class="pill-button__icon" aria-hidden="true">→</span></button>
				</div>
			</form>
		</div>
	</section>
</main>
<?php
get_footer();

