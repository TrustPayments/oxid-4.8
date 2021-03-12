[{$smarty.block.parent}]
[{foreach from=$oView->getTruAlerts() item=alert}]
    <li class="sep">
        <a href="[{$oViewConf->getSelfLink()}]&cl=tru_trustPayments_Alert&amp;fnc=[{$alert.func}]" target="[{$alert.target}]" class="rc"><b>[{$alert.title}]</b></a>
    </li>
[{/foreach}]