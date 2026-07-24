<?php
/**
 * Gutenberg pattern library for modular page building.
 *
 * @package Ingbiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ingbiro_register_block_patterns() {
	register_block_pattern_category(
		'ingbiro',
		array( 'label' => __( 'Inženjerski biro sekcije', 'ingbiro' ) )
	);

	$patterns = array(
		'two-cards' => array(
			'title'   => __( 'Dvije kartice', 'ingbiro' ),
			'content' => '<!-- wp:group {"className":"modular-section","layout":{"type":"constrained"}} --><div class="wp-block-group modular-section"><!-- wp:heading --><h2 class="wp-block-heading">Naslov sekcije</h2><!-- /wp:heading --><!-- wp:columns {"className":"modular-cards"} --><div class="wp-block-columns modular-cards"><!-- wp:column --><div class="wp-block-column modular-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Prva kartica</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Uredite sadržaj kartice.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column modular-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Druga kartica</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Uredite sadržaj kartice.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		),
		'image-copy' => array(
			'title'   => __( 'Slika i tekst', 'ingbiro' ),
			'content' => '<!-- wp:group {"className":"modular-section","layout":{"type":"constrained"}} --><div class="wp-block-group modular-section"><!-- wp:columns {"verticalAlignment":"center","className":"modular-media"} --><div class="wp-block-columns are-vertically-aligned-center modular-media"><!-- wp:column {"verticalAlignment":"center"} --><div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="' . esc_url( ingbiro_asset( 'images/education-procurement.jpg' ) ) . '" alt=""/></figure><!-- /wp:image --></div><!-- /wp:column --><!-- wp:column {"verticalAlignment":"center"} --><div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading --><h2 class="wp-block-heading">Naslov</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Dodajte tekst koji prati sliku.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		),
		'callout' => array(
			'title'   => __( 'Istaknuti poziv na akciju', 'ingbiro' ),
			'content' => '<!-- wp:group {"className":"modular-callout","layout":{"type":"constrained"}} --><div class="wp-block-group modular-callout"><!-- wp:heading --><h2 class="wp-block-heading">Poziv na akciju</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Kratko objasnite sljedeći korak.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} --><div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button">Saznajte više</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
		),
		'section-copy' => array(
			'title'   => __( 'Tekstualna sekcija', 'ingbiro' ),
			'content' => '<!-- wp:group {"className":"modular-section","layout":{"type":"constrained"}} --><div class="wp-block-group modular-section"><!-- wp:heading --><h2 class="wp-block-heading">Naslov sekcije</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Ovdje dodajte odlomke, popise, tablice, slike ili druge Gutenberg blokove.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
		),
		'event-blueprint' => array(
			'title'   => __( 'Kompletan modularni program edukacije', 'ingbiro' ),
			'content' => ingbiro_event_blueprint_content(),
		),
		'event-text-section' => array(
			'title'   => __( 'Sekcija programa edukacije', 'ingbiro' ),
			'content' => '<!-- wp:group {"className":"event-block","layout":{"type":"constrained"}} --><div class="wp-block-group event-block"><!-- wp:heading --><h2 class="wp-block-heading">Naslov sekcije</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Dodajte tekst, raspored, popis, tablicu, slike ili druge Gutenberg blokove.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
		),
		'event-two-columns' => array(
			'title'   => __( 'Dvije kolone informacija o edukaciji', 'ingbiro' ),
			'content' => '<!-- wp:group {"className":"event-block event-block--fee","layout":{"type":"constrained"}} --><div class="wp-block-group event-block event-block--fee"><!-- wp:heading --><h2 class="wp-block-heading">Naslov sekcije</h2><!-- /wp:heading --><!-- wp:columns {"className":"event-fee-columns"} --><div class="wp-block-columns event-fee-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Prva kolona</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Uredite podatke.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Druga kolona</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Uredite podatke.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		),
	);

	foreach ( $patterns as $slug => $pattern ) {
		register_block_pattern(
			'ingbiro/' . $slug,
			array(
				'title'      => $pattern['title'],
				'categories' => array( 'ingbiro' ),
				'content'    => $pattern['content'],
			)
		);
	}
}
add_action( 'init', 'ingbiro_register_block_patterns', 15 );
