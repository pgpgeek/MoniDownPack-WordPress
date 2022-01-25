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
*	Load ressources in the Wordpress login page
*
*
*
*/



/*
*
*
*	Create settings view
*
*
*/
function display_monitorDownloadUrlPackage_settings_page() {
   try {
	include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	$plugins = get_plugins();
	$row_template = "<tr><td><b>%s</b></td><td>%s</td></tr>";
	echo "<h1>Plugin(s)</h1><br><table>";
	foreach ($plugins as $plugin => $datas) {
		$api = plugins_api('plugin_information', array('slug' => basename($plugin,'.php'), 'fields' => array('sections' => false)));
		echo sprintf($row_template, $datas['Name'], $api->download_link ? $api->download_link : " --- ");
	}
	echo "</table>";
	$themes = get_themes();
	echo "<h1>Theme(s)</h1><br><table>";
        foreach ($themes as $theme => $datas) {
                $api = themes_api('theme_information', array('slug' => $datas->stylesheet, 'fields' => array('sections' => false)));    
                echo sprintf($row_template, $datas['Name'], $api->download_link ? $api->download_link : " --- ");
        }
        echo "</table>";
	

   } catch(Exception $e) {
	print_r(array("Error" => $e));
   }
}
function monitorDownloadUrlPackage_settings_admin_menu() {
  add_menu_page(
        'Monitor download\'s Url Package',// page title
        'Monitor download\'s Url Package',
        'manage_options',// capability
        'monitorDownloadUrlPackage-settings',// menu slug
        'display_monitorDownloadUrlPackage_settings_page' // callback function
    );
}
add_action('admin_menu', 'monitorDownloadUrlPackage_settings_admin_menu');

