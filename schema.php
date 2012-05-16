<?php 

global $popshop_db_version;
$popshop_db_version = "1.0";


function popshop_intents()
{
    return array("like" => "Likes", "tweet" => "Tweets", "plusone" => "Google +1", "pinit" => "Pin it", "linkedin" => "LinkedIn", "youtube" => "Youtube views");
}

function popshop_intent_label($event)
{
    $intents = popshop_intents();
    return $intents[$event];
}


function popshop_table($table)
{
    global $wpdb;
    
    return $wpdb->prefix . "popshop_" . $table;
}


/* 
 * There are three Popshop tables, for three different types of "events":
 *  * 1 for intents (social shares + video views), which are non-order events (no user info input)
 *  * 1 for orders, which are transactional events where the user had to input some info (typically for a PayPal payment)
 *  * 1 for visit stats
 * 
 * Worth noting is that the three tables actually share the same model: 
 *  * a numerical ID, 
 *  * a timestamp, 
 *  * a text identifier,
 *  * and a text field to store custom serialized (extendable) details (in JSON format).
 *   Yes, we're not big fans of structured databases and prefer NoSQL models! :)
 */
function popshop_check_schema()
{
    // @see http://codex.wordpress.org/Creating_Tables_with_Plugins
    
    global $wpdb;
    global $popshop_db_version;
    
    // From woocommerce:
    $collate = '';
    if($wpdb->supports_collation()) {
		if(!empty($wpdb->charset)) $collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if(!empty($wpdb->collate)) $collate .= " COLLATE $wpdb->collate";
    }
    
    $schema = array("intent" => "CREATE TABLE table_name (
                                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                                    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                                    name VARCHAR(16) NOT NULL,
                                    details text NOT NULL,
                                    PRIMARY KEY  (id) 
                                    ) $collate;",
                    "order"  => "CREATE TABLE table_name (
                                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                                    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                                    name VARCHAR(16) NOT NULL,
                                    details text NOT NULL,
                                    PRIMARY KEY  (id)
                                    ) $collate;",
                    "visit"  => "CREATE TABLE table_name (
                                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                                    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                                    name VARCHAR(16) NOT NULL,
                                    details text NOT NULL,
                                    PRIMARY KEY  (id)
                                    ) $collate;");
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    foreach ($schema as $table => $model) {
        
        $sql = str_replace('table_name', popshop_table($table), $model);
        
        dbDelta($sql);
    }
    
    add_option("popshop_db_version", $popshop_db_version);
}


function popshop_all_tables()
{
    return array("intent", "order", "visit");
}



function popshop_insert_event($table, $name, $details)
{
    // $details is expected in JSON format.
    
    global $wpdb;
    
    // @todo: Do I need to escape?
    
    $rows_affected = $wpdb->insert(popshop_table($table),
                                   array('time' => current_time('mysql'),
                                         'name' => $name,
                                         'details' => $details));
    
    $last_id = $wpdb->insert_id;
    
    return $last_id;
}


function popshop_update_order($id, $detail, $value)
{
    // This could easily be generalized into popshop_update_event (all types of events) if needed be.
    global $wpdb;
    
    $table = popshop_table("order");
    
    $res = $wpdb->get_row(sprintf("SELECT *
                                   FROM $table
                                   WHERE id = %d",
                                  $id));
    
    $details = json_decode($res->details);
    if ($details) {
        $details->$detail = $value;
    }
    
    $res = $wpdb->update($table, 
                         array('details' => json_encode($details)),
                         array('id' => $id));
    
    return $res;
}



/*-----------------------------------------------------------------------------------*/
/* Dashboard page
/*-----------------------------------------------------------------------------------*/


function popshop_stats_intents_by_type()
{
    global $wpdb;
    
    $table = popshop_table("intent");
    
    $res = $wpdb->get_results("SELECT name, COUNT(*) AS cnt
                               FROM $table
                               GROUP BY name",
                               OBJECT_K);
    
    // Default to 0 if no event of a particular type:
    
    foreach (popshop_intents() as $event => $label) {
        if (!isset($res[$event])) {
            $res[$event] = (object) array("cnt" => '0');
        }
    }
    
    return $res;
}


function popshop_stats_visits_by_channel()
{
    global $wpdb;
    
    $table = popshop_table("visit");
    
    $res = $wpdb->get_results("SELECT name, COUNT(*) AS cnt
                              FROM $table
                              GROUP BY name",
                              OBJECT_K);
    if (!isset($res['facebook'])) {
        $res['facebook'] = (object) array("cnt" => 0);
    }
    if (!isset($res['mobile'])) {
        $res['mobile'] = (object) array("cnt" => 0);
    }
    if (!isset($res['web'])) {
        $res['web'] = (object) array("cnt" => 0);
    }
    
    $total = $res['facebook']->cnt + $res['mobile']->cnt + $res['web']->cnt;
    
    if ($total == 0) {
        return (object) array("facebook" => "0",
                              "mobile" => "0",
                              "web" => "0");
    }
    
    $data = (object) array("facebook" => round($res['facebook']->cnt / $total * 100, 2) ." %",
                           "mobile"   => round($res['mobile']->cnt / $total * 100, 2) ." %",
                           "web"      => round($res['web']->cnt / $total * 100, 2) ." %");
    
    return $data;
}


