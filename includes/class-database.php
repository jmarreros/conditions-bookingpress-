<?php

class Conditions_BookingPress_Database {

	public static function init(): void {
		register_activation_hook( plugin_dir_path( __FILE__ ), array( __CLASS__, 'create_table' ) );
	}

	public static function create_table(): void {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'conditions_bookingpress';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                bookingpress_appointment_id bigint(20) NOT NULL,
                status bool NOT NULL DEFAULT false,
                datetime timestamp NULL DEFAULT NULL,
                exclude bool NOT NULL DEFAULT false,
                PRIMARY KEY (id)
            ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}