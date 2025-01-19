// Gestionnaire centralisé des graphiques
const chartInstances = {};

// Fonction pour enregistrer un graphique
function registerChart(chartId, chartInstance) {
    if (chartInstances[chartId]) {
        chartInstances[chartId].destroy(); // Détruire l'ancien graphique s'il existe
    }
    chartInstances[chartId] = chartInstance; // Enregistrer la nouvelle instance
}

// Fonction pour détruire un graphique
function destroyChart(chartId) {
    if (chartInstances[chartId]) {
        chartInstances[chartId].destroy();
        delete chartInstances[chartId];
    }
}

// chart-manager.js (exposer globalement)
window.scoreColors = {
    'score-good': '#28a745',    // Vert
    'score-average': '#ffc107', // Orange
    'score-bad': '#dc3545'      // Rouge
};

const activeCharts = {};

// Fonction pour détruire un graphique existant avant de le redessiner
function destroyChart(canvasId) {
    const canvas = document.getElementById(canvasId);

    if (canvas && canvas.chart) {
        canvas.chart.destroy();
        delete canvas.chart;
    }
}
// Plugin personnalisé pour afficher du texte au centre des doughnuts
// Expose le plugin globalement
window.centerTextPlugin = {
    id: 'centerText',
    beforeDraw(chart) {
        const ctx = chart.ctx;
        const width = chart.width;
        const height = chart.height;
        const centerText = chart.options.plugins.centerText;
        const dataset = chart.data.datasets[0];
        const primaryColor = dataset.backgroundColor[0]; // Première couleur (segment principal)

        if (centerText && centerText.display) {
            ctx.save();
            ctx.font = 'bold 24px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = primaryColor; // Utiliser la couleur du segment principal
            ctx.fillText(centerText.text, width / 2, height / 2);
            ctx.restore();
        }
    }
};

const progressAnimationPlugin = {
    id: 'progressAnimation',
    beforeDatasetDraw(chart) {
        const dataset = chart.data.datasets[0];
        if (!dataset.currentValue) dataset.currentValue = 0;

        // Si la valeur actuelle a atteint la cible, arrêter l'animation
        if (dataset.currentValue >= dataset.targetValue) {
            dataset.currentValue = dataset.targetValue; // S'assurer qu'on atteint exactement la cible
            return;
        }

        // Incrémentation progressive
        dataset.currentValue += 1; // Ajustez cette valeur pour contrôler la vitesse
        dataset.data = [dataset.currentValue, 100 - dataset.currentValue];
    }
};

