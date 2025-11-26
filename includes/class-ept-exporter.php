<?php

if (!defined('ABSPATH')) {
    exit;
}

class EPT_Exporter {
    
    public function __construct() {
    }
    
    public function get_plugins() {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $all_plugins = get_plugins();
        $plugins = array();
        
        foreach ($all_plugins as $plugin_path => $plugin_data) {
            $plugins[] = array(
                'path' => $plugin_path,
                'name' => $plugin_data['Name'],
                'version' => $plugin_data['Version'],
                'active' => is_plugin_active($plugin_path)
            );
        }
        
        return $plugins;
    }
    
    public function get_themes() {
        if (!function_exists('wp_get_themes')) {
            require_once ABSPATH . WPINC . '/theme.php';
        }
        
        $all_themes = wp_get_themes();
        $themes = array();
        
        foreach ($all_themes as $theme_path => $theme_data) {
            $themes[] = array(
                'path' => $theme_path,
                'name' => $theme_data->get('Name'),
                'version' => $theme_data->get('Version'),
                'active' => ($theme_path == get_stylesheet())
            );
        }
        
        return $themes;
    }
    
    public function export_plugin($plugin_path) {
        $plugin_real_path = WP_PLUGIN_DIR . '/' . $plugin_path;
        if (!file_exists($plugin_real_path)) {
            return false;
        }
        
        $plugin_dir = dirname($plugin_real_path);
        $plugin_folder = basename(dirname($plugin_real_path));
        
        if ($plugin_folder === '.' || $plugin_folder === WP_PLUGIN_DIR) {
            $plugin_folder = basename($plugin_real_path, '.php');
            $plugin_dir = WP_PLUGIN_DIR;
        }
        
        return $this->create_zip($plugin_dir, $plugin_folder, 'plugin');
    }
    
    public function export_theme($theme_path) {
        $theme_real_path = get_theme_root() . '/' . $theme_path;
        if (!file_exists($theme_real_path)) {
            return false;
        }
        
        return $this->create_zip($theme_real_path, $theme_path, 'theme');
    }
    
    private function create_zip($source_dir, $folder_name, $type = 'plugin') {
        if (!class_exists('ZipArchive')) {
            return false;
        }
        
        $zip_name = $folder_name . '_' . gmdate('Y-m-d_H-i-s') . '.zip';
        $zip_path = WP_CONTENT_DIR . '/uploads/' . $zip_name;
        
        if (!file_exists(WP_CONTENT_DIR . '/uploads')) {
            wp_mkdir_p(WP_CONTENT_DIR . '/uploads');
        }
        
        $zip = new ZipArchive();
        if ($zip->open($zip_path, ZipArchive::CREATE) !== TRUE) {
            return false;
        }
        
        $this->add_files_to_zip($zip, $source_dir, $folder_name, $type);
        
        $zip->close();
        
        if (file_exists($zip_path)) {
            return $zip_path;
        }
        
        return false;
    }
    
    private function add_files_to_zip($zip, $source_dir, $local_path = '', $type = 'plugin') {
        if (!is_dir($source_dir)) {
            if (is_file($source_dir) && $type === 'plugin') {
                $zip->addFile($source_dir, basename($source_dir));
            }
            return;
        }
        
        $files = scandir($source_dir);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $source_path = $source_dir . '/' . $file;
            $zip_path = $local_path ? $local_path . '/' . $file : $file;
            
            if (is_dir($source_path)) {
                $zip->addEmptyDir($zip_path);
                $this->add_files_to_zip($zip, $source_path, $zip_path, $type);
            } else {
                $zip->addFile($source_path, $zip_path);
            }
        }
    }
}