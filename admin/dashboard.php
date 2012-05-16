<?php

$intents_by_type = popshop_stats_intents_by_type();

$visits_by_channel = popshop_stats_visits_by_channel();

$visits_by_time = popshop_stats_visits_by_time();

$orders_by_time = popshop_stats_orders_by_time();

$k_factor = round(($intents_by_type['like']->cnt + $intents_by_type['tweet']->cnt + $intents_by_type['plusone']->cnt + $intents_by_type['pinit']->cnt + $intents_by_type['linkedin']->cnt) / ($visits_by_time['total'] + 1), 3);

?>


<div class="flot-container">
    <div id="flot"></div>
</div>


<?php 

popshop_databox("Visits",
                "people",
                array("Today"      => $visits_by_time['today'],
                      "This Week"  => $visits_by_time['week'],
                      "This Month" => $visits_by_time['month'],
                      "All Time"   => $visits_by_time['total']));

popshop_databox("Visits by Channel",
                "download",
                array("Facebook" => $visits_by_channel->facebook,
                      "Web"      => $visits_by_channel->web,
                      "Mobile"   => $visits_by_channel->mobile));

popshop_databox("Social Shares",
                "rocket",
                array("All"      => $intents_by_type['like']->cnt + $intents_by_type['tweet']->cnt + $intents_by_type['plusone']->cnt + $intents_by_type['pinit']->cnt + $intents_by_type['linkedin']->cnt,
                      "Facebook" => $intents_by_type['like']->cnt,
                      "Twitter"  => $intents_by_type['tweet']->cnt,
                      "Other"    => $intents_by_type['plusone']->cnt + $intents_by_type['pinit']->cnt + $intents_by_type['linkedin']->cnt));

if ($intents_by_type['youtube']->cnt > 0) {
    popshop_databox("YouTube Views",
                    "video",
                    array("All Time" => $intents_by_type['youtube']->cnt));
}

popshop_databox("Virality K-Factor",
                "science",
                array("Social Shares / Visits" => $k_factor));  
// This should be "Visits from Social Networks / Social Share",
// but this is much easier to compute.

popshop_databox("Orders",
                "card",
                array("Today"      => $orders_by_time['today'],
                      "This Week"  => $orders_by_time['week'],
                      "This Month" => $orders_by_time['month'],
                      "All Time"   => $orders_by_time['total']));


// popshop_databox("Revenue",
//                 "cash",
//                 array("All time" => "n/a"));

// @todo: Orders by Channel ?


?>




<div style="display:none;">
    <div id="triangle">
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100px" height="5px">
            <line x1="30" y1="0" x2="45" y2="0" style="stroke:#333;stroke-width:1.7;"/>
            <line x1="45" y1="0" x2="50" y2="5" style="stroke:#333;stroke-width:1;"/>
            <line x1="50" y1="5" x2="55" y2="0" style="stroke:#333;stroke-width:1;"/>
            <line x1="55" y1="0" x2="70" y2="0" style="stroke:#333;stroke-width:1.7;"/>
        </svg>
    </div>
    
    
    
    <div id="popshop_timecount_intents"><?php echo popshop_timecount() ?></div>
    
    <div id="popshop_tomorrow"><?php echo popshop_tomorrow() ?></div>
    
</div>






