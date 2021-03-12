[{$smarty.block.parent}]
[{if ($oView->isTrustPaymentsTransaction()) }]
<div class="panel panel-default" id="TrustPayments-payment-information">
	<div class="panel-heading">
		<h3 class="panel-title section">[{oxmultilang ident="PAYMENT_INFORMATION"}]</h3>
	</div>
	<div class="panel-body">
		<div id="TrustPayments-iframe-spinner" class="trustpayments-loader"></div>
		<div id="TrustPayments-iframe-container" style="display:none"></div>
		<input type="hidden" name="TrustPayments-iframe-loaded" value="false">
	</div>
</div>
[{capture name=TrustPaymentsInitScript assign=TrustPaymentsInitScript}]
function initTrustPaymentsIframe(){
	if(typeof TrustPayments === 'undefined') {
    	setTimeout(initTrustPaymentsIframe, 500);
	} else {
    	TrustPayments.init('[{$oView->getTrustPaymentsPaymentId()}]');
	}
}
jQuery().ready(initTrustPaymentsIframe);
[{/capture}]
[{oxscript add=$TrustPaymentsInitScript priority=10}]
[{oxscript include=$oView->getTrustPaymentsJavascriptUrl() priority=8}]
[{oxscript include=$oViewConf->getModuleUrl("truTrustPayments", "out/src/js/trustpayments.js") priority=9}]
[{oxstyle include=$oViewConf->getModuleUrl("truTrustPayments", "out/src/css/spinner.css")}]
[{/if}]