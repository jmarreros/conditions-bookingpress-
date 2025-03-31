<?php

class Conditions_BookingPress_Admin_Menu {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
    }

    public static function add_admin_menu() {
        add_menu_page(
            __( 'Conditions BookingPress', 'conditions-bookingpress' ),
            __( 'Conditions BookingPress', 'conditions-bookingpress' ),
            'manage_options',
            'conditions-bookingpress',
            array( 'Conditions_BookingPress_Reports', 'display_report_page' ),
            'dashicons-admin-generic'
        );

        add_submenu_page(
            'conditions-bookingpress',
            __( 'Configuración', 'conditions-bookingpress' ),
            __( 'Configuración', 'conditions-bookingpress' ),
            'manage_options',
            'conditions-bookingpress-settings',
            array( 'Conditions_BookingPress_Settings', 'display_settings_page' )
        );
    }
}