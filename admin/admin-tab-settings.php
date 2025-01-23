<div id="content-tab-settings" class="slpi-tab-content">
    <h1>Paramètres du Plugin</h1>
    
    <!-- Conteneur principal de la carte -->
    <div class="settings-card">
        <form method="post" action="options.php">
            <?php
            // Affiche les champs de configuration pour la clé API et les options
            settings_fields('slpi_settings');
            do_settings_sections('slpi');
            $api_key = get_option('slpi_api_key', '');
            $keep_data = get_option('slpi_keep_data');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Clé API Google PageSpeed</th>
                    <td><input type="text" name="slpi_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Conserver les données à la désinstallation</th>
                    <td>
                        <input type="checkbox" id="slpi_keep_data" name="slpi_keep_data" value="1" <?php checked($keep_data, 1); ?> />
                        <label for="slpi_keep_data">Conserver les résultats d'analyse lors de la désinstallation du plugin</label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
</div>