--
-- Table structure for table `coming_soon_emails`
--

CREATE TABLE `coming_soon_emails` (
  `email` varchar(64) collate utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;