<?php

use JetBrains\PhpStorm\NoReturn;

class Conditions_BookingPress_Process {
	public static function init(): void {
		add_action( 'admin_post_process_pending_appointments', array(
			__CLASS__,
			'manual_process_pending_appointments'
		) );
	}

	#[NoReturn] public static function manual_process_pending_appointments(): void {
		if ( ! isset( $_POST['process_pending_appointments_nonce_field'] ) || ! wp_verify_nonce( $_POST['process_pending_appointments_nonce_field'], 'process_pending_appointments_nonce' ) ) {
			wp_die( __( 'Nonce verification failed', 'conditions-bookingpress' ) );
		}

		self::process_pending_appointments();

		// Redirect
		wp_redirect( admin_url( 'admin.php?page=conditions-bookingpress&tab=pending' ) );
		exit;
	}

	public static function process_pending_appointments(): void {
		$options     = get_option( 'conditions_bookingpress_options' );
		$hours       = $options['min_hours_cancel'] ?? 24;
		$send_emails = $options['email_enabled'] ?? true;
		$per_page    = 5;

		// Process pending appointments
		$pending_appointments = Conditions_BookingPress_Database::get_pending_appointments( $hours, 1, $per_page );

		foreach ( $pending_appointments as $appointment ) {
			Conditions_BookingPress_Database::update_status_appointment( $appointment['bookingpress_appointment_booking_id'] );

			// Send email
			if ( $send_emails ) {
				$name  = $appointment['bookingpress_customer_firstname'] ?? '';
				$email = $appointment['bookingpress_customer_email'] ?? '';
				Conditions_BookingPress_Email::send_notifications( $name, $email, $options );
			}
		}
	}

}