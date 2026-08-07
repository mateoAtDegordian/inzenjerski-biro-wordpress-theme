<?php
/**
 * Lightweight editable English section.
 *
 * The English pages are normal hierarchical WordPress pages. Their content is
 * built from core Gutenberg blocks, so no translation plugin or code change is
 * required to edit the supplied copy.
 *
 * @package Ingbiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ingbiro_current_language() {
	$post_id = get_queried_object_id();
	if ( $post_id && 'en' === get_post_meta( $post_id, '_ingbiro_language', true ) ) {
		return 'en';
	}

	return 'hr';
}

function ingbiro_is_english() {
	return 'en' === ingbiro_current_language();
}

/**
 * The English site intentionally has no conference archive.
 *
 * Remove the page created by an earlier archive migration and clear the HR
 * page's stale translation link so the language switch returns to English home.
 */
function ingbiro_remove_english_archive_page() {
	if ( '1.0.0' === get_option( 'ingbiro_english_archive_removed_version' ) ) {
		return;
	}

	$english_archive = get_page_by_path( 'en/archive', OBJECT, 'page' );
	if ( $english_archive ) {
		wp_delete_post( $english_archive->ID, true );
	}

	$croatian_archive = get_page_by_path( 'arhiva', OBJECT, 'page' );
	if ( $croatian_archive ) {
		delete_post_meta( $croatian_archive->ID, '_ingbiro_translation_id' );
	}

	update_option( 'ingbiro_english_archive_removed_version', '1.0.0' );
}
add_action( 'init', 'ingbiro_remove_english_archive_page', 50 );

/**
 * Keep Croatian and English pages on the same PHP structure.
 */
function ingbiro_l( $croatian, $english ) {
	return ingbiro_is_english() ? $english : $croatian;
}

/**
 * Static copy translations used by the shared HR/EN page templates.
 *
 * Keeping one HTML/PHP template per page means structural and CSS changes are
 * applied to both languages automatically.
 */
