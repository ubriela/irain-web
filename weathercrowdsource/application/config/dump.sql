--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: tiger; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA tiger;


ALTER SCHEMA tiger OWNER TO postgres;

--
-- Name: fuzzystrmatch; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS fuzzystrmatch WITH SCHEMA public;


--
-- Name: EXTENSION fuzzystrmatch; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION fuzzystrmatch IS 'determine similarities and distance between strings';


--
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


--
-- Name: postgis_tiger_geocoder; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis_tiger_geocoder WITH SCHEMA tiger;


--
-- Name: EXTENSION postgis_tiger_geocoder; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_tiger_geocoder IS 'PostGIS tiger geocoder and reverse geocoder';


--
-- Name: topology; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA topology;


ALTER SCHEMA topology OWNER TO postgres;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: adminpack; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS adminpack WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION adminpack; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION adminpack IS 'administrative functions for PostgreSQL';


--
-- Name: postgis_topology; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS postgis_topology WITH SCHEMA topology;


--
-- Name: EXTENSION postgis_topology; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_topology IS 'PostGIS topology spatial types and functions';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
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
-- Name: location_report; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE location_report (
    date timestamp without time zone,
    date_server timestamp without time zone,
    location geometry,
    userid bytea NOT NULL,
    isassigned smallint DEFAULT 0::smallint NOT NULL,
    isactive smallint DEFAULT 1::smallint NOT NULL,
    place text
);


ALTER TABLE public.location_report OWNER TO postgres;

--
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
-- Name: task_worker_matches; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE task_worker_matches (
    taskid integer,
    assigned_date timestamp without time zone,
    completed_date timestamp without time zone,
    userid bytea,
    iscompleted smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.task_worker_matches OWNER TO postgres;

--
-- Name: weather_report; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE weather_report (
    response_code smallint,
    level smallint,
    response_date timestamp without time zone,
    location geometry,
    userid bytea NOT NULL,
    id integer NOT NULL
);


ALTER TABLE public.weather_report OWNER TO postgres;

--
-- Name: weather_report_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE weather_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.weather_report_id_seq OWNER TO postgres;

--
-- Name: weather_report_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE weather_report_id_seq OWNED BY weather_report.id;


--
-- Name: tasks; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tasks (
    taskid integer DEFAULT nextval('weather_report_id_seq'::regclass),
    title character varying(100),
    place character varying(21845),
    request_date timestamp without time zone,
    startdate timestamp without time zone,
    enddate timestamp without time zone,
    type smallint,
    status smallint,
    radius integer,
    location geometry,
    requesterid bytea NOT NULL,
    iscompleted smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.tasks OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    username character varying(128),
    password character varying(128),
    email character varying(128),
    firstname character varying(45),
    lastname character varying(45),
    phone_number character varying(45),
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
    isactive smallint DEFAULT 1::smallint NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY weather_report ALTER COLUMN id SET DEFAULT nextval('weather_report_id_seq'::regclass);


--
-- Name: weather_report_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY weather_report
    ADD CONSTRAINT weather_report_pkey PRIMARY KEY (id);


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

