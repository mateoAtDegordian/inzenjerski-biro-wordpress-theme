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
		'required'    => $required ? 'true' : 'false',
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
				ingbiro_form_text_field( 'url-1', __( 'LinkedIn / portfolio', 'ingbiro' ), '6', false, 'url' ),
				ingbiro_form_text_field( 'text-3', __( 'Željena pozicija', 'ingbiro' ), '6', false ),
			) ),
			ingbiro_form_wrapper( 'career-company', array(
				ingbiro_form_text_field( 'text-4', __( 'Tvrtka', 'ingbiro' ), '6', false ),
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
