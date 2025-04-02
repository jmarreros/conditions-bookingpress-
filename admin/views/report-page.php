<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

$items = $items ?? [];

// Tabs definitions
$plugin_tabs = [
	'pending'   => __( 'Reservas Pendientes', 'conditions-bookingpress' ),
	'cancelled' => __( 'Reservas Procesadas', 'conditions-bookingpress' ),
	'excluded'  => __( 'Reservas Excluidas', 'conditions-bookingpress' ),
];

$current_tab = $_GET['tab'] ?? 'pending';

?>
<div class="wrap">

    <h1><?php _e( 'Reservas Detectadas', 'conditions-bookingpress' ) ?></h1>

	<?php
	// tabs
	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $plugin_tabs as $tab_key => $tab_caption ) {
		$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
		echo "<a data-tab='" . $current_tab . "' class='nav-tab " . $active . "' href='" . admin_url( "?page=conditions-bookingpress&tab=" . $tab_key ) . "'>" . $tab_caption . '</a>';
	}
	echo '</h2>';

	// Partials
	switch ( $current_tab ) {
		case 'pending':
			echo "pending";
			break;
		case 'cancelled':
			echo "cancelled";
			break;
		case 'excluded':
			echo "excluded";
	}
	?>

    <div class="tab-content">
        <table class="wp-list-table widefat striped">
            <thead>
            <tr>
                <th><?php _e( 'ID', 'conditions-bookingpress' ) ?></th>
                <th><?php _e( 'Nombre', 'conditions-bookingpress' ) ?></th>
                <th><?php _e( 'Apellido', 'conditions-bookingpress' ) ?></th>
                <th><?php _e( 'Servicio', 'conditions-bookingpress' ) ?></th>
                <th><?php _e( 'Fecha', 'conditions-bookingpress' ) ?></th>
                <th><?php _e( 'Hora', 'conditions-bookingpress' ) ?></th>
                <th><?php _e( 'Creado', 'conditions-bookingpress' ) ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ( $items as $item ): ?>
                <tr>
                    <td><?php echo $item['bookingpress_appointment_booking_id'] ?></td>
                    <td><?php echo $item['bookingpress_customer_firstname'] ?></td>
                    <td><?php echo $item['bookingpress_customer_lastname'] ?></td>
                    <td><?php echo $item['bookingpress_service_name'] ?></td>
                    <td><?php echo $item['bookingpress_appointment_date'] ?></td>
                    <td><?php echo $item['bookingpress_appointment_time'] ?></td>
                    <td><?php echo $item['bookingpress_created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

</div><!--wrap -->
