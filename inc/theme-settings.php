<?php
/**
 * Global theme settings shared by every page template.
 *
 * @package Ingbiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const INGBIRO_BUILDING_BANNER_MEDIA_OPTION = 'ingbiro_building_banner_media_id';
const INGBIRO_BUILDING_BANNER_VIDEO_OPTION = 'ingbiro_building_banner_video_settings';

/**
 * Defaults preserve the current silent, automatic decorative animation.
 *
 * @return array<string, int|string>
 */
function ingbiro_default_building_banner_video_settings() {
	return array(
		'autoplay'   => 1,
		'loop'       => 1,
		'muted'      => 1,
		'controls'   => 0,
		'playsinline' => 1,
		'preload'    => 'auto',
	);
}

/**
 * Return normalized playback settings for frontend and admin preview use.
 *
 * @return array<string, int|string>
 */
function ingbiro_get_building_banner_video_settings() {
	$defaults = ingbiro_default_building_banner_video_settings();
	$stored   = get_option( INGBIRO_BUILDING_BANNER_VIDEO_OPTION, $defaults );
	return ingbiro_sanitize_building_banner_video_settings( wp_parse_args( is_array( $stored ) ? $stored : array(), $defaults ) );
}

/**
 * Determine whether a media source should render as a video or an image.
 *
 * @param string $mime Media MIME type.
 * @param string $url  Media URL.
 * @return string
 */
