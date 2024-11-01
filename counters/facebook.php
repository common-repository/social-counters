<?php

/* FACEBOOK */

function social_counter__defaults__facebook( $defaults = array() ) {
	$defaults[] = 'facebook';
	return $defaults;
}
add_filter('social_counter__defaults', 'social_counter__defaults__facebook');


function social_counters__admin_list__facebook( $codeExit = '', $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {
		return $codeExit . '<div class="misc-pub-section">Facebook: ' . social_counter__get('facebook', $_post, false) . '</div>';
	}

	return $codeExit;

}
add_filter('social_counters__admin_list', 'social_counters__admin_list__facebook', 10, 2);


function social_counter__get__facebook( $codeExit = '', $postparam = false, $linked = true ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	$shared_counter = 0;

	if ( $_post ) {

		$share_counter_meta = get_post_meta($_post->ID, '_shared_counter_facebook_v2', true);

		if ( !is_single() && !empty($share_counter_meta) && $share_counter_meta !== false ) {

			$shared_counter = $share_counter_meta;

		}else{

			if ( function_exists('json_decode') ) {
				$post_url = apply_filters('social_counters_post_url_facebook', get_permalink($_post->ID), $_post);

				//$url_request = 'http://api.facebook.com/restserver.php?format=json&method=links.getStats&urls={url}';
				$url_request = 'http://graph.facebook.com/';
					$url_request = add_query_arg('id', $post_url, $url_request);

				$data = wp_remote_retrieve_body(wp_remote_get($url_request, array('timeout' => 1,)));

				if ( !empty($data) ) {
					$data = json_decode($data);
					if ( isset($data->shares) )
						$shared_counter = $data->shares;
				}
			}

			if ( $share_counter_meta )
				$shared_counter = ( is_numeric($shared_counter) && $shared_counter >= $share_counter_meta ) ? $shared_counter : $share_counter_meta;

			update_post_meta($_post->ID, '_shared_counter_facebook_v2', $shared_counter);

		}
	}

	$shared_counter = ( empty($shared_counter) ) ? 0 : $shared_counter;

	$shared_counter = '<span>'.$shared_counter.'</span>';
	$shared_classes = 'social-counter social-counter-'. __('lang-dir', 'social-counters') .'-facebook';
	if ( $linked ) {
		$shared_url = social_counters__share_url__facebook($_post);
		$shared_counter = '<a class="'. $shared_classes .'" title="'. __('Share this in Facebook', 'social-counters') .'" href="'.$shared_url.'">'. $shared_counter .'</a>';
	}else{
		$shared_counter = '<span class="'. $shared_classes .'">'.$shared_counter.'</span>';
	}

	return $codeExit . $shared_counter;
}
add_filter('social_counter__get__facebook', 'social_counter__get__facebook', 10, 3);


function social_counters__share_url__facebook( $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {

		$post_title = urlencode( apply_filters('social_counters__the_title', get_the_title($_post->ID)) );
		$url_string = urlencode( apply_filters('social_counters_share_url_facebook', get_permalink($_post->ID), $_post) );

		$facebook_url = 'http://www.facebook.com/sharer.php?u=' . $url_string . '&amp;t=' . $post_title .'';

		return $facebook_url;
	}

	return "#ERROR";
}
