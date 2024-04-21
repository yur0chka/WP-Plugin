<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://my-plugin/author
 * @since      1.0.0
 *
 * @package    My_Plugin
 * @subpackage My_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @property  my_plugin_options
 * @package    My_Plugin
 * @subpackage My_Plugin/public
 * @author     Author <my-plugin@gmail.com>
 */
class My_Plugin_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->my_plugin_options = get_option($this->plugin_name);

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in My_Plugin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The My_Plugin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-plugin-public.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in My_Plugin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The My_Plugin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/my-plugin-public.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * The function of adding text to the footer
     *
     * @since    1.0.0
     */

    public function add_text_footer() {
        global $wpdb;

        $footer_text = '';

        if (!empty($this->my_plugin_options['footer_text'])) {
            $footer_text = sanitize_text_field($this->my_plugin_options['footer_text']);

            // Записати текст у базу даних
            $table_name = $wpdb->prefix . 'footer_data';
            $wpdb->insert(
                $table_name,
                array(
                    'footer_text' => $footer_text
                ),
                array('%s')
            );
        }

        // Вивести текст у футер
        if (!empty($footer_text)) {
            echo '<h3 class="center">' . $footer_text . '</h3>';
        }
    }

    public function my_plugin_create_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'footer_data';

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                footer_text text NOT NULL,
                PRIMARY KEY  (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}

register_activation_hook(__FILE__, function() {
    $plugin = new My_Plugin_Public('my_plugin', '1.0.0');
    $plugin->my_plugin_create_table();
});
