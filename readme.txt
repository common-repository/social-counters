=== Social Counters ===
Contributors: blogestudio,mortay
Tags: social, social bookmarking, counter, twitter, facebook, google, meneame, tuenti, bitacoras.com
Requires at least: 2.9
Tested up to: 4.4.1
Stable tag: 2.2.9

It allows to place social sharing links with counters (if available) to the most popular social networks: Menéame,Twitter,Facebook,...

== Description ==

It allows to place counters and social sharing links to the most popular social networks like Menéame, Twitter, Facebook, Google Buzz, Tuenti or Bitacoras.com.

It also has WordPress actions (add_action) and filters (add_filter) allowing to select the social sharing links we want to show.

The available functions are:

* `the_social_counters( $counters = array() )`: Displays selected social counters.
	* `$counters`: Array with list of social counters to view, by default `bitacoras,tuenti,google-buzz,meneame,twitter,facebook`

* `the_social_counters__get ( $counters = array() )`: Returns the selected social counters.
	* `$counters`: Array with list of social counters to view, by default `bitacoras,tuenti,google-buzz,meneame,twitter,facebook`

* `social_counter( $social_counter = '', $postparam = false, $linked = true )`: Displays a particular social counter
	* `$social_counter`: Name of social counter, by default the options are `bitacoras,tuenti,google-buzz,meneame,twitter,facebook`
	* `$postparam`: $post object to get the counter, by default system use global $post;
	* `$linked`: Simple option to de-activate link.

* `social_counter__get( $social_counter = '', $postparam = false, $linked = true )`: Returns a particular social counter
	* `$social_counter`: Name of social counter, by default the options are `bitacoras,tuenti,google-buzz,meneame,twitter,facebook`
	* `$postparam`: `$post` object to get the counter, by default system uses global `$post` var.
	* `$linked`: Simple option to de-activate link.


It's also possible to define two constants in 'wp-config.php'...

* SOCIAL_COUNTER__LOAD_CSS: If we define this constant as `false`, the plugin will not load the default style.
* SOCIAL_COUNTER__LOAD_CSS_SMALL: If we define this constanta as `true`, the plugin will load de small icons.
* SOCIAL_COUNTER__TWITTER_USER: The twitter sharing link includes this username with the RT link.


== Installation ==

1. Upload directory `social-counter` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place code `<?php the_social_counters() ?>` in Loop (home, single,...) in your theme

== Screenshots ==

1. Default view in a single post

== Changelog ==

= 2.2.9 =
* Reduced requests timeout to 1 second.
* Various modifications.

= 2.2.8 =
* Changed cache system to increase page load speed.

= 2.2.7 =
* Re-Tagged last version.

= 2.2.6 =
* Repaired error in function called (plugin_dir_url)!! Sorry!!
		
= 2.2.5 =
* Changed plugin version in README!!

= 2.2.4 =
* Repaired error with URLs from plugin and Domain Mapping.
* Repaired error with WPML and language load order.

= 2.2.3 =
* Google Buzz counter deleted.

= 2.2.2 =
* Repaired malfunction in Twitter counter

= 2.2.1 =
* Updated CSS Version

= 2.2.0 =
* Added counter from "Bitcoras"
* Added counter from "LinkedIn"
* Added SOCIAL_COUNTER__LOAD_CSS_SMALL constant to load only icons, not share text.

= 2.1.1 =
* Changed counter system on Twitter, now used Twitter itself, not Tweetmeme
* Fixed Facebook counter, now uses the Graph API.

= 2.1.0 =
* Added param to send post object to functions "the_" (postparam).
* Changed system of "share" in Twitter

= 2.0.1 =
* Solved error with CONSTANT `SOCIAL_COUNTER__LOAD_CSS`
		
= 2.0.0 =
* Reprogramming of the plugin to work with WordPress actions and filters.

= 1.2.0 =
* Counter system changed from Twitter to Tweetmeme.
* Added function "social_counter__twitter__login_string" to change the user ReTweet.

= 1.1.0 =
* Fixed bug with cache counter.
* Fixed connection error on Menéame.

= 1.0 =
* First Version
