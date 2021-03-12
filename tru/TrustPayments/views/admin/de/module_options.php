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
    'truTrustPayments' => 'TRU TrustPayments',

    'SHOP_MODULE_GROUP_truTrustPaymentsTrust PaymentsSettings' => 'Trust Payments Einstellungen',
	'SHOP_MODULE_GROUP_truTrustPaymentsShopSettings' => 'Shop Einstellungen',
	'SHOP_MODULE_GROUP_truTrustPaymentsSpaceSettings' => 'Space View Id',
    'SHOP_MODULE_GROUP_truTrustPaymentsSpaceViewSettings' => 'Space View Einstellungen',
    'SHOP_MODULE_truTrustPaymentsAppKey' => 'Authentication Key',
	'SHOP_MODULE_truTrustPaymentsUserId' => 'Benutzer Id',
    'SHOP_MODULE_truTrustPaymentsSpaceId' => 'Space Id',
	'SHOP_MODULE_truTrustPaymentsSpaceViewId' => 'Space View Optionen',
    'SHOP_MODULE_truTrustPaymentsEmailConfirm' => 'Email Bestätigung',
    'SHOP_MODULE_truTrustPaymentsInvoiceDoc' => 'Rechnung',
    'SHOP_MODULE_truTrustPaymentsPackingDoc' => 'Lieferschein',
	'SHOP_MODULE_truTrustPaymentsEnforceConsistency' => 'Konsistenz sicherstellen',
    'SHOP_MODULE_truTrustPaymentsLogLevel' => 'Log Level',
    'SHOP_MODULE_truTrustPaymentsLogLevel_' => ' - ',
    'SHOP_MODULE_truTrustPaymentsLogLevel_Error' => 'Error',
    'SHOP_MODULE_truTrustPaymentsLogLevel_Debug' => 'Debug',
    'SHOP_MODULE_truTrustPaymentsLogLevel_Info' => 'Info',
	
	
	'HELP_SHOP_MODULE_truTrustPaymentsUserId' => 'Der Benutzer benötigt volle Berechtigungen auf dem verbundenen space.',
	'HELP_SHOP_MODULE_truTrustPaymentsSpaceViewId' => 'Die Space View ID lässt das Gestalten der Zahlungsformulare und -seiten innerhalb eines Spaces. Dies kann u.A. für Multishopsysteme die unterschiedliche Aussehen haben sollten verwendet werden.',	'HELP_SHOP_MODULE_truTrustPaymentsEmailConfirm' => 'You may deactivate the OXID order confirmation email for Trust Payments transactions.',
	'HELP_SHOP_MODULE_truTrustPaymentsInvoiceDoc' => 'Sie können ihren Kunden erlauben Rechnungen für Ihre Bestellungen im Frontend-Bereich herunterzuladen.',
	'HELP_SHOP_MODULE_truTrustPaymentsPackingDoc' => 'Sie können ihren Kunden erlauben Lieferscheine für Ihre Bestellungen im Frontend-Bereich herunterzuladen.',
	'HELP_SHOP_MODULE_truTrustPaymentsEmailConfirm' => 'Sie können OXID Bestellbestätigungen für Trust Payments Transaktionen unterbinden.',
	'HELP_SHOP_MODULE_truTrustPaymentsEnforceConsistency' => 'Erfordere, dass die Einzelposten der Transaktion denen der Bestellung in Magento entsprechen. Dies kann dazu führen, dass die Zahlungsmethoden von Trust Payments dem Kunden in bestimmten Fällen nicht zur Verfügung stehen. Im Gegenzug wird sichergestellt, dass nur korrekte Daten an Trust Payments übertragen werden.',
	
	'tru_trustPayments_Settings saved successfully.' => 'Die Einstellungen wurden erfolgreich gespeichert.',
	'tru_trustPayments_Payment methods successfully synchronized.' => 'Die Zahlarten wurden synchronisiert.',
	'tru_trustPayments_Webhook URL updated.' => 'Webhook URL wurde aktualisiert.',
	//TODO remove unneeded
	
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
	'tru_trustPayments_transaction_title' => 'Trust Payments Transaktion');