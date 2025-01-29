<?php

function custom_pvbur_array_filter( $custom_arr ) {
$custom_arr = array_filter($custom_arr, function ( $current_value, $current_key ) {

		return ( '' !== $current_value  && '' !== $current_key ); 
	}, ARRAY_FILTER_USE_BOTH);

return $custom_arr;
}
