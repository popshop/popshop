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
    
    $options[] = array("name" => "Shop Type",
                       "id" => "shop_type",
                       "class" => "hidden",
                       "std" => '',
                       "type" => "radio",
                       "options" => array('sell'    => 'sell', 
                                          'link'    => 'link',
                                          ''        => 'Not selected yet'));
    
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
    
    $options[] = array("name" => "Product Type",
                       "id" => "product_type",
                       "class" => "hidden",
                       "std" => '',
                       "type" => "radio",
                       "options" => array('file'     => 'file', 
                                          'video'    => 'video',
                                          'physical' => 'physical',
                                          ''         => 'Not selected yet'));
    
    
    
    // This is just to pass to the JS a reference to this very page's URL:
    $options[] = array("desc" => '<div id="popshop_settings_url">'.admin_url('admin.php?page=popshop-settings').'</div>',
                       "class" => "hidden",
					   "type" => "info");
    
    
    
    /* New Tab */
    
    $options[] = array("name" => "Basic Settings",
                       "type" => "heading");
    
    $options[] = array("name" => "Popshop Header",
                       "desc" => "Enter the name of your Popshop here.",
                       "id" => "header",
                       "std" => "Pop-up Social Sampling Store",
                       "type" => "text");
    
    $options[] = array("name" => "Popshop Sub-Header",
                       "desc" => "Enter the Sub-Header, such as a description of your Popshop here.",
                       "id" => "subheader",
                       "std" => "Get Free Samples for a Social Share",
                       "type" => "text");
    
    $options[] = array("name" => "Popshop Logo",
                       "desc" => "Your brand, product or event logo (160x160px recommended).",
                       "id" => "logo",
                       "type" => "upload");
    
    
    $options[] = array("name" => "Popshop Callout Box Text",
                       "desc" => "Enter your call to action here.",
                       "id" => "main_cta_text",
                       "std" => "Get an exclusive free preview sample in return for a social share!",
                       "type" => "textarea");
    
    $options[] = array("name" => "Popshop Callout Box Button Text",
                       "desc" => "Enter the button text for the callout box.",
                       "id" => "main_cta_button",
                       "std" => "Get This Offer",
                       "type" => "text");
    
    $options[] = array("name" => "Product Summary",
                       "desc" => "Enter short summary of the product being ordered. This will appear as the line item on the order form.",
                       "id" => "product_name",
                       "std" => "One fabulous sample",
                       "type" => "text");
    
    $options[] = array("name" => 'Product Summary Free Offer "Price"',
                       "desc" => 'Enter the "free" offer price to go next to the product summary on the order form. For example "free for a share", "free for a contact", or just "free"',
                       "id" => "price",
                       "std" => "FREE",
                       "type" => "text");
    
    $options[] = array("name" => "Product Description",
                       "desc" => "Enter product description (you can also add details of limitations, such as offer eligibility).",
                       "id" => "offer_details",
                       "std" => "PRODUCT DESCRIPTION\n\nAenean lacinia bibendum nulla sed consectetur.",
                       "type" => "textarea");
    
    $options[] = array("name" => "Order/Download Button Text",
                       "desc" => "Enter the button text for the order/download form.",
                       "id" => "orderform_cta_button",
                       "std" => "Order",
                       "type" => "text");
    
    
    $options[] = array("name" => "Popshop Callout Button URL Link",
                       "desc" => "Enter the URL of the web page that you want the button to link to.",
                       "id" => "external_url",
                       "std" => "http://getpopshop.com/",
                       "type" => "text");
    
    $options[] = array("name" => "File to Download",
                       "desc" => 'Upload the file (.pdf or .zip) to be downloaded here (upload and select "use this image" - you may have to select "File URL"). To increase maximum upload file size see <a href="http://wordpress.org/search/increase+file+size">http://wordpress.org/search/increase+file+size</a>',
                       "id" => "file_to_download",
                       "type" => "upload");
    
    $options[] = array("name" => "Unlisted (Hidden) YouTube Video to Stream",
                       "desc" => 'Enter the YouTube video ID of your unlisted (hidden) Youtube video, this is the code that appears after <code>v=</code> in the YouTube URL. For example: <code>www.youtube.com/watch?v=SfqpAWPx6T4</code>, the YouTube ID is <code>SfqpAWPx6T4</code>.<br><br>For details on how to upload unlisted YouTube videos, see <a href="http://support.google.com/youtube/bin/answer.py?hl=en&answer=181547">YouTube support</a>.',
                       "id" => "video_id",
                       "std" => "zK4OpiLmReQ",
                       "type" => "text");
    
    
    $options[] = array("name" => "Activate Opt-in Email Marketing?",
                       "desc" => "Select if you want to collect opt-ins for email marketing and set the opt-in question",
                       "id" => "optin",
                       "std" => '1',
                       "type" => "radio",
                       "options" => array('1' => 'Yes', 
                                          '0' => 'No'));
    
    $options[] = array("name" => "Opt-in Email Marketing Question",
                       "desc" => '',
                       "id" => "optin_text",
                       "std" => "Sign me up to receive news, exclusives and offers.",
                       "type" => "text");
    
    $options[] = array("name" => "Thank you Message",
                       "desc" => "Enter a custom thank you message (supports HTML tags) that will appear on the download or streaming page, or as a thank you message in the case of a sample to be sent out by mail. The message will automatically include the order ID and, where appropriate, the download/streaming link.",
                       "id" => "thankyou_message",
                       "std" => "<h2>Thank you</h2>\n\nThanks. We hope you enjoy your free offer.",
                       "type" => "textarea");
    
    
    /* New Tab */
    
    $options[] = array("name" => "Slider Settings",
                       "type" => "heading");
    
    $options[] = array("name" => "Slider Mode",
                       "desc" => "Do you want to showcase your product with a series of captioned images (slideshow mode) or with an embedded YouTube video?",
                       "id" => "cover_type",
                       "class" => "greybg",
                       "std" => "slider",
                       "type" => "radio",
                       "options" => array("slider"     => "Slideshow Mode", 
                                          "covervideo" => "Video Mode"));
    
    $options[] = array("name" => "Slide Transitions",
                       "desc" => "Select transition style for slides (if you have more than one slide)",
                       "id" => "slider_animation",
                       "std" => "fade",
                       "type" => "radio",
                       "options" => array("fade"  => "Fade", 
                                          "slide" => "Slide"));
    
    $options[] = array("name" => "Slide Transitions (Speed)",
                       "desc" => "Select transition speed for slides, in milliseconds (if you have more than one slide).",
                       "id" => "slider_animation_speed",
                       "std" => "7000",
                       "type" => "text");
    
    $options[] = array("name" => "Slide Caption Style",
                       "desc" => "Choose whether you want your caption background to be Dark (default, good for most pictures) or Light (good for light images). The default transparency is 50%. The CSS element for this is <code>.flex-caption</code>.",
                       "id" => "slider_caption_style",
                       "std" => "dark-caption",
                       "type" => "radio",
                       "options" => array("dark-caption"  => "Dark Background, Light Text", 
                                          "light-caption" => "Light Background, Dark Text"));
    
    $options[] = array("name" => "Slide Caption Position",
                       "desc" => "Select where you'd like to position the text on your slides. Top Full Width spans the top of the slides, Right Full length is a full length column flush right and 265 pixels wide. The CSS element for this is <code>.flex-caption</code>.",
                       "id" => "slider_caption_position",
                       "std" => "top",
                       "type" => "radio",
                       "options" => array("top"   => "Top Full Width", 
                                          "right" => "Right Full Length"));
    
    $options[] = array("name" => "Slider Caption",
                       "desc" => "Use the same caption for every Slider image",
                       "id" => "slider_mono_caption",
                       "std" => "1",
                       "type" => "checkbox"); /* This is actually only used in the UI logic (hide or show input fields), not as an actual option. */
    
    $options[] = array("name" => "Slider Caption",
                       "desc" => "Text to overlay on all slider images. Supports basic HTML: <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>.",
                       "id" => "slider_caption",
                       "type" => "textarea");
    
    $options[] = array("name" => "Slider Image #1",
                       "desc" => 'Add slider image here (810x315px). Larger images will be resized correctly if you select "medium" size on WordPress image uploader.',
                       "id" => "slider_image_1",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #1",
                       "desc" => "Text to overlay on Slider Image #1. Supports basic HTML: <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>.",
                       "id" => "slider_caption_1",
                       "type" => "textarea");
    
    $options[] = array("name" => "Slider Image #2",
                       "desc" => 'Add slider image here (810x315px). Larger images will be resized correctly if you select "medium" size on WordPress image uploader.',
                       "id" => "slider_image_2",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #2",
                       "desc" => "Text to overlay on Slider Image #2. Supports basic HTML: <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>.",
                       "id" => "slider_caption_2",
                       "type" => "textarea");
    
    $options[] = array("name" => "Slider Image #3",
                       "desc" => 'Add slider image here (810x315px). Larger images will be resized correctly if you select "medium" size on WordPress image uploader.',
                       "id" => "slider_image_3",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #3",
                       "desc" => "Text to overlay on Slider Image #3. Supports basic HTML: <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>.",
                       "id" => "slider_caption_3",
                       "type" => "textarea");
    
    $options[] = array("name" => "Slider Image #4",
                       "desc" => 'Add slider image here (810x315px). Larger images will be resized correctly if you select "medium" size on WordPress image uploader.',
                       "id" => "slider_image_4",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #4",
                       "desc" => "Text to overlay on Slider Image #4. Supports basic HTML: <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>.",
                       "id" => "slider_caption_4",
                       "type" => "textarea");
    
    $options[] = array("name" => "Slider Image #5",
                       "desc" => 'Add slider image here (810x315px). Larger images will be resized correctly if you select "medium" size on WordPress image uploader.',
                       "id" => "slider_image_5",
                       "type" => "upload");
    
    $options[] = array("name" => "Slider Caption #5",
                       "desc" => "Text to overlay on Slider Image #5. Supports basic HTML: <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>.",
                       "id" => "slider_caption_5",
                       "type" => "textarea");
    
    
    
    $options[] = array("name" => "Video Position",
                       "desc" => "Choose where you want to position the video - in the centre, or offset to centre-right (balanced with logo). The CSS element for this is <code>.covervideo .media</code>.",
                       "id" => "covervideo_position",
                       "std" => "right",
                       "type" => "radio",
                       "options" => array("center"  => "Centre", 
                                          "right"   => "Centre-Right"));
    
    $options[] = array("name" => "Video Background image (Optional)",
                       "desc" => 'Add video background image here (810x315px). Larger images will be resized correctly if you select "medium" size on WordPress image uploader.',
                       "id" => "covervideo_image",
                       "type" => "upload");
    
    $options[] = array("name" => "Youtube Video ID",
                       "desc" => "Enter the YouTube video ID of your unlisted (hidden) Youtube video, this is the code that appears after <code>v=</code> in the YouTube URL. For example: <code>www.youtube.com/watch?v=SfqpAWPx6T4</code>, the YouTube ID is <code>SfqpAWPx6T4</code>.",
                       "id" => "covervideo_id",
                       "std" => "5Giw8rKT4Dg",
                       "type" => "text");
    
    
    /* New Tab */
    
    $options[] = array("name" => "Order Form Fields",
                       "type" => "heading");
    
    $options[] = array("name" => "Order Form Fields Generator",
                       "desc" => 'These are the fields that your customers will have to fill in on the Order Form. You can customize them and reorder them to fit your use case best. Only the Email field is required on your part, but all fields will be required on your users part. You can check <a href="#" class="orderform_fields_example" data-example="physical">this example</a> for a Physical Product, or <a href="#" class="orderform_fields_example" data-example="contact">this one</a> for "Pay with a Contact"-type form.<br><br>In the case of physical orders and if you only ship in one country, you can specify the country using a Title element below the address fields.',
                       "id" => "orderform_fields",
                       "std" => '[{"type":"h3","content":"Your Details"},{"type":"text","placeholder":"First Name","name":"customer[firstname]"},{"type":"text","placeholder":"Last Name","name":"customer[lastname]"},{"type":"email","placeholder":"Email Address","name":"email"},{"type":"h3","content":"Address"},{"type":"text","placeholder":"Address","name":"customer[address]"},{"type":"text","placeholder":"City","name":"customer[city]"},{"type":"text","placeholder":"Post Code","name":"customer[postcode]"},{"type":"h3","content":"Country"}]',
                       "type" => "textarea");
    
    $options[] = array("desc" => '<div id="orderform_fields_physical">[{"type":"h3","content":"Your Details"},{"type":"text","placeholder":"First Name","name":"customer[firstname]"},{"type":"text","placeholder":"Last Name","name":"customer[lastname]"},{"type":"email","placeholder":"Email Address","name":"email"},{"type":"h3","content":"Address"},{"type":"text","placeholder":"Address","name":"customer[address]"},{"type":"text","placeholder":"City","name":"customer[city]"},{"type":"text","placeholder":"Post Code","name":"customer[postcode]"},{"type":"h3","content":"Country"}]</div>',
                       "class" => "hidden",
					   "type" => "info");
    
    $options[] = array("desc" => '<div id="orderform_fields_contact">[{"type":"h3","content":"Your Details"},{"type":"text","placeholder":"First Name","name":"customer[firstname]"},{"type":"text","placeholder":"Last Name","name":"customer[lastname]"},{"type":"email","placeholder":"Email Address","name":"email"},{"type":"h3","content":"Your Work"},{"type":"text","placeholder":"Company","name":"customer[company]"},{"type":"text","placeholder":"Position","name":"customer[position]"}]</div>',
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
    
    
    
    $options[] = array("name" => "Custom CSS",
                       "desc" => "Any custom CSS you paste here will be added to your Popshop.",
                       "id" => "custom_css",
                       "std" => "",
                       "type" => "textarea");
    
    $options[] = array("name" => "Google Analytics tracking script",
                       "desc" => "Add your Google Analytics tracking code script here (including the <code>&lt;script&gt;&lt;/script&gt;</code> tags).",
                       "id" => "custom_script",
                       "std" => "",
                       "type" => "textarea");
    
    $options[] = array("name" => "Video embed default code (Slider Video)",
                       "desc" => "This is the default embed code from Youtube. Only modify if you know what you're doing!",
                       "id" => "video_embed_code",
                       "std" => '<iframe id="player" width="370" height="218" src="http://www.youtube.com/embed/VIDEO_ID?wmode=opaque&rel=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe>',
                       "type" => "textarea");
    
    // @see http://stackoverflow.com/questions/3820325/overlay-opaque-div-over-youtube-iframe 
    // @see http://stackoverflow.com/questions/886864/differences-between-using-wmode-transparent-opaque-or-window-for-an-embe
    // opaque is less resource-intensive than transparent
    
    $options[] = array("name" => "Video embed default code (Digital Streaming)",
                       "desc" => "This is the default embed code from Youtube. Only modify if you know what you're doing!",
                       "id" => "video_embed_code_2",
                       "std" => '<iframe id="player" width="710" height="391" src="http://www.youtube.com/embed/VIDEO_ID?wmode=opaque&rel=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe>',
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
                       "desc" => "Specify Image to be shared on Facebook when users like your Popshop (defaults to your logo, but should be at least 200x200px).",
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

