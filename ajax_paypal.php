<?php

// INCLUDE WORDPRESS STUFF
define('WP_USE_THEMES', false);
include_once('../../../wp-load.php');



require_once (TEMPLATEPATH . '/functions.php');



$order      = (isset($_POST['order'])) ? $_POST['order'] : null;
$paypal_url = (isset($_POST['paypal_url'])) ? $_POST['paypal_url'] : null;


if (!$order) {
    echo -1;
    exit;
}

if (!$paypal_url) {
    $paypal_url = get_home_url();
}


echo popshop_paypal_link($paypal_url, $order);

