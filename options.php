<?php 

class MySettingsPage {
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Notifier', 
            'manage_options', 
            'my-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        // Set class property
        $this->options = get_option( 'my_option_name' );
        ?>
        <div class="wrap">
            <h1>Notifier</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'api_section', // ID
            'API Endpoint', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );  

        add_settings_field(
            'url', // ID
            'URL', // Title 
            array( $this, 'url_callback' ), // Callback
            'my-setting-admin', // Page
            'api_section' // Section           
        );      

        add_settings_field(
            'token', 
            'Token', 
            array( $this, 'token_callback' ), 
            'my-setting-admin', 
            'api_section'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['url'] ) )
            $new_input['url'] = esc_url_raw($input['url']);

        if( isset( $input['token'] ) )
            $new_input['token'] = sanitize_text_field( $input['token'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Inserire l\'URL e il Token di autenticazione dell\'endpoint a cui verrà notificato l\'inserimento di un articolo:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function url_callback()
    {
        printf(
            '<input type="url" id="url" placeholder="https://api.endpoint.com" name="my_option_name[url]" value="%s" />',
            isset( $this->options['url'] ) ? esc_attr( $this->options['url']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function token_callback()
    {
        printf(
            '<input type="text" id="token" placeholder="Token" name="my_option_name[token]" value="%s" />',
            isset( $this->options['token'] ) ? esc_attr( $this->options['token']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new MySettingsPage();