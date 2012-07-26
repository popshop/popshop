// @see http://afarkas.github.com/webshim/demos/
// should be called as soon as possible (before DOM ready)
jQuery.webshims.polyfill('forms');


// @see http://stackoverflow.com/questions/1184624/convert-form-data-to-js-object-with-jquery
// @see http://stackoverflow.com/a/8407771/593036
// Changed name from `toJSON` to `serializeObject`
(function(a){a.fn.serializeObject=function(b){b=a.extend({},b);var c=this,d={},e={},f={validate:/^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,key:/[a-zA-Z0-9_]+|(?=\[\])/g,push:/^$/,fixed:/^\d+$/,named:/^[a-zA-Z0-9_]+$/};this.build=function(a,b,c){a[b]=c;return a};this.push_counter=function(a){if(e[a]===undefined){e[a]=0}return e[a]++};a.each(a(this).serializeArray(),function(){if(!f.validate.test(this.name)){return}var b,e=this.name.match(f.key),g=this.value,h=this.name;while((b=e.pop())!==undefined){h=h.replace(new RegExp("\\["+b+"\\]$"),"");if(b.match(f.push)){g=c.build([],c.push_counter(h),g)}else if(b.match(f.fixed)){g=c.build([],b,g)}else if(b.match(f.named)){g=c.build({},b,g)}}d=a.extend(true,d,g)});return d}})(jQuery)


var Popshop;

jQuery(document).ready(function($){
    
    Popshop = {
        shared: false,
        viewed: false,
        facebook: null,
        shop_type: null,
        payment_type: null,
        product_type: null,
        paypal_process: null,
        paypal_url: null,
        init: function() {
            // Are we on Facebook:
            if ($("body.facebook").length) {
                Popshop.facebook = true;
            }
            else {
                Popshop.facebook = false;
            }
            Popshop.shop_type    = $("#shop_type").text();
            Popshop.payment_type = $("#payment_type").text();
            Popshop.product_type = $("#product_type").text();
        },
        resizeMesh: function() {
            $("#mesh").height($(document).height()+1000);
        },
        insertObject: function(table, name, details, success) {
            $.post($('#ajax_url').text(),
                   {'table': table, 'name': name, 'details': JSON.stringify(details)},
                   success);
        },
        insertIntent: function(name, details) {
            // @todo: Review this.
            if (typeof details == 'undefined') {
                details = new Object();
            }
            details.facebook = Popshop.facebook;
            Popshop.insertObject('intent', name, details, null);
        },
        insertOrder: function(details, success) {
            Popshop.insertObject('order', 'order', details, success);
        }
    }
    
    Popshop.init();
    
    
    
    if (!Popshop.facebook) {
        /* Specifics when viewed Outside Facebook: */
        
        if ($("#background_image").text()) {
            
            $("html").addClass("supersized");
            
            $.supersized({
                // slide_interval: 3000, transition: 'fade', transition_speed: 1000,
                slides : [{ image: $("#background_image").text() }]
            });
            
            // Resize mesh to cover the whole page even if browser does not support position:fixed (iOS).
            // Add a few pixels, just to be safe.
            Popshop.resizeMesh();
            $(window).resize(function(){
                Popshop.resizeMesh();
            });
        }
    }
    
    
    /* Navigation menu and Terms & Conditions*/
    
    $(".navmenu li.menu-item a, a.linktoterms").click(function(event){
        // For navigation menu items that are internal links, display them on the same page via AJAX.
        event.preventDefault();
        if ($(this).hasClass("active")) {
            // If this link was already open, close it.
            $("#morecontent").hide();
            $(".navmenu li.menu-item a, a.linktoterms").removeClass("active");
        }
        else {
            $.get($(this).attr("href"), function(data){
                $("#morecontent").show();
                $("#morecontent-inner").html(data);
            });
            // Set this link to "active"
            $(".navmenu li.menu-item a, a.linktoterms").removeClass("active");
            $(this).addClass("active");
        }
    });
    
    $("#morecontent div.delete").click(function(){
        $("#morecontent").hide();
        $(".navmenu li.menu-item a, a.linktoterms").removeClass("active");
    });
    
    $(".popshop-fb-share").click(function(event){
        event.preventDefault();
        FB.ui({ method: 'feed',
                link: $("meta[property='og:url']").attr("content"),
                picture: $("meta[property='og:image']").attr("content")
              });
    });
    
    $(".popshop-fb-send").click(function(event){
        event.preventDefault();
        FB.ui({ method:'send',
                link: $("meta[property='og:url']").attr("content"),
                picture: $("meta[property='og:image']").attr("content")
              });
    });
    
    
    
    
    $(".flexslider").flexslider({ animation:      $("#slider_animation").text(),
                                  slideshowSpeed: $("#slider_animation_speed").text()
    });
    
    
    
    
    
    /* Main UI logic */
    
    $("#main-cta").click(function(){
        if (Popshop.payment_type == "share" && !Popshop.shared) {
            // Share Gate activated and user has not shared yet.
            $("#morecontent").show();
            return;
        }
        $("#morecontent").hide();
        if (Popshop.shop_type == "link") {
            top.location = $("#external_url").text();
        }
        else {
            $("#main-callToActionWrapper").hide();
            $("#orderform").show();
        }
    });
    
    $("#orderform div.delete").click(function(){
        $("#orderform").hide();
        $("#main-callToActionWrapper").show();
    });
    
    $("#orderform-cta").click(function(event){
        // In order to use HTML5 Form validation, we don't hook on the button's click, 
        // but rather on the form's submit event (cf. below)
    });
    
    $("#orderform-form").submit(function(event){
        event.preventDefault();
        if (Popshop.paypal_url != null) {
            top.location = Popshop.paypal_url;
        }
        else {
            var data = $("#orderform input").serializeObject();
            // Add payment_type to this object:
            data.payment = {type: Popshop.payment_type};
            Popshop.insertOrder(data, function(code){
                if (code != "-1") {
                    // Order insert was a success.
                    if (Popshop.payment_type == "paypal") {
                        $("#orderform-form").hide();
                        // Find out paypal_url:
                        var paypal_url = null;
                        if (Popshop.facebook) {
                            // @see http://facebook.stackoverflow.com/questions/9585234/find-out-page-tab-url-from-page-id-and-application-id
                            paypal_url = "http://facebook.com/pages/-/" + $("#facebook_page") + "?sk=app_" + $("#facebook_app_id").text();
                        }
                        else {
                            // Don't specify URL in that case.
                        }
                        $.post(
                            $('#ajax_url_paypal').text(),
                            {'order': code, 'paypal_url': paypal_url},
                            function(url){
                                Popshop.paypal_url = url;
                                // Turn all text input fields into <div>s:
                                $("#orderform-form input[type=text]").each(function(index, element){
                                    $(this).attr('disabled', 'disabled');
                                });
                                // Change button text to "Pay with PayPal":
                                $("#orderform-cta .uiButtonText").text("Pay with PayPal");
                                $("#orderform-form").show();
                            }
                        );
                    }
                    else {
                        $("#thankyou_order_id").text(code);
                        $("#orderform").hide();
                        $("#thankyouform").show();
                        
                        // Send confirmation version of thank you message
                        
                        if ( ($('#email_confirmation').text() == 1) && ($('input[name=email]').val()) ) { 
                       
                        $.post($('#ajax_url').text(), { 
                        	'action' : 'confirmation_email', 
                        	'email' : $('input[name=email]').val(), 
                        	'subject' : $('#email_confirmation_subject').text(), 
                        	'title' : $("#thankyouform h2").text()
                        	'message' : $("#thankyoumessage").html(),
                        	'order_id' : $("#orderid").text(),
                        	'link_url' : $("#file_to_download").text()
                          
	                    });
	                    
	                    }
                        
                        if ($("#file_to_download").text()) { 
                        
                        parent.window.location = $("#file_to_download").text();
                        
                        }
                        
                    }
                }
            });
        }
        return false;
    });
    
    
    $("#file_to_download-cta").click(function(){
        parent.window.location = $("#file_to_download").text();  
    });
    
    
});







