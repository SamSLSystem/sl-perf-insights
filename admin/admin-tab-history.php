<div id="content-tab-history" class="slpi-tab-content">
    <h1>Historique des Analyses</h1>
    <!-- Conteneur de filtrage et des actions -->
    <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 20px;">
        <!-- Filtrage par URL -->
        <div>
            <label for="filter-url">Filtrer par URL :</label>
            <select id="filter-url" class="regular-select">
                <option value="">Toutes les pages</option>
            </select>
        </div>

        <!-- Bouton pour supprimer les éléments sélectionnés -->
        <button id="delete-selected-btn" class="button button-secondary">Supprimer la sélection</button>
    </div>

    <!-- Conteneur principal de la carte -->
    <div class="history-cards-container">
        <!-- Carte contenant le tableau -->
        <div class="history-card">
            <div id="slpi-history-table">
                <!-- Contenu dynamique chargé par history.js -->
                <p>Chargement des données...</p>
            </div>
        </div>
    </div>
</div>