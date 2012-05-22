jQuery(document).ready(function($){
    
    
    
    // SETTINGS PAGE
    
    
    /*-----------------------------------------------------------------------------------*/
    /* General Stuff (Options Framework customization)
    /*-----------------------------------------------------------------------------------*/
    
    // Copy "Save Options" submit buttons at the top of pages
    var submit_button = '<input class="button-primary top-button" type="submit" value="' + $("#optionsframework-submit input[name='update']").attr('value') + '" name="update">';
    // Sadly there is no jQuery outerHTML function so we have to reconstruct the markup ourselves...
    // We also add a custom class ("top-button") to help style it.
    $("#optionsframework h3").append(submit_button);
    // We append into h3 tags, although this is not semantically ideal.
    
    // Hook into Options Framework's custom tab selection event, in order to style specific options groups differently.
    $(".nav-popshop-settings a.nav-tab").bind('of-tab-active', function(){
        $('#optionsframework').removeClass().addClass('postbox').addClass($(this).attr('id'));
    });
    
    
    
    /*-----------------------------------------------------------------------------------*/
    /* Getting Started
    /*-----------------------------------------------------------------------------------*/
    
    // On document load, set correct options:
    var shop_type = $("#section-shop_type input:checked").val();
    $("#slide-shop_type .startbox[data-shop='"+shop_type+"']").addClass("active");
    var payment_type = $("#section-payment_type input:checked").val();
    $("#slide-payment_type .startbox[data-payment='"+payment_type+"']").addClass("active");
    var product_type = $("#section-product_type input:checked").val();
    $("#slide-product_type .startbox[data-product='"+product_type+"']").addClass("active");
    
    
    // First slide (Shop type)
    $("#slide-shop_type .startbox").click(function(){
        console.log($(this).attr('data-shop'));
        $("#section-shop_type input[value='"+$(this).attr('data-shop')+"']").attr('checked', 'checked');
        $("#slide-shop_type .startbox").removeClass("active");
        $(this).addClass("active");
        refreshShopType();
        switch ($(this).attr('data-shop'))
        {
            case "sell":
                // Switch to second slide:
                $("#slide-shop_type").hide();
                $("#slide-payment_type").show();
                break;
            case "link":
                // Switch to second Options Framework tab (the one after Getting Started), by triggering a click on the corresponding tab:
                $(".nav-popshop-settings a.nav-tab").eq(1).trigger('click');
                break;
        }
    });
    
    // Second slide (Payment type)
    $("#slide-payment_type .startbox").click(function(){
        console.log($(this).attr('data-payment'));
        $("#section-payment_type input[value='"+$(this).attr('data-payment')+"']").attr('checked', 'checked');
        $("#slide-payment_type .startbox").removeClass("active");
        $(this).addClass("active");
        refreshPaymentType();
        // Switch to third slide:
        $("#slide-payment_type").hide();
        $("#slide-product_type").show();
    });
    
    // Third slide (Product type)
    $("#slide-product_type .startbox").click(function(){
        console.log($(this).attr('data-product'));
        $("#section-product_type input[value='"+$(this).attr('data-product')+"']").attr('checked', 'checked');
        $("#slide-product_type .startbox").removeClass("active");
        $(this).addClass("active");
        refreshProductType();
        // Switch to second Options Framework tab (the one after Getting Started), by triggering a click on the corresponding tab:
        $(".nav-popshop-settings a.nav-tab").eq(1).trigger('click');
    });
    
    $(".slide-back").click(function(event){
        event.preventDefault();
        $("#slide-shop_type").hide();
        $("#slide-payment_type").hide();
        $("#slide-product_type").hide();
        $($(this).attr('data-slide')).show();
    });
    
    
    // Hide and show options depending on chosen Shop, Payment and Product types:
    var refreshShopType = function(){
        var shop_type = $("#section-shop_type input:checked").val();
        switch (shop_type)
        {
            case "sell":
                $("#section-product_name").show();
                $("#section-price").show();
                $("#section-offer_details").show();
                $("#section-external_url").hide();
                $("#section-file_to_download").show();
                $("#section-video_id").show();
                $("#section-orderform_cta_button").show();
                $("#section-thankyou_message").show();
                $("#section-optin, #section-optin_text").hide();
                break;
            case "link":
                $("#section-product_name").hide();
                $("#section-price").hide();
                $("#section-offer_details").hide();
                $("#section-external_url").show();
                $("#section-file_to_download").hide();
                $("#section-video_id").hide();
                $("#section-orderform_cta_button").hide();
                $("#section-thankyou_message").hide();
                $("#section-optin, #section-optin_text").hide();
                break;
        }
    };
    var refreshPaymentType = function(){
        var payment_type = $("#section-payment_type input:checked").val();
        switch (payment_type)
        {
            case "free":
                $("#of-option-paypal-tab").hide();
                $("#of-option-orderformfields-tab").hide();
                $("#section-optin, #section-optin_text").hide();
                break;
            case "share":
                $("#of-option-paypal-tab").hide();
                $("#of-option-orderformfields-tab").hide();
                $("#section-optin, #section-optin_text").hide();
                break;
            case "contact":
                $("#of-option-paypal-tab").hide();
                $("#of-option-orderformfields-tab").show();
                $("#of-option-orderformfields-tab").text('Contact Capture Fields');
                $("#section-optin, #section-optin_text").show();
                break;
            case "paypal":
                $("#of-option-paypal-tab").show();
                $("#of-option-orderformfields-tab").show();
                $("#section-optin, #section-optin_text").show();
                break;
        }
    };
    var refreshProductType = function(){
        var product_type = $("#section-product_type input:checked").val();
        switch (product_type)
        {
            case "file":
                $("#section-file_to_download").show();
                $("#section-video_id").hide();
                break;
            case "video":
                $("#section-file_to_download").hide();
                $("#section-video_id").show();
                break;
            case "physical":
                $("#section-file_to_download").hide();
                $("#section-video_id").hide();
                $("#of-option-orderformfields-tab").show();
                $("#of-option-orderformfields-tab").text('Addressee Fields');
                break;
        }
    };
    
    // Also run these functions on document load:
    refreshShopType();
    refreshPaymentType();
    refreshProductType();
    
    // For now, always hide PayPal:
    $("#of-option-paypal-tab").hide();
    
    
    /*-----------------------------------------------------------------------------------*/
    /* Facebook Options
    /*-----------------------------------------------------------------------------------*/
    
    // "Add to Facebook Page" helper
    (function(){
        var markup = '<div class="section" id="section-facebook_add_to_page"><h4 class="heading">Add your Popshop page tab to your Facebook page</h4><a href="" class="button" id="add_to_fb_page">Add to page</a><p>Please fill in your Facebook application credentials, save options, then click on "Add to page".</p></div>';
        
        $("#section-facebook_app_secret").after(markup);
        
        $("#add_to_fb_page").click(function(event){
            event.preventDefault();
            if ($("#facebook_app_id").val() == "") {
                $("#section-facebook_add_to_page").append('<p>You need to copy and paste your Facebook application credentials first.</p>');
            }
            else {
                // Redirect to Facebook:
                window.location = 'https://www.facebook.com/dialog/pagetab?app_id=' + $("#facebook_app_id").val() + '&display=popup&next=' + encodeURIComponent($("#popshop_settings_url").text());
                // We could use document.URL, but it feels cleaner to retrieve the Settings page URL from PHP
            }
        });
    })();
    
    // After adding page tab, retrieve page id(s) from Facebook
    // (We don't use them right now, though)
    // @see Facebook tabs_added
    (function(){
        // Yes... I was too lazy to do it myself: 
        // @see http://stackoverflow.com/questions/9927465/javascript-regex-and-non-capturing-parentheses
        // (Example) /admin.php?page=popshop-settings&tabs_added[114787535263592]=1&tabs_added[217770811582323]=1&tabs_added[198738186831542]=1
        var regexp = /tabs_added\[(\d+)\]/g;
        var pageIds = [], match;
        while (match = regexp.exec(decodeURIComponent(window.location.search))) {
            pageIds.push(match[1]);
        }
        
        // User is not guaranteed to add to his page from here, but this is not used for anything critical right now (only for reporting stats), so it's ok.
        if (pageIds.length) {
            console.log(pageIds.join(","));
            
            if ($("#facebook_page_ids").val() == "") {
                $("#facebook_page_ids").val(pageIds.join(","));
            }
            else {
                // Merge Page Ids to the existing ones
                $("#facebook_page_ids").val($("#facebook_page_ids").val().split(",").concat(pageIds).join(","));
            }
            
            // Insert Page pictures, just as visual cues:
            for (var page in pageIds) {
                $("#section-facebook_add_to_page").append('<img src="http://graph.facebook.com/' + pageIds[page] + '/picture" />');
            }
        }
    })();
    
    
    
    
    /*-----------------------------------------------------------------------------------*/
    /* Custom Order Form Fields
    /*-----------------------------------------------------------------------------------*/

    
    // Order Form Fields
    // @todo: Abstract all this to a library?
    // Also @see https://github.com/botskonet/jquery.formbuilder
    
    
    // Hide the corresponding text input:
    $("#orderform_fields").hide();
    
    // Create boilerplate for our "drag'n'drop editor":
    $("#orderform_fields").after('<div id="orderform_fields_editor"><div id="orderform_fields_editor_sortable"></div></div>');
    
    var refreshFormFields = function() {
        // First, decode object from the Options Framework JSON-stored text input:
        var fields = $.parseJSON($("#orderform_fields").val());
        // Make sure fields is not null:
        fields = fields || [];
        
        // Clear the fields editor:
        $("#orderform_fields_editor_sortable").html("");
        
        // Create an input element for each desired field:
        $.each(fields, function(index, field) {
            var e = $('<div class="sortable"><input type="text"><span class="field-type"></span><span class="delete"></span></div>');
            if (field.type == "text") {
                $('input', e).addClass("text").val(field.placeholder);
                $('.field-type', e).text("Input Field");
            }
            else if (field.type == "email") {
                $('input', e).addClass("email").val(field.placeholder);
                $('.field-type', e).text("Input Field");
                // You can't delete the email field (it has a special role in the admin panel):
                $('.delete', e).remove();
            }
            else if (field.type == "h3") {
                $('input', e).addClass("h3").val(field.content);
                $('.field-type', e).text("Title").css("font-weight", "bold");
            }
            $("#orderform_fields_editor_sortable").append(e);
        });
        // Make them sortable:
        $("#orderform_fields_editor_sortable").sortable();
    };
    
    // Do it a first time on page load:
    refreshFormFields();
    
    // Handle input field deletion:
    // (Make it "live" so it also works for newly created fields and titles)
    $("#orderform_fields_editor span.delete").live('click', function(){
        $(this).closest("div.sortable").fadeOut('fast', function(){
            $(this).remove();
        });
    });
    
    // Enable adding new input fields:
    $("#orderform_fields_editor").append('<div class="add"><a id="orderform_fields_addfield" href="#">Add Field</a><a id="orderform_fields_addtitle" href="#">Add Title</a></div>');
    $("#orderform_fields_addfield").click(function(event){
        event.preventDefault();
        // This could be refactored:
        $("#orderform_fields_editor_sortable").append('<div class="sortable"><input type="text" class="text"><span class="field-type">Input Field</span><span class="delete"></span></div>');
    });
    $("#orderform_fields_addtitle").click(function(event){
        event.preventDefault();
        // This could be refactored:
        $("#orderform_fields_editor_sortable").append('<div class="sortable"><input type="text" class="h3"><span class="field-type" style="font-weight:bold;">Title</span><span class="delete"></span></div>');
    });
    
    // Finally, hook into the Options Framework form submission, parse the Field editor's DOM, and save object to JSON:
    $("#optionsframework form").submit(function(){
        var fields = [];
        $("#orderform_fields_editor_sortable input").each(function(){
            if ($(this).attr('class') == "text") {
                var cleanedUpName = $(this).val().toLowerCase().replace(/[^a-zA-Z0-9]/g, "");
                cleanedUpName = 'customer[' + cleanedUpName + ']';
                if (cleanedUpName.length) {
                    fields.push({"type":"text", "placeholder":$(this).val(), "name":cleanedUpName});
                }
            }
            else if ($(this).attr('class') == "email") {
                fields.push({"type":"email", "placeholder":$(this).val(), "name":"email"});
            }
            else if ($(this).attr('class') == "h3") {
                fields.push({"type":"h3", "content":$(this).val()});
            }
        });
        $("#orderform_fields").val(JSON.stringify(fields));
        
        return true;
    });
    
    
    // Example Form Fields Sets:
    $(".orderform_fields_example").click(function(event){
        event.preventDefault();
        var example = $(this).attr('data-example');
        $("#orderform_fields").val($("#orderform_fields_"+example).text());
        refreshFormFields();
    });
    
    
    
    /*-----------------------------------------------------------------------------------*/
    /* Cover Slider / Video Cover
    /*-----------------------------------------------------------------------------------*/
    
    // Cover Slider tab:
    // First, create two "cover type" divs and move all corresponding options inside:
    $("#of-option-slidersettings").append('<div id="section-slider"></div>');
    $("#of-option-slidersettings").append('<div id="section-covervideo"></div>');
    $("div.section[id^='section-slider_']").appendTo($("#section-slider"));
    $("div.section[id^='section-covervideo_']").appendTo($("#section-covervideo"));
    
    // Then, hide or show the cover types sections when clicking the corresponding radio button:
    $("#popshop-cover_type-slider").click(function(){
        $("#section-slider").show();
        $("#section-covervideo").hide();
    });
    $("#popshop-cover_type-covervideo").click(function(){
        $("#section-slider").hide();
        $("#section-covervideo").show();
    });
    
    // Finally, simulate a click on the correct radio button at page load:
    $("#section-cover_type input:checked").click();
    
    // Inside the Image Slider subset of options:
    if ($("#slider_mono_caption:checked").length) {
        $("#section-slider_caption_1, #section-slider_caption_2, #section-slider_caption_3, #section-slider_caption_4, #section-slider_caption_5").hide();
    }
    else {
        $("#section-slider_caption").hide();
    }
    $("#slider_mono_caption").click(function(){
        $("#section-slider_caption").toggle();
        $("#section-slider_caption_1, #section-slider_caption_2, #section-slider_caption_3, #section-slider_caption_4, #section-slider_caption_5").toggle();
    });
    
    
});

