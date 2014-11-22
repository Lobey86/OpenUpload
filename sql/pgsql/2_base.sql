--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- Name: acl_id_seq; Type: SEQUENCE SET; Schema: public; Owner: openupload
--

SELECT pg_catalog.setval('acl_id_seq', 1, true);


--
-- Name: banned_id_seq; Type: SEQUENCE SET; Schema: public; Owner: openupload
--

SELECT pg_catalog.setval('banned_id_seq', 1, false);


--
-- Name: file_options_id_seq; Type: SEQUENCE SET; Schema: public; Owner: openupload
--

SELECT pg_catalog.setval('file_options_id_seq', 1, true);


--
-- Name: plugin_acl_id_seq; Type: SEQUENCE SET; Schema: public; Owner: openupload
--

SELECT pg_catalog.setval('plugin_acl_id_seq', 1, false);


--
-- Name: plugin_options_id_seq; Type: SEQUENCE SET; Schema: public; Owner: openupload
--

SELECT pg_catalog.setval('plugin_options_id_seq', 1, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: openupload
--

SELECT pg_catalog.setval('users_id_seq', 1, true);


--
-- Data for Name: acl; Type: TABLE DATA; Schema: public; Owner: openupload
--

INSERT INTO acl VALUES (1, '*', '*', 'admins', 'allow');
INSERT INTO acl VALUES (2, 'admin', '*', 'admins', 'allow');
INSERT INTO acl VALUES (3, 'admin', '*', '*', 'deny');
INSERT INTO acl VALUES (4, 'auth', 'login', 'unregistered', 'allow');


--
-- Data for Name: banned; Type: TABLE DATA; Schema: public; Owner: openupload
--

INSERT INTO banned VALUES (1, '127.0.0.1', 'allow', 1);
INSERT INTO banned VALUES (2, '0.0.0.0/0', 'allow', 9999999);

--
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: openupload
--

INSERT INTO groups VALUES ('admins', 'Administrators group');
INSERT INTO groups VALUES ('unregistered', 'Unregistered users');
INSERT INTO groups VALUES ('registered', 'Registered Users');


--
-- Data for Name: langs; Type: TABLE DATA; Schema: public; Owner: openupload
--

INSERT INTO langs VALUES ('en', 'English', 'en_EN', '[en];[en-EN]', 'utf-8', 1);
INSERT INTO langs VALUES ('it', 'Italiano', 'it_IT.utf8', '[it];[it-IT]', 'utf-8', 1);
INSERT INTO langs VALUES ('fr', 'Français', 'fr_FR.utf8', '[fr];[fr-FR]', 'utf-8', 1);
INSERT INTO langs VALUES ('de', 'Deutsch', 'de_DE.utf8', '[de];[de-DE]', 'utf-8', 1);
INSERT INTO langs VALUES ('pt_BR', 'Português', 'pt_BR.utf8', '[pt];[pt-BR]', 'utf-8', 1);
INSERT INTO langs VALUES ('zh_CN', '中文', 'zh_CN.utf8', '[zh];[zh-CN]', 'utf-8', 1);


--
-- Data for Name: plugin_acl; Type: TABLE DATA; Schema: public; Owner: openupload
--

INSERT INTO plugin_acl VALUES (1, 'admins', 'password', 'enable');
INSERT INTO plugin_acl VALUES (2, 'admins', 'captcha', 'enable');
INSERT INTO plugin_acl VALUES (3, 'admins', 'email', 'enable');

--
-- Data for Name: plugin_options; Type: TABLE DATA; Schema: public; Owner: openupload
--

INSERT INTO plugin_options VALUES (1, 'mimetypes', 'unregistered', 'message', 'Pdf, JPEG');
INSERT INTO plugin_options VALUES (2, 'mimetypes', 'unregistered', 'allowed', 'application/pdf
image/jpeg
');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: openupload
--

INSERT INTO users VALUES (1, 'admin', '$1$jYkADrMf$pIf7UKkS3prHZPlvJ9vX61', 'Administrator', 'admins', 'openupload@yourdomain.com', 'en', now(), '', 1);


--
-- PostgreSQL database dump complete
--

