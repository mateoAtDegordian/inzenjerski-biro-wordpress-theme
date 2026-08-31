<?php
/**
 * Unified event archive and legacy archive importer.
 *
 * @package Ingbiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta query shared by every list of current events.
 *
 * @return array
 */
function ingbiro_active_event_meta_query() {
	return array(
		'relation' => 'OR',
		array(
			'key'     => 'ing_event_archived',
			'compare' => 'NOT EXISTS',
		),
		array(
			'key'     => 'ing_event_archived',
			'value'   => '1',
			'compare' => '!=',
		),
	);
}

/**
 * Whether an event was deliberately moved from current events to the archive.
 *
 * @param int $event_id Event post ID.
 * @return bool
 */
function ingbiro_is_event_archived( $event_id ) {
	return (bool) get_post_meta( $event_id, 'ing_event_archived', true );
}

/**
 * Return the public URL for either kind of archive entry.
 *
 * @param WP_Post $post Archive or event post.
 * @return string
 */
function ingbiro_archive_entry_url( $post ) {
	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	return get_permalink( $post );
}

/**
 * Human-readable date shared by imported archives and native events.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function ingbiro_archive_entry_date_label( $post_id ) {
	if ( 'ing_event' === get_post_type( $post_id ) ) {
		return (string) get_post_meta( $post_id, 'ing_event_date', true );
	}

	$label = (string) get_post_meta( $post_id, 'ing_archive_date_label', true );
	if ( $label ) {
		return $label;
	}

	$date = (string) get_post_meta( $post_id, 'ing_archive_date', true );
	return $date ? wp_date( get_option( 'date_format' ), strtotime( $date ) ) : '';
}

/**
 * Location shared by imported archives and native events.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function ingbiro_archive_entry_location( $post_id ) {
	if ( 'ing_event' === get_post_type( $post_id ) ) {
		return (string) get_post_meta( $post_id, 'ing_event_location', true );
	}

	return (string) get_post_meta( $post_id, 'ing_archive_location', true );
}

/**
 * Resolve relative legacy URLs against their source document.
 *
 * @param string $url      Relative or absolute URL.
 * @param string $base_url Source document URL.
 * @return string
 */
