--
-- PostgreSQL database dump
--

-- Dumped from database version 18.2
-- Dumped by pg_dump version 18.2

-- Started on 2026-03-09 22:00:56

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 222 (class 1259 OID 17337)
-- Name: chore; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.chore (
    id uuid NOT NULL,
    person_id uuid NOT NULL,
    name character varying(64) NOT NULL,
    done boolean DEFAULT false NOT NULL
);


ALTER TABLE public.chore OWNER TO postgres;

--
-- TOC entry 5030 (class 0 OID 0)
-- Dependencies: 222
-- Name: TABLE chore; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE public.chore IS 'Records of the chores that people need to do';


--
-- TOC entry 220 (class 1259 OID 16391)
-- Name: migration; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migration (
    id integer NOT NULL,
    name character varying(180) NOT NULL,
    apply_time integer NOT NULL
);


ALTER TABLE public.migration OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16390)
-- Name: migration_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migration_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migration_id_seq OWNER TO postgres;

--
-- TOC entry 5031 (class 0 OID 0)
-- Dependencies: 219
-- Name: migration_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migration_id_seq OWNED BY public.migration.id;


--
-- TOC entry 221 (class 1259 OID 17330)
-- Name: person; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.person (
    id uuid NOT NULL,
    name character varying(64) NOT NULL
);


ALTER TABLE public.person OWNER TO postgres;

--
-- TOC entry 5032 (class 0 OID 0)
-- Dependencies: 221
-- Name: TABLE person; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE public.person IS 'Records for people who have chores to do';


--
-- TOC entry 4864 (class 2604 OID 16394)
-- Name: migration id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migration ALTER COLUMN id SET DEFAULT nextval('public.migration_id_seq'::regclass);


--
-- TOC entry 5024 (class 0 OID 17337)
-- Dependencies: 222
-- Data for Name: chore; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.chore (id, person_id, name, done) FROM stdin;
019cd5cd-d2d8-72a9-b4c3-41fef1019587	019cd5cd-8ba6-723d-8525-01672c6a37b6	Do the laundry	f
019cd5ce-0b7c-7373-8514-256dad0fe4da	019cd5cd-8ba6-723d-8525-01672c6a37b6	Wash dishes	f
019cd5ce-3d83-712c-9447-51209658dd41	019cd5cd-92ae-739c-82c9-ef18b268f774	Clean bathroom	t
\.


--
-- TOC entry 5022 (class 0 OID 16391)
-- Dependencies: 220
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migration (id, name, apply_time) FROM stdin;
55	App\\Migration\\M260305062211CreatePersonTable	1772691755
56	App\\Migration\\M260305062213CreateChoreTable	1772691755
\.


--
-- TOC entry 5023 (class 0 OID 17330)
-- Dependencies: 221
-- Data for Name: person; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.person (id, name) FROM stdin;
019cd5cd-8ba6-723d-8525-01672c6a37b6	John Doe
019cd5cd-92ae-739c-82c9-ef18b268f774	Jane Doe
\.


--
-- TOC entry 5033 (class 0 OID 0)
-- Dependencies: 219
-- Name: migration_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migration_id_seq', 56, true);


--
-- TOC entry 4871 (class 2606 OID 17346)
-- Name: chore chore_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.chore
    ADD CONSTRAINT chore_pkey PRIMARY KEY (id);


--
-- TOC entry 4867 (class 2606 OID 16399)
-- Name: migration migration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (id);


--
-- TOC entry 4869 (class 2606 OID 17336)
-- Name: person person_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.person
    ADD CONSTRAINT person_pkey PRIMARY KEY (id);


--
-- TOC entry 4872 (class 1259 OID 17347)
-- Name: idx-chore-person_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "idx-chore-person_id" ON public.chore USING btree (person_id);


--
-- TOC entry 4873 (class 2606 OID 17348)
-- Name: chore fk-chore-person_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.chore
    ADD CONSTRAINT "fk-chore-person_id" FOREIGN KEY (person_id) REFERENCES public.person(id) ON DELETE CASCADE;


-- Completed on 2026-03-09 22:00:56

--
-- PostgreSQL database dump complete
--