function ingbiro_english_output_translations() {
	return array(
		'Upoznajte najdugovječniju konzultantsku kuću u Republici Hrvatskoj' => 'Meet Croatia’s longest-running consultancy',
		'75 godina iskustva na izradi studija, elaborata, strategija i pravilnika za klijente iz poduzetničkog i javnog sektora.' => '75 years of experience developing studies, reports, strategies and regulations for private- and public-sector clients.',
		'Inovativnim funkcionalnim rješenjima, alatima i profesionalnim i poslovnim uslugama pružamo podršku poslovnim i pravnim subjektima u Republici Hrvatskoj u rješavanju poslovnih izazova i ostvarivanju konkurentnosti.' => 'Through innovative solutions, tools and professional services, we help business and legal entities solve complex challenges and strengthen their competitiveness.',
		'Naši stručnjaci specijalizirani su za područje prava, organizacije poslovanja i ekonomije, što nam omogućuje da projektima pristupimo na interdisciplinaran način te da budemo konkurentni.' => 'Our specialists in law, business organization and economics bring a comprehensive, interdisciplinary approach to every project.',
		'Neovisno o kojem se sektoru radi, ostvarili smo suradnju s ključnim akterima u njemu. Obratite nam se s povjerenjem.' => 'Across sectors, we have built lasting relationships with key institutions and market participants. Talk to a trusted business adviser.',
		'Jedan od vodećih poduzetnika u području LegalTecha.' => 'One of Croatia’s LegalTech leaders.',
		'Područja djelovanja' => 'Areas of expertise',
		'Ekonomski, pravni i organizacijski konzalting koji pokriva cjelokupan razvoj vašeg poslovanja:' => 'Business, legal and management consulting across the full development of your organization:',
		'Izrada <strong>poslovnih planova i strategija</strong> te njihova implementacija' => 'Development and implementation of <strong>business plans and strategies</strong>',
		'Procesi <strong>restrukturiranja i reorganizacije</strong>' => '<strong>Restructuring and reorganization</strong> processes',
		'<strong>Komunikacijski menadžment i korporativno pravo</strong>' => '<strong>Communications management and corporate law</strong>',
		'Ekonomski, pravni i tehnički <strong>due diligence</strong>' => 'Economic, legal and technical <strong>due diligence</strong>',
		'<strong>Razvojne i investicijske studije</strong>' => '<strong>Development and investment studies</strong>',
		'Pravni portal nove generacije koji poslovnim ljudima omogućuje jednostavno usklađivanje poslovanja s propisima RH i EU.' => 'A next-generation legal portal that helps professionals keep their operations aligned with Croatian and EU regulations.',
		'<strong>Budite u tijeku:</strong> napredni sustav obavijesti o promjenama u propisima.' => '<strong>Stay informed:</strong> advanced notifications about regulatory changes.',
		'<strong>Stručni sadržaj:</strong> sentencije, stručni članci i mišljenja.' => '<strong>Professional content:</strong> legal headnotes, expert articles and opinions.',
		'<strong>Lansiran 2024. godine</strong> kao nastavak tradicije pravne publicistike.' => '<strong>Launched in 2024</strong> as the continuation of our legal publishing tradition.',
		'Okupljamo vodeće stručnjake iz područja prava, javne uprave i gospodarstva.' => 'We bring together leading experts in law, public administration and economics.',
		'<strong>Ovlašteni nositelj:</strong> programi izobrazbe u području javne nabave i edukacije iz područja osiguranja.' => '<strong>Authorized provider:</strong> public procurement and insurance training programs.',
		'<strong>Tradicija i povjerenje:</strong> izvršni organizator tradicionalnog savjetovanja Hrvatskog društva ekonomista.' => '<strong>Tradition and trust:</strong> executive organizer of the Croatian Economic Association’s traditional annual conference.',
		'Newsletter pretplata' => 'Newsletter subscription',
		'Budite u tijeku s najnovijim zakonskim promjenama i poslovnim propisima.' => 'Stay up to date with the latest legislative changes and business regulations.',
		'Pročitajte više' => 'Read more',

		'Evolucija uspjeha:<br>Tradicija u službi modernog poslovanja' => 'An evolution of success:<br>tradition serving modern business',
		'Brzi linkovi' => 'Quick links',
		'Upoznajte nas<br>Naša ekspertiza<br>Povezanost s korijenima<br>Mjesto susreta znanstvene i stručne zajednice<br>Suradnja s vodećim institucijama' => 'Meet us<br>Our expertise<br>Rooted in history<br>A meeting point for professionals and scholars<br>Cooperation with leading institutions',
		'Upoznajte nas' => 'Meet us',
		'INŽENJERSKI BIRO d.o.o. jedna je od vodećih konzultantskih kuća u Republici Hrvatskoj sa 75 godina iskustva na izradi studija, elaborata, strategija i pravilnika za klijente iz poduzetničkog i javnog sektora, kao i jedan od vodećih poduzetnika u području LegalTecha i digitalnog izdavaštva pravnih publikacija.' => 'INŽENJERSKI BIRO d.o.o. is one of Croatia’s leading consultancies, with 75 years of experience developing studies, reports, strategies and regulations for private- and public-sector clients, and is one of the country’s leaders in LegalTech and digital legal publishing.',
		'Naša ekspertiza' => 'Our expertise',
		'Pružamo konzultantske usluge velikome broju klijenata iz različitih sektora hrvatskoga gospodarstva, iz područja brodogradnje, naftne industrije, obnovljivih izvora energije, regionalnog razvoja, turizma i sektora poduzetništva. Dio svojih poslovnih usluga obavljamo putem revizorskih kuća kojima je INŽENJERSKI BIRO d.o.o. suosnivač.' => 'We advise clients across many sectors of the Croatian economy, including shipbuilding, oil, renewable energy, regional development, tourism and entrepreneurship. Part of our professional services is delivered through auditing companies co-founded by INŽENJERSKI BIRO d.o.o.',
		'Pružamo konzultantske usluge izuzetno velikome broju klijenata iz različitih sektora hrvatskoga gospodarstva, iz područja brodogradnje, naftne industrije, obnovljivih izvora energije, regionalnog razvoja, turizma i sektora poduzetništva. Dio svojih poslovnih usluga obavljamo putem revizorskih kuća kojima je INŽENJERSKI BIRO d.o.o. suosnivač. One su organizirane kao društva s ograničenom odgovornošću sa sjedištima u Rijeci i Zadru.' => 'We provide consulting services to a vast number of clients across various sectors of the Croatian economy, including shipbuilding, the oil industry, renewable energy, regional development, tourism and entrepreneurship. Part of our professional services is delivered through auditing companies co-founded by INŽENJERSKI BIRO d.o.o. These are limited liability companies based in Rijeka and Zadar.',
		'U veljači 2024. lansirali smo inovativni proizvod u LegalTech niši – Pravni portal' => 'In February 2024 we launched an innovative LegalTech product – the LING Legal Portal',
		'. Radi se o pravnom portalu nove generacije, koji sadrži napredne funkcionalnosti i ključne kolekcije pravnih dokumenata, izdanja i pravnih alata. LING sadrži i napredni sustav obavještavanja o promjenama u propisima te objavi novih sentenci, stručnih članaka i mišljenja iz odabranog područja prava.' => '. It is a new-generation legal portal featuring advanced functionalities and key collections of legal documents, publications and legal tools. LING also has an advanced notification system that informs users when regulations change and new legal headnotes, professional papers and opinions in selected areas of law are published.',
		'U veljači 2024. lansirali smo inovativni proizvod u LegalTech niši – Pravni portal' => 'In February 2024, we launched an innovative LegalTech product – the LING Legal Portal',
		'Portal sadrži napredne funkcionalnosti, ključne kolekcije pravnih dokumenata, izdanja i pravnih alata.' => 'The portal combines advanced functionality with essential collections of legal documents, publications and practical legal tools.',
		'Povezanost s korijenima' => 'Rooted in history',
		'Lansiranje LING-a predstavlja nastavak duge tradicije koju Inženjerski biro ima u pravnoj publicistici, a koja seže u 1950-e godine. Tada je počela izlaziti publikacija ING Registar pravnih propisa, pravni priručnik koji je desetljećima bio nezamjenjiv u radu mnogih generacija pravnika i poslovnih stručnjaka.' => 'LING continues Inženjerski biro’s legal publishing tradition, which reaches back to the 1950s and the launch of the ING Register of Regulations, a reference used by generations of legal and business professionals.',
		'Lansiranje LING-a predstavlja nastavak duge tradicije koju Inženjerski biro ima u pravnoj publicistici, a koja seže u 1950-e godine. Tada je počela izlaziti publikacija ING Registar pravnih propisa, pravni priručnik koji je desetljećima bio nezamjenjiv u radu mnogih generacija pravnika, odvjetnika i drugih poslovnih ljudi i stručnjaka te koji se izdavao na nekoliko jezika. S vremenom smo počeli izdavati i druge cijenjene pravne publikacije, među ostalim, časopis Hrvatsku pravnu reviju, Hrvatsku gospodarsku reviju, publikaciju ING Pregled sudske prakse, zbornike, specijalističke priručnike i druge publikacije.' => 'The release of LING continues Inženjerski biro’s long-standing tradition in legal publishing. In the 1950s, the ING Register of Regulations began publication: a legal manual that was indispensable for decades to generations of legal experts, lawyers and other business professionals, and was published in several languages. Over the years, our portfolio expanded to include the Croatian Law Review, the Croatian Economic Review, the ING Review of Case-Law, conference proceedings, specialist manuals and other publications.',
		'Mjesto susreta znanstvene i stručne zajednice' => 'A meeting point for the scientific and professional community',
		'Redovito organiziramo savjetovanja i edukacije koje okupljaju vodeće stručnjake i znanstvenike iz raznih područja prava, javne uprave i gospodarstva. Ovlašteni smo nositelj Programa izobrazbe u području javne nabave i pružatelj edukacije iz područja osiguranja.' => 'We regularly organize conferences and training programs that bring together leading experts and scholars in law, public administration and economics. We are an authorized provider of public procurement and insurance training.',
		'Redovito organiziramo savjetovanja i edukacije koje okupljaju vodeće stručnjake i znanstvenike iz raznih područja prava, javne uprave i gospodarstva. Ovlašteni smo nositelj Programa izobrazbe u području javne nabave i pružatelj edukacije iz područja osiguranja. Izvršni smo organizator tradicionalnog godišnjeg savjetovanja Hrvatskog društva ekonomista od njegovih začetaka.' => 'Our conferences and training programs bring together leading experts and scholars in law, public administration and economics. We are an authorized provider of the Training Program in the Field of Public Procurement, as well as specialist training in insurance. We have also served as the executive organizer of the Croatian Economic Association’s traditional annual conference since its inception.',
		'Suradnja s vodećim institucijama' => 'Cooperation with leading institutions',
		'Naša dugogodišnja suradnja s vodećim obrazovnim i strukovnim institucijama usmjerena je unapređenju ekonomske i pravne znanosti i struke u Republici Hrvatskoj.' => 'Our long-standing cooperation with leading educational and professional institutions supports the advancement of economic and legal scholarship and practice in Croatia.',
		'Naša dugogodišnja suradnja s vodećim obrazovnim i strukovnim institucijama, kao što su Ekonomski fakultet Sveučilišta u Zagrebu, Sveučilišta u Splitu i Sveučilišta u Osijeku, Fakultet za menadžment u turizmu i ugostiteljstvu Opatija, Pravni fakultet Sveučilišta u Rijeci i Sveučilišta u Zagrebu, Hrvatsko društvo ekonomista, Udruga hrvatskih sudaca, Hrvatska revizorska komora i dr., usmjerena je unapređenju ekonomske i pravne znanosti i struke u Republici Hrvatskoj.' => 'Our long-standing cooperation with leading educational and professional institutions — including the Faculty of Economics and Business of the University of Zagreb, the Universities of Split and Osijek, the Faculty of Tourism and Hospitality Management in Opatija, the Faculties of Law of the Universities of Rijeka and Zagreb, the Croatian Economic Association, the Association of Croatian Judges, the Croatian Audit Chamber and others — advances economic and legal scholarship and professional practice in Croatia.',
		'Kontakt' => 'Contact',
		'Adresa:' => 'Address:',
		'Telefon:' => 'Phone:',
		'Faks:' => 'Fax:',
		'Opći kontakt:' => 'General enquiries:',
		'Prodaja:' => 'Sales:',
		'Pravni portal:' => 'Legal portal:',
		'Hrvatska' => 'Croatia',
		'Podaci o poduzeću' => 'Company information',
		'IBAN kod Privredne banke:' => 'IBAN at Privredna banka Zagreb:',
		'Uprava društva:' => 'Management:',

		'Konzalting' => 'Consulting',
		'Usmjereni smo ka iznalaženju sigurnih, visokokvalitetnih i dugoročnih rješenja i rezultata koji našim klijentima donose konkurentnu prednost i jačanje tržišne pozicije.' => 'We deliver secure, high-quality and long-term solutions that give our clients a competitive advantage and strengthen their market position.',
		'Naše usluge' => 'Our services',
		'Ekonomski konzalting' => 'Business consulting',
		'Pravni konzalting' => 'Legal consulting',
		'Organizacijski konzalting' => 'Management consulting',
		'INŽENJERSKI BIRO d.o.o. ima stručnu potporu ovlaštenih revizorskih kuća, društava-kćeri:' => 'INŽENJERSKI BIRO d.o.o. is professionally supported by its authorized auditing subsidiaries:',

		'Pravni portal<br>LING' => 'LING Legal Portal',
		'Pravni portal LING' => 'LING Legal Portal',
		'Pravni portal nove generacije, dizajniran da zadovolji sve potrebe modernih pravnih profesionalaca, od sudaca, odvjetnika i javnih bilježnika preko pravnika u gospodarstvu, do djelatnika državnih i lokalnih institucija.' => 'A next-generation legal portal for modern legal professionals — from judges, attorneys and notaries to corporate lawyers and public-sector employees.',
		'Posjetite portal' => 'Visit the portal',
		'Vaš preglednik ne podržava HTML5 video.' => 'Your browser does not support HTML5 video.',
		'Korištenje portala' => 'Using the portal',
		'Prijava na pravni portal LING omogućuje neograničen pristup svim kolekcijama unutar portala i korištenje alatima koji olakšavaju svakodnevni rad naših korisnika, a odabir vlastitih interesnih područja prava omogućuje personalizaciju sustava obavijesti i jamči pravovremenu informiranost o izmjenama propisa, objavama novih sentenci, stručnih članaka i mišljenja iz odabranih područja.' => 'Logging into the LING Legal Portal provides unlimited access to all collections and practical tools, while the selection of legal areas of interest personalizes notifications about regulatory changes, new legal headnotes, expert articles and opinions.',
		'Sadržaj pravnog portala' => 'Legal portal content',
		'Kolekcije pravnog portala LING' => 'LING Legal Portal collections',
		'pročišćeni tekstovi propisa' => 'consolidated texts of regulations',
		'objave Narodnih novina' => 'Official Gazette publications',
		'sudska praksa svih sudova RH, Suda Europske unije i Europskog suda za ljudska prava' => 'case law of Croatian courts, the Court of Justice of the EU and the European Court of Human Rights',
		'sentencije sudskih odluka' => 'legal headnotes of court decisions',
		'stručni članci eminentnih autora' => 'expert articles by eminent authors',
		'odluke DKOM-a' => 'decisions of the State Commission for Supervision of Public Procurement Procedures',
		'međunarodni ugovori' => 'international treaties',
		'mišljenja i odluke javnopravnih tijela' => 'opinions and decisions of public-law bodies',
		'primjeri i obrasci' => 'templates and forms',
		'Pomoćni alati' => 'Supporting tools',
		'Za potrebe jednostavnog rješavanja pravnih, administrativnih i računovodstvenih zadataka u svakodnevnoj praksi, pravni portal LING sadržava i pomoćne alate:' => 'For everyday legal, administrative and accounting tasks, the LING Legal Portal also includes practical supporting tools:',
		'odgovori iz kolekcija sudske prakse' => 'answers based on case-law collections',
		'evidencije predmeta i izračun radnji' => 'case tracking and calculation of legal actions',
		'zakonske zatezne kamate i sudske pristojbe' => 'statutory default interest and court fees',
		'Sav sadržaj portala LING je međusobno povezan, što znatno utječe na uštedu vremena naših korisnika.' => 'All content on the LING Legal Portal is interconnected, saving users significant time.',
		'Pretplatite se na pravni portal LING' => 'Subscribe to the LING Legal Portal',
		'Saznajte više' => 'Learn more',

		'Savjetovanja i edukacije' => 'Conferences and training',
		'Imamo dugu tradiciju izvođenja i organiziranja savjetovanja i edukacija za pravnike i ekonomiste.<br><br>Pogledajte što je u najavi.' => 'We have a long-standing tradition of organizing and delivering conferences and training programs for legal and business professionals.',
		'Savjetovanja' => 'Conferences',
		'Redovito organiziramo savjetovanja koja okupljaju vodeće stručnjake i znanstvenike iz raznih područja prava, javne uprave i gospodarstva, a koja je moguće pratiti uživo ili online.' => 'We regularly organize conferences that bring together leading experts and scholars in law, public administration and economics. Participants can attend in person or online.',
		'Izvršni smo organizator tradicionalnog godišnjeg savjetovanja Hrvatskog društva ekonomista kojem je moguće prisustvovati isključivo uživo.' => 'We are the executive organizer of the Croatian Economic Association’s traditional annual conference, which is an exclusively in-person event.',
		'Edukacije' => 'Training programs',
		'INŽENJERSKI BIRO d.o.o. ovlašteni je nositelj Programa izobrazbe u području javne nabave prema Rješenju o ovlaštenju Ministarstva gospodarstva, od 1. ožujka 2024. godine, izdanom na rok od tri godine.' => 'INŽENJERSKI BIRO d.o.o. is an authorized provider of the Public Procurement Training Program under the Ministry of Economy’s authorization dated 1 March 2024 and issued for a three-year period.',
		'Naš tim predavača čine certificirani i renomirani stručnjaci te redovni predavači na specijalističkim programima izobrazbe, seminarima, radionicama i stručnim programima usavršavanja.' => 'Our teaching team consists of certified and renowned experts and regular lecturers in specialist programs, seminars, workshops and professional development.',
		'Ujedno smo ovlašteni pružatelj edukacije radi kontinuiranog ispunjavanja uvjeta stručnosti distributera osiguranja i reosiguranja.' => 'We are also an authorized provider of continuing professional training for insurance and reinsurance distributors.',
		'Javna nabava' => 'Public procurement',
		'Inženjerski biro d.o.o., Zagreb ovlašteni je nositelj Programa izobrazbe u području javne nabave prema rješenju Ministarstva gospodarstva izdanom u 2024. godini na rok od tri godine.' => 'Inženjerski biro d.o.o., Zagreb is an authorized provider of the Public Procurement Training Program under a Ministry of Economy decision issued in 2024 for a three-year period.',
		'Osiguranje' => 'Insurance',
		'Inženjerski biro d.o.o. ovlašteni je pružatelj edukacije radi kontinuiranog ispunjavanja uvjeta stručnosti iz članka 422. Zakona o osiguranju i u skladu s Pravilnikom o stručnosti i primjerenosti distributera osiguranja i reosiguranja.' => 'Inženjerski biro d.o.o. is an authorized training provider for continuous professional competence under Article 422 of the Insurance Act and the rules governing insurance and reinsurance distributors.',

		'Kontaktirajte nas' => 'Contact us',
		'Ime i prezime' => 'Full name',
		'Broj telefona' => 'Phone number',
		'Predmet' => 'Subject',
		'Poruka' => 'Message',
		'Pošaljite' => 'Send',
		'Pošaljite upit' => 'Send enquiry',

		'Pridružite se našem timu' => 'Join our team',
		'Otvorene pozicije' => 'Open positions',
		'Otvorena prijava' => 'Open application',
		'Tražimo stručne, radoznale i pouzdane kolegice i kolege iz područja prava, ekonomije, organizacije poslovanja i edukacije.' => 'We welcome knowledgeable, curious and dependable colleagues from law, economics, business organization and education.',
		'Pošaljite nam otvorenu prijavu i predstavite kako možete doprinijeti našem timu.' => 'Send us an open application and tell us how you can contribute to our team.',
		'Pošaljite prijavu' => 'Apply now',
		'Prijava: Otvorena prijava' => 'Application: Open application',
		'Trenutno nemamo otvorenih pozicija' => 'We currently have no open positions',
		'Uvijek rado upoznajemo stručne i motivirane ljude. Pošaljite nam otvorenu prijavu.' => 'We are always glad to meet skilled and motivated people. Send us an open application.',
		'Pretplatite se na naš newsletter' => 'Subscribe to our newsletter',
	);
}

