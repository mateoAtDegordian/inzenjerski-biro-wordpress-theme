<?php
/**
 * Template Name: Konzalting
 *
 * @package Ingbiro
 */

$ingbiro_embedded_template = ! empty( $GLOBALS['ingbiro_embedded_template'] );
if ( ! $ingbiro_embedded_template ) {
	get_header();
}

$services = ingbiro_is_english() ? ingbiro_get_english_consulting_services() : ingbiro_get_consulting_services();
?>
<main id="main" class="page-main">
	<section class="page-hero">
		<div class="container">
			<div class="page-hero__copy">
				<h1>Konzalting</h1>
				<div class="page-hero__aside">
					<p>Usmjereni smo ka iznalaženju sigurnih, visokokvalitetnih i dugoročnih rješenja i rezultata koji našim klijentima donose konkurentnu prednost i jačanje tržišne pozicije.</p>
				</div>
			</div>
			<div class="page-hero__image">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/consulting-hero.jpg' ) ); ?>" alt="Poslovno savjetovanje">
			</div>
		</div>
	</section>

	<section class="container accordion-layout">
		<div><?php ingbiro_section_label( 'Naše usluge' ); ?></div>
		<div class="accordion">
			<?php foreach ( $services as $index => $service ) : ?>
				<?php $service_title = isset( $service->post_title ) ? $service->post_title : get_the_title( $service ); ?>
				<article class="accordion-item <?php echo 0 === $index ? 'is-open' : ''; ?>">
					<button class="accordion-item__button" type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>">
						<span class="accordion-item__number"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<span class="accordion-item__title"><?php echo esc_html( $service_title ); ?></span>
						<span class="accordion-item__toggle" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/chevron.svg' ) ); ?>" alt=""></span>
					</button>
					<div class="accordion-item__panel">
						<?php echo apply_filters( 'the_content', $service->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<?php ingbiro_building_banner(); ?>

	<div class="support-note container">
		INŽENJERSKI BIRO d.o.o. ima stručnu potporu ovlaštenih revizorskih kuća, društava-kćeri:<br>
		<strong>HLB Adria Inženjerski biro d.o.o.</strong>, Strossmayerova 11, 51000 Rijeka.<br>
		<strong>INŽENJERSKI BIRO-REVIZIJA d.o.o.</strong>, Poljana Plankit 1, 23000 Zadar.
	</div>
</main>
<?php
if ( ! $ingbiro_embedded_template ) {
	get_footer();
}
