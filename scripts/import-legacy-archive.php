<?php
/**
 * CLI importer for the legacy ingbiro.hr conference archive.
 *
 * Run from the theme directory:
 * php scripts/import-legacy-archive.php
 */

if ( 'cli' !== PHP_SAPI ) {
	exit( "This importer can only run from the command line.\n" );
}

define( 'WP_USE_THEMES', false );
require dirname( __DIR__, 4 ) . '/wp-load.php';

$result = ingbiro_import_legacy_archive();
if ( is_wp_error( $result ) ) {
	fwrite( STDERR, $result->get_error_message() . "\n" );
	exit( 1 );
}

printf(
	"Archive import complete: %d total, %d created, %d updated, %d pages with local content, %d local heroes, %d local original pages, %d fetch/import failures.\n",
	$result['total'],
	$result['created'],
	$result['updated'],
	$result['content'],
	$result['heroes'],
	$result['originals'],
	$result['failed']
);
