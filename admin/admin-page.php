<?php
// Affiche la page d’administration de SL Perf Insights avec la liste interactive des pages et la clé API
function slpi_admin_page() {
    
    ?>
    <div class="wrap">
        <h1>SL Perf Insights - Analyse de Performance By <a href="https://sl-system.io" target="_blank">SL SYSTEM</a></h1>

        <!-- Liste des onglets -->
        <ul class="slpi-tab-list">
            <li data-tab="dashboard" class="active">Dashboard</li>
            <li data-tab="analysis">Analyse</li>
            <li data-tab="history">Historique</li>
            <li data-tab="settings">Paramètres</li>
            <li data-tab="audit">Audit</li>
        </ul>

        <!-- Contenus des onglets -->
        <div data-tab-content="dashboard" class="slpi-tab-content active">
            <?php include SLPI_PATH . 'admin/admin-tab-dashboard.php'; ?>
        </div>
        <div data-tab-content="analysis" class="slpi-tab-content">
            <?php include SLPI_PATH . 'admin/admin-tab-analysis.php'; ?>
        </div>
        <div data-tab-content="history" class="slpi-tab-content">
            <?php include SLPI_PATH . 'admin/admin-tab-history.php'; ?>
        </div>
        <div data-tab-content="settings" class="slpi-tab-content">
            <?php include SLPI_PATH . 'admin/admin-tab-settings.php'; ?>
        </div>
        <div data-tab-content="audit" class="slpi-tab-content">
        <?php 
        // Inclure le fichier de base de l'onglet audit
        include SLPI_PATH . 'admin/admin-tab-audit.php'; 
    ?>
</div>
    </div>
    <?php
}

// Enregistrer les paramètres de la clé API
function slpi_register_settings() {
    register_setting('slpi_settings', 'slpi_api_key');
    register_setting('slpi_settings', 'slpi_keep_data');
}
add_action('admin_init', 'slpi_register_settings');
?>