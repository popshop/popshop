<?php

// INCLUDE WORDPRESS STUFF
define('WP_USE_THEMES', false);
include_once('../../../wp-load.php');

// @see http://wordpress.stackexchange.com/questions/44802/how-to-create-a-specific-frontend-url-not-a-page-from-a-theme-or-plugin

require_once (TEMPLATEPATH . '/schema.php');


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


