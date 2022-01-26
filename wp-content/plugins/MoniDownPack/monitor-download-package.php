<?php
/**
 * Hello World
 *
 * @package     MoniDownPack
 * @author      Mario Gosparini
 * @copyright   2021 Mario Gosparini
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: MoniDownPack
 * Plugin URI:  https://dyrk.org?q=MoniDownPack
 * Description: This plugin list all plugins & theme and their update url
 * Version:     1.0.0
 * Author:      Mario Gosparini
 * Author URI:  https://dyrk.org
 * Text Domain: MonyDownPack
 * License:     GPL v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */

/*
*
*
*	Create settings view
*
*
*/
function display_monidownpack_settings_page() {
    try {
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        $plugins = get_plugins();
        $row_template = "<tr><td><b>%s</b></td><td style='color:%s'>%s</td></tr>";
        echo "<h1>Plugin(s)</h1><br><table>";
        foreach ($plugins as $plugin => $datas) {
            $api = plugins_api('plugin_information', array('slug' => basename($plugin,'.php'), 'fields' => array('sections' => false)));
            $name  = HTMLEntities($datas['Name']);
            $url   = HTMLEntities($api->download_link ? $api->download_link : " --- ");        $color = substr($url, 0,32) == 'https://downloads.wordpress.org/' ? 'green' : 'red';
            echo sprintf($row_template, $name, $color, $url);
        }
        echo "</table>";
        $themes = get_themes();
        echo "<h1>Theme(s)</h1><br><table>";
        foreach ($themes as $theme => $datas) {
            $api = themes_api('theme_information', array('slug' => $datas->stylesheet, 'fields' => array('sections' => false)));
            $name  = HTMLEntities($datas['Name']);
            $url   = HTMLEntities($api->download_link ? $api->download_link : " --- ");        $color = substr($url, 0,32) == 'https://downloads.wordpress.org/' ? 'green' : 'red';
            echo sprintf($row_template, $name, $color, $url);
            }
        echo "</table>";
   } catch(Exception $e) {
    print_r(array("Error" => $e));
   }
}

/*
 *
 *
 *  This short part return a fake update's url package, to provide example of evil extension 
 *
 *
 */
function monidownpack_plugin_info($res, $action, $args ){
        if( 'plugin_information' !== $action ) {
                return false;
        }
        if( basename(plugin_basename( __FILE__ ), '.php') !== $args->slug ) {
                return false;
        }
            $fakeUrl = 'https://'.gethostname().'/evil-package-containing-many-backdoor.zip ! <--- It\'s not true, only to check if system is working fine';
        $res = new stdClass();
        $res->name = 'test';
        $res->slug = $args->slug;
        $res->plugin = plugin_basename( __FILE__ ) ;
        $res->download_link = $fakeUrl;
        return $res;
}
add_filter( 'plugins_api', 'monidownpack_plugin_info', 20, 3);



/*
 *
 *
 * Create Menu
 *
 *
 */
function monidownpack_settings_admin_menu() {
  add_menu_page(
        'Monitor download\'s Url Package',// page title
        'Monitor download\'s Url Package',
        'manage_options',// capability
        'monidownpack-settings',// menu slug
        'display_monidownpack_settings_page' // callback function
    );
}
add_action('admin_menu', 'monidownpack_settings_admin_menu');

