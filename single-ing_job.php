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
		<?php $job_form_id = absint( get_post_meta( get_the_ID(), 'ing_job_form_id', true ) ); ?>
		<article class="generic-page container">
			<p><a href="<?php echo esc_url( ingbiro_page_url( 'karijera' ) ); ?>">← Povratak na karijere</a></p>
			<h1><?php the_title(); ?></h1>
			<?php if ( get_post_meta( get_the_ID(), 'ing_job_location', true ) ) : ?>
				<p class="tag-list"><span><?php echo esc_html( get_post_meta( get_the_ID(), 'ing_job_location', true ) ); ?></span></p>
			<?php endif; ?>
			<div class="prose gutenberg-content"><?php the_content(); ?></div>
			<?php if ( $job_form_id && shortcode_exists( 'forminator_form' ) ) : ?>
				<section id="prijava" class="job-registration">
					<?php ingbiro_section_label( 'Prijava za poziciju' ); ?>
					<?php echo do_shortcode( sprintf( '[forminator_form id="%d"]', $job_form_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</section>
			<?php else : ?>
				<?php ingbiro_button( 'Pošaljite prijavu', add_query_arg( 'job_id', get_the_ID(), ingbiro_page_url( 'prijava-za-posao' ) ) ); ?>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
