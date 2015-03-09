1. Copy /center folder to your server

2. Change login data to database in:
system/config.php

3. Define root in system/config.php if nessessary

4. Make /log directory writable

5. Create DB table
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
