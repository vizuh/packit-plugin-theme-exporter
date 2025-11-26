<?php

if (!defined('ABSPATH')) {
    exit;
}

class EPT_Admin {
    
    private $exporter;
    
    public function __construct($exporter) {
        $this->exporter = $exporter;
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'handle_export_requests'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    public function add_admin_menu() {
        add_management_page(
            esc_html__('Exportador de Plugins e Temas', 'packit-plugin-theme-exporter'),
            esc_html__('Exportar Plugins/Temas', 'packit-plugin-theme-exporter'),
            'manage_options',
            'packit-plugin-theme-exporter',
            array($this, 'admin_page')
        );
    }
    
    public function enqueue_scripts($hook) {
        if ($hook !== 'tools_page_packit-plugin-theme-exporter') {
            return;
        }
        
        wp_enqueue_style(
            'ept-admin-style',
            EPT_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script(
            'ept-admin-script',
            EPT_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
    
    public function handle_export_requests() {
        if (!isset($_GET['page']) || $_GET['page'] !== 'packit-plugin-theme-exporter') {
            return;
        }
        
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'ept_export')) {
            return;
        }
        
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('Você não tem permissão suficiente para acessar esta página.', 'packit-plugin-theme-exporter'));
        }
        
        if (isset($_GET['export_plugin'])) {
            $plugin_path = sanitize_text_field(wp_unslash($_GET['export_plugin']));
            $this->export_selected_plugin($plugin_path);
        }
        
        if (isset($_GET['export_theme'])) {
            $theme_path = sanitize_text_field(wp_unslash($_GET['export_theme']));
            $this->export_selected_theme($theme_path);
        }
    }
    
    private function export_selected_plugin($plugin_path) {
        if (empty($plugin_path)) {
            wp_die(esc_html__('Caminho do plugin inválido.', 'packit-plugin-theme-exporter'));
        }
        
        $zip_path = $this->exporter->export_plugin($plugin_path);
        
        if (!$zip_path) {
            wp_die(esc_html__('Falha ao exportar o plugin.', 'packit-plugin-theme-exporter'));
        }
        
        $this->download_file($zip_path);
    }
    
    private function export_selected_theme($theme_path) {
        if (empty($theme_path)) {
            wp_die(esc_html__('Caminho do tema inválido.', 'packit-plugin-theme-exporter'));
        }
        
        $zip_path = $this->exporter->export_theme($theme_path);
        
        if (!$zip_path) {
            wp_die(esc_html__('Falha ao exportar o tema.', 'packit-plugin-theme-exporter'));
        }
        
        $this->download_file($zip_path);
    }
    
    private function download_file($file_path) {
        if (!file_exists($file_path)) {
            wp_die(esc_html__('Arquivo não encontrado.', 'packit-plugin-theme-exporter'));
        }
        
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        
        ob_clean();
        flush();
        
        // Usar exclusivamente o sistema de arquivos do WordPress
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        
        // Ler o conteúdo do arquivo usando WP_Filesystem
        $file_content = $wp_filesystem->get_contents($file_path);
        if ($file_content !== false) {
            // Para arquivos binários como ZIP, enviamos diretamente sem escapar
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $file_content;
        }
        
        // Excluir o arquivo de forma segura usando WP_Filesystem
        $wp_filesystem->delete($file_path);
        
        exit;
    }
    
    public function admin_page() {
        $plugins = $this->exporter->get_plugins();
        $themes = $this->exporter->get_themes();
        ?>
        <div class="wrap ept-admin-page">
            <h1><?php esc_html_e('Exportador de Plugins e Temas', 'packit-plugin-theme-exporter'); ?></h1>
            
            <div class="ept-tabs">
                <a href="#" class="ept-tab active" data-target="plugins-content"><?php esc_html_e('Plugins', 'packit-plugin-theme-exporter'); ?></a>
                <a href="#" class="ept-tab" data-target="themes-content"><?php esc_html_e('Temas', 'packit-plugin-theme-exporter'); ?></a>
            </div>
            
            <div class="ept-search-box">
                <input type="text" id="ept-search" placeholder="<?php esc_attr_e('Pesquisar...', 'packit-plugin-theme-exporter'); ?>">
            </div>
            
            <div id="plugins-content" class="ept-tab-content active">
                <h2><?php esc_html_e('Plugins Instalados', 'packit-plugin-theme-exporter'); ?></h2>
                <table class="ept-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Nome', 'packit-plugin-theme-exporter'); ?></th>
                            <th><?php esc_html_e('Versão', 'packit-plugin-theme-exporter'); ?></th>
                            <th><?php esc_html_e('Status', 'packit-plugin-theme-exporter'); ?></th>
                            <th><?php esc_html_e('Ações', 'packit-plugin-theme-exporter'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plugins as $plugin): ?>
                        <tr>
                            <td><?php echo esc_html($plugin['name']); ?></td>
                            <td><?php echo esc_html($plugin['version']); ?></td>
                            <td>
                                <?php if ($plugin['active']): ?>
                                    <span class="ept-status-active"><?php esc_html_e('Ativo', 'packit-plugin-theme-exporter'); ?></span>
                                <?php else: ?>
                                    <span class="ept-status-inactive"><?php esc_html_e('Inativo', 'packit-plugin-theme-exporter'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('tools.php?page=packit-plugin-theme-exporter&export_plugin=' . urlencode($plugin['path'])), 'ept_export')); ?>" class="ept-export-button" data-item-name="<?php echo esc_attr($plugin['name']); ?>" data-item-type="plugin">
                                    <?php esc_html_e('Exportar', 'packit-plugin-theme-exporter'); ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div id="themes-content" class="ept-tab-content">
                <h2><?php esc_html_e('Temas Instalados', 'packit-plugin-theme-exporter'); ?></h2>
                <table class="ept-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Nome', 'packit-plugin-theme-exporter'); ?></th>
                            <th><?php esc_html_e('Versão', 'packit-plugin-theme-exporter'); ?></th>
                            <th><?php esc_html_e('Status', 'packit-plugin-theme-exporter'); ?></th>
                            <th><?php esc_html_e('Ações', 'packit-plugin-theme-exporter'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($themes as $theme): ?>
                        <tr>
                            <td><?php echo esc_html($theme['name']); ?></td>
                            <td><?php echo esc_html($theme['version']); ?></td>
                            <td>
                                <?php if ($theme['active']): ?>
                                    <span class="ept-status-active"><?php esc_html_e('Ativo', 'packit-plugin-theme-exporter'); ?></span>
                                <?php else: ?>
                                    <span class="ept-status-inactive"><?php esc_html_e('Inativo', 'packit-plugin-theme-exporter'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('tools.php?page=packit-plugin-theme-exporter&export_theme=' . urlencode($theme['path'])), 'ept_export')); ?>" class="ept-export-button" data-item-name="<?php echo esc_attr($theme['name']); ?>" data-item-type="theme">
                                    <?php esc_html_e('Exportar', 'packit-plugin-theme-exporter'); ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}