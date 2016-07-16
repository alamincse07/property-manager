ALTER TABLE `estate`
ADD COLUMN `default_min_los`  int(10) NULL AFTER `slayt`,
ADD COLUMN `default_nightly`  numeric(50,0) NULL AFTER `default_min_los`,
ADD COLUMN `default_weekly`  numeric(50,0) NULL AFTER `default_nightly`,
ADD COLUMN `fk`  tinyint(2) NULL DEFAULT 1 AFTER `default_weekly`,
ADD COLUMN `vrbo`  tinyint(2) NULL DEFAULT 0 AFTER `fk`,
ADD COLUMN `hw`  tinyint(2) NULL DEFAULT 1 AFTER `vrbo`;


truncate table `price_rates`;
ALTER TABLE `price_rates`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT FIRST ;