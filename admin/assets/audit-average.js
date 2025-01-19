jQuery(document).ready(function ($) {
    console.log('audit-average.js chargé et exécuté.');

    // Fonction pour charger les scores moyens
    function loadAverageScores() {
        $.ajax({
            url: slpiData.ajaxurl,
            method: 'POST',
            data: { action: 'slpi_get_average_scores' },
            success(response) {
                if (response.success) {
                    console.log('Données reçues pour scores moyens :', response.data);
                    renderProgressiveDoughnuts(response.data, 'average');
                    displayAverageMetrics(response.data);
                } else {
                    console.error('Erreur lors du chargement des scores moyens.');
                }
            },
            error(xhr, status, error) {
                console.error('Erreur AJAX :', error);
            }
        });
    }

    // Fonction pour afficher les métriques moyennes
    function displayAverageMetrics(data) {
    // Helper pour formater les métriques
    function formatMetric(value, metricType) {
        switch (metricType) {
            case 'FCP':
            case 'LCP':
            case 'Speed Index':
                return parseFloat(value).toFixed(1) + ' s'; // 1 décimale + s
            case 'TBT':
                return parseInt(value, 10) + ' ms'; // En ms
            case 'CLS':
                   // Convertir en nombre, limiter à 3 décimales
            return parseFloat(value).toFixed(3);
            default:
                return parseInt(value, 10); // Nombre entier pour les scores globaux
        }
    }

    // Résultats Mobile (moyenne)
    setMetricValue('average-mobile-fcp', formatMetric(data.avg_fcp_mobile, 'FCP'), 'FCP', 'mobile');
    setMetricIcon('average-mobile-fcp-icon', data.avg_fcp_mobile, 'FCP', 'mobile');

    setMetricValue('average-mobile-lcp', formatMetric(data.avg_lcp_mobile, 'LCP'), 'LCP', 'mobile');
    setMetricIcon('average-mobile-lcp-icon', data.avg_lcp_mobile, 'LCP', 'mobile');

    setMetricValue('average-mobile-tbt', formatMetric(data.avg_tbt_mobile, 'TBT'), 'TBT', 'mobile');
    setMetricIcon('average-mobile-tbt-icon', data.avg_tbt_mobile, 'TBT', 'mobile');

    setMetricValue('average-mobile-cls', formatMetric(data.avg_cls_mobile, 'CLS'), 'CLS','mobile');
    setMetricIcon('average-mobile-cls-icon', data.avg_cls_mobile, 'CLS', 'mobile');

    setMetricValue('average-mobile-speed-index', formatMetric(data.avg_speed_index_mobile, 'Speed Index'), 'Speed Index', 'mobile');
    setMetricIcon('average-mobile-speed-index-icon', data.avg_speed_index_mobile, 'Speed Index', 'mobile');

    // Résultats Desktop (moyenne)
    setMetricValue('average-desktop-fcp', formatMetric(data.avg_fcp_desktop, 'FCP'), 'FCP', 'desktop');
    setMetricIcon('average-desktop-fcp-icon', data.avg_fcp_desktop, 'FCP', 'desktop');

    setMetricValue('average-desktop-lcp', formatMetric(data.avg_lcp_desktop, 'LCP'), 'LCP', 'desktop');
    setMetricIcon('average-desktop-lcp-icon', data.avg_lcp_desktop, 'LCP', 'desktop');

    setMetricValue('average-desktop-tbt', formatMetric(data.avg_tbt_desktop, 'TBT'), 'TBT', 'desktop');
    setMetricIcon('average-desktop-tbt-icon', data.avg_tbt_desktop, 'TBT', 'desktop');

    setMetricValue('average-desktop-cls', formatMetric(data.avg_cls_desktop, 'CLS'), 'CLS','desktop');
    setMetricIcon('average-desktop-cls-icon', data.avg_cls_desktop, 'CLS', 'desktop');

    setMetricValue('average-desktop-speed-index', formatMetric(data.avg_speed_index_desktop, 'Speed Index'), 'Speed Index', 'desktop');
    setMetricIcon('average-desktop-speed-index-icon', data.avg_speed_index_desktop, 'Speed Index', 'desktop');
}
    // Charger les scores moyens au chargement de la page
    loadAverageScores();
});