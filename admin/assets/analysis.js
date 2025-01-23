jQuery(document).ready(function ($) {
    console.log('Script analysis.js chargé et DOM prêt.');

    // Lancer l'analyse
    function launchAnalysis(selectedItems, resultContainer, buttonId) {
        var $result = $(resultContainer);
        var selectedURLs = [];
        
        // Parcours des éléments sélectionnés et récupération des URL et du post_type
        $(selectedItems + ':checked').each(function () {
            var page_url = $(this).val();  // URL de l'élément sélectionné
            var post_type = $(this).data('post-type') || 'home'; // Par défaut 'page' si non défini
            
            selectedURLs.push({ url: page_url, post_type: post_type });
        });
    
        if (selectedURLs.length === 0) {
            alert('Veuillez sélectionner au moins un élément à analyser.');
            return;
        }
    
        console.log('URLs sélectionnées pour l’analyse :', selectedURLs);
    
        $(buttonId).prop('disabled', true);
    
        $result.html('<p>Analyse en cours...</p><div id="progress-bar" style="width: 100%; background: #f4f4f4; border: 1px solid #ddd; height: 20px;"><div id="progress" style="width: 0%; height: 100%; background: #0073aa;"></div></div><table class="widefat"><thead><tr><th>URL</th><th>Score Global</th><th>Speed Index</th><th>FCP</th><th>LCP</th><th>TBT</th><th>CLS</th><th>TTI</th><th>Analyse complète</th></tr></thead><tbody></tbody></table>');
    
        var totalItems = selectedURLs.length;
        var completedItems = 0;
        var processedItems = {};
    
        function analyzeNextItem() {
            if (selectedURLs.length === 0) {
                console.log('Analyse terminée. Total des éléments traités :', completedItems);
                $result.find('p').text('Analyse terminée.');
                $(buttonId).prop('disabled', false);
                return;
            }
    
            var currentItem = selectedURLs.shift();
            console.log('Analyse de l’élément en cours :', currentItem);
    
            if (processedItems[currentItem.url]) {
                console.warn('Analyse dupliquée détectée pour :', currentItem.url);
                analyzeNextItem();
                return;
            }
    
            processedItems[currentItem.url] = true;
    
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: { 
                    action: 'slpi_analyze_single_page', 
                    page_url: currentItem.url,
                    post_type: currentItem.post_type // Ajout du post_type correct pour chaque élément
                },
                beforeSend: function () {
                    console.log('Données envoyées pour l’analyse :', { 
                        action: 'slpi_analyze_single_page', 
                        page_url: currentItem.url, 
                        post_type: currentItem.post_type 
                    });
                },
                success: function (response) {
                    console.log('Réponse AJAX reçue :', response);
    
                    completedItems++;
                    var progressPercent = Math.round((completedItems / totalItems) * 100);
                    $('#progress').css('width', progressPercent + '%');
    
                    var rowHtml = '<tr><td>' + currentItem.url + '</td>';
                    if (response.success) {
                        var data = response.data;
    
                        rowHtml += '<td class="' + getScoreClass(data.mobile_score) + '">' + 'Mobile: ' + data.mobile_score + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_mobile['Speed Index'], 'Speed Index') + '">' + data.audit_results_mobile['Speed Index'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_mobile['First Contentful Paint'], 'FCP') + '">' + data.audit_results_mobile['First Contentful Paint'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_mobile['Largest Contentful Paint'], 'LCP') + '">' + data.audit_results_mobile['Largest Contentful Paint'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_mobile['Total Blocking Time'], 'TBT') + '">' + data.audit_results_mobile['Total Blocking Time'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_mobile['Cumulative Layout Shift'], 'CLS') + '">' + data.audit_results_mobile['Cumulative Layout Shift'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_mobile['Time to Interactive'], 'TTI') + '">' + data.audit_results_mobile['Time to Interactive'] + '</td>';
                        rowHtml += '<td><a href="https://developers.google.com/speed/pagespeed/insights/?url=' + encodeURIComponent(currentItem.url) + '" target="_blank">Voir analyse complète</a></td>';
    
                        rowHtml += '</tr><tr><td></td>';
                        rowHtml += '<td class="' + getScoreClass(data.desktop_score) + '">' + 'Desktop: ' + data.desktop_score + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_desktop['Speed Index'], 'Speed Index') + '">' + data.audit_results_desktop['Speed Index'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_desktop['First Contentful Paint'], 'FCP') + '">' + data.audit_results_desktop['First Contentful Paint'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_desktop['Largest Contentful Paint'], 'LCP') + '">' + data.audit_results_desktop['Largest Contentful Paint'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_desktop['Total Blocking Time'], 'TBT') + '">' + data.audit_results_desktop['Total Blocking Time'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_desktop['Cumulative Layout Shift'], 'CLS') + '">' + data.audit_results_desktop['Cumulative Layout Shift'] + '</td>';
                        rowHtml += '<td class="' + getMetricClass(data.audit_results_desktop['Time to Interactive'], 'TTI') + '">' + data.audit_results_desktop['Time to Interactive'] + '</td>';
                        rowHtml += '<td></td></tr>';
                    } else {
                        rowHtml += '<td colspan="3" style="color: red;">Erreur : ' + response.data + '</td>';
                    }
                    rowHtml += '</tr>';
    
                    $result.find('tbody').append(rowHtml);
                    analyzeNextItem();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Erreur AJAX :', textStatus, errorThrown);
                    $result.find('p').text('Erreur lors de l’analyse.');
                    $(buttonId).prop('disabled', false);
                }
            });
        }
    
        analyzeNextItem();
    }

    // Exposer la fonction au contexte global
    window.launchAnalysis = launchAnalysis;
});