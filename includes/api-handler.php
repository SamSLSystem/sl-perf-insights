<?php
// Gestion de l’appel à l’API Google PageSpeed Insights pour une seule page avec les résultats Lighthouse
function slpi_analyze_single_page() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission refusée.');
    }

    if (empty($_POST['page_url'])) {
        wp_send_json_error('URL de la page manquante.');
    }

    if (empty($_POST['post_type'])) {
        wp_send_json_error('Type de contenu manquant.');
    }

    $page_url = esc_url_raw($_POST['page_url']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $post_type = get_post_type($post_id);  // Récupérer le type de contenu (page ou post)
    $api_key = get_option('slpi_api_key', '');

    if (empty($api_key)) {
        wp_send_json_error('Clé API manquante. Veuillez configurer votre clé API dans les paramètres slpi.');
    }

    $url = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';
    $timeout = 30;

    // Appels API pour mobile et desktop
    $response_mobile = wp_remote_get("$url?url=$page_url&key=$api_key&strategy=mobile", ['timeout' => $timeout]);
    $response_desktop = wp_remote_get("$url?url=$page_url&key=$api_key&strategy=desktop", ['timeout' => $timeout]);

    if (is_wp_error($response_mobile) || is_wp_error($response_desktop)) {
        wp_send_json_error('Erreur lors de l’appel à l’API.');
    }

    $data_mobile = json_decode(wp_remote_retrieve_body($response_mobile), true);
    $data_desktop = json_decode(wp_remote_retrieve_body($response_desktop), true);

    $mobile_score = isset($data_mobile['lighthouseResult']['categories']['performance']['score']) 
        ? $data_mobile['lighthouseResult']['categories']['performance']['score'] * 100 
        : 'Score non disponible';
    
    $desktop_score = isset($data_desktop['lighthouseResult']['categories']['performance']['score']) 
        ? $data_desktop['lighthouseResult']['categories']['performance']['score'] * 100 
        : 'Score non disponible';

    $audit_results_mobile = isset($data_mobile['lighthouseResult']['audits']) 
        ? extractLighthouseMetrics($data_mobile['lighthouseResult']['audits']) 
        : [];
    
    $audit_results_desktop = isset($data_desktop['lighthouseResult']['audits']) 
        ? extractLighthouseMetrics($data_desktop['lighthouseResult']['audits']) 
        : [];

    $result = [
        'mobile_score' => $mobile_score,
        'desktop_score' => $desktop_score,
        'audit_results_mobile' => $audit_results_mobile,
        'audit_results_desktop' => $audit_results_desktop,
    ];

    // Sauvegarder les résultats avec le post_type
    slpi_store_results($page_url, $result, $post_type);

    wp_send_json_success($result);
}
add_action('wp_ajax_slpi_analyze_single_page', 'slpi_analyze_single_page');

// Fonction pour stocker les résultats dans la base de données
function slpi_store_results($url, $result, $post_type) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'slpi_results';

    // Vérification de l'existence de 'post_type' dans la requête POST
    if (empty($post_type)) {
        // Si 'post_type' est vide, essayer de récupérer depuis l'URL
        $post_id = url_to_postid($url);  // Convertir l'URL en post_id
        $post_type = get_post_type($post_id);  // Récupérer le type de contenu (page ou post)
    }
    
    // Vérifier si l'URL correspond à la page d'accueil
    $homepage_url = get_home_url();
    if ($url === $homepage_url) {
        $post_type = 'home'; // Forcer le post_type à 'home' pour la page d'accueil
    }
    
    // Assurez-vous que le post_type est valide
    if (!$post_type) {
        $post_type = 'inconnu';  // Définir un type par défaut en cas d'échec
    }

    // Enregistrer les résultats dans la table
    $wpdb->insert(
        $table_name,
        [
            'url' => $url,
            'post_type' => $post_type,
            'date_analyse' => current_time('mysql'),
            'score_mobile' => $result['mobile_score'],
            'score_desktop' => $result['desktop_score'],
            'fcp_mobile' => $result['audit_results_mobile']['First Contentful Paint'],
            'lcp_mobile' => $result['audit_results_mobile']['Largest Contentful Paint'],
            'tbt_mobile' => $result['audit_results_mobile']['Total Blocking Time'],
            'cls_mobile' => $result['audit_results_mobile']['Cumulative Layout Shift'],
            'tti_mobile' => $result['audit_results_mobile']['Time to Interactive'],
            'speed_index_mobile' => $result['audit_results_mobile']['Speed Index'],
            'fcp_desktop' => $result['audit_results_desktop']['First Contentful Paint'],
            'lcp_desktop' => $result['audit_results_desktop']['Largest Contentful Paint'],
            'tbt_desktop' => $result['audit_results_desktop']['Total Blocking Time'],
            'cls_desktop' => $result['audit_results_desktop']['Cumulative Layout Shift'],
            'tti_desktop' => $result['audit_results_desktop']['Time to Interactive'],
            'speed_index_desktop' => $result['audit_results_desktop']['Speed Index'],
        ],
        [
            '%s', '%s', '%s', '%d', '%d', // Types des colonnes
            '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s'
        ]
    );
}

