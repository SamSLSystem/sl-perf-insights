<?php
/*
Plugin Name: SL Perf Insights
Description: Un outil puissant et intuitif pour analyser et améliorer les performances de votre site WordPress. Exploitez l'API Google PageSpeed Insights pour obtenir des audits détaillés, suivre vos scores (mobile et desktop), et optimiser l'expérience utilisateur.
Version: 1.0
Author: SL SYSTEM
Author URI: https://www.slsystem.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: sl-perf-insights
*/

// Sécurité : Empêcher un accès direct au fichier
if (!defined('ABSPATH')) {
    exit;
}

// Définir le chemin et l'URL du plugin
define('SLPI_PATH', plugin_dir_path(__FILE__));
define('SLPI_URL', plugin_dir_url(__FILE__));
define('SLPI_DB_VERSION', '1.1'); // Version de la base de données

// Inclure les fichiers nécessaires
require_once SLPI_PATH . 'admin/admin-page.php';
require_once SLPI_PATH . 'includes/api-handler.php';
require_once SLPI_PATH . 'includes/audit-handler.php';


// Fonction pour créer ou mettre à jour la table
function slpi_create_results_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'slpi_results';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        url TEXT NOT NULL,
        post_type VARCHAR(20) NOT NULL,
        date_analyse DATETIME NOT NULL,
        score_mobile INT(3) DEFAULT NULL,
        score_desktop INT(3) DEFAULT NULL,
        fcp_mobile VARCHAR(20) DEFAULT NULL,
        lcp_mobile VARCHAR(20) DEFAULT NULL,
        tbt_mobile VARCHAR(20) DEFAULT NULL,
        cls_mobile VARCHAR(20) DEFAULT NULL,
        tti_mobile VARCHAR(20) DEFAULT NULL,
        speed_index_mobile VARCHAR(20) DEFAULT NULL,
        fcp_desktop VARCHAR(20) DEFAULT NULL,
        lcp_desktop VARCHAR(20) DEFAULT NULL,
        tbt_desktop VARCHAR(20) DEFAULT NULL,
        cls_desktop VARCHAR(20) DEFAULT NULL,
        tti_desktop VARCHAR(20) DEFAULT NULL,
        speed_index_desktop VARCHAR(20) DEFAULT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Fonction d’activation du plugin
function slpi_activate() {
    slpi_create_results_table();
    update_option('slpi_db_version', SLPI_DB_VERSION);
}
register_activation_hook(__FILE__, 'slpi_activate');

// Vérifier et mettre à jour la base de données si nécessaire lors du chargement du plugin
function slpi_check_db_update() {
    $current_version = get_option('slpi_db_version');
    
    if ($current_version !== SLPI_DB_VERSION) {
        slpi_create_results_table();
        update_option('slpi_db_version', SLPI_DB_VERSION);
    }
}
add_action('plugins_loaded', 'slpi_check_db_update');

// Ajouter une page d’administration
function slpi_admin_menu() {
    add_menu_page(
        'SL Perf Insights',
        'SL Perf Insights',
        'manage_options',
        'sl-perf-insights',
        'slpi_admin_page',
        'dashicons-performance'
    );
}
add_action('admin_menu', 'slpi_admin_menu');

// Enregistrer et inclure les scripts JS et le style CSS pour l'admin
function slpi_enqueue_assets($hook) {
    // Vérifier qu’on est bien sur la page d’administration SL Perf Insights
    if ($hook !== 'toplevel_page_sl-perf-insights') {
        return;
    }
    
    
    
    
    // Inclure le fichier CSS de DataTables
    wp_enqueue_style('datatables-style', 'https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css');
    
    // Inclure le fichier JS de DataTables
    wp_enqueue_script('datatables-script', 'https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js', array('jquery'), '1.13.1', true);
    
    // Inclure le fichier CSS du plugin
    wp_enqueue_style('slpi-style', SLPI_URL . 'admin/assets/style.css', [], '1.0');

    // Inclure la bibliothèque Chart.js
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js', [], '4.4.7', true);

    // Inclure les fichiers JS divisés
    wp_enqueue_script('slpi-tabs', SLPI_URL . 'admin/assets/tabs.js', array('jquery'), '1.0', true);
    wp_enqueue_script('slpi-history', SLPI_URL . 'admin/assets/history.js', array('jquery'), '1.0', true);
    wp_enqueue_script('slpi-analysis', SLPI_URL . 'admin/assets/analysis.js', array('jquery'), '1.0', true);
    wp_enqueue_script('slpi-helpers', SLPI_URL . 'admin/assets/helpers.js', array('jquery'), '1.0', true);
    wp_enqueue_script('slpi-main', SLPI_URL . 'admin/assets/main.js', array('jquery', 'slpi-tabs', 'slpi-history', 'slpi-analysis', 'slpi-helpers'), '1.0', true);

    // Inclure le script pour la carte "Score de la page d'accueil et moyennes"
    wp_enqueue_script('chart-manager', SLPI_URL . 'admin/assets/chart-manager.js', ['chart-js'], '1.0', true);
    wp_enqueue_script('audit-homepage-js', SLPI_URL . 'admin/assets/audit-homepage.js', ['jquery', 'chart-js'], '1.0', true);
    wp_enqueue_script('audit-average-script', SLPI_URL . 'admin/assets/audit-average.js', ['jquery', 'audit-homepage-js'], '1.0', true);
    wp_enqueue_script('slpi-quadrant', SLPI_URL . 'admin/assets/quadrant.js', ['jquery', 'chart-js', 'datatables-script'], '1.0', true);
    
    // Passer ajaxurl et l’URL de la page d’accueil à audit.js
    wp_localize_script('audit-homepage-js', 'slpiData', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'homepageUrl' => home_url()
    ]);
}
add_action('admin_enqueue_scripts', 'slpi_enqueue_assets');

// Fonction de désinstallation du plugin
function slpi_uninstall() {
    // Vérifie si l'option "conserver les données" est activée
    $keep_data = get_option('slpi_keep_data');
    
    if (!$keep_data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'slpi_results';
        
        // Supprime la table si l'option "conserver les données" n'est pas activée
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
    
    // Supprime les options du plugin
    delete_option('slpi_api_key');
    delete_option('slpi_keep_data');
}

// Enregistre la fonction de désinstallation
register_uninstall_hook(__FILE__, 'slpi_uninstall');
?>