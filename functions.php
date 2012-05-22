<?php 


require_once (TEMPLATEPATH . '/extlib/detectmobilebrowser.php');
require_once (TEMPLATEPATH . '/schema.php');


/*-----------------------------------------------------------------------------------*/
/* Wordpress hooks
/*-----------------------------------------------------------------------------------*/


// ENQUEUE THEME SCRIPTS

function popshop_scripts() {
    wp_enqueue_script('jquery');
    
    wp_register_script('popshop_script_supersized', get_template_directory_uri() . '/js/supersized.3.2.6.min.js', array('jquery'));
    wp_enqueue_script('popshop_script_supersized');
    wp_register_script('popshop_script_flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', array('jquery'));
    wp_enqueue_script('popshop_script_flexslider');
    
    wp_register_script('popshop_script_modernizr', get_template_directory_uri() . '/js/modernizr-custom.js', array('jquery'));
    wp_enqueue_script('popshop_script_modernizr');
    wp_register_script('popshop_script_polyfiller', get_template_directory_uri() . '/js/polyfiller.js', array('jquery'));
    wp_enqueue_script('popshop_script_polyfiller');
    
    wp_register_script('popshop', get_template_directory_uri() . '/js/popshop.js', array('jquery'));
    wp_enqueue_script('popshop');
}
add_action('wp_enqueue_scripts', 'popshop_scripts');



// ENQUEUE ADMIN SCRIPTS

function popshop_admin_scripts($hook) {
    // Load scripts selectively:
    switch ($hook) {
        case "toplevel_page_popshop":
            // Dashboard page:
            wp_register_script('popshop_flot', get_template_directory_uri() . '/admin/js/jquery.flot.min.js', array('jquery'));
            wp_enqueue_script('popshop_flot');
            wp_register_script('popshop_flot_resize', get_template_directory_uri() . '/admin/js/jquery.flot.resize.js', array('jquery'));
            wp_enqueue_script('popshop_flot_resize');
            
            wp_register_script('popshop_admin_dashboard', get_template_directory_uri() . '/admin/js/popshop.dashboard.js', array('jquery'));
            wp_enqueue_script('popshop_admin_dashboard');
            break;
        
        case "popshop_page_popshop-orders":
            // Orders page:
            wp_register_script('popshop_admin_orders', get_template_directory_uri() . '/admin/js/popshop.orders.js', array('jquery'));
            wp_enqueue_script('popshop_admin_orders');
            break;
        
        case "popshop_page_popshop-settings":
            // Settings page:
            wp_register_script('popshop_admin_settings', get_template_directory_uri() . '/admin/js/popshop.settings.js', array('jquery', 'jquery-ui-sortable'));
            wp_enqueue_script('popshop_admin_settings');
            break;
    }
}
add_action('admin_enqueue_scripts', 'popshop_admin_scripts');



// ENQUEUE ADMIN STYLES

function popshop_admin_styles($hook) {
    // Load CSS selectively, only on Popshop admin pages:
    if (popshop_admin_page($hook)) {
        wp_enqueue_style('popshop_admin', get_template_directory_uri() . '/admin/css/admin.css');
    }
}
add_action('admin_enqueue_scripts', 'popshop_admin_styles');



// BUILD THEME ADMIN MENU

function build_popshop_menu() {
    
    add_menu_page('Popshop', 'Popshop', 'edit_theme_options', 'popshop', 'build_popshop_dashboard_page', get_template_directory_uri().'/images/popshop16.png');
    
    add_submenu_page('popshop', 'Dashboard', 'Dashboard', 'edit_theme_options', 'popshop', 'build_popshop_dashboard_page');
    add_submenu_page('popshop', 'Orders', 'Orders', 'edit_theme_options', 'popshop-orders', 'build_popshop_orders_page');
    add_submenu_page('popshop', 'Setup', 'Setup', 'edit_theme_options', 'popshop-settings', 'build_popshop_settings_page');
    // This last slug is what we save as OPTIONS_FRAMEWORK_ADMIN_PAGE (called $of_page in native Options Framework)
}
add_action('admin_menu', 'build_popshop_menu');
define('OPTIONS_FRAMEWORK_ADMIN_PAGE', 'popshop_page_popshop-settings');


