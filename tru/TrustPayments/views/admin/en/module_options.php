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
$sLangName = 'English';

$aLang = array(
    'charset' => 'UTF-8',
    'truTrustPayments' => 'TRU TrustPayments',
	
	'SHOP_MODULE_GROUP_truTrustPaymentsTrust PaymentsSettings' => 'Trust Payments Settings',
	'SHOP_MODULE_GROUP_truTrustPaymentsShopSettings' => 'Shop Settings',
	'SHOP_MODULE_GROUP_truTrustPaymentsSpaceViewSettings' => 'Space View Options',
	'SHOP_MODULE_truTrustPaymentsAppKey' => 'Authentication Key',
	'SHOP_MODULE_truTrustPaymentsUserId' => 'User Id',
    'SHOP_MODULE_truTrustPaymentsSpaceId' => 'Space Id',
	'SHOP_MODULE_truTrustPaymentsSpaceViewId' => 'Space View Id',
	'SHOP_MODULE_truTrustPaymentsEmailConfirm' => 'Email Confirm',
	'SHOP_MODULE_truTrustPaymentsInvoiceDoc' => 'Invoice Doc',
	'SHOP_MODULE_truTrustPaymentsPackingDoc' => 'Packing Doc',
	'SHOP_MODULE_truTrustPaymentsEnforceConsistency' => 'Enforce consistency',
    'SHOP_MODULE_truTrustPaymentsLogLevel' => 'Log Level',
    'SHOP_MODULE_truTrustPaymentsLogLevel_' => ' - ',
    'SHOP_MODULE_truTrustPaymentsLogLevel_Error' => 'Error',
    'SHOP_MODULE_truTrustPaymentsLogLevel_Debug' => 'Debug',
	'SHOP_MODULE_truTrustPaymentsLogLevel_Info' => 'Info',
	
	'HELP_SHOP_MODULE_truTrustPaymentsUserId' => 'The user requires full permission in the space the shop is linked to.',
	'HELP_SHOP_MODULE_truTrustPaymentsSpaceViewId' => 'The space view ID allows to control the styling of the payment form and the payment page within the space. In multi shop setups it allows to adapt the payment form to different styling per sub store without requiring a dedicated space.',
	'HELP_SHOP_MODULE_truTrustPaymentsEmailConfirm' => 'You may deactivate the OXID order confirmation email for Trust Payments transactions.',
	'HELP_SHOP_MODULE_truTrustPaymentsInvoiceDoc' => 'You may allow customers to download invoices in their account area.',
	'HELP_SHOP_MODULE_truTrustPaymentsPackingDoc' => 'You may allow customers to download packing slips in their account area.',
	'HELP_SHOP_MODULE_truTrustPaymentsEnforceConsistency' => 'Require that the line items of the transaction correspond to those of the purchase order in Magento. This may result in the Trust Payments payment methods not being available to the customer in certain cases. In return, it is ensured that only correct data is transmitted to Trust Payments.',
	
	'tru_trustPayments_Settings saved successfully.' => 'Settings saved successfully.',
	'tru_trustPayments_Payment methods successfully synchronized.' => 'Payment methods successfully synchronized.',
	'tru_trustPayments_Webhook URL updated.' => 'Webhook URL updated.',
	//TODO remove uneeded
	
	'tru_trustPayments_Download Invoice' => 'Download Invoice',
	'tru_trustPayments_Download Packing Slip' => 'Download Packing Slip',
	'tru_trustPayments_Delivery Fee' => 'Delivery Fee',
	'tru_trustPayments_Payment Fee' => 'Payment Fee',
	'tru_trustPayments_Gift Card' => 'Gift Card',
	'tru_trustPayments_Wrapping Fee' => 'Wrapping Fee',
	'tru_trustPayments_Total Discount' => 'Total Discount',
	'tru_trustPayments_VAT' => 'VAT',
	'tru_trustPayments_Order already exists. Please check if you have already received a confirmation, then try again.' => 'Order already exists. Please check if you have already received a confirmation, then try again.',
	'tru_trustPayments_Unable to load transaction !id in space !space.' => 'Unable to load transaction !id in space !space',
	'tru_trustPayments_Manual Tasks (!count)' => 'Manual Tasks (!count)',
	'tru_trustPayments_Unable to confirm order in state !state.' => 'Unable to confirm order in state !state.',
	'tru_trustPayments_Not a Trust Payments order.' => 'Not a Trust Payments order.',
	'tru_trustPayments_An unknown error occurred, and the order could not be loaded.' => 'An unknown error occurred, and the order could not be loaded.',
	'tru_trustPayments_Successfully created and sent completion job !id.' => 'Successfully created and sent completion job !id.',
	'tru_trustPayments_Successfully created and sent void job !id.' => 'Successfully created and sent void job !id.',
	'tru_trustPayments_Successfully created and sent refund job !id.' => 'Successfully created and sent refund job !id.',
	'tru_trustPayments_Unable to load transaction for order !id.' => 'Unable to load transaction for order !id.',
	'tru_trustPayments_Completions' => 'Completions',
	'tru_trustPayments_Completion' => 'Completion',
	'tru_trustPayments_Refunds' => 'Refunds',
	'tru_trustPayments_Voids' => 'Voids',
	'tru_trustPayments_Completion #!id' => 'Completion #!id',
	'tru_trustPayments_Refund #!id' => 'Refund #!id',
	'tru_trustPayments_Void #!id' => 'Void #!id',
	'tru_trustPayments_Transaction information' => 'Transaction information',
	'tru_trustPayments_Authorization amount' => 'Authorization amount',
	'tru_trustPayments_The amount which was authorized with the Trust Payments transaction.' => 'The amount which was authorized with the Trust Payments transaction.',
	'tru_trustPayments_Transaction #!id' => 'Transaction #!id',
	'tru_trustPayments_Status' => 'Status',
	'tru_trustPayments_Status in the Trust Payments system.' => 'Status in the Trust Payments system.',
	'tru_trustPayments_Payment method' => 'Payment method',
	'tru_trustPayments_Open in your Trust Payments backend.' => 'Open in your Trust Payments backend.',
	'tru_trustPayments_Open' => 'Open',
	'tru_trustPayments_Trust Payments Link' => 'Trust Payments Link',
	
	// tpl translations
	'tru_trustPayments_Restock' => 'Restock',
	'tru_trustPayments_Total' => 'Total',
	'tru_trustPayments_Reset' => 'Reset',
	'tru_trustPayments_Full' => 'Full',
	'tru_trustPayments_Empty refund not permitted' => 'Empty refund not permitted.',
	'tru_trustPayments_Void' => 'Void',
	'tru_trustPayments_Complete' => 'Complete',
	'tru_trustPayments_Refund' => 'Refund',
	'tru_trustPayments_Name' => 'Name',
	'tru_trustPayments_SKU' => 'SKU',
	'tru_trustPayments_Quantity' => 'Quantity',
	'tru_trustPayments_Reduction' => 'Reduction',
	'tru_trustPayments_Refund amount' => 'Refund amount',
	
	// menu
	'tru_trustPayments_transaction_title' => 'Trust Payments Transaction'
);