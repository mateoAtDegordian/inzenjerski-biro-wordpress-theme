<?php
/**
 * Main fallback template.
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main generic-page container">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'prose' ); ?>>
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<h1>Sadržaj nije pronađen</h1>
	<?php endif; ?>
</main>
<?php
get_footer();

