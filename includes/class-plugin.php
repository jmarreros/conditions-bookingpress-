<?php

class Conditions_BookingPress_Plugin {

	public static function init(): void {
		error_log( print_r( 'Aqui', true ) );
		register_activation_hook( CONDITIONS_BOOKINGPRESS_BASENAME, array( __CLASS__, 'activate' ) );
		register_deactivation_hook( CONDITIONS_BOOKINGPRESS_BASENAME, array( __CLASS__, 'deactivate' ) );

		add_filter( 'plugin_action_links_' . CONDITIONS_BOOKINGPRESS_BASENAME, array( __CLASS__, 'add_action_links' ) );
	}

	public static function activate(): void {
		error_log( print_r( 'Activando plugin', true ) );

//        Conditions_BookingPress_Cron::activate();
		Conditions_BookingPress_Database::create_table();
	}

	public static function deactivate(): void {
//        Conditions_BookingPress_Cron::deactivate();
	}

	public static function add_action_links( $links ) {
		$settings_link = '<a href="admin.php?page=conditions-bookingpress-settings">' . __( 'Configuración', 'conditions-bookingpress' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}
}