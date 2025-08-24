CREATE TABLE IF NOT EXISTS `mc_faqmulti` (
  `id_faqmulti` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module_faqmulti` varchar(25) NOT NULL DEFAULT 'home',
  `id_module` int(11) DEFAULT NULL,
  `order_faqmulti` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_faqmulti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_faqmulti_content` (
  `id_faqmulti_content` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_faqmulti` int(11) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `title_faqmulti` varchar(125) NOT NULL,
  `desc_faqmulti` text,
  `published_faqmulti` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_faqmulti_content`),
  KEY `id_lang` (`id_lang`),
  KEY `id_faqmulti` (`id_faqmulti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_faqmulti_content`
  ADD CONSTRAINT `mc_faqmulti_content_ibfk_1` FOREIGN KEY (`id_faqmulti`) REFERENCES `mc_faqmulti` (`id_faqmulti`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_faqmulti_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;