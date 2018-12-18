<?php
if ( ! class_exists( 'CBDealerSettings' ) ) :
    class CBDealerSettings
    {
        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Start up
         */
        public function __construct()
        {
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
        }

        /**
         * Add options page
         */
        public function add_plugin_page()
        {
            // This page will be under "Settings"
            add_menu_page(
                'CBDealer Settings',
                'CBDealer Settings',
                'manage_options',
                'cbdealer-settings',
                array( $this, 'create_admin_page' )
            );
        }

        /**
         * Options page callback
         */
        public function create_admin_page()
        {
            // Set class property
            $this->options = get_option( 'cbdealer_settings' );
            ?>
            <div class="wrap">
                <h1>CBDealer Settings</h1>
                <form method="post" action="options.php">
                <?php
                    settings_fields( 'cbdealer_settings_group' );
                    do_settings_sections( 'cbdelaer-settings-admin' );
                    submit_button();
                ?>
                </form>
            </div>
            <?php
        }

        /**
         * Register and add settings
         */
        public function page_init()
        {
            register_setting(
                'cbdealer_settings_group', // Option group
                'cbdealer_settings', // Option name
                array( $this, 'sanitize' ) // Sanitize
            );

            add_settings_section(
                'shipping_products', // ID
                'Shipping Products', // Title
                array( $this, 'print_section_info' ), // Callback
                'cbdelaer-settings-admin' // Page
            );

            add_settings_field(
                'delivery_insurance_product_id', // ID
                'Delivery lnsurance product id', // Title
                array( $this, 'delivery_insurance_product_id_callback' ), // Callback
                'cbdelaer-settings-admin', // Page
                'shipping_products' // Section
            );

            add_settings_field(
                'express_delivery_product_id',
                'Express delivery product id',
                array( $this, 'express_delivery_product_id_callback' ),
                'cbdelaer-settings-admin',
                'shipping_products'
            );

            add_settings_field(
                'registered_delivery_product_id',
                'Registered delivery product id',
                array( $this, 'registered_delivery_product_id_callback' ),
                'cbdelaer-settings-admin',
                'shipping_products'
            );
        }

        /**
         * Sanitize each setting field as needed
         *
         * @param array $input Contains all settings fields as array keys
         */
        public function sanitize( $input )
        {

            foreach($input as $key => $value) {
                $new_input[$key] = sanitize_text_field( $value );
            }

            return $new_input;
        }

        /**
         * Print the Section text
         */
        public function print_section_info()
        {
            print 'Enter your settings below:';
        }

        public function delivery_insurance_product_id_callback()
        {
            printf(
                '<input type="text" id="delivery_insurance_product_id" name="cbdealer_settings[delivery_insurance_product_id]" value="%s" />',
                isset( $this->options['delivery_insurance_product_id'] ) ? esc_attr( $this->options['delivery_insurance_product_id']) : ''
            );
        }


        public function express_delivery_product_id_callback()
        {
            printf(
                '<input type="text" id="express_delivery_product_id" name="cbdealer_settings[express_delivery_product_id]" value="%s" />',
                isset( $this->options['express_delivery_product_id'] ) ? esc_attr( $this->options['express_delivery_product_id']) : ''
            );
        }


        public function registered_delivery_product_id_callback()
        {
            printf(
                '<input type="text" id="registered_delivery_product_id" name="cbdealer_settings[registered_delivery_product_id]" value="%s" />',
                isset( $this->options['registered_delivery_product_id'] ) ? esc_attr( $this->options['registered_delivery_product_id']) : ''
            );
        }

    }
endif;

return new CBDealerSettings();
