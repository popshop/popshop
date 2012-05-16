<?php

// @see http://wordpress.org/extend/plugins/custom-list-table-example/
// @see http://codex.wordpress.org/Class_Reference/WP_List_Table
// Also @see http://wp.smashingmagazine.com/2011/11/03/native-admin-tables-wordpress/



/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}




class Popshop_Orders_List_Table extends WP_List_Table 
{
    
    function __construct()
    {
        global $status, $page;
        
        //Set parent defaults
        parent::__construct(array(
            'singular'  => 'order',     //singular name of the listed records
            'plural'    => 'orders',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ));
        
    }
    
    
    function get_columns()
    {
        $columns = array(
            'cb'       => '<input type="checkbox" />', //Render a checkbox instead of text
            'id'       => 'Order ID',
            'email'    => 'Customer Email',
            'customer' => 'Customer Info',
            'optin'    => 'Opted-in',
            'payment'  => 'Payment Info',
            'status'   => 'Status',
            'date'     => 'Date'
        );
        return $columns;
    }
    
    
    function column_default($item, $column_name)
    {
        return '&ndash;';
    }
    
    
    function column_id($item)
    {
        return sprintf("#%d", $item->id);
    }
    
    
    function column_date($item)
    {
        // @see wp-admin/includes/class-wp-posts-list-table.php
        
        $time = strtotime($item->time);
        $time_diff = time() - $time;
        
        if ($time_diff > 0 && $time_diff < 24*60*60)
        	$h_time = sprintf(__('%s ago'), human_time_diff($time));
        else
        	$h_time = mysql2date(__('Y/m/d'), $item->time);
        
        $out = '<abbr title="' . $item->time . '">' . $h_time . '</abbr>';
        return $out;
    }
    
    
    function column_email($item)
    {
        $details = json_decode($item->details);
        if ($details && isset($details->email) && ($details->email)) {
            // @see http://codex.wordpress.org/Function_Reference/get_avatar
            
            $out = sprintf("%s <a href='mailto:%s'>%s</a>", get_avatar($details->email, 32), $details->email, $details->email);
            return $out;
        }
        
        return $this->column_default(null, null);
    }
    
    
    function column_status($item)
    {
        $details = json_decode($item->details);
        if ($details && isset($details->status) && ($details->status)) {
            $status = $details->status;
        }
        else {
            $status = "pending";
        }
        return sprintf('<span class="status-indicator %s">%s</span>',
                       $status,
                       ucfirst($status));
    }
    
    
    function column_customer($item)
    {
        $details = json_decode($item->details);
        if ($details && isset($details->customer)) {
            $customer = $details->customer;
            
            // First, get the customer's name.
            $fullname = $customer->firstname." ".$customer->lastname."<br>";
            unset($customer->firstname);
            unset($customer->lastname);
            
            // Then concatenate all the other properties.
            $out = get_object_vars($customer);
            // Remove empty values from array:
            $out = array_filter($out, 'strlen');
            $out = implode(", ", $out);
            
            return $fullname.$out;
        }
        
        return $this->column_default(null, null);
    }
    
    
    function column_optin($item)
    {
        $details = json_decode($item->details);
        if ($details && isset($details->optin) && ($details->optin == "on")) {
            
            return "Yes";
        }
        else {
            return "No";
        }
    }
    
    
    function column_payment($item)
    {
        $details = json_decode($item->details);
        if ($details && isset($details->payment) && isset($details->payment->type)) {
            $types = array('free'    => "Free",
                           'share'   => "Free for a Share",
                           'contact' => "Free for a Contact",
                           'paypal'  => "PayPal");
            if (isset($types[$details->payment->type])) {
                return $types[$details->payment->type];
            }
        }
        
        return $this->column_default(null, null);
    }
    
    
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item->id                  //The value of the checkbox should be the record's id
        );
    }
    
    
    
    
    function get_sortable_columns() 
    {
        return array();
    }
    
    
    function get_bulk_actions()
    {
        $actions = array(
            'pending'    => 'Pending',
            'processing' => 'Processing',
            'completed'  => 'Completed',
            'cancelled'  => 'Cancelled'
        );
        // Unfortunately we can't change the "Bulk Actions" option label.
        return $actions;
    }
    
    
    function process_bulk_action()
    {
        // We only use bulk actions to update order statuses
        
        if ($this->current_action()) {
            
            foreach ($_REQUEST['order'] as $id) {
                popshop_update_order($id, 'status', $this->current_action());
            }
        }
    }
    
    
    
    
    function prepare_items()
    {
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = popshop_orders_per_page();
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $this->process_bulk_action();
        
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = popshop_total_orders();
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = popshop_get_orders(($current_page-1)*$per_page, $per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args(array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ));
    }
    
    
    
    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav($which)
    {
        if ($which == "bottom") {
            $out  = sprintf('<div class="tablenav-export"><a href="%s" target="_blank">Export as JSON</a></div>',
                            admin_url('admin.php?page=popshop-orders&export=true'));
            
            $out .= sprintf('<div class="tablenav-export"><a href="%s">Export as CSV</a></div>',
                            admin_url('admin.php?page=popshop-orders&export=true&format=csv'));
            
            echo $out;
        }
    }
    
}


