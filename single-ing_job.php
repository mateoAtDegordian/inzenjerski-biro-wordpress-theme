<?php
/**
 * Single job.
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article class="generic-page container">
			<p><a href="<?php echo esc_url( ingbiro_page_url( 'karijera' ) ); ?>">← Povratak na karijere</a></p>
			<h1><?php the_title(); ?></h1>
			<?php if ( get_post_meta( get_the_ID(), 'ing_job_location', true ) ) : ?>
				<p class="tag-list"><span><?php echo esc_html( get_post_meta( get_the_ID(), 'ing_job_location', true ) ); ?></span></p>
			<?php endif; ?>
			<div class="prose"><?php the_content(); ?></div>
			<?php ingbiro_button( 'Pošaljite prijavu', add_query_arg( 'job_id', get_the_ID(), ingbiro_page_url( 'prijava-za-posao' ) ) ); ?>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();

