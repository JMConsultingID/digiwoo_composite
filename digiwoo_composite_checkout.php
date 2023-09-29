<?php
if ( ! class_exists( 'WooCommerce' ) ) return;

class Digiwoo_Composite_Checkout {

    public function __construct() {
        add_action('woocommerce_after_order_notes', array($this, 'custom_checkout_fields'));
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_custom_checkout_fields'));

        // Enqueue Scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_custom_scripts'));

        add_action('wp_ajax_get_products_by_category', array($this, 'get_products_by_category'));
        add_action('wp_ajax_nopriv_get_products_by_category', array($this, 'get_products_by_category'));
    }

    public function custom_checkout_fields($checkout) {
        echo '<div id="digiwoo_custom_checkout"><h2>' . __('DigiWoo Custom Fields') . '</h2>';

        // Pilihan Kategori
        woocommerce_form_field('product_category', array(
            'type' => 'select',
            'class' => array('form-row-wide'),
            'label' => __('Select Product Category'),
            'options' => $this->get_product_categories()
        ));

        // Pilihan Produk berdasarkan Kategori (Kosong pada awalnya)
        woocommerce_form_field('product_choice', array(
            'type' => 'select',
            'class' => array('form-row-wide', 'digiwoo-products-list'),
            'label' => __('Select a Product'),
            'options' => array()
        ));

        // Pilihan Meta Version
        woocommerce_form_field('select_meta_version', array(
            'type' => 'select',
            'class' => array('form-row-wide'),
            'label' => __('Select Meta Version'),
            'options' => array(
                '' => 'Select one...',
                'mt4' => 'MT4',
                'mt5' => 'MT5'
            )
        ));

        // Custom field untuk Add-on Trading
        woocommerce_form_field('add_on_trading', array(
            'type' => 'multiselect',
            'class' => array('form-row-wide'),
            'label' => __('Add-on Trading'),
            'options' => array(
                'increase_profit_90' => 'Increase profit split 90% (+20%)',
                'increase_leverage_100' => 'Increase leverage 1:100 (+25%)',
                'no_time_limit' => 'No time limit (+5%)',
                'bi_weekly_payouts' => 'Bi weekly payouts (+5%)',
                'raw_spreads' => 'Raw spreads (+20%)'
            )
        ));

        echo '</div>';
    }

    public function save_custom_checkout_fields($order_id) {
        if (!empty($_POST['product_category'])) {
            update_post_meta($order_id, 'product_category', sanitize_text_field($_POST['product_category']));
        }

        if (!empty($_POST['product_choice'])) {
            update_post_meta($order_id, 'product_choice', sanitize_text_field($_POST['product_choice']));
        }

        if (!empty($_POST['select_meta_version'])) {
            update_post_meta($order_id, 'select_meta_version', sanitize_text_field($_POST['select_meta_version']));
        }

        if (!empty($_POST['add_on_trading'])) {
            update_post_meta($order_id, 'add_on_trading', $_POST['add_on_trading']);
        }
    }

    private function get_product_categories() {
        $categories = get_terms('product_cat', array('hide_empty' => 0));
        $category_list = array('' => 'Select a category...');

        foreach ($categories as $category) {
            $category_list[$category->term_id] = $category->name;
        }

        return $category_list;
    }

    public function get_products_by_category() {
        $category_id = $_GET['category_id'];
        $args = array(
            'post_type' => 'product',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $category_id,
                )
            ),
        );
        $products = get_posts($args);
        $product_data = array();

        foreach ($products as $product) {
            $product_data[] = array(
                'id' => $product->ID,
                'name' => $product->post_title
            );
        }

        wp_send_json_success($product_data);
    }

    public function enqueue_custom_scripts() {
        wp_enqueue_script('digiwoo-checkout', plugin_dir_url(__FILE__) . 'digiwoo-checkout.js', array('jquery'), '1.0.0', true);

        // Localize script to pass data to JavaScript
        wp_localize_script('digiwoo-checkout', 'digiwoo_vars', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }
}

new Digiwoo_Composite_Checkout();