// REGISTER FRONTEND NAVIGATION MENU

function register_popshop_menus() {
    
    register_nav_menus(array('popshop-menu' => __('Popshop navigation menu')));
}
add_action('init', 'register_popshop_menus');



// THEME "ACTIVATION HOOK"

function popshop_activation() {
    if (is_admin()) {
        
        // Hack, as Wordpress currently offers no activation hook for themes (unlike for plugins)
        // @see http://wordpress.stackexchange.com/questions/40530/whats-the-best-action-to-use-when-you-want-to-do-something-only-once-per-theme
        
        if (isset($_GET['activated']) && get_current_screen()->id == 'themes') {
            // @see http://wordpress.stackexchange.com/questions/44625/in-the-wordpress-admin-how-can-i-find-out-which-page-themes-php-widgets-php
            
            
            // CREATE DUMMY CONTENT
            if (!get_page_by_path('about')) {
                wp_insert_post(array('post_title' => 'About',
                                     'post_content' => "It's just mind-blowingly awesome. I apologize, and I wish I was more articulate, but it's hard to be articulate when your mind's blown - but in a very good way.",
                                     'post_name' => 'about',
                                     'post_status' => 'publish',
                                     'post_type' => 'page'));
            }
            if (!get_page_by_path('contact')) {
                wp_insert_post(array('post_title' => 'Contact',
                                     'post_content' => "I saw for the first time the earth's shape. I could easily see the shores of continents, islands, great rivers, folds of the terrain, large bodies of water. The horizon is dark blue, smoothly turning to black... the feelings which filled me I can express with one word - joy.",
                                     'post_name' => 'contact',
                                     'post_status' => 'publish',
                                     'post_type' => 'page'));
            }
            if (!get_page_by_path('blog')) {
                wp_insert_post(array('post_title' => 'Blog',
                                     'post_content' => "",
                                     'post_name' => 'blog',
                                     'post_status' => 'publish',
                                     'post_type' => 'page'));
            }
            if (!get_page_by_path('terms')) {
                wp_insert_post(array('post_title' => 'Terms and Conditions',
                                     'post_content' => "Our posturings, our imagined self-importance, the delusion that we have some privileged position in the Universe, are challenged by this point of pale light. Our planet is a lonely speck in the great enveloping cosmic dark. In our obscurity, in all this vastness, there is no hint that help will come from elsewhere to save us from ourselves.",
                                     'post_name' => 'terms',
                                     'post_status' => 'publish',
                                     'post_type' => 'page'));
            }
            
            
            // CREATE NAVIGATION MENU
            // @see http://wordpress.stackexchange.com/questions/44736/programmatically-add-a-navigation-menu-and-menu-items
            // @todo: Check and, as suggested, document.
            
            $menu_name = "popshop-menu";
            
            if (!wp_get_nav_menu_object($menu_name)) {
                
                $menu_id = wp_create_nav_menu($menu_name);
                
                wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => 'About',
                                                           'menu-item-object' => 'page',
                                                           'menu-item-object-id' => get_page_by_path('about')->ID,
                                                           'menu-item-type' => 'post_type',
                                                           'menu-item-status' => 'publish'));
                wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => 'Contact',
                                                           'menu-item-object' => 'page',
                                                           'menu-item-object-id' => get_page_by_path('contact')->ID,
                                                           'menu-item-type' => 'post_type',
                                                           'menu-item-status' => 'publish'));
                wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => 'Blog',
                                                           'menu-item-object' => 'page',
                                                           'menu-item-object-id' => get_page_by_path('blog')->ID,
                                                           'menu-item-type' => 'post_type',
                                                           'menu-item-status' => 'publish'));
                
                // Select this menu in the current theme
                if (!has_nav_menu($menu_name)) {
                    $locations = get_theme_mod('nav_menu_locations');
                    $locations[$menu_name] = $menu_id;
                    set_theme_mod('nav_menu_locations', $locations);
                }
            }
            
            
            
            // SCHEDULE POPSHOP CRON
            
            if (!wp_next_scheduled('popshop_daily_cron')) {
                wp_schedule_event(current_time('timestamp'), 'daily', 'popshop_daily_cron');
            }
            
            // CHECK SCHEMA
            popshop_check_schema();
            
            // IMAGE SIZES
            popshop_hardcode_image_sizes();
        }
    }
}
add_action('admin_head', 'popshop_activation');