// Fonction pour extraire les métriques importantes de Lighthouse
function extractLighthouseMetrics($audits) {
    return [
        'First Contentful Paint' => $audits['first-contentful-paint']['displayValue'] ?? 'Non disponible',
        'Largest Contentful Paint' => $audits['largest-contentful-paint']['displayValue'] ?? 'Non disponible',
        'Total Blocking Time' => $audits['total-blocking-time']['displayValue'] ?? 'Non disponible',
        'Cumulative Layout Shift' => $audits['cumulative-layout-shift']['displayValue'] ?? 'Non disponible',
        'Speed Index' => $audits['speed-index']['displayValue'] ?? 'Non disponible',
        'Time to Interactive' => $audits['interactive']['displayValue'] ?? 'Non disponible',
    ];
}

// Action AJAX pour récupérer l’historique des analyses
function slpi_get_history() {
    global $wpdb;
    
    // Vérification des permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Permission refusée.');
    }

    $table_name = $wpdb->prefix . 'slpi_results';
    
    // Récupération des  analyses
    $results = $wpdb->get_results("SELECT id, date_analyse, url, post_type, score_desktop, score_mobile FROM $table_name ORDER BY date_analyse DESC", ARRAY_A);

    if (empty($results)) {
        wp_send_json_error('Aucune donnée disponible.');
    }

    wp_send_json_success($results);
}
function slpi_delete_history_bulk() {
    // Vérification des permissions
    if (!current_user_can('manage_options')) {
        error_log('Erreur : Permission refusée.');
        wp_send_json_error('Permission refusée.');
    }

    // Vérifier que les IDs sont envoyés
    if (empty($_POST['ids'])) {
        error_log('Erreur : Aucun ID sélectionné.');
        wp_send_json_error('Aucun ID sélectionné.');
    }

    // Initialisation des logs
    $logs = [];

    // Log pour vérifier les IDs reçus
    $logs[] = 'IDs reçus pour suppression : ' . print_r($_POST['ids'], true);

    $ids = array_map('intval', $_POST['ids']);  // Assurez-vous que les IDs sont des entiers

    // Log pour vérifier les IDs après conversion en entiers
    $logs[] = 'IDs convertis en entiers : ' . print_r($ids, true);

    global $wpdb;
    $table_name = $wpdb->prefix . 'slpi_results';  // Cela prend automatiquement le préfixe correct.

    // Vérifier que la table existe
    $query = "SHOW TABLES LIKE '$table_name'";
    $results = $wpdb->get_results($query);
    error_log('Table vérifiée : ' . $table_name . ' | Résultat SHOW TABLES : ' . print_r($results, true));

    // Vérifier si les IDs existent dans la base de données avant suppression
    $ids_check = implode(",", $ids);
    $check_query = "SELECT id FROM $table_name WHERE id IN ($ids_check)";
    $check_results = $wpdb->get_results($check_query);
    error_log('Vérification des IDs avant suppression : ' . print_r($check_results, true));

    // Créer des placeholders pour la requête SQL
    $placeholders = implode(',', array_fill(0, count($ids), '%d'));  // Créer des placeholders pour les IDs
    $delete_query = "DELETE FROM $table_name WHERE id IN ($placeholders)";

    // Log de la requête SQL avant exécution
    error_log('Requête DELETE exécutée : ' . $delete_query);

    // Suppression des enregistrements en masse
    $deleted = $wpdb->query($wpdb->prepare($delete_query, ...$ids));

    // Log pour vérifier si la suppression a eu lieu
    if ($deleted) {
        $logs[] = 'Entrées supprimées avec succès.';
        wp_send_json_success(['message' => 'Entrées supprimées avec succès.', 'logs' => $logs]);
    } else {
        $logs[] = 'Erreur lors de la suppression des entrées.';
        wp_send_json_error(['message' => 'Erreur lors de la suppression des entrées.', 'logs' => $logs]);
    }
}

add_action('wp_ajax_slpi_delete_history_bulk', 'slpi_delete_history_bulk');

add_action('wp_ajax_slpi_get_history', 'slpi_get_history');
?>