<?php
// MicroAudio Admin Page
require_once('microAudio.options.php');
$options = microAudioOptions::getInstance();
if(!isset($options)) die( "There was an error with the &micro;Audio options. Re-installing may fix this.");

// Business Logic
if (isset($_POST['action'])) {
	if (!isset($_POST['_wpnonce'])) die("There was a problem authenticating. Please log out and log back in");
	if (!check_admin_referer('ma-update_options')) die("There was a problem authenticating. Please log out and log back in");
	if ($_POST['action'] == 'update') {
		(isset($_POST['ma_include_jquery'])) ? $options->include_jquery = true : $options->include_jquery = false;
		(isset($_POST['ma_autostart'])) ? $options->autostart = true : $options->autostart = false;
		$options->autoconfig = $_POST['ma_autoconfig'];
		(isset($_POST['ma_download'])) ? $options->download = true : $options->download = false;
		(isset($_POST['ma_enable_widget'])) ? $options->enable_widget = true : $options->enable_widget = false;
		(isset($_POST['ma_debug'])) ? $options->debug = true : $options->debug = false;
		(isset($_POST['ma_clear_errors'])) ? $options->clear_errors() : $a;
	}
	if( isset($_GET['build']) || isset($_POST['build']) ) {
		require_once('microAudio.jsbuilder.php');
		?><div class="updated"><p><strong>Files Built</strong></p></div><?php
	}			
	?><div class="updated"><p><strong>Options Updated</strong></p></div><?php
}


?>
<div class="wrap">
	<h2>&micro;Audio Management Page</h2>
    <p>In most cases the way it's configured out of the box is just about right, but feel free to play with it.</p>
    <p>&micro;Audio Version: <em><?php echo get_option('ma_version'); ?></em></p>
    <form id="ma_options" name="ma_options" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
    <table class="form-table">
    	<tbody>
        	<tr valign="top">
            	<th scope="row">jQuery</th>
                <td>
                	<input type="checkbox" name="ma_include_jquery" id="ma_include_jquery" <?php if ($options->include_jquery) echo "checked"; ?> />
                    <label for="ma_include_jquery">Include jQuery on the display pages</label>
                    <br />
                    <span call="explanatory-text">From 0.6 &micro;Audio requires jQuery 1.3+. DO NOT uncheck this unless you are 100% sure that you have jQuery 1.3 loaded.</span>
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">Autostart</th>
                <td>
                	<input type="checkbox" name="ma_autostart" id="ma_autostart" <?php if ($options->autostart) echo "checked"; ?> />
                    <label for="ma_autostart">Autostart the player</label>
                    <br />
                    <span call="explanatory-text">Causes the player to start playin immediately on load if enabled.</span>                	
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">Widget</th>
                <td>
                	<input type="checkbox" name="ma_enable_widget" id="ma_enable_widget" <?php if ($options->enable_widget) echo "checked"; ?> />
                    <label for="ma_enable_widget">Enable Sidebar Widget</label>
                    <br />
                    <span class="explanatory-text">NOTE: Increases the javascript size</span>
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">Configuration</th>
                <td>
                	<input type="radio" name="ma_autoconfig" id="ma_autoconfig_none" value="false" <?php if ($options->autoconfig == 'false') echo "checked";?> />
                    <label for="ma_autoconfig">Player wears it's default skin</label>
					<br />
                	<input type="radio" name="ma_autoconfig" id="ma_autoconfig_true" value="true" <?php if ($options->autoconfig == 'true') echo "checked";?> />
                    <label for="ma_autoconfig">Player configures based on the css already present on the page</label>
					<br />
                	<input type="radio" name="ma_autoconfig" id="ma_autoconfig_magic" value="magic" <?php if ($options->autoconfig == 'magic') echo "checked";?> />
                    <label for="ma_autoconfig">Player expects css propertied as outlined in microAudio.example.css</label>
                    <br />
                    <span class="explanatory-text">How to color the player. If the last option is used, then you <em><b>must</b></em> have the css classes in microAudio.example.css somewhere in your css. NOTE: "default skin" produces the smallest and fastest javascript.</span>                	
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">Download Link</th>
                <td>
                	<input type="checkbox" name="ma_download" id="ma_download" <?php if ($options->download) echo "checked"; ?> />
                    <label for="ma_download">Include a static download link</label>
                    <br />
                    <span class="explanatory-text">Whether to include a link next to the flash player to download the file. Marginally increases the javascript size.</span>                	
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">Debug</th>
                <td>
                	<input type="checkbox" name="ma_debug" id="ma_debug" <?php if ($options->debug) echo "checked"; ?> />
                    <label for="ma_debug">Include dedugging information</label>
                    <br />
                    <span class="explanatory-text">Whether to include debugging information in the code. Significantly increases the code size and runtime.</span>                	
                </td>
            </tr>  
        </tbody>
    </table>
    <p class="submit">
    	<input type="hidden" name="action" value="update" />
    	<input type="hidden" name="build" value="true" />
        <?php wp_nonce_field('ma-update_options'); ?>
    	<input type="submit" name="Submit" value="Configureate >" class="button" />
    </p>
    <?php if ($options->hasErrors()) : ?>
    <h3>&micro;Audio Errors</h3>
    <p>&micro;Audio has encountered some errors in the course of execution. Please include these errors in any bug reports.</p>
    <table class="form-table">
    	<tbody>
            <tr valign="top">
            	<th scope="row">Clear Error Log</th>
                <td>
                	<input type="checkbox" name="ma_clear_errors" id="ma_clear_errors" />
                    <label for="ma_clear_errors">Clear Error Logs</label>
                    <br />
                    <span class="explanatory-text">Checking this will cause the error log to be deleted.</span>                	
                </td>
            </tr>  
        </tbody>
    </table>
    <table>
    	<thead>
    	<tr>
    		<th>Date / Time</th>
    		<th>Error Message</th>
    		<th>Error Level</th>
    	</tr>
    	</thead>
    	<tbody style="padding: 10px;">
    		<?php foreach($options->errors() as $error) : ?>
    		<tr>
    			<td><?php echo date('c', $error->date); ?></td>
    			<td><?php echo $error->error; ?></td>
    			<td><?php echo $error->level; ?></td>
    		</tr>
    		<?php endforeach; ?>
    	</tbody>
    </table>
    <?php endif; ?>
	</form>
</div>
<?php
?>