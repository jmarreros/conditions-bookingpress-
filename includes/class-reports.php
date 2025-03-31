<?php

class Conditions_BookingPress_Reports {

    public static function init() {
        // Inicialización si es necesario
    }

    public static function display_report_page() {
        include plugin_dir_path( __FILE__ ) . '../admin/views/report-page.php';
    }
}