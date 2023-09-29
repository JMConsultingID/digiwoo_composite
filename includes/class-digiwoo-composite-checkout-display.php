<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class DigiWoo_Composite_Checkout_Display {

    public function __construct() {
        add_shortcode('digiwoo_composite_checkout', array($this, 'display_checkout'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        // Add an action to register the 'digiwoo_get_pages' AJAX action
add_action('wp_ajax_digiwoo_get_pages', array($this, 'get_pages'));
add_action('wp_ajax_nopriv_digiwoo_get_pages', array($this, 'get_pages'));
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
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
        $products = wc_get_products(array('category' => array($category_id)));

        if (is_wp_error($products)) {
            // Log the error for debugging
            error_log('WooCommerce Product Query Error: ' . $products->get_error_message());
            echo json_encode(array('error' => 'Unable to fetch products.'));
        } else {
            $options = array();
            foreach ($products as $product) {
                $options[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name()
                );
            }
            echo json_encode($options);
        }
        wp_die();
    }

    public function get_pages() {
        $pages = get_pages();
        $options = array();
        
        foreach ($pages as $page) {
            $options[] = array(
                'id' => $page->ID,
                'title' => $page->post_title
            );
        }
        
        echo json_encode($options);
        wp_die();
    }


}

new DigiWoo_Composite_Checkout_Display();
