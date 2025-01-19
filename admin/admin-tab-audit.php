<?php
// Sécurité : Empêcher un accès direct
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="audit-wrapper">
    <h2>Audit - Navigation</h2>
    <ul class="audit-tab-list">
        <li data-tab="homepage" class="active">Page d'accueil</li>
        <li data-tab="average">Moyennes</li>
        <li data-tab="quadrant">Quadrant</li>
        <li data-tab="legends">Légendes</li> <!-- Nouvel onglet -->
    </ul>

    <div class="audit-tab-content" id="audit-tab-homepage" style="display: block;">
        <?php include SLPI_PATH . 'admin/admin-tab-audit-homepage.php'; ?>
    </div>
    <div class="audit-tab-content" id="audit-tab-average" style="display: none;">
        <?php include SLPI_PATH . 'admin/admin-tab-audit-average.php'; ?>
    </div>
    <div class="audit-tab-content" id="audit-tab-quadrant" style="display: none;">
        <p>La section Quadrant sera bientôt disponible.</p>
    </div>
    <div class="audit-tab-content" id="audit-tab-legends" style="display: none;">
        <?php include SLPI_PATH . 'admin/admin-tab-legends.php'; ?> <!-- Inclusion des légendes -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.audit-tab-list li');
        const contents = document.querySelectorAll('.audit-tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                // Réinitialiser tous les onglets
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(content => (content.style.display = 'none'));

                // Activer l'onglet sélectionné
                this.classList.add('active');
                const target = this.getAttribute('data-tab');
                document.getElementById(`audit-tab-${target}`).style.display = 'block';
            });
        });
    });
</script>

<style>
    .audit-navigation {
        margin-bottom: 20px;
        text-align: center;
    }

    .audit-nav-tabs {
        list-style: none;
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 0;
        margin: 0;
    }

    .audit-nav-tabs li {
        padding: 10px 20px;
        cursor: pointer;
        background: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-weight: bold;
    }

    .audit-nav-tabs li.active {
        background: #0073aa;
        color: white;
    }

    .audit-tab-content {
        display: none;
    }

    .audit-tab-content.active {
        display: block;
    }
</style>