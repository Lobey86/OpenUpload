--
-- Dump dei dati per la tabella `acl`
--

INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(5, 'auth', '*', '*', 'deny');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(6, 'files', 'l', 'unregistered', 'deny');
INSERT INTO `acl` (`id`, `module`, `action`, `group_name`, `access`) VALUES(7, 'files', '*', '*', 'allow');

--
-- Dump dei dati per la tabella `plugin_acl`
--

INSERT INTO `plugin_acl` (`id`, `group_name`, `plugin`, `access`) VALUES(4, 'unregistered', 'password', 'enable');
INSERT INTO `plugin_acl` (`id`, `group_name`, `plugin`, `access`) VALUES(5, 'unregistered', 'captcha', 'enable');
INSERT INTO `plugin_acl` (`id`, `group_name`, `plugin`, `access`) VALUES(6, 'unregistered', 'email', 'enable');