// HOOK POPSHOP CRON
add_action('popshop_daily_cron', 'popshop_stats_report');


// VISITOR COUNTER HOOK
// I'm guessing it might be good to do it near the end of the page generation. 

add_action('wp_footer', 'popshop_save_hit');


// Add AJAX callback (for AJAX updating of a single order's status)
// @see http://codex.wordpress.org/AJAX_in_Plugins

function popshop_orderstatus_ajax_callback() {
    
    // If this was critical security-wise, we'd implement check_ajax_referer()
    
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    popshop_update_order($id, 'status', $status);
    
    die();
}
add_action('wp_ajax_popshop_orderstatus_ajax', 'popshop_orderstatus_ajax_callback');


// CROPPING/RESIZING SLIDER IMAGES
// Right now, we don't have a way to retrieve the attachment ID from an uploaded file in Options Framework (though this would be very helpful)
// so we can't use the natively-resized thumbnails/images automatically.
// However, users can use them manually (from within the Media uploader).
// Additionally, we "crop" images that might still be larger than 810x315 using CSS only, even though it's not ideal.

// add_image_size('slider-thumb', 810, 315, true);

function popshop_hardcode_image_sizes()
{
    update_option('thumbnail_size_w', 160);
    update_option('thumbnail_size_h', 160);
    update_option('thumbnail_crop', 1);
    update_option('medium_size_w', 810);
    update_option('medium_size_h', 315);
    update_option('medium_crop', 1);
    update_option('large_size_w', 790);
    update_option('large_size_h', 3000);
}



function popshop_force_https()
{
    if (!is_admin()) {
        // We're on the frontend.
        if (popshop_get_option('force_https')) {
            if (!is_ssl()) {
                $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                wp_redirect($url);
                exit;
            }
        }
    }
}
add_action('after_setup_theme', 'popshop_force_https');


/*-----------------------------------------------------------------------------------*/
/* Options Framework Theme
/*-----------------------------------------------------------------------------------*/


// Override some plugguable functions from Options Framework.

require_once (TEMPLATEPATH . '/lib/options-framework-overrides.php');

/* 
 * Wordpress Options Framework Theme
 *
 * @see  http://wptheming.com/options-framework-theme/
 */


if ( !function_exists( 'optionsframework_init' ) ) {

    /* Set the file path based on whether the Options Framework Theme is a parent theme or child theme */

    if ( STYLESHEETPATH == TEMPLATEPATH ) {
        define('OPTIONS_FRAMEWORK_URL', TEMPLATEPATH . '/extlib/options-framework/');
        define('OPTIONS_FRAMEWORK_DIRECTORY', get_bloginfo('template_directory') . '/extlib/options-framework/');
    } else {
        define('OPTIONS_FRAMEWORK_URL', STYLESHEETPATH . '/extlib/options-framework/');
        define('OPTIONS_FRAMEWORK_DIRECTORY', get_bloginfo('stylesheet_directory') . '/extlib/options-framework/');
    }

    require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');
}


/* 
 * This is an example of how to add custom scripts to the options panel.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { 
    
    // The following is my attempt at adding some (more) parametrability to Options Framework's JS.
    
    $of_options = array('fadeDuration' => 0,
                        'navTabSelector' => '.nav-popshop-settings a');
    
    echo sprintf('<script type="text/javascript">/* <![CDATA[ */ var Of_options = %s; /* ]]> */</script>', json_encode($of_options));
    
}


