--
-- Dump dei dati per la tabella `acl`
--
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(5, 'auth', 'register', '*', 'allow');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(6, 'auth', '*', 'unregistered', 'deny');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(7, 'auth', '*', '*', 'allow');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(8, 'files', 'd', 'unregistered', 'allow');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(9, 'files', 'g', 'unregistered', 'allow');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(10, 'files', '*', 'unregistered', 'deny');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(11, 'files', '*', '*', 'allow');

--
-- Dump dei dati per la tabella `plugin_acl`
--

INSERT INTO `plugin_acl` (`id`, `group_name`, `plugin`, `access`) VALUES(4, 'registered', 'password', 'enable');
INSERT INTO `plugin_acl` (`id`, `group_name`, `plugin`, `access`) VALUES(5, 'registered', 'captcha', 'enable');
INSERT INTO `plugin_acl` (`id`, `group_name`, `plugin`, `access`) VALUES(6, 'registered', 'email', 'enable');
