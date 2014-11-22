--
-- Dump dei dati per la tabella acl
--

INSERT INTO acl VALUES(5, 'auth', '*', '*', 'deny');
INSERT INTO acl VALUES(6, 'files', 'l', 'unregistered', 'deny');
INSERT INTO acl VALUES(7, 'files', '*', '*', 'allow');

--
-- Dump dei dati per la tabella plugin_acl
--

INSERT INTO plugin_acl VALUES (4, 'unregistered', 'password', 'enable');
INSERT INTO plugin_acl VALUES (5, 'unregistered', 'captcha', 'enable');
INSERT INTO plugin_acl VALUES (6, 'unregistered', 'email', 'enable');
