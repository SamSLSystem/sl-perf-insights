jQuery(document).ready(function ($) {
    console.log('Script history.js chargé et DOM prêt.');

    // Chargement de l'historique
    function loadHistory() {
        var $historyTable = $('#slpi-history-table');
        var $filterSelect = $('#filter-url');
        $historyTable.html('<p>Chargement des données...</p>');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: { action: 'slpi_get_history' },
            success: function (response) {
                if (response.success) {
                    var results = response.data;

                    // Remplir le menu déroulant avec les URL uniques
                    var uniqueUrls = [...new Set(results.map(result => result.url))];
                    $filterSelect.empty().append('<option value="">Toutes les pages</option>');
                    uniqueUrls.forEach(function (url) {
                        $filterSelect.append('<option value="' + url + '">' + url + '</option>');
                    });

                    // Construire le tableau unique avec les résultats Mobile et Desktop
                    displayHistoryTable(results);

                    // Ajouter un événement pour filtrer les résultats lorsque l'URL est sélectionnée
                    $filterSelect.off('change').on('change', function () {
                        var selectedUrl = $(this).val();
                        var filteredResults = selectedUrl
                            ? results.filter(result => result.url === selectedUrl)
                            : results;
                        displayHistoryTable(filteredResults);
                    });
                } else {
                    $historyTable.html('<p>Aucune donnée disponible.</p>');
                }
            },
            error: function () {
                $historyTable.html('<p>Erreur lors de la récupération des données.</p>');
            }
        });
    }

    // Fonction pour afficher les résultats dans un seul tableau
    function displayHistoryTable(results) {
        var $historyTable = $('#slpi-history-table');
        var tableHtml = '<table id="history-datatable" class="widefat display"><thead><tr>' +
            '<th><input type="checkbox" id="select-all"></th>' +
            '<th>Horodatage</th><th>URL</th><th>Post Type</th>' +
            '<th>Score Desktop</th><th>Score Mobile</th>' +
            '<th>Analyse complète</th>' +
            '</tr></thead><tbody>';
    
        results.forEach(function(result) {
            tableHtml += '<tr data-id="' + result.id + '">' +  // Utilisation de 'data-id' pour stocker l'ID
                '<td><input type="checkbox" class="entry-checkbox" data-id="' + result.id + '" data-platform="mobile"></td>' +
                '<td>' + result.date_analyse + '</td>' +
                '<td><a href="' + result.url + '" target="_blank">' + result.url + '</a></td>' +
                '<td>' + result.post_type + '</td>' +
                '<td>' + result.score_desktop + '</td>' +
                '<td>' + result.score_mobile + '</td>' +
                '<td><a href="https://developers.google.com/speed/pagespeed/insights/?url=' + encodeURIComponent(result.url) + '" target="_blank">Voir analyse complète</a></td>' +
                '</tr>';
        });
    
        tableHtml += '</tbody></table>';
        $historyTable.html(tableHtml);
    
        // Initialiser DataTables après mise à jour
        $('#history-datatable').DataTable();
    
        // Gérer la case "Tout sélectionner"
        $('#select-all').on('change', function() {
            var isChecked = $(this).prop('checked');
            $('.entry-checkbox').prop('checked', isChecked);
        });
    }

// Fonction pour supprimer des enregistrements en masse via AJAX
function deleteSelectedEntries() {
    var selectedIds = [];
    $('.entry-checkbox:checked').each(function () {
        selectedIds.push($(this).data('id'));  // Récupérer l'ID de l'élément sélectionné
    });

    if (selectedIds.length === 0) {
        alert('Veuillez sélectionner au moins une entrée à supprimer.');
        console.log('Aucun élément sélectionné.');  // Log si aucun élément n'est sélectionné
        return;
    }

    console.log('IDs envoyés pour suppression :', selectedIds);  // Log pour afficher les IDs envoyés

    if (confirm('Êtes-vous sûr de vouloir supprimer les entrées sélectionnées ?')) {
        $.ajax({
            url: ajaxurl,  // URL de la requête AJAX
            method: 'POST',
            data: {
                action: 'slpi_delete_history_bulk',  // Action AJAX pour la suppression
                ids: selectedIds  // Les IDs des enregistrements à supprimer
            },
            success: function (response) {
                console.log('Réponse reçue du serveur :', response);  // Log pour afficher la réponse du serveur
                if (response.success) {
                    alert('Entrées supprimées avec succès.');
                    loadHistory();  // Recharger l'historique après suppression
                } else {
                    alert('Erreur lors de la suppression des entrées.');
                }

                // Afficher les logs du serveur dans la console
                if (response.data && response.data.logs) {
                    console.log('Logs du serveur :');
                    response.data.logs.forEach(function(log) {
                        console.log(log);
                    });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log('Erreur AJAX:', textStatus, errorThrown);  // Log pour les erreurs AJAX
                alert('Erreur lors de la communication avec le serveur.');
            }
        });
    }
}
 // Événement sur le bouton de suppression
 $('#delete-selected-btn').on('click', function () {
    var selectedIds = [];
    
    // Récupérer les IDs des cases cochées
    $('.entry-checkbox:checked').each(function () {
        var id = $(this).data('id');
        if (id) {
            selectedIds.push(id);
        }
    });

    if (selectedIds.length === 0) {
        alert('Aucune entrée sélectionnée.');
        return;
    }

    if (!confirm('Voulez-vous vraiment supprimer les entrées sélectionnées ?')) {
        return;
    }

    // Envoi de la requête AJAX pour suppression
    $.ajax({
        url: ajaxurl,
        method: 'POST',
        data: {
            action: 'slpi_delete_history_bulk',
            ids: selectedIds
        },
        success: function (response) {
            if (response.success) {
                alert('Entrées supprimées avec succès.');
                // Recharger l’historique
                loadHistory();
            } else {
                alert('Erreur lors de la suppression : ' + response.data);
            }
        },
        error: function () {
            alert('Une erreur est survenue lors de la requête.');
        }
    });
});

function autoRefreshHistory() {
    setInterval(function () {
        console.log('Rafraîchissement automatique de l\'onglet Historique...');
        $('#history-tab').load(location.href + ' #history-tab > *');
    }, 10000); // Toutes les 10 secondes
}

// Appelle la fonction pour démarrer le rafraîchissement automatique
autoRefreshHistory();
    // Exposer la fonction au contexte global
    window.loadHistory = loadHistory;
});