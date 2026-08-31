<?php
/**
 * Legal pages sourced verbatim from the client-approved DOCX files.
 *
 * @package Ingbiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ingbiro_legal_url( $document, $language = null ) {
	$language = $language ?: ( ingbiro_is_english() ? 'en' : 'hr' );
	$slugs    = array(
		'privacy' => array( 'hr' => 'politika-privatnosti', 'en' => 'privacy-policy' ),
		'cookies' => array( 'hr' => 'politika-koristenja-kolacica', 'en' => 'cookie-policy' ),
		'terms'   => array( 'hr' => 'opci-uvjeti-poslovanja', 'en' => 'general-terms-and-conditions' ),
	);
	$slug = $slugs[ $document ][ $language ] ?? '';
	return 'en' === $language ? ingbiro_english_page_url( $slug ) : ingbiro_page_url( $slug );
}

function ingbiro_legal_document_name() {
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	$map  = array(
		'politika-privatnosti'            => 'privacy',
		'privacy-policy'                  => 'privacy',
		'politika-koristenja-kolacica'    => 'cookies',
		'cookie-policy'                   => 'cookies',
		'opci-uvjeti-poslovanja'          => 'terms',
		'general-terms-and-conditions'    => 'terms',
	);
	return $map[ $slug ] ?? '';
}

function ingbiro_render_legal_document() {
	$document = ingbiro_legal_document_name();
	$language = ingbiro_is_english() ? 'en' : 'hr';
	if ( 'terms' === $document ) {
		printf(
			'<h1>%1$s</h1><div class="legal-callout"><p>%2$s</p></div>',
			esc_html( 'en' === $language ? 'General Terms and Conditions' : 'Opći uvjeti poslovanja' ),
			esc_html( 'en' === $language ? 'The final text will be published following legal review.' : 'Konačni tekst bit će objavljen nakon završetka pravne provjere.' )
		);
		return;
	}

	$file = get_template_directory() . '/assets/legal/' . $document . '-' . $language . '.html';
	if ( is_readable( $file ) ) {
		echo file_get_contents( $file ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}
}

function ingbiro_install_legal_pages() {
	if ( version_compare( (string) get_option( 'ingbiro_legal_pages_version', '0' ), '1.0.0', '>=' ) ) {
		return;
	}

	$pages = array(
		'politika-privatnosti'         => array( 'Politika privatnosti', 0, 'hr' ),
		'politika-koristenja-kolacica' => array( 'Politika korištenja kolačića', 0, 'hr' ),
		'opci-uvjeti-poslovanja'       => array( 'Opći uvjeti poslovanja', 0, 'hr' ),
	);
	$english_root = get_page_by_path( 'en' );
	if ( $english_root ) {
		$pages['privacy-policy']               = array( 'Privacy Policy', $english_root->ID, 'en' );
		$pages['cookie-policy']                = array( 'Cookie Policy', $english_root->ID, 'en' );
		$pages['general-terms-and-conditions'] = array( 'General Terms and Conditions', $english_root->ID, 'en' );
	}

	$created = array();
	foreach ( $pages as $slug => $definition ) {
		$path = $definition[1] ? 'en/' . $slug : $slug;
		$page = get_page_by_path( $path );
		$id   = $page ? $page->ID : wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_parent' => $definition[1],
				'post_name'   => $slug,
				'post_title'  => $definition[0],
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_wp_page_template', 'page-legal.php' );
			if ( 'en' === $definition[2] ) {
				update_post_meta( $id, '_ingbiro_language', 'en' );
			}
			$created[ $slug ] = $id;
		}
	}

	$pairs = array(
		'privacy-policy'               => 'politika-privatnosti',
		'cookie-policy'                => 'politika-koristenja-kolacica',
		'general-terms-and-conditions' => 'opci-uvjeti-poslovanja',
	);
	foreach ( $pairs as $english_slug => $croatian_slug ) {
		if ( ! empty( $created[ $english_slug ] ) && ! empty( $created[ $croatian_slug ] ) ) {
			update_post_meta( $created[ $english_slug ], '_ingbiro_translation_id', $created[ $croatian_slug ] );
			update_post_meta( $created[ $croatian_slug ], '_ingbiro_translation_id', $created[ $english_slug ] );
		}
	}

	update_option( 'ingbiro_legal_pages_version', '1.0.0' );
}
add_action( 'admin_init', 'ingbiro_install_legal_pages', 55 );