/* EVENTS & INTENTS */


// @see http://developers.facebook.com/docs/reference/javascript/FB.Event.subscribe/
window.fbAsyncInit = function() {
    FB.init({
        appId  : jQuery('#facebook_app_id').text(), // App ID
        status : true, // check login status
        cookie : true, // enable cookies to allow the server to access the session
        xfbml  : true  // parse XFBML
    });
    FB.Canvas.setAutoGrow();
    FB.Event.subscribe('edge.create',
        function(response) {
            Popshop.shared = true;
            Popshop.insertIntent('like');
        }
    );
};

// @see https://dev.twitter.com/docs/intents/events
// Should we add an option to choose a Follow or a Retweet button instead of a Tweet button?
function popshop_tweet(event) {
    Popshop.shared = true;
    Popshop.insertIntent('tweet', event.data);
    $('#main-cta').click();
    // Unfortunately, event.data is empty
    // @see http://dev.twitter.com/discussions/304
}

// @see https://developers.google.com/+/plugins/+1button/
function popshop_plusone(data) {
    Popshop.shared = true;
    Popshop.insertIntent('plusone');
    jQuery('#main-cta').click();
}

// @see https://developer.linkedin.com/share-plugin-reference
// @see https://developer.linkedin.com/forum/basic-event-support-share-button
function popshop_linkedin() {
    Popshop.shared = true;
    Popshop.insertIntent('linkedin');
    jQuery('#main-cta').click();
}

// Fake event support for the "Pin it" button,
// until Pinterest adds support like implemented by the other sharing buttons.
jQuery(document).ready(function($){
    $(".pinitfix").hover(function(){
        Popshop.shared = true;
        jQuery('#main-cta').click();
        // Popshop.insertIntent('pinit');
        // Commented out because it's not reliable at all.
    });
});

// @see https://developers.google.com/youtube/iframe_api_reference
var player;
function onYouTubePlayerAPIReady() {
    player = new YT.Player('player', {
        events: {
            'onStateChange': onPlayerStateChange
        }
    });
}
function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING) {
        Popshop.viewed = true;
        Popshop.insertIntent('youtube');
    }
}






