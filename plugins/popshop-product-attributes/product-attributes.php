<?php 
/*
Plugin Name: Popshop Product Attributes
Plugin URI: http://getpopshop.com/
Description: Extends Popshop. Provides <strong>one possible set of product attributes</strong> (T-shit size or color, etc.) at a <strong>fixed price</strong>.
Version: 0.1
Author: Julien Chaumond
Author URI: http://getpopshop.com/
*/




add_action('popshop_orderform_firstcol_top', 'popshop_product_attributes');

function popshop_product_attributes()
{
    echo "<p>TEST</p>";
}

