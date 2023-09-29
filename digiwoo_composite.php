<?php
/**
 * Plugin Name: DigiWoo Composite Checkout
 * Description: A custom WooCommerce checkout composite plugin to enhance the checkout process.
 * Version: 1.0.1
 * Author: Ardika JM-Consulting
 * Text Domain: digiwoo-composite
 * WC requires at least: 3.0.0
 * WC tested up to: 5.5.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('DigiWoo_Composite')) :

class DigiWoo_Composite {

    /**
     * Plugin instance.
     */
    protected static $instance = null;

    /**
     * Get plugin instance.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define constants.
     */
    private function define_constants() {
        define('DIGIWOO_COMPOSITE_VERSION', '1.0');
        define('DIGIWOO_COMPOSITE_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('DIGIWOO_COMPOSITE_PLUGIN_URL', plugin_dir_url(__FILE__));
    }

    /**
     * Include required files.
     */
    public function includes() {
        include_once DIGIWOO_COMPOSITE_PLUGIN_DIR . 'includes/class-digiwoo-settings.php';
        include_once DIGIWOO_COMPOSITE_PLUGIN_DIR . 'includes/class-digiwoo-composite.php';
        include_once DIGIWOO_COMPOSITE_PLUGIN_DIR . 'includes/class-digiwoo-categories.php';
        include_once DIGIWOO_COMPOSITE_PLUGIN_DIR . 'includes/class-digiwoo-products.php';
        include_once DIGIWOO_COMPOSITE_PLUGIN_DIR . 'includes/class-digiwoo-meta-version.php';
        include_once DIGIWOO_COMPOSITE_PLUGIN_DIR . 'includes/class-digiwoo-add-on-trading.php';
    }

    /**
     * Initialize hooks.
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'load_assets'));
        add_action('wp', array($this, 'display_product_categories'));
    }

    /**
     * Load frontend assets.
     */
    public function load_assets() {
        wp_enqueue_style('digiwoo-composite-css', DIGIWOO_COMPOSITE_PLUGIN_URL . 'assets/css/frontend.css', array(), DIGIWOO_COMPOSITE_VERSION);
        wp_enqueue_script('digiwoo-composite-js', DIGIWOO_COMPOSITE_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), DIGIWOO_COMPOSITE_VERSION, true);
    }

    public function display_product_categories() {
        // Check if the plugin is enabled
        if ('yes' !== get_option('wc_digiwoo_composite_enable_plugin')) {
            return;
        }
        
        // Check if the current page is the 'composite' page
        if (is_page(get_option('wc_digiwoo_composite_custom_page_checkout'))) {
            $args = array(
                'taxonomy'   => 'product_cat',
                'orderby'    => 'name',
                'show_count' => 0,
                'pad_counts' => 0,
                'hierarchical' => 1,
                'title_li'   => '',
                'hide_empty' => 0,
            );
            
            $all_categories = get_categories($args);
            echo '<ul class="digiwoo_product_categories">';
            foreach ($all_categories as $cat) {
                if ($cat->category_parent == 0) {
                    $category_id = $cat->term_id;
                    echo '<li><a href="' . get_term_link($cat->slug, 'product_cat') . '">' . $cat->name . '</a></li>';
                }       
            }
            echo '</ul>';
        }
    }


}

endif;

/**
 * Returns the main instance of DigiWoo_Composite.
 */
function digiwoo_composite() {
    return DigiWoo_Composite::get_instance();
}

// Initialize the plugin
digiwoo_composite();

