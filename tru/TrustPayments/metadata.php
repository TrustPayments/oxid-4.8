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
require_once(__DIR__.'/autoload.php');



/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id' => 'truTrustPayments',
    'title' => array(
        'de' => 'TRU :: TrustPayments',
        'en' => 'TRU :: TrustPayments'
    ),
    'description' => array(
        'de' => 'TRU TrustPayments Module',
        'en' => 'TRU TrustPayments Module'
    ),
    'thumbnail' => 'out/pictures/picture.png',
    'version' => '1.0.40',
    'author' => 'customweb GmbH',
    'url' => 'https://www.customweb.com',
    'email' => 'info@customweb.com',
    'extend' => array(
    	'oxorder' => 'tru/TrustPayments/Extend/Application/Model/trutrustpayments_oxorder',
    	'oxpaymentlist' => 'tru/TrustPayments/Extend/Application/Model/trutrustpayments_payment_list',
    	'oxbasketitem' => 'tru/TrustPayments/Extend/Application/Model/trutrustpayments_oxbasketitem',
    	'oxstart' => 'tru/TrustPayments/Extend/Application/Controller/trutrustpayments_start',
    	'basket' => 'tru/TrustPayments/Extend/Application/Controller/trutrustpayments_basket',
    	'order' => 'tru/TrustPayments/Extend/Application/Controller/trutrustpayments_order',
    	'login' => 'tru/TrustPayments/Extend/Application/Controller/Admin/trutrustpayments_login',
    	'module_config' => 'tru/TrustPayments/Extend/Application/Controller/Admin/trutrustpayments_module_config',
    	'navigation' => 'tru/TrustPayments/Extend/Application/Controller/Admin/trutrustpayments_navigation',
    	'order_list' => 'tru/TrustPayments/Extend/Application/Controller/Admin/trutrustpayments_order_list',
    ),
	'files' => array(
		'TruTrustPaymentsSetup' => 'tru/TrustPayments/Core/TruTrustPaymentsSetup.php',
		'tru_trustPayments_Webhook' => 'tru/TrustPayments/Application/Controller/tru_trustPayments_Webhook.php',
		'tru_trustPayments_Pdf' => 'tru/TrustPayments/Application/Controller/tru_trustPayments_Pdf.php',
		'tru_trustPayments_Alert' => 'tru/TrustPayments/Application/Controller/Admin/tru_trustPayments_Alert.php',
		'tru_trustPayments_RefundJob' => 'tru/TrustPayments/Application/Controller/Admin/tru_trustPayments_RefundJob.php',
		'tru_trustPayments_Transaction' => 'tru/TrustPayments/Application/Controller/Admin/tru_trustPayments_Transaction.php',
    ),
    'templates' => array(
        'truTrustPaymentsCheckoutBasket.tpl' => 'tru/TrustPayments/Application/views/pages/truTrustPaymentsCheckoutBasket.tpl',
        'truTrustPaymentsCron.tpl' => 'tru/TrustPayments/Application/views/pages/truTrustPaymentsCron.tpl',
        'truTrustPaymentsError.tpl' => 'tru/TrustPayments/Application/views/pages/truTrustPaymentsError.tpl',
        'truTrustPaymentsTransaction.tpl' => 'tru/TrustPayments/Application/views/admin/tpl/truTrustPaymentsTransaction.tpl',
        'truTrustPaymentsRefundJob.tpl' => 'tru/TrustPayments/Application/views/admin/tpl/truTrustPaymentsRefundJob.tpl',
    	'truTrustPaymentsOrderList.tpl' => 'tru/TrustPayments/Application/views/admin/tpl/truTrustPaymentsOrderList.tpl',
    	'truTrustPaymentsHeader.tpl' => 'tru/TrustPayments/Application/views/admin/tpl/truTrustPaymentsHeader.tpl',
    ),
    'blocks' => array(
        array(	
        	'template' => 'page/checkout/order.tpl',
        	'block' => 'shippingAndPayment',
        	'file' => 'Application/views/blocks/truTrustPayments_checkout_order_shippingAndPayment.tpl'
        ),
        array(
            'template' => 'layout/base.tpl',
            'block' => 'base_js',
            'file' => 'Application/views/blocks/truTrustPayments_include_cron.tpl'
        ),
        array(
            'template' => 'login.tpl',
            'block' => 'admin_login_form',
            'file' => 'Application/views/blocks/truTrustPayments_include_cron.tpl'
        ),
        array(
            'template' => 'truTrustPaymentsHeader.tpl',
            'block' => 'admin_header_links',
            'file' => 'Application/views/blocks/truTrustPayments_admin_header_links.tpl'
        ),
    	array(
    		'template' => 'page/account/order.tpl',
    		'block' => 'account_order_history',
    		'file' => 'Application/views/blocks/truTrustPayments_account_order_history.tpl'
    	),
    ),
    'settings' => array(
    	array(
    		'group' => 'truTrustPaymentsTrust PaymentsSettings',
    		'name' => 'truTrustPaymentsSpaceId',
    		'type' => 'str',
    		'value' => ''
    	),
        array(
            'group' => 'truTrustPaymentsTrust PaymentsSettings',
            'name' => 'truTrustPaymentsUserId',
            'type' => 'str',
            'value' => ''
        ),
    	array(
    		'group' => 'truTrustPaymentsTrust PaymentsSettings',
    		'name' => 'truTrustPaymentsAppKey',
    		'type' => 'str',
    		'value' => ''
    	),
        array(
            'group' => 'truTrustPaymentsShopSettings',
            'name' => 'truTrustPaymentsEmailConfirm',
            'type' => 'bool',
            'value' => true
        ),
    	array(
    		'group' => 'truTrustPaymentsShopSettings',
    		'name' => 'truTrustPaymentsEnforceConsistency',
    		'type' => 'bool',
    		'value' => true
    	),
        array(
            'group' => 'truTrustPaymentsShopSettings',
            'name' => 'truTrustPaymentsInvoiceDoc',
            'type' => 'bool',
            'value' => true
        ),
        array(
            'group' => 'truTrustPaymentsShopSettings',
            'name' => 'truTrustPaymentsPackingDoc',
            'type' => 'bool',
            'value' => true
        ),
        array(
            'group' => 'truTrustPaymentsShopSettings',
            'name' => 'truTrustPaymentsLogLevel',
            'type' => 'select',
            'value' => 'Error',
            'constraints' => 'Error|Info|Debug'
        ),
    	array(
    		'group' => 'truTrustPaymentsSpaceViewSettings',
    		'name' => 'truTrustPaymentsSpaceViewId',
    		'type' => 'str',
    		'value' => ''
    	),
    ),
    'events' => array(
        'onActivate' => 'TruTrustPaymentsSetup::onActivate',
        'onDeactivate' => 'TruTrustPaymentsSetup::onDeactivate'
    )
);