function ingbiro_resolve_legacy_url( $url, $base_url ) {
	$url = trim( html_entity_decode( (string) $url, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
	if ( '' === $url || str_starts_with( $url, '#' ) || preg_match( '#^(?:mailto:|tel:|data:|javascript:)#i', $url ) ) {
		return $url;
	}
	if ( preg_match( '#^https?://#i', $url ) ) {
		return $url;
	}
	if ( str_starts_with( $url, '//' ) ) {
		return 'https:' . $url;
	}

	$base = wp_parse_url( $base_url );
	if ( empty( $base['host'] ) ) {
		return $url;
	}

	$scheme = $base['scheme'] ?? 'https';
	if ( str_starts_with( $url, '/' ) ) {
		return $scheme . '://' . $base['host'] . $url;
	}

	$path     = $base['path'] ?? '/';
	$segments = explode( '/', trim( dirname( $path ) . '/' . $url, '/' ) );
	$resolved = array();
	foreach ( $segments as $segment ) {
		if ( '' === $segment || '.' === $segment ) {
			continue;
		}
		if ( '..' === $segment ) {
			array_pop( $resolved );
			continue;
		}
		$resolved[] = $segment;
	}

	return $scheme . '://' . $base['host'] . '/' . implode( '/', $resolved );
}

/**
 * Normalize whitespace from legacy HTML.
 *
 * @param string $value Raw text.
 * @return string
 */
function ingbiro_archive_clean_text( $value ) {
	return trim( preg_replace( '/\s+/u', ' ', html_entity_decode( (string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
}

/**
 * Convert Croatian display dates from the old archive into sortable ISO dates.
 *
 * @param string $label Display date.
 * @param int    $fallback_year Year supplied by the archive accordion.
 * @return string
 */
function ingbiro_archive_iso_date( $label, $fallback_year ) {
	$months = array(
		'sijecnja'  => 1,
		'veljace'   => 2,
		'ozujka'    => 3,
		'travnja'   => 4,
		'svibnja'   => 5,
		'lipnja'    => 6,
		'srpnja'    => 7,
		'kolovoza'  => 8,
		'rujna'     => 9,
		'listopada' => 10,
		'listopad'  => 10,
		'studenoga' => 11,
		'prosinca'  => 12,
	);

	if ( ! preg_match( '/(\d{1,2})\s*\.\s*(?:-\s*(\d{1,2})\s*\.)?\s*([\p{L}]+)\s+(\d{4})/u', $label, $matches ) ) {
		return sprintf( '%04d-01-01', absint( $fallback_year ) );
	}

	$month_key = strtolower( remove_accents( $matches[3] ) );
	$month     = $months[ $month_key ] ?? 1;
	$day       = ! empty( $matches[2] ) ? absint( $matches[2] ) : absint( $matches[1] );
	$year      = absint( $matches[4] ) ?: absint( $fallback_year );

	return sprintf( '%04d-%02d-%02d', $year, $month, max( 1, min( 31, $day ) ) );
}

/**
 * Download a legacy image once and return its local uploads URL.
 *
 * @param string $source_url Image URL.
 * @return string
 */
function ingbiro_mirror_legacy_image( $source_url ) {
	$uploads = wp_upload_dir();
	if ( ! empty( $uploads['error'] ) ) {
		return $source_url;
	}

	$path     = (string) wp_parse_url( $source_url, PHP_URL_PATH );
	$basename = sanitize_file_name( basename( $path ) );
	if ( '' === $basename || ! str_contains( $basename, '.' ) ) {
		$basename .= '.jpg';
	}

	$folder   = trailingslashit( $uploads['basedir'] ) . 'ingbiro-archive/assets';
	$filename = substr( sha1( $source_url ), 0, 12 ) . '-' . $basename;
	$target   = trailingslashit( $folder ) . $filename;
	$url      = trailingslashit( $uploads['baseurl'] ) . 'ingbiro-archive/assets/' . rawurlencode( $filename );

	if ( file_exists( $target ) ) {
		return $url;
	}

	$response = wp_remote_get(
		$source_url,
		array(
			'timeout'     => 25,
			'redirection' => 5,
			'user-agent'  => 'Ingbiro archive migration',
		)
	);
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return $source_url;
	}

	$body = wp_remote_retrieve_body( $response );
	if ( '' === $body || ! wp_mkdir_p( $folder ) ) {
		return $source_url;
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	if ( false === file_put_contents( $target, $body ) ) {
		return $source_url;
	}

	return $url;
}

/**
 * Convert a URL on this WordPress installation to a host-independent path.
 *
 * @param string $url Local URL.
 * @return string
 */
function ingbiro_archive_root_relative_url( $url ) {
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	return $path ?: $url;
}

/**
 * Mirror a legacy stylesheet, script, font or other page resource.
 *
 * Stylesheet url() references are rewritten to locally mirrored files too.
 *
 * @param string $source_url Resource URL.
 * @param bool   $process_css Whether to rewrite CSS dependencies.
 * @return string Root-relative local URL, or the original URL on failure.
 */
function ingbiro_mirror_legacy_resource( $source_url, $process_css = false ) {
	$uploads = wp_upload_dir();
	if ( ! empty( $uploads['error'] ) ) {
		return $source_url;
	}

	$path     = (string) wp_parse_url( $source_url, PHP_URL_PATH );
	$basename = sanitize_file_name( basename( $path ) );
	if ( '' === $basename ) {
		$basename = 'resource.bin';
	}

	$folder   = trailingslashit( $uploads['basedir'] ) . 'ingbiro-archive/original-assets';
	$filename = substr( sha1( $source_url ), 0, 12 ) . '-' . $basename;
	$target   = trailingslashit( $folder ) . $filename;
	$local    = '/wp-content/uploads/ingbiro-archive/original-assets/' . rawurlencode( $filename );

	if ( file_exists( $target ) ) {
		return $local;
	}

	$response = wp_remote_get(
		$source_url,
		array(
			'timeout'     => 30,
			'redirection' => 5,
			'user-agent'  => 'Ingbiro archive migration',
		)
	);
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return $source_url;
	}

	$body = wp_remote_retrieve_body( $response );
	if ( '' === $body || ! wp_mkdir_p( $folder ) ) {
		return $source_url;
	}

	if ( $process_css ) {
		$body = preg_replace_callback(
			'/url\(\s*([\'"]?)(?!data:)([^)\'"]+)\1\s*\)/i',
			static function ( $matches ) use ( $source_url ) {
				$dependency = ingbiro_resolve_legacy_url( trim( $matches[2] ), $source_url );
				$host       = strtolower( (string) wp_parse_url( $dependency, PHP_URL_HOST ) );
				if ( ! str_ends_with( $host, 'ingbiro.hr' ) ) {
					return $matches[0];
				}

				$is_image = (bool) preg_match( '/\.(?:avif|gif|jpe?g|png|svg|webp)(?:\?.*)?$/i', $dependency );
				$local    = $is_image
					? ingbiro_archive_root_relative_url( ingbiro_mirror_legacy_image( $dependency ) )
					: ingbiro_mirror_legacy_resource( $dependency );

				return 'url("' . esc_url_raw( $local ) . '")';
			},
			$body
		);
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	if ( false === file_put_contents( $target, $body ) ) {
		return $source_url;
	}

	return $local;
}

/**
 * Find and mirror the page-specific masthead image declared in legacy CSS.
 *
 * @param string $html       Legacy HTML document.
 * @param string $source_url Legacy document URL.
 * @return string
 */
function ingbiro_extract_legacy_hero_image( $html, $source_url ) {
	if ( ! preg_match_all( '#<link[^>]+href=["\']([^"\']+\.css)["\'][^>]*>#i', $html, $styles ) ) {
		return '';
	}

	foreach ( $styles[1] as $style ) {
		if ( ! str_contains( strtolower( $style ), 'agency' ) ) {
			continue;
		}

		$style_url = ingbiro_resolve_legacy_url( $style, $source_url );
		$response  = wp_remote_get( $style_url, array( 'timeout' => 25, 'redirection' => 5 ) );
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			continue;
		}

		$css = wp_remote_retrieve_body( $response );
		if ( preg_match( '/header\.masthead\s*\{.*?background-image\s*:\s*url\(\s*([\'"]?)([^)\'"]+)\1\s*\)/is', $css, $match ) ) {
			$image_url = ingbiro_resolve_legacy_url( trim( $match[2] ), $style_url );
			return ingbiro_mirror_legacy_image( $image_url );
		}
	}

	return '';
}

/**
 * Return the local static copy URL for a legacy archive post.
 *
 * @param int $post_id Archive post ID.
 * @return string
 */
function ingbiro_archive_original_url( $post_id ) {
	$relative_path = ltrim( (string) get_post_meta( $post_id, '_ingbiro_legacy_original_path', true ), '/' );
	if ( ! $relative_path ) {
		return '';
	}

	$uploads = wp_upload_dir();
	return trailingslashit( $uploads['baseurl'] ) . $relative_path;
}

/**
 * Build a concise, readable local slug from the archive title and date.
 *
 * The legacy filename remains available only for the untouched static copy.
 *
 * @param string $title Archive title.
 * @param string $date  ISO archive date.
 * @return string
 */
function ingbiro_archive_seo_slug( $title, $date = '' ) {
	$clean_title = html_entity_decode( wp_strip_all_tags( (string) $title ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$clean_title = preg_replace(
		'/^(?:(?:webinar|seminar|savjetovanje|radionica)(?:\s+uživo)?|online\s+(?:edukacija|savjetovanje))\s*[:\-–—]\s*/iu',
		'',
		$clean_title
	);
	$slug = sanitize_title( $clean_title );

	if ( strlen( $slug ) > 72 ) {
		$slug = substr( $slug, 0, 72 );
		$slug = preg_replace( '/-[^-]*$/', '', $slug );
	}

	$date_suffix = '';
	if ( preg_match( '/^(\d{4})-(\d{2})/', (string) $date, $matches ) ) {
		$date_suffix = '-' . $matches[1] . '-' . $matches[2];
	}

	return trim( ( $slug ?: 'arhivski-zapis' ) . $date_suffix, '-' );
}

/**
 * Replace links between old event pages with their new local detail URLs.
 *
 * @param string $url Absolute legacy URL.
 * @return string
 */
function ingbiro_localize_legacy_archive_link( $url ) {
	$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	if ( ! str_ends_with( $host, 'ingbiro.hr' ) || ! str_contains( $path, '/najava/savjetovanja/' ) ) {
		return $url;
	}

	$posts = get_posts(
		array(
			'post_type'      => 'ing_archive',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => 'ing_archive_legacy_url',
			'meta_value'     => $url,
		)
	);

	if ( ! $posts ) {
		$legacy_filename = basename( $path );
		$posts = $legacy_filename
			? get_posts(
				array(
					'post_type'      => 'ing_archive',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'meta_query'     => array(
						array(
							'key'     => 'ing_archive_legacy_url',
							'value'   => $legacy_filename,
							'compare' => 'LIKE',
						),
					),
				)
			)
			: array();
	}

	return $posts ? get_permalink( $posts[0] ) : home_url( '/arhiva/' );
}

/**
 * Save a self-contained local copy of the original legacy page.
 *
 * Registration forms and registration buttons are deliberately removed.
 *
 * @param string $html       Legacy HTML document.
 * @param string $source_url Legacy document URL.
 * @param string $slug       Local archive slug.
 * @return string Relative uploads path to index.html, or an empty string.
 */
function ingbiro_mirror_legacy_original_page( $html, $source_url, $slug ) {
	if ( ! class_exists( 'DOMDocument' ) || '' === trim( (string) $html ) ) {
		return '';
	}

	$previous = libxml_use_internal_errors( true );
	$document = new DOMDocument();
	$loaded   = $document->loadHTML( '<?xml encoding="utf-8" ?>' . $html, LIBXML_NOWARNING | LIBXML_NOERROR );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );
	if ( ! $loaded ) {
		return '';
	}

	$xpath = new DOMXPath( $document );
	$remove = array();
	foreach ( $xpath->query( '//comment() | //form | //*[contains(concat(" ", normalize-space(@class), " "), " btn ")]' ) as $node ) {
		$remove[] = $node;
	}
	foreach ( $xpath->query( '//a | //button' ) as $node ) {
		if ( preg_match( '/\bprijav/iu', ingbiro_archive_clean_text( $node->textContent ) ) ) {
			$remove[] = $node;
		}
	}
	$unique_remove = array();
	foreach ( $remove as $node ) {
		$unique_remove[ spl_object_hash( $node ) ] = $node;
	}
	foreach ( $unique_remove as $node ) {
		if ( $node->parentNode ) {
			$node->parentNode->removeChild( $node );
		}
	}

	foreach ( $xpath->query( '//*[@src]' ) as $node ) {
		$source = ingbiro_resolve_legacy_url( $node->getAttribute( 'src' ), $source_url );
		if ( ! preg_match( '#^https?://#i', $source ) ) {
			continue;
		}

		$host = strtolower( (string) wp_parse_url( $source, PHP_URL_HOST ) );
		if ( ! str_ends_with( $host, 'ingbiro.hr' ) ) {
			continue;
		}

		$is_image = 'img' === strtolower( $node->nodeName ) || 'source' === strtolower( $node->nodeName );
		$local    = $is_image
			? ingbiro_archive_root_relative_url( ingbiro_mirror_legacy_image( $source ) )
			: ingbiro_mirror_legacy_resource( $source );
		$node->setAttribute( 'src', $local );
		$node->removeAttribute( 'srcset' );
	}

	foreach ( $xpath->query( '//link[@href]' ) as $node ) {
		$href = ingbiro_resolve_legacy_url( $node->getAttribute( 'href' ), $source_url );
		$host = strtolower( (string) wp_parse_url( $href, PHP_URL_HOST ) );
		if ( str_ends_with( $host, 'ingbiro.hr' ) ) {
			$node->setAttribute( 'href', ingbiro_mirror_legacy_resource( $href, str_ends_with( strtolower( (string) wp_parse_url( $href, PHP_URL_PATH ) ), '.css' ) ) );
		}
	}

	foreach ( $xpath->query( '//a[@href]' ) as $node ) {
		$href = $node->getAttribute( 'href' );
		if ( str_starts_with( $href, '#' ) || preg_match( '#^(?:mailto:|tel:)#i', $href ) ) {
			continue;
		}
		$absolute = ingbiro_resolve_legacy_url( $href, $source_url );
		$node->setAttribute( 'href', ingbiro_localize_legacy_archive_link( $absolute ) );
	}

	$head = $document->getElementsByTagName( 'head' )->item( 0 );
	if ( $head ) {
		$robots = $document->createElement( 'meta' );
		$robots->setAttribute( 'name', 'robots' );
		$robots->setAttribute( 'content', 'noindex,nofollow' );
		$head->appendChild( $robots );

		$style = $document->createElement(
			'style',
			'.btn, form, [class*="registration"], [class*="prijava"] { display: none !important; }'
		);
		$head->appendChild( $style );
	}

	$output = $document->saveHTML();
	$output = preg_replace( '/^<\?xml[^>]+>\s*/', '', $output );
	$output = preg_replace( '/&lt;!--.*?--&gt;/su', '', $output );
	$output = preg_replace( '/&lt;!\s*(?:[-–—]|&#(?:8211|8212);)+.*?(?:[-–—]|&#(?:8211|8212);)+\s*&gt;/isu', '', $output );

	$uploads      = wp_upload_dir();
	$relative_dir = 'ingbiro-archive/original/' . sanitize_title( $slug );
	$folder       = trailingslashit( $uploads['basedir'] ) . $relative_dir;
	if ( ! wp_mkdir_p( $folder ) ) {
		return '';
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	if ( false === file_put_contents( trailingslashit( $folder ) . 'index.html', $output ) ) {
		return '';
	}

	return $relative_dir . '/index.html';
}

/**
 * Extract the useful program from a legacy standalone page into local content.
 *
 * @param string $html       Legacy HTML document.
 * @param string $source_url Legacy document URL.
 * @return string
 */
function ingbiro_extract_legacy_archive_content( $html, $source_url ) {
	if ( ! class_exists( 'DOMDocument' ) || '' === trim( (string) $html ) ) {
		return '';
	}

	$previous = libxml_use_internal_errors( true );
	$document = new DOMDocument();
	$loaded   = $document->loadHTML( '<?xml encoding="utf-8" ?>' . $html, LIBXML_NOWARNING | LIBXML_NOERROR );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );
	if ( ! $loaded ) {
		return '';
	}

	$xpath = new DOMXPath( $document );
	foreach ( array( '//script', '//style', '//form', '//*[contains(concat(" ", normalize-space(@class), " "), " btn ")]' ) as $query ) {
		$remove = array();
		foreach ( $xpath->query( $query ) as $node ) {
			$remove[] = $node;
		}
		foreach ( $remove as $node ) {
			if ( $node->parentNode ) {
				$node->parentNode->removeChild( $node );
			}
		}
	}

	$content_nodes = array();
	$query         = '//body/header/following-sibling::div[contains(concat(" ", normalize-space(@class), " "), " container ")][1] | //body/section[not(@id = "sponzori")]';
	foreach ( $xpath->query( $query ) as $node ) {
		$content_nodes[] = $node;
	}
	if ( ! $content_nodes ) {
		return '';
	}

	$images = array();
	foreach ( $content_nodes as $content_node ) {
		foreach ( $content_node->getElementsByTagName( 'img' ) as $image ) {
			$images[] = $image;
		}
	}
	foreach ( $images as $image ) {
		$src = ingbiro_resolve_legacy_url( $image->getAttribute( 'src' ), $source_url );
		if ( $src && preg_match( '#^https?://#i', $src ) ) {
			$host = strtolower( (string) wp_parse_url( $src, PHP_URL_HOST ) );
			$image->setAttribute( 'src', str_ends_with( $host, 'ingbiro.hr' ) ? ingbiro_mirror_legacy_image( $src ) : $src );
			$image->removeAttribute( 'srcset' );
		}
	}

	foreach ( $content_nodes as $content_node ) {
		foreach ( $content_node->getElementsByTagName( 'a' ) as $link ) {
			$href = $link->getAttribute( 'href' );
			if ( $href && ! str_starts_with( $href, '#' ) ) {
				$link->setAttribute( 'href', ingbiro_resolve_legacy_url( $href, $source_url ) );
			}
		}
	}

	$content = '';
	foreach ( $content_nodes as $content_node ) {
		$content .= $document->saveHTML( $content_node );
	}
	$content = preg_replace( '/&lt;!--.*?--&gt;/su', '', $content );
	$content = preg_replace( '/&lt;!\s*(?:[-–—]|&#(?:8211|8212);)+.*?(?:[-–—]|&#(?:8211|8212);)+\s*&gt;/isu', '', $content );

	$allowed            = wp_kses_allowed_html( 'post' );
	$allowed['section'] = array(
		'id'    => true,
		'class' => true,
	);
	$allowed['font']    = array(
		'color' => true,
		'size'  => true,
	);

	$safe_content = wp_kses( $content, $allowed );
	$safe_content = preg_replace( '/&lt;!\s*(?:[-–—]|&#(?:8211|8212);)+.*?(?:[-–—]|&#(?:8211|8212);)+\s*&gt;/isu', '', $safe_content );

	return '<div class="legacy-archive-content">' . $safe_content . '</div>';
}

/**
 * Read the old archive index into structured records.
 *
 * @param string $archive_url Old archive URL.
 * @return array|WP_Error
 */
function ingbiro_parse_legacy_archive( $archive_url ) {
	$response = wp_remote_get(
		$archive_url,
		array(
			'timeout'     => 30,
			'redirection' => 5,
			'user-agent'  => 'Ingbiro archive migration',
		)
	);
	if ( is_wp_error( $response ) ) {
		return $response;
	}
	if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return new WP_Error( 'archive_http_error', 'Stara arhiva nije dostupna.' );
	}

	$html = wp_remote_retrieve_body( $response );

	/*
	 * The old site hid the linked 2017-2020 archive panels by wrapping each
	 * complete panel in an HTML comment. Selectively restore only those known
	 * year panels before DOM parsing; unrelated comments remain untouched.
	 */
	$html = preg_replace_callback(
		'/<!--(.*?)-->/su',
		function ( $matches ) {
			return preg_match( '/id=["\']collapse20(?:17|18|19|20)["\']/', $matches[1] )
				? $matches[1]
				: $matches[0];
		},
		$html
	);

	$previous = libxml_use_internal_errors( true );
	$document = new DOMDocument();
	$loaded   = $document->loadHTML( '<?xml encoding="utf-8" ?>' . $html, LIBXML_NOWARNING | LIBXML_NOERROR );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );
	if ( ! $loaded ) {
		return new WP_Error( 'archive_parse_error', 'HTML stare arhive nije moguće pročitati.' );
	}

	$xpath   = new DOMXPath( $document );
	$records = array();
	foreach ( $xpath->query( '//div[starts-with(@id, "collapse20")]' ) as $panel ) {
		$year = absint( preg_replace( '/\D+/', '', $panel->getAttribute( 'id' ) ) );
		if ( ! $year ) {
			continue;
		}

		foreach ( $xpath->query( './/p[.//a//strong]', $panel ) as $paragraph ) {
			$link   = $xpath->query( './/a[.//strong]', $paragraph )->item( 0 );
			$strong = $xpath->query( './/a//strong', $paragraph )->item( 0 );
			if ( ! $link || ! $strong ) {
				continue;
			}

			$title     = ingbiro_archive_clean_text( $strong->textContent );
			$full_text = ingbiro_archive_clean_text( $paragraph->textContent );
			$details   = str_starts_with( $full_text, $title ) ? trim( substr( $full_text, strlen( $title ) ) ) : $full_text;
			$date      = '';
			$location  = $details;
			$pattern   = '/(\d{1,2}(?:\s*\.\s*-\s*\d{1,2})?\s*\.\s*[\p{L}]+\s+' . $year . '\.?)/u';
			if ( preg_match_all( $pattern, $details, $date_matches, PREG_OFFSET_CAPTURE ) ) {
				$last_match = end( $date_matches[1] );
				$date       = ingbiro_archive_clean_text( $last_match[0] );
				$location   = trim( substr( $details, 0, $last_match[1] ), " \t\n\r\0\x0B·-" );
			}

			$source_url = ingbiro_resolve_legacy_url( $link->getAttribute( 'href' ), $archive_url );
			if ( '' === $title || '' === $source_url ) {
				continue;
			}

			$records[] = array(
				'title'      => $title,
				'location'   => $location,
				'date_label' => $date,
				'date'       => ingbiro_archive_iso_date( $date, $year ),
				'source_url' => $source_url,
				'year'       => $year,
			);
		}
	}

	/*
	 * These live legacy education pages were never linked from arhiva.html,
	 * but they are substantial, distinct pages and must not fall back to the
	 * generic archive landing page.
	 */
	$supplemental_records = array(
		array(
			'title'      => 'WEBINAR: Kontinuirana edukacija sporednih posrednika u osiguranju',
			'location'   => 'Online',
			'date_label' => '28. - 29. rujna 2021.',
			'date'       => '2021-09-28',
			'source_url' => 'https://www.ingbiro.hr/najava/savjetovanja/w202109_osiguranje.html',
			'year'       => 2021,
		),
		array(
			'title'      => 'WEBINAR: Pravila izrade općih akata',
			'location'   => 'Online',
			'date_label' => '13. listopada 2022.',
			'date'       => '2022-10-13',
			'source_url' => 'https://www.ingbiro.hr/najava/savjetovanja/w202210_opci_akti.html',
			'year'       => 2022,
		),
		array(
			'title'      => 'WEBINAR: Program prakse poslovnog prava',
			'location'   => 'Online',
			'date_label' => '16. ožujka - 11. svibnja 2023.',
			'date'       => '2023-03-16',
			'source_url' => 'https://www.ingbiro.hr/najava/savjetovanja/w202303legal-4p.html',
			'year'       => 2023,
		),
		array(
			'title'      => 'WEBINAR: Pravo društava',
			'location'   => 'Online',
			'date_label' => '3. svibnja 2023.',
			'date'       => '2023-05-03',
			'source_url' => 'https://www.ingbiro.hr/najava/savjetovanja/w202303-02-04pravo_drustava.html',
			'year'       => 2023,
		),
		array(
			'title'      => 'WEBINAR: Trgovačko pravo',
			'location'   => 'Online',
			'date_label' => '11. svibnja 2023.',
			'date'       => '2023-05-11',
			'source_url' => 'https://www.ingbiro.hr/najava/savjetovanja/w202303-01-05trgovacko_pravo.html',
			'year'       => 2023,
		),
	);

	$known_sources = array();
	foreach ( $records as $record ) {
		$known_sources[ strtolower( untrailingslashit( $record['source_url'] ) ) ] = true;
	}
	foreach ( $supplemental_records as $record ) {
		$key = strtolower( untrailingslashit( $record['source_url'] ) );
		if ( ! isset( $known_sources[ $key ] ) ) {
			$records[]             = $record;
			$known_sources[ $key ] = true;
		}
	}

	return $records;
}

/**
 * Remove the five known placeholder records from the first theme demo.
 */
function ingbiro_remove_demo_archive_items() {
	$slugs = array(
		'savjetovanje-12-2025',
		'savjetovanje-10-2025',
		'savjetovanje-09-2025',
		'savjetovanje-04-2025',
		'savjetovanje-02-2025',
	);
	foreach ( $slugs as $slug ) {
		$post = get_page_by_path( $slug, OBJECT, 'ing_archive' );
		if ( $post && 'https://ingbiro.hr/' === get_post_meta( $post->ID, 'ing_archive_url', true ) ) {
			wp_trash_post( $post->ID );
		}
	}
}

/**
 * Import or refresh the legacy archive.
 *
 * @param string $archive_url Old archive index URL.
 * @return array|WP_Error
 */
function ingbiro_import_legacy_archive( $archive_url = 'https://www.ingbiro.hr/arhiva.html' ) {
	$records = ingbiro_parse_legacy_archive( $archive_url );
	if ( is_wp_error( $records ) ) {
		return $records;
	}

	ingbiro_remove_demo_archive_items();
	$stats = array(
		'total'     => count( $records ),
		'created'   => 0,
		'updated'   => 0,
		'content'   => 0,
		'heroes'    => 0,
		'originals' => 0,
		'failed'    => 0,
	);

	foreach ( $records as $record ) {
		$legacy_key = sha1( $record['source_url'] . '|' . $record['date'] . '|' . $record['title'] );
		$existing = get_posts(
			array(
				'post_type'      => 'ing_archive',
				'post_status'    => array( 'publish', 'draft', 'trash' ),
				'posts_per_page' => 1,
				'meta_key'       => 'ing_archive_legacy_key',
				'meta_value'     => $legacy_key,
			)
		);
		if ( ! $existing ) {
			$existing = get_posts(
				array(
					'post_type'      => 'ing_archive',
					'post_status'    => array( 'publish', 'draft', 'trash' ),
					'posts_per_page' => 1,
					'meta_query'     => array(
						'relation' => 'AND',
						array(
							'key'   => 'ing_archive_legacy_url',
							'value' => $record['source_url'],
						),
						array(
							'key'   => 'ing_archive_date',
							'value' => $record['date'],
						),
					),
				)
			);
		}
		$post        = $existing ? $existing[0] : null;
		$legacy_slug = sanitize_title( pathinfo( (string) wp_parse_url( $record['source_url'], PHP_URL_PATH ), PATHINFO_FILENAME ) );
		if ( '' === $legacy_slug ) {
			$legacy_slug = sanitize_title( $record['title'] . '-' . $record['date'] );
		}
		$seo_slug = ingbiro_archive_seo_slug( $record['title'], $record['date'] );

		$content         = $post ? (string) $post->post_content : '';
		$hero_url        = $post ? (string) get_post_meta( $post->ID, 'ing_archive_hero_url', true ) : '';
		$original_path   = $post ? (string) get_post_meta( $post->ID, '_ingbiro_legacy_original_path', true ) : '';
		$source_host     = strtolower( (string) wp_parse_url( $record['source_url'], PHP_URL_HOST ) );
		$content_version = $post ? (string) get_post_meta( $post->ID, '_ingbiro_legacy_content_version', true ) : '';
		$detail_refreshed = false;
		if ( str_ends_with( $source_host, 'ingbiro.hr' ) && version_compare( $content_version, '1.2.3', '<' ) ) {
			$detail_response = wp_remote_get(
				$record['source_url'],
				array(
					'timeout'     => 30,
					'redirection' => 5,
					'user-agent'  => 'Ingbiro archive migration',
				)
			);
			if ( ! is_wp_error( $detail_response ) && 200 === wp_remote_retrieve_response_code( $detail_response ) ) {
				$detail_html       = wp_remote_retrieve_body( $detail_response );
				$refreshed_content = ingbiro_extract_legacy_archive_content( $detail_html, $record['source_url'] );
				if ( $refreshed_content ) {
					$content = $refreshed_content;
					++$stats['content'];
				}
				$refreshed_hero = ingbiro_extract_legacy_hero_image( $detail_html, $record['source_url'] );
				if ( $refreshed_hero ) {
					$hero_url = $refreshed_hero;
					++$stats['heroes'];
				}
				$refreshed_original = ingbiro_mirror_legacy_original_page( $detail_html, $record['source_url'], $legacy_slug );
				if ( $refreshed_original ) {
					$original_path = $refreshed_original;
					++$stats['originals'];
				}
				$detail_refreshed = true;
			} else {
				++$stats['failed'];
			}
		}

		$post_data = array(
			'post_type'    => 'ing_archive',
			'post_status'  => 'publish',
			'post_name'    => $seo_slug,
			'post_title'   => $record['title'],
			'post_excerpt' => trim( $record['location'] . ( $record['date_label'] ? ' · ' . $record['date_label'] : '' ) ),
			'post_content' => $content,
		);
		if ( $post ) {
			$post_data['ID'] = $post->ID;
			$post_id         = wp_update_post( $post_data, true );
			++$stats['updated'];
		} else {
			$post_id = wp_insert_post( $post_data, true );
			++$stats['created'];
		}
		if ( is_wp_error( $post_id ) ) {
			++$stats['failed'];
			continue;
		}

		update_post_meta( $post_id, 'ing_archive_date', $record['date'] );
		update_post_meta( $post_id, 'ing_archive_date_label', $record['date_label'] );
		update_post_meta( $post_id, 'ing_archive_location', $record['location'] );
		update_post_meta( $post_id, 'ing_archive_url', get_permalink( $post_id ) );
		update_post_meta( $post_id, 'ing_archive_legacy_url', $record['source_url'] );
		update_post_meta( $post_id, 'ing_archive_legacy_key', $legacy_key );
		update_post_meta( $post_id, 'ing_archive_hero_url', $hero_url );
		update_post_meta( $post_id, '_ingbiro_legacy_original_path', $original_path );
		update_post_meta( $post_id, '_ingbiro_legacy_import', 1 );
		if ( $detail_refreshed ) {
			update_post_meta( $post_id, '_ingbiro_legacy_content_version', '1.2.3' );
		}
	}

	update_option( 'ingbiro_legacy_archive_imported_at', gmdate( 'c' ) );
	flush_rewrite_rules( false );

	return $stats;
}

/**
 * Migrate filename-like local archive URLs to readable SEO slugs once.
 */
function ingbiro_migrate_archive_seo_slugs() {
	if ( version_compare( (string) get_option( 'ingbiro_archive_seo_slugs_version', '0' ), '1.0.0', '>=' ) ) {
		return;
	}

	$posts = get_posts(
		array(
			'post_type'      => 'ing_archive',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'ASC',
		)
	);

	foreach ( $posts as $post ) {
		$date = (string) get_post_meta( $post->ID, 'ing_archive_date', true );
		$slug = ingbiro_archive_seo_slug( $post->post_title, $date );
		if ( $slug && $slug !== $post->post_name ) {
			wp_update_post(
				array(
					'ID'        => $post->ID,
					'post_name' => $slug,
				)
			);
		}
		update_post_meta( $post->ID, 'ing_archive_url', get_permalink( $post->ID ) );
	}

	update_option( 'ingbiro_archive_seo_slugs_version', '1.0.0' );
	flush_rewrite_rules( false );
}
add_action( 'init', 'ingbiro_migrate_archive_seo_slugs', 46 );

/**
 * Ensure the Croatian archive landing page exists.
 *
 * The English site deliberately has no archive section. Remove the previously
 * provisioned English page when upgrading from the first archive installer.
 */
function ingbiro_install_archive_pages() {
	if ( version_compare( (string) get_option( 'ingbiro_archive_pages_version', '0' ), '1.1.0', '>=' ) ) {
		return;
	}

	$croatian = get_page_by_path( 'arhiva' );
	if ( ! $croatian ) {
		$croatian_id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_name'   => 'arhiva',
				'post_title'  => 'Arhiva',
			)
		);
		$croatian    = get_post( $croatian_id );
	}
	if ( $croatian ) {
		update_post_meta( $croatian->ID, '_wp_page_template', 'page-arhiva.php' );
		delete_post_meta( $croatian->ID, '_ingbiro_translation_id' );
	}

	$english = get_page_by_path( 'en/archive', OBJECT, 'page' );
	if ( $english ) {
		wp_delete_post( $english->ID, true );
	}

	update_option( 'ingbiro_archive_pages_version', '1.1.0' );
}
add_action( 'init', 'ingbiro_install_archive_pages', 45 );
