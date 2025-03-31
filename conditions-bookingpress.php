<?php
/*
Plugin Name: Conditions BookingPress
Description: Plugin que se integra con BookingPress para controlar las reservas pendientes y cancelarlas
Version: 1.0
Author: Webservi
Author URI: https://webservi.es
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: conditions-bookingpress
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

const CONDITIONS_BOOKINGPRESS_VERSION = '1.0';

// Definir la constante
define( 'CONDITIONS_BOOKINGPRESS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CONDITIONS_BOOKINGPRESS_URL', plugin_dir_url( __FILE__ ) );
define( 'CONDITIONS_BOOKINGPRESS_BASENAME', plugin_basename( __FILE__ ) );

// Incluir archivos necesarios
require_once CONDITIONS_BOOKINGPRESS_PATH . 'includes/class-cron.php';
require_once CONDITIONS_BOOKINGPRESS_PATH . 'includes/class-database.php';
require_once CONDITIONS_BOOKINGPRESS_PATH . 'includes/class-email.php';
require_once CONDITIONS_BOOKINGPRESS_PATH . 'includes/class-reports.php';
require_once CONDITIONS_BOOKINGPRESS_PATH . 'includes/class-settings.php';
require_once CONDITIONS_BOOKINGPRESS_PATH . 'includes/class-plugin.php';
require_once CONDITIONS_BOOKINGPRESS_PATH . 'admin/class-admin-menu.php';

// Inicializar plugin
Conditions_BookingPress_Plugin::init();

add_action( 'plugins_loaded', 'conditions_bookingpress_init' );

function conditions_bookingpress_init(): void {
    Conditions_BookingPress_Cron::init();
    Conditions_BookingPress_Database::init();
    Conditions_BookingPress_Email::init();
    Conditions_BookingPress_Reports::init();
    Conditions_BookingPress_Settings::init();
    Conditions_BookingPress_Admin_Menu::init();
}