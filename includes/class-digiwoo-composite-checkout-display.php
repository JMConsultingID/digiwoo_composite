<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class DigiWoo_Composite_Checkout_Display {

    public function __construct() {
        add_shortcode('digiwoo_composite_checkout', array($this, 'display_checkout'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_digiwoo_get_products', array($this, 'get_products'));
        add_action('wp_ajax_nopriv_digiwoo_get_products', array($this, 'get_products'));
    }

    public function display_checkout() {
        ob_start();

        // Display product categories
        $categories = get_terms('product_cat');
        echo '<select id="digiwoo_category_select">';
        foreach ($categories as $category) {
            echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
        }
        echo '</select>';

        // Placeholder for products dropdown
        echo '<select id="digiwoo_product_select" style="display: none;"></select>';

        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_script('digiwoo-ajax', DIGIWOO_COMPOSITE_PLUGIN_URL . 'assets/js/digiwoo-ajax.js', array('jquery'), DIGIWOO_COMPOSITE_VERSION, true);
        wp_localize_script('digiwoo-ajax', 'digiwoo_params', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }

    public function get_products() {
        echo json_encode(array('status' => 'success'));
        wp_die();
    }



}

new DigiWoo_Composite_Checkout_Display();
