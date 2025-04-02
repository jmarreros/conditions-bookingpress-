<?php

class Conditions_BookingPress_Database {

	public static function create_table(): void {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'conditions_bookingpress';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                bookingpress_appointment_id bigint(20) NOT NULL,
                status bool NOT NULL DEFAULT false,
                datetime timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}


	public static function get_pending_appointments( $hours ): array {
		global $wpdb;

		$table_name    = $wpdb->prefix . 'conditions_bookingpress';
		$booking_table = $wpdb->prefix . 'bookingpress_appointment_bookings';

		$query = $wpdb->prepare(
			"SELECT 
            ba.bookingpress_appointment_booking_id, 
            ba.bookingpress_customer_firstname, 
            ba.bookingpress_customer_lastname,
            ba.bookingpress_service_name,
            ba.bookingpress_appointment_date,
            ba.bookingpress_appointment_time,
            ba.bookingpress_created_at,
            TIMESTAMPDIFF(HOUR, ba.bookingpress_created_at, ba.bookingpress_appointment_date) AS diff_created,
            TIMESTAMPDIFF(HOUR, NOW(), ba.bookingpress_appointment_date) AS diff_now
        FROM $booking_table ba
        LEFT JOIN $table_name cb ON ba.bookingpress_appointment_booking_id = cb.bookingpress_appointment_id
        WHERE ba.bookingpress_appointment_status = 2
        AND cb.status IS NULL
        AND TIMESTAMPDIFF(HOUR, ba.bookingpress_created_at, ba.bookingpress_appointment_date) >= %d
        AND TIMESTAMPDIFF(HOUR, NOW(), ba.bookingpress_appointment_date) BETWEEN 0 AND %d",
			$hours, $hours
		);

		return $wpdb->get_results( $query, ARRAY_A );
	}

	public static function get_procceced_appointments(): array {
		global $wpdb;

		$table_name    = $wpdb->prefix . 'conditions_bookingpress';
		$booking_table = $wpdb->prefix . 'bookingpress_appointment_bookings';

		$query = $wpdb->prepare(
			"SELECT 
			ba.bookingpress_appointment_booking_id, 
			ba.bookingpress_customer_firstname, 
			ba.bookingpress_customer_lastname,
			ba.bookingpress_service_name,
			ba.bookingpress_appointment_date,
			ba.bookingpress_appointment_time,
			ba.bookingpress_created_at
		FROM $booking_table ba
		LEFT JOIN $table_name cb ON ba.bookingpress_appointment_booking_id = cb.bookingpress_appointment_id
		WHERE cb.status = 1"
		);

		return $wpdb->get_results( $query, ARRAY_A );
	}
}