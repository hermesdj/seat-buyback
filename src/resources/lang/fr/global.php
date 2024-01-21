<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

return [
    'browser_title' => 'Rachats Corporation',
    'page_title' => 'Rachats Corporation',
    'admin_browser_title' => 'Configuration Rachats Corporation',
    'admin_page_title' => 'Configuration Rachats Corporation',
    'contract_browser_title' => 'Contrats Rachats Corporation',
    'contract_page_title' => 'Contrats Rachats Corporation',
    'character_contract_browser_title' => 'Mes Rachats Corporation',
    'character_contract_page_title' => 'Mes Rachats Corporation',
    'page_subtitle' => 'Rachats Corporation',
    'itemcheck.error' => 'Veuillez ne pas envoyer avec un champ vide',
    'error' => 'Une erreur s\'est produite !',
    'error_empty_item_field' => ' Aucun de ces objets ne sont achetés par la corporation. Veuillez vérifier la liste !',
    'error_too_much_items' => 'Il y a trop d\'objet dans la liste. Le nombre maximal est: :count',
    'error_price_provider_down' => 'Erreur du fournisseur de prix ! Il semble que le fournisseur de prix ne soient pas en ligne. Veuillez essayer plus tard ou avec un autre fournisseur de prix.',
    'error_item_parser_format' => 'Mauvais format des données !',
    'currency' => 'ISK',
    'step_one_label' => '1. Démarrer une Requête de Rachat',
    'step_one_introduction' => 'Copiez vos objets en jeu ( CTRL + A & CTRL + C ) depuis votre inventaire et collez le avec ( CTRL + V ) dans le champ ci dessous avant de cliquer sur le bouton envoyer. Assurez vous que l\'affichage des objets est bien au format liste avec la quantité. Le nom des objets doit être en anglais.',
    'step_one_button' => 'Envoyer',
    'step_two_label' => '2. Contract Item Overview',
    'step_two_introduction' => 'Veuillez vérifiez les objets et les prix avant de valider.',
    'step_two_item_table_title' => 'Liste des objets à racheter',
    'step_two_ignored_table_title' => ' Objets Ignorés ( Non acheté )',
    'step_two_summary' => 'Résumé',
    'step_three_label' => '3. Votre Contrat',
    'step_three_introduction' => 'Veuillez créer un contrat avec les informations ci dessous',
    'step_three_button' => 'Confirmer',
    'step_three_contract_type' => 'Type de Contrat',
    'step_three_contract_to' => 'Contractualiser avec le Personnage',
    'step_three_contract_receive' => 'Je recevrais',
    'step_three_contract_expiration' => 'Expiration',
    'step_three_contract_description' => 'Description',
    'step_three_contract_tip' => 'Vous pouvez cliquer sur les éléments avec un * pour les copier directement dans votre presse-papier.',
    'step_three_contract_tip_title' => 'Conseil:',
    'max_allowed_items' => 'Nombre maximum d\'objets par rachat:',
    'admin_title' => 'Ajout Config Objet',
    'admin_description' => 'Fill out the form below and press the add button to generate a new item config entry',
    'admin_group_title' => 'Item Overview',
    'admin_group_table_item_name' => 'ItemName',
    'admin_group_table_jita' => ' Jita',
    'admin_group_table_percentage' => 'Percentage',
    'admin_group_table_price' => 'Price',
    'admin_group_table_market_name' => 'Market Group Name',
    'admin_group_table_actions' => 'Actions',
    'admin_group_table_button' => ' Remove',
    'admin_discord_title' => 'Discord Settings',
    'admin_discord_first_title' => 'Webhook',
    'admin_discord_webhook_url_label' => 'URL',
    'admin_discord_webhook_url_description' => 'Set a Discord channel webhook url where the notifications will be send to',
    'admin_discord_webhook_status_label' => 'Status ',
    'admin_discord_webhook_status_description' => 'Activer ou Désactiver les notifications Discord.',
    'admin_setting_bot_name_label' => 'Bot Name',
    'admin_setting_bot_name_description' => 'Under this name the notifications are posted into the discord channel. (Max Length: 32)',
    'admin_discord_webhook_color_label' => 'Border Color',
    'admin_discord_webhook_color_description' => 'Select a border color for your discord notification',
    'admin_discord_button' => 'Update',
    'admin_discord_error_url' => 'This is not a Discord webhook url',
    'admin_discord_error_curl' => 'It was not possible to send the discord notification. Please check your discord settings!',
    'admin_setting_title' => 'Buyback Plugin Settings',
    'admin_setting_first_title' => 'General',
    'admin_setting_cache_label' => 'Price Cache Time',
    'admin_setting_cache_description' => 'Please enter the time in seconds that items prices should be cached.',
    'admin_setting_allowed_items_label' => 'Max Items Allowed',
    'admin_setting_allowed_items_description' => ' Please enter the maximum number of items that are allowed per buyback request',
    'admin_setting_price_provider_label' => 'Price Provider',
    'admin_setting_price_provider_description' => ' Select the price provider you want to fetch the item prices from',
    'admin_setting_price_provider_url_label' => 'EvePraisal Mirror Url',
    'admin_setting_price_provider_url_description' => 'Set the url to your evepraisal price provider instance',
    'admin_setting_second_title' => 'Contract',
    'admin_setting_contract_to_label' => 'Contract to',
    'admin_setting_contract_to_description' => 'Enter the name of the character that should be in the "Contract to" field',
    'admin_setting_expiration_label' => 'Expiration',
    'admin_setting_expiration_description' => 'Choose a contract expiration option',
    'admin_setting_third_title' => 'Default Prices',
    'admin_allow_default_prices' => 'Enable Default Prices',
    'admin_allow_default_prices_description' => 'Default prices are a way to accept any item as a buyback with a default percentage up/down',
    'admin_default_prices_operation_type' => 'Market',
    'admin_default_prices_operation_type_0' => 'Under',
    'admin_default_prices_operation_type_1' => 'Above',
    'admin_default_prices_operation_type_description' => 'Choose if you want to set the percentage value under or above Market price. (Example: 5% under Market = 95% of the item price)',
    'admin_default_prices_percentage' => 'Percentage (%)',
    'admin_default_prices_percentage_description' => 'Choose a value between 1% and 99%',
    'admin_setting_button' => 'Update',
    'admin_setting_error' => 'Admin setting: :message could not be found! Please check your database records!',
    'admin_setting_key_error' => 'Admin setting key: :message could not be found! Please check your database records!',
    'admin_select_placeholder' => 'Type to select an item',
    'admin_item_select_label' => 'Select an item',
    'admin_item_select_description' => 'Select an item you wanna add to the item config below.',
    'admin_item_percentage_label' => 'Percentage (%)',
    'admin_item_percentage_description' => 'Choose a value between 1% and 99%.',
    'admin_item_price_label' => 'Price (ISK)',
    'admin_item_price_description' => 'If you set a price this price will be taken for the calculation and the percentage will be ignored',
    'admin_item_jita_label' => 'Jita',
    'admin_item_jita_description' => 'Choose if you want to set the percentage value under or above Jita. (Example: 5% under Jita = 95% of the item price)',
    'admin_error_config' => 'There is already a config for Id: ',
    'admin_success_config' => 'Admin config successfully updated.',
    'admin_success_market_add' => 'Market config successfully added.',
    'admin_success_market_remove' => 'Market config successfully deleted.',
    'contract_introduction' => 'In this section you are able to manage all the incoming buyback requests. Doing a click on a request entry will open the details. The idea behind this section is to differ the ingame contracts with the buyback requests. As last step you can delete or finish a buyback request.',
    'contract_error_no_items' => 'No contracts found yet!',
    'contract_success_submit' => 'Contract with ID: :id successfully submitted.',
    'contract_success_deleted' => 'Contract with ID: :id successfully deleted.',
    'contract_success_succeeded' => 'Contract with ID: :id successfully marked as succeeded.',
    'my_contract_introduction' => 'In this section you are able to manage your created and pending buyback contracts. If you wanna delete a pending contract press on the delete button. Don\'t forget to cancel also the contract ingame. Buyback contracts that are finished by your corp are shown under the closed contracts section. Clicking on a contract will show you the contract details.',
    'my_contracts_open_title' => 'Open Contracts',
    'my_contracts_open_error' => 'No open contracts found',
    'my_contracts_closed_title' => 'Closed Contracts',
    'my_contracts_closed_error' => 'No closed contracts found'
];
