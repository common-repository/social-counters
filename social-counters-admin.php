<?php

/* Admin Info */
function social_counters__manage_edit_columns( $columns ) {
	
	$columns['social_counters'] = apply_filters('social_counters__manage_edit_columns', ''. __('Social Counters', 'social-counters') .'');
	
	return $columns;
}
add_filter('manage_edit_columns', 'social_counters__manage_edit_columns');


function social_counters__manage_posts_custom_column( $column_name, $post_ID ) {
	
	if ( $post_ID ) {
		switch ( $column_name ) {
			case "social_counters":
				$social_counters__admin_list = apply_filters('social_counters__admin_list', '', get_post($post_ID));
				echo ( !empty( $social_counters__admin_list ) ) ? $social_counters__admin_list : '&nbsp;';
				break;
		}
	}
	
}
add_action('manage_posts_custom_column', 'social_counters__manage_posts_custom_column', 10, 2);



function social_counters__post_submitbox_misc_actions( $postparam = false ) {
	global $post;
	
	$_post = ( $postparam ) ? $postparam : $post;
	
	if ( $_post ) {
		echo apply_filters('social_counters__admin_list', '', $_post);
	}
}
add_action('post_submitbox_misc_actions', 'social_counters__post_submitbox_misc_actions');
