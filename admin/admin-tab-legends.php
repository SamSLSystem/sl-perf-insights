<?php
// Sécurité : Empêcher un accès direct
if (!defined('ABSPATH')) {
    exit;
}
?>

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