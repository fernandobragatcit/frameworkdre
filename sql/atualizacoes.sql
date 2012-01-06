CREATE TABLE `fwk_verificacao` (
  `tabela_verificacao` varchar(100) NOT NULL,
  `data_verificacao` date DEFAULT NULL,
  PRIMARY KEY (`tabela_verificacao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

#####Atualização em 06/01/2012#####
CREATE TABLE `ibs1`.`fwk_portal`( 
   `id_portal` INT(11) NOT NULL AUTO_INCREMENT , 
   `nome_portal` VARCHAR(50) NOT NULL , 
   PRIMARY KEY (`id_portal`)
 )  ENGINE=INNODB COMMENT='' ROW_FORMAT=DEFAULT;
 
 ALTER TABLE `ibs1`.`fwk_ficheiro` ADD COLUMN `id_portal` INT(11) DEFAULT '1' NULL AFTER `id_ficheiro`;
#colocar campo id_portal como fk para a tabela fwk_portal.

ALTER TABLE `ibs1`.`fwk_newsletter` ADD COLUMN `id_portal` INT(11) DEFAULT '1' NULL AFTER `email`;
#colocar campo id_portal como fk para a tabela fwk_portal.