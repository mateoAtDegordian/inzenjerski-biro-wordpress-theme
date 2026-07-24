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

	if ( version_compare( (string) get_option( 'ingbiro_english_content_version', '0' ), '1.2.2', '>=' ) ) {
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
				'<strong>INŽENJERSKI BIRO d.o.o. za poslovne i računalne usluge</strong><br>Heinzelova 4A, 10000 Zagreb, Croatia<br>Phone: +385/1 46 00 888 · Fax: +385/1 46 00 876<br>General enquiries: <a href="mailto:ingbiro@ingbiro.hr">ingbiro@ingbiro.hr</a> · Sales: <a href="mailto:prodaja@ingbiro.hr">prodaja@ingbiro.hr</a>',
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

	ingbiro_link_translation_pages( $en_home, 'naslovnica' );
	ingbiro_link_translation_pages( $about_id, 'o-nama' );
	ingbiro_link_translation_pages( $consulting_id, 'konzalting' );
	ingbiro_link_translation_pages( $legal_id, 'pravni-portal' );
	ingbiro_link_translation_pages( $education_id, 'savjetovanja-i-edukacije' );
	ingbiro_link_translation_pages( $contact_id, 'kontakt' );

	update_option( 'ingbiro_english_content_version', '1.2.2' );
}
add_action( 'admin_init', 'ingbiro_seed_english_content', 40 );
