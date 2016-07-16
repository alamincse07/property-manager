ALTER TABLE `estate`
ADD COLUMN `sleep`  tinyint(3) NULL AFTER `room`;
ALTER TABLE `estate`
ADD COLUMN `guest_count`  tinyint(3) NULL AFTER `sleep`;

