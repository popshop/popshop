<?php

$stats_intents = popshop_stats_intents();

$stats_channels = popshop_stats_channels();


?>


<div class="flot-container">
    <div id="flot"></div>
</div>


<?php 

popshop_databox("Visits",
                "people",
                array("Today" => "873.50 €",
                      "This Week" => "2150.70 €",
                      "This Month" => "3150.10 €",
                      "All Time" => "$ 4150.90"));

popshop_databox("Visits by Channel",
                "download",
                array("Facebook" => $stats_channels->facebook,
                      "Web" => $stats_channels->web,
                      "Mobile" => $stats_channels->mobile));

popshop_databox("Social Shares",
                "rocket",
                array("Facebook" => $stats_intents['like']->cnt,
                      "Twitter" => $stats_intents['tweet']->cnt,
                      "Google Plus" => $stats_intents['plusone']->cnt,
                      "Pinterest" => $stats_intents['pinit']->cnt));

popshop_databox("Video Views",
                "video",
                array("All Time" => $stats_intents['youtube']->cnt));


popshop_databox("Virality K-Factor",
                "science",
                array("Visits from Social Networks / Social Share" => "n/a"));

popshop_databox("Orders",
                "card",
                array("Today" => "873.50 €",
                      "This Week" => "2150.70 €",
                      "This Month" => "3150.10 €",
                      "All Time" => "$ 4150.90"));

popshop_databox("Revenue",
                "cash",
                array("All time" => "n/a"));

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
    
    
    
    <div id="popshop_timecount_intents"><?php echo popshop_timecount_intents() ?></div>
    
    <div id="popshop_tomorrow"><?php echo popshop_tomorrow() ?></div>
    
</div>






