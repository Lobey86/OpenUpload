-- phpMyAdmin SQL Dump
-- version 2.11.3deb1ubuntu1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 18 Ott, 2008 at 11:15 AM
-- Versione MySQL: 5.0.51
-- Versione PHP: 5.2.4-2ubuntu5.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `openupload`
--

--
-- Dump dei dati per la tabella `banned`
--

INSERT INTO `banned` (`id`, `ip`, `access`, `priority`) VALUES(1, '127.0.0.1', 'allow', 1);
INSERT INTO `banned` (`id`, `ip`, `access`, `priority`) VALUES(2, '0.0.0.0/0', 'allow', 9999999);

--
-- Dump dei dati per la tabella `groups`
--

INSERT INTO `groups` (`name`, `description`) VALUES('admins', 'Administrators group');
INSERT INTO `groups` (`name`, `description`) VALUES('registered', 'Registered Users');
INSERT INTO `groups` (`name`, `description`) VALUES('unregistered', 'Unregistered users');

--
-- Dump dei dati per la tabella `langs`
--

INSERT INTO `langs` (`id`, `name`, `locale`, `browser`, `charset`, `active`) VALUES('en', 'English', 'en_EN', '[en];[en-EN]', 'utf-8', 1);
INSERT INTO `langs` (`id`, `name`, `locale`, `browser`, `charset`, `active`) VALUES('it', 'Italiano', 'it_IT.utf8', '[it];[it-IT]', 'utf-8', 1);
INSERT INTO `langs` (`id`, `name`, `locale`, `browser`, `charset`, `active`) VALUES('fr', 'Français', 'fr_FR.utf8', '[fr];[fr-FR]', 'utf-8', 1);
INSERT INTO `langs` (`id`, `name`, `locale`, `browser`, `charset`, `active`) VALUES('de', 'Deutsch', 'de_DE.utf8', '[de];[de-DE]', 'utf-8', 1);
INSERT INTO `langs` (`id`, `name`, `locale`, `browser`, `charset`, `active`) VALUES('pt_BR', 'Português', 'pt_BR.utf8', '[pt];[pt-BR]', 'utf-8', 1);
INSERT INTO `langs` (`id`, `name`, `locale`, `browser`, `charset`, `active`) VALUES('zh_CN', '中文', 'zh_CN.utf8', '[zh];[zh-CN]', 'utf-8', 1);

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `name`, `group_name`, `email`, `lang`, `reg_date`, `regid`, `active`) VALUES(1, 'admin', '$1$sLCQ3aFR$rCIb4Owhgi3mIHgYnbA351', 'Administrator', 'admins', 'openupload@yourdomain.com', 'en', now(), '', 1);

--
-- Dump dei dati per la tabella `acl`
--

INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(1, '*', '*', 'admins', 'allow');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(2, 'admin', '*', 'admins', 'allow');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(3, 'admin', '*', '*', 'deny');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(4, 'auth', 'login', 'unregistered', 'allow');

--
-- Dump dei dati per la tabella `plugin_acl`
--

INSERT INTO `plugin_acl` (`id`, `group_name`, `plugin`, `access`) VALUES(1, 'admins', 'password', 'enable');
INSERT INTO `plugin_acl` (`id`, `group_name`, `plugin`, `access`) VALUES(2, 'admins', 'captcha', 'enable');
INSERT INTO `plugin_acl` (`id`, `group_name`, `plugin`, `access`) VALUES(3, 'admins', 'email', 'enable');


INSERT INTO `plugin_options` (`id`, `plugin`, `group_name`, `name`, `value`) VALUES (1, 'mimetypes', 'unregistered', 'message', 'Pdf, JPEG');
INSERT INTO `plugin_options` (`id`, `plugin`, `group_name`, `name`, `value`) VALUES (2, 'mimetypes', 'unregistered', 'allowed', 'application/pdf
image/jpeg
');

