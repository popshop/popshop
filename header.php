<!DOCTYPE html>
<html>
<head>

<title>POPSHOP - <?php echo popshop_get_option('header') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<style>
<?php if(popshop_get_option('custom_css')): ?>
    <?php echo popshop_get_option('custom_css'); ?>
<?php endif; ?>
</style>
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/images/favicon.gif" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta property="og:title" content="<?php echo popshop_get_option('header') ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo popshop_facebook_url() ?>" />
<meta property="og:image" content="<?php echo popshop_facebook_image() ?>" />
<?php if(popshop_get_option('facebook_app_id')): ?>
<meta property="fb:app_id" content="<?php echo popshop_get_option('facebook_app_id') ?>" />
<?php endif; ?>
<meta property="og:description" content="<?php echo popshop_get_option('main_cta_text') ?>" />

<?php if(popshop_get_option('custom_script')): ?>
    <?php echo popshop_get_option('custom_script'); ?>
<?php endif; ?>

<?php wp_head(); ?>
</head>






<?php

$bodyclasses = array();
if (popshop_get_option('theme')) {
    // If one day we add different themes (Not currently used).
    $bodyclasses[] = popshop_get_option('theme');
}
if (popshop_on_facebook()) {
    $bodyclasses[] = "facebook";
}
else {
    $bodyclasses[] = "off-facebook";
}

?>


<body class="<?php echo implode(' ', $bodyclasses) ?>">

<div id="fb-root"></div>



<?php /*** Asynchronous social JS loading ***/ ?>
<script>
  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     d.getElementsByTagName('head')[0].appendChild(js);
   }(document));
</script>


<?php if (popshop_share_button('tweet')): ?>
<script type="text/javascript">
window.twttr = (function (d,s,id) {var t, js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return; js=d.createElement(s); js.id=id;
js.src="//platform.twitter.com/widgets.js"; fjs.parentNode.insertBefore(js, fjs);
return window.twttr || (t = { _e: [], ready: function(f){ t._e.push(f) } });
}(document, "script", "twitter-wjs"));
window.twttr.ready(function(){
  twttr.events.bind('tweet', function(event) {
    popshop_tweet(event);
  });
});
</script>
<?php endif; ?>


<?php if (popshop_share_button('plusone')): ?>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<?php endif; ?>


<?php if (popshop_share_button('pinit')): ?>
<script type="text/javascript">
(function() {
    window.PinIt = window.PinIt || { loaded:false };
    if (window.PinIt.loaded) return;
    window.PinIt.loaded = true;
    function async_load(){
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.async = true;
        if (window.location.protocol == "https:")
            s.src = "https://assets.pinterest.com/js/pinit.js";
        else
            s.src = "http://assets.pinterest.com/js/pinit.js";
        var x = document.getElementsByTagName("script")[0];
        x.parentNode.insertBefore(s, x);
    }
    if (window.attachEvent)
        window.attachEvent("onload", async_load);
    else
        window.addEventListener("load", async_load, false);
})();
</script>
<?php endif; ?>


<?php if (popshop_share_button('linkedin')): ?>
<script type="text/javascript">
  (function() {
    var js = document.createElement('script'); js.type = 'text/javascript'; js.async = true;
    js.src = '//platform.linkedin.com/in.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(js, s);
  })();
</script>
<?php endif; ?>


<script type="text/javascript">
  var tag = document.createElement('script');
  tag.src = "//www.youtube.com/player_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
</script>

