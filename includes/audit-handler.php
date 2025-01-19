<?php
// Sécurité : Empêcher un accès direct au fichier
if (!defined('ABSPATH')) {
    exit;
}

// Fonction AJAX pour récupérer les scores de la page d'accueil
function slpi_get_homepage_scores() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'slpi_results';
    $homepage_url = home_url();

    // Requête pour récupérer les scores et les métriques détaillées de la page d'accueil
    $result = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT score_mobile, score_desktop, 
                    fcp_mobile, lcp_mobile, tbt_mobile, cls_mobile, speed_index_mobile,
                    fcp_desktop, lcp_desktop, tbt_desktop, cls_desktop, speed_index_desktop
             FROM $table_name 
             WHERE url = %s 
             ORDER BY date_analyse DESC 
             LIMIT 1",
            $homepage_url
        ),
        ARRAY_A
    );

    if ($result) {
        wp_send_json_success([
            'score_mobile' => (int) $result['score_mobile'],
            'score_desktop' => (int) $result['score_desktop'],
            'fcp_mobile' => $result['fcp_mobile'],
            'lcp_mobile' => $result['lcp_mobile'],
            'tbt_mobile' => $result['tbt_mobile'],
            'cls_mobile' => $result['cls_mobile'],
            'speed_index_mobile' => $result['speed_index_mobile'],
            'fcp_desktop' => $result['fcp_desktop'],
            'lcp_desktop' => $result['lcp_desktop'],
            'tbt_desktop' => $result['tbt_desktop'],
            'cls_desktop' => $result['cls_desktop'],
            'speed_index_desktop' => $result['speed_index_desktop'],
            'require_analysis' => false // Indiquer que l'analyse n'est pas nécessaire
        ]);
    } else {
        wp_send_json_success(['require_analysis' => true]); // Indiquer qu'une analyse est nécessaire
    }
}
function slpi_get_average_scores() {
    global $wpdb;

    // Nom de la table des résultats
    $table_name = $wpdb->prefix . 'slpi_results';

    // Requête pour calculer les moyennes uniquement à partir des derniers enregistrements par URL
    $result = $wpdb->get_row(
        "SELECT 
            AVG(latest.score_mobile) AS avg_score_mobile, 
            AVG(latest.score_desktop) AS avg_score_desktop,
            AVG(latest.fcp_mobile) AS avg_fcp_mobile, 
            AVG(latest.lcp_mobile) AS avg_lcp_mobile, 
            AVG(latest.tbt_mobile) AS avg_tbt_mobile, 
            AVG(latest.cls_mobile) AS avg_cls_mobile, 
            AVG(latest.speed_index_mobile) AS avg_speed_index_mobile,
            AVG(latest.fcp_desktop) AS avg_fcp_desktop, 
            AVG(latest.lcp_desktop) AS avg_lcp_desktop, 
            AVG(latest.tbt_desktop) AS avg_tbt_desktop, 
            AVG(latest.cls_desktop) AS avg_cls_desktop, 
            AVG(latest.speed_index_desktop) AS avg_speed_index_desktop
        FROM (
            SELECT *
            FROM (
                SELECT *, ROW_NUMBER() OVER (PARTITION BY url ORDER BY date_analyse DESC) AS row_num
                FROM $table_name
            ) AS ranked
            WHERE ranked.row_num = 1
        ) AS latest",
        ARRAY_A
    );

    // Si des résultats sont trouvés, les retourner sous forme de JSON
    if ($result) {
        wp_send_json_success($result);
    } else {
        // Si aucun résultat, retourner une erreur
        wp_send_json_error('Aucune donnée disponible pour les moyennes.');
    }
}

// Enregistrer la fonction pour les actions AJAX (authentifié et non-authentifié)
add_action('wp_ajax_slpi_get_average_scores', 'slpi_get_average_scores');

// Enregistrer la fonction pour les actions AJAX
add_action('wp_ajax_slpi_get_homepage_scores', 'slpi_get_homepage_scores');
add_action('wp_ajax_nopriv_slpi_get_homepage_scores', 'slpi_get_homepage_scores');