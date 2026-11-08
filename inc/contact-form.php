<?php
/**
 * Contact Form AJAX Handler & Persistence
 * Processes direct contact messages with nonce security, validation, and email dispatch.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Contact Form Submission
 */
function negar_ajax_submit_contact_form() {
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( mb_strlen( $name, 'UTF-8' ) < 2 ) {
		wp_send_json_error( array( 'message' => esc_html__( 'لطفاً نام و نام خانوادگی خود را کامل وارد نمایید.', 'negar-shop' ) ) );
	}

	if ( empty( $phone ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'لطفاً شماره تماس خود را وارد نمایید.', 'negar-shop' ) ) );
	}

	if ( mb_strlen( $message, 'UTF-8' ) < 8 ) {
		wp_send_json_error( array( 'message' => esc_html__( 'متن پیام شما باید حداقل ۸ کاراکتر باشد.', 'negar-shop' ) ) );
	}

	if ( empty( $subject ) ) {
		$subject = esc_html__( 'پیام عمومی از فرم تماس سایت', 'negar-shop' );
	}

	// Persist submission into transient or option for admin review
	$submission = array(
		'name'       => $name,
		'phone'      => $phone,
		'subject'    => $subject,
		'message'    => $message,
		'time'       => current_time( 'mysql' ),
		'ip'         => preg_replace( '/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR'] ?? '' ),
	);

	$recent_messages = get_option( 'negar_contact_submissions', array() );
	if ( ! is_array( $recent_messages ) ) {
		$recent_messages = array();
	}
	array_unshift( $recent_messages, $submission );
	if ( count( $recent_messages ) > 100 ) {
		$recent_messages = array_slice( $recent_messages, 0, 100 );
	}
	update_option( 'negar_contact_submissions', $recent_messages, false );

	// Optional email dispatch to site administrator
	$admin_email = get_option( 'admin_email' );
	if ( is_email( $admin_email ) ) {
		$email_subject = sprintf( '[%s] %s', get_bloginfo( 'name' ), $subject );
		$email_body    = sprintf(
			"نام: %s\nتلفن: %s\nموضوع: %s\n\nمتن پیام:\n%s\n\nزمان ارسال: %s",
			$name,
			$phone,
			$subject,
			$message,
			current_time( 'Y-m-d H:i:s' )
		);
		@wp_mail( $admin_email, $email_subject, $email_body );
	}

	wp_send_json_success( array(
		'message' => esc_html__( 'پیام شما با موفقیت ثبت شد و به زودی با شما تماس خواهیم گرفت.', 'negar-shop' ),
	) );
}
add_action( 'wp_ajax_negar_submit_contact_form', 'negar_ajax_submit_contact_form' );
add_action( 'wp_ajax_nopriv_negar_submit_contact_form', 'negar_ajax_submit_contact_form' );
