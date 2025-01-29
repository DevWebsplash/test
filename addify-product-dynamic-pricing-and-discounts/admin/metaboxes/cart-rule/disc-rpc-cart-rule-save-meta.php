<?php
defined( 'ABSPATH' ) || exit;

//For custom post type:
$exclude_statuses = array(
	'auto-draft',
	'trash',
);

$action1 = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';

if (in_array(get_post_status($post_id), $exclude_statuses) || is_ajax() || 'untrash' === $action1) {
	return;
}

// Nounce Verify.
if ( empty( $_POST['addf_discount_rpc'] ) || !wp_verify_nonce( sanitize_text_field( $_POST['addf_discount_rpc'] ), 'addf_discount_rpc' ) ) {
	
	die('Failed Security Check!');

}


if ( isset( $_POST['addf_disc_rpc_product_selection_op'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_product_selection_op', sanitize_text_field(  wp_unslash( $_POST['addf_disc_rpc_product_selection_op'] ) ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_product_selection_op', '' );
}

if ( isset( $_POST['addf_disc_rpc_products'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_products', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_products'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_products', array() );
}
if ( isset( $_POST['addf_disc_rpc_categories'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_categories', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_categories'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_categories', array() );
}
if ( isset( $_POST['addf_disc_rpc_roles_select'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_roles_select', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_roles_select'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_roles_select', '' );
}
if ( isset( $_POST['addf_disc_rpc_customers'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_customers', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_customers'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_customers', '' );
}
if ( isset( $_POST['addf_disc_week_days_arr'] ) ) {
	update_post_meta( $post_id, 'addf_disc_week_days_arr', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_week_days_arr'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_week_days_arr', array() );
}
if ( isset( $_POST['addf_disc_rpc_qty_from'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_qty_from', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_qty_from'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_qty_from', '' );
}
if ( isset( $_POST['addf_disc_rpc_qty_to'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_qty_to', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_qty_to'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_qty_to', '' );
}
if ( isset( $_POST['addf_disc_rpc_qty_disc_apply'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_qty_disc_apply', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_qty_disc_apply'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_qty_disc_apply', '' );
}
if ( isset( $_POST['addf_disc_rpc_gift_products_list_qty'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_gift_products_list_qty', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_gift_products_list_qty'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_gift_products_list_qty', '' );
}
if ( isset( $_POST['addf_disc_rpc_gift_products_list'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_gift_products_list', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_gift_products_list'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_gift_products_list', '' );
}
if ( isset( $_POST['addf_disc_rpc_cart_select_customer'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cart_select_customer', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cart_select_customer'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cart_select_customer', '' );
}
if ( isset( $_POST['addf_disc_rpc_disc_on_cart_rule'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_disc_on_cart_rule', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_disc_on_cart_rule'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_disc_on_cart_rule', '' );
}
if ( isset( $_POST['addf_drpc_cust_disc_choice'] ) ) {
	update_post_meta( $post_id, 'addf_drpc_cust_disc_choice', sanitize_meta( '' , wp_unslash( $_POST['addf_drpc_cust_disc_choice'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_drpc_cust_disc_choice', '' );
}
if ( isset( $_POST['addf_disc_rpc_disc_val_tbl_cust'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_disc_val_tbl_cust', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_disc_val_tbl_cust'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_disc_val_tbl_cust', '' );
}
if ( isset( $_POST['addf_disc_rpc_min_qty_tbl_cust'] ) ) {
	$af_cart_min_qty_cust = (array) sanitize_meta( '' , wp_unslash(  $_POST['addf_disc_rpc_min_qty_tbl_cust']  ) , '' );
	foreach ($af_cart_min_qty_cust as $key => $af_cart_min_qty_cust_value) {
		$af_cart_min_qty_cust[ $key ] = ( floatval($af_cart_min_qty_cust_value) > 0 ) ? $af_cart_min_qty_cust_value : 1; 
	}
	update_post_meta( $post_id, 'addf_disc_rpc_min_qty_tbl_cust', $af_cart_min_qty_cust );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_min_qty_tbl_cust', '' );
}
if ( isset( $_POST['addf_disc_rpc_max_qty_tbl_cust'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_max_qty_tbl_cust', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_max_qty_tbl_cust'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_max_qty_tbl_cust', '' );
}
if ( isset( $_POST['addf_disc_rpc_cart_select_user_role'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cart_select_user_role', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cart_select_user_role'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cart_select_user_role', '' );
}
if ( isset( $_POST['addf_disc_rpc_role_disc_on_cart_rule'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_role_disc_on_cart_rule', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_role_disc_on_cart_rule'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_role_disc_on_cart_rule', '' );
}
if ( isset( $_POST['addf_drpc_user_role_disc_choice'] ) ) {
	update_post_meta( $post_id, 'addf_drpc_user_role_disc_choice', sanitize_meta( '' , wp_unslash( $_POST['addf_drpc_user_role_disc_choice'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_drpc_user_role_disc_choice', '' );
}
if ( isset( $_POST['addf_disc_rpc_disc_val_tbl_user_role'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_disc_val_tbl_user_role', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_disc_val_tbl_user_role'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_disc_val_tbl_user_role', '' );
}
if ( isset( $_POST['addf_disc_rpc_min_qty_tbl_user_role'] ) ) {
	$af_cart_min_qty_user_role = (array) sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_min_qty_tbl_user_role'] ) , '' );
	foreach ($af_cart_min_qty_user_role as $key => $af_cart_min_qty_user_role_value) {
		$af_cart_min_qty_user_role[ $key ] = ( floatval($af_cart_min_qty_user_role_value) > 0 ) ? $af_cart_min_qty_user_role_value : 1; 
	}
	update_post_meta( $post_id, 'addf_disc_rpc_min_qty_tbl_user_role', $af_cart_min_qty_user_role );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_min_qty_tbl_user_role', '' );
}
if ( isset( $_POST['addf_disc_rpc_max_qty_tbl_user_role'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_max_qty_tbl_user_role', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_max_qty_tbl_user_role'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_max_qty_tbl_user_role', '' );
}
if ( isset( $_POST['addf_disc_rpc_cart_cond_user_role'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cart_cond_user_role', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cart_cond_user_role'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cart_cond_user_role', '' );
}
if ( isset( $_POST['addf_disc_rpc_cond_role_disc_on_cart_rule'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_role_disc_on_cart_rule', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cond_role_disc_on_cart_rule'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_role_disc_on_cart_rule', '' );
}
if ( isset( $_POST['addf_disc_user_role_cond_cart_gift_list'] ) ) {
	update_post_meta( $post_id, 'addf_disc_user_role_cond_cart_gift_list', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_user_role_cond_cart_gift_list'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_user_role_cond_cart_gift_list', '' );
}
if ( isset( $_POST['addf_disc_rpc_cond_disc_val_tbl_user_role'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_disc_val_tbl_user_role', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cond_disc_val_tbl_user_role'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_disc_val_tbl_user_role', '' );
}
if ( isset( $_POST['addf_disc_rpc_cond_min_qty_tbl_user_role'] ) ) {
	$af_cart_min_qty_user_role = (array) sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cond_min_qty_tbl_user_role'] ) , '' );
	foreach ($af_cart_min_qty_user_role as $key => $af_cart_min_qty_user_role_value) {
		$af_cart_min_qty_user_role[ $key ] = ( floatval($af_cart_min_qty_user_role_value) > 0 ) ? $af_cart_min_qty_user_role_value : 1; 
	}
	update_post_meta( $post_id, 'addf_disc_rpc_cond_min_qty_tbl_user_role', $af_cart_min_qty_user_role );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_min_qty_tbl_user_role', '' );
}
if ( isset( $_POST['addf_disc_rpc_cond_max_qty_tbl_user_role'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_max_qty_tbl_user_role', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cond_max_qty_tbl_user_role'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_max_qty_tbl_user_role', '' );
}
if ( isset( $_POST['addf_disc_rpc_cond_cart_select_customer'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_cart_select_customer', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cond_cart_select_customer'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_cart_select_customer', '' );
}
if ( isset( $_POST['addf_disc_rpc_cond_disc_on_cart_rule'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_disc_on_cart_rule', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cond_disc_on_cart_rule'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_disc_on_cart_rule', '' );
}
if ( isset( $_POST['addf_disc_cust_cart_gift_list'] ) ) {
	update_post_meta( $post_id, 'addf_disc_cust_cart_gift_list', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_cust_cart_gift_list'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_cust_cart_gift_list', '' );
}
if ( isset( $_POST['addf_disc_rpc_cond_qty_tbl_cust'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_qty_tbl_cust', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cond_qty_tbl_cust'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_qty_tbl_cust', '' );
}
if ( isset( $_POST['addf_disc_rpc_cond_min_qty_tbl_cust'] ) ) {
	$af_cart_min_qty_cust = (array) sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cond_min_qty_tbl_cust'] ) , '' );
	foreach ($af_cart_min_qty_cust as $key => $af_cart_min_qty_cust_value) {
		$af_cart_min_qty_cust[ $key ] = ( floatval($af_cart_min_qty_cust_value) > 0 ) ? $af_cart_min_qty_cust_value : 1; 
	}
	update_post_meta( $post_id, 'addf_disc_rpc_cond_min_qty_tbl_cust', $af_cart_min_qty_cust );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_min_qty_tbl_cust', '' );
}
if ( isset( $_POST['addf_disc_rpc_cond_max_qty_tbl_cust'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_max_qty_tbl_cust', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_cond_max_qty_tbl_cust'] ) , '' ) );
} else {
	update_post_meta( $post_id, 'addf_disc_rpc_cond_max_qty_tbl_cust', '' );
}

if ( isset( $_POST['addf_disc_rpc_roles_choice_cb'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_roles_choice_cb', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_roles_choice_cb'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_rule_priority'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_rule_priority', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_rule_priority'] ) , '' ) );
}
if ( isset( $_POST['addf_drpc_discount_type_choice'] ) ) {
	update_post_meta( $post_id, 'addf_drpc_discount_type_choice', sanitize_meta( '' , wp_unslash( $_POST['addf_drpc_discount_type_choice'] ) , '' ) );
}
if ( isset( $_POST['addf_drpc_disc_min_spent_amount'] ) ) {
	update_post_meta( $post_id, 'addf_drpc_disc_min_spent_amount', sanitize_meta( '' , wp_unslash( $_POST['addf_drpc_disc_min_spent_amount'] ) , '' ) );
}
if ( isset( $_POST['addf_drpc_discount_amount_choice'] ) ) {
	update_post_meta( $post_id, 'addf_drpc_discount_amount_choice', sanitize_meta( '' , wp_unslash( $_POST['addf_drpc_discount_amount_choice'] ) , '' ) );
}
if ( isset( $_POST['addf_drpc_discount_amount'] ) ) {
	update_post_meta( $post_id, 'addf_drpc_discount_amount', sanitize_meta( '' , wp_unslash( $_POST['addf_drpc_discount_amount'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_min_qty'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_min_qty', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_min_qty'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_same_price_s_time'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_same_price_s_time', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_same_price_s_time'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_start_time'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_start_time', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_start_time'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_end_time'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_end_time', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_end_time'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_days_radio'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_days_radio', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_days_radio'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_before_disc_msg'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_before_disc_msg', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_before_disc_msg'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_after_disc_msg'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_after_disc_msg', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_after_disc_msg'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_price_or_qty'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_price_or_qty', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_price_or_qty'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_min_spent_amount'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_min_spent_amount', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_min_spent_amount'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_min_start_date'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_min_start_date', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_min_start_date'] ) , '' ) );
}
if ( isset( $_POST['addf_disc_rpc_min_end_date'] ) ) {
	update_post_meta( $post_id, 'addf_disc_rpc_min_end_date', sanitize_meta( '' , wp_unslash( $_POST['addf_disc_rpc_min_end_date'] ) , '' ) );
}
