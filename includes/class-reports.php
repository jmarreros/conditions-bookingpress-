<?php

class Conditions_BookingPress_Reports {

	public static function init() {
		// Inicialización si es necesario
	}

	public static function display_report_page(): void {
		$options = get_option( 'conditions_bookingpress_options' );
		$hours   = $options['min_hours_cancel'] ?? 24;

		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'pending';
		$page        = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;

		$items       = [];
		$total_items = 0;
		$per_page    = 30; //get_option( 'posts_per_page' );
		switch ( $current_tab ) {
			case 'pending':
				$total_items = Conditions_BookingPress_Database::get_total_pending_appointments( $hours );
				$items       = Conditions_BookingPress_Database::get_pending_appointments( $hours, $page, $per_page );
				break;
			case 'cancelled':
				$total_items = Conditions_BookingPress_Database::get_total_processed_appointments();
				$items       = Conditions_BookingPress_Database::get_processed_appointments( $page, $per_page );
				break;
			case 'excluded':
				$total_items = Conditions_BookingPress_Database::get_total_excluded_appointments( $hours );
				$items       = Conditions_BookingPress_Database::get_excluded_appointments( $hours, $page, $per_page );
				break;
		}

		include plugin_dir_path( __FILE__ ) . '../admin/views/report-page.php';
	}

}