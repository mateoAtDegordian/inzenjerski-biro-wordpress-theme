<?php
/**
 * Template Name: Pravni dokument
 *
 * @package Ingbiro
 */

get_header();
?>
<main id="main" class="page-main legal-page">
	<article class="container legal-page__content" lang="<?php echo esc_attr( ingbiro_is_english() ? 'en' : 'hr' ); ?>">
		<?php ingbiro_render_legal_document(); ?>
	</article>
</main>
<?php
get_footer();
