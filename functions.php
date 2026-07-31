<?php
/**
 * Inženjerski biro theme setup and content model.
 *
 * @package Ingbiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'INGBIRO_VERSION', '1.7.0' );

function ingbiro_setup() {
	load_theme_textdomain( 'ingbiro', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
	add_image_size( 'ingbiro-event-hero', 1600, 800, true );
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
	wp_localize_script(
		'ingbiro-theme',
		'ingbiroForms',
		array(
			'language'     => function_exists( 'ingbiro_current_language' ) ? ingbiro_current_language() : 'hr',
			'translations' => function_exists( 'ingbiro_form_english_strings' ) ? ingbiro_form_english_strings() : array(),
			'formKeys' => array_filter(
				array(
					(string) ingbiro_get_form_id( 'contact' )    => 'contact',
					(string) ingbiro_get_form_id( 'newsletter' ) => 'newsletter',
					(string) ingbiro_get_form_id( 'quick' )      => 'quick',
					(string) ingbiro_get_form_id( 'career' )     => 'career',
					(string) ingbiro_get_form_id( 'event' )      => 'event',
				)
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'ingbiro_enqueue_assets' );

function ingbiro_asset( $path ) {
	return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
}

require_once get_template_directory() . '/inc/forms.php';
require_once get_template_directory() . '/inc/language.php';
require_once get_template_directory() . '/inc/legal.php';
require_once get_template_directory() . '/inc/patterns.php';

function ingbiro_favicons() {
	printf(
		'<link rel="icon" href="%1$s" sizes="32x32" type="image/png"><link rel="icon" href="%2$s" sizes="512x512" type="image/png"><link rel="apple-touch-icon" href="%3$s">',
		esc_url( ingbiro_asset( 'images/favicon-32.png' ) ),
		esc_url( ingbiro_asset( 'images/favicon-512.png' ) ),
		esc_url( ingbiro_asset( 'images/favicon-180.png' ) )
	);
}
add_action( 'wp_head', 'ingbiro_favicons', 2 );

function ingbiro_page_url( $slug ) {
	if ( function_exists( 'ingbiro_is_english' ) && ingbiro_is_english() ) {
		$english_slugs = array(
			'naslovnica'               => '',
			'o-nama'                   => 'about-us',
			'konzalting'               => 'consulting',
			'pravni-portal'            => 'legal-portal',
			'savjetovanja-i-edukacije' => 'conferences-and-training',
			'kontakt'                  => 'contact',
		);

		if ( array_key_exists( $slug, $english_slugs ) ) {
			return ingbiro_english_page_url( $english_slugs[ $slug ] );
		}
	}

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
		'<div class="section-label%s"><img class="section-label__gear" src="%s" alt="" aria-hidden="true"><span>%s</span></div>',
		$light ? ' section-label--light' : '',
		esc_url( ingbiro_asset( 'icons/section-gear.svg' ) ),
		esc_html( $label )
	);
}

function ingbiro_button( $label, $url, $class = '', $attributes = array() ) {
	$attribute_html = '';
	foreach ( $attributes as $name => $value ) {
		$attribute_html .= sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
	}

	printf(
		'<a class="pill-button %s" href="%s"%s><span class="pill-button__label">%s</span><span class="pill-button__icon" aria-hidden="true"><img src="%s" alt=""></span></a>',
		esc_attr( $class ),
		esc_url( $url ),
		$attribute_html, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_html( $label ),
		esc_url( ingbiro_asset( 'icons/arrow-right.svg' ) )
	);
}

function ingbiro_building_banner() {
	printf(
		'<div class="building-banner" aria-hidden="true"><img class="building-banner__image" src="%s" alt="" width="1470" height="630"></div>',
		esc_url( add_query_arg( 'ver', INGBIRO_VERSION, ingbiro_asset( 'images/building-animation.svg' ) ) )
	);
}

/**
 * A single source of truth for event imagery keeps cards, the event page and
 * generated PDFs visually identical.
 */
function ingbiro_event_image_url( $event_id, $size = 'ingbiro-event-hero' ) {
	$image = get_the_post_thumbnail_url( $event_id, $size );
	return $image ?: ingbiro_asset( 'images/education-event-figma.png' );
}

function ingbiro_event_image_path( $event_id ) {
	$thumbnail_id = get_post_thumbnail_id( $event_id );
	$path         = $thumbnail_id ? get_attached_file( $thumbnail_id ) : '';

	if ( $path && file_exists( $path ) ) {
		return $path;
	}

	return get_template_directory() . '/assets/images/education-event-figma.png';
}

/**
 * Figma event page content, expressed only with core Gutenberg blocks.
 *
 * Each top-level group is deliberately independent so editors can move,
 * duplicate or remove complete sections in the block editor.
 */
