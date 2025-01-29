<?php
wp_head();
get_header();

$curr_role = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
$nonce     = isset($_GET['amp;afpvu_nonce']) ? sanitize_text_field($_GET['amp;afpvu_nonce']) : '';


if (!empty(get_option('afpvu_user_role_visibility'))) {

	$role_selected_data = get_option('afpvu_user_role_visibility');
} else {

	$role_selected_data = array();
}

if (!empty($role_selected_data[ esc_attr($curr_role) ]['afpvu_enable_role'])) {

	$role_data = 'yes';
} else {

	$role_data = 'no';
}

if (isset($_GET['afpvu_message']) && !empty($_GET['afpvu_message']) && wp_verify_nonce($nonce, 'afpvu_nonce')) {
	
	?>
<div class="pro_visib_msg">
	<?php
	echo wp_kses_post($_GET['afpvu_message']);
	?>
</div>


<?php
} else {

	?>
<div class="pro_visib_msg">
	<?php

	if ('yes' == $role_data) {

		if (!empty($role_selected_data[ esc_attr($curr_role) ]['afpvu_custom_message_role'])) {

			echo wp_kses_post($role_selected_data[ esc_attr($curr_role) ]['afpvu_custom_message_role']);
		}
	} else {

		$all_roles = wp_roles()->roles;
		$srole     = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
		$r_flag    = true;

		foreach ($all_roles as $role_key => $role_name) {
			$role_data = isset($role_selected_data[ $role_key ]['afpvu_enable_role']) ? $role_selected_data[ $role_key ]['afpvu_enable_role'] : '';

			if (!empty($role_data) && $r_flag) {
				$r_flag = false;
				echo wp_kses_post($role_selected_data[ esc_attr($role_key) ]['afpvu_custom_message_role']);
				break;
			}
		}

		if ($r_flag) {
			echo wp_kses_post(get_option('afpvu_global_custom_msg'));
		}
	}



	?>
</div>


<?php



}


get_footer(); ?>