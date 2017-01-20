CREATE TABLE IF NOT EXISTS `Admin` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `Email` varchar(100) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `Options` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `OptionID` varchar(10) NOT NULL,
  `OptionCategoryID` varchar(10) NOT NULL,
  `OptionName` varchar(255) NOT NULL,
  `OptionDescr` text NOT NULL,
  `OptionDays` int(11) NOT NULL DEFAULT '1',
  `OptionActive` tinyint(1) NOT NULL,
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `OptionID` (`OptionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `Prices` (
  `PriceID` int(11) NOT NULL AUTO_INCREMENT,
  `PriceStudents` int(11) NOT NULL,
  `PriceDays` int(11) NOT NULL,
  `PriceAmount` float NOT NULL,
  PRIMARY KEY (`PriceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `Quotes` (
  `QuoteID` int(11) NOT NULL AUTO_INCREMENT,
  `QuoteDate` datetime NOT NULL,
  `QuoteType` varchar(4) NOT NULL,
  `QuoteClassTitle` varchar(10) NOT NULL,
  `QuoteStudents` int(11) NOT NULL,
  `QuoteDays` int(11) NOT NULL,
  `QuoteUrgency` int(11) NOT NULL,
  `QuoteEmail` varchar(100) NOT NULL,
  `QuoteName` varchar(60) NOT NULL,
  `QuotePhone` varchar(14) NOT NULL,
  `QuoteLocation` varchar(2) NOT NULL,
  `QuotePrice` varchar(10) NOT NULL,
  PRIMARY KEY (`QuoteID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `Registrations` (
  `RegID` int(11) NOT NULL AUTO_INCREMENT,
  `QuoteID` int(11) DEFAULT NULL,
  `SiteID` int(11) NOT NULL DEFAULT '1',
  `RegDate` datetime NOT NULL,
  `RegCourse` varchar(255) NOT NULL,
  `RegLocation` varchar(2) NOT NULL,
  `RegPrice` varchar(10) NOT NULL,
  `RegAttendees` varchar(4000) NOT NULL,
  `RegCompanyName` varchar(150) DEFAULT NULL,
  `RegCompanyContact` varchar(60) NOT NULL,
  `RegPhone` varchar(14) NOT NULL,
  `RegFax` varchar(14) DEFAULT NULL,
  `RegEmail` varchar(75) NOT NULL,
  `RegAddress1` varchar(150) NOT NULL,
  `RegAddress2` varchar(150) DEFAULT NULL,
  `RegCity` varchar(60) NOT NULL,
  `RegState` varchar(2) NOT NULL,
  `RegZip` varchar(10) NOT NULL,
  `RegNotes` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`RegID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `Sessions` (
  `id` char(32) NOT NULL,
  `data` longtext NOT NULL,
  `last_accessed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `Site` (
  `SiteID` int(11) NOT NULL AUTO_INCREMENT,
  `SiteName` varchar(150) NOT NULL,
  `SiteURL` varchar(200) NOT NULL,
  `SiteEmail` varchar(100) NOT NULL,
  `SiteEmailRecord` varchar(100) NOT NULL,
  `SiteEmailRecord2` varchar(100) NOT NULL,
  `SitePhone` varchar(14) NOT NULL,
  `SiteFax` varchar(14) NOT NULL,
  `SiteLogo` varchar(250) NOT NULL,
  `SiteLogoWidth` int(11) NOT NULL,
  `SiteLogoHeight` int(11) NOT NULL,
  `SiteHotel` decimal(10,2) NOT NULL,
  `SiteRental` decimal(10,2) NOT NULL,
  `SiteInstructor` decimal(10,2) NOT NULL,
  `SitePerDiem` decimal(10,2) NOT NULL,
  `SiteBooks` decimal(10,2) NOT NULL,
  `SiteShipping` decimal(10,2) NOT NULL,
  PRIMARY KEY (`SiteID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;