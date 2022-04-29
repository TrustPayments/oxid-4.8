<?php
/**
 * TrustPayments OXID
 *
 * This OXID module enables to process payments with TrustPayments (https://www.trustpayments.com//).
 *
 * @package Whitelabelshortcut\TrustPayments
 * @author customweb GmbH (http://www.customweb.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */
$sLangName = 'Français';

$aLang = array(
    'charset' => 'UTF-8',
    'truTrustPayments' => 'TRU TrustPayments',
	
	'SHOP_MODULE_GROUP_truTrustPaymentsTrust PaymentsSettings' => 'Trust Payments Réglages',
	'SHOP_MODULE_GROUP_truTrustPaymentsShopSettings' => 'Paramètres de la boutique',
	'SHOP_MODULE_GROUP_truTrustPaymentsSpaceViewSettings' => 'Options d\'affichage de l\'espace',
	'SHOP_MODULE_truTrustPaymentsAppKey' => 'Authentication Key',
	'SHOP_MODULE_truTrustPaymentsUserId' => 'User Id',
    'SHOP_MODULE_truTrustPaymentsSpaceId' => 'Space Id',
	'SHOP_MODULE_truTrustPaymentsSpaceViewId' => 'Space View Id',
	'SHOP_MODULE_truTrustPaymentsEmailConfirm' => 'E-mail de confirmation',
	'SHOP_MODULE_truTrustPaymentsInvoiceDoc' => 'Document de facturation',
	'SHOP_MODULE_truTrustPaymentsPackingDoc' => 'Document d\'emballage',
	'SHOP_MODULE_truTrustPaymentsEnforceConsistency' => 'Appliquer la cohérence',
    'SHOP_MODULE_truTrustPaymentsLogLevel' => 'Log Level',
    'SHOP_MODULE_truTrustPaymentsLogLevel_' => ' - ',
    'SHOP_MODULE_truTrustPaymentsLogLevel_Error' => 'Error',
    'SHOP_MODULE_truTrustPaymentsLogLevel_Debug' => 'Debug',
	'SHOP_MODULE_truTrustPaymentsLogLevel_Info' => 'Info',
	
	'HELP_SHOP_MODULE_truTrustPaymentsUserId' => 'L\'utilisateur a besoin d\'une autorisation complète dans l\'espace auquel la boutique est liée.',
	'HELP_SHOP_MODULE_truTrustPaymentsSpaceViewId' => 'L\'ID de vue de l\'espace permet de contrôler le style du formulaire de paiement et de la page de paiement dans l\'espace. Dans les configurations multi-boutiques, cela permet d\'adapter le formulaire de paiement à différents styles par sous-magasin sans nécessiter d\'espace dédié.',
	'HELP_SHOP_MODULE_truTrustPaymentsEmailConfirm' => 'Vous pouvez désactiver l\'e-mail de confirmation de commande OXID pour les transactions Trust Payments.',
	'HELP_SHOP_MODULE_truTrustPaymentsInvoiceDoc' => 'Vous pouvez autoriser les clients à télécharger des factures dans leur espace de compte.',
	'HELP_SHOP_MODULE_truTrustPaymentsPackingDoc' => 'Vous pouvez autoriser les clients à télécharger les bordereaux d\'expédition dans leur espace de compte.',
	'HELP_SHOP_MODULE_truTrustPaymentsEnforceConsistency' => 'Exiger que les rubriques de la transaction correspondent à celles du bon de commande dans Magento. Il peut en résulter que les méthodes de paiement Trust Payments ne sont pas disponibles pour le client dans certains cas. En retour, il est garanti que seules des données correctes sont transmises à Trust Payments.',
	
	'tru_trustPayments_Settings saved successfully.' => 'Paramètres enregistrés avec succès.',
	'tru_trustPayments_Payment methods successfully synchronized.' => 'Modes de paiement synchronisés avec succès.',
	'tru_trustPayments_Webhook URL updated.' => 'URL du webhook mise à jour.',
	//TODO remove uneeded
	
	'tru_trustPayments_Download Invoice' => 'Télécharger la facture',
	'tru_trustPayments_Download Packing Slip' => 'Télécharger le bordereau d\'expédition',
	'tru_trustPayments_Delivery Fee' => 'Frais de livraison',
	'tru_trustPayments_Payment Fee' => 'Frais de paiement',
	'tru_trustPayments_Gift Card' => 'Carte cadeau',
	'tru_trustPayments_Wrapping Fee' => 'Frais d\'emballage',
	'tru_trustPayments_Total Discount' => 'Remise totale',
	'tru_trustPayments_VAT' => 'VAT',
	'tru_trustPayments_Order already exists. Please check if you have already received a confirmation, then try again.' => 'La commande existe déjà. Veuillez vérifier si vous avez déjà reçu une confirmation, puis réessayez.',
	'tru_trustPayments_Unable to load transaction !id in space !space.' => 'Impossible de charger la transaction !id dans l\'espace !space',
	'tru_trustPayments_Manual Tasks (!count)' => 'Tâches manuelles (!count)',
	'tru_trustPayments_Unable to confirm order in state !state.' => 'Impossible de confirmer la commande dans l\'état !state.',
	'tru_trustPayments_Not a Trust Payments order.' => 'Pas une commande Trust Payments.',
	'tru_trustPayments_An unknown error occurred, and the order could not be loaded.' => 'Une erreur inconnue s\'est produite et la commande n\'a pas pu être chargée.',
	'tru_trustPayments_Successfully created and sent completion job !id.' => 'Tâche d\'achèvement créée et envoyée avec succès !id.',
	'tru_trustPayments_Successfully created and sent void job !id.' => 'Travail annulé créé et envoyé avec succès !id.',
	'tru_trustPayments_Successfully created and sent refund job !id.' => 'La tâche de remboursement a bien été créée et envoyée !id.',
	'tru_trustPayments_Unable to load transaction for order !id.' => 'Impossible de charger la transaction pour la commande !id.',
	'tru_trustPayments_Completions' => 'Achèvements',
	'tru_trustPayments_Completion' => 'Achèvement',
	'tru_trustPayments_Refunds' => 'Remboursements',
	'tru_trustPayments_Voids' => 'Vides',
	'tru_trustPayments_Completion #!id' => 'Achèvement #!id',
	'tru_trustPayments_Refund #!id' => 'Rembourser #!id',
	'tru_trustPayments_Void #!id' => 'Vide #!id',
	'tru_trustPayments_Transaction information' => 'Informations sur les transactions',
	'tru_trustPayments_Authorization amount' => 'Montant de l\'autorisation',
	'tru_trustPayments_The amount which was authorized with the Trust Payments transaction.' => 'Le montant qui a été autorisé avec la transaction Trust Payments.',
	'tru_trustPayments_Transaction #!id' => 'Transaction #!id',
	'tru_trustPayments_Status' => 'Statut',
	'tru_trustPayments_Status in the Trust Payments system.' => 'Statut dans le système Trust Payments.',
	'tru_trustPayments_Payment method' => 'Mode de paiement',
	'tru_trustPayments_Open in your Trust Payments backend.' => 'Ouvrir dans votre backend Trust Payments.',
	'tru_trustPayments_Open' => 'Ouvrir',
	'tru_trustPayments_Trust Payments Link' => 'Lien Trust Payments',
	
	// tpl translations
	'tru_trustPayments_Restock' => 'Réapprovisionner',
	'tru_trustPayments_Total' => 'Total',
	'tru_trustPayments_Reset' => 'Réinitialiser',
	'tru_trustPayments_Full' => 'Plein',
	'tru_trustPayments_Empty refund not permitted' => 'Remboursement vide non autorisé.',
	'tru_trustPayments_Void' => 'Vide',
	'tru_trustPayments_Complete' => 'Complet',
	'tru_trustPayments_Refund' => 'Rembourser',
	'tru_trustPayments_Name' => 'Nom',
	'tru_trustPayments_SKU' => 'SKU',
	'tru_trustPayments_Quantity' => 'Quantité',
	'tru_trustPayments_Reduction' => 'Réduction',
	'tru_trustPayments_Refund amount' => 'Montant du remboursement',
	
	// menu
	'tru_trustPayments_transaction_title' => 'Trust Payments Transaction'
);