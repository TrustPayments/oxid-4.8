CREATE TABLE IF NOT EXISTS `truTrustPayments_transaction` (
  `OXID` char(32) NOT NULL,
  `OXORDERID` char(32) NOT NULL,
  `TRUTRANSACTIONID` bigint(20) unsigned NOT NULL,
  `TRUSTATE` varchar(255) NOT NULL,
  `TRUSPACEID` bigint(20) unsigned NOT NULL,
  `TRUSPACEVIEWID` bigint(20) unsigned DEFAULT NULL,
  `TRUFAILUREREASON` longtext,
  `TRUTEMPBASKET` longtext,
  `TRUVERSION` int(11) NOT NULL DEFAULT 0,
  `TRUUPDATED` TIMESTAMP NOT NULL DEFAULT now() ON UPDATE now(),
  PRIMARY KEY (`OXID`),
  UNIQUE KEY `unq_transaction_id_space_id` (`TRUTRANSACTIONID`,`TRUSPACEID`),
  UNIQUE KEY `unq_order_id` (`OXORDERID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `truTrustPayments_completionjob` (
  `OXID` char(32) NOT NULL,
  `OXORDERID` char(32) NOT NULL,
  `TRUTRANSACTIONID` bigint(20) unsigned NOT NULL,
  `TRUJOBID` bigint(20) unsigned,
  `TRUSTATE` varchar(255) NOT NULL,
  `TRUSPACEID` bigint(20) unsigned NOT NULL,
  `TRUFAILUREREASON` longtext,
  `TRUUPDATED` TIMESTAMP NOT NULL DEFAULT now() ON UPDATE now(),
  PRIMARY KEY (`OXID`),
  INDEX `unq_job_id_space_id` (`TRUJOBID`,`TRUSPACEID`),
  INDEX `idx_order_id` (`OXORDERID`),
  INDEX `idx_state` (`TRUSTATE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `truTrustPayments_voidjob` (
  `OXID` char(32) NOT NULL,
  `OXORDERID` char(32) NOT NULL,
  `TRUTRANSACTIONID` bigint(20) unsigned NOT NULL,
  `TRUJOBID` bigint(20) unsigned,
  `TRUSTATE` varchar(255) NOT NULL,
  `TRUSPACEID` bigint(20) unsigned NOT NULL,
  `TRUFAILUREREASON` longtext,
  `TRUUPDATED` TIMESTAMP NOT NULL DEFAULT now() ON UPDATE now(),
  PRIMARY KEY (`OXID`),
  INDEX `unq_job_id_space_id` (`TRUJOBID`,`TRUSPACEID`),
  INDEX `idx_order_id` (`OXORDERID`),
  INDEX `idx_state` (`TRUSTATE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `truTrustPayments_refundjob` (
  `OXID` char(32) NOT NULL,
  `OXORDERID` char(32) NOT NULL,
  `TRUTRANSACTIONID` bigint(20) unsigned NOT NULL,
  `TRUJOBID` bigint(20) unsigned,
  `TRUSTATE` varchar(255) NOT NULL,
  `TRUSPACEID` bigint(20) unsigned NOT NULL,
  `FORMREDUCTIONS` longtext,
  `TRURESTOCK` bool NOT NULL,
  `TRUFAILUREREASON` longtext,
  `TRUUPDATED` TIMESTAMP NOT NULL DEFAULT now() ON UPDATE now(),
  PRIMARY KEY (`OXID`),
  INDEX `unq_job_id_space_id` (`TRUJOBID`,`TRUSPACEID`),
  INDEX `idx_order_id` (`OXORDERID`),
  INDEX `idx_state` (`TRUSTATE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `truTrustPayments_cron` (
  `OXID` char(32) NOT NULL,
  `TRUFAILUREREASON` longtext,
  `TRUSTATE` char(7),
  `TRUSCHEDULED` DATETIME NOT NULL,
  `TRUSTARTED` DATETIME,
  `TRUCOMPLETED` DATETIME,
  `TRUCONSTRAINT` SMALLINT,
  PRIMARY KEY (`OXID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `truTrustPayments_alert` (
  `TRUKEY` varchar(11) NOT NULL,
  `TRUFUNC` varchar(20) NOT NULL,
  `TRUTARGET` varchar(20) NOT NULL,
  `TRUCOUNT` int unsigned DEFAULT NULL,
  `TRUUPDATED` TIMESTAMP NOT NULL DEFAULT now() ON UPDATE now(),
  PRIMARY KEY (`TRUKEY`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `truTrustPayments_alert` (`TRUKEY`, `TRUFUNC`, `TRUTARGET`, `TRUCOUNT`) VALUES ('manual_task', 'manualtask', '_parent', 0);

SET SQL_MODE='ALLOW_INVALID_DATES';
CREATE INDEX idx_tru_oxorder_oxtransstatus ON `oxorder` (`OXTRANSSTATUS`);