<?php

/* MENEAME */

function social_counter__defaults__meneame( $defaults = array() ) {
	$defaults[] = 'meneame';
	return $defaults;
}
add_filter('social_counter__defaults', 'social_counter__defaults__meneame');


function social_counters__admin_list__meneame( $codeExit = '', $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {
		return $codeExit . '<div class="misc-pub-section">Meneame: ' . social_counter__get('meneame', $_post, false) . '</div>';
	}

	return $codeExit;

}
add_filter('social_counters__admin_list', 'social_counters__admin_list__meneame', 10, 2);


function social_counter__get__meneame( $codeExit = '', $postparam = false, $linked = true ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	$shared_counter = 0;

	if ( $_post ) {

		$share_counter_meta = get_post_meta($_post->ID, '_shared_counter_meneame_v2', true);

		if ( !is_single() && !empty($share_counter_meta) && $share_counter_meta !== false ) {

			$shared_counter = $share_counter_meta;

		}else{

			$post_url = apply_filters('social_counters_post_url_meneame', get_permalink($_post->ID), $_post);
			$post_url = $post_url; //urlencode($post_url);

			$url_request = 'http://meneame.net/api/url.php?all=1&url={url}';
			$url_request = str_replace('{url}', $post_url, $url_request);

			$data = trim(wp_remote_retrieve_body(wp_remote_get($url_request, array('timeout' => 1,))));

			if ( !empty($data) ) {
				$data_lines = explode("\n", trim($data));

				if ( sizeof($data_lines) >= 1 ) {

					foreach ( $data_lines as $data_line ) {
						$line = explode(" ", trim($data_line));
						if ( $line[0] == 'OK' && in_array( $line[3], array('published','queued') ) ) {
							$shared_counter += $line[2];
						}
					}

				}
			}

			if ( $share_counter_meta )
				$shared_counter = ( is_numeric($shared_counter) && $shared_counter >= $share_counter_meta ) ? $shared_counter : $share_counter_meta;

			update_post_meta($_post->ID, '_shared_counter_meneame_v2', $shared_counter);

		}
	}

	$shared_counter = ( empty($shared_counter) ) ? 0 : $shared_counter;

	$shared_counter = '<span>'.$shared_counter.'</span>';
	$shared_classes = 'social-counter social-counter-'. __('lang-dir', 'social-counters') .'-meneame';
	if ( $linked ) {
		$shared_url = ( trim(strip_tags($shared_counter)) == 0 ) ? social_counters__share_url__meneame($_post) : social_counters__vote_url__meneame($_post);
		$shared_counter = '<a class="'. $shared_classes .'" title="'. __('Share this in Menéame', 'social-counters') .'" href="'.$shared_url.'">'. $shared_counter .'</a>';
	}else{
		$shared_counter = '<span class="'. $shared_classes .'">'.$shared_counter.'</span>';
	}

	return $codeExit . $shared_counter;
}
add_filter('social_counter__get__meneame', 'social_counter__get__meneame', 10, 3);


function social_counters__share_url__meneame( $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {
		$post_title = urlencode( apply_filters('social_counters__the_title', get_the_title($_post->ID)) );
		$url_string = urlencode( apply_filters('social_counters_share_url_meneame', get_permalink($_post->ID), $_post) );

		$meneame_url = 'http://meneame.net/submit.php?url=' . $url_string . '&amp;title=' . $post_title .'';

		return $meneame_url;
	}

	return "#ERROR";
}

function social_counters__vote_url__meneame( $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	$share_vote_url = '#';

	if ( $_post ) {

		$share_vote_url_meta = get_post_meta($_post->ID, '_shared_vote_url_meneame', true);

		if ( !is_single() && $share_vote_url_meta ) {

			$share_vote_url = $share_vote_url_meta;

		}else{

			$post_url = apply_filters('social_counters_post_url_meneame', get_permalink($_post->ID), $_post);
			$post_url = $post_url; //urlencode($post_url);

			$url_request = 'http://meneame.net/api/url.php?url={url}';
			$url_request = str_replace('{url}', $post_url, $url_request);

			$data = trim(wp_remote_retrieve_body(wp_remote_get($url_request, array('timeout' => 1,))));

			if ( !empty($data) ) {
				$data_lines = explode("\n", trim($data));

				if ( sizeof($data_lines) >= 1 ) {

					foreach ( $data_lines as $data_line ) {
						$line = explode(" ", trim($data_line));
						if ( $line[0] == 'OK' && in_array( $line[3], array('published','queued') ) ) {
							$share_vote_url = $line[1];
							break;
						}
					}

				}
			}

			update_post_meta($_post->ID, '_shared_vote_url_meneame', $share_vote_url);

		}

		return $share_vote_url;
	}

	return "#ERROR";
}
