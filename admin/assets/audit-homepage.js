jQuery(document).ready(function ($) {
    console.log('audit-homepage.js est bien chargé et exécuté.');
    function renderHomepageScores(scores) {
        const canvasIds = ['homepage-mobile-score-chart', 'homepage-desktop-score-chart'];
    
        // Appel de la fonction renderProgressiveDoughnuts pour la page d'accueil
        renderProgressiveDoughnuts(response.data, 'homepage');
    }
    // Charger les scores de la page d'accueil
    // Déclarez cette fonction dans audit-homepage.js
    function loadHomepageScores() {
        $.ajax({
            url: slpiData.ajaxurl, // URL configurée par WordPress
            method: 'POST',
            data: { action: 'slpi_get_homepage_scores' },
            success: function(response) {
                console.log('Réponse AJAX reçue pour la page d\'accueil :', response);
    
                if (response.success) {
                    if (response.data.require_analysis) {
                        console.log('Aucune analyse disponible, lancement de l\'analyse...');
                        performHomepageAnalysis(); // Lancer l'analyse
                    } else {
                        console.log('Scores disponibles, mise à jour des graphiques...');
                        renderProgressiveDoughnuts(response.data, 'homepage');
                        displayHomepageMetrics(response.data);
                    }
                } else {
                    console.error('Erreur lors du chargement des scores de la page d\'accueil :', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX :', error);
            }
        });
    }


    // Lancer l'analyse de la page d'accueil
    function performHomepageAnalysis() {
        $.ajax({
            url: slpiData.ajaxurl,
            method: 'POST',
            data: {
                action: 'slpi_analyze_single_page',
                page_url: slpiData.homepageUrl,
                post_type: 'page'
            },
            success: function (response) {
                if (response.success) {
                    console.log('Analyse lancée :', response.data);
                    startAutoRefresh();
                } else {
                    console.error('Erreur lors du lancement de l\'analyse.');
                }
            },
            error: function () {
                console.error('Erreur AJAX lors du lancement de l\'analyse.');
            }
        });
    }

    // Fonction pour démarrer le rafraîchissement automatique des scores
    function startAutoRefresh() {
        var refreshInterval = setInterval(function () {
            console.log('Tentative de rafraîchissement des scores...');
            $.ajax({
                url: slpiData.ajaxurl,
                method: 'POST',
                data: { action: 'slpi_get_homepage_scores' },
                success: function (response) {
                    if (response.success && !response.data.require_analysis) {
                        console.log('Scores disponibles. Mise à jour des graphiques...');
                        clearInterval(refreshInterval); // Arrêter le rafraîchissement
                        renderProgressiveDoughnuts(response.data);
                        displayMetrics(response.data);
                    }
                },
                error: function () {
                    console.error('Erreur AJAX lors du rafraîchissement.');
                }
            });
        }, 10000); // Toutes les 10 secondes
    }

   

  

    // Fonction displayMetrics pour la page d'accueil
function displayHomepageMetrics(data) {
    // Résultats Mobile
    setMetricValue('homepage-mobile-fcp', data.fcp_mobile, 'FCP', 'mobile');
    setMetricIcon('homepage-mobile-fcp-icon', data.fcp_mobile, 'FCP', 'mobile');

    setMetricValue('homepage-mobile-lcp', data.lcp_mobile, 'LCP', 'mobile');
    setMetricIcon('homepage-mobile-lcp-icon', data.lcp_mobile, 'LCP', 'mobile');

    setMetricValue('homepage-mobile-tbt', data.tbt_mobile, 'TBT', 'mobile');
    setMetricIcon('homepage-mobile-tbt-icon', data.tbt_mobile, 'TBT', 'mobile');

    setMetricValue('homepage-mobile-cls', data.cls_mobile, 'CLS', 'mobile');
    setMetricIcon('homepage-mobile-cls-icon', data.cls_mobile, 'CLS', 'mobile');

    setMetricValue('homepage-mobile-speed-index', data.speed_index_mobile, 'Speed Index', 'mobile');
    setMetricIcon('homepage-mobile-speed-index-icon', data.speed_index_mobile, 'Speed Index', 'mobile');

    // Résultats Desktop
    setMetricValue('homepage-desktop-fcp', data.fcp_desktop, 'FCP', 'desktop');
    setMetricIcon('homepage-desktop-fcp-icon', data.fcp_desktop, 'FCP', 'desktop');

    setMetricValue('homepage-desktop-lcp', data.lcp_desktop, 'LCP', 'desktop');
    setMetricIcon('homepage-desktop-lcp-icon', data.lcp_desktop, 'LCP', 'desktop');

    setMetricValue('homepage-desktop-tbt', data.tbt_desktop, 'TBT', 'desktop');
    setMetricIcon('homepage-desktop-tbt-icon', data.tbt_desktop, 'TBT', 'desktop');

    setMetricValue('homepage-desktop-cls', data.cls_desktop, 'CLS', 'desktop');
    setMetricIcon('homepage-desktop-cls-icon', data.cls_desktop, 'CLS', 'desktop');

    setMetricValue('homepage-desktop-speed-index', data.speed_index_desktop, 'Speed Index', 'desktop');
    setMetricIcon('homepage-desktop-speed-index-icon', data.speed_index_desktop, 'Speed Index', 'desktop');
}

    // Charger les scores de la page d'accueil au chargement de la page
    loadHomepageScores();
});