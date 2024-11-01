<?
/*
    Plugin Name: Twitter OEmbed Language fix
    Plugin URI: http://etfovac.com
    Description: This plugin fixes a bug in embedded tweets. Embedded tweets are displayed in language based on server location.
    Author: Sibin Grasic
    Version: 1.0
    Author URI: http://etfovac.com
    License: GPL2

	Copyright 2011  Sibin Grasic  (email : sibin.grasic@coderesidence.com)
	
	Uses portions of code released under GPL2
	by Jennifer M. Dodd (email: jmdodd@gmail.com) and Tom J Nowell ( contact@tomjn.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/

global $wp_version;


if (version_compare ($wp_version, '3.4', 'lt'))
{
	require_once ABSPATH.'/wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
	wp_die( 'Twitter OEmbed fix requiers WordPress 3.4 and higher. The plugin has now disabled itself.' );
}
if (function_exists('ucc_oembed_twitter_lang'))
{
	require_once ABSPATH.'/wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
	wp_die( 'You are already using another version of Twitter OEmbed fix. The plugin has now disabled itself.');
}
else
{
	
 
function ucc_oembed_twitter_lang( $provider, $url, $args )
{
	if ( stripos( $url, 'twitter.com' ) )
	{
		if ( defined( 'WPLANG' ) )
			$lang = strtolower( WPLANG );
		if ( empty( $lang ) )
			$lang = 'en';
			
		$args['lang'] = $lang;
		$provider = add_query_arg( 'lang', urlencode( $lang ), $provider );
	}
	return $provider;
}

 function twitter_oembed($a) 
 {
	if ( defined( 'WPLANG' ) )
		$lang = strtolower( WPLANG );
	if ( empty( $lang ) )
		$lang = 'en';
	$a['#https?://(www\.)?twitter.com/.+?/status(es)?/.*#i'] = array( 'http://api.twitter.com/1/statuses/oembed.{format}?lang='.$lang, true  );
	return $a;
}

 
if ( version_compare( $wp_version, '3.5', 'ge' ) )
{
	add_filter( 'oembed_fetch_url', 'ucc_oembed_twitter_lang', 10, 3 );
}
else
{
	// The above filter was introduced in v3.5, for 3.4 replace the existing OEmbed line with a new one	
	add_filter('oembed_providers','twitter_oembed');
}
}