<?php

/* LINKEDIN */

function social_counter__defaults__linkedin( $defaults = array() ) {
	$defaults[] = 'linkedin';
	return $defaults;
}
add_filter('social_counter__defaults', 'social_counter__defaults__linkedin');


function social_counters__admin_list__linkedin( $codeExit = '', $postparam = false ) {
	global $post;
	
	$_post = ( $postparam ) ? $postparam : $post;
	
	if ( $_post ) {
		return $codeExit . '<div class="misc-pub-section">LinkedIn: ' . social_counter__get('linkedin', $_post, false) . '</div>';
	}
	
	return $codeExit;

}
add_filter('social_counters__admin_list', 'social_counters__admin_list__linkedin', 10, 2);


function social_counter__get__linkedin( $codeExit = '', $postparam = false, $linked = true ) {
	global $post;
	
	$_post = ( $postparam ) ? $postparam : $post;
	
	$shared_counter = 0;
	
	if ( $_post ) {
		
		$share_counter_meta = get_post_meta($_post->ID, '_shared_counter_linkedin_v2', true);
		
		if ( !is_single() && !empty($share_counter_meta) && $share_counter_meta !== false ) {
			
			$shared_counter = $share_counter_meta;
			
		}else{
			
			$post_url = apply_filters('social_counters_post_url_linkedin', get_permalink($_post->ID), $_post);
			$post_url = urlencode($post_url);
			
			$shared_counter = social_counters__linkedin__get_counter($post_url, $shared_counter);
			
			if ( $share_counter_meta ) {
				$shared_counter = ( is_numeric($shared_counter) && $shared_counter >= $share_counter_meta ) ? $shared_counter : $share_counter_meta;
			}
			
			update_post_meta($_post->ID, '_shared_counter_linkedin_v2', $shared_counter);
		}
	}
			
	$shared_counter = ( empty($shared_counter) ) ? 0 : $shared_counter;
	
	$shared_counter = '<span>'.$shared_counter.'</span>';
	$shared_classes = 'social-counter social-counter-'. __('lang-dir', 'social-counters') .'-linkedin';
	if ( $linked ) {
		$shared_url = social_counters__share_url__linkedin($_post);
		$shared_counter = '<a class="'. $shared_classes .'" title="'. __('Share this in LinkedIn', 'social-counters') .'" href="'.$shared_url.'">'. $shared_counter .'</a>';
	}else{
		$shared_counter = '<span class="'. $shared_classes .'">'.$shared_counter.'</span>';
	}
	
	return $codeExit . $shared_counter;
}
add_filter('social_counter__get__linkedin', 'social_counter__get__linkedin', 10, 3);


function social_counters__share_url__linkedin( $postparam = false ) {
	global $post;
	
	$_post = ( $postparam ) ? $postparam : $post;
	
	if ( $_post ) {
		
		$url_string = apply_filters('social_counters_share_url_linkedin', get_permalink($_post->ID), $_post);
		
		$linkedin_url = 'http://www.linkedin.com/cws/share';
			$linkedin_url = add_query_arg('url', $url_string, $linkedin_url);
			$linkedin_url = add_query_arg('original_referer', get_bloginfo('url'), $linkedin_url);
		
		return $linkedin_url;
	}
	
	return "#ERROR";
}


function social_counter__wp_head__linkedin() {
	echo "\n". '<script type="text/javascript" src="'. social_counters__url() .'counters/linkedin.js"></script>' ."\n";
}
add_action('social_counter__wp_head', 'social_counter__wp_head__linkedin');


function social_counters__linkedin__get_counter( $post_url = '', $shared_counter = 0 ) {
	
	if ( !empty($post_url) ) {
		
		$url_request_params = '?url={url}';
		$url_request_params = str_replace('{url}', $post_url, $url_request_params);
		
		$url_request = 'http://www.linkedin.com/countserv/count/share';
		$url_request .= $url_request_params;
		
		$data = wp_remote_retrieve_body(wp_remote_get($url_request, array('timeout' => 1,)));
		
		$url_request_params = '';
		if ( !empty($data) ) {
			$data = preg_replace("/IN\.Tags\.Share\.handleCount\((.*)\);/", "\\1", $data);
			
			$data = json_decode($data);
			
			if ( isset($data->count) )
				$shared_counter = $data->count;
		}
		
	}
	
	return $shared_counter;
}
