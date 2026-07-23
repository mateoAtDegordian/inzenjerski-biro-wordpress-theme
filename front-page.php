<?php
/**
 * Homepage.
 *
 * @package Ingbiro
 */

get_header();

$events = get_posts(
	array(
		'post_type'      => 'ing_event',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
	)
);
$event = $events ? $events[0] : null;
?>
<main id="main" class="page-main">
	<section class="home-hero">
		<div class="container">
			<div class="home-hero__copy">
				<h1>Upoznajte najdugovječniju konzultantsku kuću u Republici Hrvatskoj</h1>
				<div class="home-hero__aside">
					<p>75 godina iskustva na izradi studija, elaborata, strategija i pravilnika za klijente iz poduzetničkog i javnog sektora.</p>
					<?php ingbiro_button( 'Pročitajte više', ingbiro_page_url( 'o-nama' ) ); ?>
				</div>
			</div>
			<div class="home-hero__media">
				<img src="<?php echo esc_url( ingbiro_asset( 'images/home-hero.jpg' ) ); ?>" alt="Poslovni razgovor sa savjetnikom">
				<button class="home-hero__play" type="button" data-video-placeholder aria-label="Pokreni video"><span>▶</span></button>
				<p class="video-message screen-reader-text" hidden>Video će biti povezan kada se unese konačni video URL.</p>
			</div>
		</div>
	</section>

	<section class="section section--paper home-about">
		<div class="container home-about__grid">
			<div>
				<?php ingbiro_section_label( 'O nama' ); ?>
				<div class="home-about__copy">
					<p>Inovativnim funkcionalnim rješenjima, alatima i profesionalnim i poslovnim uslugama pružamo podršku poslovnim i pravnim subjektima u Republici Hrvatskoj u rješavanju poslovnih izazova i ostvarivanju konkurentnosti.</p>
					<p>Naši stručnjaci specijalizirani su za područje prava, organizacije poslovanja i ekonomije, što nam omogućuje da projektima pristupimo na interdisciplinaran način te da budemo konkurentni.</p>
					<p>Neovisno o kojem se sektoru radi, ostvarili smo suradnju s ključnim akterima u njemu. Obratite nam se s povjerenjem.</p>
				</div>
			</div>
			<p class="home-about__statement">Jedan od vodećih poduzetnika u području LegalTecha.</p>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<?php ingbiro_section_label( 'Područja djelovanja' ); ?>
			<div class="service-grid">
				<a class="service-card" href="<?php echo esc_url( ingbiro_page_url( 'konzalting' ) ); ?>">
					<span class="service-card__icon" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/service-consulting.svg' ) ); ?>" alt=""></span>
					<h3>Konzalting</h3>
					<p>Ekonomski, pravni i organizacijski konzalting koji pokriva cjelokupan razvoj vašeg poslovanja:</p>
					<ul>
						<li>Izrada <strong>poslovnih planova i strategija</strong> te njihova implementacija</li>
						<li>Procesi <strong>restrukturiranja i reorganizacije</strong></li>
						<li><strong>Komunikacijski menadžment i korporativno pravo</strong></li>
						<li>Ekonomski, pravni i tehnički <strong>due diligence</strong></li>
						<li><strong>Razvojne i investicijske studije</strong></li>
					</ul>
				</a>

				<a class="service-card" href="<?php echo esc_url( ingbiro_page_url( 'pravni-portal' ) ); ?>">
					<span class="service-card__icon" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/service-portal.svg' ) ); ?>" alt=""></span>
					<h3>Pravni portal<br>LING</h3>
					<p>Pravni portal nove generacije koji poslovnim ljudima omogućuje jednostavno usklađivanje poslovanja s propisima RH i EU.</p>
					<ul>
						<li><strong>Budite u tijeku:</strong> napredni sustav obavijesti o promjenama u propisima.</li>
						<li><strong>Stručni sadržaj:</strong> sentencije, stručni članci i mišljenja.</li>
						<li><strong>Lansiran 2024. godine</strong> kao nastavak tradicije pravne publicistike.</li>
					</ul>
				</a>

				<a class="service-card" href="<?php echo esc_url( ingbiro_page_url( 'savjetovanja-i-edukacije' ) ); ?>">
					<span class="service-card__icon" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/service-education.svg' ) ); ?>" alt=""></span>
					<h3>Savjetovanja<br>i edukacije</h3>
					<p>Okupljamo vodeće stručnjake iz područja prava, javne uprave i gospodarstva.</p>
					<ul>
						<li><strong>Ovlašteni nositelj:</strong> programi izobrazbe u području javne nabave i edukacije iz područja osiguranja.</li>
						<li><strong>Tradicija i povjerenje:</strong> izvršni organizator tradicionalnog savjetovanja Hrvatskog društva ekonomista.</li>
					</ul>
				</a>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<?php ingbiro_section_label( 'Aktualna događanja' ); ?>
			<?php if ( $event ) : ?>
				<article class="event-feature">
					<div class="event-feature__image">
						<img src="<?php echo esc_url( get_the_post_thumbnail_url( $event, 'large' ) ?: ingbiro_asset( 'images/event.png' ) ); ?>" alt="">
					</div>
					<div>
						<h2><?php echo esc_html( get_the_title( $event ) ); ?></h2>
						<div class="tag-list">
							<span><?php echo esc_html( get_post_meta( $event->ID, 'ing_event_format', true ) ?: 'UŽIVO · WEBINAR' ); ?></span>
							<span><?php echo esc_html( get_post_meta( $event->ID, 'ing_event_date', true ) ?: '11.6.2026.' ); ?></span>
						</div>
						<p><?php echo esc_html( get_the_excerpt( $event ) ); ?></p>
						<?php ingbiro_button( 'Pročitajte više', get_permalink( $event ), 'pill-button--small' ); ?>
					</div>
				</article>
			<?php endif; ?>
		</div>
	</section>

	<?php ingbiro_building_banner(); ?>

	<section class="newsletter-cta">
		<div class="container">
			<div class="newsletter-cta__card">
				<div>
					<h2>Newsletter pretplata</h2>
					<p>Budite u tijeku s najnovijim zakonskim promjenama i poslovnim propisima.</p>
				</div>
				<form class="inline-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
					<input type="hidden" name="action" value="ingbiro_submit">
					<input type="hidden" name="submission_type" value="newsletter">
					<?php wp_nonce_field( 'ingbiro_submit', 'ingbiro_nonce' ); ?>
					<label class="screen-reader-text" for="home-newsletter-email">Vaš e-mail</label>
					<input id="home-newsletter-email" name="email" type="email" placeholder="Vaš e-mail" required>
					<button class="pill-button" type="submit"><span class="pill-button__label">Pretplatite se</span><span class="pill-button__icon" aria-hidden="true"><img src="<?php echo esc_url( ingbiro_asset( 'icons/arrow-right.svg' ) ); ?>" alt=""></span></button>
				</form>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
