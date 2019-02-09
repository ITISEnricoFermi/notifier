<?php

add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_options_page( 'Notifier', 'Notifier', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
}

function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
   ?>
        <div class="wrap">
            <h1>Notifier</h1>
            <form method="post" action="options.php">
                <?php settings_fields('extra-notifier_url-info-settings'); ?>
                <?php do_settings_sections('notifier_url-post-info-settings'); ?>
                <label for="api">URL delle API</label><br/>
                <input type="text" placeholder="URL" name="api" value="<?php echo get_option('notifier_url'); ?>">
                <?php submit_button(); ?>
            </form>
        </div>  
    <?php
}