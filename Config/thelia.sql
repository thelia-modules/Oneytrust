
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- Oneytrust
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `Oneytrust`;

CREATE TABLE `Oneytrust`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `commande` TEXT,
    `status` TEXT,
    `validation` TEXT,
    `motifs` TEXT,
    `evaldate` TEXT,
    `customerIp` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