function popshop_stats_visits_by_time()
{
    return popshop_stats_by_time("visit");
}

function popshop_stats_orders_by_time()
{
    return popshop_stats_by_time("order");
}


function popshop_stats_by_time($table)
{
    // Move to a separate library
    
    global $wpdb;
    
    $table = popshop_table($table);
    
    $data = array();
    
    $times = array("today" => 24*60*60,
                   "week"  => 7*24*60*60,
                   "month" => 30*24*60*60);
    
    foreach ($times as $key => $value) {
        
        $res = $wpdb->get_var("SELECT COUNT(*) AS cnt
                               FROM $table
                               WHERE time > DATE_SUB(NOW(), INTERVAL $value SECOND)");
        
        $data[$key] = $res;
    }
    
    $res = $wpdb->get_var("SELECT COUNT(*) AS cnt
                           FROM $table");
    $data["total"] = $res;
    
    return $data;
}



function popshop_mintime_events()
{
    global $wpdb;
    $mins = array();
    
    $table = popshop_table("intent");
    $min = $wpdb->get_var("SELECT MIN(DATE(time)) AS min
                           FROM $table");
    if ($min) {
        $mins[] = strtotime($min);
    }
    
    $table = popshop_table("order");
    $min = $wpdb->get_var("SELECT MIN(DATE(time)) AS min
                           FROM $table");
    if ($min) {
        $mins[] = strtotime($min);
    }
    
    $table = popshop_table("visit");
    $min = $wpdb->get_var("SELECT MIN(DATE(time)) AS min
                           FROM $table");
    if ($min) {
        $mins[] = strtotime($min);
    }
    
    if (count($min) > 0) {
        return min($mins);
    }
    else {
        return time();
    }
}


function popshop_timecount_intents()
{
    global $wpdb;
    
    // Go back to 30 days back max.
    $limit = 30;
    
    // @todo: We might be reinventing the wheel here.
    // Isn't there any "timecount" temporal aggregation library that could do the heavy work for us?
    
    $data = array();
    
    // First, fill arrays with zeros for eventless days.
    // By construction, the last day is today (we can't have any future data),
    // and we set the first day to be the day before the day when the first event happened (unless it's more than 30 days ago).
    $today = (int) (time() / 86400);
    $firstday = (int) (popshop_mintime_events() / 86400) - 1;
    if ($today - $firstday > $limit) {
        $firstday = $today - $limit;
    }
    
    foreach (popshop_intents() as $event => $label) {
        $data[$event] = array_fill($firstday, $today - $firstday + 1, 0);
    }
    
    $table = popshop_table("intent");
    
    $res = $wpdb->get_results("SELECT DATE(time) AS date, name, COUNT(*) AS cnt
                               FROM $table
                               GROUP BY date, name");
    
    
    
    // Then, we insert the real data.
    foreach ($res as $count) {
        $day = (int) (strtotime($count->date) / 86400);
        if ($day >= $firstday) {
            // @todo: This should be done in the query above.
            $data[$count->name][$day] = (int) $count->cnt;
        }
    }
    
    // Finally, we process this array to have it in the format that Flot wants.
    $flot = array();
    foreach ($data as $event => $d) {
        $dots = array();
        foreach ($d as $date => $cnt) {
            $dots[] = array($date * 86400 * 1000, $cnt);
        }
        $flot[] = array("label" => popshop_intent_label($event),
                        "data" => $dots);
    }
    
    return json_encode($flot);
}


function popshop_tomorrow()
{
    $today = (int) (time() / 86400);
    $tomorrow = ($today + 1) * 86400 * 1000;
    return $tomorrow;
}





/*-----------------------------------------------------------------------------------*/
/* Orders page
/*-----------------------------------------------------------------------------------*/


function popshop_total_orders()
{
    global $wpdb;
    $mins = array();
    
    $table = popshop_table("order");
    $cnt = $wpdb->get_var("SELECT COUNT(*) AS cnt
                           FROM $table");
    
    if ($cnt) {
        return $cnt;
    }
    return 0;
}

function popshop_get_orders($offset = 0, $limit = 0)
{
    global $wpdb;
    
    $table = popshop_table("order");
    
    $res = $wpdb->get_results(sprintf("SELECT *
                                       FROM $table
                                       ORDER BY id DESC
                                       LIMIT %d, %d",
                                      $offset, $limit));
    
    return $res;
}



function popshop_check_export_orders()
{
    // @todo: Make that a plugin.
    
    if (isset($_GET['export'])) {
        
        require_once (TEMPLATEPATH . '/lib/popshop-export-orders.php');
        
        popshop_export_orders();
        exit;
    }
}

