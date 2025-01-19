jQuery(document).ready(function ($) {
    console.log('Script tabs.js chargé et DOM prêt.');

    // Gestion des clics sur les onglets
    $('.slpi-tab-list li').on('click', function () {
        var tabName = $(this).data('tab'); // Récupère le nom de l'onglet
        console.log('Onglet cliqué : ' + tabName);

        // Réinitialise l'état des onglets et des contenus
        $('.slpi-tab-list li').removeClass('active');
        $('.slpi-tab-content').removeClass('active');

        // Active l'onglet cliqué et le contenu correspondant
        $(this).addClass('active');
        $('[data-tab-content="' + tabName + '"]').addClass('active');
    });

    // Activer le premier onglet et son contenu au chargement
    $('.slpi-tab-list li[data-tab="dashboard"]').addClass('active');
    $('.slpi-tab-content[data-tab-content="dashboard"]').addClass('active');
    console.log('Onglet actif par défaut :', $('.slpi-tab-list li:first').data('tab'));
});