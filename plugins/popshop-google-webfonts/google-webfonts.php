<?php 
/*
Plugin Name: Popshop Google Webfonts
Plugin URI: http://getpopshop.com/
Description: Extends Popshop. Provides Google Webfonts integration.
Version: 1.0
Author: Julien Chaumond
Author URI: http://getpopshop.com/
*/


// ENQUEUE THEME SCRIPTS

function popshop_webfonts_scripts() {
    wp_enqueue_script('jquery');
    
    // Should we load this asynchronously?
    // wp_register_script('popshop_script_googlewebfonts', 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js', array('jquery'));
    // wp_enqueue_script('popshop_script_googlewebfonts');
    
    // We need to include this script only after WebFontConfig has been defined (cf. below).
    
}
add_action('wp_enqueue_scripts', 'popshop_webfonts_scripts');



// ENQUEUE ADMIN SCRIPTS

function popshop_webfonts_admin_scripts() {
    wp_enqueue_script('jquery');
    
    wp_register_script('popshop_admin', plugins_url('js/google-webfonts-admin.js', __FILE__), array('jquery'));
    wp_enqueue_script('popshop_admin');
}
add_action('admin_enqueue_scripts', 'popshop_webfonts_admin_scripts');



// SETTINGS/OPTIONS

add_filter('popshop_options', 'popshop_webfonts_options');

function popshop_webfonts_options($options) {
    
    $options[] = array("name" => "Google Webfonts",
                       "type" => "heading");
    
    // Google Web Fonts sampling from LaunchEffect:
    
    $google_fonts = array('','Abel','Allerta Stencil','Anton','Architects Daughter','Arvo','Bangers','Bevan','Bowlby One SC','Cabin Sketch:700','Cardo','Chewy','Corben:700','Dancing Script','Delius Swash Caps','Didact Gothic','Forum','Francois One','Geo','Gravitas One','Gruppo','Hammersmith One','IM Fell Double Pica SC','Josefin Sans','Kameron','League Script','Leckerli One','Loved by the King','Maiden Orange','Maven Pro','Muli','Nixie One','Old Standard TT','Oswald','Ovo','Pacifico','Permanent Marker','Playfair Display','Podkova','Pompiere','Raleway:100','Rokkitt','Six Caps','Sniglet:800','Syncopate','Terminal Dosis Light','Ultra','Unna','Varela Round','Yanone Kaffeesatz');
    
    $fonts = array();
    foreach ($google_fonts as $font) {
        $fonts[$font] = $font;
    }
    
    $options[] = array( "name" => "Font: Google WebFonts",
                        "desc" => "Select your Google Webfont from this list.",
                        "id" => "heading_font_goog",
                        "std" => "",
                        "type" => "select",
                        "options" => $fonts);
    
    // Hidden field
    // @todo: Use some other way, so that this is not stored as an option.
    $options[] = array("name" => "",
                       "desc" => "",
                       "id" => "popshop_webfonts_dir",
                       "std" => plugins_url("/", __FILE__),
                       "class" => "hidden",
                       "type" => "text");
    
    return $options;
}



// FRONTEND SCRIPT AND CSS

add_action('wp_head', 'popshop_webfonts_head');

function popshop_webfonts_head() {
    if (popshop_get_option('heading_font_goog')) {
        ?>
        
        <script type="text/javascript">
        WebFontConfig = {
            google: { families: [ "<?php echo popshop_get_option('heading_font_goog') ?>" ] }
        };
        </script>
        
        <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js?ver=3.3.1'></script>
        
        <style>
        h1, h2, h3 {
        font-family:"<?php echo popshop_webfonts_strip_colon(popshop_get_option('heading_font_goog')) ?>";
        }
        </style>
        
    <?php
    }
}


function popshop_webfonts_strip_colon($str)
{
    $pos = strpos($str, ':');
    if ($pos === false) {
        return $str;
    }
    else {
        return substr($str, 0, $pos);
    }
}

