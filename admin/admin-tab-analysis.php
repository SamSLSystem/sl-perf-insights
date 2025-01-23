<?php
// Onglet Analyse : Cartes côte à côte avec bouton centré et carte pour les résultats (largeur 100%)

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
    <h1>Sélectionnez les pages et articles que vous souhaitez analyser :</h1>

    <!-- Sélecteur de filtre -->
    <select id="slpi-filter-select" style="margin-left: 10px; margin-bottom: 20px;">
        <option value="all" selected>Tout afficher</option>
        <option value="pages">Pages uniquement</option>
        <option value="articles">Articles uniquement</option>
    </select>

    <!-- Conteneur des cartes -->
    <div class="analyse-cards-container">
        <!-- Carte des Pages -->
        <div class="audit-card">
            <h3>Pages</h3>
            <table id="pages-table" class="display" style="width:100%;">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all-pages"></th>
                        <th>ID</th>
                        <th>URL</th>
                        <th>Date de publication</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $page): ?>
                        <tr class="slpi-item-page">
                            <td><input type="checkbox" class="item-checkbox" value="<?php echo esc_url(get_permalink($page->ID)); ?>" data-post-type="page"></td>
                            <td><?php echo esc_html($page->ID); ?></td>
                            <td><?php echo esc_url(get_permalink($page->ID)); ?></td>
                            <td><?php echo esc_html($page->post_date); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Carte des Articles -->
        <div class="audit-card">
            <h3>Articles</h3>
            <table id="articles-table" class="display" style="width:100%;">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all-articles"></th>
                        <th>ID</th>
                        <th>URL</th>
                        <th>Date de publication</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr class="slpi-item-article">
                            <td><input type="checkbox" class="item-checkbox" value="<?php echo esc_url(get_permalink($article->ID)); ?>" data-post-type="post"></td>
                            <td><?php echo esc_html($article->ID); ?></td>
                            <td><?php echo esc_url(get_permalink($article->ID)); ?></td>
                            <td><?php echo esc_html($article->post_date); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Conteneur du bouton et des résultats -->
    <div class="analyse-actions-container">
        <!-- Bouton centré -->
        <button id="slpi-analyze-btn" class="button button-primary">Lancer l’analyse</button>

        <!-- Carte pour les résultats -->
        <div class="result-card" id="slpi-result">
            <h3>Résultats de l'analyse</h3>
            <p>Aucun résultat pour l'instant. Lancez une analyse pour voir les détails ici.</p>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialisation de DataTables pour les pages
    $('#pages-table').DataTable({
        "paging": true,
        "searching": true,
        "info": true,
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50],
        "autoWidth": false // Laisser DataTables ajuster les largeurs dynamiquement
    });

    // Initialisation de DataTables pour les articles
    $('#articles-table').DataTable({
        "paging": true,
        "searching": true,
        "info": true,
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50],
        "autoWidth": false // Laisser DataTables ajuster les largeurs dynamiquement
    });

    // Gérer la case "Tout sélectionner" pour les pages
    $('#select-all-pages').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('#pages-table .item-checkbox').prop('checked', isChecked);
    });

    // Gérer la case "Tout sélectionner" pour les articles
    $('#select-all-articles').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('#articles-table .item-checkbox').prop('checked', isChecked);
    });

    // Gérer le filtrage entre "Pages", "Articles", et "Tout afficher"
    $('#slpi-filter-select').on('change', function() {
        var filter = $(this).val();

        if (filter === 'all') {
            $('.audit-card').show(); // Affiche toutes les cartes
        } else if (filter === 'pages') {
            $('.audit-card').hide(); // Masque toutes les cartes
            $('.audit-card:has(#pages-table)').show(); // Affiche uniquement la carte des pages
        } else if (filter === 'articles') {
            $('.audit-card').hide(); // Masque toutes les cartes
            $('.audit-card:has(#articles-table)').show(); // Affiche uniquement la carte des articles
        }
    });
});
</script>