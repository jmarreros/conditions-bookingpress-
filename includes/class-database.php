<?php
// Status = 2 = Pending
// Status = 3 = Cancelled
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


	public static function get_total_pending_appointments( $hours ): int {
		global $wpdb;

		$table_name    = $wpdb->prefix . 'conditions_bookingpress';
		$booking_table = $wpdb->prefix . 'bookingpress_appointment_bookings';

		$query = $wpdb->prepare(
			"SELECT COUNT(*) 
			        FROM $booking_table ba
			        LEFT JOIN $table_name cb ON ba.bookingpress_appointment_booking_id = cb.bookingpress_appointment_id
			        WHERE ba.bookingpress_appointment_status = 2
			        AND cb.status IS NULL
			        AND TIMESTAMPDIFF(HOUR, ba.bookingpress_created_at, ba.bookingpress_appointment_date) >= %d
			        AND TIMESTAMPDIFF(HOUR, NOW(), ba.bookingpress_appointment_date) BETWEEN 0 AND %d",
			$hours, $hours
		);

		return (int) $wpdb->get_var( $query );
	}

	public static function get_total_processed_appointments(): int {
		global $wpdb;

		$table_name    = $wpdb->prefix . 'conditions_bookingpress';
		$booking_table = $wpdb->prefix . 'bookingpress_appointment_bookings';

		$query = "SELECT COUNT(*)
	              FROM $booking_table ba
	              LEFT JOIN $table_name cb ON ba.bookingpress_appointment_booking_id = cb.bookingpress_appointment_id
	              WHERE cb.status = 1";

		return (int) $wpdb->get_var( $query );
	}

	public static function get_total_excluded_appointments( $hours ): int {
		global $wpdb;

		$table_name    = $wpdb->prefix . 'conditions_bookingpress';
		$booking_table = $wpdb->prefix . 'bookingpress_appointment_bookings';

		$query = $wpdb->prepare(
			"SELECT COUNT(*)
			         FROM $booking_table ba
			         LEFT JOIN $table_name cb ON ba.bookingpress_appointment_booking_id = cb.bookingpress_appointment_id
			         WHERE ba.bookingpress_appointment_status = 2
			         AND cb.status IS NULL
			         AND TIMESTAMPDIFF(HOUR, ba.bookingpress_created_at, ba.bookingpress_appointment_date) < %d",
			$hours
		);

		return (int) $wpdb->get_var( $query );
	}

	public static function get_pending_appointments( $hours, $page = 1, $per_page = 20 ): array {
		global $wpdb;

		$offset        = ( $page - 1 ) * $per_page;
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
				    AND TIMESTAMPDIFF(HOUR, NOW(), ba.bookingpress_appointment_date) BETWEEN 0 AND %d
				    ORDER BY ba.bookingpress_appointment_booking_id DESC
				    LIMIT %d OFFSET %d",
			$hours, $hours, $per_page, $offset
		);

		return $wpdb->get_results( $query, ARRAY_A );
	}

	public static function get_processed_appointments( $page = 1, $per_page = 20 ): array {
		global $wpdb;

		// Status = 2 = Pending

		$offset        = ( $page - 1 ) * $per_page;
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
		WHERE cb.status = 1
		ORDER BY ba.bookingpress_appointment_booking_id DESC
		LIMIT %d OFFSET %d",
			$per_page, $offset
		);

		return $wpdb->get_results( $query, ARRAY_A );
	}

	public static function get_excluded_appointments( $hours, $page = 1, $per_page = 20 ): array {
		global $wpdb;

		$offset        = ( $page - 1 ) * $per_page;
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
        AND TIMESTAMPDIFF(HOUR, ba.bookingpress_created_at, ba.bookingpress_appointment_date) < %d
        ORDER BY ba.bookingpress_appointment_booking_id DESC
        LIMIT %d OFFSET %d",
			$hours, $per_page, $offset );

		return $wpdb->get_results( $query, ARRAY_A );
	}


	public static function update_status_appointment( $id ): void {
		global $wpdb;

		$table_name    = $wpdb->prefix . 'conditions_bookingpress';
		$booking_table = $wpdb->prefix . 'bookingpress_appointment_bookings';

		$wpdb->update(
			$booking_table,
			array( 'status' => 3 ),
			array( 'bookingpress_appointment_id' => $id )
		);

		$wpdb->update(
			$table_name,
			array( 'status' => 1, 'datetime' => current_time( 'mysql' ) ),
			array( 'bookingpress_appointment_id' => $id )
		);
	}
}