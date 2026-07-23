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
<a class="skip-link screen-reader-text" href="#main">Preskoči na sadržaj</a>
<div class="site-shell">
	<header class="site-header">
		<div class="site-header__inner container">
			<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/logo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			</a>

			<nav class="site-nav" id="site-navigation" aria-label="Glavni meni">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'site-nav__list',
						'fallback_cb'    => 'ingbiro_primary_menu_fallback',
					)
				);
				?>
			</nav>

			<a class="contact-link" href="<?php echo esc_url( ingbiro_page_url( 'kontakt' ) ); ?>">Kontaktirajte nas</a>

			<button class="menu-toggle" type="button" aria-controls="site-navigation" aria-expanded="false">
				<span></span>
				<span class="screen-reader-text">Otvori meni</span>
			</button>
		</div>
	</header>

