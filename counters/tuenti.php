<?php

/* TUENTI */

function social_counter__defaults__tuenti( $defaults = array() ) {
	$defaults[] = 'tuenti';
	return $defaults;
}
add_filter('social_counter__defaults', 'social_counter__defaults__tuenti');


function social_counters__admin_list__tuenti( $codeExit = '', $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {
		return $codeExit . '<div class="misc-pub-section">Tuenti: ' . social_counter__get('tuenti', $_post, false) . '</div>';
	}

	return $codeExit;

}
// add_filter('social_counters__admin_list', 'social_counters__admin_list__tuenti', 10, 2);


function social_counter__get__tuenti( $codeExit = '', $postparam = false, $linked = true ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	$shared_counter = ''; // <span>'.$shared_counter.'</span>';
	$shared_classes = 'social-counter social-counter-'. __('lang-dir', 'social-counters') .'-tuenti';
	if ( $linked ) {
		$shared_url = social_counters__share_url__tuenti($_post);
		$shared_counter = '<a class="'. $shared_classes .'" title="'. __('Share this in Tuenti', 'social-counters') .'" href="'.$shared_url.'">'. $shared_counter .'</a>';
	}else{
		$shared_counter = '<span class="'. $shared_classes .'">'.$shared_counter.'</span>';
	}

	return $codeExit . $shared_counter;
}
add_filter('social_counter__get__tuenti', 'social_counter__get__tuenti', 10, 3);


function social_counters__share_url__tuenti( $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {

		$url_string = apply_filters('social_counters_share_url_tuenti', get_permalink($_post->ID), $_post);

		$post_title = apply_filters('social_counters__the_title', get_the_title($_post->ID));
		$post_title = substr($post_title, 0, 140 - ( 3 + ( strlen($pre_string) + strlen($url_string) + strlen($login_string) ) ));

		$service_url = 'http://www.tuenti.com/share';
			$service_url = add_query_arg('url', $url_string, $service_url);

		return $service_url;
	}

	return "#ERROR";
}