function ingbiro_event_blueprint_content() {
	$image_one   = esc_url( ingbiro_asset( 'images/event-detail-1.jpg' ) );
	$image_two   = esc_url( ingbiro_asset( 'images/event-detail-2.jpg' ) );
	$image_three = esc_url( ingbiro_asset( 'images/event-detail-3.jpg' ) );

	return '
<!-- wp:group {"className":"event-block event-block--why","layout":{"type":"constrained"}} -->
<div class="wp-block-group event-block event-block--why">
<!-- wp:heading --><h2 class="wp-block-heading">Zašto sudjelovati na webinaru?</h2><!-- /wp:heading -->
<!-- wp:columns {"className":"event-why-layout"} --><div class="wp-block-columns event-why-layout">
<!-- wp:column {"width":"58%"} --><div class="wp-block-column" style="flex-basis:58%">
<!-- wp:paragraph --><p>Izmjene i dopune ZJN 2016 u području jednostavne nabave donose niz novina koje zahtijevaju ne samo razumijevanje zakonodavnog okvira već i sigurnost u njihovoj praktičnoj primjeni kroz sve faze postupka. Ovo stručno usavršavanje pruža cjelovit pregled ključnih promjena, s posebnim naglaskom na pitanja sukoba interesa, pravne zaštite te izmjena u planiranju nabave i vođenju registra ugovora.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Kroz detaljno vođene prikaze rada u EOJN RH, sudionici će steći konkretna znanja o provedbi jednostavne nabave – od pripreme postupka i slanja poziva gospodarskim subjektima do pregleda i ocjene ponuda te donošenja odluke o odabiru i objave ugovora. Praktični primjeri i „ekranski prikazi“ rada u sustavu omogućit će lakše snalaženje u svakodnevnim situacijama i sigurniju primjenu propisa.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Poseban naglasak stavlja se na izradu i usklađivanje općeg akta o provedbi jednostavne nabave s novim pravilima, uključujući definiranje pragova, sadržaja akta te pravilno uređenje pitanja sukoba interesa i pravne zaštite. Kombinacija teorijskih pojašnjenja i konkretnih primjera iz prakse pruža jasne smjernice za pravilno postupanje u svim fazama jednostavne nabave i učinkovitu primjenu novih zakonskih odredbi.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Program usavršavanja provodi se kao jednodnevni program (8 nastavnih sati). Svakom polazniku izdaje se potvrda o pohađanju programa usavršavanja sukladno Pravilniku o izobrazbi u području javne nabave (NN 154/2023 i 94/2025).</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Maksimalan broj polaznika po pojedinačnom programu je 50, a termin se popunjava redoslijedom primljenih uplata. Svi polaznici moraju imati uključene kamere u realnom vremenu, a mikrofone prema potrebi.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>INŽENJERSKI BIRO d.o.o., Ulica Vjekoslava Heinzela 4A, Zagreb, ovlašteni je nositelj Programa izobrazbe u području javne nabave (Evidencijski broj 19 u Registru nositelja programa). Program je odobren od Ministarstva gospodarstva (Evidencijski broj programa 2026-0192).</p><!-- /wp:paragraph -->
</div><!-- /wp:column -->
<!-- wp:column {"width":"42%","className":"event-image-stack"} --><div class="wp-block-column event-image-stack" style="flex-basis:42%">
<!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="' . $image_one . '" alt="Sudionici stručnog programa"/></figure><!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="' . $image_two . '" alt="Predavanje i rasprava"/></figure><!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="' . $image_three . '" alt="Rad na stručnom programu"/></figure><!-- /wp:image -->
</div><!-- /wp:column -->
</div><!-- /wp:columns -->
</div><!-- /wp:group -->

<!-- wp:group {"className":"event-block event-block--program","layout":{"type":"constrained"}} -->
<div class="wp-block-group event-block event-block--program">
<!-- wp:heading --><h2 class="wp-block-heading">Program webinara</h2><!-- /wp:heading -->
<!-- wp:group {"className":"event-program","layout":{"type":"constrained"}} --><div class="wp-block-group event-program">
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">9.00 – 10.30</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>JEDNOSTAVNA NABAVA – IZMJENE I DOPUNE ZJN 2016</strong><br>(2 nastavna sata)</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>Novine koje donose izmjene i dopune ZJN 2016</li><li>Sukob interesa i pravna zaštita u jednostavnoj nabavi – novine ZJN 2016</li><li>Izmjene u odnosu na plan nabave i registar ugovora</li></ul><!-- /wp:list -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">10.30 – 10.45</h3><!-- /wp:heading --><!-- wp:paragraph --><p>STANKA</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">10.45 – 12.15</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>PROVEDBA JEDNOSTAVNE NABAVE U MODULU EOJN RH</strong><br>(2 nastavna sata)</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>Provedba jednostavne nabave na platformi EOJN RH kroz sve faze od pripreme do objave ugovora</li><li>e-Dostava s pozivom odabranim gospodarskim subjektima – prikaz u EOJN RH</li><li>e-Dostava s javnom objavom poziva – prikaz u EOJN RH</li></ul><!-- /wp:list -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">12.15 – 12.45</h3><!-- /wp:heading --><!-- wp:paragraph --><p>STANKA</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">12.45 – 14.15</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>OPĆI AKT O PROVEDBI JEDNOSTAVNE NABAVE</strong><br>(2 nastavna sata)</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>Ogledni primjer kako izmijeniti opći akt sukladno izmjenama i dopunama ZJN 2016</li><li>Novi pragovi i preporuke što opći akt treba sadržavati</li><li>Sukob interesa i pravna zaštita – kako propisati u općem aktu</li></ul><!-- /wp:list -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">14.15 – 14.30</h3><!-- /wp:heading --><!-- wp:paragraph --><p>STANKA</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">14.30 – 16.00</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>OBJAVA JEDNOSTAVNE NABAVE U MODULU EOJN RH</strong><br>(2 nastavna sata)</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>Otvaranje ponuda – ekranski prikaz u EOJN RH</li><li>Pregled i ocjena ponuda te donošenje Odluke o odabiru – ekranski prikaz u EOJN RH</li><li>Priprema i objava ugovora – ekranski prikaz u EOJN RH</li></ul><!-- /wp:list -->
<!-- wp:paragraph --><p><strong>PITANJA I ODGOVORI</strong></p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:html -->
<div class="event-program-gears" aria-hidden="true"><img src="' . esc_url( ingbiro_asset( 'icons/figma-gear-soft-event.svg' ) ) . '" alt=""><img src="' . esc_url( ingbiro_asset( 'icons/figma-gear-blue-event.svg' ) ) . '" alt=""></div>
<!-- /wp:html -->
</div><!-- /wp:group -->

<!-- wp:group {"className":"event-block event-block--fee","layout":{"type":"constrained"}} -->
<div class="wp-block-group event-block event-block--fee">
<!-- wp:heading --><h2 class="wp-block-heading">Kotizacija</h2><!-- /wp:heading -->
<!-- wp:columns {"className":"event-fee-columns"} --><div class="wp-block-columns event-fee-columns">
<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Naknada</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Naknada za sudjelovanje po sudioniku iznosi <strong>180,00 eura</strong>, a uplaćuje se unaprijed na žiro-račun organizatora:</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>INŽENJERSKI BIRO d.o.o., Heinzelova 4a, Zagreb<br><strong>IBAN: HR2323400091100205049<br>SWIFT: PBZGHR2X<br>OIB: 84170114747<br>poziv na broj: 02-312608<br>naznaka: „za program usavršavanja”</strong></p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>Na temelju čl. 39. st. 1. toč. i) Zakona o PDV-u, kotizacija je oslobođena od PDV-a.</strong></p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>Naknada uključuje:</strong></p><!-- /wp:paragraph --><!-- wp:list --><ul class="wp-block-list"><li>sudjelovanje u Programu usavršavanja</li><li>prezentaciju predavača u elektroničkom formatu</li></ul><!-- /wp:list -->
</div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Prijave</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Prijave za Program usavršavanja molimo poslati:</p><!-- /wp:paragraph --><!-- wp:list --><ul class="wp-block-list"><li>online prijavom na ovoj stranici</li><li>e-mailom na <a href="mailto:prodaja@ingbiro.hr">prodaja@ingbiro.hr</a></li><li>poštom na INŽENJERSKI BIRO d.o.o., Heinzelova 4a, 10 000 Zagreb</li></ul><!-- /wp:list -->
<!-- wp:paragraph --><p><strong>Posebne pogodnosti:</strong></p><!-- /wp:paragraph --><!-- wp:list --><ul class="wp-block-list"><li>svi pretplatnici na pravni portal LING ostvaruju popust na radionice i webinare</li><li>paket LING – 10 % popusta</li><li>paket LING PLUS – 25 % popusta</li></ul><!-- /wp:list -->
</div><!-- /wp:column -->
</div><!-- /wp:columns -->
</div><!-- /wp:group -->