function ingbiro_get_english_consulting_services() {
	$services = array(
		array(
			'Business consulting',
			'<ul><li>Business advisory in mergers and acquisitions (M&amp;A): preparation of information memorandums, financial, commercial, and tax due diligence reports, business analyses, and valuations of companies, shares, and stocks; performing necessary preparatory actions for company sale; assistance in sourcing potential investors; drafting final transaction structures and transaction documents; conducting preparatory activities for holding general meetings and electing new corporate bodies; implementation of adopted resolutions in the commercial register</li><li>Business analysis and company valuation</li><li>Preparation of company development studies for restructuring purposes, securing new financing from financial institutions, etc.</li><li>Financial, operational, and ownership restructuring</li><li>Project preparation for grant funding from EU funds and other sources</li><li>Preparation of investment studies tailored to investor requirements</li><li>Preparation of economic feasibility studies for the public sector</li></ul>',
		),
		array(
			'Legal consulting',
			'<ul><li>Preparation of legal due diligence reports</li><li>Organizing and conducting professional conferences and training programs (both general and thematic) on issues related to corporate management and business functions, as well as the practical application of regulations across all areas of civil, commercial, labor, and other fields of law</li><li>Organizing specialized conferences tailored to the needs of our business partners</li><li>Providing consulting services to business entities on the application of laws and other regulations across various legal fields, as well as publishing conference proceedings, authored books, and manuals that promote legal theory and monitor relevant professional practice</li></ul>',
		),
		array(
			'Management consulting',
			'<ul><li>Assessment, analysis, and diagnostics of the current state of business and other organizational systems</li><li>Analysis of existing organizational regulations and their alignment with new organizational solutions</li><li>Design of new organizational projects</li><li>Development of implementation programs for new organizational solutions aimed at improving business operations</li><li>Drafting rules of organization, job classification rulebooks, and rules of operation</li><li>Preparation of technical due diligence reports</li><li>Valuation of tangible company assets (equipment, real estate).</li></ul>',
		),
	);

	return array_map(
		static function ( $service ) {
			$item               = new stdClass();
			$item->post_title   = $service[0];
			$item->post_content = $service[1];
			return $item;
		},
		$services
	);
}