/*
 * Options' Framework default validation is very strict,
 * so we actually remove filters entirely on textareas and "info" areas.
 * (This only relates to validation in the admin panel, not on the frontend, of course).
 * We also allow "target" attributes on links.
 *
 * @see  http://wptheming.com/2011/05/options-framework-0-6/
 */
add_action('admin_init','optionscheck_change_sanitization', 100);
function optionscheck_change_sanitization() {
    global $allowedtags;
    // Allow target attributes in links, used in our Options page.
    $allowedtags["a"]["target"] = array();
    $allowedtags["a"]["class"] = array();
    $allowedtags["a"]["data-example"] = array();
    $allowedtags["br"] = array();
    
    remove_all_filters('of_sanitize_info');
    
    remove_filter('of_sanitize_textarea', 'of_sanitize_textarea');
    // We have to add an empty filter, or nothing will get saved (@see optionsframework_validate) 
    add_filter('of_sanitize_textarea', 'of_sanitize_textarea_custom');
}
function of_sanitize_textarea_custom($input) {
    return $input;
}




/*-----------------------------------------------------------------------------------*/
/* Admin Pages
/*-----------------------------------------------------------------------------------*/


function popshop_admin_page($screen_id)
{
    return (strpos($screen_id, 'popshop') !== false);
    // Yes, that's a bit hackish...
}


function build_popshop_dashboard_page()
{
    include(TEMPLATEPATH . '/admin/templates/top.php');
    include(TEMPLATEPATH . '/admin/dashboard.php');
    include(TEMPLATEPATH . '/admin/templates/bottom.php');
}


function build_popshop_orders_page()
{
    include(TEMPLATEPATH . '/admin/templates/top.php');
    include(TEMPLATEPATH . '/admin/orders.php');
    include(TEMPLATEPATH . '/admin/templates/bottom.php');
}


function build_popshop_settings_page()
{
    include(TEMPLATEPATH . '/admin/templates/top.php');
    optionsframework_page();
    include(TEMPLATEPATH . '/admin/templates/bottom.php');
}


function popshop_gettingstarted_page()
{
    return file_get_contents(TEMPLATEPATH . '/admin/settings.gettingstarted.php');
}


function popshop_databox($title, $img, $data)
{
    // Build a dashboard databox from template.
    // This function's arguments are passed on to the template,
    // we just change the format of $data from an associative array of structure array("tab_title" => "tab_data"),
    // to two different arrays ($tab_titles for tab titles, $tab_data for actual data).
    // We also pad $tab_titles (with "decoys") so that there are always 4 tabs (or it breaks the layout).
    
    $tab_titles = array_keys($data);
    $tab_data   = array_values($data);
    
    if (count($tab_titles) > 1 && count($tab_titles) < 4) {
        $tab_titles = array_pad($tab_titles, 4, "decoy");
    }
    
    include(TEMPLATEPATH . '/admin/templates/databox.php');
}



// CONTEXTUAL HELP AND SCREEN OPTIONS (ADMIN PANEL)

// @see http://codex.wordpress.org/Adding_Contextual_Help_to_Administration_Menus
// We actually use another method, which doesn't seem really documented.
// @todo: Document it.

add_filter('contextual_help', 'popshop_contextual_help', 10, 3);

function popshop_contextual_help($contextual_help, $screen_id, $screen)
{
    if (popshop_admin_page($screen_id)) {
        if (method_exists($screen, 'add_help_tab')) {
            // WordPress 3.3
            
            $documentation = '<p>Please read the documentation and installation instructions for Popshop <a href="http://getpopshop.com/documentation" target="_blank">here</a>.</p>';
            
            $screen->add_help_tab(array(
                'title' => __('Documentation', 'popshop'),
                'id' => 'popshop-documentation',
                'content' => $documentation));
            
            $credits  = '<p>Thanks!: <a href="http://thenounproject.com" target="_blank">Noun Project</a>, 
                                     <a href="http://subtlepatterns.com/" target="_blank">Subtle Patterns</a>, 
                                     <a href="http://code.google.com/p/flot/" target="_blank">jQuery Flot</a>, 
                                     <a href="http://wptheming.com/options-framework-theme/" target="_blank">Options Framework</a>, 
                                     <a href="http://www.woothemes.com/flexslider/" target="_blank">FlexSlider</a>, 
                                     <a href="http://buildinternet.com/project/supersized/" target="_blank">Supersized</a>...</p>';
            
            $screen->add_help_tab(array(
                'title' => __('Credits', 'popshop'),
                'id' => 'popshop-credits',
                'content' => $credits));
        }
    }
    
    return $contextual_help;
}

