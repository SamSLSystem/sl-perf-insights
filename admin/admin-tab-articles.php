<?php
// Onglet Articles : Affiche la liste des articles publiés avec un bouton d’analyse
// Récupérer tous les articles publiés
$args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
);
$articles = get_posts($args);
?>

<div id="content-tab-articles" class="slpi-tab-content">
    <p>Sélectionnez les articles que vous souhaitez analyser :</p>
    <table class="widefat" id="slpi-article-list">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all-articles"></th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><input type="checkbox" class="article-checkbox" value="<?php echo esc_url(get_permalink($article->ID)); ?>"></td>
                    <td><?php echo esc_url(get_permalink($article->ID)); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button id="slpi-analyze-btn-articles" class="button button-primary" style="margin-top: 20px;">Lancer l’analyse</button>
    <div id="slpi-result-articles" style="margin-top: 20px;"></div>
</div>