jQuery(document).ready(function ($) {
    console.log('Script helpers.js chargé et DOM prêt.');

    // Fonction pour obtenir la classe CSS en fonction du score global
    function getScoreClass(score) {
        if (score >= 90) {
            return 'score-good'; // Excellent (vert)
        } else if (score >= 50) {
            return 'score-average'; // Moyen (jaune)
        } else {
            return 'score-bad'; // Mauvais (rouge)
        }
    }

    // Seuils pour les métriques spécifiques (mobile et desktop)
    const metricThresholds = {
        FCP: {
            mobile: { excellent: 1.8, passable: 3 },
            desktop: { excellent: 1.0, passable: 2.5 }
        },
        LCP: {
            mobile: { excellent: 2.5, passable: 4 },
            desktop: { excellent: 1.2, passable: 2.5 }
        },
        TBT: {
            mobile: { excellent: 200, passable: 600 },
            desktop: { excellent: 150, passable: 500 }
        },
        CLS: {
            mobile: { excellent: 0.1, passable: 0.25 },
            desktop: { excellent: 0.1, passable: 0.25 }
        },
        'Speed Index': {
            mobile: { excellent: 3.4, passable: 5.8 },
            desktop: { excellent: 1.3, passable: 2.3 }
        }
    };

    // Fonction pour obtenir la classe CSS des métriques individuelles (avec différenciation mobile/desktop)
    function getMetricClass(value, metricType, deviceType = 'mobile') {
        let numericValue = parseFloat(value);

        if (!metricThresholds[metricType] || !metricThresholds[metricType][deviceType]) {
            return 'score-na'; // Retourne une classe "non applicable" si la métrique ou le type d'appareil n'est pas reconnu
        }

        const thresholds = metricThresholds[metricType][deviceType];

        if (numericValue <= thresholds.excellent) {
            return 'score-good';
        } else if (numericValue <= thresholds.passable) {
            return 'score-average';
        } else {
            return 'score-bad';
        }
    }

    // Exposer les fonctions au contexte global
    window.getScoreClass = getScoreClass;
    window.getMetricClass = getMetricClass;
});