<?php
/*
Plugin Name: Canvas Extension - Menu Pack for Menu Customizer
Plugin URI: http://pootlepress.com/
Description: An extension for WooThemes Canvas that provide different menus to apply to Menu Customizer
Version: 1.0
Author: PootlePress
Author URI: http://pootlepress.com/
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( 'classes/class-pootlepress-menu-customizer-menu-pack.php' );
require_once( 'classes/class-pootlepress-updater.php');

$GLOBALS['pootlepress_menu_customizer_menu_pack'] = new Pootlepress_Menu_Customizer_Menu_Pack( __FILE__ );
$GLOBALS['pootlepress_menu_customizer_menu_pack']->version = '1.0';

add_action('init', 'pp_pnm_mp_updater');
function pp_pnm_mp_updater()
{
    if (!function_exists('get_plugin_data')) {
        include(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $data = get_plugin_data(__FILE__);
    $wptuts_plugin_current_version = $data['Version'];
    $wptuts_plugin_remote_path = 'http://www.pootlepress.com/?updater=1';
    $wptuts_plugin_slug = plugin_basename(__FILE__);
    new Pootlepress_Updater ($wptuts_plugin_current_version, $wptuts_plugin_remote_path, $wptuts_plugin_slug);
}
?>
