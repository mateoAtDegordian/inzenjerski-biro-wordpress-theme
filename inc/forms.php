<?php
/**
 * Forminator forms and integration hooks.
 *
 * @package Ingbiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the option name used for a managed form.
 */
function ingbiro_form_option( $key ) {
	$options = array(
		'contact'    => 'ingbiro_contact_form_id',
		'newsletter' => 'ingbiro_newsletter_form_id',
		'quick'      => 'ingbiro_quick_newsletter_form_id',
		'career'     => 'ingbiro_career_form_id',
		'event'      => 'ingbiro_default_event_form_id',
	);

	return isset( $options[ $key ] ) ? $options[ $key ] : '';
}

/**
 * Get a managed Forminator form ID.
 */
function ingbiro_get_form_id( $key ) {
	$option = ingbiro_form_option( $key );
	return $option ? absint( get_option( $option ) ) : 0;
}

/**
 * Render a managed form.
 */
function ingbiro_render_form( $key, $class = '' ) {
	$form_id = ingbiro_get_form_id( $key );

	if ( $form_id && shortcode_exists( 'forminator_form' ) ) {
		$previous_form_key                  = isset( $GLOBALS['ingbiro_form_render_key'] ) ? $GLOBALS['ingbiro_form_render_key'] : '';
		$GLOBALS['ingbiro_form_render_key'] = $key;
		$form_markup                       = do_shortcode( sprintf( '[forminator_form id="%d"]', $form_id ) );
		$GLOBALS['ingbiro_form_render_key'] = $previous_form_key;

		printf(
			'<div class="ing-forminator ing-forminator--%1$s %2$s" data-form-key="%1$s">%3$s</div>',
			esc_attr( $key ),
			esc_attr( $class ),
			$form_markup // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
		return;
	}

	echo '<div class="form-notice form-notice--error">';
	echo esc_html__( 'Obrazac trenutačno nije dostupan. Administrator ga može aktivirati u Forminatoru.', 'ingbiro' );
	echo '</div>';
}

/**
 * Return the published job selected for the career application.
 */
function ingbiro_get_career_application_job( $job_id = null ) {
	if ( null === $job_id ) {
		$job_id = isset( $_GET['job_id'] ) ? absint( wp_unslash( $_GET['job_id'] ) ) : 0;
	}

	$job = $job_id ? get_post( absint( $job_id ) ) : null;
	if (
		! $job instanceof WP_Post ||
		'ing_job' !== $job->post_type ||
		'publish' !== $job->post_status
	) {
		return null;
	}

	return $job;
}

/**
 * Adapt the shared career form to its current application context.
 *
 * A specific job stores its title in the existing position field without
 * asking the applicant to repeat it. The open application keeps the same
 * field visible and labels it "Željena pozicija".
 */
function ingbiro_render_career_position_field( $wrappers, $form_id ) {
	if (
		'career' !== ( isset( $GLOBALS['ingbiro_form_render_key'] ) ? $GLOBALS['ingbiro_form_render_key'] : '' ) ||
		ingbiro_get_form_id( 'career' ) !== absint( $form_id )
	) {
		return $wrappers;
	}

	$job          = ingbiro_get_career_application_job();
	$position     = __( 'Željena pozicija', 'ingbiro' );
	$job_id_added = false;

	foreach ( $wrappers as &$wrapper ) {
		if ( empty( $wrapper['fields'] ) || ! is_array( $wrapper['fields'] ) ) {
			continue;
		}

		foreach ( $wrapper['fields'] as &$field ) {
			$element_id = isset( $field['element_id'] ) ? $field['element_id'] : '';

			if ( $job && 'url-1' === $element_id ) {
				$field['cols'] = '12';
			}

			if ( 'text-3' !== $element_id ) {
				continue;
			}

			if ( $job ) {
				$field = array(
					'element_id'   => 'text-3',
					'type'         => 'hidden',
					'cols'         => '12',
					'required'     => 'false',
					'field_label'  => __( 'Pozicija', 'ingbiro' ),
					'default_value' => 'custom_value',
					'custom_value' => $job->post_title,
				);

				$wrapper['fields'][] = array(
					'element_id'    => 'ingbiro_job_id',
					'type'          => 'hidden',
					'cols'          => '12',
					'required'      => 'false',
					'field_label'   => __( 'ID pozicije', 'ingbiro' ),
					'default_value' => 'custom_value',
					'custom_value'  => (string) $job->ID,
				);
				$job_id_added = true;
			} else {
				$field['field_label'] = $position;
				$field['placeholder'] = $position;
			}
		}
		unset( $field );

		if ( $job_id_added ) {
			break;
		}
	}
	unset( $wrapper );

	return $wrappers;
}
add_filter( 'forminator_cform_render_fields', 'ingbiro_render_career_position_field', 20, 2 );

/**
 * Detect the language of the page that rendered or submitted a Forminator form.
 *
 * AJAX submissions do not have a queried WordPress page, so the English form
 * adds a small language marker to its regular Forminator payload.
 */
function ingbiro_form_request_is_english() {
	if ( isset( $_POST['ingbiro_form_language'] ) ) {
		return 'en' === sanitize_key( wp_unslash( $_POST['ingbiro_form_language'] ) );
	}

	return function_exists( 'ingbiro_is_english' ) && ingbiro_is_english();
}

/**
 * English equivalents for labels and custom messages stored in Forminator.
 *
 * The database remains Croatian and editable as one shared form. These values
 * are applied only to the public English render.
 */
function ingbiro_form_english_strings() {
	return array(
		'Ime'                                           => 'First name',
		'Prezime'                                       => 'Last name',
		'Ime i prezime'                                 => 'Full name',
		'Srednje ime'                                   => 'Middle name',
		'Titula'                                        => 'Title',
		'Broj telefona'                                 => 'Phone number',
		'Tvrtka / organizacija'                         => 'Company / organization',
		'Tvrtka / institucija'                          => 'Company / institution',
		'Tvrtka'                                        => 'Company',
		'Grad'                                          => 'City',
		'Država'                                        => 'Country',
		'Vaš e-mail'                                    => 'Your email',
		'Vaša poruka'                                   => 'Your message',
		'Napomena'                                      => 'Note',
		'Željena pozicija'                              => 'Desired position',
		'Pozicija'                                      => 'Position',
		'ID pozicije'                                   => 'Position ID',
		'Životopis (PDF, DOC ili DOCX)'                 => 'CV / résumé (PDF, DOC or DOCX)',
		'Kratko motivacijsko pismo'                     => 'Short cover letter',
		'Pošaljite upit'                                => 'Send enquiry',
		'Pretplatite se'                                => 'Subscribe',
		'Pošaljite prijavu'                             => 'Submit application',
		'Provjerite označena polja i pokušajte ponovno.' => 'Please check the highlighted fields and try again.',
		'Hvala! Vaš upit je poslan.'                    => 'Thank you! Your enquiry has been sent.',
		'Hvala! Uspješno ste se prijavili na newsletter.' => 'Thank you! You have successfully subscribed to the newsletter.',
		'Hvala! Vaša prijava je zaprimljena.'           => 'Thank you! Your application has been received.',
		'Ovo polje je obavezno.'                        => 'This field is required.',
		'Unesite e-mail adresu.'                        => 'Enter your email address.',
		'Unesite ispravnu e-mail adresu.'               => 'Enter a valid email address.',
		'Unesite broj telefona.'                        => 'Enter your phone number.',
		'Unesite ispravan broj telefona.'               => 'Enter a valid phone number.',
		'Unesite poveznicu.'                            => 'Enter a URL.',
		'Unesite ispravnu poveznicu (npr. https://primjer.hr/).' => 'Enter a valid URL (e.g. https://example.com/).',
		'Unesite LinkedIn ili portfolio poveznicu.'     => 'Enter your LinkedIn or portfolio URL.',
		'Unesite željenu poziciju.'                     => 'Enter the desired position.',
		'Unesite naziv tvrtke.'                         => 'Enter the company name.',
		'Dodajte životopis.'                            => 'Upload your CV / résumé.',
		'Unesite kratko motivacijsko pismo.'            => 'Enter a short cover letter.',
		'Dodajte traženu datoteku.'                     => 'Upload the required file.',
		'Provjerite označena polja prije slanja.'       => 'Please correct the highlighted fields before submitting.',
		'Slanje obrasca je u tijeku, pričekajte.'       => 'Submitting form, please wait.',
		'Slanje...'                                     => 'Submitting...',
		'Pokušajte ponovno.'                            => 'Please try again.',
		'Prethodno'                                     => 'Previous',
		'Sljedeće'                                      => 'Next',
		'Pošalji'                                       => 'Submit',
		'Odaberite datoteku'                            => 'Choose File',
		'Datoteka nije odabrana'                        => 'No file chosen',
		'Ukloni odabranu datoteku'                      => 'Delete uploaded file',
		'Uredi obrazac'                                 => 'Edit form',
	);
}

/**
 * Recursively translate Forminator field settings without changing the saved
 * form configuration in the WordPress dashboard.
 */
function ingbiro_translate_forminator_field_values( $values ) {
	$translations = ingbiro_form_english_strings();

	foreach ( $values as $key => $value ) {
		if ( is_array( $value ) ) {
			$values[ $key ] = ingbiro_translate_forminator_field_values( $value );
		} elseif ( is_string( $value ) && isset( $translations[ $value ] ) ) {
			$values[ $key ] = $translations[ $value ];
		}
	}

	return $values;
}

/**
 * Localize all labels, placeholders and custom field validation messages on
 * English pages.
 */
function ingbiro_translate_forminator_fields_to_english( $wrappers, $form_id ) {
	unset( $form_id );

	if ( ! ingbiro_form_request_is_english() ) {
		return $wrappers;
	}

	return ingbiro_translate_forminator_field_values( $wrappers );
}
add_filter( 'forminator_cform_render_fields', 'ingbiro_translate_forminator_fields_to_english', 40, 2 );

/**
 * Replace the submitted position with the authoritative title from WordPress.
 */
function ingbiro_store_career_position( $field_data, $form_id ) {
	if ( ingbiro_get_form_id( 'career' ) !== absint( $form_id ) ) {
		return $field_data;
	}

	$job_id = isset( $_POST['ingbiro_job_id'] ) ? absint( wp_unslash( $_POST['ingbiro_job_id'] ) ) : 0;
	$job    = ingbiro_get_career_application_job( $job_id );

	if ( ! $job ) {
		return $field_data;
	}

	$position_found = false;
	foreach ( $field_data as &$field ) {
		if ( isset( $field['name'] ) && 'text-3' === $field['name'] ) {
			$field['value']   = $job->post_title;
			$position_found   = true;
			break;
		}
	}
	unset( $field );

	if ( ! $position_found ) {
		$field_data[] = array(
			'name'  => 'text-3',
			'value' => $job->post_title,
		);
	}

	return $field_data;
}
add_filter( 'forminator_custom_form_submit_field_data', 'ingbiro_store_career_position', 20, 2 );

/**
 * Small helpers keep the generated forms readable and editable in Forminator.
 */
function ingbiro_form_text_field( $id, $label, $cols = '6', $required = false, $type = 'text' ) {
	$field = array(
		'element_id'  => $id,
		'type'        => $type,
		'cols'        => $cols,
		'required'    => (bool) $required,
		'field_label' => $label,
		'placeholder' => $label,
	);

	if ( 'email' === $type ) {
		$field['validation']      = true;
		$field['validation_text'] = '';
	}

	if ( 'phone' === $type ) {
		$field['validation']            = 'none';
		$field['phone_validation_type'] = 'standard';
		$field['validation_text']       = '';
	}

	return $field;
}

function ingbiro_form_wrapper( $id, $fields ) {
	return array(
		'wrapper_id' => 'ingbiro-' . $id,
		'fields'     => $fields,
	);
}

/**
 * Create one managed form from the shared Forminator contact template.
 */
function ingbiro_create_managed_form( $key, $title, $submit_label, $thank_you, $fields ) {
	$option = ingbiro_form_option( $key );
	if ( ! $option || get_option( $option ) ) {
		return;
	}

	$base               = new Forminator_Template_Contact_Form();
	$template           = new stdClass();
	$template->settings = $base->settings;
	$template->settings['thankyou-message']                    = $thank_you;
	$template->settings['submitData']['custom-submit-text']    = $submit_label;
	$template->settings['submitData']['custom-invalid-form-message'] = __( 'Provjerite označena polja i pokušajte ponovno.', 'ingbiro' );
	$template->settings['enable-ajax']                         = 'true';
	$template->settings['fields-style']                        = 'open';
	$template->settings['form-padding-top']                    = '0';
	$template->settings['form-padding-right']                  = '0';
	$template->settings['form-padding-bottom']                 = '0';
	$template->settings['form-padding-left']                   = '0';
	$template->fields                                          = $fields;

	$form_id = Forminator_Custom_Form_Admin::create( $title, Forminator_Form_Model::STATUS_PUBLISH, $template );
	if ( ! is_wp_error( $form_id ) ) {
		update_option( $option, absint( $form_id ) );
	}
}

/**
 * Provision all theme forms once. Afterwards every field remains editable in
 * Forminator and can use its native notifications, webhooks and integrations.
 */
function ingbiro_create_managed_forms() {
	if ( ! class_exists( 'Forminator_Custom_Form_Admin' ) || ! class_exists( 'Forminator_Template_Contact_Form' ) ) {
		return;
	}

	ingbiro_create_managed_form(
		'contact',
		__( 'Kontakt', 'ingbiro' ),
		__( 'Pošaljite upit', 'ingbiro' ),
		__( 'Hvala! Vaš upit je poslan.', 'ingbiro' ),
		array(
			ingbiro_form_wrapper( 'contact-name', array(
				ingbiro_form_text_field( 'text-1', __( 'Ime', 'ingbiro' ), '6', true ),
				ingbiro_form_text_field( 'text-2', __( 'Prezime', 'ingbiro' ), '6', true ),
			) ),
			ingbiro_form_wrapper( 'contact-details', array(
				ingbiro_form_text_field( 'email-1', __( 'E-mail', 'ingbiro' ), '6', true, 'email' ),
				ingbiro_form_text_field( 'phone-1', __( 'Broj telefona', 'ingbiro' ), '6', false, 'phone' ),
			) ),
			ingbiro_form_wrapper( 'contact-company', array(
				ingbiro_form_text_field( 'text-3', __( 'Tvrtka / organizacija', 'ingbiro' ), '12', false ),
			) ),
			ingbiro_form_wrapper( 'contact-message', array(
				array(
					'element_id'  => 'textarea-1',
					'type'        => 'textarea',
					'cols'        => '12',
					'required'    => 'true',
					'field_label' => __( 'Vaša poruka', 'ingbiro' ),
					'input_type'  => 'paragraph',
				),
			) ),
		)
	);

	ingbiro_create_managed_form(
		'newsletter',
		__( 'Newsletter pretplata', 'ingbiro' ),
		__( 'Pretplatite se', 'ingbiro' ),
		__( 'Hvala! Uspješno ste se prijavili na newsletter.', 'ingbiro' ),
		array(
			ingbiro_form_wrapper( 'newsletter-name', array(
				ingbiro_form_text_field( 'text-1', __( 'Ime', 'ingbiro' ), '6', true ),
				ingbiro_form_text_field( 'text-2', __( 'Prezime', 'ingbiro' ), '6', true ),
			) ),
			ingbiro_form_wrapper( 'newsletter-email-phone', array(
				ingbiro_form_text_field( 'email-1', __( 'E-mail', 'ingbiro' ), '6', true, 'email' ),
				ingbiro_form_text_field( 'phone-1', __( 'Broj telefona', 'ingbiro' ), '6', false, 'phone' ),
			) ),
			ingbiro_form_wrapper( 'newsletter-place', array(
				ingbiro_form_text_field( 'text-3', __( 'Grad', 'ingbiro' ), '6', false ),
				ingbiro_form_text_field( 'text-4', __( 'Država', 'ingbiro' ), '6', false ),
			) ),
			ingbiro_form_wrapper( 'newsletter-company', array(
				ingbiro_form_text_field( 'text-5', __( 'Tvrtka', 'ingbiro' ), '12', false ),
			) ),
		)
	);

	ingbiro_create_managed_form(
		'quick',
		__( 'Newsletter – brza prijava', 'ingbiro' ),
		__( 'Pretplatite se', 'ingbiro' ),
		__( 'Hvala! Uspješno ste se prijavili na newsletter.', 'ingbiro' ),
		array(
			ingbiro_form_wrapper( 'quick-email', array(
				ingbiro_form_text_field( 'email-1', __( 'Vaš e-mail', 'ingbiro' ), '12', true, 'email' ),
			) ),
		)
	);

	ingbiro_create_managed_form(
		'career',
		__( 'Prijava za posao', 'ingbiro' ),
		__( 'Pošaljite prijavu', 'ingbiro' ),
		__( 'Hvala! Vaša prijava je zaprimljena.', 'ingbiro' ),
		array(
			ingbiro_form_wrapper( 'career-name', array(
				ingbiro_form_text_field( 'text-1', __( 'Ime', 'ingbiro' ), '6', true ),
				ingbiro_form_text_field( 'text-2', __( 'Prezime', 'ingbiro' ), '6', true ),
			) ),
			ingbiro_form_wrapper( 'career-contact', array(
				ingbiro_form_text_field( 'email-1', __( 'E-mail', 'ingbiro' ), '6', true, 'email' ),
				ingbiro_form_text_field( 'phone-1', __( 'Broj telefona', 'ingbiro' ), '6', false, 'phone' ),
			) ),
			ingbiro_form_wrapper( 'career-profile', array(
				ingbiro_form_text_field( 'url-1', __( 'LinkedIn / portfolio', 'ingbiro' ), '6', true, 'url' ),
				ingbiro_form_text_field( 'text-3', __( 'Željena pozicija', 'ingbiro' ), '6', true ),
			) ),
			ingbiro_form_wrapper( 'career-company', array(
				ingbiro_form_text_field( 'text-4', __( 'Tvrtka', 'ingbiro' ), '6', true ),
				array(
					'element_id'  => 'upload-1',
					'type'        => 'upload',
					'cols'        => '6',
					'required'    => 'true',
					'field_label' => __( 'Životopis (PDF, DOC ili DOCX)', 'ingbiro' ),
					'filetypes'   => array( 'pdf', 'doc', 'docx' ),
					'file-type'   => 'single',
					'file-limit'  => 'unlimited',
					'upload-limit' => 8,
					'filesize'    => 'MB',
				),
			) ),
			ingbiro_form_wrapper( 'career-message', array(
				array(
					'element_id'  => 'textarea-1',
					'type'        => 'textarea',
					'cols'        => '12',
					'required'    => 'true',
					'field_label' => __( 'Kratko motivacijsko pismo', 'ingbiro' ),
					'input_type'  => 'paragraph',
				),
			) ),
		)
	);

	update_option( 'ingbiro_managed_forms_version', '1.2.0' );
}
add_action( 'admin_init', 'ingbiro_create_managed_forms', 20 );

/**
 * Bring already provisioned career forms in line with their visible labels.
 *
 * This runs once and updates the real Forminator configuration, after which
 * administrators can continue editing the fields normally in Forminator.
 */
function ingbiro_migrate_managed_form_validations() {
	if (
		'1.0.0' === get_option( 'ingbiro_managed_forms_validation_version' ) ||
		! class_exists( 'Forminator_API' )
	) {
		return;
	}

	$form_id = ingbiro_get_form_id( 'career' );
	if ( ! $form_id ) {
		return;
	}

	$updates = array(
		'url-1'      => array(
			'required'           => 'true',
			'required_message'   => __( 'Unesite LinkedIn ili portfolio poveznicu.', 'ingbiro' ),
			'validation_message' => __( 'Unesite ispravnu poveznicu (npr. https://primjer.hr/).', 'ingbiro' ),
		),
		'text-3'     => array(
			'required'         => 'true',
			'field_label'      => __( 'Željena pozicija', 'ingbiro' ),
			'placeholder'      => __( 'Željena pozicija', 'ingbiro' ),
			'required_message' => __( 'Unesite željenu poziciju.', 'ingbiro' ),
		),
		'text-4'     => array(
			'required'         => 'true',
			'required_message' => __( 'Unesite naziv tvrtke.', 'ingbiro' ),
		),
		'upload-1'   => array(
			'required_message' => __( 'Dodajte životopis.', 'ingbiro' ),
		),
		'textarea-1' => array(
			'required_message' => __( 'Unesite kratko motivacijsko pismo.', 'ingbiro' ),
		),
	);

	foreach ( $updates as $field_id => $settings ) {
		$result = Forminator_API::update_form_field( $form_id, $field_id, $settings );
		if ( is_wp_error( $result ) ) {
			return;
		}
	}

	update_option( 'ingbiro_managed_forms_validation_version', '1.0.0' );
}
add_action( 'admin_init', 'ingbiro_migrate_managed_form_validations', 30 );

/**
 * Translate Forminator's public validation and upload messages.
 *
 * The managed forms deliberately keep Forminator's native validation so they
 * remain editable and integration-friendly. These translations cover both
 * inline browser validation and AJAX/server responses.
 */
function ingbiro_translate_forminator_message( $translated, $text, $domain ) {
	if ( 'forminator' !== $domain || ( is_admin() && ! wp_doing_ajax() ) ) {
		return $translated;
	}

	/*
	 * Forminator's source strings are English. Return those originals on the
	 * English site even when the WordPress installation locale is Croatian.
	 */
	if ( ingbiro_form_request_is_english() ) {
		return $text;
	}

	$translations = array(
		'This field is required. Please enter text.'                              => 'Ovo polje je obavezno.',
		'This field is required. Please input a valid email.'                     => 'Unesite e-mail adresu.',
		'This is not a valid email.'                                               => 'Unesite ispravnu e-mail adresu.',
		'This email is not allowed. Please use a different one.'                  => 'Ova e-mail adresa nije dopuštena. Upotrijebite drugu adresu.',
		'You must confirm your email address'                                     => 'Potvrdite svoju e-mail adresu.',
		'Your email addresses do not match'                                       => 'Unesene e-mail adrese se ne podudaraju.',
		'This field is required. Please input a phone number.'                     => 'Unesite broj telefona.',
		'Please input a valid phone number.'                                      => 'Unesite ispravan broj telefona.',
		'Please enter a valid phone number.'                                      => 'Unesite ispravan broj telefona.',
		'Please input a valid international phone number.'                        => 'Unesite ispravan međunarodni broj telefona.',
		'Invalid phone number. %s'                                                 => 'Neispravan broj telefona. %s',
		'You exceeded the allowed amount of numbers. Please check again.'          => 'Unijeli ste previše znamenki. Provjerite broj telefona.',
		'This field is required. Please input a valid URL'                         => 'Unesite poveznicu.',
		'Please enter a valid Website URL (e.g. https://wpmudev.com/).'             => 'Unesite ispravnu poveznicu (npr. https://primjer.hr/).',
		'This field is required. Please upload a file.'                            => 'Dodajte traženu datoteku.',
		'Error saving form. Uploaded file extension is not allowed.'               => 'Odabrana vrsta datoteke nije dopuštena.',
		'Error saving form. Failed to read uploaded file.'                         => 'Datoteku nije moguće pročitati. Pokušajte ponovno.',
		'Error saving form. Upload error.'                                         => 'Prijenos datoteke nije uspio. Pokušajte ponovno.',
		'Sorry, you are not allowed to upload this file type.'                     => 'Ova vrsta datoteke nije dopuštena.',
		'The attached file is empty and can\'t be uploaded.'                       => 'Odabrana datoteka je prazna i nije je moguće prenijeti.',
		'file extension is not allowed.'                                           => 'vrsta datoteke nije dopuštena.',
		'.%s file extension is not allowed.'                                       => 'Datoteke vrste .%s nisu dopuštene.',
		'You can upload a maximum of %d files.'                                    => 'Možete prenijeti najviše %d datoteka.',
		'Maximum file size allowed is %s. '                                        => 'Najveća dopuštena veličina datoteke je %s.',
		'This field is required. Please input your name.'                          => 'Unesite ime i prezime.',
		'This field is required. Please input your first name.'                    => 'Unesite ime.',
		'This field is required. Please input your middle name.'                   => 'Unesite srednje ime.',
		'This field is required. Please input your last name.'                     => 'Unesite prezime.',
		'Prefix is required.'                                                      => 'Unesite titulu.',
		'This field is required. Please select a value.'                           => 'Odaberite jednu od ponuđenih vrijednosti.',
		'Please, enter a custom value'                                             => 'Unesite vlastitu vrijednost.',
		'Selected value does not exist.'                                           => 'Odabrana vrijednost ne postoji.',
		'This field is required. Please check it.'                                 => 'Ovo polje je obavezno.',
		'This field is required. Please enter number.'                             => 'Unesite broj.',
		'This is not valid number.'                                                => 'Unesite ispravan broj.',
		'Please enter a value greater than or equal to {0}.'                       => 'Unesite vrijednost veću od ili jednaku {0}.',
		'Please enter a value less than or equal to {0}.'                          => 'Unesite vrijednost manju od ili jednaku {0}.',
		'You exceeded the allowed amount of characters. Please check again.'       => 'Unijeli ste previše znakova. Skratite unos.',
		'You exceeded the allowed amount of words. Please check again.'            => 'Unijeli ste previše riječi. Skratite unos.',
		'Please correct the errors before submission.'                             => 'Provjerite označena polja prije slanja.',
		'Submitting form, please wait'                                             => 'Slanje obrasca je u tijeku, pričekajte.',
		'Submitting...'                                                            => 'Slanje...',
		'An error occurred while processing the form. Please try again'            => 'Došlo je do pogreške pri obradi obrasca. Pokušajte ponovno.',
		'An upload error occurred while processing the form. Please try again'     => 'Došlo je do pogreške pri prijenosu datoteke. Pokušajte ponovno.',
		'Please try again'                                                         => 'Pokušajte ponovno.',
		'Invalid CAPTCHA'                                                          => 'CAPTCHA provjera nije uspjela.',
		'Previous'                                                                 => 'Prethodno',
		'Next'                                                                     => 'Sljedeće',
		'Submit'                                                                   => 'Pošalji',
		'Choose File'                                                              => 'Odaberite datoteku',
		'No file chosen'                                                           => 'Datoteka nije odabrana',
		'Delete uploaded file'                                                     => 'Ukloni odabranu datoteku',
		'Edit form'                                                                => 'Uredi obrazac',
	);

	return isset( $translations[ $text ] ) ? $translations[ $text ] : $translated;
}
add_filter( 'gettext', 'ingbiro_translate_forminator_message', 20, 3 );

/**
 * Translate the saved submit label and include the language in AJAX requests.
 */
function ingbiro_translate_forminator_submit_markup( $html, $form_id, $post_id, $nonce, $settings ) {
	unset( $form_id, $post_id, $nonce, $settings );

	if ( ! ingbiro_form_request_is_english() ) {
		return $html;
	}

	foreach ( ingbiro_form_english_strings() as $croatian => $english ) {
		$html = str_replace(
			array(
				$croatian,
				htmlentities( $croatian, ENT_QUOTES, 'UTF-8' ),
			),
			esc_html( $english ),
			$html
		);
	}
	$html .= '<input type="hidden" name="ingbiro_form_language" value="en">';

	return $html;
}
add_filter( 'forminator_render_form_submit_markup', 'ingbiro_translate_forminator_submit_markup', 20, 5 );

/**
 * Translate custom database-backed Forminator response messages after submit.
 */
function ingbiro_translate_forminator_response_to_english( $message ) {
	if ( ! ingbiro_form_request_is_english() ) {
		return $message;
	}

	$translations = ingbiro_form_english_strings();
	return isset( $translations[ $message ] ) ? $translations[ $message ] : $message;
}
add_filter( 'forminator_custom_form_invalid_form_message', 'ingbiro_translate_forminator_response_to_english', 20 );
add_filter( 'forminator_custom_form_thankyou_message', 'ingbiro_translate_forminator_response_to_english', 20 );

/**
 * Stable server-side integration point for future CRM/API code.
 *
 * Example:
 * add_action( 'ingbiro_form_submission', 'my_crm_handler', 10, 3 );
 */
function ingbiro_forminator_submission_bridge( $form_id, $response ) {
	if ( empty( $response['success'] ) ) {
		return;
	}

	$key = '';
	foreach ( array( 'contact', 'newsletter', 'quick', 'career', 'event' ) as $candidate ) {
		if ( ingbiro_get_form_id( $candidate ) === absint( $form_id ) ) {
			$key = $candidate;
			break;
		}
	}

	if ( $key ) {
		do_action( 'ingbiro_form_submission', absint( $form_id ), $key, $response );
	}
}
add_action( 'forminator_custom_form_after_save_entry', 'ingbiro_forminator_submission_bridge', 10, 2 );
