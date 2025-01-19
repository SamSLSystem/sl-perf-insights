jQuery(document).ready(function ($) {
    console.log('Script main.js chargé et DOM prêt.');

    // Charger l'historique des analyses au démarrage
    loadHistory();

    // Gestion du bouton d'analyse pour l'onglet Analyse
    $('#slpi-analyze-btn').on('click', function () {
        launchAnalysis('.item-checkbox:visible', '#slpi-result', '#slpi-analyze-btn');
    });

    // Gérer la case "Tout sélectionner" pour l'onglet Analyse
    $('#select-all-items').on('change', function () {
        var isChecked = $(this).prop('checked');
        // Ne cocher que les cases visibles
        $('.item-checkbox:visible').prop('checked', isChecked);
    });

    // Filtrage des pages et articles
    $('#slpi-filter-select').on('change', function () {
        var filter = $(this).val();

        if (filter === 'all') {
            $('#slpi-analysis-list tbody tr').show();
        } else if (filter === 'pages') {
            $('#slpi-analysis-list tbody tr').hide();
            $('.slpi-item-page').show();
        } else if (filter === 'articles') {
            $('#slpi-analysis-list tbody tr').hide();
            $('.slpi-item-article').show();
        }

        // Réinitialiser l'état de la case "Tout sélectionner" après un changement de filtre
        $('#select-all-items').prop('checked', false);
    });
        

});