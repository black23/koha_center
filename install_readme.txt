1. Copy /center folder to your server

2. Change login data to database in:
system/config.php

3. Define root in system/config.php if nessessary

4. Make /log directory writable

5. Create DB tables
CREATE TABLE `cen_smshistory` (
  `sms_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `phone` varchar(16) COLLATE 'utf8_unicode_ci' NOT NULL,
  `type` varchar(8) COLLATE 'utf8_unicode_ci' NOT NULL,
  `message` varchar(459) COLLATE 'utf8_unicode_ci' NOT NULL,
  `time` datetime NOT NULL,
  `system_sms_id` int NULL,
  `status` tinyint NULL,
  `sender_id` tinyint NULL,
  `solver_id` int NULL
) COMMENT='' ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `cen_smshistory`
ADD UNIQUE `sms_id` (`sms_id`),
ADD INDEX `phone` (`phone`);

CREATE TABLE `cen_circulations` (
  `circulation_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `datetime` datetime NOT NULL,
  `borrowernumber` int NULL,
  `target` tinyint NULL,
  `branchcode` varchar(10) COLLATE 'utf8_unicode_ci' NOT NULL,
) COMMENT='' ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `cen_circulations`
ADD UNIQUE `circulation_id` (`circulation_id`),
ADD INDEX `target` (`target`);

ALTER TABLE `cen_circulations`
ADD `branchcode` varchar(10) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `datetime`,
COMMENT=''
REMOVE PARTITIONING;

ALTER TABLE `cen_circulations`
CHANGE `borrowernumber` `internet` int(11) NOT NULL AFTER `circulation_id`,
CHANGE `target` `study_room` int NOT NULL AFTER `internet`,
COMMENT=''
REMOVE PARTITIONING;
