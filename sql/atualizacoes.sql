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

######Atualização em 11/01/2012#####
ALTER TABLE `fwk_ficheiro` ADD COLUMN `id_portal` INT(11) DEFAULT '2' NULL AFTER `id_ficheiro`;
ALTER TABLE `fwk_ficheiro` ADD CONSTRAINT `fk_menu_portal` FOREIGN KEY (`id_portal`) REFERENCES `fwk_portal` (`id_portal`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `fwk_menu` ADD COLUMN `id_portal` INT(11) DEFAULT '1' NULL AFTER `ordem_menu`;
ALTER TABLE `fwk_menu` ADD CONSTRAINT `fk_menu_portal` FOREIGN KEY (`id_portal`) REFERENCES `fwk_portal` (`id_portal`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `fwk_item_menu` ADD COLUMN `id_portal` INT(11) DEFAULT '1' NULL AFTER `ordem_item_menu`;
ALTER TABLE `fwk_item_menu` ADD CONSTRAINT `fk_portal` FOREIGN KEY (`id_portal`) REFERENCES `fwk_portal` (`id_portal`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `fwk_direitos` ADD COLUMN `id_portal` INT(11) DEFAULT '1' NULL AFTER `nome_direito`;
ALTER TABLE `fwk_direitos` ADD CONSTRAINT `fk_portal` FOREIGN KEY (`id_portal`) REFERENCES `fwk_portal` (`id_portal`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `fwk_grupo` ADD COLUMN `id_portal` INT(11) DEFAULT '1' NULL AFTER `nome_grupo`;
ALTER TABLE `fwk_grupo` ADD CONSTRAINT `fk_portal` FOREIGN KEY (`id_portal`) REFERENCES `fwk_portal` (`id_portal`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `fwk_banner` ADD COLUMN `id_portal` INT(11) DEFAULT '2' NULL AFTER `status_banner`;
ALTER TABLE `fwk_banner` ADD CONSTRAINT `fk_portal` FOREIGN KEY (`id_portal`) REFERENCES `fwk_portal` (`id_portal`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `fwk_galeria` ADD COLUMN `id_portal` INT(11) DEFAULT '2' NULL AFTER `identificador_galeria`;
ALTER TABLE `fwk_galeria` ADD CONSTRAINT `fk_portal` FOREIGN KEY (`id_portal`) REFERENCES `fwk_portal` (`id_portal`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `fwk_noticias` ADD COLUMN `id_portal` INT(11) DEFAULT '2' NULL AFTER `id_noticia`;
ALTER TABLE `fwk_noticias` ADD CONSTRAINT `fk_portal` FOREIGN KEY (`id_portal`) REFERENCES `fwk_portal` (`id_portal`) ON DELETE CASCADE ON UPDATE CASCADE;
