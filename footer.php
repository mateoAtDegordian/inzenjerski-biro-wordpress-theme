<?php
/**
 * Site footer.
 *
 * @package Ingbiro
 */
?>
	<footer class="site-footer">
		<div class="container">
			<div class="site-footer__grid">
				<div class="site-footer__brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php echo esc_url( ingbiro_asset( 'images/logo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					</a>
					<p class="site-footer__tagline">Savjetnici od 1952.</p>
					<ul class="contact-list">
						<li><span class="contact-list__icon" aria-hidden="true">●</span><span>Heinzelova 4A, 10000 Zagreb, Hrvatska</span></li>
						<li><span class="contact-list__icon" aria-hidden="true">✉</span><span><a href="mailto:ingbiro@ingbiro.hr">ingbiro@ingbiro.hr</a> &nbsp; | &nbsp; <a href="mailto:prodaja@ingbiro.hr">prodaja@ingbiro.hr</a></span></li>
						<li><span class="contact-list__icon" aria-hidden="true">●</span><span><strong>Tel.</strong> (+385) 1 46 00 888; &nbsp; <strong>Fax.</strong> (+385) 1 46 50 366</span></li>
					</ul>
				</div>

				<ul class="footer-links">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Naslovnica</a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'o-nama' ) ); ?>">O nama</a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'konzalting' ) ); ?>">Konzalting</a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'pravni-portal' ) ); ?>">Pravni portal</a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'savjetovanja-i-edukacije' ) ); ?>">Savjetovanja i edukacije</a></li>
				</ul>

				<ul class="footer-links">
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'kontakt' ) ); ?>">Kontakt</a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'karijera' ) ); ?>">Karijera</a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'politika-privatnosti' ) ); ?>">Politika privatnosti</a></li>
					<li><a href="<?php echo esc_url( ingbiro_page_url( 'newsletter' ) ); ?>">Newsletter</a></li>
				</ul>

				<div class="site-footer__social">
					<strong>Jezik:</strong>
					<div class="language-links">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Hrvatski</a>
						<span aria-hidden="true">|</span>
						<a href="#" lang="en">English</a>
					</div>
					<strong>Zapratite nas:</strong>
					<a class="social-link" href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">in</a>
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

