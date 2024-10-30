<?php
/*
Plugin Name: &micro;Audio Player
Plugin URI: http://compu.terlicio.us/code/plugins/audio/
Description: Converts links to mp3 files to a small flash player and a link to the raw file.
Version: 0.7 Development
Author: Christopher O'Connell
Author URI: http://compu.terlicio.us/
****************************************************
This software is licensed under the creative commons 2.0 license, available at http://creativecommons.org/licenses/by-nc-sa/2.0/.
*/
?>
<?php

$MICROAUDIO_VERSION = "0.7 Dev 4";

// Include classes
require_once('microAudio.options.php');
require_once('microAudio.widget.php');
$options = microAudioOptions::getInstance();

// Hook to add scripts
add_action('admin_menu','ma_add_pages');
add_action('wp_head','ma_head');
//add_action('plugins_loaded', 'ma_init');
add_action('widgets_init','ma_init');

// See if we need to install/update
if (get_option('ma_version') != $MICROAUDIO_VERSION) ma_setup($MICROAUDIO_VERSION);

// PluginsLoaded Init
function ma_init() {
	$options = microAudioOptions::getInstance();
	if ($options->enable_widget == 'true') {
		
		if(get_option('db_version') > 10000) { // Wordpress 2.8+? 
			register_widget('microAudioWidget');
		} else {
			register_sidebar_widget('&micro;Audio', 'ma_widget');
			register_widget_control('&micro;Audio', 'ma_widget_control', 300, 200 );
		}     
	}
}

// Add the script
function ma_add_pages() {
	// Add a new submenu under options
	add_options_page('&micro;Audio','&micro;Audio',6,'microaudio','ma_manage_page');
}

// Add the page header
function ma_head() {
	$options = microAudioOptions::getInstance();
	$ma_url = get_option('siteurl');
	echo "<script type='text/javascript' src='$ma_url/wp-content/plugins/microaudio/js/microAudio.init-$options->key.js' ></script>\n";
}

// Management Page
function ma_manage_page() {
	include_once('microAudio.admin.php');
}

// 

// Setup Function
function ma_setup($MICROAUDIO_VERSION) {
	
	$options = microAudioOptions::getInstance();
	
	$options->log_message("Performing setup from ".get_option('ma_version')." to $MICROAUDIO_VERSION.");
	
	// Remove the old options and create the new options object
	if (substr($MICROAUDIO_VERSION,0,3) < 0.7) {
		global $wpdb;

		$old_options = array(
						'ma_autostart',
						'ma_autoconfig',
						'ma_enable_widget',
						'ma_widget_title',
						'ma_include_jquery',
						'ma_download',
						'widget_title'
						);

		global $wpdb;
		foreach($old_options as $option) {
			$new_option = str_replace('ma_','',$option);
			$options->$new_option = get_option($option);
			$sql = "DELETE FROM $wpdb->options WHERE `option_name` = '$option' LIMIT 1";
			$wpdb->query($sql);
		}
	}
	
	update_option('ma_version',$MICROAUDIO_VERSION);
}
?>