<?php
/**
 * Site header.
 *
 * @package Ingbiro
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php echo esc_html( ingbiro_is_english() ? 'Skip to content' : 'Preskoči na sadržaj' ); ?></a>
<div class="site-shell">
	<header class="site-header">
		<div class="site-header__inner container">
			<a class="site-logo" href="<?php echo esc_url( ingbiro_language_home_url() ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/logo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			</a>

			<nav class="site-nav" id="site-navigation" aria-label="<?php echo esc_attr( ingbiro_is_english() ? 'Main menu' : 'Glavni meni' ); ?>">
				<?php
				if ( ingbiro_is_english() ) {
					ingbiro_english_menu();
				} else {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'site-nav__list',
							'fallback_cb'    => 'ingbiro_primary_menu_fallback',
						)
					);
				}
				?>
			</nav>

			<a class="contact-link" href="<?php echo esc_url( ingbiro_is_english() ? ingbiro_english_page_url( 'contact' ) : ingbiro_page_url( 'kontakt' ) ); ?>"><?php echo esc_html( ingbiro_is_english() ? 'Contact us' : 'Kontaktirajte nas' ); ?></a>

			<button
				class="menu-toggle"
				type="button"
				aria-controls="site-navigation"
				aria-expanded="false"
				data-open-label="<?php echo esc_attr( ingbiro_is_english() ? 'Open menu' : 'Otvori meni' ); ?>"
				data-close-label="<?php echo esc_attr( ingbiro_is_english() ? 'Close menu' : 'Zatvori meni' ); ?>"
			>
				<span class="menu-toggle__line" aria-hidden="true"></span>
				<span class="screen-reader-text"><?php echo esc_html( ingbiro_is_english() ? 'Open menu' : 'Otvori meni' ); ?></span>
			</button>
		</div>
	</header>
