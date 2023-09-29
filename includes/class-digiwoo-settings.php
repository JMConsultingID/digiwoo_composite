<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class DigiWoo_Settings {

    /**
     * Bootstraps the class.
     */
    public function __construct() {
        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
        add_action( 'woocommerce_settings_tabs_digiwoo_composite', array( $this, 'settings_tab' ) );
        add_action( 'woocommerce_update_options_digiwoo_composite', array( $this, 'update_settings' ) );
    }

    /**
     * Adds a new settings tab to the WooCommerce settings tabs array.
     */
    public function add_settings_tab( $settings_tabs ) {
        $settings_tabs['digiwoo_composite'] = __( 'DigiWoo Composite', 'digiwoo-composite' );
        return $settings_tabs;
    }

    /**
     * Uses the WooCommerce admin fields API to output settings.
     */
    public function settings_tab() {
        woocommerce_admin_fields( $this->get_settings() );
    }

    /**
     * Uses the WooCommerce options API to save settings.
     */
    public function update_settings() {
        woocommerce_update_options( $this->get_settings() );
    }

    /**
     * Returns an array of the available settings.
     */
    public function get_settings() {
        $settings = array(
            'section_title' => array(
                'name'     => __( 'DigiWoo Composite Settings', 'digiwoo-composite' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_digiwoo_composite_section_title'
            ),
            'enable_plugin' => array(
                'name'     => __( 'Enable Plugin', 'digiwoo-composite' ),
                'type'     => 'checkbox',
                'desc'     => __( 'Enable the DigiWoo Composite checkout process.', 'digiwoo-composite' ),
                'id'       => 'wc_digiwoo_composite_enable_plugin'
            ),
            'custom_page_checkout' => array(
                'name'     => __( 'Custom Checkout Page', 'digiwoo-composite' ),
                'type'     => 'dropdown_pages',
                'desc'     => __( 'Select the custom checkout page.', 'digiwoo-composite' ),
                'id'       => 'wc_digiwoo_composite_custom_page_checkout'
            ),
            'section_end' => array(
                'type'     => 'sectionend',
                'id'       => 'wc_digiwoo_composite_section_end'
            )
        );

        return apply_filters( 'wc_digiwoo_composite_settings', $settings );
    }

}

return new DigiWoo_Settings();
