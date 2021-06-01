CREATE TABLE `node_tree` (
  `idNode` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level` int(11) unsigned NOT NULL,
  `iLeft` int(11) unsigned NOT NULL,
  `iRight` int(11) unsigned NOT NULL,
  PRIMARY KEY (`idNode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `node_tree_names` (
  `idNode` int(11) unsigned NOT NULL,
  `language` varchar(255) NOT NULL DEFAULT '',
  `nodeName` varchar(255) NOT NULL DEFAULT '',
  KEY `FK_NODE_TREE` (`idNode`),
  CONSTRAINT `FK_NODE_TREE` FOREIGN KEY (`idNode`) REFERENCES `node_tree` (`idNode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;