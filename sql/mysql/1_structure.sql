-- phpMyAdmin SQL Dump
-- version 2.11.3deb1ubuntu1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 18 Ott, 2008 at 11:12 AM
-- Versione MySQL: 5.0.51
-- Versione PHP: 5.2.4-2ubuntu5.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `openupload`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `acl`
--

CREATE TABLE IF NOT EXISTS `acl` (
  `id` int(11) NOT NULL auto_increment,
  `module` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `access` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `banned`
--

CREATE TABLE IF NOT EXISTS `banned` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(50) NOT NULL,
  `access` varchar(50) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `mime` varchar(200) NOT NULL,
  `description` tinytext NOT NULL,
  `size` int(12) NOT NULL,
  `remove` varchar(100) NOT NULL,
  `user_login` varchar(100) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `upload_date` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `file_options`
--

CREATE TABLE IF NOT EXISTS `file_options` (
  `id` bigint(20) NOT NULL auto_increment,
  `file_id` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `file_id` (`file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `name` varchar(50) NOT NULL,
  `description` varchar(250) default NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `langs`
--

CREATE TABLE IF NOT EXISTS `langs` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `locale` varchar(10) NOT NULL,
  `browser` varchar(200) default NULL,
  `charset` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `plugin_acl`
--

CREATE TABLE IF NOT EXISTS `plugin_acl` (
  `id` int(11) NOT NULL auto_increment,
  `group_name` varchar(50) NOT NULL,
  `plugin` varchar(100) NOT NULL,
  `access` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `group_name` (`group_name`,`plugin`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `plugin_options`
--

CREATE TABLE IF NOT EXISTS `plugin_options` (
  `id` int(11) NOT NULL auto_increment,
  `plugin` varchar(100) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `group_name` varchar(50) NOT NULL default 'registered',
  `email` varchar(250) NOT NULL,
  `lang` varchar(10) NOT NULL default 'en',
  `reg_date` datetime NOT NULL,
  `regid` varchar(50) NOT NULL default '',
  `active` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

--
-- Table structure for `activitylog`
--

CREATE TABLE `activitylog` (
  `id` int(20) NOT NULL auto_increment,
  `level` varchar(20) NOT NULL,
  `log_time` datetime NOT NULL,
  `ip` varchar(20) NOT NULL,
  `user_login` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `realaction` varchar(50) default NULL,
  `plugin` varchar(50) default NULL,
  `result` varchar(100) default NULL,
  `moreinfo` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