<!-- wp:group {"className":"event-block event-block--instructions","layout":{"type":"constrained"}} -->
<div class="wp-block-group event-block event-block--instructions">
<!-- wp:heading --><h2 class="wp-block-heading">Upute</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Prijavnicu za webinar treba popuniti točnim i obveznim podacima putem internetske stranice ingbiro.hr. Traženi podaci prikupljaju se radi evidencije prisutnosti polaznika i izdavanja potvrde o pohađanju programa.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Kotizaciju treba uplatiti u cijelosti. Potvrdu o uplati potrebno je dostaviti najkasnije jedan dan prije održavanja. Poveznica za webinar dostavlja se na dan održavanja na adresu elektroničke pošte navedenu u prijavnici.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>Registraciju je potrebno napraviti najkasnije 15 minuta prije početka:</strong></p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>svakom sudioniku e-mailom dostavljamo podsjetnik, poveznicu i prezentaciju</li><li>putem poveznice sudionik ulazi u čekaonicu, a administrator dopušta ulazak</li><li>administratori prije početka provjeravaju prijavu i uvjete održavanja</li><li>webinar počinje u najavljeno vrijeme prema rasporedu predavanja</li></ul><!-- /wp:list -->
<!-- wp:paragraph --><p><strong>Osnovne pretpostavke za sudjelovanje:</strong></p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>na računalo nije potrebno instalirati dodatni program; dovoljno je otvoriti primljenu poveznicu</li><li>za praćenje na pametnom telefonu potrebno je instalirati Microsoft Teams</li><li>potrebna je stabilna širokopojasna internetska veza i zvučnik</li><li>mikrofon nije obvezan, ali web kamera mora biti uključena u realnom vremenu</li></ul><!-- /wp:list -->
<!-- wp:paragraph --><p>Za tehničku pomoć i dodatne informacije nazovite 01 / 4600 888 ili pišite na <a href="mailto:abatinic@ingbiro.hr">abatinic@ingbiro.hr</a>. Pitanja za predavača možete poslati i unaprijed na istu adresu.</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->';
}

/**
 * Starter content for a new event.
 *
 * The structure mirrors the complete event page while the bracketed copy makes
 * it obvious which content should be replaced before publishing.
 */
