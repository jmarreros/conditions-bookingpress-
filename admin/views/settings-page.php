<div class="wrap">
    <h1><?php _e( 'Configuración de Conditions BookingPress', 'conditions-bookingpress' ); ?></h1>
    <form method="post" action="options.php">
		<?php
		settings_fields( 'conditions_bookingpress_settings' );
		do_settings_sections( 'conditions_bookingpress_settings' );
		submit_button();
		?>
    </form>
</div>