function ingbiro_building_banner_media_kind( $mime, $url = '' ) {
	if ( 0 === strpos( (string) $mime, 'video/' ) ) {
		return 'video';
	}

	if ( 0 === strpos( (string) $mime, 'image/' ) ) {
		return 'image';
	}

	$extension = strtolower( (string) pathinfo( (string) wp_parse_url( $url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
	if ( in_array( $extension, array( 'webm', 'mp4', 'm4v', 'mov', 'ogv', 'ogg' ), true ) ) {
		return 'video';
	}

	if ( in_array( $extension, array( 'jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg' ), true ) ) {
		return 'image';
	}

	return '';
}

/**
 * Bundled fallback used until an editor selects another Media Library item.
 *
 * @return array<string, mixed>
 */
function ingbiro_default_building_banner_media() {
	return array(
		'id'         => 0,
		'url'        => add_query_arg( 'ver', INGBIRO_VERSION, ingbiro_asset( 'video/ingbiro-animacija.webm' ) ),
		'mime'       => 'video/webm',
		'kind'       => 'video',
		'width'      => 1138,
		'height'     => 640,
		'label'      => 'INGbiro-animacija.webm',
		'is_default' => true,
	);
}

/**
 * Return the selected media, falling back safely when an attachment is removed.
 *
 * @return array<string, mixed>
 */
function ingbiro_get_building_banner_media() {
	$attachment_id = absint( get_option( INGBIRO_BUILDING_BANNER_MEDIA_OPTION, 0 ) );
	if ( ! $attachment_id ) {
		return ingbiro_default_building_banner_media();
	}

	$url  = wp_get_attachment_url( $attachment_id );
	$mime = (string) get_post_mime_type( $attachment_id );
	$kind = ingbiro_building_banner_media_kind( $mime, $url );
	if ( ! $url || ! $kind ) {
		return ingbiro_default_building_banner_media();
	}

	$metadata = wp_get_attachment_metadata( $attachment_id );
	$width    = ! empty( $metadata['width'] ) ? absint( $metadata['width'] ) : 1138;
	$height   = ! empty( $metadata['height'] ) ? absint( $metadata['height'] ) : 640;

	return array(
		'id'         => $attachment_id,
		'url'        => $url,
		'mime'       => $mime,
		'kind'       => $kind,
		'width'      => $width,
		'height'     => $height,
		'label'      => get_the_title( $attachment_id ) ?: wp_basename( (string) wp_parse_url( $url, PHP_URL_PATH ) ),
		'is_default' => false,
	);
}

/**
 * Render the selected asset without controls or redundant screen-reader output.
 *
 * @param array<string, mixed> $media Media data.
 * @param string               $class Element class.
 * @return string
 */
function ingbiro_building_banner_media_markup( $media, $class = 'building-banner__media' ) {
	if ( 'video' === $media['kind'] ) {
		$settings   = ingbiro_get_building_banner_video_settings();
		$attributes = '';
		foreach ( array( 'autoplay', 'muted', 'loop', 'controls', 'playsinline' ) as $attribute ) {
			if ( ! empty( $settings[ $attribute ] ) ) {
				$attributes .= ' ' . $attribute;
			}
		}

		if ( empty( $settings['controls'] ) ) {
			$attributes .= ' tabindex="-1" aria-hidden="true"';
		} else {
			$attributes .= ' aria-label="' . esc_attr__( 'Animacija zgrade', 'ingbiro' ) . '"';
		}

		return sprintf(
			'<video class="%1$s"%2$s preload="%3$s"><source src="%4$s" type="%5$s"></video>',
			esc_attr( $class . ' building-banner__video' ),
			$attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $settings['preload'] ),
			esc_url( $media['url'] ),
			esc_attr( $media['mime'] )
		);
	}

	return sprintf(
		'<img class="%1$s" src="%2$s" alt="" width="%3$d" height="%4$d" loading="lazy" decoding="async">',
		esc_attr( $class . ' building-banner__image' ),
		esc_url( $media['url'] ),
		absint( $media['width'] ),
		absint( $media['height'] )
	);
}

/**
 * Shared banner used by all relevant page templates.
 */
function ingbiro_building_banner() {
	$media            = ingbiro_get_building_banner_media();
	$video_settings   = ingbiro_get_building_banner_video_settings();
	$hidden_attribute = ( 'video' === $media['kind'] && ! empty( $video_settings['controls'] ) ) ? '' : ' aria-hidden="true"';
	printf(
		'<div class="building-banner-shell"><div class="building-banner" style="--building-banner-aspect: %1$d / %2$d;"%3$s>%4$s</div></div>',
		absint( $media['width'] ),
		absint( $media['height'] ),
		$hidden_attribute, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		ingbiro_building_banner_media_markup( $media ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}

/**
 * Reject documents or deleted/non-attachment IDs while preserving the old value.
 *
 * @param mixed $value Submitted option value.
 * @return int
 */
function ingbiro_sanitize_building_banner_media_id( $value ) {
	$attachment_id = absint( $value );
	if ( ! $attachment_id ) {
		return 0;
	}

	$url  = wp_get_attachment_url( $attachment_id );
	$mime = (string) get_post_mime_type( $attachment_id );
	if ( 'attachment' !== get_post_type( $attachment_id ) || ! ingbiro_building_banner_media_kind( $mime, $url ) ) {
		add_settings_error(
			INGBIRO_BUILDING_BANNER_MEDIA_OPTION,
			'ingbiro_building_banner_media_invalid',
			__( 'Odaberite video ili slikovni fajl iz Media Libraryja.', 'ingbiro' )
		);
		return absint( get_option( INGBIRO_BUILDING_BANNER_MEDIA_OPTION, 0 ) );
	}

	return $attachment_id;
}

/**
 * Normalize all editable video attributes and reject unsupported preload modes.
 *
 * @param mixed $value Submitted settings array.
 * @return array<string, int|string>
 */
function ingbiro_sanitize_building_banner_video_settings( $value ) {
	$value    = is_array( $value ) ? $value : array();
	$preload  = isset( $value['preload'] ) ? sanitize_key( $value['preload'] ) : 'auto';
	$preloads = array( 'none', 'metadata', 'auto' );

	return array(
		'autoplay'    => empty( $value['autoplay'] ) ? 0 : 1,
		'loop'        => empty( $value['loop'] ) ? 0 : 1,
		'muted'       => empty( $value['muted'] ) ? 0 : 1,
		'controls'    => empty( $value['controls'] ) ? 0 : 1,
		'playsinline' => empty( $value['playsinline'] ) ? 0 : 1,
		'preload'     => in_array( $preload, $preloads, true ) ? $preload : 'auto',
	);
}

function ingbiro_register_theme_settings() {
	register_setting(
		'ingbiro_theme_settings',
		INGBIRO_BUILDING_BANNER_MEDIA_OPTION,
		array(
			'type'              => 'integer',
			'sanitize_callback' => 'ingbiro_sanitize_building_banner_media_id',
			'default'           => 0,
			'show_in_rest'      => false,
		)
	);

	register_setting(
		'ingbiro_theme_settings',
		INGBIRO_BUILDING_BANNER_VIDEO_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'ingbiro_sanitize_building_banner_video_settings',
			'default'           => ingbiro_default_building_banner_video_settings(),
			'show_in_rest'      => false,
		)
	);
}
add_action( 'admin_init', 'ingbiro_register_theme_settings' );

function ingbiro_register_theme_settings_page() {
	add_theme_page(
		__( 'Postavke teme', 'ingbiro' ),
		__( 'Postavke teme', 'ingbiro' ),
		'edit_theme_options',
		'ingbiro-theme-settings',
		'ingbiro_render_theme_settings_page'
	);
}
add_action( 'admin_menu', 'ingbiro_register_theme_settings_page' );

/**
 * Load the Media Library picker only on this theme settings screen.
 *
 * @param string $hook_suffix Current admin page hook.
 */
function ingbiro_enqueue_theme_settings_assets( $hook_suffix ) {
	if ( 'appearance_page_ingbiro-theme-settings' !== $hook_suffix ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style(
		'ingbiro-theme-settings',
		ingbiro_asset( 'css/theme-settings.css' ),
		array(),
		INGBIRO_VERSION
	);
	wp_enqueue_script(
		'ingbiro-theme-settings',
		ingbiro_asset( 'js/theme-settings.js' ),
		array(),
		INGBIRO_VERSION,
		true
	);

	$default_media = ingbiro_default_building_banner_media();
	wp_localize_script(
		'ingbiro-theme-settings',
		'ingbiroThemeSettings',
		array(
			'frameTitle'      => __( 'Odaberite završni banner', 'ingbiro' ),
			'frameButton'     => __( 'Koristi ovaj fajl', 'ingbiro' ),
			'unsupported'     => __( 'Odaberite video ili slikovni fajl.', 'ingbiro' ),
			'defaultUrl'      => $default_media['url'],
			'defaultMime'     => $default_media['mime'],
			'defaultKind'     => $default_media['kind'],
			'defaultWidth'    => $default_media['width'],
			'defaultHeight'   => $default_media['height'],
			'defaultLabel'    => $default_media['label'],
		)
	);
}
add_action( 'admin_enqueue_scripts', 'ingbiro_enqueue_theme_settings_assets' );

/**
 * Render Appearance -> Theme settings.
 */
function ingbiro_render_theme_settings_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$media          = ingbiro_get_building_banner_media();
	$video_settings = ingbiro_get_building_banner_video_settings();
	?>
	<div class="wrap ingbiro-theme-settings">
		<h1><?php esc_html_e( 'Postavke teme', 'ingbiro' ); ?></h1>
		<p><?php esc_html_e( 'Ovdje se jednom mijenja zajednički završni banner koji se automatski prikazuje na svim pripadajućim stranicama.', 'ingbiro' ); ?></p>
		<?php settings_errors(); ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'ingbiro_theme_settings' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="ingbiro-building-banner-media-id"><?php esc_html_e( 'Animacija zgrade', 'ingbiro' ); ?></label></th>
					<td>
						<div class="ingbiro-media-setting" data-ingbiro-media-setting>
							<div class="ingbiro-media-preview" data-media-preview style="--ingbiro-preview-aspect: <?php echo absint( $media['width'] ); ?> / <?php echo absint( $media['height'] ); ?>;">
								<?php echo ingbiro_building_banner_media_markup( $media, 'ingbiro-media-preview__asset' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
							<input id="ingbiro-building-banner-media-id" name="<?php echo esc_attr( INGBIRO_BUILDING_BANNER_MEDIA_OPTION ); ?>" type="hidden" value="<?php echo absint( $media['id'] ); ?>" data-media-id>
							<p class="ingbiro-media-setting__actions">
								<button class="button button-secondary" type="button" data-media-select><?php esc_html_e( 'Odaberi ili zamijeni fajl', 'ingbiro' ); ?></button>
								<button class="button button-link-delete" type="button" data-media-reset <?php disabled( $media['is_default'] ); ?>><?php esc_html_e( 'Vrati zadani WEBM', 'ingbiro' ); ?></button>
							</p>
							<p class="description"><strong><?php esc_html_e( 'Trenutačno:', 'ingbiro' ); ?></strong> <span data-media-label><?php echo esc_html( $media['label'] ); ?></span></p>
							<p class="description"><?php esc_html_e( 'Podržani su video i slikovni formati koje WordPress prihvaća u Media Libraryju. Video se prikazuje automatski, utišano i u petlji, bez play gumba.', 'ingbiro' ); ?></p>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Postavke videa', 'ingbiro' ); ?></th>
					<td>
						<fieldset class="ingbiro-video-options" data-video-playback-settings>
							<legend class="screen-reader-text"><?php esc_html_e( 'Postavke reprodukcije završnog videa', 'ingbiro' ); ?></legend>
							<label><input type="checkbox" name="<?php echo esc_attr( INGBIRO_BUILDING_BANNER_VIDEO_OPTION ); ?>[autoplay]" value="1" data-video-setting="autoplay" <?php checked( $video_settings['autoplay'] ); ?>> <?php esc_html_e( 'Automatski pokreni video', 'ingbiro' ); ?></label>
							<label><input type="checkbox" name="<?php echo esc_attr( INGBIRO_BUILDING_BANNER_VIDEO_OPTION ); ?>[loop]" value="1" data-video-setting="loop" <?php checked( $video_settings['loop'] ); ?>> <?php esc_html_e( 'Ponavljaj video u petlji (loop)', 'ingbiro' ); ?></label>
							<label><input type="checkbox" name="<?php echo esc_attr( INGBIRO_BUILDING_BANNER_VIDEO_OPTION ); ?>[muted]" value="1" data-video-setting="muted" <?php checked( $video_settings['muted'] ); ?>> <?php esc_html_e( 'Utišaj zvuk', 'ingbiro' ); ?></label>
							<label><input type="checkbox" name="<?php echo esc_attr( INGBIRO_BUILDING_BANNER_VIDEO_OPTION ); ?>[controls]" value="1" data-video-setting="controls" <?php checked( $video_settings['controls'] ); ?>> <?php esc_html_e( 'Prikaži video kontrole', 'ingbiro' ); ?></label>
							<label><input type="checkbox" name="<?php echo esc_attr( INGBIRO_BUILDING_BANNER_VIDEO_OPTION ); ?>[playsinline]" value="1" data-video-setting="playsinline" <?php checked( $video_settings['playsinline'] ); ?>> <?php esc_html_e( 'Reproduciraj unutar stranice na mobitelu', 'ingbiro' ); ?></label>
							<label class="ingbiro-video-options__select" for="ingbiro-building-banner-preload">
								<span><?php esc_html_e( 'Učitavanje videa', 'ingbiro' ); ?></span>
								<select id="ingbiro-building-banner-preload" name="<?php echo esc_attr( INGBIRO_BUILDING_BANNER_VIDEO_OPTION ); ?>[preload]" data-video-setting="preload">
									<option value="auto" <?php selected( $video_settings['preload'], 'auto' ); ?>><?php esc_html_e( 'Automatski učitaj', 'ingbiro' ); ?></option>
									<option value="metadata" <?php selected( $video_settings['preload'], 'metadata' ); ?>><?php esc_html_e( 'Samo metadata', 'ingbiro' ); ?></option>
									<option value="none" <?php selected( $video_settings['preload'], 'none' ); ?>><?php esc_html_e( 'Ne učitavaj unaprijed', 'ingbiro' ); ?></option>
								</select>
							</label>
							<p class="description"><?php esc_html_e( 'Za pouzdan autoplay preglednici uglavnom zahtijevaju uključen utišani zvuk. Ove postavke primjenjuju se samo kada je odabrani banner video.', 'ingbiro' ); ?></p>
						</fieldset>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'Spremi postavke', 'ingbiro' ) ); ?>
		</form>
	</div>
	<?php
}
