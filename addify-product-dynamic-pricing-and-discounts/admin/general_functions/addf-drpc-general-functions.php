<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function addf_drpc_set_default_setting() {

	if (!get_option('addf_drpc_enable_table_save_column')) {
		update_option('addf_drpc_enable_table_save_column', 'yes');
	}

	if (!get_option('addf_drpc_template_type')) {
		update_option('addf_drpc_template_type', 'table');
	}

	if (!get_option('addf_drpc_template_heading_text') || '' == get_option('addf_drpc_template_heading_text')) {
		update_option('addf_drpc_template_heading_text', 'Select your Deal');
	} 
	
	if (!get_option('addf_drpc_template_heading_font_size') || '' == get_option('addf_drpc_template_heading_font_size')) {
		update_option('addf_drpc_template_heading_font_size', '28');
	} 

	if (!get_option('addf_drpc_table_header_background_color')) {
		update_option('addf_drpc_table_header_background_color', '#FFFFFF');
	}

	if (!get_option('addf_drpc_table_odd_rows_background_color')) {
		update_option('addf_drpc_table_odd_rows_background_color', '#FFFFFF');
	}

	if (!get_option('addf_drpc_table_even_rows_background_color')) {
		update_option('addf_drpc_table_even_rows_background_color', '#FFFFFF');
	} 

	if (!get_option('addf_drpc_table_header_text_color')) {
		update_option('addf_drpc_table_header_text_color', '#000000');
	}

	if (!get_option('addf_drpc_table_odd_rows_text_color')) {
		update_option('addf_drpc_table_odd_rows_text_color', '#000000');
	} 

	if (!get_option('addf_drpc_table_even_rows_text_color')) {
		update_option('addf_drpc_table_even_rows_text_color', '#000000');
	} 

	if (!get_option('addf_drpc_enable_table_border')) {
		update_option('addf_drpc_enable_table_border', 'yes');
	} 

	if (!get_option('addf_drpc_table_border_color')) {
		update_option('addf_drpc_table_border_color', '#CFCFCF');
	} 

	if (!get_option('addf_drpc_table_header_font_size') || '' == get_option('addf_drpc_table_header_font_size')) {
		update_option('addf_drpc_table_header_font_size', '18');
	} 

	if (!get_option('addf_drpc_table_row_font_size') || '' == get_option('addf_drpc_table_row_font_size')) {
		update_option('addf_drpc_table_row_font_size', '16');
	} 

	if (!get_option('addf_drpc_list_border_color')) {
		update_option('addf_drpc_list_border_color', '#95B0EE');
	} 

	if (!get_option('addf_drpc_list_background_color')) {
		update_option('addf_drpc_list_background_color', '#FFFFFF');
	}  

	if (!get_option('addf_drpc_list_text_color')) {
		update_option('addf_drpc_list_text_color', '#000000');
	}  

	if (!get_option('addf_drpc_selected_list_background_color')) {
		update_option('addf_drpc_selected_list_background_color', '#DFEBFF');
	}  
	
	if (!get_option('addf_drpc_selected_list_text_color')) {
		update_option('addf_drpc_selected_list_text_color', '#000000');
	} 

	if (!get_option('addf_drpc_card_border_color')) {
		update_option('addf_drpc_card_border_color', '#A3B39E');
	}  

	if (!get_option('addf_drpc_card_background_color')) {
		update_option('addf_drpc_card_background_color', '#FFFFFF');
	}  

	if (!get_option('addf_drpc_card_text_color')) {
		update_option('addf_drpc_card_text_color', '#000000');
	}  

	if (!get_option('addf_drpc_selected_card_border_color')) {
		update_option('addf_drpc_selected_card_border_color', '#27CA34');
	}  

	if (!get_option('addf_drpc_sale_tag_background_color')) {
		update_option('addf_drpc_sale_tag_background_color', '#FF0000');
	} 
	
	if (!get_option('addf_drpc_sale_tag_text_color')) {
		update_option('addf_drpc_sale_tag_text_color', '#FFFFFF');
	} 
}


function addf_drpc_get_all_categories() {

	$addf_drpc_product_categories = get_terms(
		'product_cat'
	);

	$addf_drpc_category_ids = array();

	if ( ! empty( $addf_drpc_product_categories ) && ! is_wp_error( $addf_drpc_product_categories ) ) {
		foreach ( $addf_drpc_product_categories as $category ) {
			$addf_drpc_category_id = $category->term_id;

			$addf_drpc_category_ids[] = $addf_drpc_category_id;
		}
	}

	return $addf_drpc_category_ids;
}
