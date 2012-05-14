jQuery(document).ready(function($){
    
    
    
    // ORDERS PAGE
    
    // Status indicators
    // When clicking on a status indicator we cycle through all possible statuses.
    $(".status-indicator").click(function(){
        
        // Get all possible statuses from the page's "Bulk actions" select input element.
        var statuses = $("select[name='action'] option[value!='-1']").map(function(){ return this.value; });
        // Make it a "real" JS array:
        statuses = $.makeArray(statuses);
        
        var current_status = $(this).attr("class").split(" ")[1];
        
        // "Jump" to the next status:
        var new_status = statuses[($.inArray(current_status, statuses) + 1) % statuses.length];
        
        // Switch color:
        $(this).removeClass(current_status).addClass(new_status);
        // Switch text:
        $(this).text($("select[name='action'] option[value='"+new_status+"']").text());
        
        // Finally, update status in database:
        // @see http://codex.wordpress.org/AJAX_in_Plugins
        var id = $(".check-column input[type='checkbox']", $(this).closest("tr")).val();
        
        $.post(ajaxurl, {"action":"popshop_orderstatus_ajax", "id":id, "status":new_status}, function(response){
            // Do nothing special.
        });
    });
    
    
    
    
});

