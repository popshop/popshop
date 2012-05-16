<?php get_header(); ?>


<?php

$containerclasses = array();
if (!popshop_on_facebook()) {
    // Display Facebook Timeline-like chrome around our content if we're NOT on Facebook
    $containerclasses[] = "fbTimelineSection";
}

?>


<div id="container" class="<?php echo implode(' ', $containerclasses) ?>">
    
    <div class="cf">
        <div class="title">
            <h1><?php echo popshop_get_option('header') ?></h1>
            <h2><?php echo popshop_get_option('subheader') ?></h2>
        </div>
        
        <div class="social">
            <?php /* @todo: Widgetize? */ ?>
            <?php if (popshop_share_button('like')): ?>
            <div class="likefix">
                <div class="fb-like" data-send="false" data-layout="button_count" data-width="120" data-show-faces="false"></div>
            </div>
            <?php endif; ?>
            <?php if (popshop_share_button('tweet')): ?>
            <div class="tweetfix">
                <a href="https://twitter.com/share" rel="nofollow" class="twitter-share-button"></a>
            </div>
            <?php endif; ?>
            <?php if (popshop_share_button('plusone')): ?>
            <div>
                <div class="g-plusone" data-size="medium" data-callback="popshop_plusone"></div>
            </div>
            <?php endif; ?>
            <?php if (popshop_share_button('pinit')): ?>
            <div class="pinitfix"">
                <a href="http://pinterest.com/pin/create/button/?<?php echo popshop_pinit_data() ?>" rel="nofollow" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
            </div>
            <?php endif; ?>
            <?php if (popshop_share_button('linkedin')): ?>
            <div class="linkedinfix">
                <script type="IN/Share" data-onsuccess="popshop_linkedin" data-counter="right" data-showzero="true"></script>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if(popshop_get_option('cover_type') == "covervideo"): /* Cover: Video mode */ ?>
        <div id="cover" class="covervideo">
            <div class="moviebg">
                <?php if(popshop_get_option('covervideo_image')): ?>
                    <img class="moviebg" src="<?php echo popshop_get_option('covervideo_image') ?>" />
                <?php else: ?>
                    <img class="moviebg" src="<?php bloginfo('template_url') ?>/images/moviebg.png" />
                <?php endif; ?>
            </div>
            <div class="media-container">
                <div class="media <?php echo popshop_get_option('covervideo_position') ?>">
                    <?php echo popshop_get_video_embed("cover") ?>
                </div>
            </div>
        </div>
    <?php else: /* Cover: Image Slider mode */ ?>
        <div id="cover" class="flexslider">
            <ul class="slides">
                <?php if (!popshop_slider_slides()): ?>
                    <li>
                        <img src="<?php bloginfo('template_url') ?>/images/810x315.png" />
                    </li>
                <?php else: ?>
                    <?php foreach(popshop_slider_slides() as $slide): ?>
                        <li>
                            <img src="<?php echo $slide['image'] ?>" />
                            <?php if($slide['caption']): ?>
                                <p class="flex-caption <?php echo popshop_get_option('slider_caption_style') ?> <?php echo popshop_get_option('slider_caption_position') ?>"><?php echo $slide['caption'] ?></p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div id="fbTimelineHeadline" class="cf">
        <div class="profilePicThumb">
            <div class="uiScaledImageContainer profilePic">
                <?php if (popshop_get_option('logo')): ?>
                    <img class="scaledImageFitWidth" src="<?php echo popshop_get_option('logo') ?>" />
                <?php else: ?>
                    <img class="scaledImageFitWidth" src="<?php bloginfo('template_url') ?>/images/160x160.png" />
                <?php endif; /* We could also look into using Facebook Graph pictures directly, but let's keep things simple. */ ?>
            </div>
        </div>
        
        <ul class="navmenu">
            <li><a class="popshop-fb-share" href="#">Share</a></li>
            <li><a class="popshop-fb-send" href="#">Send</a></li>
            <?php echo popshop_get_navmenu() ?>
            <?php if(!popshop_get_option('hide_popshop_link')): ?>
                <li><a class="popshop-link" href="http://getpopshop.com" target="_blank" title="Powered by Popshop">Popshop</a></li>
            <?php endif; ?>
        </ul>
    </div>
    
    <div id="morecontent" class="morecontent" style="display:none;">
        <div class="delete"></div>
        <div id="morecontent-inner" class="morecontent-inner"></div>
    </div>
    
    <div class="callToActionWrapper cf" id="main-callToActionWrapper">
        <div class="callToAction">
            <button class="uiButton uiButtonSpecial uiButtonLarge" id="main-cta">
                <span class="uiButtonText"><?php echo popshop_get_option('main_cta_button') ?></span>
            </button>
        </div>
        <span class="callToActionText">
            <?php echo popshop_get_option('main_cta_text') ?>
        </span>
    </div>
    
    <div id="orderform" class="cf formpage" style="display:none">
        <div class="delete"></div>
        <h2>Order Form</h2>
        
        <div class="itemsummary">
            <span class="productname-label">Item</span>
            <span class="price-label">Total</span>
            <div class="itemsummary-inner">
                <span class="productname"><?php echo popshop_get_option('product_name') ?></span>
                <span class="price"><?php echo popshop_get_option('price') ?></span>
            </div>
        </div>
        
        <div class="cf">
            <div class="order-column">
                <?php do_action('popshop_orderform_firstcol_top') ?>
                <div><?php echo wpautop(popshop_get_option('offer_details')) ?></div>
                <?php do_action('popshop_orderform_firstcol_bottom') ?>
            </div>
            <div class="order-column">
                <form id="orderform-form">
                    <?php if((popshop_get_option('payment_type') == "contact") || (popshop_get_option('payment_type') == "paypal")): ?>
                        <?php echo popshop_orderform_fields() ?>
                        <label class="checkbox">
                            <input type="checkbox" name="terms" required> Accept <a class="linktoterms" href="<?php echo popshop_get_page_link_by_slug('terms') ?>">Terms and Conditions</a>
                        </label>
                        <?php if(popshop_get_option('optin')): ?>
                            <label class="checkbox">
                                <input type="checkbox" name="optin"> <?php echo popshop_get_option('optin_text') ?>
                            </label>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="callToAction">
                        <button class="uiButton uiButtonSpecial uiButtonLarge" type="submit" id="orderform-cta">
                            <span class="uiButtonText"><?php echo popshop_get_option('orderform_cta_button') ?></span>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
        
    </div>
    
    
    <div id="thankyouform" class="cf formpage" style="display:none;">
        <h2>Thank you</h2>
        
        <?php echo popshop_get_option('thankyou_message') ?>
        
        <div>Order ID: <code id="thankyou_order_id">n/a</code></div>
        
        <?php if(popshop_get_option('product_type') == "file"): ?>
            <div class="callToAction">
                <button class="uiButton uiButtonSpecial uiButtonLarge" type="submit" id="file_to_download-cta">
                    <span class="uiButtonText">Download File</span>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if(popshop_get_option('product_type') == "video"): ?>
            <div>Direct URL to save for future access: <code>http://youtu.be/<?php echo popshop_get_option('video_id') ?></code></div>
            <div class="streaming">
                <div class="media-container">
                    <div class="media">
                        <?php echo popshop_get_video_embed("product") ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
    
</div>


<?php if (!popshop_on_facebook() && popshop_get_option('background_raster')): ?>
<div id="mesh" class="mesh<?php echo popshop_get_option('background_raster') ?>"></div>
<?php endif; ?>


<?php get_footer(); ?>