// Enregistrer le plugin globalement
Chart.register(progressAnimationPlugin);


 // Fonction pour afficher les résultats détaillés sous forme de doughnuts progressifs
 window.renderProgressiveDoughnuts = function (finalScores, type) {
    console.log('Scores reçus pour les graphiques :', finalScores, 'Type :', type);

    // Détection des scores et des canvases selon le type
    let mobileScore, desktopScore, mobileCanvas, desktopCanvas;

    if (type === 'homepage') {
        mobileScore = parseInt(finalScores.score_mobile, 10); // Entier
        desktopScore = parseInt(finalScores.score_desktop, 10); // Entier
        mobileCanvas = document.getElementById('homepage-mobile-score-chart');
        desktopCanvas = document.getElementById('homepage-desktop-score-chart');
    } else if (type === 'average') {
        mobileScore = parseInt(finalScores.avg_score_mobile, 10); // Entier
        desktopScore = parseInt(finalScores.avg_score_desktop, 10); // Entier
        mobileCanvas = document.getElementById('average-mobile-score-chart');
        desktopCanvas = document.getElementById('average-desktop-score-chart');
    } else {
        console.error('Type inconnu pour renderProgressiveDoughnuts');
        return;
    }

    // Vérification des canvases
    if (!mobileCanvas || !desktopCanvas) {
        console.error('Les éléments canvas pour les graphiques sont introuvables.');
        return;
    }

    // Détruire les graphiques existants
    if (mobileCanvas.chart) mobileCanvas.chart.destroy();
    if (desktopCanvas.chart) desktopCanvas.chart.destroy();

    const ctxMobile = mobileCanvas.getContext('2d');
    const ctxDesktop = desktopCanvas.getContext('2d');

    const mobileColor = scoreColors[window.getScoreClass(mobileScore)];
    const desktopColor = scoreColors[window.getScoreClass(desktopScore)];

    if (!mobileColor || !desktopColor) {
        console.error('Impossible de déterminer les couleurs des scores.');
        return;
    }

    // Créer les graphiques initiaux
    const mobileChart = new Chart(ctxMobile, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [0, 100],
                backgroundColor: [mobileColor, '#e9ecef'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '80%',
            plugins: {
                tooltip: { enabled: false },
                legend: { display: false },
                centerText: { display: true, text: '0' }
            }
        },
        plugins: [centerTextPlugin]
    });

    const desktopChart = new Chart(ctxDesktop, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [0, 100],
                backgroundColor: [desktopColor, '#e9ecef'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '80%',
            plugins: {
                tooltip: { enabled: false },
                legend: { display: false },
                centerText: { display: true, text: '0' }
            }
        },
        plugins: [centerTextPlugin]
    });

    // Fonction d'animation
    function animateScore(chart, targetScore) {
        let currentScore = 0;
        const interval = setInterval(() => {
            if (currentScore < targetScore) {
                currentScore += 1; // Augmente de 1 (ajustez si nécessaire)
                chart.data.datasets[0].data = [currentScore, 100 - currentScore];
                chart.options.plugins.centerText.text = currentScore.toString();
                chart.update('none'); // Mise à jour sans animation complète
            } else {
                clearInterval(interval); // Arrête l'animation une fois la cible atteinte
            }
        }, 20); // 20ms par incrément (ajustez pour ralentir ou accélérer)
    }

    // Lancer les animations pour les deux graphiques
    animateScore(mobileChart, mobileScore);
    animateScore(desktopChart, desktopScore);

    // Associer les graphiques aux canvases
    mobileCanvas.chart = mobileChart;
    desktopCanvas.chart = desktopChart;

    console.log('Graphiques animés créés.');
};

    // Animation des scores
    function animateScore(mobileChart, desktopChart, finalScores) {
        let currentMobileScore = 0;
        let currentDesktopScore = 0;
        const incrementMobile = Math.ceil(finalScores.score_mobile / 20); // Divise l'animation en 20 étapes
        const incrementDesktop = Math.ceil(finalScores.score_desktop / 20); // Idem pour desktop
        const interval = 50; // Intervalle en ms entre chaque mise à jour
    
        const animationInterval = setInterval(function () {
            let updated = false;
    
            if (currentMobileScore < finalScores.score_mobile) {
                currentMobileScore = Math.min(currentMobileScore + incrementMobile, finalScores.score_mobile);
                mobileChart.data.datasets[0].data = [currentMobileScore, 100 - currentMobileScore];
                mobileChart.options.plugins.centerText.text = currentMobileScore;
                mobileChart.update();
                updated = true;
            }
    
            if (currentDesktopScore < finalScores.score_desktop) {
                currentDesktopScore = Math.min(currentDesktopScore + incrementDesktop, finalScores.score_desktop);
                desktopChart.data.datasets[0].data = [currentDesktopScore, 100 - currentDesktopScore];
                desktopChart.options.plugins.centerText.text = currentDesktopScore;
                desktopChart.update();
                updated = true;
            }
    
            if (!updated) {
                clearInterval(animationInterval);
            }
        }, interval);
    }

    // Expose la fonction animateScore pour un usage global
    window.animateScore = animateScore;



// Mettre à jour l'élément avec la classe correspondante
function setMetricValue(elementId, value, metric, deviceType) {
    const element = document.getElementById(elementId);
    const scoreClass = window.getMetricClass(value, metric, deviceType);
    element.innerText = value;
    element.className = scoreClass;
}

// Mettre à jour l'icône de la métrique
function setMetricIcon(elementId, value, metric, deviceType) {
    const element = document.getElementById(elementId);
    const scoreClass = window.getMetricClass(value, metric, deviceType);

    let color;
    if (scoreClass === 'score-good') {
        color = '#28a745';
        element.className = 'icon icon-circle';
    } else if (scoreClass === 'score-average') {
        color = '#ffc107';
        element.className = 'icon icon-square';
    } else {
        color = '#dc3545';
        element.className = 'icon icon-triangle';
    }

    if (element.classList.contains('icon-triangle')) {
        element.style.borderBottomColor = color;
    } else {
        element.style.backgroundColor = color;
    }
}