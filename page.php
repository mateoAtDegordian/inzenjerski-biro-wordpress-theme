<?php
/**
 * Generic page.
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article class="generic-page container">
			<h1><?php the_title(); ?></h1>
			<div class="prose"><?php the_content(); ?></div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();

