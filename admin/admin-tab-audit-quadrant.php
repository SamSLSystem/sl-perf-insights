<?php
if (!defined('ABSPATH')) {
    exit; // Sécurité
}

// 1. Récupération des données depuis la base de données
global $wpdb;
$table_name = $wpdb->prefix . 'slpi_results';

$results = $wpdb->get_results("
    SELECT
        id,
        url,
        post_type,
        date_analyse,
        score_mobile,
        score_desktop,
        fcp_mobile,
        lcp_mobile,
        tbt_mobile,
        cls_mobile,
        tti_mobile,
        speed_index_mobile,
        fcp_desktop,
        lcp_desktop,
        tbt_desktop,
        cls_desktop,
        tti_desktop,
        speed_index_desktop
    FROM $table_name
    ORDER BY id DESC
");

// Préparation des données pour le graphique
$chartData = [];
if (!empty($results)) {
    foreach ($results as $row) {
        $chartData[] = [
            'x' => is_numeric($row->score_mobile) ? floatval($row->score_mobile) : 0,
            'y' => is_numeric($row->score_desktop) ? floatval($row->score_desktop) : 0,
            'id' => $row->id,
            'url' => $row->url,
            'post_type' => $row->post_type,
            'date_analyse' => $row->date_analyse,
            'fcp_mobile' => $row->fcp_mobile,
            'lcp_mobile' => $row->lcp_mobile,
            'tbt_mobile' => $row->tbt_mobile,
            'cls_mobile' => $row->cls_mobile,
            'tti_mobile' => $row->tti_mobile,
            'speed_index_mobile' => $row->speed_index_mobile,
            'fcp_desktop' => $row->fcp_desktop,
            'lcp_desktop' => $row->lcp_desktop,
            'tbt_desktop' => $row->tbt_desktop,
            'cls_desktop' => $row->cls_desktop,
            'tti_desktop' => $row->tti_desktop,
            'speed_index_desktop' => $row->speed_index_desktop,
        ];
    }
}

// Localisation des données pour le script JavaScript
wp_localize_script('slpi-quadrant', 'slpiQuadrantData', [
    'chartPoints' => $chartData
]);
?>

<div class="slpi-quadrant-container">
    <h3>Quadrant - Score Mobile vs. Score Desktop</h3>

    <!-- Canvas pour le Scatter Chart -->
    <canvas id="slpiQuadrantChart" style="max-width:800px; max-height:500px;"></canvas>

    <hr />

    <!-- Modal personnalisé pour afficher les détails d'un point -->
    <div id="detailModal" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <span class="custom-close">&times;</span>
            <h2>Détails du Point</h2>
            <p><strong>Nom de la Page :</strong> <span id="modalPageName"></span></p>
            <p><strong>Score Mobile :</strong> <span id="modalScoreMobile"></span></p>
            <p><strong>Score Desktop :</strong> <span id="modalScoreDesktop"></span></p>
            <p><strong>Type de Post :</strong> <span id="modalPostType"></span></p>
            <p><strong>URL :</strong> <a href="#" target="_blank" id="modalURL"></a></p>
        </div>
    </div>

    <!-- Tableau (DataTables) avec les résultats complets -->
    <table id="slpiQuadrantTable" class="display" style="width:100%; margin-top:20px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>URL</th>
                <th>Type</th>
                <th>Date Analyse</th>
                <th>Score Mobile</th>
                <th>Score Desktop</th>
                <th>FCP Mobile</th>
                <th>LCP Mobile</th>
                <th>TBT Mobile</th>
                <th>CLS Mobile</th>
                <th>TTI Mobile</th>
                <th>Speed Index Mobile</th>
                <th>FCP Desktop</th>
                <th>LCP Desktop</th>
                <th>TBT Desktop</th>
                <th>CLS Desktop</th>
                <th>TTI Desktop</th>
                <th>Speed Index Desktop</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?php echo esc_html($row->id); ?></td>
                    <td><a href="<?php echo esc_url($row->url); ?>" target="_blank"><?php echo esc_url($row->url); ?></a></td>
                    <td><?php echo esc_html($row->post_type); ?></td>
                    <td><?php echo esc_html($row->date_analyse); ?></td>
                    <td><?php echo esc_html($row->score_mobile); ?></td>
                    <td><?php echo esc_html($row->score_desktop); ?></td>
                    <td><?php echo esc_html($row->fcp_mobile); ?></td>
                    <td><?php echo esc_html($row->lcp_mobile); ?></td>
                    <td><?php echo esc_html($row->tbt_mobile); ?></td>
                    <td><?php echo esc_html($row->cls_mobile); ?></td>
                    <td><?php echo esc_html($row->tti_mobile); ?></td>
                    <td><?php echo esc_html($row->speed_index_mobile); ?></td>
                    <td><?php echo esc_html($row->fcp_desktop); ?></td>
                    <td><?php echo esc_html($row->lcp_desktop); ?></td>
                    <td><?php echo esc_html($row->tbt_desktop); ?></td>
                    <td><?php echo esc_html($row->cls_desktop); ?></td>
                    <td><?php echo esc_html($row->tti_desktop); ?></td>
                    <td><?php echo esc_html($row->speed_index_desktop); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="18">Aucune donnée trouvée.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>