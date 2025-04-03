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

		$options = get_option( 'conditions_bookingpress_options' );
		$hours   = $options['min_hours_cancel'] ?? 24;

		// Process pending appointments
		$pending_appointments = Conditions_BookingPress_Database::get_pending_appointments( $hours, 1, 1 );

		foreach ( $pending_appointments as $appointment ) {
			Conditions_BookingPress_Database::update_status_appointment( $appointment['bookingpress_appointment_booking_id'] );
		}
		
		// Redirige de nuevo a la página de reportes
		wp_redirect( admin_url( 'admin.php?page=conditions-bookingpress&tab=pending' ) );
		exit;
	}
}