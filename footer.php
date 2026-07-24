<?php
/**
 * Site footer.
 *
 * @package Ingbiro
 */
$is_english = ingbiro_is_english();
$language   = $is_english ? 'en' : 'hr';
?>
	<footer class="site-footer">
		<div class="container">
			<div class="site-footer__grid">
				<div class="site-footer__brand">
					<a href="<?php echo esc_url( ingbiro_language_home_url() ); ?>">
						<img src="<?php echo esc_url( ingbiro_asset( 'images/logo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					</a>
					<p class="site-footer__tagline"><?php echo esc_html( $is_english ? 'Consultants since 1952.' : 'Savjetnici od 1952.' ); ?></p>
					<ul class="contact-list">
						<li>
							<span class="contact-list__icon" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/footer-location.svg' ) ); ?>" alt=""></span>
							<a href="https://www.google.com/maps/search/?api=1&amp;query=Heinzelova+4A%2C+10000+Zagreb" target="_blank" rel="noopener noreferrer">Heinzelova 4A, 10000 Zagreb, <?php echo esc_html( $is_english ? 'Croatia' : 'Hrvatska' ); ?></a>
						</li>
						<li>
							<span class="contact-list__icon" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/footer-email.svg' ) ); ?>" alt=""></span>
							<span><a href="mailto:ingbiro@ingbiro.hr">ingbiro@ingbiro.hr</a> &nbsp; | &nbsp; <a href="mailto:prodaja@ingbiro.hr">prodaja@ingbiro.hr</a></span>
						</li>
						<li>
							<span class="contact-list__icon" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/footer-phone.svg' ) ); ?>" alt=""></span>
							<span><strong>Tel.</strong> <a href="tel:+38514600888">(+385) 1 46 00 888</a>; &nbsp; <strong>Fax.</strong> <a href="tel:+38514650366">(+385) 1 46 50 366</a></span>
						</li>
					</ul>
				</div>

				<ul class="footer-links">
					<li><a href="<?php echo esc_url( ingbiro_language_home_url() ); ?>"><?php echo esc_html( $is_english ? 'Home' : 'Naslovnica' ); ?></a></li>
					<li><a href="<?php echo esc_url( $is_english ? ingbiro_english_page_url( 'about-us' ) : ingbiro_page_url( 'o-nama' ) ); ?>"><?php echo esc_html( $is_english ? 'About us' : 'O nama' ); ?></a></li>
					<li><a href="<?php echo esc_url( $is_english ? ingbiro_english_page_url( 'consulting' ) : ingbiro_page_url( 'konzalting' ) ); ?>"><?php echo esc_html( $is_english ? 'Consulting' : 'Konzalting' ); ?></a></li>
					<li><a href="<?php echo esc_url( $is_english ? ingbiro_english_page_url( 'legal-portal' ) : ingbiro_page_url( 'pravni-portal' ) ); ?>"><?php echo esc_html( $is_english ? 'Legal portal' : 'Pravni portal' ); ?></a></li>
					<li><a href="<?php echo esc_url( $is_english ? ingbiro_english_page_url( 'conferences-and-training' ) : ingbiro_page_url( 'savjetovanja-i-edukacije' ) ); ?>"><?php echo esc_html( $is_english ? 'Conferences and training' : 'Savjetovanja i edukacije' ); ?></a></li>
				</ul>

				<ul class="footer-links">
					<li><a href="<?php echo esc_url( $is_english ? ingbiro_english_page_url( 'contact' ) : ingbiro_page_url( 'kontakt' ) ); ?>"><?php echo esc_html( $is_english ? 'Contact' : 'Kontakt' ); ?></a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'karijera' ) ); ?>"><?php echo esc_html( $is_english ? 'Careers' : 'Karijera' ); ?></a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'politika-privatnosti' ) ); ?>"><?php echo esc_html( $is_english ? 'Privacy policy' : 'Politika privatnosti' ); ?></a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'newsletter' ) ); ?>">Newsletter</a></li>
				</ul>

				<div class="site-footer__social">
					<strong><?php echo esc_html( $is_english ? 'Language:' : 'Jezik:' ); ?></strong>
					<div class="language-links">
						<a class="<?php echo esc_attr( 'hr' === $language ? 'is-active' : '' ); ?>" href="<?php echo esc_url( ingbiro_translation_url( 'hr' ) ); ?>"<?php if ( 'hr' === $language ) : ?> aria-current="page"<?php endif; ?>>Hrvatski</a>
						<span aria-hidden="true">|</span>
						<a class="<?php echo esc_attr( 'en' === $language ? 'is-active' : '' ); ?>" href="<?php echo esc_url( ingbiro_translation_url( 'en' ) ); ?>" lang="en"<?php if ( 'en' === $language ) : ?> aria-current="page"<?php endif; ?>>English</a>
					</div>
					<strong><?php echo esc_html( $is_english ? 'Follow us:' : 'Zapratite nas:' ); ?></strong>
					<a class="social-link" href="https://hr.linkedin.com/company/inzenjerskibiro" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
						<img src="<?php echo esc_url( ingbiro_asset( 'icons/footer-linkedin.svg' ) ); ?>" alt="">
					</a>
				</div>
			</div>

			<div class="site-footer__bottom">
				@<?php echo esc_html( gmdate( 'Y' ) ); ?> All rights reserved. Inženjerski biro d.o.o.
			</div>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
