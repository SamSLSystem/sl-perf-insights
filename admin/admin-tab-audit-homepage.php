<?php
// Sécurité : Empêcher un accès direct au fichier
if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="content-tab-audit" class="slpi-tab-content">
    <div class="audit-analysis-container">
        <h1>Analyse de la Landing Page</h1>

        <!-- Ligne avec les 2 cartes de résultats -->
        <div class="audit-row">
        <div class="audit-card">
    <h3>Résultats Mobile</h3>
    <canvas id="homepage-mobile-score-chart"></canvas>
            <p>
                <span class="icon icon-circle score-good"></span> 90-100
                <span class="icon icon-square score-average"></span> 50-89
                <span class="icon icon-triangle score-bad"></span> 0-49
            </p>
    <table class="metrics-table">
        <tr>
            <td><span class="icon" id="homepage-mobile-fcp-icon"></span></td>
            <td><strong>First Contentful Paint</strong></td>
            <td><span id="homepage-mobile-fcp">--</span></td>
        </tr>
        <tr>
            <td><span class="icon" id="homepage-mobile-lcp-icon"></span></td>
            <td><strong>Largest Contentful Paint</strong></td>
            <td><span id="homepage-mobile-lcp">--</span></td>
        </tr>
        <tr>
            <td><span class="icon" id="homepage-mobile-tbt-icon"></span></td>
            <td><strong>Total Blocking Time</strong></td>
            <td><span id="homepage-mobile-tbt">--</span></td>
        </tr>
        <tr>
            <td><span class="icon" id="homepage-mobile-cls-icon"></span></td>
            <td><strong>Cumulative Layout Shift</strong></td>
            <td><span id="homepage-mobile-cls">--</span></td>
        </tr>
        <tr>
            <td><span class="icon" id="homepage-mobile-speed-index-icon"></span></td>
            <td><strong>Speed Index</strong></td>
            <td><span id="homepage-mobile-speed-index">--</span></td>
        </tr>
    </table>
</div>

<!-- Carte pour les résultats Desktop -->
<div class="audit-card">
    <h3>Résultats Desktop</h3>
    <canvas id="homepage-desktop-score-chart"></canvas>
            <p>
                <span class="icon icon-circle score-good"></span> 90-100
                <span class="icon icon-square score-average"></span> 50-89
                <span class="icon icon-triangle score-bad"></span> 0-49
            </p>
    <table class="metrics-table">
        <tr>
            <td><span class="icon" id="homepage-desktop-fcp-icon"></span></td>
            <td><strong>First Contentful Paint</strong></td>
            <td><span id="homepage-desktop-fcp">--</span></td>
        </tr>
        <tr>
            <td><span class="icon" id="homepage-desktop-lcp-icon"></span></td>
            <td><strong>Largest Contentful Paint</strong></td>
            <td><span id="homepage-desktop-lcp">--</span></td>
        </tr>
        <tr>
            <td><span class="icon" id="homepage-desktop-tbt-icon"></span></td>
            <td><strong>Total Blocking Time</strong></td>
            <td><span id="homepage-desktop-tbt">--</span></td>
        </tr>
        <tr>
            <td><span class="icon" id="homepage-desktop-cls-icon"></span></td>
            <td><strong>Cumulative Layout Shift</strong></td>
            <td><span id="homepage-desktop-cls">--</span></td>
        </tr>
        <tr>
            <td><span class="icon" id="homepage-desktop-speed-index-icon"></span></td>
            <td><strong>Speed Index</strong></td>
            <td><span id="homepage-desktop-speed-index">--</span></td>
        </tr>
    </table>
</div>
        </div>

        <!-- Ligne avec la carte légende -->
        <div class="legend-card">
            <h3>Légende des Métriques</h3>
            <div class="legend-flex-container">
            <div class="legend-item">
            <p><strong>First Contentful Paint</strong><br>Indique quand le premier texte ou image est affiché.</p>
            <p>
                <strong>Mobile :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 1.8 s
                <span class="icon icon-square score-average"></span> Moyen ≤ 3 s
                <span class="icon icon-triangle score-bad"></span> Mauvais > 3 s
            </p>
            <p>
                <strong>Desktop :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 1 s
                <span class="icon icon-square score-average"></span> Moyen ≤ 2.5 s
                <span class="icon icon-triangle score-bad"></span> Mauvais > 2.5 s
            </p>
        </div>

        <div class="legend-item">
            <p><strong>Largest Contentful Paint</strong><br>Indique quand le plus grand élément est affiché.</p>
            <p>
                <strong>Mobile :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 2.5 s
                <span class="icon icon-square score-average"></span> Moyen ≤ 4 s
                <span class="icon icon-triangle score-bad"></span> Mauvais > 4 s
            </p>
            <p>
                <strong>Desktop :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 1.2 s
                <span class="icon icon-square score-average"></span> Moyen ≤ 2.5 s
                <span class="icon icon-triangle score-bad"></span> Mauvais > 2.5 s
            </p>
        </div>

        <!-- Ligne 2 avec deux métriques -->
        <div class="legend-item">
            <p><strong>Total Blocking Time</strong><br>Somme des périodes où les tâches dépassent 50 ms.</p>
            <p>
                <strong>Mobile :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 200 ms
                <span class="icon icon-square score-average"></span> Moyen ≤ 600 ms
                <span class="icon icon-triangle score-bad"></span> Mauvais > 600 ms
            </p>
            <p>
                <strong>Desktop :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 150 ms
                <span class="icon icon-square score-average"></span> Moyen ≤ 500 ms
                <span class="icon icon-triangle score-bad"></span> Mauvais > 500 ms
            </p>
        </div>

        <div class="legend-item">
            <p><strong>Cumulative Layout Shift</strong><br>Mesure le déplacement des éléments visibles.</p>
            <p>
                <strong>Mobile :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 0.1
                <span class="icon icon-square score-average"></span> Moyen ≤ 0.25
                <span class="icon icon-triangle score-bad"></span> Mauvais > 0.25
            </p>
            <p>
                <strong>Desktop :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 0.1
                <span class="icon icon-square score-average"></span> Moyen ≤ 0.25
                <span class="icon icon-triangle score-bad"></span> Mauvais > 0.25
            </p>
        </div>

        <!-- Ligne 3 avec une métrique -->
        <div class="legend-item">
            <p><strong>Speed Index</strong><br>Indique la vitesse d’affichage du contenu.</p>
            <p>
                <strong>Mobile :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 3.4 s
                <span class="icon icon-square score-average"></span> Moyen ≤ 5.8 s
                <span class="icon icon-triangle score-bad"></span> Mauvais > 5.8 s
            </p>
            <p>
                <strong>Desktop :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≤ 1.3 s
                <span class="icon icon-square score-average"></span> Moyen ≤ 2.3 s
                <span class="icon icon-triangle score-bad"></span> Mauvais > 2.3 s
            </p>
        </div>

        <!-- Ligne pour le score global -->
        <div class="legend-item">
            <p><strong>Score Global</strong><br>Indique la performance globale de la page.</p>
            <p>
                <strong>Mobile :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≥ 90
                <span class="icon icon-square score-average"></span> Moyen ≥ 50
                <span class="icon icon-triangle score-bad"></span> Mauvais < 50
            </p>
            <p>
                <strong>Desktop :</strong>
                <span class="icon icon-circle score-good"></span> Bon ≥ 90
                <span class="icon icon-square score-average"></span> Moyen ≥ 50
                <span class="icon icon-triangle score-bad"></span> Mauvais < 50
            </p>
        </div>
    </div>
</div>

    </div>
</div>
