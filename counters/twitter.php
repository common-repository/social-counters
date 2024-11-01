<?php

/* TWITTER */

function social_counter__defaults__twitter( $defaults = array() ) {
	$defaults[] = 'twitter';
	return $defaults;
}
add_filter('social_counter__defaults', 'social_counter__defaults__twitter');


function social_counters__admin_list__twitter( $codeExit = '', $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {
		return $codeExit . '<div class="misc-pub-section">Twitter: ' . social_counter__get('twitter', $_post, false) . '</div>';
	}

	return $codeExit;

}
add_filter('social_counters__admin_list', 'social_counters__admin_list__twitter', 10, 2);


function social_counter__get__twitter( $codeExit = '', $postparam = false, $linked = true ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	$shared_counter = 0;

	if ( $_post ) {

		$share_counter_meta = get_post_meta($_post->ID, '_shared_counter_twitter_v2', true);

		if ( !is_single() && !empty($share_counter_meta) && $share_counter_meta !== false ) {

			$shared_counter = $share_counter_meta;

		}else{

			$post_url = apply_filters('social_counters_post_url_twitter', get_permalink($_post->ID), $_post);
			$post_url = urlencode($post_url);

			// Search in TWEETMEME
			// $shared_counter = social_counters__twitter__tweetmeme($post_url, $shared_counter);
			// Search in TWITTER
			$shared_counter = social_counters__twitter__twitter($post_url, $shared_counter);

			if ( $share_counter_meta ) {
				$shared_counter = ( is_numeric($shared_counter) && $shared_counter >= $share_counter_meta ) ? $shared_counter : $share_counter_meta;
			}

			update_post_meta($_post->ID, '_shared_counter_twitter_v2', $shared_counter);
		}
	}

	$shared_counter = ( empty($shared_counter) ) ? 0 : $shared_counter;

	$shared_counter = '<span>'.$shared_counter.'</span>';
	$shared_classes = 'social-counter social-counter-'. __('lang-dir', 'social-counters') .'-twitter';
	if ( $linked ) {
		$shared_url = social_counters__share_url__twitter($_post);
		$shared_counter = '<a class="'. $shared_classes .'" title="'. __('Share this in Twitter', 'social-counters') .'" href="'.$shared_url.'">'. $shared_counter .'</a>';
	}else{
		$shared_counter = '<span class="'. $shared_classes .'">'.$shared_counter.'</span>';
	}

	return $codeExit . $shared_counter;
}
add_filter('social_counter__get__twitter', 'social_counter__get__twitter', 10, 3);


function social_counters__share_url__twitter( $postparam = false ) {
	global $post;

	$_post = ( $postparam ) ? $postparam : $post;

	if ( $_post ) {

		$pre_string = 'RT';
		$login_string = '@'. social_counters__twitter_user() .'';
		$url_string = apply_filters('social_counters_share_url_twitter', get_permalink($_post->ID), $_post);

		$post_title = apply_filters('social_counters__the_title', get_the_title($_post->ID));
		$post_title = substr($post_title, 0, 140 - ( 3 + ( strlen($pre_string) + strlen($url_string) + strlen($login_string) ) ));

		$twitter_url = 'http://twitter.com/intent/tweet';
			$twitter_url = add_query_arg('text', $pre_string . ' ' . $login_string . ' ' . $post_title . ' ' .$url_string, $twitter_url);

		return $twitter_url;
	}

	return "#ERROR";
}


function social_counters__twitter_user() {
	$twitter_user = '';

	if ( defined('SOCIAL_COUNTER__TWITTER_USER') )
		$twitter_user = SOCIAL_COUNTER__TWITTER_USER;

	if ( empty($twitter_user) && defined('SOCIAL_COUNTER_TWITTER_USER') )
		$twitter_user = SOCIAL_COUNTER_TWITTER_USER;

	return $twitter_user;
}


function social_counter__wp_head__twitter() {
	echo "\n". '<script type="text/javascript" src="'. social_counters__url() .'counters/twitter.js"></script>' ."\n";
}
add_action('social_counter__wp_head', 'social_counter__wp_head__twitter');




// Twitter Counter Systems!!

function social_counters__twitter__tweetmeme( $post_url = '', $shared_counter = 0 ) {

	if ( !empty($post_url) ) {

		$url_request_params = '?url={url}';
		$url_request_params = str_replace('{url}', $post_url, $url_request_params);

		$url_request = 'http://api.tweetmeme.com/url_info.php';
		$url_request .= $url_request_params;

		$data = wp_remote_retrieve_body(wp_remote_get($url_request, array('timeout' => 1)));

		$url_request_params = '';
		if ( !empty($data) ) {
			$data = unserialize($data);

			if ( isset($data['status']) && $data['status'] == 'failure' ) {
				$shared_counter = '<em title="'. $data['comment'] .'">0</em>';
			}else{
				$shared_counter = $data['story']['url_count'];
			}
		}

	}

	return $shared_counter;
}

function social_counters__twitter__twitter( $post_url = '', $shared_counter = 0 ) {

	if ( !empty($post_url) ) {

		$url_request_params = '?url={url}';
		$url_request_params = str_replace('{url}', $post_url, $url_request_params);

		$url_request = 'http://urls.api.twitter.com/1/urls/count.json';
		$url_request .= $url_request_params;

		$data = wp_remote_retrieve_body(wp_remote_get($url_request, array('timeout' => 1,)));

		$url_request_params = '';
		if ( !empty($data) && function_exists('json_decode') ) {
			$data = json_decode($data);

			if ( isset($data->count) && $data->count > $shared_counter )
				$shared_counter = $data->count;
		}

	}

	return $shared_counter;
}
