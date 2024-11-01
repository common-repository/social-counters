<?php

/* BITACORAS */

function social_counter__defaults__bitacoras( $defaults = array() ) {

	$key = social_counter__bitacoras_key();
	if ( !empty($key) )
		$defaults[] = 'bitacoras';

	return $defaults;
}
add_filter('social_counter__defaults', 'social_counter__defaults__bitacoras');


function social_counters__admin_list__bitacoras( $codeExit = '', $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	$key = social_counter__bitacoras_key();
	if ( $_post && !empty($key) ) {
		return $codeExit . '<div class="misc-pub-section">Bitacoras: ' . social_counter__get('bitacoras', $_post, false) . '</div>';
	}

	return $codeExit;

}
add_filter('social_counters__admin_list', 'social_counters__admin_list__bitacoras', 10, 2);


function social_counter__get__bitacoras( $codeExit = '', $postparam = false, $linked = true ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	$shared_counter = 0;

	$key = social_counter__bitacoras_key();
	if ( $_post && !empty($key) ) {

		$share_counter_meta = get_post_meta($_post->ID, '_shared_counter_bitacoras_v2', true);

		if ( !is_single() && !empty($share_counter_meta) && $share_counter_meta !== false ) {

			$shared_counter = $share_counter_meta;

		}else{

			if ( function_exists('json_decode') ) {
				$post_url = apply_filters('social_counters_post_url_bitacoras', get_permalink($_post->ID), $_post);
				$post_url = urlencode($post_url);

				$url_request = 'http://api.bitacoras.com/anotacion/key/'. social_counter__bitacoras_key() .'/format/'. 'json' .'/url/{url}/';
				$url_request = str_replace('{url}', $post_url, $url_request);

				$data = wp_remote_retrieve_body(wp_remote_get($url_request, array('timeout' => 1,)));

				if ( !empty($data) ) {
					$data = json_decode($data);

					if ( $data->status == 'success' ) {
						$shared_counter = $data->data->votos;
					}
				}
			}

			update_post_meta($_post->ID, '_shared_counter_bitacoras_v2', $shared_counter);

		}
	}

	$shared_counter = ( empty($shared_counter) ) ? 0 : $shared_counter;

	$shared_counter = '<span>'.$shared_counter.'</span>';
	$shared_classes = 'social-counter social-counter-'. __('lang-dir', 'social-counters') .'-bitacoras';
	if ( $linked ) {
		$shared_url = social_counters__share_url__bitacoras($_post);
		$shared_counter = '<a class="'. $shared_classes .'" title="'. __('Share this in Bitacoras.com', 'social-counters') .'" href="'.$shared_url.'">'. $shared_counter .'</a>';
	}else{
		$shared_counter = '<span class="'. $shared_classes .'">'.$shared_counter.'</span>';
	}

	return $codeExit . $shared_counter;
}
add_filter('social_counter__get__bitacoras', 'social_counter__get__bitacoras', 10, 3);


function social_counters__share_url__bitacoras( $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	$key = social_counter__bitacoras_key();
	if ( $_post && !empty($key) ) {

		$post_title = urlencode( apply_filters('social_counters__the_title', get_the_title($_post->ID)) );
		$url_string = urlencode( apply_filters('social_counters_share_url_bitacoras', get_permalink($_post->ID), $_post) );

		$bitacoras_url = 'http://bitacoras.com/anotaciones/' . $url_string . '/';

		return $bitacoras_url;
	}

	return "#ERROR";
}


function social_counter__bitacoras_key() {
	$bitacoras_key = '';

	if ( defined('SOCIAL_COUNTER__BITACORAS_KEY') )
		$bitacoras_key = SOCIAL_COUNTER__BITACORAS_KEY;

	return $bitacoras_key;
}