// @see http://chrismarslender.com/wp-tutorials/wordpress-screen-options-tutorial/

add_action('load-popshop_page_popshop-orders', 'popshop_screen_options');
function popshop_screen_options()
{
    add_screen_option('per_page', array('label'   => 'Orders',
                                        'default' => 10,
                                        'option'  => 'popshop_orders_per_page'));
}

add_filter('set-screen-option', 'popshop_screen_options_set', 10, 3);
function popshop_screen_options_set($status, $option, $value)
{
    if ($option == 'popshop_orders_per_page') {
        return $value;
    }
}

function popshop_orders_per_page()
{
    $user = get_current_user_id();
    $screen = get_current_screen();
    $option = $screen->get_option('per_page', 'option');
    
    $per_page = get_user_meta($user, $option, true);
    
    if (empty($per_page) || ($per_page < 1)) {
        $per_page = $screen->get_option('per_page', 'default');
    }
    return $per_page;
}


// JSON EXPORT FROM ORDERS PAGE
add_action('load-popshop_page_popshop-orders', 'popshop_check_export_orders');


/*-----------------------------------------------------------------------------------*/
/* Popshop Functions
/*-----------------------------------------------------------------------------------*/



/* Rename the get_option function from Options Framework, in case we need to add some functionality later on. */

function popshop_get_option($name)
{
    return of_get_option($name, false);
}


function popshop_share_button($type)
{
    $buttons = popshop_get_option('share_buttons');
    if (isset($buttons[$type]) && $buttons[$type] == "1") {
        return true;
    }
    else {
        return false;
    }
}


function popshop_on_facebook()
{
    // @see  http://stackoverflow.com/questions/5587784/how-can-i-find-out-what-page-has-installed-my-facebook-canvas-app
    // We could use the Facebook PHP SDK, but this seems overkill for just this.
    
    if (isset($_REQUEST["signed_request"])) {
        $signed_request = $_REQUEST["signed_request"];
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        
        if (isset($data["page"])) {
             return $data["page"]["id"];
             // Note: Here, we could know whether the user has liked our page.
        }
        else {
             return false;
        }
    }
}


function popshop_facebook_image()
{
    if (popshop_get_option('facebook_image')) {
        return popshop_get_option('facebook_image');
    }
    else if (popshop_get_option('logo')) {
        return popshop_get_option('logo');
    }
    else {
        return "";
    }
}

function popshop_facebook_url()
{
    // Currently, we don't know the Facebook Page URL. (unless the user is actually on Facebook, but that's not the case for Facebook's bots)
    // Besides, I'm not sure Facebook allows facebook.com URLs in <meta property="og:url">.
    // So we use the "canonical" URL outside Facebook:
    return home_url();
}


function popshop_get_video_embed($which)
{
    if ($which == "cover") {
        // Cover video
        if (popshop_get_option('video_embed_code')) {
            $video_embed_code = popshop_get_option('video_embed_code');
        }
        else {
            // Default:
            $video_embed_code = '<iframe id="player" width="370" height="218" src="http://www.youtube.com/embed/VIDEO_ID?wmode=opaque&rel=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe>';
        }
    }
    else {
        // Digital Streaming
        if (popshop_get_option('video_embed_code_2')) {
            $video_embed_code = popshop_get_option('video_embed_code_2');
        }
        else {
            // Default:
            $video_embed_code = '<iframe id="player" width="710" height="391" src="http://www.youtube.com/embed/VIDEO_ID?wmode=opaque&rel=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe>';
        }
    }
    
    
    if ($which == "cover") {
        $video_id = popshop_get_option('covervideo_id');
    }
    else {
        $video_id = popshop_get_option('video_id');
    }
    
    return str_replace('VIDEO_ID', $video_id, $video_embed_code);
}


