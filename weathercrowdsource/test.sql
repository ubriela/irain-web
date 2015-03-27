--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.5
-- Dumped by pg_dump version 9.3.1
-- Started on 2014-11-17 10:09:58

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 8 (class 2615 OID 17896)
-- Name: tiger; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA tiger;


ALTER SCHEMA tiger OWNER TO postgres;

--
-- TOC entry 253 (class 3079 OID 17885)
-- Name: fuzzystrmatch; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS fuzzystrmatch WITH SCHEMA public;


--
-- TOC entry 3714 (class 0 OID 0)
-- Dependencies: 253
-- Name: EXTENSION fuzzystrmatch; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION fuzzystrmatch IS 'determine similarities and distance between strings';


--
-- TOC entry 256 (class 3079 OID 16394)
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- TOC entry 3715 (class 0 OID 0)
-- Dependencies: 256
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


--
-- TOC entry 258 (class 3079 OID 17897)
-- Name: postgis_tiger_geocoder; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis_tiger_geocoder WITH SCHEMA tiger;


--
-- TOC entry 3716 (class 0 OID 0)
-- Dependencies: 258
-- Name: EXTENSION postgis_tiger_geocoder; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_tiger_geocoder IS 'PostGIS tiger geocoder and reverse geocoder';


--
-- TOC entry 7 (class 2615 OID 17727)
-- Name: topology; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA topology;


ALTER SCHEMA topology OWNER TO postgres;

--
-- TOC entry 252 (class 3079 OID 11750)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 3717 (class 0 OID 0)
-- Dependencies: 252
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- TOC entry 254 (class 3079 OID 17878)
-- Name: address_standardizer; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS address_standardizer WITH SCHEMA public;


--
-- TOC entry 255 (class 3079 OID 17680)
-- Name: pgrouting; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS pgrouting WITH SCHEMA public;


--
-- TOC entry 3718 (class 0 OID 0)
-- Dependencies: 255
-- Name: EXTENSION pgrouting; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgrouting IS 'pgRouting Extension';


--
-- TOC entry 257 (class 3079 OID 17728)
-- Name: postgis_topology; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis_topology WITH SCHEMA topology;


--
-- TOC entry 3719 (class 0 OID 0)
-- Dependencies: 257
-- Name: EXTENSION postgis_topology; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_topology IS 'PostGIS topology spatial types and functions';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 245 (class 1259 OID 24576)
-- Name: ci_sessions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ci_sessions (
    session_id character varying(40),
    ip_address character varying(45),
    user_agent character varying(120),
    last_activity integer,
    user_data character varying(21845)
);


ALTER TABLE public.ci_sessions OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 24582)
-- Name: location_report; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE location_report (
    date_server timestamp without time zone,
    location geometry,
    userid bytea NOT NULL,
    isassigned smallint DEFAULT (0)::smallint NOT NULL,
    isactive smallint DEFAULT (1)::smallint NOT NULL,
    city character varying,
    state character varying,
    country character varying
);


ALTER TABLE public.location_report OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 24590)
-- Name: responses; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE responses (
    taskid integer,
    worker_place character varying(21845),
    response_code smallint,
    level smallint,
    response_date timestamp without time zone,
    response_date_server timestamp without time zone,
    worker_location geometry,
    workerid bytea
);


ALTER TABLE public.responses OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 24596)
-- Name: task_worker_matches; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE task_worker_matches (
    taskid integer,
    assigned_date timestamp without time zone,
    completed_date timestamp without time zone,
    userid bytea,
    iscompleted smallint DEFAULT (0)::smallint NOT NULL
);


ALTER TABLE public.task_worker_matches OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 24611)
-- Name: tasks; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tasks (
    place character varying(21845),
    request_date timestamp without time zone,
    startdate timestamp without time zone,
    enddate timestamp without time zone,
    type smallint,
    status smallint,
    radius integer,
    location geometry,
    requesterid bytea NOT NULL,
    iscompleted smallint DEFAULT (0)::smallint NOT NULL,
    taskid integer NOT NULL
);


ALTER TABLE public.tasks OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 32768)
-- Name: tasks_taskid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tasks_taskid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tasks_taskid_seq OWNER TO postgres;

--
-- TOC entry 3720 (class 0 OID 0)
-- Dependencies: 251
-- Name: tasks_taskid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tasks_taskid_seq OWNED BY tasks.taskid;


--
-- TOC entry 250 (class 1259 OID 24619)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    username character varying(128),
    password character varying(128),
    firstname character varying(45),
    lastname character varying(45),
    avatar character varying(45),
    created_date timestamp without time zone,
    last_login_date timestamp without time zone,
    last_activity_date timestamp without time zone,
    last_password_change_date timestamp without time zone,
    islogout character(1),
    last_logout_date timestamp without time zone,
    salt character varying(128),
    reset_password character varying(128),
    password_question character varying(256),
    password_answer character varying(128),
    channelid character varying(20),
    userid bytea NOT NULL,
    isactive smallint DEFAULT (1)::smallint NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 3584 (class 2604 OID 32770)
