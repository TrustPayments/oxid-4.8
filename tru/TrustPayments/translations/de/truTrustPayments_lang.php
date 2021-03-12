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

$sLangName = 'Deutsch';

$aLang = array(
    'charset' => 'UTF-8',
	
	'tru_trustPayments_Downloads' => 'Dokumente herunterladen',
	'tru_trustPayments_Download Invoice' => 'Rechnung herunterladen',
	'tru_trustPayments_Download Packing Slip' => 'Lieferschein herunterladen',
	'tru_trustPayments_Delivery Fee' => 'Liefergebühr',
	'tru_trustPayments_Payment Fee' => 'Zahlartgebühr',
	'tru_trustPayments_Gift Card' => 'Geschenkkarte',
	'tru_trustPayments_Wrapping Fee' => 'Packgebühr',
	'tru_trustPayments_Total Discount' => 'Gesamte Rabatte',
	'tru_trustPayments_VAT' => 'MwSt.',
	'tru_trustPayments_Order already exists. Please check if you have already received a confirmation, then try again.' => 'Die Bestellung existiert bereits. Bitte prüfen Sie ob sie eine Bestätigung erhalten haben, und versuchen Sie es erneut wenn nicht.',
	'tru_trustPayments_Unable to load transaction !id in space !space.' => 'Transaktion konnte nicht geladen werden (Transaktion: !id. Space: !space)',
	'tru_trustPayments_Manual Tasks (!count)' => 'Manuelle Aufgaben (!count)',
	'tru_trustPayments_Unable to confirm order in state !state.' => 'Bestellung im status !state kann nicht bestätigt werden.',
	'tru_trustPayments_Not a Trust Payments order.' => 'Nicht eine Trust Payments Bestellung.',
	'tru_trustPayments_An unknown error occurred, and the order could not be loaded.' => 'Ein unbekannter Fehler ist aufgetreten und die Bestellung konnte nicht geladen werden.',
	'tru_trustPayments_Successfully created and sent completion job !id.' => 'Bestätigung (!id) erfolgreich erstellt und versandt.',
	'tru_trustPayments_Successfully created and sent void job !id.' => 'Storno (!id) erfolgreich erstellt und versandt.',
	'tru_trustPayments_Successfully created and sent refund job !id.' => 'Rückerstattung (!id) erfolgreich erstellt und versandt.',
	'tru_trustPayments_Unable to load transaction for order !id.' => 'Transaktion für die Bestellung !id konnte nicht geladen werden.',
	'tru_trustPayments_Completions' => 'Bestätigungen',
	'tru_trustPayments_Completion #!id' => 'Bestätigung #!id',
	'tru_trustPayments_Refunds' => 'Rückerstattungen',
	'tru_trustPayments_Refund #!id' => 'Rückerstattung #!id',
	'tru_trustPayments_Voids' => 'Stornos',
	'tru_trustPayments_Void #!id' => 'Storno #!id',
	'tru_trustPayments_Transaction information' => 'Transaktionsinformation',
	'tru_trustPayments_Authorization amount' => 'Authorisierter Betrag',
	'tru_trustPayments_The amount which was authorized with the Trust Payments transaction.' => 'Der Betrag der durch die Trust Payments transaktion authorisiert wurde.',
	'tru_trustPayments_Transaction #!id' => 'Transaktion #!id',
	'tru_trustPayments_Status' => 'Status',
	'tru_trustPayments_Status in the Trust Payments system.' => 'Status in dem Trust Payments system.',
	'tru_trustPayments_Payment method' => 'Payment method',
	'tru_trustPayments_Open in your Trust Payments backend.' => 'Öffne im Trust Payments backend.',
	'tru_trustPayments_Open' => 'Öffnen',
	'tru_trustPayments_Trust Payments Link' => 'Trust Payments Link',
	'tru_trustPayments_You must agree to the terms and conditions.' => 'Sie müssen den AGBs und Datenschutzvereinbarung zustimmen.',
	'tru_trustPayments_Rounding Adjustment' => 'Rundungsbetrag',
	'tru_trustPayments_Totals mismatch, please contact merchant or use another payment method.' => 'Fehler bei Betragsberechnung. Bitte kontaktieren Sie den Händler oder wählen Sie eine andere Zahlart.',
	
	// tpl translations
	'tru_trustPayments_Restock' => 'Lagerbestand wiederherstellen',
	'tru_trustPayments_Total' => 'Total',
	'tru_trustPayments_Reset' => 'Zurücksetzen',
	'tru_trustPayments_Full' => 'Volle Rückerstattung',
	'tru_trustPayments_Empty refund not permitted' => 'Eine leere Rückerstattung kann nicht erstellt werden.',
	'tru_trustPayments_Void' => 'Storno',
	'tru_trustPayments_Complete' => 'Bestätigen',
	'tru_trustPayments_Refund' => 'Rückerstatten',
	'tru_trustPayments_Name' => 'Name',
	'tru_trustPayments_SKU' => 'SKU',
	'tru_trustPayments_Quantity' => 'Quantität',
	'tru_trustPayments_Reduction' => 'Reduktion',
	'tru_trustPayments_Refund amount' => 'Rückerstattungsbetrag',
	
	// menu
	'tru_trustPayments_transaction_title' => 'Trust Payments Transaktion'
);