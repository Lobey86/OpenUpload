--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: acl; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE acl (
    id integer NOT NULL,
    module character varying(100) NOT NULL,
    action character varying(100) NOT NULL,
    group_name character varying(100) NOT NULL,
    access character varying(100) NOT NULL
);


--
-- Name: banned; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE banned (
    id integer NOT NULL,
    ip character varying(50),
    access character varying(50),
    priority integer
);



--
-- Name: file_options; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE file_options (
    id integer NOT NULL,
    file_id character varying(100) NOT NULL,
    module character varying(50) NOT NULL,
    name character varying(50) NOT NULL,
    value character varying(200) NOT NULL
);



--
-- Name: files; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE files (
    id character varying(100) NOT NULL,
    name character varying(200) NOT NULL,
    mime character varying(200) NOT NULL,
    description text NOT NULL,
    size integer NOT NULL,
    remove character varying(100) NOT NULL,
    user_login character varying(100) NOT NULL,
    ip character varying(40) NOT NULL,
    upload_date timestamp without time zone NOT NULL
);



--
-- Name: groups; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE groups (
    name character varying(50) NOT NULL,
    description character varying(250)
);



--
-- Name: langs; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE langs (
    id character varying(10) NOT NULL,
    name character varying(50) NOT NULL,
    locale character varying(10) NOT NULL,
    browser character varying(200),
    charset character varying(50) NOT NULL,
    active integer DEFAULT 1 NOT NULL
);



--
-- Name: plugin_acl; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE plugin_acl (
    id integer NOT NULL,
    group_name character varying(50) NOT NULL,
    plugin character varying(100) NOT NULL,
    access character varying(10) NOT NULL
);



--
-- Name: plugin_options; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE plugin_options (
    id integer NOT NULL,
    plugin character varying(100) NOT NULL,
    group_name character varying(100) NOT NULL,
    name character varying(100) NOT NULL,
    value text
);



--
-- Name: users; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    login character varying(100) NOT NULL,
    password character varying(100) NOT NULL,
    name character varying(200) NOT NULL,
    group_name character varying(50) NOT NULL,
    email character varying(250) NOT NULL,
    lang character varying(10) NOT NULL,
    reg_date timestamp without time zone NOT NULL,
    regid character varying(50) NOT NULL,
    active integer NOT NULL
);


--
-- Name: activitylog; Type: TABLE; Schema: public; Owner: openupload; Tablespace: 
--

CREATE TABLE activitylog (
    id integer NOT NULL,
    level character varying(20) NOT NULL,
    log_time timestamp without time zone NOT NULL,
    ip character varying(20) NOT NULL,
    user_login character varying(100) NOT NULL,
    module character varying(50) NOT NULL,
    action character varying(50) NOT NULL,
    realaction character varying(50),
    plugin character varying(50),
    result character varying(100),
    moreinfo text
);


--
-- Name: acl_id_seq; Type: SEQUENCE; Schema: public; Owner: openupload
--

CREATE SEQUENCE acl_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



--
-- Name: acl_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: openupload
--

ALTER SEQUENCE acl_id_seq OWNED BY acl.id;


--
-- Name: banned_id_seq; Type: SEQUENCE; Schema: public; Owner: openupload
--

CREATE SEQUENCE banned_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



--
-- Name: banned_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: openupload
--

ALTER SEQUENCE banned_id_seq OWNED BY banned.id;


--
-- Name: file_options_id_seq; Type: SEQUENCE; Schema: public; Owner: openupload
--

CREATE SEQUENCE file_options_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



--
-- Name: file_options_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: openupload
--

ALTER SEQUENCE file_options_id_seq OWNED BY file_options.id;


--
-- Name: plugin_acl_id_seq; Type: SEQUENCE; Schema: public; Owner: openupload
--

CREATE SEQUENCE plugin_acl_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



--
-- Name: plugin_acl_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: openupload
--

ALTER SEQUENCE plugin_acl_id_seq OWNED BY plugin_acl.id;


--
-- Name: plugin_options_id_seq; Type: SEQUENCE; Schema: public; Owner: openupload
--

CREATE SEQUENCE plugin_options_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;



--
-- Name: plugin_options_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: openupload
--

ALTER SEQUENCE plugin_options_id_seq OWNED BY plugin_options.id;


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: openupload
--

CREATE SEQUENCE users_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: openupload
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;

--
-- Name: activitylog_id_seq; Type: SEQUENCE; Schema: public; Owner: openupload
--

CREATE SEQUENCE activitylog_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

--
-- Name: activitylog_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: openupload
--

ALTER SEQUENCE activitylog_id_seq OWNED BY activitylog.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: openupload
--

ALTER TABLE acl ALTER COLUMN id SET DEFAULT nextval('acl_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: openupload
--

ALTER TABLE banned ALTER COLUMN id SET DEFAULT nextval('banned_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: openupload
--

ALTER TABLE file_options ALTER COLUMN id SET DEFAULT nextval('file_options_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: openupload
--

ALTER TABLE plugin_acl ALTER COLUMN id SET DEFAULT nextval('plugin_acl_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: openupload
--

ALTER TABLE plugin_options ALTER COLUMN id SET DEFAULT nextval('plugin_options_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: openupload
--

ALTER TABLE users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: openupload
--

ALTER TABLE activitylog ALTER COLUMN id SET DEFAULT nextval('activitylog_id_seq'::regclass);


--
-- Name: acl_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY acl
    ADD CONSTRAINT acl_pkey PRIMARY KEY (id);


--
-- Name: banned_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY banned
    ADD CONSTRAINT banned_pkey PRIMARY KEY (id);


--
-- Name: file_options_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY file_options
    ADD CONSTRAINT file_options_pkey PRIMARY KEY (id);


--
-- Name: files_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY files
    ADD CONSTRAINT files_pkey PRIMARY KEY (id);


--
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (name);


--
-- Name: langs_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY langs
    ADD CONSTRAINT langs_pkey PRIMARY KEY (id);


--
-- Name: plugin_acl_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY plugin_acl
    ADD CONSTRAINT plugin_acl_pkey PRIMARY KEY (id);


--
-- Name: plugin_options_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY plugin_options
    ADD CONSTRAINT plugin_options_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);

--
-- Name: activitylog_pkey; Type: CONSTRAINT; Schema: public; Owner: openupload; Tablespace: 
--

ALTER TABLE ONLY activitylog
    ADD CONSTRAINT activitylog_pkey PRIMARY KEY (id);


--
-- Name: file_id_idx; Type: INDEX; Schema: public; Owner: openupload; Tablespace: 
--

CREATE INDEX file_id_idx ON file_options USING btree (file_id);


--
-- Name: plugin_acl_group_plugin_key; Type: INDEX; Schema: public; Owner: openupload; Tablespace: 
--

CREATE UNIQUE INDEX plugin_acl_group_plugin_key ON plugin_acl USING btree (group_name, plugin);


--
-- Name: users_login_key; Type: INDEX; Schema: public; Owner: openupload; Tablespace: 
--

CREATE UNIQUE INDEX users_login_key ON users USING btree (login);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--
