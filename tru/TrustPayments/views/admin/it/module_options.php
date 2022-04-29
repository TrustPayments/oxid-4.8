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
$sLangName = 'Italiano';

$aLang = array(
    'charset' => 'UTF-8',
    'truTrustPayments' => 'TRU TrustPayments',
	
	'SHOP_MODULE_GROUP_truTrustPaymentsTrust PaymentsSettings' => 'Trust Payments Impostazioni',
	'SHOP_MODULE_GROUP_truTrustPaymentsShopSettings' => 'Impostazioni del negozio',
	'SHOP_MODULE_GROUP_truTrustPaymentsSpaceViewSettings' => 'Opzioni di visualizzazione dello spazio',
	'SHOP_MODULE_truTrustPaymentsAppKey' => 'Authentication Key',
	'SHOP_MODULE_truTrustPaymentsUserId' => 'User Id',
    'SHOP_MODULE_truTrustPaymentsSpaceId' => 'Space Id',
	'SHOP_MODULE_truTrustPaymentsSpaceViewId' => 'Space View Id',
	'SHOP_MODULE_truTrustPaymentsEmailConfirm' => 'E-mail di conferma',
	'SHOP_MODULE_truTrustPaymentsInvoiceDoc' => 'Fattura doc',
	'SHOP_MODULE_truTrustPaymentsPackingDoc' => 'Imballaggio Doc',
	'SHOP_MODULE_truTrustPaymentsEnforceConsistency' => 'Imponi la coerenza',
    'SHOP_MODULE_truTrustPaymentsLogLevel' => 'Log Level',
    'SHOP_MODULE_truTrustPaymentsLogLevel_' => ' - ',
    'SHOP_MODULE_truTrustPaymentsLogLevel_Error' => 'Error',
    'SHOP_MODULE_truTrustPaymentsLogLevel_Debug' => 'Debug',
	'SHOP_MODULE_truTrustPaymentsLogLevel_Info' => 'Info',
	
	'HELP_SHOP_MODULE_truTrustPaymentsUserId' => 'L\'utente richiede l\'autorizzazione completa nello spazio a cui è collegato il negozio.',
	'HELP_SHOP_MODULE_truTrustPaymentsSpaceViewId' => 'L\'ID vista spazio consente di controllare lo stile del modulo di pagamento e della pagina di pagamento all\'interno dello spazio. Nelle configurazioni multi negozio permette di adattare il modulo di pagamento a stili differenti per sub store senza richiedere uno spazio dedicato..',
	'HELP_SHOP_MODULE_truTrustPaymentsEmailConfirm' => 'È possibile disattivare l\'e-mail di conferma dell\'ordine OXID per le transazioni Trust Payments.',
	'HELP_SHOP_MODULE_truTrustPaymentsInvoiceDoc' => 'Puoi consentire ai clienti di scaricare le fatture nella loro area account.',
	'HELP_SHOP_MODULE_truTrustPaymentsPackingDoc' => 'Puoi consentire ai clienti di scaricare i documenti di trasporto nell\'area del loro account.',
	'HELP_SHOP_MODULE_truTrustPaymentsEnforceConsistency' => 'Richiedi che le voci della transazione corrispondano a quelle dell\'ordine di acquisto in Magento. Ciò potrebbe comportare che i metodi di pagamento Trust Payments non siano disponibili per il cliente in alcuni casi. In cambio, è garantito che solo i dati corretti vengano trasmessi a Trust Payments..',
	
	'tru_trustPayments_Settings saved successfully.' => 'Impostazioni salvate correttamente.',
	'tru_trustPayments_Payment methods successfully synchronized.' => 'Metodi di pagamento sincronizzati con successo.',
	'tru_trustPayments_Webhook URL updated.' => 'URL webhook aggiornato.',
	//TODO remove uneeded
	
	'tru_trustPayments_Download Invoice' => 'Scarica fattura',
	'tru_trustPayments_Download Packing Slip' => 'Scarica fattura',
	'tru_trustPayments_Delivery Fee' => 'Tassa di consegna',
	'tru_trustPayments_Payment Fee' => 'Commissione di pagamento',
	'tru_trustPayments_Gift Card' => 'Commissione di pagamento',
	'tru_trustPayments_Wrapping Fee' => 'Spese di confezionamento',
	'tru_trustPayments_Total Discount' => 'Sconto totale',
	'tru_trustPayments_VAT' => 'VAT',
	'tru_trustPayments_Order already exists. Please check if you have already received a confirmation, then try again.' => 'L\'ordine esiste già. Verifica di aver già ricevuto una conferma, quindi riprova.',
	'tru_trustPayments_Unable to load transaction !id in space !space.' => 'Impossibile caricare la transazione !id nello spazio !space',
	'tru_trustPayments_Manual Tasks (!count)' => 'Compiti manuali (!count)',
	'tru_trustPayments_Unable to confirm order in state !state.' => 'Impossibile confermare l\'ordine nello stato !state.',
	'tru_trustPayments_Not a Trust Payments order.' => 'Non un ordine Trust Payments.',
	'tru_trustPayments_An unknown error occurred, and the order could not be loaded.' => 'Si è verificato un errore sconosciuto e non è stato possibile caricare l\'ordine.',
	'tru_trustPayments_Successfully created and sent completion job !id.' => 'Lavoro di completamento creato e inviato con successo !id.',
	'tru_trustPayments_Successfully created and sent void job !id.' => 'Lavoro annullato creato e inviato con successo !id.',
	'tru_trustPayments_Successfully created and sent refund job !id.' => 'Lavoro di rimborso creato e inviato con successo !id.',
	'tru_trustPayments_Unable to load transaction for order !id.' => 'Impossibile caricare la transazione per l\'ordine !id.',
	'tru_trustPayments_Completions' => 'Completamenti',
	'tru_trustPayments_Completion' => 'Completamento',
	'tru_trustPayments_Refunds' => 'Refunds',
	'tru_trustPayments_Voids' => 'Rimborsi',
	'tru_trustPayments_Completion #!id' => 'Completamento #!id',
	'tru_trustPayments_Refund #!id' => 'Refund #!id',
	'tru_trustPayments_Void #!id' => 'Vuoto #!id',
	'tru_trustPayments_Transaction information' => 'Informazioni sulla transazione',
	'tru_trustPayments_Authorization amount' => 'Authorization amount',
	'tru_trustPayments_The amount which was authorized with the Trust Payments transaction.' => 'L\'importo autorizzato con la transazione Trust Payments.',
	'tru_trustPayments_Transaction #!id' => 'Transazione #!id',
	'tru_trustPayments_Status' => 'Stato',
	'tru_trustPayments_Status in the Trust Payments system.' => 'Stato nel sistema Trust Payments.',
	'tru_trustPayments_Payment method' => 'Metodo di pagamento',
	'tru_trustPayments_Open in your Trust Payments backend.' => 'Apri nel tuo back-end Trust Payments.',
	'tru_trustPayments_Open' => 'Aprire',
	'tru_trustPayments_Trust Payments Link' => 'Trust Payments Collegamento',
	
	// tpl translations
	'tru_trustPayments_Restock' => 'Rifornire',
	'tru_trustPayments_Total' => 'Totale',
	'tru_trustPayments_Reset' => 'Ripristina',
	'tru_trustPayments_Full' => 'Pieno',
	'tru_trustPayments_Empty refund not permitted' => 'Rimborso vuoto non consentito.',
	'tru_trustPayments_Void' => 'Vuoto',
	'tru_trustPayments_Complete' => 'Completare',
	'tru_trustPayments_Refund' => 'Rimborso',
	'tru_trustPayments_Name' => 'Nome',
	'tru_trustPayments_SKU' => 'SKU',
	'tru_trustPayments_Quantity' => 'Quantità',
	'tru_trustPayments_Reduction' => 'Riduzione',
	'tru_trustPayments_Refund amount' => 'Importo rimborsato',
	
	// menu
	'tru_trustPayments_transaction_title' => 'Trust Payments Transazione'
);