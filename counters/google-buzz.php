<?php

/* BUZZ */

function social_counter__defaults__gbuzz( $defaults = array() ) {
	$defaults[] = 'gbuzz';
	return $defaults;
}
add_filter('social_counter__defaults', 'social_counter__defaults__gbuzz');


function social_counters__admin_list__gbuzz( $codeExit = '', $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {
		return $codeExit . '<div class="misc-pub-section">Google Buzz: ' . social_counter__get('gbuzz', $_post, false) . '</div>';
	}

	return $codeExit;

}
add_filter('social_counters__admin_list', 'social_counters__admin_list__gbuzz', 10, 2);


function social_counters__get__gbuzz( $codeExit = '', $postparam = false, $linked = true ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	$shared_counter = 0;

	if ( $_post ) {

		$share_counter_meta = get_post_meta($_post->ID, '_shared_counter_gbuzz_v2', true);

		if ( !is_single() && !empty($share_counter_meta) && $share_counter_meta !== false ) {

			$shared_counter = $share_counter_meta;

		}else{

			$post_url = apply_filters('social_counters_post_url_gbuzz', get_permalink($_post->ID), $_post);
			$post_url = urlencode($post_url);

			$url_request = 'http://www.google.com/buzz/api/buzzThis/buzzCounter?url={url}';
			$url_request = str_replace('{url}', $post_url, $url_request);

			$data = trim(wp_remote_retrieve_body(wp_remote_get($url_request, array('timeout' => 1,))));

			if ( !empty($data) ) {
				$data = str_replace('google_buzz_set_count({"http://', '', $data);
				$data = str_replace('});', '', $data);
				$data = explode(":", $data);

				$shared_counter = trim($data[1]);
			}

			if ( $share_counter_meta )
				$shared_counter = ( is_numeric($shared_counter) && $shared_counter >= $share_counter_meta ) ? $shared_counter : $share_counter_meta;

			update_post_meta($_post->ID, '_shared_counter_gbuzz_v2', $shared_counter);

		}
	}

	$shared_counter = ( empty($shared_counter) ) ? 0 : $shared_counter;

	$shared_counter = '<span>'.$shared_counter.'</span>';
	$shared_classes = 'social-counter social-counter-'. __('lang-dir', 'social-counters') .'-gbuzz';
	if ( $linked ) {
		$shared_url = social_counters__share_url__gbuzz($_post);
		$shared_counter = '<a class="'. $shared_classes .'" title="'. __('Share this in Google Buzz', 'social-counters') .'" href="'.$shared_url.'">'. $shared_counter .'</a>';
	}else{
		$shared_counter = '<span class="'. $shared_classes .'">'.$shared_counter.'</span>';
	}

	return $codeExit . $shared_counter;
}
add_filter('social_counter__get__gbuzz', 'social_counters__get__gbuzz', 10, 3);


function social_counters__share_url__gbuzz( $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {

		$post_title = urlencode( apply_filters('social_counters__the_title', get_the_title($_post->ID)) );
		$post_url = urlencode( apply_filters('social_counters_share_url_gbuzz', get_permalink($_post->ID), $_post) );

		$buzz_url = 'http://www.google.com/buzz/post';
			$buzz_url = add_query_arg('url', $post_url, $buzz_url);

		return $buzz_url;
	}

	return "#ERROR";
}