function ingbiro_english_root_url() {
	$page = get_page_by_path( 'en' );
	return $page ? get_permalink( $page ) : home_url( '/en/' );
}

function ingbiro_language_home_url() {
	return ingbiro_is_english() ? ingbiro_english_root_url() : home_url( '/' );
}

function ingbiro_translation_url( $language ) {
	$post_id        = get_queried_object_id();
	$translation_id = $post_id ? absint( get_post_meta( $post_id, '_ingbiro_translation_id', true ) ) : 0;

	if ( $language === ingbiro_current_language() ) {
		return $post_id ? get_permalink( $post_id ) : ingbiro_language_home_url();
	}

	if ( $translation_id ) {
		return get_permalink( $translation_id );
	}

	return 'en' === $language ? ingbiro_english_root_url() : home_url( '/' );
}

function ingbiro_english_page_url( $slug = '' ) {
	$path = $slug ? 'en/' . trim( $slug, '/' ) : 'en';
	$page = get_page_by_path( $path );
	return $page ? get_permalink( $page ) : home_url( '/' . $path . '/' );
}

function ingbiro_english_menu() {
	$items = array(
		'about-us'                => 'About us',
		'consulting'              => 'Consulting',
		'legal-portal'            => 'Legal portal',
		'conferences-and-training'=> 'Conferences and training',
	);

	echo '<ul class="site-nav__list">';
	foreach ( $items as $slug => $label ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( ingbiro_english_page_url( $slug ) ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

function ingbiro_english_language_attributes( $output ) {
	if ( ingbiro_is_english() ) {
		$output = preg_replace( '/lang="[^"]+"/', 'lang="en"', $output );
	}
	return $output;
}
add_filter( 'language_attributes', 'ingbiro_english_language_attributes' );

function ingbiro_block_section( $heading, $body, $class = '' ) {
	return sprintf(
		'<!-- wp:group {"className":"modular-section %1$s","layout":{"type":"constrained"}} --><div class="wp-block-group modular-section %1$s"><!-- wp:heading --><h2 class="wp-block-heading">%2$s</h2><!-- /wp:heading -->%3$s</div><!-- /wp:group -->',
		esc_attr( $class ),
		esc_html( $heading ),
		$body
	);
}

function ingbiro_block_paragraphs( $paragraphs ) {
	$output = '';
	foreach ( $paragraphs as $paragraph ) {
		$output .= '<!-- wp:paragraph --><p>' . wp_kses_post( $paragraph ) . '</p><!-- /wp:paragraph -->';
	}
	return $output;
}

function ingbiro_block_list( $items ) {
	$list = '';
	foreach ( $items as $item ) {
		$list .= '<li>' . wp_kses_post( $item ) . '</li>';
	}
	return '<!-- wp:list --><ul class="wp-block-list">' . $list . '</ul><!-- /wp:list -->';
}

function ingbiro_block_hero( $title, $intro, $image ) {
	return sprintf(
		'<!-- wp:group {"align":"full","className":"modular-hero","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull modular-hero"><!-- wp:columns {"className":"modular-hero__copy"} --><div class="wp-block-columns modular-hero__copy"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">%1$s</h1><!-- /wp:heading --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph --><p>%2$s</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:image {"sizeSlug":"full","className":"modular-hero__image"} --><figure class="wp-block-image size-full modular-hero__image"><img src="%3$s" alt=""/></figure><!-- /wp:image --></div><!-- /wp:group -->',
		esc_html( $title ),
		wp_kses_post( $intro ),
		esc_url( $image )
	);
}

function ingbiro_block_video_hero( $title, $intro, $video, $poster ) {
	return sprintf(
		'<!-- wp:group {"align":"full","className":"modular-hero","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull modular-hero"><!-- wp:columns {"className":"modular-hero__copy"} --><div class="wp-block-columns modular-hero__copy"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">%1$s</h1><!-- /wp:heading --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph --><p>%2$s</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:video {"className":"modular-hero__image modular-hero__video"} --><figure class="wp-block-video modular-hero__image modular-hero__video"><video controls playsinline preload="metadata" poster="%4$s" src="%3$s"></video></figure><!-- /wp:video --></div><!-- /wp:group -->',
		esc_html( $title ),
		wp_kses_post( $intro ),
		esc_url( $video ),
		esc_url( $poster )
	);
}

function ingbiro_block_cards( $cards, $class = '' ) {
	$columns = '';
	foreach ( $cards as $card ) {
		$columns .= '<!-- wp:column --><div class="wp-block-column modular-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html( $card['title'] ) . '</h3><!-- /wp:heading -->' . $card['content'] . '</div><!-- /wp:column -->';
	}

	return '<!-- wp:columns {"className":"modular-cards ' . esc_attr( $class ) . '"} --><div class="wp-block-columns modular-cards ' . esc_attr( $class ) . '">' . $columns . '</div><!-- /wp:columns -->';
}

/**
 * Create or return an English child page.
 */
function ingbiro_seed_english_page( $slug, $title, $content, $parent_id = 0 ) {
	$path     = $parent_id ? 'en/' . $slug : $slug;
	$existing = get_page_by_path( $path );
	if ( $existing ) {
		if ( version_compare( (string) get_post_meta( $existing->ID, '_ingbiro_seed_version', true ), '1.2.2', '<' ) ) {
			wp_update_post(
				array(
					'ID'           => $existing->ID,
					'post_title'   => $title,
					'post_content' => $content,
				)
			);
			update_post_meta( $existing->ID, '_ingbiro_seed_version', '1.2.2' );
		}
		return $existing->ID;
	}

	$page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_parent'  => $parent_id,
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => $content,
		)
	);

	if ( $page_id && ! is_wp_error( $page_id ) ) {
		update_post_meta( $page_id, '_wp_page_template', 'page-english.php' );
		update_post_meta( $page_id, '_ingbiro_language', 'en' );
		update_post_meta( $page_id, '_ingbiro_seed_version', '1.2.2' );
	}

	return $page_id;
}

function ingbiro_link_translation_pages( $english_id, $croatian_slug ) {
	$croatian = get_page_by_path( $croatian_slug );
	if ( $croatian && $english_id ) {
		update_post_meta( $english_id, '_ingbiro_translation_id', $croatian->ID );
		update_post_meta( $croatian->ID, '_ingbiro_translation_id', $english_id );
	}
}

/**
 * Import the supplied English DOCX copy into editable Gutenberg pages.
 */
function ingbiro_seed_english_content() {
	if ( ! shortcode_exists( 'forminator_form' ) || ! ingbiro_get_form_id( 'contact' ) ) {
		return;
	}

	if ( version_compare( (string) get_option( 'ingbiro_english_content_version', '0' ), '1.3.1', '>=' ) ) {
		return;
	}

	$home_content  = ingbiro_block_hero(
		'Meet the longest-running consultancy in Croatia.',
		'75 years of experience in developing studies, reports, strategies, and regulations for private and public sector clients.',
		ingbiro_asset( 'images/home-hero.jpg' )
	);
	$home_content .= ingbiro_block_section(
		'About us',
		ingbiro_block_paragraphs(
			array(
				'<strong>One of the leaders in LegalTech.</strong>',
				'Through innovative functional solutions, tools, and professional and business services, we support business and legal entities in Croatia in overcoming business challenges and achieving competitiveness.',
				'Our experts specialize in the fields of law, business organization, and economics, enabling a comprehensive, multi-layered approach to every project and maintaining our competitive edge.',
				'Regardless of the sector, we have collaborated and maintained close ties with its key players. Partner with a trusted business advisor.',
			)
		)
	);
	$home_content .= ingbiro_block_section(
		'Our areas of expertise',
		ingbiro_block_cards(
			array(
				array(
					'title'   => 'Consulting',
					'content' => ingbiro_block_paragraphs( array( 'Our business, legal, and management consulting services encompass a comprehensive range of solutions. We support businesses from the initial drafting of business plans and corporate strategies, and their implementation, through restructuring and reorganization. Additionally, we provide expert guidance in communication management, corporate law, performing economic, legal, and technical due diligence, as well as drafting development and investment studies.' ) ),
				),
				array(
					'title'   => 'LING Legal Portal',
					'content' => ingbiro_block_paragraphs( array( 'A next-generation legal portal that enables business professionals — both in the legal sector and other industries — to seamlessly align their operations with Croatian and EU regulations. It keeps users informed about new legislation relevant to their specific field and business operations. The portal features an advanced notification system for regulatory changes, as well as updates on new court decisions, professional articles, and official opinions in chosen areas of law.', 'Launched in 2024, the portal builds upon the company’s long-standing tradition in legal publishing, which dates back to the 1950s.' ) ),
				),
				array(
					'title'   => 'Conferences and webinars',
					'content' => ingbiro_block_paragraphs( array( 'Our conferences and webinars bring together leading experts in law, public administration, and economics, representing a key starting point for those who wish to remain updated on current developments.', 'We are an authorized provider of the Training Program in the Field of Public Procurement, as well as specialized training in the insurance sector.', 'For the last 34 years, we have proudly served as the executive organizer of the traditional annual conference for the Croatian Economic Association.' ) ),
				),
			),
			'modular-cards--three'
		)
	);
	$en_home = ingbiro_seed_english_page( 'en', 'Inženjerski biro', $home_content );

	$about_content  = ingbiro_block_hero(
		'Consultants since 1952',
		'One of Croatia’s leading consultancies and one of the leaders in LegalTech.',
		ingbiro_asset( 'images/about-hero.jpg' )
	);
	$about_content .= ingbiro_block_section(
		'Inženjerski biro',
		ingbiro_block_paragraphs(
			array(
				'INŽENJERSKI BIRO d.o.o. is one of Croatia’s leading consultancies with over 75 years of developing studies, reports, strategies, and regulations for private and public sector clients. We are also one of the leaders in LegalTech.',
			)
		)
	);
	$about_content .= ingbiro_block_section(
		'Our areas of expertise',
		ingbiro_block_paragraphs(
			array(
				'We provide consulting services to a vast number of clients across various sectors of the Croatian economy, including shipbuilding, the oil industry, renewable energy, regional development, tourism, and entrepreneurship. Part of our professional services is delivered through auditing companies co-founded by INŽENJERSKI BIRO d.o.o.',
				'In February 2024 we launched an innovative LegalTech product – the LING Legal Portal. It is a new-generation legal portal featuring advanced functionalities and key collections of legal documents, publications and legal tools. LING also has an advanced notification system that informs users when regulatory changes occur and new legal headnotes, professional papers, and opinions are published.',
			)
		)
	);
	$about_content .= ingbiro_block_section(
		'Rootedness in history',
		ingbiro_block_paragraphs( array( 'The release of LING represents a continuation of Inženjerski biro’s long-standing tradition in legal publishing. In the 1950s the ING Register of Regulations began publication. Over the years, our portfolio expanded to include the Croatian Law Review, the Croatian Economic Review, the Ing Review of Case-Law, conference proceedings, specialized publications and more.' ) )
	);
	$about_content .= ingbiro_block_section(
		'A meeting point for the scientific and professional community',
		ingbiro_block_paragraphs( array( 'Our conferences and webinars bring together leading experts in law, public administration, and economics. We are an authorized provider of the Training Program in the Field of Public Procurement, as well as specialized training in the insurance sector. We have also served as the executive organizer of the traditional annual conference for the Croatian Economic Association since its inception.' ) )
	);
	$about_content .= ingbiro_block_section(
		'Cooperation with leading institutions',
		ingbiro_block_paragraphs( array( 'Our long-standing cooperation with leading educational and professional institutions, such as the Faculty of Economics and Business of the University of Zagreb, the University of Split, the University of Osijek, the Faculty of Tourism and Hospitality Management in Opatija, the Faculties of Law of the Universities of Rijeka and Zagreb, the Croatian Economic Association, the Association of Croatian Judges, the Croatian Audit Chamber, and others, is aimed at advancing economic and legal science and the profession in Croatia.' ) )
	);
	$about_content .= ingbiro_block_section(
		'Contact and company information',
		ingbiro_block_paragraphs(
			array(
				'<strong>INŽENJERSKI BIRO d.o.o. za poslovne i računalne usluge</strong><br>Ulica Vjekoslava Heinzela 4A, 10000 Zagreb, Croatia<br>Phone: +385/1 46 00 888 · Fax: +385/1 46 00 876<br>General enquiries: <a href="mailto:ingbiro@ingbiro.hr">ingbiro@ingbiro.hr</a> · Sales: <a href="mailto:prodaja@ingbiro.hr">prodaja@ingbiro.hr</a>',
				'IBAN: HR2323400091100205049 · SWIFT: PBZGHR2X · Tax Identification Number (OIB): 84170114747.',
				'Inženjerski biro d.o.o. is entered into the Zagreb Commercial Court’s registry under Company Registration Number (MBS) 080008032. The share capital is fully paid up and amounts to EUR 809,760.00. Management: Mladen Mlinarević, CEO.',
			)
		)
	);
	$about_id = ingbiro_seed_english_page( 'about-us', 'About us', $about_content, $en_home );

	$consulting_content  = ingbiro_block_hero(
		'Consulting',
		'We are dedicated to delivering secure, high-quality, and long-term solutions and results that provide our clients with a competitive edge and a stronger market position.',
		ingbiro_asset( 'images/consulting-hero.jpg' )
	);
	$consulting_content .= ingbiro_block_section(
		'Business consulting',
		ingbiro_block_list(
			array(
				'<strong>Business advisory in mergers and acquisitions (M&amp;A):</strong> preparation of information memorandums, financial, commercial, and tax due diligence reports, business analyses, and valuations of companies, shares, and stocks; performing necessary preparatory actions for company sale; assistance in sourcing potential investors; drafting final transaction structures and transaction documents; conducting preparatory activities for holding general meetings and electing new corporate bodies; implementation of adopted resolutions in the commercial register.',
				'Business analysis and company valuation.',
				'Preparation of company development studies for restructuring purposes, securing new financing from financial institutions, etc.',
				'Financial, operational, and ownership restructuring.',
				'Project preparation for grant funding from EU funds and other sources.',
				'Preparation of investment studies tailored to investor requirements.',
				'Preparation of economic feasibility studies for the public sector.',
			)
		)
	);
	$consulting_content .= ingbiro_block_section(
		'Legal consulting',
		ingbiro_block_list(
			array(
				'Preparation of legal due diligence reports.',
				'Organizing and conducting professional conferences and training programs (both general and thematic) on issues related to corporate management and business functions, as well as the practical application of regulations across all areas of civil, commercial, labor, and other fields of law.',
				'Organizing specialized conferences tailored to the needs of our business partners.',
				'Providing consulting services to business entities on the application of laws and other regulations across various legal fields, as well as publishing conference proceedings, authored books, and manuals that promote legal theory and monitor relevant professional practice.',
			)
		)
	);
	$consulting_content .= ingbiro_block_section(
		'Management consulting',
		ingbiro_block_list(
			array(
				'Assessment, analysis, and diagnostics of the current state of business and other organizational systems.',
				'Analysis of existing organizational regulations and their alignment with new organizational solutions.',
				'Design of new organizational projects.',
				'Development of implementation programs for new organizational solutions aimed at improving business operations.',
				'Drafting rules of organization, job classification rulebooks, and rules of operation.',
				'Preparation of technical due diligence reports.',
				'Valuation of tangible company assets (equipment, real estate).',
			)
		)
	);
	$consulting_content .= ingbiro_block_section(
		'Professional support',
		ingbiro_block_paragraphs( array( 'Part of our professional services is delivered through auditing companies co-founded by INŽENJERSKI BIRO d.o.o.: HLB Adria Inženjerski biro d.o.o., Strossmayerova 11, 51000 Rijeka and INŽENJERSKI BIRO-REVIZIJA d.o.o., Poljana Plankit 1, 23000 Zadar.' ) )
	);
	$consulting_id = ingbiro_seed_english_page( 'consulting', 'Consulting', $consulting_content, $en_home );

	$legal_content  = ingbiro_block_video_hero(
		'LING Legal Portal',
		'A next-generation legal portal tailored to modern legal professionals — from judges, attorneys, and notaries to corporate lawyers and public institution employees — who rely on daily access to relevant legal information and accurate data.',
		ingbiro_asset( 'video/ling-promo.mp4' ),
		ingbiro_asset( 'images/ling-video-poster.jpg' )
	);
	$legal_content .= ingbiro_block_section(
		'Using the LING Legal Portal',
		ingbiro_block_paragraphs( array( 'Logging into the LING Legal Portal grants unlimited access to all collections within the portal and the use of tools that facilitate our users’ daily work, while selecting specific legal fields of interest enables personalization of the notification system and guarantees timely information on regulatory changes, new legal headnotes of court decisions, professional articles, and opinions in the chosen areas.' ) ),
		'modular-section--gears'
	);
	$legal_content .= ingbiro_block_section(
		'Legal portal content',
		ingbiro_block_cards(
			array(
				array(
					'title'   => 'LING Legal Portal collections',
					'content' => ingbiro_block_list( array( 'Consolidated texts of regulations', 'Official Gazette publications', 'Case law of all Croatian courts, the Court of Justice of the European Union, and the European Court of Human Rights', 'Legal headnotes of court decisions', 'Professional articles by eminent authors', 'Decisions of the State Commission for Supervision of Public Procurement Procedures (DKOM)', 'International treaties', 'Opinions and decisions of public law bodies', 'Templates and forms – official forms and sample legal acts' ) ),
				),
				array(
					'title'   => 'Supporting tools',
					'content' => ingbiro_block_paragraphs( array( 'To easily resolve legal, administrative, and accounting tasks in day-to-day practice, the legal portal also includes interactive utility tools:' ) ) . ingbiro_block_list( array( '<strong>LINGBot (CHATBot)</strong> – An AI assistant that responds to legal queries using case law databases and highlights relevant court decisions', '<strong>Attorney Fee Schedule</strong> – a tool for case tracking, calculating legal actions, and generating cost statements in accordance with the Tariff', '<strong>Calculators</strong> – for calculating statutory default interest, court fees, as well as attorney fees and remuneration' ) ),
				),
			),
			'modular-cards--portal'
		)
	);
	$legal_content .= ingbiro_block_section(
		'All content on the LING Legal Portal is interconnected, which significantly saves our users’ time.',
		ingbiro_block_paragraphs( array( '<a href="https://ling.hr/">Visit the LING Legal Portal</a>' ) ),
		'modular-section--ribbon'
	);
	$legal_id = ingbiro_seed_english_page( 'legal-portal', 'LING Legal Portal', $legal_content, $en_home );

	$education_content  = ingbiro_block_hero(
		'Conferences and training programs',
		'We have a long-standing tradition of organizing and delivering professional conferences, webinars and training programs for legal and business professionals. Explore our upcoming events.',
		ingbiro_asset( 'images/education-hero.jpg' )
	);
	$education_content .= ingbiro_block_section(
		'Areas of activity',
		ingbiro_block_cards(
			array(
				array(
					'title'   => 'Conferences',
					'content' => ingbiro_block_paragraphs( array( 'Our conferences that bring together leading law, public administration and economic experts and scholars are organized on a regular basis. Participants can attend either in-person or online.', 'We are the executive organizer of the Croatian Economic Association’s traditional annual conference, which is strictly an in-person event.' ) ),
				),
				array(
					'title'   => 'Training programs',
					'content' => ingbiro_block_paragraphs( array( 'INŽENJERSKI BIRO d.o.o. is an authorized provider of the Public Procurement Training Program under the Authorization Decision of the Ministry of Economy, dated 1 March 2024, Class: UP/I-406-01/12-01/04, Ref. No.: 517-08-04-02-03-24-23, issued for a three-year period in 2024.', 'Our lecturing team consists exclusively of certified and renowned experts, as well as regular lecturers in specialist training programs, seminars, workshops, and professional development programs in the field of public procurement. They are all officially registered with the Ministry of Economy’s Registry of Lecturers and have extensive practical experience in the public procurement sector.', 'INŽENJERSKI BIRO d.o.o. is also an authorized training provider for the continuous development of professional competence, in line with Article 422 of the Insurance Act and the Ordinance on Insurance and Reinsurance Distributors, under the official authorization issued by the Croatian Financial Services Supervisory Agency (HANFA) in 2020.' ) ),
				),
			),
			'modular-cards--education'
		)
	);
	$education_content .= ingbiro_block_section(
		'Upcoming events',
		ingbiro_block_paragraphs( array( 'Current dates, programs, speakers, registration, and downloadable information are maintained in the Events section of the website.' ) )
	);
	$education_id = ingbiro_seed_english_page( 'conferences-and-training', 'Conferences and training', $education_content, $en_home );

	$contact_content  = ingbiro_block_hero(
		'Contact us',
		'Tell us what you need and our team will get back to you.',
		ingbiro_asset( 'images/building-banner.png' )
	);
	$contact_content .= '<!-- wp:group {"className":"ing-forminator ing-forminator--page","layout":{"type":"constrained"}} --><div class="wp-block-group ing-forminator ing-forminator--page"><!-- wp:shortcode -->[forminator_form id="' . absint( ingbiro_get_form_id( 'contact' ) ) . '"]<!-- /wp:shortcode --></div><!-- /wp:group -->';
	$contact_id = ingbiro_seed_english_page( 'contact', 'Contact', $contact_content, $en_home );
	$careers_id = ingbiro_seed_english_page( 'careers', 'Careers', '', $en_home );
	$career_application_id = ingbiro_seed_english_page( 'career-application', 'Career application', '', $en_home );
	$newsletter_id = ingbiro_seed_english_page( 'newsletter', 'Newsletter', '', $en_home );

	ingbiro_link_translation_pages( $en_home, 'naslovnica' );
	ingbiro_link_translation_pages( $about_id, 'o-nama' );
	ingbiro_link_translation_pages( $consulting_id, 'konzalting' );
	ingbiro_link_translation_pages( $legal_id, 'pravni-portal' );
	ingbiro_link_translation_pages( $education_id, 'savjetovanja-i-edukacije' );
	ingbiro_link_translation_pages( $contact_id, 'kontakt' );
	ingbiro_link_translation_pages( $careers_id, 'karijera' );
	ingbiro_link_translation_pages( $career_application_id, 'prijava-za-posao' );
	ingbiro_link_translation_pages( $newsletter_id, 'newsletter' );

	update_option( 'ingbiro_english_content_version', '1.3.1' );
}
add_action( 'admin_init', 'ingbiro_seed_english_content', 40 );
