<?php
// Onglet Pages : Affiche la liste des pages publiées avec un bouton d’analyse

global $wp_rewrite;

// Vérification et initialisation de $wp_rewrite si nécessaire
if (!$wp_rewrite) {
    require_once ABSPATH . 'wp-includes/rewrite.php';
    $wp_rewrite = new WP_Rewrite();
}

// Récupérer toutes les pages publiées
$args = array(
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
);
$pages = get_posts($args);
?>

<div id="content-tab-pages" class="slpi-tab-content" style="display: none;">
    <p>Sélectionnez les pages que vous souhaitez analyser :</p>
    <table class="widefat" id="slpi-page-list">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all-pages"></th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page): ?>
                <tr>
                    <?php
                    // Récupérer l'URL de la page
                    $url = get_permalink($page->ID);
                    if (!$url) {
                        $url = home_url('/?page_id=' . $page->ID); // URL brute en cas d’échec
                    }
                    ?>
                    <td><input type="checkbox" class="page-checkbox" value="<?php echo esc_url($url); ?>"></td>
                    <td><?php echo esc_url($url); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button id="slpi-analyze-btn-pages" class="button button-primary" style="margin-top: 20px;">Lancer l’analyse</button>
    <div id="slpi-result-pages" style="margin-top: 20px;"></div>
</div>