jQuery(document).ready(function($){
    
    
    // DASHBOARD PAGE
    
    
    /*-----------------------------------------------------------------------------------*/
    /* Databox tabs
    /*-----------------------------------------------------------------------------------*/
    
    
    $(".databox-legend ul li").click(function(){
        if ($(this).hasClass("decoy")) {
            return;
        }
        var databox = $(this).closest(".databox-inner");
        $(".databox-legend ul li", databox).removeClass("active");
        $(this).addClass("active");
        $(".databox-data-inner div", databox).hide();
        $(".databox-data-inner div", databox).eq($(this).index()).show();
    });
    
    
    
    
    /*-----------------------------------------------------------------------------------*/
    /* Flot graph
    /*-----------------------------------------------------------------------------------*/
    
    
    var data = $.parseJSON($("#popshop_timecount_intents").text());
    
    var options = {
        lines: {show: true},
        points: {show: true},
        xaxis: {
            mode: "time",
            minTickSize: [1, "day"],
            max: $("#popshop_tomorrow").text()
        },
        yaxes: [{min:0, position:"left"}, {min:0, position:"right"}],            
        legend: {
            position: "nw"
        },
        grid: {
            borderWidth: 0,
            hoverable: true
        }
        
    };
    
    var plot = $.plot($("#flot"), data, options);
    
    var svg = $('#triangle').html();
    
    // @see http://people.iola.dk/olau/flot/examples/interacting.html
    
    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + svg + '</div>').css( {
            position: 'absolute',
            top: y - 45,
            left: x - 50
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#flot").bind("plothover", function (event, pos, item) {
        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;
                
                $("#tooltip").remove();
                var x = item.datapoint[0],
                    y = item.datapoint[1];
                
                showTooltip(item.pageX, item.pageY, y);
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;            
        }
    });
    
    
});

