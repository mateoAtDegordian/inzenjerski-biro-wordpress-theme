<?php
/**
 * 404 template.
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main generic-page container">
	<h1>Stranica nije pronađena</h1>
	<p>Tražena adresa ne postoji ili je sadržaj premješten.</p>
	<?php ingbiro_button( 'Povratak na naslovnicu', home_url( '/' ) ); ?>
</main>
<?php
get_footer();

