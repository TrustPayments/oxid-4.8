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
	
	'tru_trustPayments_Downloads' => 'Télécharger des documents',
	'tru_trustPayments_Download Invoice' => 'Télécharger la facture',
	'tru_trustPayments_Download Packing Slip' => 'Télécharger le bon de livraison',
	'tru_trustPayments_Delivery Fee' => 'Frais de livraison',
	'tru_trustPayments_Payment Fee' => 'Frais de paiement',
	'tru_trustPayments_Gift Card' => 'Carte cadeau',
	'tru_trustPayments_Wrapping Fee' => 'Frais d\'emballage',
	'tru_trustPayments_Total Discount' => 'Remise totale',
	'tru_trustPayments_VAT' => 'T.V.A.',
	'tru_trustPayments_Order already exists. Please check if you have already received a confirmation, then try again.' => 'La commande existe déjà. Veuillez vérifier si vous avez déjà reçu une confirmation, puis réessayez.',
	'tru_trustPayments_Unable to load transaction !id in space !space.' => 'Impossible de charger la transaction !id dans l\'espace !space',
	'tru_trustPayments_Manual Tasks (!count)' => 'Tâches manuelles (!count)',
	'tru_trustPayments_Unable to confirm order in state !state.' => 'Impossible de confirmer la commande dans l\'état !state.',
	'tru_trustPayments_Not a Trust Payments order.' => 'Pas une commande Trust Payments.',
	'tru_trustPayments_An unknown error occurred, and the order could not be loaded.' => 'Une erreur inconnue s\'est produite et la commande n\'a pas pu être chargée.',
	'tru_trustPayments_Successfully created and sent completion job !id.' => 'Tâche d\'achèvement créée et envoyée avec succès !id.',
	'tru_trustPayments_Successfully created and sent void job !id.' => 'Travail annulé créé et envoyé avec succès !id.',
	'tru_trustPayments_Successfully created and sent refund job !id.' => 'La tâche de remboursement !id a bien été créée et envoyée.',
	'tru_trustPayments_Unable to load transaction for order !id.' => 'Impossible de charger la transaction pour la commande !id.',
	'tru_trustPayments_Completions' => 'Achèvements',
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
	'tru_trustPayments_Status in the Trust Payments system.' => 'Statut dans le système Trust Payments system.',
	'tru_trustPayments_Payment method' => 'Mode de paiement',
	'tru_trustPayments_Open in your Trust Payments backend.' => 'Ouvrir dans votre backend Trust Payments.',
	'tru_trustPayments_Open' => 'Ouvrir',
	'tru_trustPayments_Trust Payments Link' => 'Trust Payments Lien',
	'tru_trustPayments_You must agree to the terms and conditions.' => 'Vous devez accepter les termes et conditions.',
	'tru_trustPayments_Rounding Adjustment' => 'Ajustement d\'arrondi',
	'tru_trustPayments_Totals mismatch, please contact merchant or use another payment method.' => 'Les totaux ne correspondent pas, veuillez contacter le marchand ou utiliser un autre mode de paiement.',
	
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
	'tru_trustPayments_SKU' => 'UGS',
	'tru_trustPayments_Quantity' => 'Quantité',
	'tru_trustPayments_Reduction' => 'Réduction',
	'tru_trustPayments_Refund amount' => 'Montant du remboursement',
	
	// menu
	'tru_trustPayments_transaction_title' => 'Trust Payments Transaction'
);