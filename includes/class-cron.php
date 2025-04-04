<?php

class Conditions_BookingPress_Cron {

    public static function init() {
        add_action( 'conditions_bookingpress_cron_event', array( __CLASS__, 'cron_task' ) );
    }

    public static function activate() :void{
        if ( ! wp_next_scheduled( 'conditions_bookingpress_cron_event' ) ) {
            wp_schedule_event( time(), 'twicedaily', 'conditions_bookingpress_cron_event' );
        }
    }

    public static function deactivate() :void{
        wp_clear_scheduled_hook( 'conditions_bookingpress_cron_event' );
    }

    public static function cron_task(): void {
		Conditions_BookingPress_Process::process_pending_appointments();
    }
}