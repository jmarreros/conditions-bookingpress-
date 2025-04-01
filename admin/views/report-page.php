<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

// Tabs definitions
$plugin_tabs = [
	'pending'   => __( 'Reservas Pendientes', 'conditions-bookingpress' ),
	'cancelled' => __( 'Reservas Canceladas', 'conditions-bookingpress' ),
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

</div><!--wrap -->
