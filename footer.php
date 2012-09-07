


<?php wp_footer(); ?> 

<?php /* Data passed from PHP to Javascript. This is so that we don't have to output any JS, for the sake of elegance. */ ?>
<div class="hide">
    <span id="ajax_url"><?php echo get_stylesheet_directory_uri().'/ajax.php' ?></span>
    <span id="ajax_url_paypal"><?php echo get_stylesheet_directory_uri().'/ajax_paypal.php' ?></span>
    <span id="facebook_app_id"><?php echo popshop_get_option('facebook_app_id') ?></span>
    <span id="background_image"><?php echo popshop_get_option('background_image') ?></span>
    <span id="slider_animation"><?php echo popshop_get_option('slider_animation') ?></span>
    <span id="slider_animation_speed"><?php echo popshop_get_option('slider_animation_speed') ?></span>
    <span id="shop_type"><?php echo popshop_get_option('shop_type') ?></span>
    <span id="payment_type"><?php echo popshop_get_option('payment_type') ?></span>
    <span id="product_type"><?php echo popshop_get_option('product_type') ?></span>
    <span id="external_url"><?php echo popshop_get_option('external_url') ?></span>
    <?php if (popshop_get_option('product_type') == 'file') { ?>
    <span id="file_to_download"><?php echo popshop_get_option('file_to_download') ?></span>
    <?php } ?>
    <?php if(popshop_on_facebook()): ?>
        <span id="facebook_page"><?php echo popshop_on_facebook() ?></span>
    <?php endif; ?>
    <span id="one_per_customer"><?php echo popshop_get_option('one_per_customer') ?></span>
    <span id="one_per_customer_alert"><?php echo popshop_get_option('one_per_customer_alert') ?></span>
    <span id="email_confirmation"><?php echo popshop_get_option('email_confirmation') ?></span>
    <span id="email_confirmation_subject">Confirmation email from <?php echo popshop_get_option('header') ?></span>
</div>

<?php if ($_COOKIE['popshop_shared'] == true) { ?>

<script>

jQuery(document).ready(function() { Popshop.shared = true; });

</script>

<?php } ?>

</body>
</html>