-- Name: taskid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks ALTER COLUMN taskid SET DEFAULT nextval('tasks_taskid_seq'::regclass);


--
-- TOC entry 3700 (class 0 OID 24576)
-- Dependencies: 245
-- Data for Name: ci_sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ci_sessions (session_id, ip_address, user_agent, last_activity, user_data) FROM stdin;
ffc31d1f3ad8570e005de941ade44c28	::1	Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.104 Safari/537.36	1416193034	
\.


--
-- TOC entry 3701 (class 0 OID 24582)
-- Dependencies: 246
-- Data for Name: location_report; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY location_report (date_server, location, userid, isassigned, isactive, city, state, country) FROM stdin;
\.


--
-- TOC entry 3702 (class 0 OID 24590)
-- Dependencies: 247
-- Data for Name: responses; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY responses (taskid, worker_place, response_code, level, response_date, response_date_server, worker_location, workerid) FROM stdin;
\.


--
-- TOC entry 3508 (class 0 OID 16662)
-- Dependencies: 174
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY spatial_ref_sys (srid, auth_name, auth_srid, srtext, proj4text) FROM stdin;
\.


--
-- TOC entry 3703 (class 0 OID 24596)
-- Dependencies: 248
-- Data for Name: task_worker_matches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY task_worker_matches (taskid, assigned_date, completed_date, userid, iscompleted) FROM stdin;
\.


--
-- TOC entry 3704 (class 0 OID 24611)
-- Dependencies: 249
-- Data for Name: tasks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tasks (place, request_date, startdate, enddate, type, status, radius, location, requesterid, iscompleted, taskid) FROM stdin;
\.


--
-- TOC entry 3721 (class 0 OID 0)
-- Dependencies: 251
-- Name: tasks_taskid_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tasks_taskid_seq', 32, true);


--
-- TOC entry 3705 (class 0 OID 24619)
-- Dependencies: 250
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY users (username, password, firstname, lastname, avatar, created_date, last_login_date, last_activity_date, last_password_change_date, islogout, last_logout_date, salt, reset_password, password_question, password_answer, channelid, userid, isactive) FROM stdin;
\.


SET search_path = tiger, pg_catalog;

--
-- TOC entry 3511 (class 0 OID 17903)
-- Dependencies: 197
-- Data for Name: geocode_settings; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY geocode_settings (name, setting, unit, category, short_desc) FROM stdin;
debug_geocode_address	false	boolean	debug	outputs debug information in notice log such as queries when geocode_addresss is called if true
debug_geocode_intersection	false	boolean	debug	outputs debug information in notice log such as queries when geocode_intersection is called if true
debug_normalize_address	false	boolean	debug	outputs debug information in notice log such as queries and intermediate expressions when normalize_address is called if true
debug_reverse_geocode	false	boolean	debug	if true, outputs debug information in notice log such as queries and intermediate expressions when reverse_geocode
reverse_geocode_numbered_roads	0	integer	rating	For state and county highways, 0 - no preference in name, 1 - prefer the numbered highway name, 2 - prefer local state/county name
use_pagc_address_parser	false	boolean	normalize	If set to true, will try to use the pagc_address normalizer instead of tiger built one
\.


--
-- TOC entry 3512 (class 0 OID 18250)
-- Dependencies: 240
-- Data for Name: pagc_gaz; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY pagc_gaz (id, seq, word, stdword, token, is_custom) FROM stdin;
\.


--
-- TOC entry 3513 (class 0 OID 18262)
-- Dependencies: 242
-- Data for Name: pagc_lex; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY pagc_lex (id, seq, word, stdword, token, is_custom) FROM stdin;
\.


--
-- TOC entry 3514 (class 0 OID 18274)
-- Dependencies: 244
-- Data for Name: pagc_rules; Type: TABLE DATA; Schema: tiger; Owner: postgres
--

COPY pagc_rules (id, rule, is_custom) FROM stdin;
\.


SET search_path = topology, pg_catalog;

--
-- TOC entry 3510 (class 0 OID 17744)
-- Dependencies: 191
-- Data for Name: layer; Type: TABLE DATA; Schema: topology; Owner: postgres
--

COPY layer (topology_id, layer_id, schema_name, table_name, feature_column, feature_type, level, child_id) FROM stdin;
\.


--
-- TOC entry 3509 (class 0 OID 17731)
-- Dependencies: 190
-- Data for Name: topology; Type: TABLE DATA; Schema: topology; Owner: postgres
--

COPY topology (id, name, srid, "precision", hasz) FROM stdin;
\.


--
-- TOC entry 3713 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2014-11-17 10:09:59

--
-- PostgreSQL database dump complete
--