function ingbiro_event_editor_template_content() {
	$image_one   = esc_url( ingbiro_asset( 'images/event-detail-1.jpg' ) );
	$image_two   = esc_url( ingbiro_asset( 'images/event-detail-2.jpg' ) );
	$image_three = esc_url( ingbiro_asset( 'images/event-detail-3.jpg' ) );

	return '
<!-- wp:group {"className":"event-block event-block--why","layout":{"type":"constrained"}} -->
<div class="wp-block-group event-block event-block--why">
<!-- wp:heading --><h2 class="wp-block-heading">[Zašto sudjelovati na edukaciji?]</h2><!-- /wp:heading -->
<!-- wp:columns {"className":"event-why-layout"} --><div class="wp-block-columns event-why-layout">
<!-- wp:column {"width":"58%"} --><div class="wp-block-column" style="flex-basis:58%">
<!-- wp:paragraph --><p>[Ukratko predstavite temu edukacije, problem koji rješava i kome je program namijenjen.]</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>[Opišite praktična znanja, alate i primjere koje će polaznici dobiti tijekom edukacije.]</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>[Dodajte važne informacije o programu, certifikatu, broju polaznika ili uvjetima sudjelovanja.]</p><!-- /wp:paragraph -->
</div><!-- /wp:column -->
<!-- wp:column {"width":"42%","className":"event-image-stack"} --><div class="wp-block-column event-image-stack" style="flex-basis:42%">
<!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="' . $image_one . '" alt="[Zamijenite prvom fotografijom edukacije]"/></figure><!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="' . $image_two . '" alt="[Zamijenite drugom fotografijom edukacije]"/></figure><!-- /wp:image -->
<!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="' . $image_three . '" alt="[Zamijenite trećom fotografijom edukacije]"/></figure><!-- /wp:image -->
</div><!-- /wp:column -->
</div><!-- /wp:columns -->
</div><!-- /wp:group -->

<!-- wp:group {"className":"event-block event-block--program","layout":{"type":"constrained"}} -->
<div class="wp-block-group event-block event-block--program">
<!-- wp:heading --><h2 class="wp-block-heading">[Program edukacije]</h2><!-- /wp:heading -->
<!-- wp:group {"className":"event-program","layout":{"type":"constrained"}} --><div class="wp-block-group event-program">
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">[09.00 – 10.30]</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>[NAZIV PRVOG PROGRAMSKOG BLOKA]</strong><br>[Trajanje ili broj nastavnih sati]</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>[Prva tema ili ishod]</li><li>[Druga tema ili ishod]</li><li>[Treća tema ili ishod]</li></ul><!-- /wp:list -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">[10.30 – 10.45]</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>[STANKA]</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">[10.45 – 12.15]</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>[NAZIV DRUGOG PROGRAMSKOG BLOKA]</strong><br>[Trajanje ili broj nastavnih sati]</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>[Prva tema ili ishod]</li><li>[Druga tema ili ishod]</li><li>[Treća tema ili ishod]</li></ul><!-- /wp:list -->
<!-- wp:paragraph --><p><strong>[PITANJA I ODGOVORI]</strong></p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:html -->
<div class="event-program-gears" aria-hidden="true"><img src="' . esc_url( ingbiro_asset( 'icons/figma-gear-soft-event.svg' ) ) . '" alt=""><img src="' . esc_url( ingbiro_asset( 'icons/figma-gear-blue-event.svg' ) ) . '" alt=""></div>
<!-- /wp:html -->
</div><!-- /wp:group -->

<!-- wp:group {"className":"event-block event-block--fee","layout":{"type":"constrained"}} -->
<div class="wp-block-group event-block event-block--fee">
<!-- wp:heading --><h2 class="wp-block-heading">[Kotizacija]</h2><!-- /wp:heading -->
<!-- wp:columns {"className":"event-fee-columns"} --><div class="wp-block-columns event-fee-columns">
<!-- wp:column --><div class="wp-block-column">
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">[Naknada]</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>[Upišite cijenu, porezni status i rok plaćanja.]</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>[Upišite IBAN, model, poziv na broj i opis plaćanja.]</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>[Što je uključeno u kotizaciju:]</strong></p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>[Stavka uključena u cijenu]</li><li>[Materijali, potvrda ili dodatna pogodnost]</li></ul><!-- /wp:list -->
</div><!-- /wp:column -->
<!-- wp:column --><div class="wp-block-column">
<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">[Prijave]</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>[Objasnite način i rok prijave.]</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>[Online prijava na ovoj stranici]</li><li>[Kontakt e-mail ili telefon]</li></ul><!-- /wp:list -->
<!-- wp:paragraph --><p><strong>[Posebne pogodnosti:]</strong></p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>[Popust ili pogodnost za pretplatnike]</li><li>[Druga pogodnost, ako postoji]</li></ul><!-- /wp:list -->
</div><!-- /wp:column -->
</div><!-- /wp:columns -->
</div><!-- /wp:group -->

<!-- wp:group {"className":"event-block event-block--instructions","layout":{"type":"constrained"}} -->
<div class="wp-block-group event-block event-block--instructions">
<!-- wp:heading --><h2 class="wp-block-heading">[Upute za sudionike]</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>[Upišite što polaznik mora napraviti nakon prijave i prije početka edukacije.]</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>[Tehničke i organizacijske upute:]</strong></p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list"><li>[Način pristupa edukaciji ili lokacija]</li><li>[Potrebna oprema ili dokumenti]</li><li>[Vrijeme registracije ili dolaska]</li></ul><!-- /wp:list -->
<!-- wp:paragraph --><p>[Dodajte kontakt za pomoć i dodatne informacije.]</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->';
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
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'ing_service',
		array(
			'labels' => array(
				'name'          => __( 'Konzalting usluge', 'ingbiro' ),
				'singular_name' => __( 'Konzalting usluga', 'ingbiro' ),
				'add_new_item'  => __( 'Dodaj konzalting uslugu', 'ingbiro' ),
				'edit_item'     => __( 'Uredi konzalting uslugu', 'ingbiro' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-portfolio',
			'supports'            => array( 'title', 'editor', 'page-attributes' ),
			'exclude_from_search' => true,
		)
	);

	register_post_type(
		'ing_archive',
		array(
			'labels' => array(
				'name'          => __( 'Arhiva savjetovanja', 'ingbiro' ),
				'singular_name' => __( 'Arhivski zapis', 'ingbiro' ),
				'add_new_item'  => __( 'Dodaj arhivski link', 'ingbiro' ),
				'edit_item'     => __( 'Uredi arhivski link', 'ingbiro' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-archive',
			'supports'            => array( 'title', 'editor' ),
			'exclude_from_search' => true,
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

/**
 * Pre-populate only brand-new events. Existing posts and saved drafts retain
 * exactly the content entered by an editor.
 *
 * @param string  $content Default editor content.
 * @param WP_Post $post    Post being created.
 * @return string
 */
function ingbiro_default_event_content( $content, $post ) {
	if (
		$post instanceof WP_Post
		&& 'ing_event' === $post->post_type
		&& '' === trim( (string) $content )
	) {
		return ingbiro_event_editor_template_content();
	}

	return $content;
}
add_filter( 'default_content', 'ingbiro_default_event_content', 10, 2 );

/**
 * Persist the same template when WordPress creates an event auto-draft.
 *
 * This also covers reopening "Add event" after the editor has already created
 * an otherwise empty auto-draft for the current user.
 *
 * @param array $data    Sanitized post data.
 * @param array $postarr Raw post data.
 * @return array
 */
function ingbiro_seed_event_auto_draft( $data, $postarr ) {
	if (
		'ing_event' === ( $data['post_type'] ?? '' )
		&& 'auto-draft' === ( $data['post_status'] ?? '' )
		&& '' === trim( (string) ( $data['post_content'] ?? '' ) )
	) {
		$data['post_content'] = ingbiro_event_editor_template_content();
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'ingbiro_seed_event_auto_draft', 10, 2 );

/**
 * Backfill empty event auto-drafts created before the editor template existed.
 */
function ingbiro_upgrade_empty_event_auto_drafts() {
	if ( version_compare( (string) get_option( 'ingbiro_event_auto_draft_version', '0' ), '1.0.0', '>=' ) ) {
		return;
	}

	$auto_draft_ids = get_posts(
		array(
			'post_type'      => 'ing_event',
			'post_status'    => 'auto-draft',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	foreach ( $auto_draft_ids as $post_id ) {
		if ( '' !== trim( (string) get_post_field( 'post_content', $post_id ) ) ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_content' => ingbiro_event_editor_template_content(),
			)
		);
	}

	update_option( 'ingbiro_event_auto_draft_version', '1.0.0' );
}
add_action( 'init', 'ingbiro_upgrade_empty_event_auto_drafts', 37 );

function ingbiro_register_meta() {
	$event_meta = array(
		'ing_event_date'                 => 'string',
		'ing_event_start_date'           => 'string',
		'ing_event_format'               => 'string',
		'ing_event_hours'                => 'string',
		'ing_event_start'                => 'string',
		'ing_event_location'             => 'string',
		'ing_event_speaker'              => 'string',
		'ing_event_speaker_role'         => 'string',
		'ing_event_fee'                  => 'string',
		'ing_event_form_id'              => 'integer',
		'ing_event_registration_enabled' => 'boolean',
	);

	foreach ( $event_meta as $key => $type ) {
		register_post_meta(
			'ing_event',
			$key,
			array(
				'type'              => $type,
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'integer' === $type ? 'absint' : ( 'boolean' === $type ? 'rest_sanitize_boolean' : 'sanitize_text_field' ),
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

	register_post_meta(
		'ing_job',
		'ing_job_form_id',
		array(
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);

	foreach ( array( 'ing_archive_date' => 'sanitize_text_field', 'ing_archive_url' => 'esc_url_raw' ) as $key => $sanitize_callback ) {
		register_post_meta(
			'ing_archive',
			$key,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $sanitize_callback,
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', 'ingbiro_register_meta' );

function ingbiro_add_meta_boxes() {
	add_meta_box( 'ing_event_details', __( 'Detalji događanja', 'ingbiro' ), 'ingbiro_event_meta_box', 'ing_event', 'normal', 'high' );
	add_meta_box( 'ing_job_details', __( 'Detalji pozicije', 'ingbiro' ), 'ingbiro_job_meta_box', 'ing_job', 'side' );
	add_meta_box( 'ing_archive_details', __( 'Datum i poveznica', 'ingbiro' ), 'ingbiro_archive_meta_box', 'ing_archive', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'ingbiro_add_meta_boxes' );

function ingbiro_event_meta_box( $post ) {
	wp_nonce_field( 'ingbiro_save_event', 'ingbiro_event_nonce' );
	$fields = array(
		'ing_event_date'         => array( __( 'Datum za prikaz (npr. 11. lipnja 2026.)', 'ingbiro' ), 'text' ),
		'ing_event_start_date'   => array( __( 'Datum za sortiranje', 'ingbiro' ), 'date' ),
		'ing_event_format'       => array( __( 'Format (npr. UŽIVO · WEBINAR)', 'ingbiro' ), 'text' ),
		'ing_event_hours'        => array( __( 'Trajanje / broj sati', 'ingbiro' ), 'text' ),
		'ing_event_start'        => array( __( 'Početak', 'ingbiro' ), 'text' ),
		'ing_event_location'     => array( __( 'Lokacija ili način praćenja', 'ingbiro' ), 'text' ),
		'ing_event_speaker'      => array( __( 'Predavač', 'ingbiro' ), 'text' ),
		'ing_event_speaker_role' => array( __( 'Opis predavača', 'ingbiro' ), 'text' ),
		'ing_event_fee'          => array( __( 'Kotizacija', 'ingbiro' ), 'text' ),
		'ing_event_form_id'      => array( __( 'Forminator form ID (prazno = zadana forma)', 'ingbiro' ), 'number' ),
	);

	foreach ( $fields as $key => $field ) {
		printf(
			'<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" type="%4$s" id="%1$s" name="%1$s" value="%3$s"></p>',
			esc_attr( $key ),
			esc_html( $field[0] ),
			esc_attr( get_post_meta( $post->ID, $key, true ) ),
			esc_attr( $field[1] )
		);
	}

	printf(
		'<p><label><input type="checkbox" name="ing_event_registration_enabled" value="1" %s> <strong>%s</strong></label></p>',
		checked( (bool) get_post_meta( $post->ID, 'ing_event_registration_enabled', true ), true, false ),
		esc_html__( 'Omogući prijavu i prikaži formu na stranici događanja', 'ingbiro' )
	);

	echo '<p class="description">' . esc_html__( 'Glavni sadržaj programa, upute, raspored, slike, tablice i dodatne sekcije uređuju se u Gutenberg editoru iznad. Istaknuta slika koristi se kao hero fotografija.', 'ingbiro' ) . '</p>';
}

function ingbiro_job_meta_box( $post ) {
	wp_nonce_field( 'ingbiro_save_job', 'ingbiro_job_nonce' );
	printf(
		'<p><label for="ing_job_location"><strong>%s</strong></label><input class="widefat" id="ing_job_location" name="ing_job_location" value="%s"></p>',
		esc_html__( 'Lokacija / način rada', 'ingbiro' ),
		esc_attr( get_post_meta( $post->ID, 'ing_job_location', true ) )
	);
	printf(
		'<p><label for="ing_job_form_id"><strong>%s</strong></label><input class="widefat" type="number" id="ing_job_form_id" name="ing_job_form_id" value="%s"></p><p class="description">%s</p>',
		esc_html__( 'Forminator form ID', 'ingbiro' ),
		esc_attr( get_post_meta( $post->ID, 'ing_job_form_id', true ) ),
		esc_html__( 'Ostavite prazno za standardnu stranicu prijave.', 'ingbiro' )
	);
}

function ingbiro_archive_meta_box( $post ) {
	wp_nonce_field( 'ingbiro_save_archive', 'ingbiro_archive_nonce' );
	printf(
		'<p><label for="ing_archive_date"><strong>%s</strong></label><br><input class="widefat" type="date" id="ing_archive_date" name="ing_archive_date" value="%s"></p>',
		esc_html__( 'Datum savjetovanja', 'ingbiro' ),
		esc_attr( get_post_meta( $post->ID, 'ing_archive_date', true ) )
	);
	printf(
		'<p><label for="ing_archive_url"><strong>%s</strong></label><br><input class="widefat" type="url" id="ing_archive_url" name="ing_archive_url" value="%s" placeholder="https://..."></p><p class="description">%s</p>',
		esc_html__( 'Link na snimku ili preneseni stari HTML', 'ingbiro' ),
		esc_attr( get_post_meta( $post->ID, 'ing_archive_url', true ) ),
		esc_html__( 'Naslov zapisa automatski postaje tekst linka u arhivi. Dodatnu bilješku možete unijeti u editor.', 'ingbiro' )
	);
}

function ingbiro_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['ingbiro_event_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ingbiro_event_nonce'] ) ), 'ingbiro_save_event' ) ) {
		$keys = array(
			'ing_event_date',
			'ing_event_start_date',
			'ing_event_format',
			'ing_event_hours',
			'ing_event_start',
			'ing_event_location',
			'ing_event_speaker',
			'ing_event_speaker_role',
			'ing_event_fee',
		);
		foreach ( $keys as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
			}
		}
		update_post_meta( $post_id, 'ing_event_form_id', isset( $_POST['ing_event_form_id'] ) ? absint( $_POST['ing_event_form_id'] ) : 0 );
		update_post_meta( $post_id, 'ing_event_registration_enabled', isset( $_POST['ing_event_registration_enabled'] ) ? 1 : 0 );
	}

	if ( isset( $_POST['ingbiro_job_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ingbiro_job_nonce'] ) ), 'ingbiro_save_job' ) && isset( $_POST['ing_job_location'] ) ) {
		update_post_meta( $post_id, 'ing_job_location', sanitize_text_field( wp_unslash( $_POST['ing_job_location'] ) ) );
		update_post_meta( $post_id, 'ing_job_form_id', isset( $_POST['ing_job_form_id'] ) ? absint( $_POST['ing_job_form_id'] ) : 0 );
	}

	if ( isset( $_POST['ingbiro_archive_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ingbiro_archive_nonce'] ) ), 'ingbiro_save_archive' ) ) {
		if ( isset( $_POST['ing_archive_date'] ) ) {
			update_post_meta( $post_id, 'ing_archive_date', sanitize_text_field( wp_unslash( $_POST['ing_archive_date'] ) ) );
		}
		if ( isset( $_POST['ing_archive_url'] ) ) {
			update_post_meta( $post_id, 'ing_archive_url', esc_url_raw( wp_unslash( $_POST['ing_archive_url'] ) ) );
		}
	}
}
add_action( 'save_post', 'ingbiro_save_meta' );

function ingbiro_get_consulting_services() {
	return get_posts(
		array(
			'post_type'      => 'ing_service',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'order'          => 'ASC',
		)
	);
}

function ingbiro_get_event_form_id( $event_id ) {
	$form_id = absint( get_post_meta( $event_id, 'ing_event_form_id', true ) );
	return $form_id ?: absint( get_option( 'ingbiro_default_event_form_id' ) );
}

function ingbiro_render_event_form( $event_id ) {
	$form_id = ingbiro_get_event_form_id( $event_id );
	if ( $form_id && shortcode_exists( 'forminator_form' ) ) {
		echo '<div class="event-registration__form forminator-theme-wrap ing-forminator" data-form-key="event">';
		echo do_shortcode( sprintf( '[forminator_form id="%d"]', $form_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
		return;
	}

	echo '<div class="event-registration__fallback">';
	echo '<p>' . esc_html__( 'Forma se može povezati unosom Forminator form ID-a u detaljima događanja.', 'ingbiro' ) . '</p>';
	ingbiro_button(
		__( 'Otvorite prijavnicu', 'ingbiro' ),
		add_query_arg( 'event_id', $event_id, ingbiro_page_url( 'prijava-za-edukaciju' ) )
	);
	echo '</div>';
}

function ingbiro_create_default_forminator_form() {
	if ( get_option( 'ingbiro_default_event_form_id' ) || ! class_exists( 'Forminator_Custom_Form_Admin' ) || ! class_exists( 'Forminator_Template_Contact_Form' ) ) {
		return;
	}

	$base              = new Forminator_Template_Contact_Form();
	$template          = new stdClass();
	$template->settings = $base->settings;
	$template->settings['thankyou-message'] = __( 'Hvala! Vaša prijava je zaprimljena.', 'ingbiro' );
	$template->settings['submitData']['custom-submit-text'] = __( 'Prijavite se', 'ingbiro' );
	$template->fields = ingbiro_event_form_fields();

	$form_id = Forminator_Custom_Form_Admin::create( __( 'Prijava na događanje', 'ingbiro' ), Forminator_Form_Model::STATUS_PUBLISH, $template );
	if ( ! is_wp_error( $form_id ) ) {
		update_option( 'ingbiro_default_event_form_id', absint( $form_id ) );
	}
}
add_action( 'admin_init', 'ingbiro_create_default_forminator_form' );

function ingbiro_forminator_notice() {
	if ( ! current_user_can( 'activate_plugins' ) || shortcode_exists( 'forminator_form' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Inženjerski biro:', 'ingbiro' ) . '</strong> ' . esc_html__( 'Za uređive prijavnice instalirajte i aktivirajte Forminator. Bez plugina ostaje aktivna ugrađena rezervna prijavnica.', 'ingbiro' ) . '</p></div>';
}
add_action( 'admin_notices', 'ingbiro_forminator_notice' );

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

function ingbiro_event_pdf_url( $event_id ) {
	return add_query_arg(
		array(
			'action'   => 'ingbiro_event_pdf',
			'event_id' => absint( $event_id ),
		),
		admin_url( 'admin-post.php' )
	);
}

function ingbiro_pdf_image_data_uri( $path ) {
	if ( ! $path || ! is_readable( $path ) ) {
		return '';
	}

	$filetype = wp_check_filetype( $path );
	$mime     = $filetype['type'] ?: 'image/jpeg';
	$data     = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	return $data ? 'data:' . $mime . ';base64,' . base64_encode( $data ) : ''; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
}

function ingbiro_handle_event_pdf() {
	$event_id = isset( $_GET['event_id'] ) ? absint( $_GET['event_id'] ) : 0;
	$event    = $event_id ? get_post( $event_id ) : null;

	if ( ! $event || 'ing_event' !== $event->post_type || 'publish' !== $event->post_status ) {
		wp_die( esc_html__( 'Događanje nije pronađeno.', 'ingbiro' ), 404 );
	}

	$autoload = get_template_directory() . '/vendor/dompdf/dompdf/autoload.inc.php';
	if ( ! file_exists( $autoload ) ) {
		wp_die( esc_html__( 'PDF generator nije dostupan.', 'ingbiro' ), 500 );
	}
	require_once $autoload;

	$logo_path = get_template_directory() . '/assets/images/logo.png';
	$hero_path = ingbiro_event_image_path( $event_id );

	$logo    = ingbiro_pdf_image_data_uri( $logo_path );
	$hero    = ingbiro_pdf_image_data_uri( $hero_path );
	$content = apply_filters( 'the_content', $event->post_content );
	$content = preg_replace( '/<figure\b.*?<\/figure>/s', '', $content );
	$content = preg_replace( '/<img\b[^>]*>/s', '', $content );
	$content = preg_replace( '/<div class="event-program-gears".*?<\/div>/s', '', $content );
	$content = strip_tags( $content, '<h2><h3><p><ul><ol><li><strong><b><em><br><a><img><figure>' );

	$meta_cards = array(
		__( 'Predavač', 'ingbiro' ) => get_post_meta( $event_id, 'ing_event_speaker', true ),
		__( 'Datum', 'ingbiro' )     => get_post_meta( $event_id, 'ing_event_date', true ),
		__( 'Početak', 'ingbiro' )   => get_post_meta( $event_id, 'ing_event_start', true ) ?: get_post_meta( $event_id, 'ing_event_hours', true ),
		__( 'Lokacija', 'ingbiro' )  => get_post_meta( $event_id, 'ing_event_location', true ),
		__( 'Kotizacija', 'ingbiro' ) => get_post_meta( $event_id, 'ing_event_fee', true ),
	);

	ob_start();
	?>
	<!doctype html>
	<html lang="hr">
	<head>
		<meta charset="utf-8">
		<style>
			@page { margin: 34px 42px 58px; }
			body { margin: 0; color: #1b1b1b; font-family: "DejaVu Sans", sans-serif; font-size: 10px; line-height: 1.42; }
			.header { padding-bottom: 18px; border-bottom: 2px solid #244e9c; }
			.logo { width: 175px; }
			.kicker { margin: 20px 0 8px; color: #244e9c; font-size: 10px; font-weight: bold; text-transform: uppercase; }
			h1 { margin: 0 0 12px; font-size: 25px; line-height: 1.12; }
			h2 { margin: 24px 0 8px; color: #244e9c; font-size: 16px; line-height: 1.2; page-break-after: avoid; }
			h3 { margin: 16px 0 6px; font-size: 12px; page-break-after: avoid; }
			p { margin: 0 0 9px; }
			.hero { width: 100%; height: 230px; margin: 18px 0; object-fit: cover; border-radius: 12px; }
			.meta { width: 100%; margin: 4px 0 20px; border-collapse: separate; border-spacing: 6px; }
			.meta td { width: 33.33%; padding: 12px; vertical-align: top; border-radius: 8px; background: #244e9c; color: white; }
			.meta strong { display: block; margin-bottom: 4px; color: #fff4e5; font-size: 8px; text-transform: uppercase; }
			.content ul, .content ol { padding-left: 20px; }
			.content img { display: block; width: 100%; max-width: 100%; height: auto; margin: 8px 0 14px; border-radius: 8px; }
			.event-block { padding: 8px 0 14px; page-break-inside: auto; }
			.event-block > h2 { padding-bottom: 5px; border-bottom: 1px solid #dae0e1; text-transform: uppercase; }
			.event-why-layout, .event-fee-columns { width: 100%; }
			.event-why-layout .wp-block-column, .event-fee-columns .wp-block-column { width: 100% !important; }
			.event-image-stack figure { margin: 0 0 14px; page-break-inside: avoid; }
			.event-program h3 { color: #244e9c; page-break-after: avoid; }
			.event-program p, .event-program ul { page-break-inside: avoid; }
			.event-fee-columns .wp-block-column { padding: 10px 12px; margin-bottom: 12px; background: #fff4e5; }
			.footer { position: fixed; right: 42px; bottom: 14px; left: 42px; padding-top: 6px; border-top: 1px solid #dae0e1; color: #61707d; font-size: 8px; }
			a { color: #244e9c; }
		</style>
	</head>
	<body>
		<div class="header">
			<?php if ( $logo ) : ?><img class="logo" src="<?php echo esc_attr( $logo ); ?>" alt="Inženjerski biro"><?php endif; ?>
			<div class="kicker"><?php echo esc_html( get_post_meta( $event_id, 'ing_event_format', true ) ?: __( 'Edukacija', 'ingbiro' ) ); ?></div>
			<h1><?php echo esc_html( get_the_title( $event_id ) ); ?></h1>
			<?php if ( has_excerpt( $event_id ) ) : ?><p><?php echo esc_html( get_the_excerpt( $event_id ) ); ?></p><?php endif; ?>
		</div>
		<?php if ( $hero ) : ?><img class="hero" src="<?php echo esc_attr( $hero ); ?>" alt=""><?php endif; ?>
		<table class="meta">
			<?php
			$chunks = array_chunk( array_filter( $meta_cards ), 3, true );
			foreach ( $chunks as $chunk ) :
				?>
				<tr>
					<?php foreach ( $chunk as $label => $value ) : ?>
						<td><strong><?php echo esc_html( $label ); ?></strong><?php echo esc_html( $value ); ?></td>
					<?php endforeach; ?>
					<?php for ( $i = count( $chunk ); $i < 3; $i++ ) : ?><td></td><?php endfor; ?>
				</tr>
			<?php endforeach; ?>
		</table>
		<div class="content"><?php echo wp_kses( $content, wp_kses_allowed_html( 'post' ), array( 'http', 'https', 'mailto', 'data' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<div class="footer">Inženjerski biro d.o.o. · Heinzelova 4A, Zagreb · ingbiro@ingbiro.hr · (+385) 1 46 00 888</div>
	</body>
	</html>
	<?php
	$html = ob_get_clean();

	$options = new Dompdf\Options();
	$options->set( 'defaultFont', 'DejaVu Sans' );
	$options->set( 'isRemoteEnabled', false );
	$options->setChroot( array( get_template_directory(), wp_upload_dir()['basedir'] ) );

	$pdf = new Dompdf\Dompdf( $options );
	$pdf->loadHtml( $html, 'UTF-8' );
	$pdf->setPaper( 'A4', 'portrait' );
	$pdf->render();
	$pdf->getCanvas()->page_text( 520, 806, '{PAGE_NUM}/{PAGE_COUNT}', null, 8, array( 0.38, 0.44, 0.49 ) );
	$pdf->stream( sanitize_file_name( get_the_title( $event_id ) ) . '.pdf', array( 'Attachment' => true ) );
	exit;
}
add_action( 'admin_post_nopriv_ingbiro_event_pdf', 'ingbiro_handle_event_pdf' );
add_action( 'admin_post_ingbiro_event_pdf', 'ingbiro_handle_event_pdf' );

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

function ingbiro_seed_post( $post_type, $slug, $title, $content, $args = array(), $meta = array() ) {
	$existing = get_page_by_path( $slug, OBJECT, $post_type );
	if ( $existing ) {
		return $existing->ID;
	}

	$post_id = wp_insert_post(
		wp_parse_args(
			$args,
			array(
				'post_type'    => $post_type,
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $title,
				'post_content' => $content,
			)
		)
	);

	if ( $post_id && ! is_wp_error( $post_id ) ) {
		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	return $post_id;
}

function ingbiro_upgrade_content_model() {
	if ( version_compare( (string) get_option( 'ingbiro_content_model_version', '0' ), '1.1.0', '>=' ) ) {
		return;
	}

	$services = array(
		array(
			'ekonomski-konzalting',
			'Ekonomski konzalting',
			'<p><strong>Savjetovanje pri spajanju i preuzimanju kompanija (M&amp;A):</strong></p><ul><li>Izrada informacijskog memoranduma i analiza poslovanja</li><li>Financijski, komercijalni i porezni due diligence</li><li>Procjena vrijednosti poduzeća, udjela i dionica</li><li>Pronalaženje investitora i strukturiranje transakcija</li><li>Pravno-formalna priprema prodaje (skupštine, registracija u trgovačkom registru)</li></ul><p><strong>Strateško i financijsko planiranje:</strong></p><ul><li>Financijsko, operativno i vlasničko restrukturiranje</li><li>Razvojne studije za potrebe restrukturiranja i novih zaduživanja</li><li>Investicijske studije i studije gospodarske opravdanosti za javni sektor</li><li>Priprema projekata za financiranje iz EU fondova i ostalih izvora</li></ul>',
		),
		array(
			'pravni-konzalting',
			'Pravni konzalting',
			'<p><strong>Pravni due diligence</strong> – Dubinska pravna analiza poslovanja.</p><p><strong>Konzultantske usluge</strong> – Podrška gospodarskim subjektima u primjeni propisa iz svih područja građanskog, trgovačkog i radnog prava.</p><p><strong>Edukacije i savjetovanja</strong> – Organizacija općih, tematskih i specijalističkih seminara te radionica o upravljanju društvom i primjeni zakona.</p><p><strong>Stručna literatura</strong> – Izdavanje zbornika radova, autorskih knjiga i priručnika koji prate relevantnu pravnu i stručnu praksu.</p>',
		),
		array(
			'organizacijski-konzalting',
			'Organizacijski konzalting',
			'<p><strong>Snimka i dijagnostika stanja</strong> – Analiza postojećeg organizacijskog sustava i usklađivanje s novim rješenjima.</p><p><strong>Projektiranje organizacije</strong> – Izrada projekata i programa za uvođenje novih, naprednijih organizacijskih rješenja.</p><p><strong>Izrada korporativnih akata</strong> – Pravilnici o organizaciji, sistematizaciji radnih mjesta i radu.</p><p><strong>Tehnički segment</strong> – Izrada tehničkog due diligencea i procjena vrijednosti materijalne imovine (oprema, nekretnine).</p>',
		),
	);

	foreach ( $services as $index => $service ) {
		ingbiro_seed_post(
			'ing_service',
			$service[0],
			$service[1],
			$service[2],
			array( 'menu_order' => $index + 1 )
		);
	}

	$archive_items = array(
		array( 'savjetovanje-12-2025', 'Savjetovanje o novostima u javnoj nabavi (12/2025)', '2025-12-01' ),
		array( 'savjetovanje-10-2025', 'Praktična primjena propisa u poslovanju (10/2025)', '2025-10-01' ),
		array( 'savjetovanje-09-2025', 'Aktualnosti iz radnog prava (09/2025)', '2025-09-01' ),
		array( 'savjetovanje-04-2025', 'Korporativno upravljanje u praksi (04/2025)', '2025-04-01' ),
		array( 'savjetovanje-02-2025', 'Godišnje ekonomsko savjetovanje (02/2025)', '2025-02-01' ),
	);

	foreach ( $archive_items as $item ) {
		ingbiro_seed_post(
			'ing_archive',
			$item[0],
			$item[1],
			'',
			array(),
			array(
				'ing_archive_date' => $item[2],
				'ing_archive_url'  => 'https://ingbiro.hr/',
			)
		);
	}

	$event_content = '<!-- wp:heading --><h2 class="wp-block-heading">Zašto sudjelovati na webinaru?</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Izmjene i dopune ZJN 2016 u području jednostavne nabave donose niz novina koje zahtijevaju razumijevanje zakonodavnog okvira i sigurnost u njihovoj praktičnoj primjeni. Program uključuje konkretne prikaze rada u EOJN RH, od pripreme postupka do objave ugovora.</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">Program webinara</h2><!-- /wp:heading --><!-- wp:list --><ul><li>Usklađivanje općeg akta o provedbi jednostavne nabave</li><li>Priprema i slanje poziva gospodarskim subjektima</li><li>Otvaranje, pregled i ocjena ponuda</li><li>Donošenje odluke o odabiru i objava ugovora</li><li>Pitanja i odgovori</li></ul><!-- /wp:list --><!-- wp:heading --><h2 class="wp-block-heading">Upute za sudionike</h2><!-- /wp:heading --><!-- wp:list --><ul><li>Prijavnicu popunite točnim i obveznim podacima.</li><li>Kotizaciju uplatite najkasnije jedan dan prije održavanja.</li><li>Poveznica za webinar dostavlja se na e-mail naveden u prijavi.</li><li>Registraciju napravite najkasnije 15 minuta prije početka.</li></ul><!-- /wp:list -->';

	$primary_events = get_posts(
		array(
			'post_type'      => 'ing_event',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);
	if ( $primary_events ) {
		$primary_id = $primary_events[0]->ID;
		if ( strlen( trim( $primary_events[0]->post_content ) ) < 700 ) {
			wp_update_post( array( 'ID' => $primary_id, 'post_content' => $event_content ) );
		}
		$primary_meta = array(
			'ing_event_start_date'           => '2026-06-11',
			'ing_event_start'                => '9:00 sati',
			'ing_event_location'             => 'Webinar uživo',
			'ing_event_speaker_role'         => 'Savjetnica za pravne poslove i stručnjakinja za javnu nabavu',
			'ing_event_registration_enabled' => 1,
		);
		foreach ( $primary_meta as $key => $value ) {
			if ( '' === (string) get_post_meta( $primary_id, $key, true ) ) {
				update_post_meta( $primary_id, $key, $value );
			}
		}
	}

	ingbiro_seed_post(
		'ing_event',
		'prakticna-primjena-novog-pravilnika-javne-nabave',
		'Praktična primjena novog Pravilnika o izobrazbi u području javne nabave',
		'<!-- wp:heading --><h2 class="wp-block-heading">Program edukacije</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Interaktivna jednodnevna edukacija vodi polaznike kroz obveze nositelja i polaznika programa, evidenciju prisutnosti, izdavanje potvrda te primjenu novih pravila na stvarnim primjerima.</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">Što ćete naučiti?</h2><!-- /wp:heading --><!-- wp:list --><ul><li>pravilno evidentirati sudjelovanje i nastavne sate</li><li>primijeniti nova pravila u svakodnevnom radu</li><li>prepoznati najčešće proceduralne pogreške</li><li>pripremiti dokumentaciju za nadzor i obnovu certifikata</li></ul><!-- /wp:list --><!-- wp:heading --><h2 class="wp-block-heading">Dodatne informacije</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Materijali za rad uključeni su u cijenu. Broj mjesta je ograničen, a prijave se prihvaćaju redoslijedom zaprimanja.</p><!-- /wp:paragraph -->',
		array(
			'post_excerpt' => 'Nova jednodnevna edukacija s praktičnim primjerima i materijalima za rad.',
		),
		array(
			'ing_event_date'                 => '25. rujna 2026.',
			'ing_event_start_date'           => '2026-09-25',
			'ing_event_format'               => 'UŽIVO · WEBINAR',
			'ing_event_hours'                => '8 nastavnih sati',
			'ing_event_start'                => '9:00 sati',
			'ing_event_location'             => 'Zagreb i online',
			'ing_event_speaker'              => 'Tim stručnjaka Inženjerskog biroa',
			'ing_event_speaker_role'         => 'Certificirani predavači iz područja javne nabave',
			'ing_event_fee'                  => '195,00 eura',
			'ing_event_registration_enabled' => 1,
		)
	);

	update_option( 'ingbiro_content_model_version', '1.1.0' );
}
add_action( 'init', 'ingbiro_upgrade_content_model', 30 );

/**
 * Upgrade the original demo webinar to the complete modular Figma blueprint.
 *
 * The marker is stored on the event so subsequent editor changes are preserved.
 */
function ingbiro_upgrade_event_blueprint() {
	$events = get_posts(
		array(
			'post_type'      => 'ing_event',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	if ( ! $events ) {
		return;
	}

	$event_id = $events[0]->ID;
	if ( version_compare( (string) get_post_meta( $event_id, '_ingbiro_event_blueprint_version', true ), '1.3.0', '>=' ) ) {
		return;
	}

	wp_update_post(
		array(
			'ID'           => $event_id,
			'post_excerpt' => 'Ovo usavršavanje donosi 8 sati za obnovu certifikata. Oslonite se na jasne smjernice i praktične prikaze svih faza jednostavne nabave u EOJN RH.',
			'post_content' => ingbiro_event_blueprint_content(),
		)
	);

	$meta = array(
		'ing_event_format'               => 'UŽIVO · WEBINAR',
		'ing_event_date'                 => '11. lipnja 2026.',
		'ing_event_start_date'           => '2026-06-11',
		'ing_event_hours'                => '8 nastavnih sati',
		'ing_event_start'                => '9:00 sati',
		'ing_event_location'             => 'Webinar uživo',
		'ing_event_speaker'              => 'Ančica Jonjić, dipl. iur.',
		'ing_event_speaker_role'         => 'Savjetnica u Službi za pravne poslove, Središnji državni ured za središnju javnu nabavu',
		'ing_event_fee'                  => '180,00 eura',
		'ing_event_registration_enabled' => 1,
	);

	foreach ( $meta as $key => $value ) {
		update_post_meta( $event_id, $key, $value );
	}
	update_post_meta( $event_id, '_ingbiro_event_blueprint_version', '1.3.0' );
}
add_action( 'init', 'ingbiro_upgrade_event_blueprint', 35 );

/**
 * Repair the original gear decoration so Gutenberg treats it as a supported
 * Custom HTML block instead of unexpected content inside the Program group.
 */
function ingbiro_repair_event_program_blocks() {
	if ( version_compare( (string) get_option( 'ingbiro_event_block_repair_version', '0' ), '1.0.0', '>=' ) ) {
		return;
	}

	$event_ids = get_posts(
		array(
			'post_type'      => 'ing_event',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	$gear_markup = '<div class="event-program-gears" aria-hidden="true"><img src="' . esc_url( ingbiro_asset( 'icons/figma-gear-soft-event.svg' ) ) . '" alt=""><img src="' . esc_url( ingbiro_asset( 'icons/figma-gear-blue-event.svg' ) ) . '" alt=""></div>';
	$gear_block  = "<!-- wp:html -->\n" . $gear_markup . "\n<!-- /wp:html -->";

	foreach ( $event_ids as $event_id ) {
		$content = (string) get_post_field( 'post_content', $event_id );

		if ( ! str_contains( $content, $gear_markup ) || str_contains( $content, $gear_block ) ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $event_id,
				'post_content' => str_replace( $gear_markup, $gear_block, $content ),
			)
		);
	}

	update_option( 'ingbiro_event_block_repair_version', '1.0.0' );
}
add_action( 'init', 'ingbiro_repair_event_program_blocks', 36 );
