<?php
// The uAudio widgets

/*********************************************************************
 * New, 2.8+ widget
 */
class microAudioWidget extends WP_Widget {

	function microAudioWidget() {
		$widget_ops = array(
						'classname' => 'widget_micro_audio', 
						'description' => 'The &micro;Audio Widget' 
						);
		$this->WP_Widget('micro_audio', '&micro;Audio', $widget_ops);
		
	}
 
	function widget($args, $instance) {
		extract($args);
		echo $before_widget;
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		if (!empty($title)) echo $before_title; echo $title; echo $after_title;
		ma_widget_body();
		echo $after_widget;		
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
 
		return $instance;
		
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags($instance['title']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		<?php 
	}
	
}


/*********************************************************************
 * Legacy, pre wordpress 2.8 widget
 */

// Create the widget
function ma_widget($args) {
  	$options = microAudioOptions::getInstance();
  	extract($args);
  	echo $before_widget;
  	echo $before_title; echo $options->widget_title; echo $after_title;
  	ma_widget_body();	
  	echo $after_widget;
}

// Widget body
function ma_widget_body() {
?>
<p id="microAudio-widget-container">
	<noscript>
	Sorry, it appears that you have javascript disabled or that your browse does not support javascript. Please enable javascript and reload the page.
	</noscript>
</p><?php
}

// Widget Control
function ma_widget_control() {
	$options = microAudioOptions::getInstance();
	if (isset($_POST['ma_widget_options_updated'])) {
		if (wp_verify_nonce($_POST['ma_widget_options_nonce'],'ma-update_widget-options')) {
			if (isset($_POST['ma_widget_title'])) $options->widget_title = $_POST['ma_widget_title'];
		}
	}
?><p>
	<label for="ma_widget_title">
    	Title:
        <input type="text" name="ma_widget_title" id="ma_widget_title" value="<?php echo $options->widget_title; ?>" />
	</label>
    <input type="hidden" name="ma_widget_options_updated" value="true" />
    <input type="hidden" name="ma_widget_options_nonce" value="<?php echo wp_create_nonce('ma-update_widget-options'); ?>" />
</p><?php 
}