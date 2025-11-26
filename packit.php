<?php
/**
 * Plugin Name: PackIt Plugin & Theme Exporter
 * Plugin URI: https://vizuh.com/
 * Description: Empacote e exporte facilmente plugins e temas do WordPress com um único clique. Perfeito para backups, migrações e compartilhamento de desenvolvimento.
 * Version: 1.0.0
 * Author: Hugo C, MEAOWS Developer
 * Author URI: https://vizuh.com/
 * Contributors: hugoc, meaowsdev
 * Donate link: https://vizuh.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: packit-plugin-theme-exporter
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.8
 * Requires PHP: 7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PACKIT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PACKIT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PACKIT_PLUGIN_FILE', __FILE__);


require_once PACKIT_PLUGIN_DIR . 'includes/class-packit-exporter.php';
require_once PACKIT_PLUGIN_DIR . 'includes/class-packit-admin.php';

function packit_init() {
    $exporter = new PackIt_Exporter();
    $admin = new PackIt_Admin($exporter);
}
add_action('plugins_loaded', 'packit_init');

function packit_plugin_settings_link($links) {
    $settings_link = '<a href="tools.php?page=packit-plugin-theme-exporter">' . esc_html__('Configurações', 'packit-plugin-theme-exporter') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'packit_plugin_settings_link');

register_activation_hook(__FILE__, 'packit_activate');
register_deactivation_hook(__FILE__, 'packit_deactivate');

function packit_activate() {
    if (!class_exists('ZipArchive')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(esc_html__('Este plugin requer a extensão ZipArchive do PHP. Por favor, habilite-a e tente novamente.', 'packit-plugin-theme-exporter'));
    }
    
    $upload_dir = wp_upload_dir();
    if (!wp_is_writable($upload_dir['basedir'])) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(esc_html__('Este plugin precisa de permissão de escrita no diretório de uploads do WordPress.', 'packit-plugin-theme-exporter'));
    }
}

function packit_deactivate() {
}
