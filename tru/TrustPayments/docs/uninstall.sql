DROP INDEX idx_tru_oxorder_oxtransstatus ON `oxorder`;

-- not required to persist following tables after uninstall
DROP TABLE `truTrustPayments_alert`;
DROP TABLE `truTrustPayments_cron`;