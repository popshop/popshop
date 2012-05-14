<?php


// @see http://wplift.com/notify-your-theme-users-about-updates-in-their-dashboard
// Also @see http://github.com/unisphere/unisphere_notifier



// @todo: Switch to Theme Updater, which does auto-update
// https://github.com/UCF/Theme-Updater
// http://wordpress.org/extend/plugins/theme-updater/
// http://www.disruptiveconversations.com/2012/02/how-to-auto-update-wordpress-custom-themes-using-github.html

// Also @see http://wordpress.stackexchange.com/questions/47019/is-the-current-theme-version-number-cached-somewhere


function update_notifier_menu() {
	$xml = get_latest_theme_version(21600); // This tells the function to cache the remote call for 21600 seconds (6 hours)
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Get theme data from style.css (current version is what we want)
    
    // @Popshop:
    if (isset($xml->latest)) {
    	if (version_compare($theme_data['Version'], $xml->latest) == -1) {
    		add_dashboard_page( $theme_data['Name'] . ' Theme Updates', $theme_data['Name'] . '<span class="update-plugins count-1"><span class="update-count">1</span></span>', 'administrator', strtolower($theme_data['Name']) . '-updates', 'update_notifier');
    	}
    }
}

add_action('admin_menu', 'update_notifier_menu');

function update_notifier() {
	$xml = get_latest_theme_version(21600); // This tells the function to cache the remote call for 21600 seconds (6 hours)
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Get theme data from style.css (current version is what we want) 
	?>

	<style>
		.update-nag {display: none;}
		#instructions {max-width: 800px;}
		h3.title {margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd;}
	</style>

	<div class="wrap">

		<div id="icon-tools" class="icon32"></div>
		<h2><?php echo $theme_data['Name']; ?> Theme Updates</h2>
	    <div id="message" class="updated below-h2"><p><strong>There is a new version of the <?php echo $theme_data['Name']; ?> theme available.</strong> You have version <?php echo $theme_data['Version']; ?> installed. Update to version <?php echo $xml->latest; ?>.</p></div>

        <img style="float: left; margin: 0 20px 20px 0; border: 1px solid #ddd;" src="<?php echo get_bloginfo( 'template_url' ) . '/screenshot.png'; ?>" />

        <div id="instructions" style="max-width: 800px;">
            <h3>Update Download and Instructions</h3>
            <p><strong>Please note:</strong> make a <strong>backup</strong> of the Theme inside your WordPress installation folder <strong>/wp-content/themes/<?php echo strtolower($theme_data['Name']); ?>/</strong></p>
            <p>To update the Theme, download the new version from the <a href="http://getpopshop.com">Popshop website</a>.</p>
            <p>Extract the zip's contents, look for the extracted theme folder, and after you have all the new files upload them using FTP to the <strong>/wp-content/themes/<?php echo strtolower($theme_data['Name']); ?>/</strong> folder overwriting the old ones.</p>
            <p>If you didn't make any changes to the theme files, you are free to overwrite them with the new ones without the risk of losing theme settings, and backwards compatibility is guaranteed.</p>
        </div>

            <div class="clear"></div>

	    <h3 class="title">Changelog</h3>
	    <?php echo $xml->changelog; ?>

	</div>

<?php } 



// This function retrieves a remote xml file on my server to see if there's a new update
// For performance reasons this function caches the xml content in the database for XX seconds ($interval variable)
// @Popshop @todo: Use Transients API instead.
// @Popshop: Switched from XML to JSON.
function get_latest_theme_version($interval) {
	// remote xml file location
	// @Popshop @todo: Update this:
	$notifier_file_url = 'http://192.168.0.84/wordpress/wp-content/plugins/popshop-stats/notifier.hjhxml';
    
	$db_cache_field = 'popshop-notifier-cache';
	$db_cache_field_last_updated = 'popshop-notifier-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// check the cache
	if ( !$last || (( $now - $last ) > $interval) ) {
		// cache doesn't exist, or is old, so refresh it
		
		if( function_exists('curl_init') ) { // if cURL is available, use it...
			$ch = curl_init($notifier_file_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			// @Popshop: lower timeout from 10 to 3.
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			$cache = curl_exec($ch);
			curl_close($ch);
		} else {
			$cache = file_get_contents($notifier_file_url); // ...if not, use the common file_get_contents()
		}
        
		if ($cache) {
		    // @Popshop: Check that this is valid JSON before inserting to database:
		    if (json_decode($cache)) {
    			// we got good results
    			update_option( $db_cache_field, $cache );
    			update_option( $db_cache_field_last_updated, time() );
    		}
		}
		// read from the cache file
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// cache file is fresh enough, so read from it
		$notifier_data = get_option( $db_cache_field );
	}

	$xml = simplexml_load_string($notifier_data); 

	return $xml;
}


