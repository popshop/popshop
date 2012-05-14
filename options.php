<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {
    
    // This gets the theme name from the stylesheet (lowercase and without spaces)
    $themename = get_option( 'stylesheet' );
    $themename = preg_replace("/\W/", "_", strtolower($themename) );
    
    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);
    
    // echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
    
    $options = array();
    
    
    
    /* New Tab */
    
    $options[] = array("name" => "Getting Started",
                       "type" => "heading");
    
    $options[] = array("desc" => popshop_gettingstarted_page(),
					   "type" => "info");
    
    $options[] = array("name" => "Product Type",
                       "id" => "product_type",
                       "class" => "hidden",
                       "std" => '',
                       "type" => "radio",
                       "options" => array('file'     => 'file', 
                                          'video'    => 'video',
                                          'physical' => 'physical',
                                          'link'     => 'link',
                                          ''         => 'Not selected yet'));
    
    $options[] = array("name" => "Payment Type",
                       "id" => "payment_type",
                       "class" => "hidden",
                       "std" => '',
                       "type" => "radio",
                       "options" => array('free'    => 'free', 
                                          'share'   => 'share',
                                          'contact' => 'contact',
                                          'paypal'  => 'paypal',
                                          ''        => 'Not selected yet'));
    
    // This is just to pass to the JS a reference to this very page's URL:
    $options[] = array("desc" => '<div id="popshop_settings_url">'.admin_url('admin.php?page=popshop-settings').'</div>',
                       "class" => "hidden",
					   "type" => "info");
    
    
    
    /* New Tab */
    
    $options[] = array("name" => "Basic Settings",
                       "type" => "heading");
    
    $options[] = array("name" => "Header",
                       "desc" => "Your Popshop's Header.",
                       "id" => "header",
                       "std" => "Your product or service",
                       "type" => "text");
    
    $options[] = array("name" => "Sub-Header",
                       "desc" => "Your Sub-Header.",
                       "id" => "subheader",
                       "std" => "Popup Shop",
                       "type" => "text");
    
    $options[] = array("name" => "Logo",
                       "desc" => "Your logo image (160x160px recommended).",
                       "id" => "logo",
                       "type" => "upload");
    
    $options[] = array("name" => "Product or service name",
                       "desc" => "Your product or service's name.",
                       "id" => "product_name",
                       "std" => "Your product name",
                       "type" => "text");
    
    
    $options[] = array("name" => "File to Download",
                       "desc" => "Your downloadable file.",
                       "id" => "file_to_download",
                       "type" => "upload");
    
    $options[] = array("name" => "Your Video's Youtube ID",
                       "desc" => "Paste here your video ID from Youtube.",
                       "id" => "video_id",
                       "std" => "zK4OpiLmReQ",
                       "type" => "text");
    
    $options[] = array("name" => "External URL Redirect",
                       "desc" => "The external URL you want to redirect to.",
                       "id" => "external_url",
                       "std" => "http://getpopshop.com/",
                       "type" => "text");
    
    
    
    $options[] = array("name" => "Price",
                       "desc" => "A text string representing your price.",
                       "id" => "price",
                       "std" => "$49",
                       "class" => "mini",
                       "type" => "text");
    
    $options[] = array("name" => "Offer Details",
                       "desc" => "Detail your offer (shipping info, product details, etc.) here.",
                       "id" => "offer_details",
                       "std" => "PRODUCT INFO\n\nSkateboard cardigan terry richardson, biodiesel pariatur mumblecore aliqua gluten-free qui vegan dolore single-origin coffee. Pour-over marfa truffaut id jean shorts velit, Austin high life butcher stumptown adipisicing. Adipisicing tattooed butcher pork belly, gentrify quinoa semiotics hoodie raw denim quis typewriter voluptate. Eiusmod viral fanny pack, pitchfork sriracha pork belly cred mollit vinyl terry richardson semiotics occaecat. Trust fund shoreditch laboris, skateboard pop-up labore irure enim squid. Adipisicing american apparel keytar, duis esse kogi four loko next level. Wes anderson fingerstache twee ethnic odio.\n\nSHIPPING INFO\n\nShips to: 50 US States only\n\nEstimated arrival: 10-17 days\n\nReturns Policy: Item is final sale and not eligible for return. Statutory rights unaffected.",
                       "type" => "textarea");
    
    
    $options[] = array("name" => "Front page Call-to-action",
                       "desc" => "Text for the Front page's main call-to-action.",
                       "id" => "main_cta_text",
                       "std" => "Fan Special! Exclusive limited time offer -30% off.",
                       "type" => "textarea");
    
    $options[] = array("name" => "Front page Button",
                       "desc" => "Text for the Front page's main call-to-action button.",
                       "id" => "main_cta_button",
                       "std" => "Get This Offer",
                       "class" => "mini",
                       "type" => "text");
    
    $options[] = array("name" => "Order form Button",
                       "desc" => "Text for the Order Form page's main call-to-action button.",
                       "id" => "orderform_cta_button",
                       "std" => "Get Now",
                       "class" => "mini",
                       "type" => "text");
    
    $options[] = array("name" => "Thank you Message",
                       "desc" => "Text to display after the order has been confirmed.",
                       "id" => "thankyou_message",
                       "std" => "Thank you for your order!",
                       "type" => "textarea");
    
    
    
    /* New Tab */
    
    $options[] = array("name" => "Cover Slider",
                       "type" => "heading");
    
    $options[] = array("name" => "Cover Type",
                       "desc" => "Which type of Cover do you want: an image slider, or an embedded video?",
                       "id" => "cover_type",
                       "class" => "greybg",
                       "std" => "slider",
                       "type" => "radio",
                       "options" => array("slider"     => "Image Slider", 
                                          "covervideo" => "Embedded Video"));
    
    $options[] = array("name" => "Slider Caption",
                       "desc" => "Use the same caption for every Slider image",
                       "id" => "slider_mono_caption",
                       "std" => "1",
                       "type" => "checkbox"); /* This is actually only used in the UI logic (hide or show input fields), not as an actual option. */
    
    $options[] = array("name" => "Slider Caption",
                       "desc" => "Text to overlay on all slider images.",
                       "id" => "slider_caption",
                       "type" => "text");
    
    $options[] = array("name" => "Slider Image #1",
                       "desc" => "Add slider image here (810x315px).",
                       "id" => "slider_image_1",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #1",
                       "desc" => "Text to overlay on Slider Image #1.",
                       "id" => "slider_caption_1",
                       "type" => "text");
    
    $options[] = array("name" => "Slider Image #2",
                       "desc" => "Add slider image here (810x315px).",
                       "id" => "slider_image_2",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #2",
                       "desc" => "Text to overlay on Slider Image #2.",
                       "id" => "slider_caption_2",
                       "type" => "text");
    
    $options[] = array("name" => "Slider Image #3",
                       "desc" => "Add slider image here (810x315px).",
                       "id" => "slider_image_3",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #3",
                       "desc" => "Text to overlay on Slider Image #3.",
                       "id" => "slider_caption_3",
                       "type" => "text");
    
    $options[] = array("name" => "Slider Image #4",
                       "desc" => "Add slider image here (810x315px).",
                       "id" => "slider_image_4",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #4",
                       "desc" => "Text to overlay on Slider Image #4.",
                       "id" => "slider_caption_4",
                       "type" => "text");
    
    $options[] = array("name" => "Slider Image #5",
                       "desc" => "Add slider image here (810x315px).",
                       "id" => "slider_image_5",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #5",
                       "desc" => "Text to overlay on Slider Image #5.",
                       "id" => "slider_caption_5",
                       "type" => "text");
    
    $options[] = array("name" => "Slider Effect",
                       "desc" => "Which animation type do you want, Fade (default) or Slide?",
                       "id" => "slider_animation",
                       "std" => "fade",
                       "type" => "radio",
                       "options" => array("fade"  => "Fade", 
                                          "slide" => "Slide"));
    
    $options[] = array("name" => "Caption Style",
                       "desc" => "Which caption style do you want: Dark (default - works best for dark or medium pictures) or Light (works best for lighter pictures)",
                       "id" => "slider_caption_style",
                       "std" => "dark-caption",
                       "type" => "radio",
                       "options" => array("dark-caption"  => "Dark", 
                                          "light-caption" => "Light"));
    
    
    $options[] = array("name" => "Your Cover Video's Youtube ID",
                       "desc" => "Paste here your video ID from Youtube.",
                       "id" => "covervideo_id",
                       "std" => "5Giw8rKT4Dg",
                       "type" => "text");
    
    $options[] = array("name" => "Cover Video Background image",
                       "desc" => "Add a background image to your Cover video - Default is a theater stage (810x315px).",
                       "id" => "covervideo_image",
                       "type" => "upload");
    
    
    
    /* New Tab */
    
    $options[] = array("name" => "Order Form Fields",
                       "type" => "heading");
    
    $options[] = array("name" => "Order Form Fields Generator",
                       "desc" => 'Those are the fields that your customers will have to fill in on the Order Form. You can customize them and reorder them to fit your use case best. Only the Email field is required. You can check <a href="#" class="orderform_fields_example" data-example="physical">this example</a> for a Physical Products, or <a href="#" class="orderform_fields_example" data-example="contact">this one</a> for "Pay with a Lead"-type form.',
                       "id" => "orderform_fields",
                       "std" => '[{"type":"h3","content":"Your Details"},{"type":"text","placeholder":"First Name","name":"customer[firstname]"},{"type":"text","placeholder":"Last Name","name":"customer[lastname]"},{"type":"email","placeholder":"Email Address","name":"email"},{"type":"h3","content":"Shipping Details"},{"type":"text","placeholder":"Address","name":"customer[address]"},{"type":"text","placeholder":"City","name":"customer[city]"},{"type":"text","placeholder":"Post Code","name":"customer[postcode]"},{"type":"text","placeholder":"Country","name":"customer[country]"}]',
                       "type" => "textarea");
    
    $options[] = array("desc" => '<div id="orderform_fields_physical">[{"type":"h3","content":"Your Details"},{"type":"text","placeholder":"First Name","name":"customer[firstname]"},{"type":"text","placeholder":"Last Name","name":"customer[lastname]"},{"type":"email","placeholder":"Email Address","name":"email"},{"type":"h3","content":"Shipping Details"},{"type":"text","placeholder":"Address","name":"customer[address]"},{"type":"text","placeholder":"City","name":"customer[city]"},{"type":"text","placeholder":"Post Code","name":"customer[postcode]"},{"type":"text","placeholder":"Country","name":"customer[country]"}]</div>',
                       "class" => "hidden",
					   "type" => "info");
    
    $options[] = array("desc" => '<div id="orderform_fields_contact">[{"type":"h3","content":"Your Details"},{"type":"text","placeholder":"First Name","name":"customer[firstname]"},{"type":"text","placeholder":"Last Name","name":"customer[lastname]"},{"type":"email","placeholder":"Email Address","name":"email"},{"type":"h3","content":"Your contact Info"},{"type":"text","placeholder":"Company","name":"customer[company]"},{"type":"text","placeholder":"Position","name":"customer[position]"}]</div>',
                       "class" => "hidden",
					   "type" => "info");
    
    
    
    
    /* New Tab */
    
    $options[] = array("name" => "Advanced Settings",
                       "type" => "heading");
    
    $options[] = array("name" => "Background Image",
                       "desc" => "Background Image that will be stretched to Fullscreen when your Popshop is viewed outside Facebook.",
                       "id" => "background_image",
                       "type" => "upload");
    
    $options[] = array("name" => "Background Image Raster Effect",
                       "desc" => "Do you want to apply a raster effect on your Background Image?",
                       "id" => "background_raster",
                       "std" => '0',
                       "type" => "radio",
                       "options" => array('0' => 'Off', 
                                          '1' => 'Light',
                                          '2' => 'Dark'));
    
    
    $options[] = array("name" => "Share Buttons",
                       "desc" => "Select which Sharing Buttons you want to enable.",
                       "id" => "share_buttons",
                       "std" => array("like" => "1", "tweet" => "1", "plusone" => "1", "pinit" => "1", "linkedin" => "1"),
                       "type" => "multicheck",
                       "options" => array("like" => "Like", "tweet" => "Tweet", "plusone" => "Google +1", "pinit" => "Pin It", "linkedin" => "LinkedIn Share"));
    
    
    $options[] = array("name" => "Video embed default code",
                       "desc" => "This is the default embed code from Youtube. Only modify if you know what you're doing!",
                       "id" => "video_embed_code",
                       "std" => '<iframe id="player" width="378" height="222" src="http://www.youtube.com/embed/VIDEO_ID?wmode=opaque&rel=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe>',
                       "type" => "textarea");
    
    // @see http://stackoverflow.com/questions/3820325/overlay-opaque-div-over-youtube-iframe 
    // @see http://stackoverflow.com/questions/886864/differences-between-using-wmode-transparent-opaque-or-window-for-an-embe
    // opaque is less resource-intensive than transparent 
    
    
    $options[] = array("name" => "Custom CSS",
                       "desc" => "Any custom CSS you paste here will be added to your Popshop.",
                       "id" => "custom_css",
                       "std" => "",
                       "type" => "textarea");
    
    $options[] = array("name" => "Google Analytics tracking script",
                       "desc" => "Add your Google Analytics tracking code script here (including the <script></script> tags).",
                       "id" => "custom_script",
                       "std" => "",
                       "type" => "textarea");
    
    $options[] = array("name" => "Hide Popshop link in the Navigation menu",
                       "desc" => "Check this box if you wish not to display the Popshop link in your Navigation menu.",
                       "id" => "hide_popshop_link",
                       "std" => "0",
                       "type" => "checkbox");
    
    $options[] = array("name" => "Stats Reporting",
                       "desc" => "Popshop aggregates anonymized stats, which enable us to know how many sites are running Popshop and how their users are interacting with it. 
                                  This data will help us decide how to best evolve the project and prioritize new developments.
                                  To opt-out, please check this box.",
                       "id" => "stats_report_optout",
                       "std" => "0",
                       "type" => "checkbox");
    
    
    
    
    
    /* New Tab */
    
    $options[] = array("name" => "Facebook",
                       "type" => "heading");
    
    
    $options[] = array("desc" => 'Please read the documentation about Facebook integration <a href="http://getpopshop.com/documentation#facebook" target="_blank">here</a>.',
					   "type" => "info");
    
    $options[] = array("name" => "Your Facebook application ID",
                       "desc" => "Please enter your app ID from <a href='https://developers.facebook.com/apps/' target='_blank'>Facebook Developers</a> here.",
                       "id" => "facebook_app_id",
                       "std" => "",
                       "class" => "mini",
                       "type" => "text");
    
    $options[] = array("name" => "Your Facebook application secret key",
                       "desc" => "Please enter your secret key from <a href='https://developers.facebook.com/apps/' target='_blank'>Facebook Developers</a> here.",
                       "id" => "facebook_app_secret",
                       "std" => "",
                       "class" => "mini",
                       "type" => "text");
    
    $options[] = array("name" => "",
                       "desc" => "",
                       "id" => "facebook_page_ids",
                       "std" => "",
                       "class" => "hidden",
                       "type" => "text");
    
    $options[] = array("name" => "Image shared on Facebook",
                       "desc" => "Specify Image to be shared on Facebook when users like your Popshop (defaults to your logo).",
                       "id" => "facebook_image",
                       "type" => "upload");
    
    
    
    /* New Tab */
    
    $options[] = array("name" => "Paypal",
                       "type" => "heading");
    
    
    $options[] = array("name" => "Amount",
                       "desc" => "Product price, tax included.",
                       "id" => "paypal_amount",
                       "std" => "20",
                       "class" => "mini",
                       "type" => "text");
    
    $options[] = array("name" => "Currency",
                       "desc" => "Which currency do you want to use",
                       "id" => "paypal_currency",
                       "std" => 'EUR',
                       "type" => "select",
                       "class" => "mini",
                       "options" => array('EUR' => 'EUR',
                                          'GBP' => 'GBP',
                                          'USD' => 'USD'));
    
    $options[] = array("name" => "API Username",
                       "desc" => "Your Paypal API credentials from <a href='https://developer.paypal.com' target='_blank'>https://developer.paypal.com</a>",
                       "id" => "paypal_username",
                       "std" => "",
                       "type" => "text");
    
    $options[] = array("name" => "API Password",
                       "id" => "paypal_password",
                       "std" => "",
                       "type" => "text");
    
    $options[] = array("name" => "API Signature",
                       "id" => "paypal_signature",
                       "std" => "",
                       "type" => "text");
    
    $options[] = array("name" => "Sandbox",
                       "desc" => "Use Paypal's sandbox?",
                       "id" => "paypal_sandbox",
                       "std" => "0",
                       "type" => "checkbox");
    
    
    
    // Popshop Extensibility:
    // (Plugins to Popshop can add theme options using this filter)
    $options = apply_filters('popshop_options', $options);
    
    
    return $options;
}

