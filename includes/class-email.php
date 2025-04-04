<?php

class Conditions_BookingPress_Email {
	public static function send_notifications( $user_name, $user_email, $options ): void {
		$subject = $options['email_subject'];
		$content = $options['email_content'];

		if ( empty( $user_email ) ) {
			return;
		}

		$content = str_replace( '%name%', $user_name, $content );

		$contact_url   = get_site_url() . '/contacto';
		$contacto_html = '<a href="' . $contact_url . '">' . __( 'contáctanos', 'conditions-bookingpress' ) . '</a>';
		$content       = str_replace( '%contacto%', $contacto_html, $content );

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $user_email, $subject, $content, $headers );
	}
}