function popshop_get_custom_css()
{
    // Custom CSS from LESS file and Popshop options. 
    // This is not used right now, but it could be one day.
    require_once (TEMPLATEPATH . '/extlib/lessc.inc.php');
    
    // @see http://leafo.net/lessphp/docs/#setting_variables_from_php
    
    $less = new lessc(TEMPLATEPATH."/options.less");
    $css = $less->parse(null, array(/* 'variable' => popshop_get_option('variable') */));
    
    return $css;
}


function popshop_get_page_link_by_slug($slug)
{
    // @todo: Encapsulate into Transients API for performance?
    
    $page = get_page_by_path($slug);
    if ($page) {
        return get_permalink($page);
    }
    else {
        return "#";
    }
}



function popshop_get_navmenu()
{
    $args = array('theme_location' => 'popshop-menu',
                  'echo' => false,
                  'container' => false,
                  'items_wrap' => '%3$s',
                  'depth' => 1);
    
    $menu = wp_nav_menu($args);
    
    return $menu;
}


function popshop_pinit_data()
{
    // Url-encoded data to pass to the Pinterest button
    
    $data = array("url" => home_url(),
                  "media" => popshop_get_option("slider_image_1"),
                  "description" => popshop_get_option("product_name"));
    
    return http_build_query($data);
}


function popshop_orderform_fields()
{
    $fields = json_decode(popshop_get_option('orderform_fields'));
    if (!$fields) {
        return;
    }
    $out = "";
    foreach ($fields as $field) {
        // @see HTML5 Form validation:
        if ($field->type == "text") {
            $out .= sprintf('<input type="text" name="%s" placeholder="%s" required>', $field->name, $field->placeholder);
        }
        else if ($field->type == "email") {
            $out .= sprintf('<input type="text" name="email" placeholder="%s" required>', $field->placeholder);
            // Kept type="text" instead of type="email" or Firefox won't autocomplete, for some reason.
        }
        else if ($field->type == "h3") {
            $out .= sprintf('<h3>%s</h3>', $field->content);
        }
    }
    return $out;
}


function popshop_slider_slides()
{
    $images = array();
    foreach (range(1, 5) as $i) {
        if (popshop_get_option("slider_image_".$i)) {
            if (popshop_get_option("slider_caption_".$i)) {
                $images[] = array("image"   => popshop_get_option("slider_image_".$i),
                                  "caption" => popshop_get_option("slider_caption_".$i));
            }
            else {
                // Default caption:
                $images[] = array("image"   => popshop_get_option("slider_image_".$i),
                                  "caption" => popshop_get_option("slider_caption"));
            }
        }
    }
    return $images;
}



function popshop_save_hit()
{
    if (!stripos($_SERVER['HTTP_USER_AGENT'], "bot")) {
        // Exclude Bots from this.
        // Matching the user-agent string with "bot" is actually pretty reliable.
        
        if (popshop_on_facebook()) {
            $where = 'facebook';
        }
        else if (DetectMobileBrowser::detect()) {
            // Note: The iPad is not detected as mobile.
            $where = 'mobile';
        }
        else {
            $where = 'web';
        }
        
        
        $details = json_encode(array("ip"      => $_SERVER['REMOTE_ADDR'],
                                     "referer" => wp_get_referer()));
        
        popshop_insert_event('visit', $where, $details);
    }
}


