<?php
/**
 * Inženjerski biro theme setup and content model.
 *
 * @package Ingbiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'INGBIRO_VERSION', '1.0.0' );

function ingbiro_setup() {
	load_theme_textdomain( 'ingbiro', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Glavni meni', 'ingbiro' ),
			'footer'  => __( 'Footer meni', 'ingbiro' ),
		)
	);
}
add_action( 'after_setup_theme', 'ingbiro_setup' );

function ingbiro_enqueue_assets() {
	wp_enqueue_style( 'ingbiro-style', get_stylesheet_uri(), array(), INGBIRO_VERSION );
	wp_enqueue_script(
		'ingbiro-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		INGBIRO_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'ingbiro_enqueue_assets' );

function ingbiro_asset( $path ) {
	return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
}

function ingbiro_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . trim( $slug, '/' ) . '/' );
}

function ingbiro_primary_menu_fallback() {
	$items = array(
		'o-nama'                     => 'O nama',
		'konzalting'                 => 'Konzalting',
		'pravni-portal'              => 'Pravni portal',
		'savjetovanja-i-edukacije'   => 'Savjetovanja i edukacije',
	);

	echo '<ul class="site-nav__list">';
	foreach ( $items as $slug => $label ) {
		$current = is_page( $slug ) ? ' class="current-menu-item"' : '';
		printf(
			'<li%s><a href="%s">%s</a></li>',
			$current,
			esc_url( ingbiro_page_url( $slug ) ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

function ingbiro_section_label( $label, $light = false ) {
	printf(
		'<div class="section-label%s"><span class="section-label__gear" aria-hidden="true"></span><span>%s</span></div>',
		$light ? ' section-label--light' : '',
		esc_html( $label )
	);
}

function ingbiro_button( $label, $url, $class = '' ) {
	printf(
		'<a class="pill-button %s" href="%s"><span>%s</span><span class="pill-button__icon" aria-hidden="true">→</span></a>',
		esc_attr( $class ),
		esc_url( $url ),
		esc_html( $label )
	);
}

function ingbiro_building_banner() {
	printf(
		'<div class="building-banner" aria-hidden="true"><img src="%s" alt=""></div>',
		esc_url( ingbiro_asset( 'images/building.png' ) )
	);
}

function ingbiro_register_content_types() {
	register_post_type(
		'ing_event',
		array(
			'labels' => array(
				'name'          => __( 'Događanja', 'ingbiro' ),
				'singular_name' => __( 'Događanje', 'ingbiro' ),
				'add_new_item'  => __( 'Dodaj događanje', 'ingbiro' ),
				'edit_item'     => __( 'Uredi događanje', 'ingbiro' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-calendar-alt',
			'has_archive'  => 'arhiva-dogadanja',
			'rewrite'      => array( 'slug' => 'dogadanje' ),
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);

	register_post_type(
		'ing_job',
		array(
			'labels' => array(
				'name'          => __( 'Pozicije', 'ingbiro' ),
				'singular_name' => __( 'Pozicija', 'ingbiro' ),
				'add_new_item'  => __( 'Dodaj poziciju', 'ingbiro' ),
				'edit_item'     => __( 'Uredi poziciju', 'ingbiro' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-businessperson',
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'pozicija' ),
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);

	register_post_type(
		'ing_submission',
		array(
			'labels' => array(
				'name'          => __( 'Web prijave', 'ingbiro' ),
				'singular_name' => __( 'Web prijava', 'ingbiro' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-email-alt2',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title', 'editor' ),
			'exclude_from_search' => true,
		)
	);
}
add_action( 'init', 'ingbiro_register_content_types' );

function ingbiro_register_meta() {
	$event_meta = array(
		'ing_event_date'    => 'string',
		'ing_event_format'  => 'string',
		'ing_event_hours'   => 'string',
		'ing_event_speaker' => 'string',
		'ing_event_fee'     => 'string',
	);

	foreach ( $event_meta as $key => $type ) {
		register_post_meta(
			'ing_event',
			$key,
			array(
				'type'              => $type,
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	register_post_meta(
		'ing_job',
		'ing_job_location',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', 'ingbiro_register_meta' );

function ingbiro_add_meta_boxes() {
	add_meta_box( 'ing_event_details', __( 'Detalji događanja', 'ingbiro' ), 'ingbiro_event_meta_box', 'ing_event', 'normal', 'high' );
	add_meta_box( 'ing_job_details', __( 'Detalji pozicije', 'ingbiro' ), 'ingbiro_job_meta_box', 'ing_job', 'side' );
}
add_action( 'add_meta_boxes', 'ingbiro_add_meta_boxes' );

function ingbiro_event_meta_box( $post ) {
	wp_nonce_field( 'ingbiro_save_event', 'ingbiro_event_nonce' );
	$fields = array(
		'ing_event_date'    => __( 'Datum', 'ingbiro' ),
		'ing_event_format'  => __( 'Format (npr. UŽIVO, WEBINAR)', 'ingbiro' ),
		'ing_event_hours'   => __( 'Vrijeme / broj sati', 'ingbiro' ),
		'ing_event_speaker' => __( 'Predavač', 'ingbiro' ),
		'ing_event_fee'     => __( 'Kotizacija', 'ingbiro' ),
	);

	foreach ( $fields as $key => $label ) {
		printf(
			'<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" id="%1$s" name="%1$s" value="%3$s"></p>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( get_post_meta( $post->ID, $key, true ) )
		);
	}
}

function ingbiro_job_meta_box( $post ) {
	wp_nonce_field( 'ingbiro_save_job', 'ingbiro_job_nonce' );
	printf(
		'<p><label for="ing_job_location"><strong>%s</strong></label><input class="widefat" id="ing_job_location" name="ing_job_location" value="%s"></p>',
		esc_html__( 'Lokacija / način rada', 'ingbiro' ),
		esc_attr( get_post_meta( $post->ID, 'ing_job_location', true ) )
	);
}

function ingbiro_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['ingbiro_event_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ingbiro_event_nonce'] ) ), 'ingbiro_save_event' ) ) {
		$keys = array( 'ing_event_date', 'ing_event_format', 'ing_event_hours', 'ing_event_speaker', 'ing_event_fee' );
		foreach ( $keys as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
			}
		}
	}

	if ( isset( $_POST['ingbiro_job_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ingbiro_job_nonce'] ) ), 'ingbiro_save_job' ) && isset( $_POST['ing_job_location'] ) ) {
		update_post_meta( $post_id, 'ing_job_location', sanitize_text_field( wp_unslash( $_POST['ing_job_location'] ) ) );
	}
}
add_action( 'save_post', 'ingbiro_save_meta' );

function ingbiro_handle_submission() {
	if ( ! isset( $_POST['ingbiro_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ingbiro_nonce'] ) ), 'ingbiro_submit' ) ) {
		wp_die( esc_html__( 'Sigurnosna provjera nije uspjela.', 'ingbiro' ), 403 );
	}

	$referer = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'status', 'success', $referer ) );
		exit;
	}

	$type  = isset( $_POST['submission_type'] ) ? sanitize_key( wp_unslash( $_POST['submission_type'] ) ) : 'contact';
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';

	if ( ! $email || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'status', 'invalid-email', $referer ) );
		exit;
	}

	$allowed_types = array( 'contact', 'newsletter', 'event', 'career' );
	if ( ! in_array( $type, $allowed_types, true ) ) {
		$type = 'contact';
	}

	$labels = array(
		'contact'    => 'Kontakt upit',
		'newsletter' => 'Newsletter pretplata',
		'event'      => 'Prijava za edukaciju',
		'career'     => 'Prijava za posao',
	);

	$lines = array();
	foreach ( $_POST as $key => $value ) {
		if ( in_array( $key, array( 'action', 'ingbiro_nonce', 'website', 'submission_type' ), true ) || is_array( $value ) ) {
			continue;
		}

		$clean_key   = sanitize_text_field( str_replace( '_', ' ', $key ) );
		$clean_value = 'message' === $key ? sanitize_textarea_field( wp_unslash( $value ) ) : sanitize_text_field( wp_unslash( $value ) );
		$lines[]     = ucfirst( $clean_key ) . ': ' . $clean_value;
	}

	$title = sprintf( '%s — %s', $labels[ $type ], $name ? $name : $email );
	$post_id = wp_insert_post(
		array(
			'post_type'    => 'ing_submission',
			'post_status'  => 'private',
			'post_title'   => $title,
			'post_content' => implode( "\n", $lines ),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		wp_safe_redirect( add_query_arg( 'status', 'error', $referer ) );
		exit;
	}

	update_post_meta( $post_id, 'ing_submission_type', $type );
	update_post_meta( $post_id, 'ing_submission_email', $email );

	if ( 'career' === $type && ! empty( $_FILES['cv']['name'] ) ) {
		$allowed_mimes = array(
			'pdf'  => 'application/pdf',
			'doc'  => 'application/msword',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		);

		$file_size = isset( $_FILES['cv']['size'] ) ? absint( $_FILES['cv']['size'] ) : 0;
		$file_type = wp_check_filetype( sanitize_file_name( wp_unslash( $_FILES['cv']['name'] ) ), $allowed_mimes );

		if ( $file_size > 0 && $file_size <= 5 * MB_IN_BYTES && $file_type['type'] ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$attachment_id = media_handle_upload(
				'cv',
				$post_id,
				array(),
				array( 'test_form' => false, 'mimes' => $allowed_mimes )
			);

			if ( ! is_wp_error( $attachment_id ) ) {
				$cv_url  = wp_get_attachment_url( $attachment_id );
				$lines[] = 'Životopis: ' . $cv_url;
				wp_update_post(
					array(
						'ID'           => $post_id,
						'post_content' => implode( "\n", $lines ),
					)
				);
			}
		}
	}

	wp_mail(
		get_option( 'admin_email' ),
		'[ingbiro.hr] ' . $title,
		implode( "\n", $lines ),
		array( 'Reply-To: ' . $email )
	);

	wp_safe_redirect( add_query_arg( 'status', 'success', $referer ) );
	exit;
}
add_action( 'admin_post_nopriv_ingbiro_submit', 'ingbiro_handle_submission' );
add_action( 'admin_post_ingbiro_submit', 'ingbiro_handle_submission' );

function ingbiro_form_status() {
	if ( empty( $_GET['status'] ) ) {
		return;
	}

	$status = sanitize_key( wp_unslash( $_GET['status'] ) );
	if ( 'success' === $status ) {
		echo '<div class="form-notice form-notice--success" role="status">Hvala! Vaši podaci su uspješno poslani.</div>';
	} elseif ( 'invalid-email' === $status ) {
		echo '<div class="form-notice form-notice--error" role="alert">Molimo unesite ispravnu e-mail adresu.</div>';
	} elseif ( 'error' === $status ) {
		echo '<div class="form-notice form-notice--error" role="alert">Slanje trenutno nije uspjelo. Molimo pokušajte ponovo.</div>';
	}
}

function ingbiro_install_content() {
	ingbiro_register_content_types();

	$pages = array(
		'naslovnica'                => array( 'Naslovnica', 'front-page.php' ),
		'o-nama'                    => array( 'O nama', 'page-o-nama.php' ),
		'konzalting'                => array( 'Konzalting', 'page-konzalting.php' ),
		'pravni-portal'             => array( 'Pravni portal', 'page-pravni-portal.php' ),
		'savjetovanja-i-edukacije'  => array( 'Savjetovanja i edukacije', 'page-savjetovanja-i-edukacije.php' ),
		'arhiva'                    => array( 'Arhiva', 'page-arhiva.php' ),
		'kontakt'                   => array( 'Kontakt', 'page-kontakt.php' ),
		'newsletter'                => array( 'Newsletter', 'page-newsletter.php' ),
		'karijera'                  => array( 'Karijera', 'page-karijera.php' ),
		'prijava-za-edukaciju'      => array( 'Prijava za edukaciju', 'page-prijava-za-edukaciju.php' ),
		'prijava-za-posao'          => array( 'Prijava za posao', 'page-prijava-za-posao.php' ),
		'politika-privatnosti'      => array( 'Politika privatnosti', 'page.php' ),
	);

	foreach ( $pages as $slug => $definition ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			$page_id = wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_title'  => $definition[0],
					'post_name'   => $slug,
				)
			);
		} else {
			$page_id = $page->ID;
		}

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, '_wp_page_template', $definition[1] );
		}
	}

	$front_page = get_page_by_path( 'naslovnica' );
	if ( $front_page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page->ID );
	}

	if ( ! get_posts( array( 'post_type' => 'ing_event', 'numberposts' => 1, 'post_status' => 'any' ) ) ) {
		$event_id = wp_insert_post(
			array(
				'post_type'    => 'ing_event',
				'post_status'  => 'publish',
				'post_title'   => 'Izmjene i dopune ZJN 2016 u odnosu na jednostavnu nabavu',
				'post_excerpt' => 'Program usavršavanja iz područja javne nabave.',
				'post_content' => 'Oslonite se na jasne smjernice i praktične primjere kako biste bez nedoumica uskladili jednostavnu nabavu s najnovijim izmjenama ZJN 2016.',
			)
		);
		update_post_meta( $event_id, 'ing_event_date', '11. lipnja 2026.' );
		update_post_meta( $event_id, 'ing_event_format', 'UŽIVO · WEBINAR' );
		update_post_meta( $event_id, 'ing_event_hours', '9:00 sati · 8 sati za obnovu certifikata' );
		update_post_meta( $event_id, 'ing_event_speaker', 'Ančica Jonjić, dipl. iur.' );
		update_post_meta( $event_id, 'ing_event_fee', '180,00 eura' );
	}

	if ( ! get_posts( array( 'post_type' => 'ing_job', 'numberposts' => 1, 'post_status' => 'any' ) ) ) {
		$job_id = wp_insert_post(
			array(
				'post_type'    => 'ing_job',
				'post_status'  => 'publish',
				'post_title'   => 'Otvorena prijava',
				'post_excerpt' => 'Pošaljite nam otvorenu prijavu i predstavite kako možete doprinijeti našem timu.',
				'post_content' => 'Tražimo stručne, radoznale i pouzdane kolegice i kolege iz područja prava, ekonomije, organizacije poslovanja i edukacije.',
			)
		);
		update_post_meta( $job_id, 'ing_job_location', 'Zagreb' );
	}

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'ingbiro_install_content' );
