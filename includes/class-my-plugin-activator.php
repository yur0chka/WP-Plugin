<?php
/**
 * Fired during plugin activation
 *
 * @link       https://my-plugin/author
 * @since      1.0.0
 *
 * @package    My_Plugin
 * @subpackage My_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    My_Plugin
 * @subpackage My_Plugin/includes
 * @author     Author <my-plugin@gmail.com>
 */
class My_Plugin_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
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
