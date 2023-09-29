<?php
/**
 * Plugin Name: Digiwoo Composite Product Woocommerce
 * Description: Custom Plugin to Create Composite Product Woocommerce Checkout
 * Version: 1.0.1
 * Author: Ardika JM-Consulting
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Digiwoo_Composite_Checkout {

    public function __construct() {
        // Hook for admin settings
        add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50);
        add_action('woocommerce_settings_tabs_digiwoo_composite', array($this, 'settings_tab'));
        add_action('woocommerce_update_options_digiwoo_composite', array($this, 'update_settings'));
    }

    public function add_settings_tab($settings_tabs) {
        $settings_tabs['digiwoo_composite'] = __('DigiWoo Composite Checkout', 'digiwoo_composite');
        return $settings_tabs;
    }

    public function settings_tab() {
        woocommerce_admin_fields($this->get_settings());
    }

    public function update_settings() {
        woocommerce_update_options($this->get_settings());
    }

    public function get_settings() {
        $settings = array(
            'section_title' => array(
                'name'     => __('DigiWoo Composite Checkout Settings', 'digiwoo_composite'),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_digiwoo_composite_section_title'
            ),
            'enabled' => array(
                'name'    => __('Enable/Disable', 'digiwoo_composite'),
                'type'    => 'checkbox',
                'desc'    => __('Enable DigiWoo Composite Checkout', 'digiwoo_composite'),
                'id'      => 'wc_digiwoo_composite_enabled',
                'default' => 'no'
            ),
            'checkout_page' => array(
                'name'     => __('Custom Checkout Page', 'digiwoo_composite'),
                'type'     => 'select',
                'desc'     => __('Select a custom page for checkout', 'digiwoo_composite'),
                'id'       => 'wc_digiwoo_composite_checkout_page',
                'options'  => $this->get_pages(),
                'default'  => ''
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id'   => 'wc_digiwoo_composite_section_end'
            )
        );
        return $settings;
    }

    public function get_pages() {
        $pages_options = array();
        $pages = get_pages();
        foreach ($pages as $page) {
            $pages_options[$page->ID] = $page->post_title;
        }
        return $pages_options;
    }
}

$digiwoo_composite_checkout = new Digiwoo_Composite_Checkout();