function popshop_stats_report()
{
    // If we're not the current selected theme, this code should never be executed.
    // @todo: Check that.
    
    // If we're on localhost or a IP-only host (probably for testing), don't do anything:
    $host = parse_url(home_url(), PHP_URL_HOST);
    if ((strpos($host, "localhost") !== false) || (trim($host, ".0123456789") == "")) {
        return;
    }
    // IMPORTANT: If user has opted-out, don't do anything:
    if (popshop_get_option('stats_report_optout')) {
        return;
    }
    
    // We implement a Popshop-wide (i.e. over all Popshop installs) simple reporting system, 
    // analogous to Presstrends (http://presstrends.io) or to the one built into a few high-quality open source projects, like for example StatusNet.
    // 
    // These stats are opt-out (there's a configuration option to disable them), totally anonymized, 
    // and they enable us to know how many sites are running Popshop, and how their own users are interacting with it. 
    // This data will help us decide how to best evolve the project and prioritize new developments.
    // 
    // This also gives us the opportunity of building an up-to-date directory of sites running Popshop on http://getpopshop.com :
    // if you want to appear in our directory, you have to turn stats reporting on.
    
    $stats = array('stats_intents'  => popshop_stats_intents_by_type(),
                   'stats_channels' => popshop_stats_visits_by_channel(),
                   'home_url'       => home_url(),
                   'header'         => popshop_get_option('header'));
    
    // @todo: Add Facebook Page id, mode of operation, etc.
    
    
    $url = 'http://stats.getpopshop.com/stats';
    
    // @see http://codex.wordpress.org/Function_API/wp_remote_post
    // For instance, default timeout is 5s:
    $response = wp_remote_post($url, array('body' => array('stats' => json_encode($stats))));
    
    // Debug mode only:
    // file_put_contents("popshop.cron.log", time().": ".json_encode($response)."\n", FILE_APPEND);
}



/*-----------------------------------------------------------------------------------*/
/* Misc 
/*-----------------------------------------------------------------------------------*/

// Disable admin bar completely
// Only disable it when embedded on Facebook?
show_admin_bar(false);



/*-----------------------------------------------------------------------------------*/
/* PayPal Integration (WIP) 
/*-----------------------------------------------------------------------------------*/

function popshop_paypal_args($paypal_url, $order)
{
    $gateway = array(
        'username' => popshop_get_option('paypal_username'),
        'password' => popshop_get_option('paypal_password'),
        'signature' => popshop_get_option('paypal_signature'),
        'version' => '84.0',
        'payment_action' => 'Sale',
        'payment_amount' => popshop_get_option('paypal_amount'),
        'currency' => popshop_get_option('paypal_currency'),
        'return_url' => $paypal_url."?paypal=process&order=".$order,
        'cancel_url' => $paypal_url."?paypal=cancel"
    );
    
    return $gateway;
}


function popshop_paypal_link($paypal_url, $order)
{
    // @see https://gist.github.com/1496282
    
    require_once (TEMPLATEPATH . '/extlib/wp_adpress_paypal.php');
    
    $gateway = popshop_paypal_args($paypal_url, $order);
    
    // Create a new instance of the class
    $paypal = new wp_adpress_paypal($gateway, true);
    
    // Get the redirect URL
    $redirect_url = $paypal->doExpressCheckout();
    
    // @see SetExpressCheckout method
    
    // Note: we target="_top" this link so that it can work on Facebook.
    return $redirect_url;
}


define('PAYPAL_SUCCESS', 1);
define('PAYPAL_FAILURE', 2);



function popshop_paypal_process()
{
    // @return PAYPAL_SUCCESS, PAYPAL_FAILURE, or false
    // Static caching, so this we can call this function multiple times.
    static $res;
    if (isset($res)) {
        return $res;
    }
    
    if (isset($_GET['paypal']) && ($_GET['paypal'] == "process")) {
        
        if (isset($_GET['token']) && isset($_GET['PayerID'])) {
            
            require_once (TEMPLATEPATH . '/extlib/wp_adpress_paypal.php');
            
            // Try and process the payment
            $gateway = popshop_paypal_args();
            $paypal = new wp_adpress_paypal($gateway, true);
            $payment = $paypal->processPayment($_GET['token'], $_GET['PayerID']);
            if ($payment) {
                $res = PAYPAL_SUCCESS;
                return $res;
            }
            else {
                $res = PAYPAL_FAILURE;
                return $res;
            }
        }
    }
    $res = false;
    return $res;
}

add_action('init', 'popshop_paypal_process');
