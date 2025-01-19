<?php
// Onglet Analyse : Affiche la liste des pages et articles publiés avec un bouton d’analyse combiné

global $wp_rewrite;

// Vérification et initialisation de $wp_rewrite si nécessaire
if (!$wp_rewrite) {
    require_once ABSPATH . 'wp-includes/rewrite.php';
    $wp_rewrite = new WP_Rewrite();
}

// Récupérer toutes les pages publiées
$page_args = array(
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
);
$pages = get_posts($page_args);

// Récupérer tous les articles publiés
$article_args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
);
$articles = get_posts($article_args);
?>

<div data-tab-content="analysis" class="slpi-tab-content">
    <p>Sélectionnez les pages et articles que vous souhaitez analyser :</p>

    <!-- Sélecteur de filtre -->
    
    <select id="slpi-filter-select" style="margin-left: 10px; margin-bottom: 10px;">
        <option value="all" selected>Tout afficher</option>
        <option value="pages">Pages uniquement</option>
        <option value="articles">Articles uniquement</option>
    </select>

    <!-- Ajout d'un espace entre le sélecteur et la table -->
    <div style="height: 20px;"></div>

    <!-- Table de sélection combinée -->
    <table class="widefat" id="slpi-analysis-list">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all-items"></th>
                <th>Type</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($pages as $page): ?>
    <tr class="slpi-item-page">
        <td><input type="checkbox" class="item-checkbox" value="<?php echo esc_url(get_permalink($page->ID)); ?>" data-post-type="page"></td>
        <td>Page</td>
        <td><?php echo esc_url(get_permalink($page->ID)); ?></td>
    </tr>
<?php endforeach; ?>

<?php foreach ($articles as $article): ?>
    <tr class="slpi-item-article">
        <td><input type="checkbox" class="item-checkbox" value="<?php echo esc_url(get_permalink($article->ID)); ?>" data-post-type="post"></td>
        <td>Article</td>
        <td><?php echo esc_url(get_permalink($article->ID)); ?></td>
    </tr>
<?php endforeach; ?>

        </tbody>
    </table>

    <button id="slpi-analyze-btn" class="button button-primary" style="margin-top: 20px;">Lancer l’analyse</button>
    <div id="slpi-result" style="margin-top: 20px;"></div>
</div>