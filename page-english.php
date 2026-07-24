<?php
/**
 * Template Name: English modular page
 *
 * @package Ingbiro
 */

get_header();
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

