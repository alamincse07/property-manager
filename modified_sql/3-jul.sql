ALTER TABLE `estate`
ADD COLUMN `bk`  tinyint(2) NULL DEFAULT 1 AFTER `hw`,
ADD COLUMN `ht`  tinyint(2) NULL DEFAULT 1 AFTER `bk`,
ADD COLUMN `rm`  tinyint(2) NULL DEFAULT 1 AFTER `ht`,
ADD COLUMN `vast`  tinyint(2) NULL DEFAULT 1 AFTER `rm`,
ADD COLUMN `airbnb`  tinyint(2) NULL DEFAULT 1 AFTER `vast`,
ADD COLUMN `otalo`  tinyint(2) NULL DEFAULT 1 AFTER `airbnb`;

