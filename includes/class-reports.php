<?php

class Conditions_BookingPress_Reports {

	public static function init() {
		// Inicialización si es necesario
	}

	public static function display_report_page(): void {
		$options = get_option( 'conditions_bookingpress_options' );
		$hours   = $options['min_hours_cancel'] ?? 24;

		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'pending';
		$items       = [];
		switch ( $current_tab ) {
			case 'pending':
				$items = Conditions_BookingPress_Database::get_pending_appointments( $hours );
				break;
			case 'cancelled':
				$items = Conditions_BookingPress_Database::get_processed_appointments();
				break;
			case 'excluded':
				$items = Conditions_BookingPress_Database::get_excluded_appointments( $hours );
				break;
		}

		include plugin_dir_path( __FILE__ ) . '../admin/views/report-page.php';
	}

}