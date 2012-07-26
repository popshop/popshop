<?php

// INCLUDE WORDPRESS STUFF
define('WP_USE_THEMES', false);
include_once('../../../wp-load.php');

// @see http://wordpress.stackexchange.com/questions/44802/how-to-create-a-specific-frontend-url-not-a-page-from-a-theme-or-plugin

require_once (TEMPLATEPATH . '/schema.php');

switch ($_POST['action']) {
    case 'confirmation_email':
    
    $headers = 'From: ' . get_bloginfo('name') . ' <'.get_settings('admin_email') . '>' . "\r\n";
    add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
    
    $message = '<body style="background-color:background-color:#E7EBF2;margin:20px;">';
    
    $message .= '<div style="background:white;border:1px solid #C4CDE0;border-bottom-width:2px;border-radius:3px;padding:20px;margin:auto;">';
        
    $message .= $_POST['message'];
    
    $message .= '</div>';
    
    $message .= '</body>';
    
    
    wp_mail( $_POST['email'], $_POST['subject'], $message, $headers, $attachments ); 
    
    
        break;
    default:
    
$table    = (isset($_POST['table'])) ? $_POST['table'] : null;
$name     = (isset($_POST['name'])) ? $_POST['name'] : null;
$details  = (isset($_POST['details'])) ? $_POST['details'] : null;

// Security checks.

if (!in_array($table, popshop_all_tables())) {
    echo -1;
    exit;
}

// Strip everything from $name but letters and digits
$name = preg_replace("/[^\w]+/", "", $name);


$details = stripslashes($details);

if ($details && !json_decode($details)) {
    echo -1;
    exit;
}

    	// Returns id of insert row:
        echo popshop_insert_event($table, $name, $details);
        break;
}