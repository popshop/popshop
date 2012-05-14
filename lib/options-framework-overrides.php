<?php

function optionsframework_page() {
	settings_errors();
?>

<div class="nav-popshop-settings"><?php echo optionsframework_tabs(); ?></div>


<div id="optionsframework" class="postbox">
	<form action="options.php" method="post">
	<?php settings_fields('optionsframework'); ?>

	<?php optionsframework_fields(); /* Settings */ ?>

    <div id="optionsframework-submit">
		<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options' ); ?>" />
        <input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!' ) ); ?>' );" />
        <div class="clear"></div>
	</div>
</form>
</div> <!-- / #container -->


<?php
}

