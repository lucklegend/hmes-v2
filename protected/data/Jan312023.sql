ALTER TABLE `limslab`.`test` 
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `worksheet`,
ADD COLUMN `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_created`;

ALTER TABLE `limslab`.`testcategory` 
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `labId`,
ADD COLUMN `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_created`;

ALTER TABLE `limslab`.`sampletype` 
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `showItem`,
ADD COLUMN `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_created`;

ALTER TABLE `limslab`.`samplename` 
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `resolution`,
ADD COLUMN `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_created`;

ALTER TABLE `limslab`.`samplecode` 
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `cancelled`,
ADD COLUMN `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_created`;

ALTER TABLE `limslab`.`sample` 
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `model_no`,
ADD COLUMN `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_created`;

ALTER TABLE `limslab`.`request` 
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `vat`,
ADD COLUMN `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_created`;

ALTER TABLE `limslab`.`generatedrequest` 
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `number`,
ADD COLUMN `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_created`;

ALTER TABLE `limslab`.`analysis` 
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `worksheet`,
ADD COLUMN `date_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_created`;