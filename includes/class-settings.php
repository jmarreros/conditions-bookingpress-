<?php

class Conditions_BookingPress_Settings {

    public static function init() {
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }

    public static function register_settings():void {
        register_setting( 'conditions_bookingpress_settings', 'conditions_bookingpress_options', array(
            'type' => 'array',
            'sanitize_callback' => array( __CLASS__, 'sanitize' ),
            'default' => array(
                'email_enabled' => 0,
                'email_subject' => '',
                'email_content' => ''
            )
        ));

        add_settings_section(
            'conditions_bookingpress_email_section',
            __( 'Configuración de Correo', 'conditions-bookingpress' ),
            null,
            'conditions_bookingpress_settings'
        );

        add_settings_field(
            'conditions_bookingpress_email_enabled',
            __( 'Habilitar el envío de correo', 'conditions-bookingpress' ),
            array( __CLASS__, 'email_enabled_callback' ),
            'conditions_bookingpress_settings',
            'conditions_bookingpress_email_section'
        );

        add_settings_field(
            'conditions_bookingpress_email_subject',
            __( 'Asunto', 'conditions-bookingpress' ),
            array( __CLASS__, 'email_subject_callback' ),
            'conditions_bookingpress_settings',
            'conditions_bookingpress_email_section'
        );

        add_settings_field(
            'conditions_bookingpress_email_content',
            __( 'Contenido', 'conditions-bookingpress' ),
            array( __CLASS__, 'email_content_callback' ),
            'conditions_bookingpress_settings',
            'conditions_bookingpress_email_section'
        );
    }

    public static function sanitize( $options ) {
        $options['email_enabled'] = isset( $options['email_enabled'] ) ? 1 : 0;
        $options['email_subject'] = sanitize_text_field( $options['email_subject'] );
        $options['email_content'] = sanitize_textarea_field( $options['email_content'] );
        return $options;
    }

    public static function email_enabled_callback():void {
        $options = get_option( 'conditions_bookingpress_options' );
        $enabled = $options['email_enabled'] ?? 0;
        echo '<input type="checkbox" name="conditions_bookingpress_options[email_enabled]" value="1"' . checked( 1, $enabled, false ) . '>';
    }

    public static function email_subject_callback():void {
        $options = get_option( 'conditions_bookingpress_options' );
        $subject = $options['email_subject'] ?? '';
        echo '<input type="text" name="conditions_bookingpress_options[email_subject]" value="' . esc_attr( $subject ) . '" class="regular-text">';
    }

    public static function email_content_callback():void {
        $options = get_option( 'conditions_bookingpress_options' );
        $content = $options['email_content'] ?? '';
        echo '<textarea name="conditions_bookingpress_options[email_content]" rows="10" cols="50" class="large-text">' . esc_textarea( $content ) . '</textarea>';
        echo '<p class="description">' . __( 'Usa %name% para el nombre del usuario.', 'conditions-bookingpress' ) . '</p>';
    }

    public static function display_settings_page():void {
        include plugin_dir_path( __FILE__ ) . '../admin/views/settings-page.php';
    }
}