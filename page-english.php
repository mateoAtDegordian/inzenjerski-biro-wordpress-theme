<?php
/**
 * Template Name: English modular page
 *
 * @package Ingbiro
 */

get_header();

$template_map = array(
	'en'                       => 'front-page.php',
	'about-us'                 => 'page-o-nama.php',
	'consulting'               => 'page-konzalting.php',
	'legal-portal'             => 'page-pravni-portal.php',
	'conferences-and-training' => 'page-savjetovanja-i-edukacije.php',
	'contact'                  => 'page-kontakt.php',
);
$shared_template = $template_map[ get_post_field( 'post_name', get_queried_object_id() ) ] ?? '';

if ( $shared_template ) {
	$GLOBALS['ingbiro_embedded_template'] = true;
	ob_start();
	require get_template_directory() . '/' . $shared_template;
	$shared_output = ob_get_clean();
	unset( $GLOBALS['ingbiro_embedded_template'] );
	echo strtr( $shared_output, ingbiro_english_output_translations() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	get_footer();
	return;
}
?>
<main id="main" class="page-main modular-page" lang="en">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>
<?php
get_footer();
