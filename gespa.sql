--
-- PostgreSQL database dump
--

\restrict eH0NKH2YkP43p71yogBnutii5SOAIWkGiEe3zUQhec26oAt8OUdsOfFRXOQcWfu

-- Dumped from database version 14.23 (Ubuntu 14.23-0ubuntu0.22.04.1)
-- Dumped by pg_dump version 14.23 (Ubuntu 14.23-0ubuntu0.22.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: categorie_type; Type: TYPE; Schema: public; Owner: adeyemi
--

CREATE TYPE public.categorie_type AS ENUM (
    'recette',
    'dépense'
);


ALTER TYPE public.categorie_type OWNER TO adeyemi;

--
-- Name: td_categorie; Type: TYPE; Schema: public; Owner: adeyemi
--

CREATE TYPE public.td_categorie AS ENUM (
    'intermediaire',
    '3eme',
    'terminale'
);


ALTER TYPE public.td_categorie OWNER TO adeyemi;

--
-- Name: tests_type_enum; Type: TYPE; Schema: public; Owner: adeyemi
--

CREATE TYPE public.tests_type_enum AS ENUM (
    'interrogation1',
    'interrogation2',
    'interrogation3',
    'devoir1',
    'devoir2',
    'examen'
);


ALTER TYPE public.tests_type_enum OWNER TO adeyemi;

--
-- Name: type_conduite; Type: TYPE; Schema: public; Owner: adeyemi
--

CREATE TYPE public.type_conduite AS ENUM (
    'discipline',
    'assiduité',
    'respect',
    'travail'
);


ALTER TYPE public.type_conduite OWNER TO adeyemi;

--
-- Name: type_frais_enum; Type: TYPE; Schema: public; Owner: adeyemi
--

CREATE TYPE public.type_frais_enum AS ENUM (
    'seance',
    'mois',
    'annee'
);


ALTER TYPE public.type_frais_enum OWNER TO adeyemi;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: annee_classe; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.annee_classe (
    id bigint NOT NULL,
    classe_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    active boolean DEFAULT true
);


ALTER TABLE public.annee_classe OWNER TO adeyemi;

--
-- Name: annee_classe_frais; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.annee_classe_frais (
    id bigint NOT NULL,
    annee_id bigint NOT NULL,
    classe_id bigint NOT NULL,
    frais_id bigint NOT NULL,
    montant numeric(10,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.annee_classe_frais OWNER TO adeyemi;

--
-- Name: annee_classe_frais_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.annee_classe_frais_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.annee_classe_frais_id_seq OWNER TO adeyemi;

--
-- Name: annee_classe_frais_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.annee_classe_frais_id_seq OWNED BY public.annee_classe_frais.id;


--
-- Name: annee_trimestre; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.annee_trimestre (
    id bigint NOT NULL,
    annee_id bigint NOT NULL,
    trimestre_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    active boolean DEFAULT true NOT NULL
);


ALTER TABLE public.annee_trimestre OWNER TO adeyemi;

--
-- Name: annee_trimestre_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.annee_trimestre_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.annee_trimestre_id_seq OWNER TO adeyemi;

--
-- Name: annee_trimestre_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.annee_trimestre_id_seq OWNED BY public.annee_trimestre.id;


--
-- Name: annees; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.annees (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    debut date,
    fin date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    en_cours boolean NOT NULL
);


ALTER TABLE public.annees OWNER TO adeyemi;

--
-- Name: annees_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.annees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.annees_id_seq OWNER TO adeyemi;

--
-- Name: annees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.annees_id_seq OWNED BY public.annees.id;


--
-- Name: articles; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.articles (
    id bigint NOT NULL,
    type_id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    reference character varying(255) NOT NULL,
    prix_achat numeric(10,2),
    prix_vente numeric(10,2),
    stock_min integer DEFAULT 0 NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.articles OWNER TO adeyemi;

--
-- Name: articles_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.articles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.articles_id_seq OWNER TO adeyemi;

--
-- Name: articles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.articles_id_seq OWNED BY public.articles.id;


--
-- Name: benefices; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.benefices (
    id bigint NOT NULL,
    date_debut date NOT NULL,
    date_fin date NOT NULL,
    montant numeric(15,2) NOT NULL,
    observation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.benefices OWNER TO adeyemi;

--
-- Name: benefices_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.benefices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.benefices_id_seq OWNER TO adeyemi;

--
-- Name: benefices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.benefices_id_seq OWNED BY public.benefices.id;


--
-- Name: budgets; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.budgets (
    id bigint NOT NULL,
    annee_id bigint,
    categorie_id bigint NOT NULL,
    montant_prevu numeric(20,2) NOT NULL,
    periode character varying(255) NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    nom character varying
);


ALTER TABLE public.budgets OWNER TO adeyemi;

--
-- Name: budgets_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.budgets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.budgets_id_seq OWNER TO adeyemi;

--
-- Name: budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.budgets_id_seq OWNED BY public.budgets.id;


--
-- Name: bulletins; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.bulletins (
    id bigint NOT NULL,
    eleve_id bigint NOT NULL,
    classe_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    trimestre_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    moyenne_scientifique double precision,
    moyenne_litteraire double precision,
    moyenne_trimestrielle double precision,
    moyenne_annuelle double precision,
    rang_trimestre double precision,
    rang_annuel double precision,
    inscription_id bigint
);


ALTER TABLE public.bulletins OWNER TO adeyemi;

--
-- Name: bulletins_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.bulletins_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.bulletins_id_seq OWNER TO adeyemi;

--
-- Name: bulletins_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.bulletins_id_seq OWNED BY public.bulletins.id;


--
-- Name: categories; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    type public.categorie_type NOT NULL,
    description text,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.categories OWNER TO adeyemi;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categories_id_seq OWNER TO adeyemi;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: classe_annee_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.classe_annee_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.classe_annee_id_seq OWNER TO adeyemi;

--
-- Name: classe_annee_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.classe_annee_id_seq OWNED BY public.annee_classe.id;


--
-- Name: classe_enseignant; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.classe_enseignant (
    id bigint NOT NULL,
    enseignant_id bigint NOT NULL,
    classe_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.classe_enseignant OWNER TO adeyemi;

--
-- Name: classe_enseignant_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.classe_enseignant_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.classe_enseignant_id_seq OWNER TO adeyemi;

--
-- Name: classe_enseignant_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.classe_enseignant_id_seq OWNED BY public.classe_enseignant.id;


--
-- Name: classe_matiere; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.classe_matiere (
    id bigint NOT NULL,
    classe_id bigint NOT NULL,
    matiere_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    active boolean DEFAULT true
);


ALTER TABLE public.classe_matiere OWNER TO adeyemi;

--
-- Name: classe_transitions; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.classe_transitions (
    id bigint NOT NULL,
    classe_id bigint NOT NULL,
    classe_superieure_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.classe_transitions OWNER TO adeyemi;

--
-- Name: classe_transitions_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.classe_transitions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.classe_transitions_id_seq OWNER TO adeyemi;

--
-- Name: classe_transitions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.classe_transitions_id_seq OWNED BY public.classe_transitions.id;


--
-- Name: classes; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.classes (
    id bigint NOT NULL,
    nom character varying(100) NOT NULL,
    niveau character varying(50) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cycle_id bigint,
    ordre integer DEFAULT 99 NOT NULL,
    rang integer
);


ALTER TABLE public.classes OWNER TO adeyemi;

--
-- Name: classes_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.classes_id_seq OWNER TO adeyemi;

--
-- Name: classes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.classes_id_seq OWNED BY public.classes.id;


--
-- Name: comptes; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.comptes (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    solde_initial numeric(20,2) DEFAULT 0 NOT NULL,
    solde_actuel numeric(20,2) DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.comptes OWNER TO adeyemi;

--
-- Name: comptes_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.comptes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comptes_id_seq OWNER TO adeyemi;

--
-- Name: comptes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.comptes_id_seq OWNED BY public.comptes.id;


--
-- Name: conduites; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.conduites (
    id bigint NOT NULL,
    annee_id bigint NOT NULL,
    classe_id bigint NOT NULL,
    trimestre_id bigint NOT NULL,
    note_conduite numeric(5,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    inscription_id bigint,
    matricule character varying(255),
    type public.type_conduite,
    niveau character varying(20) DEFAULT 'Bon'::character varying NOT NULL,
    CONSTRAINT chk_niveau_conduite CHECK (((niveau)::text = ANY (ARRAY[('Excellent'::character varying)::text, ('Bon'::character varying)::text, ('Moyen'::character varying)::text, ('Mauvais'::character varying)::text])))
);


ALTER TABLE public.conduites OWNER TO adeyemi;

--
-- Name: conduites_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.conduites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.conduites_id_seq OWNER TO adeyemi;

--
-- Name: conduites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.conduites_id_seq OWNED BY public.conduites.id;


--
-- Name: contacts; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.contacts (
    id bigint NOT NULL,
    message text NOT NULL,
    nom character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    objet text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.contacts OWNER TO adeyemi;

--
-- Name: contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contacts_id_seq OWNER TO adeyemi;

--
-- Name: contacts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.contacts_id_seq OWNED BY public.contacts.id;


--
-- Name: cycles; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.cycles (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    ordre integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.cycles OWNER TO adeyemi;

--
-- Name: cycles_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.cycles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cycles_id_seq OWNER TO adeyemi;

--
-- Name: cycles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.cycles_id_seq OWNED BY public.cycles.id;


--
-- Name: depenses; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.depenses (
    id bigint NOT NULL,
    date date NOT NULL,
    libelle character varying(255) NOT NULL,
    montant numeric(20,2) NOT NULL,
    categorie character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    description character varying(225)
);


ALTER TABLE public.depenses OWNER TO adeyemi;

--
-- Name: depenses_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.depenses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.depenses_id_seq OWNER TO adeyemi;

--
-- Name: depenses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.depenses_id_seq OWNED BY public.depenses.id;


--
-- Name: echeances; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.echeances (
    id bigint NOT NULL,
    frais_id bigint NOT NULL,
    classe_id bigint,
    annee_id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    date_limite date NOT NULL,
    montant numeric(10,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.echeances OWNER TO adeyemi;

--
-- Name: echeances_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.echeances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.echeances_id_seq OWNER TO adeyemi;

--
-- Name: echeances_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.echeances_id_seq OWNED BY public.echeances.id;


--
-- Name: eleves; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.eleves (
    id bigint NOT NULL,
    nom character varying(100) NOT NULL,
    prenom character varying(100) NOT NULL,
    date_naissance date,
    sexe character varying(255) NOT NULL,
    nationalite character varying(255) NOT NULL,
    lieu_naissance character varying(255) NOT NULL,
    matricule character varying(255) NOT NULL,
    classe_id bigint NOT NULL,
    statut character varying(255) NOT NULL,
    annee_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    paren_id bigint,
    numero_ordre integer,
    numeducmaster character varying(255) DEFAULT NULL::character varying,
    photo character varying(255),
    CONSTRAINT eleves_sexe_check CHECK (((sexe)::text = ANY (ARRAY[('M'::character varying)::text, ('F'::character varying)::text]))),
    CONSTRAINT eleves_statut_check CHECK (((statut)::text = ANY (ARRAY[('passant'::character varying)::text, ('redoublant'::character varying)::text])))
);


ALTER TABLE public.eleves OWNER TO adeyemi;

--
-- Name: eleves_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.eleves_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.eleves_id_seq OWNER TO adeyemi;

--
-- Name: eleves_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.eleves_id_seq OWNED BY public.eleves.id;


--
-- Name: enseignants; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.enseignants (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    prenom character varying(255) NOT NULL,
    date_naissance date,
    sexe character varying(255) NOT NULL,
    adresse character varying(255),
    telephone character varying(255),
    email character varying(255) NOT NULL,
    matricule character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    specialite character varying(255),
    grade character varying(255),
    date_embauche date,
    statut character varying(255) DEFAULT 'actif'::character varying NOT NULL,
    matiere_id bigint,
    cycle_id bigint,
    CONSTRAINT enseignants_sexe_check CHECK (((sexe)::text = ANY (ARRAY[('M'::character varying)::text, ('F'::character varying)::text])))
);


ALTER TABLE public.enseignants OWNER TO adeyemi;

--
-- Name: enseignants_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.enseignants_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.enseignants_id_seq OWNER TO adeyemi;

--
-- Name: enseignants_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.enseignants_id_seq OWNED BY public.enseignants.id;


--
-- Name: epreuves; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.epreuves (
    id bigint NOT NULL,
    examen_blanc_id bigint NOT NULL,
    matiere_id bigint NOT NULL,
    date date NOT NULL,
    heure_debut time(0) without time zone NOT NULL,
    heure_fin time(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.epreuves OWNER TO adeyemi;

--
-- Name: epreuves_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.epreuves_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.epreuves_id_seq OWNER TO adeyemi;

--
-- Name: epreuves_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.epreuves_id_seq OWNED BY public.epreuves.id;


--
-- Name: examen_blanc_classe; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.examen_blanc_classe (
    id bigint NOT NULL,
    examen_blanc_id bigint NOT NULL,
    classe_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.examen_blanc_classe OWNER TO adeyemi;

--
-- Name: examen_blanc_classe_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.examen_blanc_classe_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.examen_blanc_classe_id_seq OWNER TO adeyemi;

--
-- Name: examen_blanc_classe_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.examen_blanc_classe_id_seq OWNED BY public.examen_blanc_classe.id;


--
-- Name: examen_blancs; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.examen_blancs (
    id bigint NOT NULL,
    type character varying(255) NOT NULL,
    annee_id bigint NOT NULL,
    date_debut date NOT NULL,
    date_fin date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    inscription_id bigint,
    classe_id bigint
);


ALTER TABLE public.examen_blancs OWNER TO adeyemi;

--
-- Name: examen_blancs_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.examen_blancs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.examen_blancs_id_seq OWNER TO adeyemi;

--
-- Name: examen_blancs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.examen_blancs_id_seq OWNED BY public.examen_blancs.id;


--
-- Name: examen_classes; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.examen_classes (
    id bigint NOT NULL,
    examen_blanc_id bigint NOT NULL,
    classe_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.examen_classes OWNER TO adeyemi;

--
-- Name: examen_classes_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.examen_classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.examen_classes_id_seq OWNER TO adeyemi;

--
-- Name: examen_classes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.examen_classes_id_seq OWNED BY public.examen_classes.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO adeyemi;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO adeyemi;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: finances; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.finances (
    id bigint NOT NULL,
    recette_id bigint NOT NULL,
    depense_id bigint NOT NULL,
    solde numeric(20,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.finances OWNER TO adeyemi;

--
-- Name: finances_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.finances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.finances_id_seq OWNER TO adeyemi;

--
-- Name: finances_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.finances_id_seq OWNED BY public.finances.id;


--
-- Name: frais; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.frais (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.frais OWNER TO adeyemi;

--
-- Name: frais_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.frais_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.frais_id_seq OWNER TO adeyemi;

--
-- Name: frais_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.frais_id_seq OWNED BY public.frais.id;


--
-- Name: galeries; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.galeries (
    id bigint NOT NULL,
    titre character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.galeries OWNER TO adeyemi;

--
-- Name: galeries_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.galeries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.galeries_id_seq OWNER TO adeyemi;

--
-- Name: galeries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.galeries_id_seq OWNED BY public.galeries.id;


--
-- Name: importation_notes; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.importation_notes (
    id bigint NOT NULL,
    classe_id bigint NOT NULL,
    trimestre_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    matiere_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    inscription_id bigint,
    moyenne_interro double precision,
    devoir1 double precision,
    devoir2 double precision,
    moyenne_matiere double precision
);


ALTER TABLE public.importation_notes OWNER TO adeyemi;

--
-- Name: importation_notes_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.importation_notes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.importation_notes_id_seq OWNER TO adeyemi;

--
-- Name: importation_notes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.importation_notes_id_seq OWNED BY public.importation_notes.id;


--
-- Name: importations_notes; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.importations_notes (
    id integer NOT NULL,
    classe_id bigint NOT NULL,
    trimestre_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    matiere_id bigint NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.importations_notes OWNER TO adeyemi;

--
-- Name: importations_notes_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.importations_notes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.importations_notes_id_seq OWNER TO adeyemi;

--
-- Name: importations_notes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.importations_notes_id_seq OWNED BY public.importations_notes.id;


--
-- Name: inscription_frais; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.inscription_frais (
    id bigint NOT NULL,
    inscription_id bigint NOT NULL,
    frais_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    montant_frais numeric(15,2) NOT NULL,
    montant_paye numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    reste numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    statut character varying(255) DEFAULT 'non_payé'::character varying NOT NULL,
    est_arriere boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    montant_total numeric(10,2),
    CONSTRAINT inscription_frais_statut_check CHECK (((statut)::text = ANY (ARRAY[('non_payé'::character varying)::text, ('partiellement_payé'::character varying)::text, ('soldé'::character varying)::text])))
);


ALTER TABLE public.inscription_frais OWNER TO adeyemi;

--
-- Name: inscription_frais_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.inscription_frais_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.inscription_frais_id_seq OWNER TO adeyemi;

--
-- Name: inscription_frais_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.inscription_frais_id_seq OWNED BY public.inscription_frais.id;


--
-- Name: inscriptions; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.inscriptions (
    id bigint NOT NULL,
    classe_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    date_inscription date DEFAULT CURRENT_DATE,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    eleve_id bigint,
    moyenne_annuelle numeric(5,2),
    passage_auto boolean DEFAULT false NOT NULL,
    ancienne_classe_id bigint,
    decision character varying(255)
);


ALTER TABLE public.inscriptions OWNER TO adeyemi;

--
-- Name: inscriptions_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.inscriptions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.inscriptions_id_seq OWNER TO adeyemi;

--
-- Name: inscriptions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.inscriptions_id_seq OWNED BY public.inscriptions.id;


--
-- Name: investissements; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.investissements (
    id bigint NOT NULL,
    investisseur_id bigint NOT NULL,
    date_investissement date NOT NULL,
    taux numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    statut character varying(255) DEFAULT 'actif'::character varying NOT NULL,
    observation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    montant numeric(15,2) DEFAULT 0 NOT NULL
);


ALTER TABLE public.investissements OWNER TO adeyemi;

--
-- Name: investissements_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.investissements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.investissements_id_seq OWNER TO adeyemi;

--
-- Name: investissements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.investissements_id_seq OWNED BY public.investissements.id;


--
-- Name: investisseurs; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.investisseurs (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    prenom character varying(255),
    telephone character varying(30),
    email character varying(255),
    adresse text,
    profession character varying(255),
    piece_identite character varying(255),
    numero_piece character varying(255),
    date_naissance date,
    actif boolean DEFAULT true NOT NULL,
    observation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.investisseurs OWNER TO adeyemi;

--
-- Name: investisseurs_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.investisseurs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.investisseurs_id_seq OWNER TO adeyemi;

--
-- Name: investisseurs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.investisseurs_id_seq OWNED BY public.investisseurs.id;


--
-- Name: matiere_classe_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.matiere_classe_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.matiere_classe_id_seq OWNER TO adeyemi;

--
-- Name: matiere_classe_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.matiere_classe_id_seq OWNED BY public.classe_matiere.id;


--
-- Name: matieres; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.matieres (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    coefficient integer DEFAULT 1 NOT NULL,
    type character varying(255) DEFAULT 'scientifique'::character varying NOT NULL,
    enseignant_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    niveau character varying(50)
);


ALTER TABLE public.matieres OWNER TO adeyemi;

--
-- Name: matieres_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.matieres_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.matieres_id_seq OWNER TO adeyemi;

--
-- Name: matieres_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.matieres_id_seq OWNED BY public.matieres.id;


--
-- Name: media; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.media (
    id bigint NOT NULL,
    galerie_id bigint NOT NULL,
    fichier character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    titre character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT media_type_check CHECK (((type)::text = ANY (ARRAY[('image'::character varying)::text, ('video'::character varying)::text])))
);


ALTER TABLE public.media OWNER TO adeyemi;

--
-- Name: media_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.media_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.media_id_seq OWNER TO adeyemi;

--
-- Name: media_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.media_id_seq OWNED BY public.media.id;


--
-- Name: message_parents; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.message_parents (
    id bigint NOT NULL,
    eleve_id bigint NOT NULL,
    paren_id bigint NOT NULL,
    user_id bigint,
    objet character varying(255) NOT NULL,
    message text NOT NULL,
    type character varying(255) DEFAULT 'info'::character varying NOT NULL,
    lu boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT messages_parents_type_check CHECK (((type)::text = ANY (ARRAY[('info'::character varying)::text, ('avertissement'::character varying)::text, ('felicitation'::character varying)::text])))
);


ALTER TABLE public.message_parents OWNER TO adeyemi;

--
-- Name: messages_parents_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.messages_parents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.messages_parents_id_seq OWNER TO adeyemi;

--
-- Name: messages_parents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.messages_parents_id_seq OWNED BY public.message_parents.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO adeyemi;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO adeyemi;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO adeyemi;

--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO adeyemi;

--
-- Name: mouvement_stocks; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.mouvement_stocks (
    id bigint NOT NULL,
    article_id bigint NOT NULL,
    type character varying(255) NOT NULL,
    quantite integer NOT NULL,
    prix_unitaire numeric(10,2),
    date_mouvement date DEFAULT '2026-02-06'::date NOT NULL,
    motif character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT mouvement_stocks_type_check CHECK (((type)::text = ANY (ARRAY[('entree'::character varying)::text, ('sortie'::character varying)::text])))
);


ALTER TABLE public.mouvement_stocks OWNER TO adeyemi;

--
-- Name: mouvement_stocks_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.mouvement_stocks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mouvement_stocks_id_seq OWNER TO adeyemi;

--
-- Name: mouvement_stocks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.mouvement_stocks_id_seq OWNED BY public.mouvement_stocks.id;


--
-- Name: moyennes; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.moyennes (
    id bigint NOT NULL,
    trimestre_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    moyenne_trimestrielle numeric(5,2),
    moyenne_annuelle numeric(5,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    classe_id bigint NOT NULL,
    rang_trimestre character varying(10),
    rang_annuel character varying(10),
    moyenne_scientifique double precision,
    moyenne_litteraire double precision,
    inscription_id bigint,
    notes json,
    note_conduite numeric(5,2),
    appreciation_conduite character varying(255),
    appreciation character varying(255),
    total_eleves integer,
    plus_faible_moyenne numeric(5,2),
    plus_forte_moyenne numeric(5,2),
    moyenne_t1 numeric(5,2),
    moyenne_t2 numeric(5,2),
    moyenne_t3 numeric(5,2),
    decision character varying(100)
);


ALTER TABLE public.moyennes OWNER TO adeyemi;

--
-- Name: moyennes_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.moyennes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.moyennes_id_seq OWNER TO adeyemi;

--
-- Name: moyennes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.moyennes_id_seq OWNED BY public.moyennes.id;


--
-- Name: note_examens; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.note_examens (
    id bigint NOT NULL,
    participant_id bigint NOT NULL,
    note numeric(5,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    matiere_id bigint NOT NULL
);


ALTER TABLE public.note_examens OWNER TO adeyemi;

--
-- Name: note_examens_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.note_examens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.note_examens_id_seq OWNER TO adeyemi;

--
-- Name: note_examens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.note_examens_id_seq OWNED BY public.note_examens.id;


--
-- Name: notes; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.notes (
    id bigint NOT NULL,
    classe_id bigint NOT NULL,
    matiere_id bigint NOT NULL,
    trimestre_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    moyenne_interro double precision,
    devoir1 double precision,
    devoir2 double precision,
    moyenne_matiere double precision,
    appreciation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    inscription_id bigint,
    interrogation1 double precision,
    interrogation2 double precision,
    interrogation3 double precision
);


ALTER TABLE public.notes OWNER TO adeyemi;

--
-- Name: notes_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.notes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.notes_id_seq OWNER TO adeyemi;

--
-- Name: notes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.notes_id_seq OWNED BY public.notes.id;


--
-- Name: notification_parents; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.notification_parents (
    id bigint NOT NULL,
    paren_id bigint NOT NULL,
    titre character varying(255) NOT NULL,
    contenu text NOT NULL,
    lu boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.notification_parents OWNER TO adeyemi;

--
-- Name: notifications; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.notifications (
    id uuid NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    notifiable_id bigint NOT NULL,
    data text NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.notifications OWNER TO adeyemi;

--
-- Name: notifications_parents_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.notifications_parents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.notifications_parents_id_seq OWNER TO adeyemi;

--
-- Name: notifications_parents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.notifications_parents_id_seq OWNED BY public.notification_parents.id;


--
-- Name: oloyes; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.oloyes (
    id bigint NOT NULL,
    date date NOT NULL,
    libelle character varying(255) NOT NULL,
    categorie character varying(255),
    montant numeric(15,2) NOT NULL,
    beneficiaire character varying(255),
    observation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.oloyes OWNER TO adeyemi;

--
-- Name: oloyes_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.oloyes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.oloyes_id_seq OWNER TO adeyemi;

--
-- Name: oloyes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.oloyes_id_seq OWNED BY public.oloyes.id;


--
-- Name: operations; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.operations (
    id bigint NOT NULL,
    date date NOT NULL,
    libelle character varying(255) NOT NULL,
    montant numeric(20,2) NOT NULL,
    description character varying(255),
    categorie character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT operations_categorie_check CHECK (((categorie)::text = ANY (ARRAY[('recette'::character varying)::text, ('dépense'::character varying)::text])))
);


ALTER TABLE public.operations OWNER TO adeyemi;

--
-- Name: operations_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.operations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.operations_id_seq OWNER TO adeyemi;

--
-- Name: operations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.operations_id_seq OWNED BY public.operations.id;


--
-- Name: paiement_details; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.paiement_details (
    id bigint NOT NULL,
    paiement_id bigint NOT NULL,
    inscription_frais_id bigint NOT NULL,
    montant_paye integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.paiement_details OWNER TO adeyemi;

--
-- Name: paiement_details_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.paiement_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.paiement_details_id_seq OWNER TO adeyemi;

--
-- Name: paiement_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.paiement_details_id_seq OWNED BY public.paiement_details.id;


--
-- Name: paiements; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.paiements (
    id bigint NOT NULL,
    inscription_id bigint NOT NULL,
    frais_id bigint NOT NULL,
    date_paiement date DEFAULT CURRENT_DATE,
    montant_verse numeric(10,2) NOT NULL,
    mode_paiement character varying(255) NOT NULL,
    numero_recu character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    montant_total numeric(10,2),
    reference character varying(255)
);


ALTER TABLE public.paiements OWNER TO adeyemi;

--
-- Name: paiements_benefices; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.paiements_benefices (
    id bigint NOT NULL,
    repartition_id bigint NOT NULL,
    date_paiement date NOT NULL,
    montant numeric(15,2) NOT NULL,
    mode_paiement character varying(255) DEFAULT 'Espèces'::character varying NOT NULL,
    reference character varying(255),
    observation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.paiements_benefices OWNER TO adeyemi;

--
-- Name: paiements_benefices_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.paiements_benefices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.paiements_benefices_id_seq OWNER TO adeyemi;

--
-- Name: paiements_benefices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.paiements_benefices_id_seq OWNED BY public.paiements_benefices.id;


--
-- Name: paiements_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.paiements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.paiements_id_seq OWNER TO adeyemi;

--
-- Name: paiements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.paiements_id_seq OWNED BY public.paiements.id;


--
-- Name: parametres_investissements; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.parametres_investissements (
    id bigint NOT NULL,
    cle character varying(255) NOT NULL,
    libelle character varying(255) NOT NULL,
    valeur text,
    type character varying(255) DEFAULT 'texte'::character varying NOT NULL,
    description text,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.parametres_investissements OWNER TO adeyemi;

--
-- Name: parametres_investissements_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.parametres_investissements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.parametres_investissements_id_seq OWNER TO adeyemi;

--
-- Name: parametres_investissements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.parametres_investissements_id_seq OWNED BY public.parametres_investissements.id;


--
-- Name: parens; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.parens (
    id bigint NOT NULL,
    nom_parent character varying(255),
    prenom_parent character varying(255),
    telephone_parent character varying(20),
    adresse_parent character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id bigint
);


ALTER TABLE public.parens OWNER TO adeyemi;

--
-- Name: parens_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.parens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.parens_id_seq OWNER TO adeyemi;

--
-- Name: parens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.parens_id_seq OWNED BY public.parens.id;


--
-- Name: participant_examens; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.participant_examens (
    id bigint NOT NULL,
    examen_blanc_id bigint NOT NULL,
    inscription_id bigint NOT NULL,
    numero_table character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    moyenne numeric(5,2)
);


ALTER TABLE public.participant_examens OWNER TO adeyemi;

--
-- Name: participant_examens_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.participant_examens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.participant_examens_id_seq OWNER TO adeyemi;

--
-- Name: participant_examens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.participant_examens_id_seq OWNED BY public.participant_examens.id;


--
-- Name: passages; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.passages (
    id bigint NOT NULL,
    inscription_id bigint NOT NULL,
    moyenne_id bigint NOT NULL,
    classe_id bigint NOT NULL,
    ancienne_classe_id bigint NOT NULL,
    nouvelle_classe_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    moyenne_annuelle numeric(5,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    decision character varying(10)
);


ALTER TABLE public.passages OWNER TO adeyemi;

--
-- Name: passages_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.passages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.passages_id_seq OWNER TO adeyemi;

--
-- Name: passages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.passages_id_seq OWNED BY public.passages.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO adeyemi;

--
-- Name: permissions; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO adeyemi;

--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.permissions_id_seq OWNER TO adeyemi;

--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO adeyemi;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.personal_access_tokens_id_seq OWNER TO adeyemi;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: recettes; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.recettes (
    id bigint NOT NULL,
    date_paiement date NOT NULL,
    montant_verse numeric(20,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    paiement_id bigint,
    inscription_id bigint,
    mode_paiement character varying,
    numero_recu character varying
);


ALTER TABLE public.recettes OWNER TO adeyemi;

--
-- Name: recettes_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.recettes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.recettes_id_seq OWNER TO adeyemi;

--
-- Name: recettes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.recettes_id_seq OWNED BY public.recettes.id;


--
-- Name: repartitions; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.repartitions (
    id bigint NOT NULL,
    benefice_id bigint NOT NULL,
    investissement_id bigint NOT NULL,
    pourcentage numeric(8,4) NOT NULL,
    montant numeric(15,2) NOT NULL,
    statut character varying(255) DEFAULT 'en_attente'::character varying NOT NULL,
    observation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT repartitions_statut_check CHECK (((statut)::text = ANY (ARRAY[('en_attente'::character varying)::text, ('partiellement_paye'::character varying)::text, ('paye'::character varying)::text])))
);


ALTER TABLE public.repartitions OWNER TO adeyemi;

--
-- Name: repartitions_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.repartitions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.repartitions_id_seq OWNER TO adeyemi;

--
-- Name: repartitions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.repartitions_id_seq OWNED BY public.repartitions.id;


--
-- Name: retraits_capital; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.retraits_capital (
    id bigint NOT NULL,
    investissement_id bigint NOT NULL,
    date_retrait date NOT NULL,
    montant numeric(15,2) NOT NULL,
    mode_retrait character varying(255),
    reference character varying(255),
    motif text,
    observation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.retraits_capital OWNER TO adeyemi;

--
-- Name: retraits_capital_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.retraits_capital_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.retraits_capital_id_seq OWNER TO adeyemi;

--
-- Name: retraits_capital_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.retraits_capital_id_seq OWNED BY public.retraits_capital.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


ALTER TABLE public.role_has_permissions OWNER TO adeyemi;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    name character varying(255),
    guard_name character varying(255) DEFAULT 'web'::character varying
);


ALTER TABLE public.roles OWNER TO adeyemi;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.roles_id_seq OWNER TO adeyemi;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: scolarites; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.scolarites (
    id bigint NOT NULL,
    classe character varying(255) NOT NULL,
    inscription character varying(255) NOT NULL,
    montant character varying(255) NOT NULL,
    mpaye character varying(255) NOT NULL,
    reste character varying(255) NOT NULL,
    inscription_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.scolarites OWNER TO adeyemi;

--
-- Name: scolarites_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.scolarites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.scolarites_id_seq OWNER TO adeyemi;

--
-- Name: scolarites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.scolarites_id_seq OWNED BY public.scolarites.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO adeyemi;

--
-- Name: td_modes_paiements; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.td_modes_paiements (
    id bigint NOT NULL,
    eleve_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    mode_paiement character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT td_modes_paiements_mode_paiement_check CHECK (((mode_paiement)::text = ANY (ARRAY[('seance'::character varying)::text, ('mois'::character varying)::text, ('annee'::character varying)::text])))
);


ALTER TABLE public.td_modes_paiements OWNER TO adeyemi;

--
-- Name: td_modes_paiements_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.td_modes_paiements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.td_modes_paiements_id_seq OWNER TO adeyemi;

--
-- Name: td_modes_paiements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.td_modes_paiements_id_seq OWNED BY public.td_modes_paiements.id;


--
-- Name: td_paiements; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.td_paiements (
    id bigint NOT NULL,
    eleve_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    montant numeric(10,2) NOT NULL,
    date_paiement date NOT NULL,
    reference character varying(255),
    observation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.td_paiements OWNER TO adeyemi;

--
-- Name: td_paiements_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.td_paiements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.td_paiements_id_seq OWNER TO adeyemi;

--
-- Name: td_paiements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.td_paiements_id_seq OWNED BY public.td_paiements.id;


--
-- Name: td_presences; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.td_presences (
    id bigint NOT NULL,
    td_seance_id bigint NOT NULL,
    eleve_id bigint NOT NULL,
    present boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.td_presences OWNER TO adeyemi;

--
-- Name: td_presences_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.td_presences_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.td_presences_id_seq OWNER TO adeyemi;

--
-- Name: td_presences_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.td_presences_id_seq OWNED BY public.td_presences.id;


--
-- Name: td_seances; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.td_seances (
    id bigint NOT NULL,
    annee_id bigint NOT NULL,
    classe_id bigint NOT NULL,
    date date NOT NULL,
    libelle character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.td_seances OWNER TO adeyemi;

--
-- Name: td_seances_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.td_seances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.td_seances_id_seq OWNER TO adeyemi;

--
-- Name: td_seances_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.td_seances_id_seq OWNED BY public.td_seances.id;


--
-- Name: td_tarifs; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.td_tarifs (
    id bigint NOT NULL,
    annee_id bigint NOT NULL,
    categorie public.td_categorie NOT NULL,
    type character varying(255) NOT NULL,
    montant numeric(10,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT td_tarifs_type_check CHECK (((type)::text = ANY (ARRAY[('seance'::character varying)::text, ('mois'::character varying)::text, ('annee'::character varying)::text])))
);


ALTER TABLE public.td_tarifs OWNER TO adeyemi;

--
-- Name: td_tarifs_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.td_tarifs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.td_tarifs_id_seq OWNER TO adeyemi;

--
-- Name: td_tarifs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.td_tarifs_id_seq OWNED BY public.td_tarifs.id;


--
-- Name: tests; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.tests (
    id bigint NOT NULL,
    titre character varying(255) NOT NULL,
    matiere_id bigint NOT NULL,
    classe_id bigint NOT NULL,
    annee_id bigint NOT NULL,
    fichier character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    trimestre_id bigint,
    date date,
    hash character varying(64),
    type public.tests_type_enum
);


ALTER TABLE public.tests OWNER TO adeyemi;

--
-- Name: tests_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.tests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tests_id_seq OWNER TO adeyemi;

--
-- Name: tests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.tests_id_seq OWNED BY public.tests.id;


--
-- Name: transactions; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.transactions (
    id bigint NOT NULL,
    date_transaction date NOT NULL,
    type character varying(255) NOT NULL,
    categorie_id bigint NOT NULL,
    compte_id bigint NOT NULL,
    montant numeric(20,2) NOT NULL,
    mode_paiement character varying(255),
    description text,
    created_by bigint,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT transactions_type_check CHECK (((type)::text = ANY (ARRAY[('recette'::character varying)::text, ('dépense'::character varying)::text])))
);


ALTER TABLE public.transactions OWNER TO adeyemi;

--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.transactions_id_seq OWNER TO adeyemi;

--
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.transactions_id_seq OWNED BY public.transactions.id;


--
-- Name: trimestres; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.trimestres (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    ordre integer,
    periode character varying(50)
);


ALTER TABLE public.trimestres OWNER TO adeyemi;

--
-- Name: trimestres_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.trimestres_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.trimestres_id_seq OWNER TO adeyemi;

--
-- Name: trimestres_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.trimestres_id_seq OWNED BY public.trimestres.id;


--
-- Name: types; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.types (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.types OWNER TO adeyemi;

--
-- Name: types_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.types_id_seq OWNER TO adeyemi;

--
-- Name: types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.types_id_seq OWNED BY public.types.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    nom character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    photo character varying(255),
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    current_team_id bigint,
    profile_photo_path character varying(2048),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    two_factor_secret text,
    two_factor_recovery_codes text,
    two_factor_confirmed_at timestamp(0) without time zone,
    telephone character varying(20),
    prenom character varying(255),
    role character varying(50)
);


ALTER TABLE public.users OWNER TO adeyemi;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO adeyemi;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: versements; Type: TABLE; Schema: public; Owner: adeyemi
--

CREATE TABLE public.versements (
    id bigint NOT NULL,
    investissement_id bigint NOT NULL,
    date_versement date NOT NULL,
    montant numeric(15,2) NOT NULL,
    mode_paiement character varying(50) NOT NULL,
    reference character varying(255),
    observation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.versements OWNER TO adeyemi;

--
-- Name: versements_id_seq; Type: SEQUENCE; Schema: public; Owner: adeyemi
--

CREATE SEQUENCE public.versements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.versements_id_seq OWNER TO adeyemi;

--
-- Name: versements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: adeyemi
--

ALTER SEQUENCE public.versements_id_seq OWNED BY public.versements.id;


--
-- Name: annee_classe id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe ALTER COLUMN id SET DEFAULT nextval('public.classe_annee_id_seq'::regclass);


--
-- Name: annee_classe_frais id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe_frais ALTER COLUMN id SET DEFAULT nextval('public.annee_classe_frais_id_seq'::regclass);


--
-- Name: annee_trimestre id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_trimestre ALTER COLUMN id SET DEFAULT nextval('public.annee_trimestre_id_seq'::regclass);


--
-- Name: annees id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annees ALTER COLUMN id SET DEFAULT nextval('public.annees_id_seq'::regclass);


--
-- Name: articles id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.articles ALTER COLUMN id SET DEFAULT nextval('public.articles_id_seq'::regclass);


--
-- Name: benefices id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.benefices ALTER COLUMN id SET DEFAULT nextval('public.benefices_id_seq'::regclass);


--
-- Name: budgets id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.budgets ALTER COLUMN id SET DEFAULT nextval('public.budgets_id_seq'::regclass);


--
-- Name: bulletins id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.bulletins ALTER COLUMN id SET DEFAULT nextval('public.bulletins_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: classe_enseignant id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_enseignant ALTER COLUMN id SET DEFAULT nextval('public.classe_enseignant_id_seq'::regclass);


--
-- Name: classe_matiere id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_matiere ALTER COLUMN id SET DEFAULT nextval('public.matiere_classe_id_seq'::regclass);


--
-- Name: classe_transitions id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_transitions ALTER COLUMN id SET DEFAULT nextval('public.classe_transitions_id_seq'::regclass);


--
-- Name: classes id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classes ALTER COLUMN id SET DEFAULT nextval('public.classes_id_seq'::regclass);


--
-- Name: comptes id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.comptes ALTER COLUMN id SET DEFAULT nextval('public.comptes_id_seq'::regclass);


--
-- Name: conduites id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.conduites ALTER COLUMN id SET DEFAULT nextval('public.conduites_id_seq'::regclass);


--
-- Name: contacts id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.contacts ALTER COLUMN id SET DEFAULT nextval('public.contacts_id_seq'::regclass);


--
-- Name: cycles id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.cycles ALTER COLUMN id SET DEFAULT nextval('public.cycles_id_seq'::regclass);


--
-- Name: depenses id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.depenses ALTER COLUMN id SET DEFAULT nextval('public.depenses_id_seq'::regclass);


--
-- Name: echeances id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.echeances ALTER COLUMN id SET DEFAULT nextval('public.echeances_id_seq'::regclass);


--
-- Name: eleves id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.eleves ALTER COLUMN id SET DEFAULT nextval('public.eleves_id_seq'::regclass);


--
-- Name: enseignants id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.enseignants ALTER COLUMN id SET DEFAULT nextval('public.enseignants_id_seq'::regclass);


--
-- Name: epreuves id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.epreuves ALTER COLUMN id SET DEFAULT nextval('public.epreuves_id_seq'::regclass);


--
-- Name: examen_blanc_classe id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blanc_classe ALTER COLUMN id SET DEFAULT nextval('public.examen_blanc_classe_id_seq'::regclass);


--
-- Name: examen_blancs id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blancs ALTER COLUMN id SET DEFAULT nextval('public.examen_blancs_id_seq'::regclass);


--
-- Name: examen_classes id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_classes ALTER COLUMN id SET DEFAULT nextval('public.examen_classes_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: finances id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.finances ALTER COLUMN id SET DEFAULT nextval('public.finances_id_seq'::regclass);


--
-- Name: frais id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.frais ALTER COLUMN id SET DEFAULT nextval('public.frais_id_seq'::regclass);


--
-- Name: galeries id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.galeries ALTER COLUMN id SET DEFAULT nextval('public.galeries_id_seq'::regclass);


--
-- Name: importation_notes id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importation_notes ALTER COLUMN id SET DEFAULT nextval('public.importation_notes_id_seq'::regclass);


--
-- Name: importations_notes id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importations_notes ALTER COLUMN id SET DEFAULT nextval('public.importations_notes_id_seq'::regclass);


--
-- Name: inscription_frais id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscription_frais ALTER COLUMN id SET DEFAULT nextval('public.inscription_frais_id_seq'::regclass);


--
-- Name: inscriptions id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscriptions ALTER COLUMN id SET DEFAULT nextval('public.inscriptions_id_seq'::regclass);


--
-- Name: investissements id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.investissements ALTER COLUMN id SET DEFAULT nextval('public.investissements_id_seq'::regclass);


--
-- Name: investisseurs id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.investisseurs ALTER COLUMN id SET DEFAULT nextval('public.investisseurs_id_seq'::regclass);


--
-- Name: matieres id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.matieres ALTER COLUMN id SET DEFAULT nextval('public.matieres_id_seq'::regclass);


--
-- Name: media id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.media ALTER COLUMN id SET DEFAULT nextval('public.media_id_seq'::regclass);


--
-- Name: message_parents id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.message_parents ALTER COLUMN id SET DEFAULT nextval('public.messages_parents_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: mouvement_stocks id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.mouvement_stocks ALTER COLUMN id SET DEFAULT nextval('public.mouvement_stocks_id_seq'::regclass);


--
-- Name: moyennes id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.moyennes ALTER COLUMN id SET DEFAULT nextval('public.moyennes_id_seq'::regclass);


--
-- Name: note_examens id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.note_examens ALTER COLUMN id SET DEFAULT nextval('public.note_examens_id_seq'::regclass);


--
-- Name: notes id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notes ALTER COLUMN id SET DEFAULT nextval('public.notes_id_seq'::regclass);


--
-- Name: notification_parents id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notification_parents ALTER COLUMN id SET DEFAULT nextval('public.notifications_parents_id_seq'::regclass);


--
-- Name: oloyes id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.oloyes ALTER COLUMN id SET DEFAULT nextval('public.oloyes_id_seq'::regclass);


--
-- Name: operations id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.operations ALTER COLUMN id SET DEFAULT nextval('public.operations_id_seq'::regclass);


--
-- Name: paiement_details id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiement_details ALTER COLUMN id SET DEFAULT nextval('public.paiement_details_id_seq'::regclass);


--
-- Name: paiements id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiements ALTER COLUMN id SET DEFAULT nextval('public.paiements_id_seq'::regclass);


--
-- Name: paiements_benefices id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiements_benefices ALTER COLUMN id SET DEFAULT nextval('public.paiements_benefices_id_seq'::regclass);


--
-- Name: parametres_investissements id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.parametres_investissements ALTER COLUMN id SET DEFAULT nextval('public.parametres_investissements_id_seq'::regclass);


--
-- Name: parens id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.parens ALTER COLUMN id SET DEFAULT nextval('public.parens_id_seq'::regclass);


--
-- Name: participant_examens id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.participant_examens ALTER COLUMN id SET DEFAULT nextval('public.participant_examens_id_seq'::regclass);


--
-- Name: passages id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.passages ALTER COLUMN id SET DEFAULT nextval('public.passages_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: recettes id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.recettes ALTER COLUMN id SET DEFAULT nextval('public.recettes_id_seq'::regclass);


--
-- Name: repartitions id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.repartitions ALTER COLUMN id SET DEFAULT nextval('public.repartitions_id_seq'::regclass);


--
-- Name: retraits_capital id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.retraits_capital ALTER COLUMN id SET DEFAULT nextval('public.retraits_capital_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: scolarites id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.scolarites ALTER COLUMN id SET DEFAULT nextval('public.scolarites_id_seq'::regclass);


--
-- Name: td_modes_paiements id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_modes_paiements ALTER COLUMN id SET DEFAULT nextval('public.td_modes_paiements_id_seq'::regclass);


--
-- Name: td_paiements id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_paiements ALTER COLUMN id SET DEFAULT nextval('public.td_paiements_id_seq'::regclass);


--
-- Name: td_presences id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_presences ALTER COLUMN id SET DEFAULT nextval('public.td_presences_id_seq'::regclass);


--
-- Name: td_seances id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_seances ALTER COLUMN id SET DEFAULT nextval('public.td_seances_id_seq'::regclass);


--
-- Name: td_tarifs id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_tarifs ALTER COLUMN id SET DEFAULT nextval('public.td_tarifs_id_seq'::regclass);


--
-- Name: tests id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.tests ALTER COLUMN id SET DEFAULT nextval('public.tests_id_seq'::regclass);


--
-- Name: transactions id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.transactions ALTER COLUMN id SET DEFAULT nextval('public.transactions_id_seq'::regclass);


--
-- Name: trimestres id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.trimestres ALTER COLUMN id SET DEFAULT nextval('public.trimestres_id_seq'::regclass);


--
-- Name: types id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.types ALTER COLUMN id SET DEFAULT nextval('public.types_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: versements id; Type: DEFAULT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.versements ALTER COLUMN id SET DEFAULT nextval('public.versements_id_seq'::regclass);


--
-- Data for Name: annee_classe; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.annee_classe (id, classe_id, annee_id, created_at, updated_at, active) FROM stdin;
155	11	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
156	12	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
157	10	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
158	9	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
159	4	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
160	3	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
161	2	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
162	1	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
163	15	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
164	16	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
165	14	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
170	4	2	2025-12-22 13:15:32	2025-12-22 13:15:32	t
171	3	2	2025-12-22 13:15:32	2025-12-22 13:15:32	t
172	2	2	2025-12-22 13:15:32	2025-12-22 13:15:32	t
173	1	2	2025-12-22 13:15:32	2025-12-22 13:15:32	t
167	12	2	2025-12-22 13:15:32	2025-12-23 19:06:24	t
166	11	2	2025-12-22 13:15:32	2025-12-23 19:06:40	t
168	10	2	2025-12-22 13:15:32	2025-12-23 19:06:53	t
169	9	2	2025-12-22 13:15:32	2025-12-23 19:07:06	t
219	9	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
220	11	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
176	14	2	2025-12-22 13:15:32	2025-12-23 19:07:57	t
174	15	2	2025-12-22 13:15:32	2026-02-28 20:39:59	t
175	16	2	2025-12-22 13:15:32	2026-02-28 20:43:28	t
190	19	2	2026-04-10 00:07:38	2026-04-10 00:07:38	t
191	20	2	2026-04-10 00:08:13	2026-04-10 00:08:13	t
192	21	2	2026-04-10 00:08:32	2026-04-10 00:08:32	t
193	22	2	2026-04-10 00:08:58	2026-04-10 00:08:58	t
194	23	2	2026-04-10 00:17:00	2026-04-10 00:17:00	t
195	24	2	2026-04-10 00:19:57	2026-04-10 00:19:57	t
196	25	2	2026-04-10 00:20:22	2026-04-10 00:32:38	t
189	18	2	2026-04-10 00:05:41	2026-04-10 15:50:33	t
205	18	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
206	19	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
207	20	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
208	21	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
209	22	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
210	23	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
211	24	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
212	25	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
213	1	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
214	2	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
215	3	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
216	4	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
217	14	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
218	12	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
221	10	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
222	15	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
223	16	5	2026-05-27 21:11:03	2026-05-27 21:11:03	t
\.


--
-- Data for Name: annee_classe_frais; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.annee_classe_frais (id, annee_id, classe_id, frais_id, montant, created_at, updated_at) FROM stdin;
2	2	2	2	80000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
3	2	3	3	90000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
4	2	4	4	90000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
5	2	9	5	110000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
6	2	1	6	5000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
7	2	2	7	5000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
8	2	3	8	5000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
9	2	4	9	5000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
10	2	9	10	5000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
16	2	1	16	2000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
17	2	2	17	2000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
18	2	3	18	2000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
19	2	4	19	2000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
20	2	9	20	2000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
21	2	18	25	2000.00	2026-05-09 20:54:22	2026-05-09 20:54:22
23	2	12	31	110000.00	2026-05-24 17:08:45	2026-05-24 17:08:45
47	5	1	1	80000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
48	5	2	2	80000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
49	5	3	3	90000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
50	5	4	4	90000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
51	5	9	5	110000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
52	5	1	6	5000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
53	5	2	7	5000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
54	5	3	8	5000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
55	5	4	9	5000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
56	5	9	10	5000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
62	5	1	16	2000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
63	5	2	17	2000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
64	5	3	18	2000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
65	5	4	19	2000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
66	5	9	20	2000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
67	5	18	25	2000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
69	5	12	31	110000.00	2026-05-27 21:11:03	2026-05-27 21:11:03
70	2	18	32	67000.00	2026-06-01 08:21:33	2026-06-01 08:21:33
71	2	19	33	67000.00	2026-06-01 08:24:52	2026-06-01 08:24:52
72	2	20	34	62000.00	2026-06-01 08:29:05	2026-06-01 08:29:05
73	2	21	35	62000.00	2026-06-01 08:40:13	2026-06-01 08:40:13
74	2	22	36	62000.00	2026-06-01 08:42:32	2026-06-01 08:42:32
75	2	23	37	62000.00	2026-06-01 08:44:34	2026-06-01 08:44:34
76	2	24	38	67000.00	2026-06-01 08:46:48	2026-06-01 08:46:48
77	2	25	39	67000.00	2026-06-01 08:48:48	2026-06-01 08:48:48
78	2	18	40	2000.00	2026-06-01 08:53:57	2026-06-01 08:53:57
79	2	19	41	2000.00	2026-06-01 08:54:55	2026-06-01 08:54:55
94	2	18	56	1500.00	2026-06-01 09:13:50	2026-06-01 09:13:50
95	2	19	57	1500.00	2026-06-01 09:15:21	2026-06-01 09:15:21
96	2	20	58	1500.00	2026-06-01 09:16:47	2026-06-01 09:16:47
97	2	21	59	1500.00	2026-06-01 09:18:06	2026-06-01 09:18:06
98	2	22	60	1500.00	2026-06-01 09:20:42	2026-06-01 09:20:42
99	2	23	61	1500.00	2026-06-01 09:22:04	2026-06-01 09:22:04
100	2	24	62	1500.00	2026-06-01 09:23:20	2026-06-01 09:23:20
101	2	25	63	1500.00	2026-06-01 09:24:35	2026-06-01 09:24:35
102	2	15	64	5000.00	2026-06-01 09:29:39	2026-06-01 09:29:39
104	2	11	66	5000.00	2026-06-01 09:36:31	2026-06-01 09:36:31
105	2	10	67	5000.00	2026-06-01 09:37:56	2026-06-01 09:37:56
106	2	12	68	5000.00	2026-06-01 09:39:51	2026-06-01 09:39:51
107	2	15	69	130000.00	2026-06-01 09:42:56	2026-06-01 09:42:56
110	2	10	72	125000.00	2026-06-01 09:51:14	2026-06-01 09:51:14
111	5	12	73	5000.00	2026-06-05 13:15:51	2026-06-05 13:15:51
112	5	12	74	2500.00	2026-06-05 13:17:28	2026-06-05 13:17:28
113	5	12	75	2000.00	2026-06-05 13:18:58	2026-06-05 13:18:58
116	5	9	78	2500.00	2026-06-05 13:28:31	2026-06-05 13:28:31
118	5	11	80	125000.00	2026-06-05 13:33:01	2026-06-05 13:33:01
120	5	11	82	2500.00	2026-06-05 13:39:35	2026-06-05 13:39:35
121	5	11	83	2000.00	2026-06-05 13:41:09	2026-06-05 13:41:09
1	2	1	1	80000.00	2026-05-09 20:54:22	2026-06-10 13:33:57
123	5	10	85	125000.00	2026-06-05 13:46:41	2026-06-05 13:46:41
124	5	10	86	5000.00	2026-06-05 13:48:01	2026-06-05 13:48:01
125	5	10	87	2500.00	2026-06-05 13:49:16	2026-06-05 13:49:16
126	5	10	88	2000.00	2026-06-05 13:50:58	2026-06-05 13:50:58
130	5	15	92	2500.00	2026-06-05 13:58:03	2026-06-05 13:58:03
131	5	15	93	2000.00	2026-06-05 13:59:30	2026-06-05 13:59:30
133	5	16	95	130000.00	2026-06-05 14:03:23	2026-06-05 14:03:23
134	5	16	96	5000.00	2026-06-05 14:04:20	2026-06-05 14:04:20
127	5	10	89	7000.00	2026-06-05 13:52:16	2026-07-07 23:47:26
114	5	12	76	7000.00	2026-06-05 13:20:33	2026-07-07 23:49:41
122	5	11	84	7000.00	2026-06-05 13:43:12	2026-07-07 23:50:15
132	5	15	94	7000.00	2026-06-05 14:01:01	2026-07-07 23:55:39
135	5	16	97	2500.00	2026-06-05 14:05:38	2026-06-05 14:05:38
136	5	16	98	2000.00	2026-06-05 14:07:09	2026-06-05 14:07:09
144	5	1	106	2500.00	2026-06-05 15:44:21	2026-06-05 15:44:21
145	5	2	107	2500.00	2026-06-05 15:45:50	2026-06-05 15:45:50
146	5	3	108	2500.00	2026-06-05 15:47:06	2026-06-05 15:47:06
147	5	4	109	2500.00	2026-06-05 15:48:14	2026-06-05 15:48:14
149	5	18	32	67000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
150	5	19	33	67000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
151	5	20	34	62000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
152	5	21	35	62000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
153	5	22	36	62000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
154	5	23	37	62000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
155	5	24	38	67000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
156	5	25	39	67000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
159	5	20	42	2000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
160	5	21	43	2000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
161	5	22	44	2000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
162	5	23	45	2000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
163	5	24	46	2000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
164	5	25	47	2000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
172	5	18	56	1500.00	2026-07-06 13:24:12	2026-07-06 13:24:12
173	5	19	57	1500.00	2026-07-06 13:24:12	2026-07-06 13:24:12
174	5	20	58	1500.00	2026-07-06 13:24:12	2026-07-06 13:24:12
175	5	21	59	1500.00	2026-07-06 13:24:12	2026-07-06 13:24:12
176	5	22	60	1500.00	2026-07-06 13:24:12	2026-07-06 13:24:12
177	5	23	61	1500.00	2026-07-06 13:24:12	2026-07-06 13:24:12
178	5	24	62	1500.00	2026-07-06 13:24:12	2026-07-06 13:24:12
179	5	25	63	1500.00	2026-07-06 13:24:12	2026-07-06 13:24:12
180	5	15	64	5000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
182	5	11	66	5000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
183	5	10	67	5000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
184	5	12	68	5000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
185	5	15	69	130000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
188	5	10	72	125000.00	2026-07-06 13:24:12	2026-07-06 13:24:12
189	2	12	73	5000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
190	2	12	74	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
191	2	12	75	2000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
193	2	9	78	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
194	2	11	80	125000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
196	2	11	82	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
197	2	11	83	2000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
199	2	10	85	125000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
200	2	10	86	5000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
201	2	10	87	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
202	2	10	88	2000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
206	2	15	92	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
207	2	15	93	2000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
209	2	16	95	130000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
210	2	16	96	5000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
211	2	16	97	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
212	2	16	98	2000.00	2026-07-06 14:26:39	2026-07-06 14:26:39
219	2	1	106	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
220	2	2	107	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
221	2	3	108	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
222	2	4	109	2500.00	2026-07-06 14:26:39	2026-07-06 14:26:39
86	2	18	48	6000.00	2026-06-01 09:02:22	2026-07-07 22:44:50
148	5	19	49	6000.00	2026-07-06 13:24:12	2026-07-07 22:49:11
223	5	18	110	2000.00	2026-07-07 22:53:18	2026-07-07 22:53:18
224	5	18	111	6000.00	2026-07-07 22:55:54	2026-07-07 22:55:54
225	5	19	112	2000.00	2026-07-07 22:58:09	2026-07-07 22:58:09
226	5	19	113	2000.00	2026-07-07 23:03:11	2026-07-07 23:03:11
166	5	20	50	6000.00	2026-07-06 13:24:12	2026-07-07 23:07:46
227	5	20	114	2000.00	2026-07-07 23:09:38	2026-07-07 23:09:38
167	5	21	51	6000.00	2026-07-06 13:24:12	2026-07-07 23:12:45
228	5	21	115	2000.00	2026-07-07 23:14:37	2026-07-07 23:14:37
168	5	22	52	6000.00	2026-07-06 13:24:12	2026-07-07 23:16:29
229	5	22	116	2000.00	2026-07-07 23:18:26	2026-07-07 23:18:26
169	5	23	53	6000.00	2026-07-06 13:24:12	2026-07-07 23:21:12
230	5	23	117	2000.00	2026-07-07 23:23:48	2026-07-07 23:23:48
170	5	24	54	6000.00	2026-07-06 13:24:12	2026-07-07 23:29:24
231	5	24	118	2000.00	2026-07-07 23:31:04	2026-07-07 23:31:04
171	5	25	55	6000.00	2026-07-06 13:24:12	2026-07-07 23:35:14
232	5	25	119	2000.00	2026-07-07 23:37:33	2026-07-07 23:37:33
140	5	1	102	7000.00	2026-06-05 15:39:22	2026-07-07 23:42:18
141	5	2	103	7000.00	2026-06-05 15:40:37	2026-07-07 23:44:24
142	5	3	104	7000.00	2026-06-05 15:41:45	2026-07-07 23:45:03
143	5	4	105	7000.00	2026-06-05 15:43:13	2026-07-07 23:45:50
138	5	9	100	7000.00	2026-06-05 15:18:24	2026-07-07 23:46:43
137	5	16	99	7000.00	2026-06-05 14:08:47	2026-07-07 23:48:31
\.


--
-- Data for Name: annee_trimestre; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.annee_trimestre (id, annee_id, trimestre_id, created_at, updated_at, active) FROM stdin;
13	1	1	2025-12-22 13:14:50	2025-12-22 13:14:50	t
14	1	2	2025-12-22 13:14:50	2025-12-22 13:14:50	t
15	1	3	2025-12-22 13:14:50	2025-12-22 13:14:50	t
16	2	1	2025-12-22 13:15:32	2025-12-22 13:15:32	t
17	2	2	2025-12-22 13:15:32	2025-12-22 13:15:32	t
18	2	3	2025-12-22 13:15:32	2025-12-22 13:15:32	t
25	5	1	\N	\N	t
26	5	2	\N	\N	t
27	5	3	\N	\N	t
\.


--
-- Data for Name: annees; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.annees (id, nom, debut, fin, created_at, updated_at, en_cours) FROM stdin;
1	2024-2025	2024-09-16	2025-06-30	2025-12-22 13:14:50	2025-12-22 13:14:50	f
2	2025-2026	2025-09-15	2026-06-30	2025-12-22 13:15:32	2026-07-06 14:26:39	f
5	2026-2027	2026-07-01	2027-06-30	2026-05-27 21:11:03	2026-07-06 14:33:48	t
\.


--
-- Data for Name: articles; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.articles (id, type_id, nom, reference, prix_achat, prix_vente, stock_min, description, created_at, updated_at) FROM stdin;
1	2	les uniformes tissu pantalon + tissu chémise	u001	5000.00	8000.00	5	ssss	2026-02-07 00:12:34	2026-02-07 00:12:34
\.


--
-- Data for Name: benefices; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.benefices (id, date_debut, date_fin, montant, observation, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: budgets; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.budgets (id, annee_id, categorie_id, montant_prevu, periode, created_at, updated_at, nom) FROM stdin;
3	2	10	100000.00	Septembre 2025-juin 2026	2026-02-14 21:39:12	2026-03-23 14:20:05	Electricité
\.


--
-- Data for Name: bulletins; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.bulletins (id, eleve_id, classe_id, annee_id, trimestre_id, created_at, updated_at, moyenne_scientifique, moyenne_litteraire, moyenne_trimestrielle, moyenne_annuelle, rang_trimestre, rang_annuel, inscription_id) FROM stdin;
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.categories (id, nom, type, description, created_at, updated_at) FROM stdin;
1	scolarités	recette	Scolarités des élèves pour la période indiquée	2025-09-29 20:27:42	2025-09-29 20:27:42
2	salaires	dépense	Le Salaire des enseignants pour la période indiquée	2025-09-29 20:51:51	2025-09-29 20:51:51
3	fournitures	dépense	les frais des fournitures payés	2025-09-29 20:53:00	2025-09-29 20:53:00
4	réparation	dépense	les frais des réparations effectuées pour le complexe	2025-09-29 20:55:36	2025-09-29 20:55:36
5	dons	recette	les dons reçus	2025-09-29 20:56:09	2025-09-29 20:56:09
6	arriérés de la scolarité	recette	les arriérés de la scolarité pour la période indiquée	2025-09-29 20:57:14	2025-09-29 20:57:14
7	électricité et eau	dépense	les frais des factures d'électricité et d'eau	2025-09-29 20:58:58	2025-09-29 20:58:58
8	YESSOUFOU A. Affissou	dépense	Somme reçue pour ses propres besions	2025-09-29 20:59:45	2025-09-29 20:59:45
9	ADEYEMI Kolawolé	dépense	Somme reçue pour ses propres besions	2025-09-29 21:00:09	2025-09-29 21:00:09
10	autres dépenses	dépense	les autres dépenses non mentionnées	2025-09-29 21:00:47	2025-09-29 21:00:47
11	autres recettes	recette	les autres recettes non mentionnées	2025-09-29 21:01:19	2025-09-29 21:01:19
12	uniforme	recette	les frais de vente des uniformes pour la période indiquée	2025-09-29 21:56:15	2025-09-29 21:56:15
13	tenue de sport	recette	Frais de vente des tenues de sport pour la période indiquée	2025-09-29 21:57:55	2025-09-29 21:57:55
14	remboursement Hounkponou	dépense	Remboursement des frais de confection des tables du menuisier HOUNKPONOU	2025-10-01 18:11:26	2025-10-01 18:11:26
15	Achat Porte	dépense	Frais d'achat de porte	2025-10-01 18:12:14	2025-10-01 18:12:14
16	salaire Justine	dépense	Salaire de ADEFOULOU Justine	2025-10-01 18:13:22	2025-10-01 18:13:22
\.


--
-- Data for Name: classe_enseignant; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.classe_enseignant (id, enseignant_id, classe_id, created_at, updated_at) FROM stdin;
3	6	1	\N	\N
4	6	2	\N	\N
\.


--
-- Data for Name: classe_matiere; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.classe_matiere (id, classe_id, matiere_id, created_at, updated_at, active) FROM stdin;
85	1	1	\N	\N	t
86	1	2	\N	\N	t
87	1	3	\N	\N	t
88	1	4	\N	\N	t
89	1	5	\N	\N	t
90	1	6	\N	\N	t
91	1	7	\N	\N	t
92	1	8	\N	\N	t
93	2	9	\N	\N	t
94	2	10	\N	\N	t
95	2	11	\N	\N	t
96	2	12	\N	\N	t
97	2	13	\N	\N	t
98	2	14	\N	\N	t
99	2	15	\N	\N	t
100	2	16	\N	\N	t
101	3	17	\N	\N	t
102	3	18	\N	\N	t
103	3	19	\N	\N	t
104	3	20	\N	\N	t
105	3	21	\N	\N	t
106	3	22	\N	\N	t
107	3	23	\N	\N	t
108	3	24	\N	\N	t
109	4	25	\N	\N	t
110	4	26	\N	\N	t
111	4	27	\N	\N	t
112	4	28	\N	\N	t
113	4	29	\N	\N	t
114	3	30	\N	\N	t
115	4	31	\N	\N	t
116	4	32	\N	\N	t
117	4	33	\N	\N	t
118	4	34	\N	\N	t
119	9	35	\N	\N	t
120	9	36	\N	\N	t
121	9	37	\N	\N	t
122	9	38	\N	\N	t
123	9	39	\N	\N	t
124	9	40	\N	\N	t
125	9	41	\N	\N	t
126	9	42	\N	\N	t
127	10	43	\N	\N	t
128	10	44	\N	\N	t
245	12	100	2026-05-03 23:39:24	2026-05-03 23:39:24	t
130	10	46	\N	\N	t
246	12	101	2026-05-03 23:39:24	2026-05-03 23:39:24	t
132	10	45	\N	\N	t
133	10	47	\N	\N	t
134	10	48	\N	\N	t
135	10	49	\N	\N	t
136	10	50	\N	\N	t
137	11	51	\N	\N	t
138	11	52	\N	\N	t
139	11	53	\N	\N	t
140	11	54	\N	\N	t
141	11	55	\N	\N	t
142	11	56	\N	\N	t
143	11	57	\N	\N	t
144	11	58	\N	\N	t
247	12	102	2026-05-03 23:39:24	2026-05-03 23:39:24	t
248	12	103	2026-05-03 23:39:24	2026-05-03 23:39:24	t
249	12	104	2026-05-03 23:39:24	2026-05-03 23:39:24	t
250	12	105	2026-05-03 23:39:24	2026-05-03 23:39:24	t
251	12	106	2026-05-03 23:39:24	2026-05-03 23:39:24	t
252	12	107	2026-05-03 23:39:24	2026-05-03 23:39:24	t
261	16	116	2026-05-03 23:44:37	2026-05-03 23:44:37	t
262	16	117	2026-05-03 23:44:37	2026-05-03 23:44:37	t
263	16	118	2026-05-03 23:44:37	2026-05-03 23:44:37	t
264	16	119	2026-05-03 23:44:37	2026-05-03 23:44:37	t
265	16	120	2026-05-03 23:44:37	2026-05-03 23:44:37	t
266	16	121	2026-05-03 23:44:37	2026-05-03 23:44:37	t
267	16	122	2026-05-03 23:44:37	2026-05-03 23:44:37	t
268	16	123	2026-05-03 23:44:37	2026-05-03 23:44:37	t
253	15	108	2026-05-03 23:42:35	2026-05-03 23:42:35	t
254	15	109	2026-05-03 23:42:35	2026-05-03 23:42:35	t
255	15	110	2026-05-03 23:42:35	2026-05-03 23:42:35	t
256	15	111	2026-05-03 23:42:35	2026-05-03 23:42:35	t
257	15	112	2026-05-03 23:42:35	2026-05-03 23:42:35	t
258	15	113	2026-05-03 23:42:35	2026-05-03 23:42:35	t
259	15	114	2026-05-03 23:42:35	2026-05-03 23:42:35	t
260	15	115	2026-05-03 23:42:35	2026-05-03 23:42:35	t
\.


--
-- Data for Name: classe_transitions; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.classe_transitions (id, classe_id, classe_superieure_id, created_at, updated_at) FROM stdin;
1	1	2	\N	\N
2	2	3	\N	\N
3	3	4	\N	\N
4	4	14	\N	\N
5	4	12	\N	\N
6	4	9	\N	\N
7	14	11	\N	\N
8	12	10	\N	\N
9	9	10	\N	\N
10	11	15	\N	\N
11	11	16	\N	\N
12	10	16	\N	\N
\.


--
-- Data for Name: classes; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.classes (id, nom, niveau, created_at, updated_at, cycle_id, ordre, rang) FROM stdin;
18	Maternelle1	Maternelle1	2026-04-10 00:05:41	2026-04-10 00:05:41	1	1	1
19	Maternelle2	Maternelle2	2026-04-10 00:07:38	2026-04-10 00:07:38	1	2	2
20	CI	CI	2026-04-10 00:08:13	2026-04-10 00:08:13	2	3	3
21	CP	CP	2026-04-10 00:08:32	2026-04-10 00:08:32	2	4	4
22	CE1	CE1	2026-04-10 00:08:58	2026-04-10 00:08:58	2	5	5
23	CE2	CE2	2026-04-10 00:17:00	2026-04-10 00:17:00	2	6	6
24	CM1	CM1	2026-04-10 00:19:57	2026-04-10 00:19:57	2	7	7
25	CM2	CM2	2026-04-10 00:20:21	2026-04-10 00:20:21	2	8	8
1	6ème	6eme	2025-08-22 20:06:28	2025-12-19 14:32:31	3	9	9
2	5ème	5eme	2025-08-22 20:06:41	2025-12-19 14:32:19	3	10	10
3	4ème	4eme	2025-08-22 20:06:54	2025-12-19 14:31:59	3	11	11
4	3ème	3eme	2025-08-22 20:07:06	2025-12-19 14:30:37	3	12	12
14	2ndeA	2ndeA	2025-12-19 14:37:43	2025-12-23 19:07:57	3	19	13
12	2ndeC	2ndeC	2025-11-15 22:16:13	2025-12-23 19:06:24	3	16	13
9	2ndeD	2ndeD	2025-11-12 21:08:24	2025-12-23 19:07:06	3	13	13
11	1èreC	1ereC	2025-11-12 21:09:11	2025-12-23 19:06:40	3	17	14
10	1èreD	1ereD	2025-11-12 21:08:52	2025-12-23 19:06:53	3	14	14
15	TleC	TleC	2025-12-19 15:37:39	2026-02-28 20:39:59	3	18	15
16	TleD	TleD	2025-12-19 15:38:31	2026-02-28 20:43:28	3	15	15
\.


--
-- Data for Name: comptes; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.comptes (id, nom, solde_initial, solde_actuel, created_at, updated_at) FROM stdin;
1	Compte Bancaire	0.00	0.00	2025-09-29 21:02:13	2025-09-29 21:02:13
3	MTN Mobile Money	0.00	0.00	2025-09-29 21:02:52	2025-09-29 21:02:52
2	Caisse Ecole	0.00	0.00	2025-09-29 21:02:33	2025-10-01 19:13:45
\.


--
-- Data for Name: conduites; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.conduites (id, annee_id, classe_id, trimestre_id, note_conduite, created_at, updated_at, inscription_id, matricule, type, niveau) FROM stdin;
262	2	1	1	18.00	2026-03-21 13:55:59	2026-03-21 13:55:59	56	231220250001	\N	Bon
263	2	1	1	18.00	2026-03-21 13:55:59	2026-03-21 13:55:59	57	231220250002	\N	Bon
264	2	1	1	18.00	2026-03-21 13:55:59	2026-03-21 13:55:59	58	231220250003	\N	Bon
265	2	1	1	18.00	2026-03-21 13:55:59	2026-03-21 13:55:59	59	231220250004	\N	Bon
266	2	2	1	18.00	2026-03-21 13:56:26	2026-03-21 13:56:26	70	231220250005	\N	Bon
267	2	2	1	18.00	2026-03-21 13:56:26	2026-03-21 13:56:26	71	231220250006	\N	Bon
268	2	2	1	18.00	2026-03-21 13:56:26	2026-03-21 13:56:26	72	231220250007	\N	Bon
269	2	2	1	18.00	2026-03-21 13:56:26	2026-03-21 13:56:26	73	231220250008	\N	Bon
270	2	2	1	18.00	2026-03-21 13:56:26	2026-03-21 13:56:26	74	231220250009	\N	Bon
271	2	2	1	18.00	2026-03-21 13:56:26	2026-03-21 13:56:26	75	231220250010	\N	Bon
272	2	2	1	18.00	2026-03-21 13:56:26	2026-03-21 13:56:26	76	231220250011	\N	Bon
273	2	3	1	17.00	2026-03-21 13:57:01	2026-03-21 13:57:01	77	231220250012	\N	Bon
274	2	3	1	17.00	2026-03-21 13:57:01	2026-03-21 13:57:01	78	231220250013	\N	Bon
275	2	3	1	17.00	2026-03-21 13:57:01	2026-03-21 13:57:01	79	231220250014	\N	Bon
276	2	3	1	17.00	2026-03-21 13:57:01	2026-03-21 13:57:01	80	231220250015	\N	Bon
277	2	3	1	17.00	2026-03-21 13:57:01	2026-03-21 13:57:01	81	231220250016	\N	Bon
278	2	3	1	17.00	2026-03-21 13:57:01	2026-03-21 13:57:01	82	231220250017	\N	Bon
279	2	3	1	17.00	2026-03-21 13:57:01	2026-03-21 13:57:01	83	231220250018	\N	Bon
280	2	4	1	15.00	2026-03-21 13:57:26	2026-03-21 13:57:26	84	231220250019	\N	Bon
281	2	4	1	15.00	2026-03-21 13:57:26	2026-03-21 13:57:26	85	231220250020	\N	Bon
282	2	4	1	15.00	2026-03-21 13:57:27	2026-03-21 13:57:27	86	231220250021	\N	Bon
283	2	4	1	15.00	2026-03-21 13:57:27	2026-03-21 13:57:27	87	231220250022	\N	Bon
284	2	4	1	15.00	2026-03-21 13:57:27	2026-03-21 13:57:27	88	231220250023	\N	Bon
285	2	4	1	15.00	2026-03-21 13:57:27	2026-03-21 13:57:27	89	231220250024	\N	Bon
286	2	4	1	15.00	2026-03-21 13:57:27	2026-03-21 13:57:27	90	231220250025	\N	Bon
287	2	9	1	16.00	2026-03-21 13:57:52	2026-03-21 13:57:52	91	231220250026	\N	Bon
288	2	9	1	16.00	2026-03-21 13:57:52	2026-03-21 13:57:52	92	231220250027	\N	Bon
289	2	9	1	16.00	2026-03-21 13:57:52	2026-03-21 13:57:52	93	231220250028	\N	Bon
290	2	9	1	16.00	2026-03-21 13:57:52	2026-03-21 13:57:52	94	231220250029	\N	Bon
291	2	9	1	16.00	2026-03-21 13:57:52	2026-03-21 13:57:52	95	231220250030	\N	Bon
293	2	11	1	16.00	2026-03-21 18:02:36	2026-03-21 18:02:36	97	231220250032	\N	Bon
294	2	1	2	17.00	2026-03-21 21:39:13	2026-03-21 21:39:13	56	231220250001	\N	Bon
295	2	1	2	18.00	2026-03-21 21:39:13	2026-03-21 21:39:13	57	231220250002	\N	Bon
296	2	1	2	17.00	2026-03-21 21:39:13	2026-03-21 21:39:13	58	231220250003	\N	Bon
297	2	1	2	18.00	2026-03-21 21:39:13	2026-03-21 21:39:13	59	231220250004	\N	Bon
298	2	2	2	18.00	2026-03-21 21:39:46	2026-03-21 21:39:46	70	231220250005	\N	Bon
299	2	2	2	17.00	2026-03-21 21:39:46	2026-03-21 21:39:46	71	231220250006	\N	Bon
300	2	2	2	18.00	2026-03-21 21:39:46	2026-03-21 21:39:46	72	231220250007	\N	Bon
301	2	2	2	18.00	2026-03-21 21:39:46	2026-03-21 21:39:46	73	231220250008	\N	Bon
302	2	2	2	17.00	2026-03-21 21:39:46	2026-03-21 21:39:46	74	231220250009	\N	Bon
303	2	2	2	18.00	2026-03-21 21:39:46	2026-03-21 21:39:46	75	231220250010	\N	Bon
304	2	2	2	18.00	2026-03-21 21:39:46	2026-03-21 21:39:46	76	231220250011	\N	Bon
305	2	3	2	18.00	2026-03-21 21:40:17	2026-03-21 21:40:17	77	231220250012	\N	Bon
306	2	3	2	18.00	2026-03-21 21:40:17	2026-03-21 21:40:17	78	231220250013	\N	Bon
307	2	3	2	18.00	2026-03-21 21:40:17	2026-03-21 21:40:17	79	231220250014	\N	Bon
308	2	3	2	17.00	2026-03-21 21:40:17	2026-03-21 21:40:17	99	231220250033	\N	Bon
309	2	3	2	18.00	2026-03-21 21:40:17	2026-03-21 21:40:17	80	231220250015	\N	Bon
310	2	3	2	18.00	2026-03-21 21:40:17	2026-03-21 21:40:17	81	231220250016	\N	Bon
311	2	3	2	17.00	2026-03-21 21:40:17	2026-03-21 21:40:17	82	231220250017	\N	Bon
312	2	3	2	18.00	2026-03-21 21:40:17	2026-03-21 21:40:17	83	231220250018	\N	Bon
313	2	4	2	17.00	2026-03-21 21:40:49	2026-03-21 21:40:49	84	231220250019	\N	Bon
314	2	4	2	17.00	2026-03-21 21:40:49	2026-03-21 21:40:49	85	231220250020	\N	Bon
315	2	4	2	17.00	2026-03-21 21:40:49	2026-03-21 21:40:49	86	231220250021	\N	Bon
316	2	4	2	17.00	2026-03-21 21:40:49	2026-03-21 21:40:49	87	231220250022	\N	Bon
317	2	4	2	17.00	2026-03-21 21:40:49	2026-03-21 21:40:49	88	231220250023	\N	Bon
318	2	4	2	17.00	2026-03-21 21:40:49	2026-03-21 21:40:49	89	231220250024	\N	Bon
319	2	4	2	17.00	2026-03-21 21:40:49	2026-03-21 21:40:49	90	231220250025	\N	Bon
320	2	9	2	16.00	2026-03-21 21:41:20	2026-03-21 21:41:20	91	231220250026	\N	Bon
321	2	9	2	16.00	2026-03-21 21:41:20	2026-03-21 21:41:20	92	231220250027	\N	Bon
322	2	9	2	16.00	2026-03-21 21:41:20	2026-03-21 21:41:20	93	231220250028	\N	Bon
323	2	9	2	16.00	2026-03-21 21:41:20	2026-03-21 21:41:20	94	231220250029	\N	Bon
324	2	9	2	16.00	2026-03-21 21:41:20	2026-03-21 21:41:20	95	231220250030	\N	Bon
325	2	11	2	16.00	2026-03-22 23:14:36	2026-03-22 23:14:36	97	231220250032	\N	Bon
327	2	10	1	16.00	2026-05-08 22:09:58	2026-05-08 22:09:58	107	231220250031	\N	Bon
328	2	1	3	18.00	2026-05-22 14:41:42	2026-05-22 14:41:42	56	231220250001	\N	Bon
329	2	1	3	18.00	2026-05-22 14:41:42	2026-05-22 14:41:42	57	231220250002	\N	Bon
330	2	1	3	18.00	2026-05-22 14:41:42	2026-05-22 14:41:42	58	231220250003	\N	Bon
331	2	1	3	18.00	2026-05-22 14:41:42	2026-05-22 14:41:42	59	231220250004	\N	Bon
332	2	2	3	18.00	2026-05-22 14:42:23	2026-05-22 14:42:23	70	231220250005	\N	Bon
333	2	2	3	12.00	2026-05-22 14:42:23	2026-05-22 14:42:23	71	231220250006	\N	Bon
334	2	2	3	18.00	2026-05-22 14:42:23	2026-05-22 14:42:23	72	231220250007	\N	Bon
335	2	2	3	18.00	2026-05-22 14:42:23	2026-05-22 14:42:23	73	231220250008	\N	Bon
336	2	2	3	18.00	2026-05-22 14:42:23	2026-05-22 14:42:23	74	231220250009	\N	Bon
337	2	2	3	18.00	2026-05-22 14:42:23	2026-05-22 14:42:23	75	231220250010	\N	Bon
338	2	2	3	18.00	2026-05-22 14:42:23	2026-05-22 14:42:23	76	231220250011	\N	Bon
339	2	3	3	17.00	2026-05-22 14:43:01	2026-05-22 14:43:01	77	231220250012	\N	Bon
340	2	3	3	17.00	2026-05-22 14:43:01	2026-05-22 14:43:01	78	231220250013	\N	Bon
341	2	3	3	17.00	2026-05-22 14:43:01	2026-05-22 14:43:01	79	231220250014	\N	Bon
342	2	3	3	17.00	2026-05-22 14:43:01	2026-05-22 14:43:01	99	231220250033	\N	Bon
343	2	3	3	17.00	2026-05-22 14:43:01	2026-05-22 14:43:01	80	231220250015	\N	Bon
345	2	3	3	14.00	2026-05-22 14:43:01	2026-05-22 14:43:01	82	231220250017	\N	Bon
346	2	3	3	17.00	2026-05-22 14:43:01	2026-05-22 14:43:01	83	231220250018	\N	Bon
347	2	4	3	16.00	2026-05-22 14:43:32	2026-05-22 14:43:32	84	231220250019	\N	Bon
348	2	4	3	15.00	2026-05-22 14:43:32	2026-05-22 14:43:32	85	231220250020	\N	Bon
349	2	4	3	14.00	2026-05-22 14:43:32	2026-05-22 14:43:32	86	231220250021	\N	Bon
350	2	4	3	15.00	2026-05-22 14:43:32	2026-05-22 14:43:32	87	231220250022	\N	Bon
351	2	4	3	15.00	2026-05-22 14:43:32	2026-05-22 14:43:32	88	231220250023	\N	Bon
352	2	4	3	15.00	2026-05-22 14:43:32	2026-05-22 14:43:32	89	231220250024	\N	Bon
353	2	4	3	15.00	2026-05-22 14:43:32	2026-05-22 14:43:32	90	231220250025	\N	Bon
354	2	9	3	16.00	2026-05-22 14:44:00	2026-05-22 14:44:00	91	231220250026	\N	Bon
355	2	9	3	16.00	2026-05-22 14:44:00	2026-05-22 14:44:00	92	231220250027	\N	Bon
356	2	9	3	16.00	2026-05-22 14:44:00	2026-05-22 14:44:00	93	231220250028	\N	Bon
357	2	9	3	16.00	2026-05-22 14:44:00	2026-05-22 14:44:00	94	231220250029	\N	Bon
358	2	9	3	16.00	2026-05-22 14:44:00	2026-05-22 14:44:00	95	231220250030	\N	Bon
344	2	3	3	17.00	2026-05-22 14:43:01	2026-05-22 20:53:22	81	231220250016	\N	Bon
359	2	10	2	16.00	2026-05-22 21:48:31	2026-05-22 21:48:31	107	231220250031	\N	Bon
361	2	10	3	16.00	2026-06-15 22:59:55	2026-06-15 22:59:55	107	231220250031	\N	Bon
\.


--
-- Data for Name: contacts; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.contacts (id, message, nom, email, objet, created_at, updated_at) FROM stdin;
1	nnnnnnnnooooooooooopppppppppppppppppp	aaaaaaaa	gggggggggg@ggggggggggggg	nnnnnnnnnnnnnnnnnnnnnnnnn	2026-03-29 20:25:07	2026-03-29 20:25:07
2	nnnnnnnnooooooooooopppppppppppppppppp	aaaaaaaa	gggggggggg@ggggggggggggg	nnnnnnnnnnnnnnnnnnnnnnnnn	2026-03-29 20:26:57	2026-03-29 20:26:57
3	nnnnnnnnooooooooooopppppppppppppppppp	aaaaaaaa	gggggggggg@ggggggggggggg	nnnnnnnnnnnnnnnnnnnnnnnnn	2026-03-29 20:29:53	2026-03-29 20:29:53
4	Bonsoir Kolawolé j'espère que tout va bien.\r\nJe suis très heureux de t'informer dès ce soir à 23h30 ton application est accessible sur le net. Je très heureux de t'en informer. Merci beaucoup. Bonne suite des travaux..	ADEYEMI KOLAWOLÉ	kolatresor.adeyemi@gmail.com	Confirmation de connexion	2026-05-10 23:42:33	2026-05-10 23:42:33
5	Recevez les félicitations. Vôtre application marche bien.	ADEYEMI KOLAWOLÉ	kolatresor.adeyemi@gmail.com	Remerciements	2026-05-10 23:56:47	2026-05-10 23:56:47
6	merci beaucoup	ADEYEMI Jean	jean@gmail.com	bonjour	2026-05-13 00:23:38	2026-05-13 00:23:38
\.


--
-- Data for Name: cycles; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.cycles (id, nom, ordre, created_at, updated_at) FROM stdin;
1	Maternelle	1	\N	\N
2	Primaire	2	\N	\N
3	Secondaire	3	\N	\N
\.


--
-- Data for Name: depenses; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.depenses (id, date, libelle, montant, categorie, created_at, updated_at, description) FROM stdin;
6	2026-03-01	salaire	450000.00	salaires	2026-03-01 21:32:22	2026-03-01 21:32:22	salaire du mois de septembre des enseignants et du personnel administratif
7	2026-03-01	salaire	550000.00	salaires	2026-03-01 21:34:43	2026-03-01 21:34:43	salaire d'octobre des enseignants et du personnel administratif
8	2026-03-01	autres	400000.00	autres	2026-03-01 21:39:01	2026-03-01 21:39:01	a---------
9	2026-03-03	llrm,xmep	40000.00	reparation	2026-03-03 13:30:55	2026-03-03 13:30:55	sfgjlsc^cs:ù4dg\r\n7vc\r\n654+v95456+9g4gr8+r
\.


--
-- Data for Name: echeances; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.echeances (id, frais_id, classe_id, annee_id, nom, date_limite, montant, created_at, updated_at) FROM stdin;
128	8	3	2	une tranche	2025-10-30	5000.00	2026-02-26 22:22:45	2026-02-26 22:22:45
129	9	4	2	une tranche	2025-10-30	5000.00	2026-02-26 22:23:44	2026-02-26 22:23:44
130	10	9	2	une tranche	2025-10-30	5000.00	2026-02-26 22:24:46	2026-02-26 22:24:46
136	16	1	2	une tranche	2025-10-30	2000.00	2026-02-26 22:40:48	2026-02-26 22:40:48
137	17	2	2	une tranche	2025-10-30	2000.00	2026-02-26 22:41:52	2026-02-26 22:41:52
138	18	3	2	une tranche	2025-10-30	2000.00	2026-02-26 22:45:10	2026-02-26 22:45:10
139	19	4	2	une tranche	2025-10-30	2000.00	2026-02-26 22:48:10	2026-02-26 22:48:10
140	20	9	2	une tranche	2025-10-30	2000.00	2026-02-26 22:49:15	2026-02-26 22:49:15
145	26	20	2	1ère tranche	2025-10-30	25000.00	2026-05-09 23:25:20	2026-05-09 23:25:20
146	26	20	2	2ème tranche	2026-01-15	22000.00	2026-05-09 23:25:20	2026-05-09 23:25:20
147	26	20	2	3ème tranche	2026-03-30	20000.00	2026-05-09 23:25:20	2026-05-09 23:25:20
151	31	12	2	1ère tranche	2025-10-30	55000.00	2026-05-24 17:08:45	2026-05-24 17:08:45
152	31	12	2	2ème tranche	2025-11-28	40000.00	2026-05-24 17:08:45	2026-05-24 17:08:45
153	31	12	2	3ème tranche	2025-01-31	20000.00	2026-05-24 17:08:45	2026-05-24 17:08:45
154	32	18	2	1ère tranche	2025-10-31	22000.00	2026-06-01 08:21:33	2026-06-01 08:21:33
155	32	18	2	2ème tranche	2025-11-28	25000.00	2026-06-01 08:21:33	2026-06-01 08:21:33
156	32	18	2	3	2026-01-30	20000.00	2026-06-01 08:21:33	2026-06-01 08:21:33
157	33	19	2	1ère tranche	2025-10-30	22000.00	2026-06-01 08:24:52	2026-06-01 08:24:52
158	33	19	2	2ème tranche	2025-11-30	25000.00	2026-06-01 08:24:52	2026-06-01 08:24:52
159	33	19	2	3ème tranche	2026-01-30	20000.00	2026-06-01 08:24:52	2026-06-01 08:24:52
160	34	20	2	1ère tranche	2025-10-30	22000.00	2026-06-01 08:29:05	2026-06-01 08:29:05
161	34	20	2	2ème tranche	2025-11-30	20000.00	2026-06-01 08:29:05	2026-06-01 08:29:05
162	34	20	2	3ème tranche	2026-01-30	20000.00	2026-06-01 08:29:05	2026-06-01 08:29:05
163	35	21	2	1ère tranche	2025-10-30	22000.00	2026-06-01 08:40:13	2026-06-01 08:40:13
164	35	21	2	2ème tranche	2025-11-30	20000.00	2026-06-01 08:40:13	2026-06-01 08:40:13
165	35	21	2	3ème tranche	2026-01-30	20000.00	2026-06-01 08:40:13	2026-06-01 08:40:13
166	36	22	2	1ère tranche	2025-10-30	22000.00	2026-06-01 08:42:32	2026-06-01 08:42:32
167	36	22	2	2ème tranche	2025-11-30	20000.00	2026-06-01 08:42:32	2026-06-01 08:42:32
168	36	22	2	3ème tranche	2026-01-30	20000.00	2026-06-01 08:42:32	2026-06-01 08:42:32
169	37	23	2	1ère tranche	2025-10-30	22000.00	2026-06-01 08:44:34	2026-06-01 08:44:34
170	37	23	2	2ème tranche	2025-11-30	20000.00	2026-06-01 08:44:34	2026-06-01 08:44:34
171	37	23	2	3ème tranche	2026-01-30	20000.00	2026-06-01 08:44:34	2026-06-01 08:44:34
172	38	24	2	1ère tranche	2025-10-30	27000.00	2026-06-01 08:46:48	2026-06-01 08:46:48
173	38	24	2	2ème tranche	2025-11-30	20000.00	2026-06-01 08:46:48	2026-06-01 08:46:48
174	38	24	2	3ème tranche	2025-01-30	20000.00	2026-06-01 08:46:48	2026-06-01 08:46:48
175	39	25	2	1ère tranche	2025-10-30	27000.00	2026-06-01 08:48:48	2026-06-01 08:48:48
176	39	25	2	2ème tranche	2025-11-30	20000.00	2026-06-01 08:48:49	2026-06-01 08:48:49
177	39	25	2	3ème tranche	2026-01-30	20000.00	2026-06-01 08:48:49	2026-06-01 08:48:49
110	1	1	2	1ère tranche	2025-10-30	35000.00	2026-02-26 21:16:28	2026-02-26 21:16:28
111	1	1	2	2ème tranche	2025-11-30	25000.00	2026-02-26 21:16:28	2026-02-26 21:16:28
112	1	1	2	3ème tranche	2026-01-30	20000.00	2026-02-26 21:16:28	2026-02-26 21:16:28
113	2	2	2	1ère tranche	2025-10-30	35000.00	2026-02-26 21:21:10	2026-02-26 21:21:10
114	2	2	2	2ème tranche	2025-11-30	25000.00	2026-02-26 21:21:10	2026-02-26 21:21:10
115	2	2	2	3ème tranche	2026-01-30	20000.00	2026-02-26 21:21:10	2026-02-26 21:21:10
117	3	3	2	1ère tranche	2025-10-30	40000.00	2026-02-26 21:28:53	2026-02-26 21:28:53
118	3	3	2	2ème tranche	2025-11-30	30000.00	2026-02-26 21:28:53	2026-02-26 21:28:53
119	3	3	2	3ème tranche	2026-01-30	20000.00	2026-02-26 21:28:53	2026-02-26 21:28:53
120	4	4	2	1ère tranche	2025-10-30	40000.00	2026-02-26 21:31:00	2026-02-26 21:31:00
121	4	4	2	2ème tranche	2025-11-30	30000.00	2026-02-26 21:31:00	2026-02-26 21:31:00
122	4	4	2	3ème tranche	2026-01-30	20000.00	2026-02-26 21:31:00	2026-02-26 21:31:00
123	5	9	2	1ère tranche	2025-10-30	55000.00	2026-02-26 21:37:45	2026-02-26 21:37:45
124	5	9	2	2ème tranche	2025-11-30	35000.00	2026-02-26 21:37:45	2026-02-26 21:37:45
125	5	9	2	3ème tranche	2026-01-30	20000.00	2026-02-26 21:37:45	2026-02-26 21:37:45
126	6	1	2	une tranche	2025-10-30	5000.00	2026-02-26 21:39:55	2026-02-26 21:39:55
127	7	2	2	une tranche	2025-10-30	5000.00	2026-02-26 21:41:10	2026-02-26 21:41:10
207	69	15	2	1ère tranche	2025-10-30	70000.00	2026-06-01 09:42:56	2026-06-01 09:42:56
208	69	15	2	2ème tranche	2025-11-30	40000.00	2026-06-01 09:42:56	2026-06-01 09:42:56
209	69	15	2	3ème tranche	2026-01-30	20000.00	2026-06-01 09:42:56	2026-06-01 09:42:56
216	72	10	2	1ère tranche	2025-10-30	65000.00	2026-06-01 09:51:14	2026-06-01 09:51:14
217	72	10	2	2ème tranche	2025-11-30	40000.00	2026-06-01 09:51:14	2026-06-01 09:51:14
218	72	10	2	3ème tranche	2026-01-30	20000.00	2026-06-01 09:51:14	2026-06-01 09:51:14
220	74	12	5	une tranche	2026-10-30	2500.00	2026-06-05 13:17:28	2026-06-05 13:17:28
224	78	9	5	une tranche	2026-10-30	2500.00	2026-06-05 13:28:31	2026-06-05 13:28:31
226	80	11	5	1ère tranche	2026-10-30	60000.00	2026-06-05 13:33:01	2026-06-05 13:33:01
227	80	11	5	2ème tranche	2026-11-30	40000.00	2026-06-05 13:33:01	2026-06-05 13:33:01
228	80	11	5	3ème tranche	2027-01-30	20000.00	2026-06-05 13:33:01	2026-06-05 13:33:01
230	82	11	5	une tranche	2026-10-30	2500.00	2026-06-05 13:39:35	2026-06-05 13:39:35
233	85	10	5	1ère tranche	2026-11-30	60000.00	2026-06-05 13:46:41	2026-06-05 13:46:41
234	85	10	5	2ème tranche	2026-11-30	40000.00	2026-06-05 13:46:41	2026-06-05 13:46:41
235	85	10	5	3ème tranche	2027-01-30	20000.00	2026-06-05 13:46:41	2026-06-05 13:46:41
237	87	10	5	une tranche	2026-10-30	2500.00	2026-06-05 13:49:16	2026-06-05 13:49:16
244	92	15	5	une tranche	2026-10-30	2500.00	2026-06-05 13:58:03	2026-06-05 13:58:03
247	95	16	5	1ère tranche	2026-10-30	70000.00	2026-06-05 14:03:23	2026-06-05 14:03:23
248	95	16	5	2ème tranche	2026-11-30	40000.00	2026-06-05 14:03:23	2026-06-05 14:03:23
249	95	16	5	3ème tranche	2027-01-30	20000.00	2026-06-05 14:03:23	2026-06-05 14:03:23
251	97	16	5	une tranche	2026-10-30	2500.00	2026-06-05 14:05:38	2026-06-05 14:05:38
262	106	1	5	une tranche	2026-10-30	2500.00	2026-06-05 15:44:21	2026-06-05 15:44:21
263	107	2	5	une tranche	2026-10-30	6500.00	2026-06-05 15:45:50	2026-06-05 15:45:50
264	108	3	5	une tranche	2026-10-30	2500.00	2026-06-05 15:47:06	2026-06-05 15:47:06
265	109	4	5	une tranche	2026-10-30	2500.00	2026-06-05 15:48:14	2026-06-05 15:48:14
266	56	18	2	une tranche	2025-10-30	1500.00	2026-07-06 16:10:54	2026-07-06 16:10:54
267	57	19	2	une tranche	2025-10-30	1500.00	2026-07-06 16:11:34	2026-07-06 16:11:34
268	98	16	5	une tranche	2026-10-30	2000.00	2026-07-06 16:13:51	2026-07-06 16:13:51
269	58	20	2	une tranche	2025-10-30	1500.00	2026-07-06 16:15:19	2026-07-06 16:15:19
272	83	11	5	une tranche	2026-10-30	2000.00	2026-07-06 16:22:55	2026-07-06 16:22:55
273	93	15	5	une tranche	2026-10-30	2000.00	2026-07-06 16:23:11	2026-07-06 16:23:11
274	96	16	5	une tranche	2026-10-30	5000.00	2026-07-06 16:23:27	2026-07-06 16:23:27
275	75	12	5	une tranche	2026-10-30	2000.00	2026-07-06 16:23:55	2026-07-06 16:23:55
276	73	12	5	une tranche	2026-10-30	5000.00	2026-07-06 16:24:16	2026-07-06 16:24:16
277	25	18	2	une tranche	2025-10-30	2000.00	2026-07-06 16:24:45	2026-07-06 16:24:45
278	59	21	2	une tranche	2025-10-30	1500.00	2026-07-06 16:25:54	2026-07-06 16:25:54
279	60	22	2	une tranche	2025-10-30	1500.00	2026-07-06 16:26:11	2026-07-06 16:26:11
280	61	23	2	une tranche	2025-10-30	1500.00	2026-07-06 16:26:33	2026-07-06 16:26:33
281	62	24	2	une tranche	2025-10-30	1500.00	2026-07-06 16:27:02	2026-07-06 16:27:02
282	63	25	2	une tranche	2025-10-30	1500.00	2026-07-06 16:27:21	2026-07-06 16:27:21
284	64	15	2	une tranche	2025-10-30	5000.00	2026-07-06 16:28:24	2026-07-06 16:28:24
285	66	11	2	une tranche	2025-10-30	5000.00	2026-07-06 16:28:51	2026-07-06 16:28:51
286	67	10	2	une tranche	2025-10-30	5000.00	2026-07-06 16:29:15	2026-07-06 16:29:15
287	68	12	2	une tranche	2025-10-30	5000.00	2026-07-06 16:29:35	2026-07-06 16:29:35
288	86	10	5	une tranche	2026-10-30	5000.00	2026-07-06 16:30:07	2026-07-06 16:30:07
289	88	10	5	une tranche	2026-10-30	2000.00	2026-07-06 16:30:38	2026-07-06 16:30:38
290	40	18	2	une tranche	2025-10-30	2000.00	2026-07-07 22:44:05	2026-07-07 22:44:05
291	48	18	2	une tranche	2025-10-30	5500.00	2026-07-07 22:44:50	2026-07-07 22:44:50
292	41	19	2	une tranche	2025-01-30	2000.00	2026-07-07 22:47:50	2026-07-07 22:47:50
293	49	19	5	une tranche	2025-10-30	5500.00	2026-07-07 22:49:11	2026-07-07 22:49:11
294	110	18	5	une tranche	2026-10-30	2000.00	2026-07-07 22:53:18	2026-07-07 22:53:18
295	111	18	5	une tranche	2026-10-30	6000.00	2026-07-07 22:55:54	2026-07-07 22:55:54
296	112	19	5	une tranche	2026-10-30	2000.00	2026-07-07 22:58:09	2026-07-07 22:58:09
298	113	19	5	une tranche	2026-10-30	2000.00	2026-07-07 23:04:52	2026-07-07 23:04:52
299	42	20	5	une tranche	2025-10-30	2000.00	2026-07-07 23:06:52	2026-07-07 23:06:52
300	50	20	5	une tranche	2025-10-30	5500.00	2026-07-07 23:07:46	2026-07-07 23:07:46
302	114	20	5	une tranche	2026-10-30	2000.00	2026-07-07 23:10:48	2026-07-07 23:10:48
303	43	21	5	une tranche	2025-10-30	2000.00	2026-07-07 23:11:58	2026-07-07 23:11:58
304	51	21	5	une tranche	2025-10-30	5500.00	2026-07-07 23:12:45	2026-07-07 23:12:45
305	115	21	5	une tranche	2026-10-30	2000.00	2026-07-07 23:14:37	2026-07-07 23:14:37
306	44	22	5	une tranche	2025-10-30	2000.00	2026-07-07 23:15:37	2026-07-07 23:15:37
307	52	22	5	une tranche	2025-10-30	5500.00	2026-07-07 23:16:29	2026-07-07 23:16:29
309	116	22	5	une tranche	2026-10-30	2000.00	2026-07-07 23:19:11	2026-07-07 23:19:11
310	45	23	5	une tranche	2025-10-30	2000.00	2026-07-07 23:20:36	2026-07-07 23:20:36
312	53	23	5	une tranche	2025-10-30	6000.00	2026-07-07 23:22:24	2026-07-07 23:22:24
313	117	23	5	une tranche	2026-10-30	2000.00	2026-07-07 23:23:48	2026-07-07 23:23:48
314	46	24	5	une tranche	2025-10-30	2000.00	2026-07-07 23:28:31	2026-07-07 23:28:31
315	54	24	5	une tranche	0005-10-30	6000.00	2026-07-07 23:29:24	2026-07-07 23:29:24
316	118	24	5	une tranche	2026-10-30	2000.00	2026-07-07 23:31:04	2026-07-07 23:31:04
317	47	25	5	une tranche	2025-10-30	2000.00	2026-07-07 23:32:17	2026-07-07 23:32:17
318	55	25	5	une tranche	2025-10-30	6000.00	2026-07-07 23:35:14	2026-07-07 23:35:14
319	119	25	5	une tranche	2026-10-30	2000.00	2026-07-07 23:37:33	2026-07-07 23:37:33
320	102	1	5	une tranche	2026-10-30	7000.00	2026-07-07 23:42:18	2026-07-07 23:42:18
321	103	2	5	une tranche	2026-10-30	7000.00	2026-07-07 23:44:24	2026-07-07 23:44:24
322	104	3	5	une tranche	2026-10-30	7000.00	2026-07-07 23:45:03	2026-07-07 23:45:03
323	105	4	5	une tranche	2026-10-30	7000.00	2026-07-07 23:45:50	2026-07-07 23:45:50
324	100	9	5	une tranche	2026-10-30	7000.00	2026-07-07 23:46:43	2026-07-07 23:46:43
325	89	10	5	une tranche	2026-10-30	7000.00	2026-07-07 23:47:26	2026-07-07 23:47:26
326	99	16	5	une tranche	2026-10-30	7000.00	2026-07-07 23:48:31	2026-07-07 23:48:31
327	76	12	5	une tranche	2026-10-30	7000.00	2026-07-07 23:49:41	2026-07-07 23:49:41
328	84	11	5	une tranche	2026-10-30	7000.00	2026-07-07 23:50:15	2026-07-07 23:50:15
329	94	15	5	une tranche	2026-10-30	7000.00	2026-07-07 23:55:39	2026-07-07 23:55:39
\.


--
-- Data for Name: eleves; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.eleves (id, nom, prenom, date_naissance, sexe, nationalite, lieu_naissance, matricule, classe_id, statut, annee_id, created_at, updated_at, paren_id, numero_ordre, numeducmaster, photo) FROM stdin;
3	SAGBOHAN	Maël Jean-Eudes	2013-05-13	M	BENINOISE	Cotonou	231220250003	1	passant	2	2026-02-01 23:11:08	2026-05-08 21:33:22	35	\N	1131024044353	photos_eleves/WdlJZOCuRKpnTrn74CDPja1CcpdVKi5S3YOVbEy0.png
45	DIENE	Fadil Olohoun Tobi	2010-09-25	M	Béninoise	Cotonou	231220250033	3	passant	2	2026-02-08 00:06:00	2026-05-08 21:33:21	\N	\N	1100323055950	photos_eleves/GZb4zRFjeaPno6UBgbdF2Hmjfq2A6AsFledcqeUN.png
23	AYABA	Ibtihaj Akorédé Ayinla	2012-02-24	M	BENIN	COTONOU	231220250013	3	passant	2	2026-02-02 00:16:51	2026-05-08 21:33:21	34	\N	1120823025485	photos_eleves/THEoVvNRS0eFGfOh3EH52O7jQpCXSF7Gkjpt95Ic.png
28	SOUNOUVOU	Merry-Life Séphora Egnonnam	2012-11-04	F	BENIN	GODOMEY	231220250018	3	passant	2	2026-02-02 00:16:51	2026-05-08 21:33:22	48	\N	2120822115920	photos_eleves/OeZVrD6OskVJ9upHUiae0tFMRd6pskjXiyRVOH7t.png
2	AYABA	Amal Folachadé Abêdjê	2014-12-14	F	BENINOISE	Cotonou	231220250002	1	passant	2	2026-02-01 23:11:08	2026-05-08 21:33:22	34	\N	2140823015463	photos_eleves/9NeeIcQq7iTgBiu5ACqF3ebmRGqlOHL77FsGFjnP.png
25	GANDONOU	Manassé	2013-07-08	M	BENIN	COTONOU	231220250015	3	passant	2	2026-02-02 00:16:51	2026-05-08 21:33:21	46	\N	1130823050183	photos_eleves/dE5RCySakQpyHqZGJUbMnDpUXV9S4lYaqIvVOVW1.png
24	BOUBACAR	Mohamed	2009-05-23	M	NIGER	NIAMEY	231220250014	3	redoublant	2	2026-02-02 00:16:51	2026-05-08 21:33:22	45	\N	109110124605	photos_eleves/omfoWzajqJxF9GWQi3bhgSdS7CxQyiYxruYe47TB.png
22	ADEYEMI	Grâces Ibukun-Oluwa Précieuse	2013-05-29	F	BENIN	COTONOU	231220250012	3	passant	2	2026-02-02 00:16:51	2026-05-08 21:33:22	44	\N	2130823033608	photos_eleves/a2PFnT39HBv1XuQLRnN4C57mO4hxR2ADtCSwWgXU.png
31	BONOU	Prince Emmanuel Verace	2012-05-15	M	BENIN	COTONOU	231220250021	4	passant	2	2026-02-02 00:17:28	2026-04-14 22:08:28	51	\N	112110153810	photos_eleves/rBeoAur2M4dz3sfPApKjnV6mpzS3NzN7fz1aJPEc.png
33	OLAAFA	Ayomidé Toholath	2011-08-27	F	BENIN	BANIGBE	231220250023	4	passant	2	2026-02-02 00:17:28	2026-04-14 22:08:28	52	\N	211110135079	photos_eleves/U4AiCG4QtuowPDFFBh22M1sLbUJKUGjG8CTHHNCu.png
32	LADOKOU	Fatihiyath Modji	2011-06-01	F	BENIN	COTONOU	231220250022	4	passant	2	2026-02-02 00:17:28	2026-04-14 22:16:30	42	\N	211110156186	photos_eleves/mEWv4rGpIEObbLpxhVvYuj8NopMkyyENZ1gEPdVH.png
34	OLAAFA	Timilèyin Toholou	2011-08-27	F	BENIN	BANIGBE	231220250024	4	passant	2	2026-02-02 00:17:28	2026-04-14 22:08:28	52	\N	211110135080	photos_eleves/8hhlCbNSBXWWWJH7aLDX7VzXEmIGgZJvEn1Zlu4C.png
15	AHOLODE	Marie Lucie Renée	2013-10-19	F	BENINOISE	COTONOU	231220250005	2	passant	2	2026-02-02 00:15:29	2026-05-08 21:33:21	37	\N	2130823015015	photos_eleves/rc5K1yWn6uiUh1L4awX8QCA0OI9T5FSl9S6RUnZX.png
39	OHOUSSOU	Kébo Franck Ulrich	2010-09-04	M	BENIN	COTONOU	231220250029	9	passant	2	2026-02-02 00:18:13	2026-05-31 21:57:05	54	\N	110110144111	photos_eleves/PSQ8IqZ6ilPXodvDFKMDdg0Rut3ar8P7yS4lOaud.jpg
21	YESSOUFOU	Andilath Okpèyèmi	2014-10-13	F	BENINOISE	COTONOU	231220250011	2	passant	2	2026-02-02 00:15:29	2026-05-08 21:33:21	43	\N	2141023039853	photos_eleves/G41PkvTxzy54k9J4VV1xcZg67SUlzOWfospdlapW.png
18	GBESSEHOUN	Oluwa Tobi Jean Jorès	2008-01-04	M	BENINOISE	SEKOU	231220250008	2	redoublant	2	2026-02-02 00:15:29	2026-05-08 21:33:21	40	\N	108120272547	photos_eleves/MFqYgnIotIesy8VCqgGySA9fwRnYG2x2UEueet8N.png
40	SOUNOUVOU	Josué Adonaï Othniel Babatoundé	2010-08-24	M	BENIN	COTONOU	231220250030	9	passant	2	2026-02-02 00:18:13	2026-05-22 17:39:52	48	\N	110110145347	photos_eleves/4MsY3Ayc30jlVlKFGikKAJ7AeKeXKLqgwh9n7LJj.png
36	GBAMIGBOLA	Feyishola Olamidé Dorifeine	2009-12-19	F	BENIN	COTONOU	231220250026	9	passant	2	2026-02-02 00:18:13	2026-05-22 17:39:52	39	\N	109110130406	photos_eleves/k521ySmzC4h7WeavjdXIjfJZbzW5Nll6O55UfAEK.png
19	KALU	John Chanceux	2013-01-04	M	BENINOISE	COTONOU	231220250009	2	passant	2	2026-02-02 00:15:29	2026-05-08 21:33:21	41	\N	1130822150681	photos_eleves/McLOpum3NepsfDt0eoSESW7HYoi4GQaadk2oQsaT.png
37	HOUNDEWAGNON	Salim Mohamed	2008-01-14	M	BENIN	COTONOU	231220250027	9	redoublant	2	2026-02-02 00:18:13	2026-05-22 17:39:52	53	\N	108110143155	photos_eleves/5GbxtTV91eQXgViuGJN67TQj6QpgiJmSM4p0hLT4.png
16	DIAKITE	Mohamed Zoumana	2012-05-11	M	BENINOISE	COTONOU	231220250006	2	redoublant	2	2026-02-02 00:15:29	2026-05-08 21:33:21	38	\N	112110160595	photos_eleves/2JwJ0V5Om9c8iRsT4fXkX6bwf3nCPSfaku6YUovZ.png
53	DEKA-JAMES	Israël Précieux Yisségnon	2011-05-08	M	Béninoise	Cotonou	231220250031	10	passant	2	2026-05-08 21:50:36	2026-05-22 17:39:52	\N	\N	109110110149	photos_eleves/xr1BxCLZxp93WlDRXViQCixW4cVW849HpJ3RuFZ3.png
26	GNONLONFOUN	Miracle Dossi Givania	2012-09-18	F	BENIN	COTONOU	231220250016	3	passant	2	2026-02-02 00:16:51	2026-05-08 21:33:22	47	\N	2120823045845	photos_eleves/KHlWTPKBbsLcbbbRwI2d6qG5ovKufCqbsKJbvmcy.png
35	TAIWO	Sabiqath Adetoutou Akanke Olaitan	2012-04-11	F	BENIN	COTONOU	231220250025	4	passant	2	2026-02-02 00:17:28	2026-04-14 22:08:28	36	\N	212110158308	photos_eleves/3vHWC3pccBhp7TQAbAxn47E8hGbd7VD1fKNMsrzc.png
30	AHOUANSE	Marie-Eudoxie	2012-07-31	F	BENIN	COTONOU	231220250020	4	passant	2	2026-02-02 00:17:28	2026-04-14 21:36:20	50	\N	212110157505	photos_eleves/u1Tl2nbhfXPN4gAXV9avL3etKgsEfZvGv5V52Fg8.png
29	AGBOZINGBA	Sèdolo Koffi Oswald	2011-10-23	M	BENIN	COTONOU	231220250019	4	passant	2	2026-02-02 00:17:28	2026-04-14 22:08:28	49	\N	111110156764	photos_eleves/im1n13uz5CoyYi4oavSHbriuUzqA8j2kS6UoLoOb.png
27	KALU	Samuel Miséricorde	2011-11-05	M	BENIN	COTONOU	231220250017	3	passant	2	2026-02-02 00:16:51	2026-05-08 21:33:21	41	\N	111110156775	photos_eleves/KwV9fqgy0xR4cfmknG2shcszObvzRDyRjcfxAio4.png
1	AGOKPINZIN	Trinité Sènakpon	2012-05-28	M	BENINOISE	Ayélawadjè	231220250001	1	passant	2	2026-02-01 23:11:08	2026-05-08 21:33:22	33	\N	1120423731120	photos_eleves/0wA9N012qlrmtuwtUH0Q08ZYsALCfEWBu11nVoaq.png
4	TAIWO	Siddiquoth Olarewadjou Atinouke Abeni	2015-01-03	F	BENINOISE	Cotonou	231220250004	1	passant	2	2026-02-01 23:11:08	2026-05-08 21:33:22	36	\N	2150823999826	photos_eleves/tKToZuPBi7ZyeKx7uSg8qcfsOQd8u8Ziphfx1Z8h.png
20	LADOKOU	Tchegoun Amir	2014-10-20	M	BENINOISE	COTONOU	231220250010	2	passant	2	2026-02-02 00:15:29	2026-05-08 21:33:22	42	\N	2130823017333	photos_eleves/zVr87TN8dKAzMb6SllyVWkcpj7UJoiNzrnlZOOTY.png
17	GBAMIGBOLA	Koladé Fadel Sourou	2013-06-05	M	BENINOISE	COTONOU	231220250007	2	passant	2	2026-02-02 00:15:29	2026-05-08 21:33:22	39	\N	1130823604185	photos_eleves/YBow5C1uMj0tss90YgTv7lwuXrC3GLrJjkCkqHYK.png
42	YESSOUFOU	Kafilath Téniola	2010-11-12	F	BENIN	COTONOU	231220250032	11	passant	2	2026-02-02 00:19:03	2026-05-22 17:39:52	43	\N	210090224751	photos_eleves/i9Ywnc9NgKeguQ3QS45PiqtNwExAt22v1rexepsP.png
38	KALU	Daniella Houeffa Exauce	2010-07-24	F	BENIN	COTONOU	231220250028	9	passant	2	2026-02-02 00:18:13	2026-05-22 17:39:52	41	\N	210110130622	photos_eleves/0sotKv8q1KgKToQlNmIBqE4th1OHAFX6wa3EdAb0.png
\.


--
-- Data for Name: enseignants; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.enseignants (id, nom, prenom, date_naissance, sexe, adresse, telephone, email, matricule, created_at, updated_at, specialite, grade, date_embauche, statut, matiere_id, cycle_id) FROM stdin;
6	AGOSSOU	FERDINAND	2002-05-02	M	COTONOU	196022545	agoss@gmail.com	LGE-2026-QWIM	2026-04-27 03:24:27	2026-04-27 03:24:27	svt	\N	\N	actif	7	3
\.


--
-- Data for Name: epreuves; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.epreuves (id, examen_blanc_id, matiere_id, date, heure_debut, heure_fin, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: examen_blanc_classe; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.examen_blanc_classe (id, examen_blanc_id, classe_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: examen_blancs; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.examen_blancs (id, type, annee_id, date_debut, date_fin, created_at, updated_at, inscription_id, classe_id) FROM stdin;
33	BEPC	2	2026-05-14	2026-05-19	2026-05-14 13:13:12	2026-05-14 13:13:12	\N	\N
35	BEPC	2	2026-06-26	2026-06-29	2026-06-26 23:28:07	2026-06-26 23:28:07	\N	\N
\.


--
-- Data for Name: examen_classes; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.examen_classes (id, examen_blanc_id, classe_id, created_at, updated_at) FROM stdin;
10	33	4	\N	\N
12	35	4	\N	\N
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: finances; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.finances (id, recette_id, depense_id, solde, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: frais; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.frais (id, nom, description, created_at, updated_at) FROM stdin;
9	frais d'inscription	Frais d'inscription de la classe de 3ème	2026-02-26 22:23:44	2026-02-26 22:23:44
10	frais d'inscription	Frais d'inscription de la classe de 2ndeD	2026-02-26 22:24:46	2026-02-26 22:24:46
1	scolarite	Frais de Scolarité de la classe de 6ème	2026-02-26 21:16:28	2026-02-26 21:16:28
2	scolarite	Frais de Scolarité de la classe de 5ème	2026-02-26 21:21:10	2026-02-26 21:21:10
3	scolarite	Frais de Scolarité de la classe de 4ème	2026-02-26 21:27:17	2026-02-26 21:27:17
4	scolarite	Frais de Scolarité de la classe de 3ème	2026-02-26 21:31:00	2026-02-26 21:31:00
5	scolarite	Frais de Scolarité de la classe de 2ndeD	2026-02-26 21:37:45	2026-02-26 21:37:45
6	frais d'inscription	Frais d'inscription de la classe de 6ème	2026-02-26 21:39:55	2026-02-26 21:39:55
7	frais d'inscription	Frais d'inscription de la classe de 5ème	2026-02-26 21:41:10	2026-02-26 21:41:10
8	frais d'inscription	Frais d'inscription de la classe de 4ème	2026-02-26 22:22:45	2026-02-26 22:22:45
16	frais tenue de sport	Frais de Tenue de Sport de la classe de 6ème	2026-02-26 22:40:48	2026-02-26 22:40:48
17	frais tenue de sport	Frais de Tenue de Sport de la classe de 5ème	2026-02-26 22:41:51	2026-02-26 22:41:51
18	frais tenue de sport	Frais de Tenue de Sport de la classe de 4ème	2026-02-26 22:45:10	2026-02-26 22:45:10
19	frais tenue de sport	Frais de Tenue de Sport de la classe de 3ème	2026-02-26 22:48:10	2026-02-26 22:48:10
20	frais tenue de sport	Frais de Tenue de Sport de la classe de 2ndeD	2026-02-26 22:49:15	2026-02-26 22:49:15
26	scolarite	Frais de Scolarité de la classe de CI	2026-05-09 23:25:20	2026-05-09 23:25:20
31	scolarite	Frais de Scolarité de la classe de 2ndeC	2026-05-24 17:08:45	2026-05-24 17:08:45
32	scolarite	Frais de Scolarité de la classe de Maternelle	2026-06-01 08:21:33	2026-06-01 08:21:33
33	scolarite	Frais de Scolarité de la classe de Maternelle2	2026-06-01 08:24:52	2026-06-01 08:24:52
34	scolarite	Frais de Scolarité de la classe de CI	2026-06-01 08:29:05	2026-06-01 08:29:05
35	scolarite	Frais de Scolarité de la classe de CP	2026-06-01 08:40:13	2026-06-01 08:40:13
36	scolarite	Frais de Scolarité de la classe de CE1	2026-06-01 08:42:32	2026-06-01 08:42:32
37	scolarite	Frais de Scolarité de la classe de CE2	2026-06-01 08:44:34	2026-06-01 08:44:34
38	scolarite	Frais de Scolarité de la classe de CM1	2026-06-01 08:46:48	2026-06-01 08:46:48
39	scolarite	Frais de Scolarité de la classe de CM2	2026-06-01 08:48:48	2026-06-01 08:48:48
48	uniforme	Frais de Tissus Uniforme de la Classe de Maternelle1	2026-06-01 09:02:22	2026-06-01 09:02:22
49	uniforme	Frais de Tissus Uniforme de la Classe de Maternelle2	2026-06-01 09:04:23	2026-06-01 09:04:23
50	uniforme	Frais de Tissus Uniforme de la Classe de CI	2026-06-01 09:05:51	2026-06-01 09:05:51
51	uniforme	Frais de Tissus Uniforme de la Classe de CP	2026-06-01 09:07:03	2026-06-01 09:07:03
52	uniforme	Frais de Tissus Uniforme de la Classe de CE1	2026-06-01 09:08:26	2026-06-01 09:08:26
53	uniforme	Frais de Tissus Uniforme de la Classe de CE2	2026-06-01 09:09:51	2026-06-01 09:09:51
54	uniforme	Frais de Tissus Uniforme de la Classe de CM1	2026-06-01 09:11:01	2026-06-01 09:11:01
55	uniforme	Frais de Tissus Uniforme de la Classe de CM2	2026-06-01 09:12:26	2026-06-01 09:12:26
69	scolarite	Frais de Scolarité de la classe de TleC	2026-06-01 09:42:56	2026-06-01 09:42:56
72	scolarite	Frais de Scolarité de la classe de 1èreD	2026-06-01 09:51:14	2026-06-01 09:51:14
74	lacoste	Frais de Lacoste Uniforme de la Classe de 2nde C	2026-06-05 13:17:28	2026-06-05 13:17:28
76	uniforme	Frais de Tissus Uniforme de la Classe de 2ndeC	2026-06-05 13:20:33	2026-06-05 13:20:33
78	lacoste	Frais de Lacoste Uniforme de la Classe de 2ndeD	2026-06-05 13:28:31	2026-06-05 13:28:31
80	scolarite	Frais de Scolarité de la classe de 1èreC	2026-06-05 13:33:01	2026-06-05 13:33:01
82	lacoste	Frais de Lacoste Uniforme de la Classe de 1èreC	2026-06-05 13:39:35	2026-06-05 13:39:35
84	uniforme	Frais de Tissus Uniforme de la Classe de 1èreC	2026-06-05 13:43:12	2026-06-05 13:43:12
85	scolarite	Frais de Scolarité de la classe de 1èreD	2026-06-05 13:46:41	2026-06-05 13:46:41
87	lacoste	Frais de Lacoste Uniforme de la Classe de 1èreD	2026-06-05 13:49:16	2026-06-05 13:49:16
58	tenue de sport	Frais de Tenue de Sport de la classe de CI	2026-06-01 09:16:47	2026-07-06 16:15:19
83	tenue de sport	Frais de Tenue de Sport de la classe de 1èreC	2026-06-05 13:41:09	2026-07-06 16:22:55
75	tenue de sport	Frais de Tenue de Sport de la classe de 2ndeC	2026-06-05 13:18:58	2026-07-06 16:23:55
73	frais de inscription	Frais d'inscription de la classe de 2nde C	2026-06-05 13:15:51	2026-07-06 16:24:16
25	frais d'inscription	Frais d'inscription de la classe de Maternelle1	2026-04-11 20:52:11	2026-07-06 16:24:45
59	tenue de sport	Frais de Tenue de Sport de la classe de CP	2026-06-01 09:18:06	2026-07-06 16:25:54
60	tenue de sport	Frais de Tenue de Sport de la classe de CE1	2026-06-01 09:20:42	2026-07-06 16:26:11
61	tenue de sport	Frais de Tenue de Sport de la classe de CE2	2026-06-01 09:22:04	2026-07-06 16:26:33
62	tenue de sport	Frais de Tenue de Sport de la classe de CM1	2026-06-01 09:23:20	2026-07-06 16:27:02
63	tenue de sport	Frais de Tenue de Sport de la classe de CM2	2026-06-01 09:24:35	2026-07-06 16:27:21
64	frais d'inscription	Frais d'inscription de la classe de TleC	2026-06-01 09:29:39	2026-07-06 16:28:24
66	frais d'inscription	Frais d'inscription de la classe de 1ère	2026-06-01 09:36:31	2026-07-06 16:28:51
67	frais d'reinscription	Frais d'inscription de la classe de 1ère D	2026-06-01 09:37:56	2026-07-06 16:29:15
86	frais d'inscription	Frais d'inscription de la classe de 1èreD	2026-06-05 13:48:01	2026-07-06 16:30:07
88	tenue de sport	Frais de Tenue de Sport de la classe de 1èreD	2026-06-05 13:50:58	2026-07-06 16:30:38
40	Lacoste uniforme	Frais de Lacoste Uniforme de la Classe de Maternelle1	2026-06-01 08:53:57	2026-07-07 22:44:05
41	Lacoste uniforme	Frais de Lacoste Uniforme de la Classe de Maternelle2	2026-06-01 08:54:55	2026-07-07 22:47:50
42	lacoste	Frais de Lacoste Uniforme de la Classe de CI	2026-06-01 08:55:59	2026-07-07 23:06:52
43	lacoste	Frais de Lacoste Uniforme de la Classe de CP	2026-06-01 08:56:55	2026-07-07 23:11:58
44	lacoste	Frais de Lacoste Uniforme de la Classe de CE1	2026-06-01 08:57:46	2026-07-07 23:15:37
45	lacoste	Frais de Lacoste Uniforme de la Classe de CE2	2026-06-01 08:58:35	2026-07-07 23:20:36
46	lacoste	Frais de Lacoste Uniforme de la Classe de CM1	2026-06-01 08:59:31	2026-07-07 23:28:31
47	lacoste	Frais de Lacoste Uniforme de la Classe de CM2	2026-06-01 09:00:16	2026-07-07 23:32:17
89	uniforme	Frais de Tissus Uniforme de la Classe de 1èreD	2026-06-05 13:52:16	2026-06-05 13:52:16
92	lacoste	Frais de Lacoste Uniforme de la Classe de TleC	2026-06-05 13:58:03	2026-06-05 13:58:03
94	uniforme	Frais de Tissus Uniforme de la Classe de TleC	2026-06-05 14:01:01	2026-06-05 14:01:01
95	scolarite	Frais de Scolarité de la classe de TleD	2026-06-05 14:03:23	2026-06-05 14:03:23
97	lacoste	Frais de Lacoste Uniforme de la Classe de TleD	2026-06-05 14:05:38	2026-06-05 14:05:38
99	uniforme	Frais de Tissus Uniforme de la Classe de TleD	2026-06-05 14:08:47	2026-06-05 14:08:47
100	uniforme	Frais de Tissus Uniforme de la Classe de 2ndeD	2026-06-05 15:18:24	2026-06-05 15:18:24
102	uniforme	Frais de Tissus Uniforme de la Classe de 6eme	2026-06-05 15:39:22	2026-06-05 15:39:22
103	uniforme	Frais de Tissus Uniforme de la Classe de 5eme	2026-06-05 15:40:37	2026-06-05 15:40:37
104	uniforme	Frais de Tissus Uniforme de la Classe de 4eme	2026-06-05 15:41:45	2026-06-05 15:41:45
105	uniforme	Frais de Tissus Uniforme de la Classe de 3eme	2026-06-05 15:43:13	2026-06-05 15:43:13
106	lacoste	Frais de Lacoste Uniforme de la Classe de 6eme	2026-06-05 15:44:21	2026-06-05 15:44:21
107	lacoste	Frais de Lacoste Uniforme de la Classe de 5eme	2026-06-05 15:45:50	2026-06-05 15:45:50
108	lacoste	Frais de Lacoste Uniforme de la Classe de 4ème	2026-06-05 15:47:06	2026-06-05 15:47:06
109	lacoste	Frais de Lacoste Uniforme de la Classe de 3eme	2026-06-05 15:48:14	2026-06-05 15:48:14
56	tenue de sport	Frais de Tenue de Sport de la classe de Maternelle1	2026-06-01 09:13:50	2026-07-06 16:10:54
57	tenue de sport	Frais de Tenue de Sport de la classe de Maternelle2	2026-06-01 09:15:21	2026-07-06 16:11:34
98	tenue de sport	Frais de Tenue de Sport de la classe de TleD	2026-06-05 14:07:09	2026-07-06 16:13:51
93	tenue de sport	Frais de Tenue de Sport de la classe de TleC	2026-06-05 13:59:30	2026-07-06 16:23:11
96	frais de inscription	Frais d'inscription de la classe de TleD	2026-06-05 14:04:20	2026-07-06 16:23:27
68	frais d'inscription	Frais d'inscription de la classe de 2nde C	2026-06-01 09:39:51	2026-07-06 16:29:35
110	lacoste	Frais Lacoste uniforme de la maternelle 1	2026-07-07 22:53:18	2026-07-07 22:53:18
111	uniforme	Frais uniforme de la classe de maternelle 1	2026-07-07 22:55:54	2026-07-07 22:55:54
112	lacoste	Frais Lacoste uniforme de la classe de maternelle 2	2026-07-07 22:58:09	2026-07-07 22:58:09
113	frais d'inscription	FRAIS D'INSCRIPTION de la classe de maternelle2	2026-07-07 23:03:11	2026-07-07 23:04:52
114	frais d'inscription	FRAIS D'INSCRIPTION de la classe de CI	2026-07-07 23:09:38	2026-07-07 23:10:48
115	frais d'inscription	FRAIS D'INSCRIPTION de la classe de CP	2026-07-07 23:14:37	2026-07-07 23:14:37
116	frais d'inscription	FRAIS D'INSCRIPTION de la classe de CE1	2026-07-07 23:18:26	2026-07-07 23:19:11
117	frais d'inscription	FRAIS D'INSCRIPTION de la classe de CE2	2026-07-07 23:23:48	2026-07-07 23:23:48
118	frais d'inscription	FRAIS D'INSCRIPTION de la classe de CM1	2026-07-07 23:31:04	2026-07-07 23:31:04
119	frais d'inscription	FRAIS D'INSCRIPTION de la classe de CM2	2026-07-07 23:37:33	2026-07-07 23:37:33
\.


--
-- Data for Name: galeries; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.galeries (id, titre, description, created_at, updated_at) FROM stdin;
6	La Promotion Première D 2026	Les photos des élèves de classe de première D promotion 2026 du COLLÈGE PRIVÉ D'ENSEIGNEMENT GENERAL LE GLORIEUX	2026-05-13 22:56:14	2026-05-21 16:47:27
5	La Promotion Seconde D 2026	Les photos des élèves de classe de Seconde D promotion 2026 du COLLÈGE PRIVÉ D'ENSEIGNEMENT GENERAL LE GLORIEUX	2026-05-13 22:54:52	2026-05-21 16:47:38
4	La Promotion Troisième 2026	Les photos des élèves de classe de 3ème promotion 2026 du COLLÈGE PRIVÉ D'ENSEIGNEMENT GENERAL LE GLORIEUX	2026-05-13 22:53:38	2026-05-21 16:47:54
3	La Promotion Quatrième 2026	Les photos des élèves de classe de 4ème promotion 2026 du COLLÈGE PRIVÉ D'ENSEIGNEMENT GENERAL LE GLORIEUX	2026-05-13 22:51:59	2026-05-21 16:48:05
2	La Promotion Cinquième 2026	Les photos des élèves de classe de 5ème promotion 2026 du COLLÈGE PRIVÉ D'ENSEIGNEMENT GENERAL LE GLORIEUX	2026-05-12 21:36:11	2026-05-21 16:48:16
1	La Promotion Sixième 2026	Les photos des élèves de classe de 6ème promotion 2026 du COLLÈGE PRIVÉ D'ENSEIGNEMENT GENERAL LE GLORIEUX	2026-05-12 21:34:24	2026-05-21 16:49:02
7	La Promotion Première C 2026	Les photos des élèves de classe de Première C promotion 2026 du COLLÈGE PRIVÉ D'ENSEIGNEMENT GENERAL LE GLORIEUX	2026-05-13 22:59:02	2026-06-03 10:04:33
\.


--
-- Data for Name: importation_notes; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.importation_notes (id, classe_id, trimestre_id, annee_id, matiere_id, created_at, updated_at, inscription_id, moyenne_interro, devoir1, devoir2, moyenne_matiere) FROM stdin;
\.


--
-- Data for Name: importations_notes; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.importations_notes (id, classe_id, trimestre_id, annee_id, matiere_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: inscription_frais; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.inscription_frais (id, inscription_id, frais_id, annee_id, montant_frais, montant_paye, reste, statut, est_arriere, created_at, updated_at, montant_total) FROM stdin;
231	208	2	5	80000.00	0.00	80000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
232	208	7	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
233	208	17	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
42	76	7	2	5000.00	0.00	5000.00	non_payé	f	2026-02-26 21:41:10	2026-02-26 21:41:10	\N
2	57	1	2	80000.00	35000.00	45000.00	partiellement_payé	f	2026-02-26 21:16:28	2026-06-08 17:57:46	\N
45	79	8	2	5000.00	2000.00	0.00	partiellement_payé	f	2026-02-26 22:22:45	2026-06-08 18:17:07	\N
14	79	3	2	90000.00	50000.00	30000.00	partiellement_payé	f	2026-02-26 21:27:17	2026-06-08 18:24:39	\N
22	86	4	2	90000.00	20000.00	50000.00	partiellement_payé	f	2026-02-26 21:31:00	2026-06-09 22:19:55	\N
56	89	9	2	5000.00	0.00	0.00	non_payé	f	2026-02-26 22:23:44	2026-06-09 22:21:46	\N
43	77	8	2	0.00	0.00	0.00	soldé	f	2026-02-26 22:22:45	2026-03-01 22:28:20	\N
12	77	3	2	0.00	0.00	0.00	soldé	f	2026-02-26 21:27:17	2026-03-01 22:29:23	\N
24	88	4	2	90000.00	90000.00	0.00	soldé	f	2026-02-26 21:31:00	2026-03-03 13:11:58	\N
55	88	9	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:23:44	2026-03-03 13:11:58	\N
11	76	2	2	0.00	0.00	0.00	soldé	f	2026-02-26 21:21:10	2026-03-03 13:20:24	\N
26	90	4	2	90000.00	60000.00	30000.00	partiellement_payé	f	2026-02-26 21:31:00	2026-04-30 14:52:05	\N
15	80	3	2	90000.00	40000.00	40000.00	partiellement_payé	f	2026-02-26 21:27:17	2026-06-09 22:25:13	\N
7	72	2	2	80000.00	30000.00	50000.00	partiellement_payé	f	2026-02-26 21:21:10	2026-04-30 14:42:24	\N
18	83	3	2	90000.00	20000.00	70000.00	partiellement_payé	f	2026-02-26 21:27:17	2026-04-12 20:03:21	\N
49	83	8	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:22:45	2026-04-12 20:03:21	\N
25	89	4	2	90000.00	70000.00	20000.00	partiellement_payé	f	2026-02-26 21:31:00	2026-04-30 14:50:05	\N
19	99	3	2	90000.00	40000.00	40000.00	partiellement_payé	f	2026-02-26 21:27:17	2026-06-09 22:26:31	\N
32	56	6	2	5000.00	2000.00	0.00	partiellement_payé	f	2026-02-26 21:39:55	2026-06-04 22:49:02	\N
61	94	10	2	5000.00	3000.00	0.00	partiellement_payé	f	2026-02-26 22:24:46	2026-06-10 23:57:51	\N
39	73	7	2	5000.00	2000.00	0.00	partiellement_payé	f	2026-02-26 21:41:10	2026-06-09 22:31:17	\N
28	92	5	2	110000.00	80000.00	20000.00	partiellement_payé	f	2026-02-26 21:37:45	2026-06-10 23:54:45	\N
31	95	5	2	110000.00	0.00	80000.00	non_payé	f	2026-02-26 21:37:45	2026-06-11 00:01:07	\N
60	93	10	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:24:46	2026-04-12 16:53:08	\N
34	58	6	2	5000.00	0.00	0.00	non_payé	f	2026-02-26 21:39:55	2026-06-04 22:57:29	\N
33	57	6	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 21:39:55	2026-05-10 21:55:25	\N
4	59	1	2	80000.00	80000.00	0.00	soldé	f	2026-02-26 21:16:28	2026-03-18 22:22:28	\N
35	59	6	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 21:39:55	2026-03-18 22:22:28	\N
8	73	2	2	80000.00	20000.00	50000.00	partiellement_payé	f	2026-02-26 21:21:10	2026-06-09 22:31:17	\N
21	85	4	2	90000.00	63000.00	20000.00	partiellement_payé	f	2026-02-26 21:31:00	2026-06-04 22:46:04	\N
16	81	3	2	90000.00	13000.00	77000.00	partiellement_payé	f	2026-02-26 21:27:17	2026-03-27 22:32:17	\N
38	72	7	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 21:41:10	2026-05-30 16:38:40	\N
50	99	8	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:22:45	2026-04-12 19:59:51	\N
47	81	8	2	5000.00	2000.00	3000.00	partiellement_payé	f	2026-02-26 22:22:45	2026-03-27 22:32:17	\N
13	78	3	2	90000.00	70000.00	20000.00	partiellement_payé	f	2026-02-26 21:27:17	2026-04-08 23:02:57	\N
29	93	5	2	110000.00	70000.00	30000.00	partiellement_payé	f	2026-02-26 21:37:45	2026-06-10 23:56:16	\N
30	94	5	2	110000.00	10000.00	70000.00	partiellement_payé	f	2026-02-26 21:37:45	2026-06-10 23:57:51	\N
57	90	9	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:23:44	2026-03-29 23:16:05	\N
36	70	7	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 21:41:10	2026-04-25 21:59:38	\N
62	95	10	2	5000.00	0.00	0.00	non_payé	f	2026-02-26 22:24:46	2026-06-11 00:01:07	\N
23	87	4	2	90000.00	30000.00	60000.00	partiellement_payé	f	2026-02-26 21:31:00	2026-03-29 23:51:36	\N
54	87	9	2	5000.00	3000.00	2000.00	partiellement_payé	f	2026-02-26 22:23:44	2026-03-29 23:51:36	\N
59	92	10	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:24:46	2026-04-11 22:59:19	\N
44	78	8	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:22:45	2026-03-30 00:11:13	\N
27	91	5	2	110000.00	50000.00	50000.00	partiellement_payé	f	2026-02-26 21:37:45	2026-06-11 00:03:29	\N
46	80	8	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:22:45	2026-03-30 00:17:43	\N
52	85	9	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:23:44	2026-04-11 23:29:55	\N
58	91	10	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:24:46	2026-04-12 17:38:08	\N
53	86	9	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:23:44	2026-04-12 19:55:30	\N
48	82	8	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:22:45	2026-04-12 19:58:09	\N
17	82	3	2	90000.00	90000.00	0.00	soldé	f	2026-02-26 21:27:17	2026-04-12 19:58:09	\N
41	75	7	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 21:41:10	2026-04-12 20:06:07	\N
1	56	1	2	80000.00	30000.00	30000.00	partiellement_payé	f	2026-02-26 21:16:28	2026-06-05 10:29:10	\N
100	72	17	2	2000.00	0.00	2000.00	non_payé	f	2026-02-26 22:41:52	2026-02-26 22:41:52	\N
104	76	17	2	2000.00	0.00	2000.00	non_payé	f	2026-02-26 22:41:52	2026-02-26 22:41:52	\N
105	77	18	2	2000.00	0.00	2000.00	non_payé	f	2026-02-26 22:45:10	2026-02-26 22:45:10	\N
113	84	19	2	2000.00	0.00	2000.00	non_payé	f	2026-02-26 22:48:10	2026-02-26 22:48:10	\N
235	209	2	5	80000.00	0.00	80000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
236	209	7	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
237	209	17	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
239	210	2	5	80000.00	0.00	80000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
240	210	7	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
241	210	17	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
243	211	2	5	80000.00	0.00	80000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
3	58	1	2	80000.00	0.00	50000.00	non_payé	f	2026-02-26 21:16:28	2026-06-19 23:47:18	\N
9	74	2	2	80000.00	62000.00	18000.00	partiellement_payé	f	2026-02-26 21:21:10	2026-06-29 00:34:52	\N
5	70	2	2	80000.00	40000.00	20000.00	partiellement_payé	f	2026-02-26 21:21:10	2026-07-06 10:55:40	\N
244	211	7	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
245	211	17	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:35:47	2026-06-05 10:35:47	\N
306	227	85	5	125000.00	0.00	125000.00	non_payé	f	2026-06-05 13:46:41	2026-06-05 13:46:41	\N
307	228	85	5	125000.00	0.00	125000.00	non_payé	f	2026-06-05 13:46:41	2026-06-05 13:46:41	\N
308	229	85	5	125000.00	0.00	125000.00	non_payé	f	2026-06-05 13:46:41	2026-06-05 13:46:41	\N
309	230	85	5	125000.00	0.00	125000.00	non_payé	f	2026-06-05 13:46:41	2026-06-05 13:46:41	\N
123	94	20	2	2000.00	0.00	0.00	non_payé	f	2026-02-26 22:49:15	2026-06-10 23:57:51	\N
51	84	9	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 22:23:44	2026-02-26 22:57:23	\N
117	88	19	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:48:10	2026-03-03 13:11:58	\N
108	80	18	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:45:10	2026-03-30 00:17:43	\N
118	89	19	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:48:10	2026-03-03 13:16:03	\N
119	90	19	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:48:10	2026-03-29 22:44:37	\N
102	74	17	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:41:52	2026-04-08 23:06:41	\N
20	84	4	2	90000.00	60000.00	30000.00	partiellement_payé	f	2026-02-26 21:31:00	2026-03-16 20:59:45	\N
124	95	20	2	2000.00	0.00	0.00	non_payé	f	2026-02-26 22:49:15	2026-06-11 00:01:07	\N
37	71	7	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 21:41:10	2026-03-18 01:24:19	\N
98	70	17	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:41:52	2026-03-18 01:47:57	\N
97	59	16	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:40:49	2026-03-18 22:22:28	\N
101	73	17	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:41:52	2026-03-18 01:59:36	\N
94	56	16	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:40:49	2026-03-18 02:06:50	\N
109	81	18	2	2000.00	1000.00	1000.00	partiellement_payé	f	2026-02-26 22:45:10	2026-03-27 22:32:17	\N
112	99	18	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:45:10	2026-04-12 19:59:51	\N
121	92	20	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:49:15	2026-04-11 22:59:19	\N
116	87	19	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:48:10	2026-03-29 23:51:36	\N
114	85	19	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:48:10	2026-04-11 23:29:55	\N
106	78	18	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:45:10	2026-03-30 00:11:13	\N
122	93	20	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:49:15	2026-04-12 16:53:08	\N
120	91	20	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:49:15	2026-04-12 17:38:08	\N
115	86	19	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:48:10	2026-04-12 19:55:30	\N
110	82	18	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:45:10	2026-04-12 19:58:09	\N
111	83	18	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:45:10	2026-04-12 20:03:21	\N
103	75	17	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:41:52	2026-04-12 20:06:07	\N
107	79	18	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:45:10	2026-06-02 13:40:26	\N
95	57	16	2	2000.00	0.00	0.00	non_payé	f	2026-02-26 22:40:49	2026-06-04 22:29:25	\N
96	58	16	2	2000.00	0.00	0.00	non_payé	f	2026-02-26 22:40:49	2026-06-04 22:57:29	\N
99	71	17	2	2000.00	2000.00	0.00	soldé	f	2026-02-26 22:41:52	2026-03-18 01:18:37	\N
370	223	109	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:48:14	2026-06-05 15:48:14	\N
197	107	72	2	125000.00	0.00	125000.00	non_payé	f	2026-06-01 09:51:14	2026-06-01 09:51:14	\N
247	212	3	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
40	74	7	2	5000.00	5000.00	0.00	soldé	f	2026-02-26 21:41:10	2026-04-08 23:06:41	\N
371	224	109	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:48:14	2026-06-05 15:48:14	\N
10	75	2	2	80000.00	80000.00	0.00	soldé	f	2026-02-26 21:21:10	2026-04-12 20:06:07	\N
248	212	8	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
249	212	18	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
251	213	3	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
252	213	8	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
253	213	18	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
255	214	3	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
256	214	8	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
257	214	18	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
259	215	3	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
260	215	8	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
261	215	18	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
263	216	3	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
264	216	8	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
265	216	18	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
267	217	3	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
268	217	8	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
269	217	18	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
271	218	3	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
272	218	8	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
273	218	18	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:36:52	2026-06-05 10:36:52	\N
310	227	86	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 13:48:01	2026-07-06 16:30:07	\N
318	227	88	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 13:50:58	2026-07-06 16:30:38	\N
319	228	88	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 13:50:58	2026-07-06 16:30:38	\N
320	229	88	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 13:50:58	2026-07-06 16:30:38	\N
321	230	88	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 13:50:58	2026-07-06 16:30:38	\N
322	227	89	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 13:52:17	2026-07-07 23:47:26	\N
323	228	89	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 13:52:17	2026-07-07 23:47:26	\N
324	229	89	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 13:52:17	2026-07-07 23:47:26	\N
372	225	109	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:48:14	2026-06-05 15:48:14	\N
355	208	107	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:45:50	2026-06-05 15:45:50	\N
356	209	107	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:45:50	2026-06-05 15:45:50	\N
357	210	107	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:45:50	2026-06-05 15:45:50	\N
358	211	107	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:45:50	2026-06-05 15:45:50	\N
366	219	109	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:48:14	2026-06-05 15:48:14	\N
367	220	109	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:48:14	2026-06-05 15:48:14	\N
368	221	109	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:48:14	2026-06-05 15:48:14	\N
369	222	109	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:48:14	2026-06-05 15:48:14	\N
373	226	109	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:48:14	2026-06-05 15:48:14	\N
397	237	78	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
399	238	5	5	110000.00	0.00	110000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
400	238	10	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
401	238	20	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
402	238	78	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
274	219	9	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
276	219	4	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
277	219	19	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
278	220	9	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
280	220	4	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
281	220	19	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
282	221	9	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
284	221	4	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
285	221	19	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
286	222	9	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
288	222	4	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
289	222	19	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
290	223	9	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
292	223	4	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
293	223	19	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
294	224	9	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
296	224	4	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
297	224	19	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
298	225	9	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
300	225	4	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
301	225	19	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
302	226	9	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
304	226	4	5	90000.00	0.00	90000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
305	226	19	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 10:37:37	2026-06-05 10:37:37	\N
314	227	87	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 13:49:17	2026-06-05 13:49:17	\N
315	228	87	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 13:49:17	2026-06-05 13:49:17	\N
316	229	87	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 13:49:17	2026-06-05 13:49:17	\N
317	230	87	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 13:49:17	2026-06-05 13:49:17	\N
331	231	95	5	130000.00	0.00	130000.00	non_payé	f	2026-06-05 14:03:23	2026-06-05 14:03:23	\N
333	231	97	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 14:05:38	2026-06-05 14:05:38	\N
332	231	96	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 14:04:20	2026-07-06 16:23:27	\N
194	97	66	2	5000.00	0.00	5000.00	non_payé	f	2026-06-01 09:36:31	2026-07-06 16:28:51	\N
195	107	67	2	5000.00	0.00	5000.00	non_payé	f	2026-06-01 09:37:56	2026-07-06 16:29:15	\N
336	208	103	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:40:37	2026-07-07 23:44:24	\N
340	212	104	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:41:45	2026-07-07 23:45:03	\N
341	213	104	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:41:45	2026-07-07 23:45:03	\N
342	214	104	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:41:45	2026-07-07 23:45:03	\N
343	215	104	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:41:45	2026-07-07 23:45:03	\N
347	219	105	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:43:13	2026-07-07 23:45:50	\N
348	220	105	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:43:13	2026-07-07 23:45:50	\N
349	221	105	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:43:13	2026-07-07 23:45:50	\N
350	222	105	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:43:13	2026-07-07 23:45:50	\N
351	223	105	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:43:13	2026-07-07 23:45:50	\N
352	224	105	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:43:13	2026-07-07 23:45:50	\N
398	237	100	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 16:09:30	2026-07-07 23:46:43	\N
403	238	100	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 16:09:30	2026-07-07 23:46:43	\N
325	230	89	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 13:52:17	2026-07-07 23:47:26	\N
335	231	99	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 14:08:47	2026-07-07 23:48:31	\N
359	212	108	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:47:06	2026-06-05 15:47:06	\N
360	213	108	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:47:06	2026-06-05 15:47:06	\N
361	214	108	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:47:06	2026-06-05 15:47:06	\N
362	215	108	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:47:06	2026-06-05 15:47:06	\N
363	216	108	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:47:06	2026-06-05 15:47:06	\N
364	217	108	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:47:06	2026-06-05 15:47:06	\N
365	218	108	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 15:47:06	2026-06-05 15:47:06	\N
374	233	5	5	110000.00	0.00	110000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
375	233	10	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
376	233	20	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
377	233	78	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
379	234	5	5	110000.00	0.00	110000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
380	234	10	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
381	234	20	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
382	234	78	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
384	235	5	5	110000.00	0.00	110000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
385	235	10	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
386	235	20	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
387	235	78	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
389	236	5	5	110000.00	0.00	110000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
390	236	10	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
391	236	20	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
392	236	78	5	2500.00	0.00	2500.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
394	237	5	5	110000.00	0.00	110000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
395	237	10	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
396	237	20	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 16:09:30	2026-06-05 16:09:30	\N
6	71	2	2	80000.00	50000.00	13000.00	partiellement_payé	f	2026-02-26 21:21:10	2026-06-29 00:39:17	\N
334	231	98	5	2000.00	0.00	2000.00	non_payé	f	2026-06-05 14:07:09	2026-07-06 16:13:51	\N
311	228	86	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 13:48:01	2026-07-06 16:30:07	\N
312	229	86	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 13:48:01	2026-07-06 16:30:07	\N
313	230	86	5	5000.00	0.00	5000.00	non_payé	f	2026-06-05 13:48:01	2026-07-06 16:30:07	\N
337	209	103	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:40:37	2026-07-07 23:44:24	\N
338	210	103	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:40:37	2026-07-07 23:44:24	\N
339	211	103	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:40:37	2026-07-07 23:44:24	\N
344	216	104	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:41:45	2026-07-07 23:45:03	\N
345	217	104	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:41:45	2026-07-07 23:45:03	\N
346	218	104	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:41:45	2026-07-07 23:45:03	\N
353	225	105	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:43:13	2026-07-07 23:45:50	\N
354	226	105	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 15:43:13	2026-07-07 23:45:50	\N
378	233	100	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 16:09:30	2026-07-07 23:46:43	\N
383	234	100	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 16:09:30	2026-07-07 23:46:43	\N
388	235	100	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 16:09:30	2026-07-07 23:46:43	\N
393	236	100	5	7000.00	0.00	7000.00	non_payé	f	2026-06-05 16:09:30	2026-07-07 23:46:43	\N
410	240	95	5	130000.00	0.00	130000.00	non_payé	f	2026-07-11 13:41:24	2026-07-11 13:41:24	\N
411	240	97	5	2500.00	0.00	2500.00	non_payé	f	2026-07-11 13:41:24	2026-07-11 13:41:24	\N
412	240	99	5	7000.00	0.00	7000.00	non_payé	f	2026-07-11 13:41:24	2026-07-11 13:41:24	\N
413	240	98	5	2000.00	0.00	2000.00	non_payé	f	2026-07-11 13:41:24	2026-07-11 13:41:24	\N
414	240	96	5	5000.00	0.00	5000.00	non_payé	f	2026-07-11 13:41:24	2026-07-11 13:41:24	\N
\.


--
-- Data for Name: inscriptions; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.inscriptions (id, classe_id, annee_id, date_inscription, created_at, updated_at, eleve_id, moyenne_annuelle, passage_auto, ancienne_classe_id, decision) FROM stdin;
70	2	2	2026-02-01	2026-02-02 00:15:29	2026-05-25 09:43:52	15	13.70	f	2	passé
71	2	2	2026-02-01	2026-02-02 00:15:29	2026-05-25 09:43:52	16	11.88	f	2	passé
72	2	2	2026-02-01	2026-02-02 00:15:29	2026-05-25 09:43:52	17	16.76	f	2	passé
73	2	2	2026-02-01	2026-02-02 00:15:29	2026-05-25 09:43:52	18	13.63	f	2	passé
74	2	2	2026-02-01	2026-02-02 00:15:29	2026-05-25 09:43:52	19	11.10	f	2	passé
75	2	2	2026-02-01	2026-02-02 00:15:29	2026-05-25 09:43:52	20	11.34	f	2	passé
76	2	2	2026-02-01	2026-02-02 00:15:29	2026-05-25 09:43:52	21	15.90	f	2	passé
79	3	2	2026-02-01	2026-02-02 00:16:51	2026-05-25 09:43:52	24	11.62	f	\N	passé
80	3	2	2026-02-01	2026-02-02 00:16:51	2026-05-25 09:43:52	25	14.54	f	\N	passé
81	3	2	2026-02-01	2026-02-02 00:16:51	2026-05-25 09:43:52	26	10.15	f	\N	passé
82	3	2	2026-02-01	2026-02-02 00:16:51	2026-05-25 09:43:52	27	10.31	f	\N	passé
83	3	2	2026-02-01	2026-02-02 00:16:51	2026-05-25 09:43:52	28	10.89	f	\N	passé
84	4	2	2026-02-01	2026-02-02 00:17:28	2026-05-25 09:43:52	29	11.83	f	\N	passé
85	4	2	2026-02-01	2026-02-02 00:17:28	2026-05-25 09:43:52	30	12.66	f	\N	passé
86	4	2	2026-02-01	2026-02-02 00:17:28	2026-05-25 09:43:52	31	12.35	f	\N	passé
87	4	2	2026-02-01	2026-02-02 00:17:28	2026-05-25 09:43:52	32	9.24	f	\N	redoublé
88	4	2	2026-02-01	2026-02-02 00:17:28	2026-05-25 09:43:52	33	12.05	f	\N	passé
89	4	2	2026-02-01	2026-02-02 00:17:28	2026-05-25 09:43:52	34	11.28	f	\N	passé
90	4	2	2026-02-01	2026-02-02 00:17:28	2026-05-25 09:43:52	35	13.10	f	\N	passé
91	9	2	2026-02-01	2026-02-02 00:18:13	2026-05-25 09:43:52	36	12.48	f	\N	passé
92	9	2	2026-02-01	2026-02-02 00:18:13	2026-05-25 09:43:52	37	9.61	f	\N	redoublé
93	9	2	2026-02-01	2026-02-02 00:18:13	2026-05-25 09:43:52	38	11.19	f	\N	passé
94	9	2	2026-02-01	2026-02-02 00:18:13	2026-05-25 09:43:52	39	12.74	f	\N	passé
95	9	2	2026-02-01	2026-02-02 00:18:13	2026-05-25 09:43:52	40	11.20	f	\N	passé
97	11	2	2026-02-01	2026-02-02 00:19:03	2026-05-25 09:43:52	42	12.64	f	\N	passé
99	3	2	2026-02-07	2026-02-08 00:06:00	2026-05-25 09:43:52	45	6.95	f	\N	redoublé
107	10	2	2026-05-08	2026-05-08 21:50:36	2026-05-25 09:43:52	53	11.21	f	\N	passé
77	3	2	2026-02-01	2026-02-02 00:16:51	2026-05-25 09:43:52	22	17.92	f	\N	passé
78	3	2	2026-02-01	2026-02-02 00:16:51	2026-05-25 09:43:52	23	10.63	f	\N	passé
56	1	2	2026-02-01	2026-02-01 23:11:08	2026-05-25 15:09:41	1	10.46	f	1	passé
57	1	2	2026-02-01	2026-02-01 23:11:08	2026-05-25 15:09:41	2	15.01	f	1	passé
208	2	5	2026-06-05	2026-06-05 10:35:47	2026-06-05 10:35:47	1	0.00	t	1	redoublé
209	2	5	2026-06-05	2026-06-05 10:35:47	2026-06-05 10:35:47	2	0.00	t	1	redoublé
58	1	2	2026-02-01	2026-02-01 23:11:08	2026-05-25 15:09:41	3	10.42	f	1	passé
59	1	2	2026-02-01	2026-02-01 23:11:08	2026-05-25 15:09:41	4	15.90	f	1	passé
210	2	5	2026-06-05	2026-06-05 10:35:47	2026-06-05 10:35:47	3	0.00	t	1	redoublé
211	2	5	2026-06-05	2026-06-05 10:35:47	2026-06-05 10:35:47	4	0.00	t	1	redoublé
219	4	5	2026-06-05	2026-06-05 10:37:37	2026-06-05 10:37:37	22	0.00	t	3	redoublé
220	4	5	2026-06-05	2026-06-05 10:37:37	2026-06-05 10:37:37	23	0.00	t	3	redoublé
221	4	5	2026-06-05	2026-06-05 10:37:37	2026-06-05 10:37:37	24	0.00	t	3	redoublé
222	4	5	2026-06-05	2026-06-05 10:37:37	2026-06-05 10:37:37	25	0.00	t	3	redoublé
223	4	5	2026-06-05	2026-06-05 10:37:37	2026-06-05 10:37:37	26	0.00	t	3	redoublé
224	4	5	2026-06-05	2026-06-05 10:37:37	2026-06-05 10:37:37	27	0.00	t	3	redoublé
225	4	5	2026-06-05	2026-06-05 10:37:37	2026-06-05 10:37:37	28	0.00	t	3	redoublé
226	4	5	2026-06-05	2026-06-05 10:37:37	2026-06-05 10:37:37	45	0.00	t	3	redoublé
231	16	5	2026-06-05	2026-06-05 10:41:10	2026-06-05 10:41:10	53	0.00	t	10	redoublé
233	9	5	2026-06-05	2026-06-05 16:09:30	2026-06-05 16:09:30	29	0.00	t	4	redoublé
234	9	5	2026-06-05	2026-06-05 16:09:30	2026-06-05 16:09:30	30	0.00	t	4	redoublé
235	9	5	2026-06-05	2026-06-05 16:09:30	2026-06-05 16:09:30	31	0.00	t	4	redoublé
236	9	5	2026-06-05	2026-06-05 16:09:30	2026-06-05 16:09:30	33	0.00	t	4	redoublé
237	9	5	2026-06-05	2026-06-05 16:09:30	2026-06-05 16:09:30	34	0.00	t	4	redoublé
238	9	5	2026-06-05	2026-06-05 16:09:30	2026-06-05 16:09:30	35	0.00	t	4	redoublé
212	3	5	2026-06-05	2026-06-05 10:36:52	2026-06-05 10:36:52	15	0.00	t	2	redoublé
213	3	5	2026-06-05	2026-06-05 10:36:52	2026-06-05 10:36:52	16	0.00	t	2	redoublé
214	3	5	2026-06-05	2026-06-05 10:36:52	2026-06-05 10:36:52	17	0.00	t	2	redoublé
215	3	5	2026-06-05	2026-06-05 10:36:52	2026-06-05 10:36:52	18	0.00	t	2	redoublé
216	3	5	2026-06-05	2026-06-05 10:36:52	2026-06-05 10:36:52	19	0.00	t	2	redoublé
217	3	5	2026-06-05	2026-06-05 10:36:52	2026-06-05 10:36:52	20	0.00	t	2	redoublé
218	3	5	2026-06-05	2026-06-05 10:36:52	2026-06-05 10:36:52	21	0.00	t	2	redoublé
227	10	5	2026-06-05	2026-06-05 10:40:41	2026-06-05 10:40:41	36	0.00	t	9	redoublé
228	10	5	2026-06-05	2026-06-05 10:40:41	2026-06-05 10:40:41	38	0.00	t	9	redoublé
229	10	5	2026-06-05	2026-06-05 10:40:41	2026-06-05 10:40:41	39	0.00	t	9	redoublé
230	10	5	2026-06-05	2026-06-05 10:40:41	2026-06-05 10:40:41	40	0.00	t	9	redoublé
240	16	5	2026-07-11	2026-07-11 13:41:24	2026-07-11 13:41:24	42	\N	t	11	\N
\.


--
-- Data for Name: investissements; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.investissements (id, investisseur_id, date_investissement, taux, statut, observation, created_at, updated_at, montant) FROM stdin;
11	1	2026-07-10	60.00	actif	COMPLEXE SCOLAIRE LE GLORIEUX	2026-07-10 12:09:06	2026-07-11 20:36:09	9821500.00
10	2	2026-07-09	40.00	actif	COMPLEXE SCOLAIRE LE GLORIEUX	2026-07-10 12:02:32	2026-07-10 21:32:03	5103750.00
\.


--
-- Data for Name: investisseurs; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.investisseurs (id, nom, prenom, telephone, email, adresse, profession, piece_identite, numero_piece, date_naissance, actif, observation, created_at, updated_at) FROM stdin;
1	ADEYEMI	Kolawolé	0197521637	kolatresor.adeyemi@gmail.com	COTONOU/DEDOKPO/M/LATOUNDJI BRICE	Enseignant	\N	\N	\N	t	\N	2026-07-09 15:20:25	2026-07-09 15:20:25
2	YESSOUFOU	Affissou A.	0197189324	affisouyessoufou@gmail.com	SEME-PODJI	Enseignant	\N	\N	\N	t	\N	2026-07-09 17:36:16	2026-07-09 17:36:16
\.


--
-- Data for Name: matieres; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.matieres (id, nom, coefficient, type, enseignant_id, created_at, updated_at, niveau) FROM stdin;
1	Anglais	1	litteraire	\N	2026-03-21 11:25:28	2026-03-21 11:25:28	6eme
2	Communication écrite	1	litteraire	\N	2026-03-21 11:30:00	2026-03-21 11:30:00	6eme
3	Lecture	1	litteraire	\N	2026-03-21 11:30:19	2026-03-21 11:30:19	6eme
4	Histoire-Géographie	1	litteraire	\N	2026-03-21 11:30:44	2026-03-21 11:30:44	6eme
5	Mathématiques	1	scientifique	\N	2026-03-21 11:37:33	2026-03-21 11:37:33	6eme
6	PCT	1	scientifique	\N	2026-03-21 11:37:51	2026-03-21 11:37:51	6eme
7	SVT	1	scientifique	\N	2026-03-21 11:38:19	2026-03-21 11:38:19	6eme
8	EPS	1	autres	\N	2026-03-21 11:38:36	2026-03-21 11:38:36	6eme
9	Anglais	1	litteraire	\N	2026-03-21 11:38:51	2026-03-21 11:38:51	5eme
10	Communication écrite	1	litteraire	\N	2026-03-21 11:39:05	2026-03-21 11:39:05	5eme
11	Lecture	1	litteraire	\N	2026-03-21 11:39:25	2026-03-21 11:39:25	5eme
12	Histoire-Géographie	1	litteraire	\N	2026-03-21 11:39:36	2026-03-21 11:39:36	5eme
13	Mathématiques	1	scientifique	\N	2026-03-21 11:39:52	2026-03-21 11:39:52	5eme
14	PCT	1	scientifique	\N	2026-03-21 11:40:05	2026-03-21 11:40:05	5eme
15	SVT	1	scientifique	\N	2026-03-21 11:40:17	2026-03-21 11:40:17	5eme
16	EPS	1	autres	\N	2026-03-21 11:40:31	2026-03-21 11:40:31	5eme
17	Anglais	2	litteraire	\N	2026-03-21 11:41:11	2026-03-21 11:41:11	4eme
18	Communication écrite	2	litteraire	\N	2026-03-21 11:41:29	2026-03-21 11:41:29	4eme
20	Histoire-Géographie	2	litteraire	\N	2026-03-21 11:42:02	2026-03-21 11:42:02	4eme
21	Mathématiques	3	scientifique	\N	2026-03-21 11:42:36	2026-03-21 11:42:36	4eme
22	PCT	2	scientifique	\N	2026-03-21 11:42:56	2026-03-21 11:42:56	4eme
23	SVT	2	scientifique	\N	2026-03-21 11:43:16	2026-03-21 11:43:16	4eme
24	EPS	1	autres	\N	2026-03-21 11:43:31	2026-03-21 11:43:31	4eme
25	Anglais	2	litteraire	\N	2026-03-21 11:44:00	2026-03-21 11:44:00	3eme
26	Communication écrite	2	litteraire	\N	2026-03-21 11:44:27	2026-03-21 11:44:27	3eme
27	Lecture	2	litteraire	\N	2026-03-21 11:45:39	2026-03-21 11:45:39	3eme
28	Histoire-Géographie	2	litteraire	\N	2026-03-21 11:45:57	2026-03-21 11:45:57	3eme
29	ESPAGNOL	2	litteraire	\N	2026-03-21 11:46:28	2026-03-21 11:46:28	3eme
30	ESPAGNOL	2	litteraire	\N	2026-03-21 11:47:00	2026-03-21 11:47:00	4eme
31	Mathématiques	3	scientifique	\N	2026-03-21 11:48:34	2026-03-21 11:48:34	3eme
32	PCT	2	scientifique	\N	2026-03-21 11:48:49	2026-03-21 11:48:49	3eme
33	SVT	2	scientifique	\N	2026-03-21 11:49:09	2026-03-21 11:49:09	3eme
34	EPS	1	autres	\N	2026-03-21 11:49:24	2026-03-21 11:49:24	3eme
35	Français	1	litteraire	\N	2026-03-21 11:49:55	2026-03-21 11:49:55	2ndeD
36	Anglais	2	litteraire	\N	2026-03-21 11:50:18	2026-03-21 11:50:18	2ndeD
37	Philosophie	2	litteraire	\N	2026-03-21 11:51:29	2026-03-21 11:51:29	2ndeD
38	Histoire-Géographie	1	litteraire	\N	2026-03-21 11:51:54	2026-03-21 11:51:54	2ndeD
39	Mathématiques	3	scientifique	\N	2026-03-21 11:52:15	2026-03-21 11:52:15	2ndeD
40	PCT	3	scientifique	\N	2026-03-21 11:52:37	2026-03-21 11:52:37	2ndeD
41	SVT	3	scientifique	\N	2026-03-21 11:53:06	2026-03-21 11:53:06	2ndeD
43	Français	2	litteraire	\N	2026-03-21 11:55:04	2026-03-21 11:55:04	1ereD
44	Anglais	2	litteraire	\N	2026-03-21 11:55:27	2026-03-21 11:55:27	1ereD
46	Histoire-Géographie	2	litteraire	\N	2026-03-21 11:56:43	2026-03-21 11:56:43	1ereD
45	Philosophie	2	litteraire	\N	2026-03-21 11:56:07	2026-03-21 11:58:34	1ereD
47	Mathématiques	4	scientifique	\N	2026-03-21 11:59:23	2026-03-21 11:59:23	1ereD
48	PCT	4	scientifique	\N	2026-03-21 11:59:57	2026-03-21 11:59:57	1ereD
49	SVT	5	scientifique	\N	2026-03-21 12:00:27	2026-03-21 12:00:27	1ereD
50	EPS	1	autres	\N	2026-03-21 12:00:47	2026-03-21 12:00:47	1ereD
51	Français	2	litteraire	\N	2026-03-21 12:01:48	2026-03-21 12:01:48	1ereC
52	Anglais	2	litteraire	\N	2026-03-21 12:02:15	2026-03-21 12:02:15	1ereC
53	Philosophie	2	litteraire	\N	2026-03-21 12:02:47	2026-03-21 12:02:47	1ereC
54	Histoire-Géographie	2	litteraire	\N	2026-03-21 12:03:12	2026-03-21 12:03:12	1ereC
55	Mathématiques	5	scientifique	\N	2026-03-21 12:03:58	2026-03-21 12:03:58	1ereC
56	PCT	5	scientifique	\N	2026-03-21 12:05:00	2026-03-21 12:05:00	1ereC
57	SVT	2	scientifique	\N	2026-03-21 12:05:19	2026-03-21 12:05:19	1ereC
58	EPS	1	autres	\N	2026-03-21 12:05:32	2026-03-21 12:05:32	1ereC
42	EPS	1	autres	\N	2026-03-21 11:53:35	2026-05-03 23:33:16	2ndeD
100	FRANÇAIS	1	litteraire	\N	2026-05-03 23:39:24	2026-05-03 23:39:24	2ndeC
101	ANGLAIS	2	litteraire	\N	2026-05-03 23:39:24	2026-05-03 23:39:24	2ndeC
102	PHILOSOPHIE	2	litteraire	\N	2026-05-03 23:39:24	2026-05-03 23:39:24	2ndeC
103	HISTOIRE-GEOGRAPHIE	1	litteraire	\N	2026-05-03 23:39:24	2026-05-03 23:39:24	2ndeC
104	MATHEMATIQUES	3	scientifique	\N	2026-05-03 23:39:24	2026-05-03 23:39:24	2ndeC
105	PCT	3	scientifique	\N	2026-05-03 23:39:24	2026-05-03 23:39:24	2ndeC
106	SVT	3	scientifique	\N	2026-05-03 23:39:24	2026-05-03 23:39:24	2ndeC
107	EPS	1	Autres	\N	2026-05-03 23:39:24	2026-05-03 23:39:24	2ndeC
108	FRANÇAIS	2	litteraire	\N	2026-05-03 23:42:35	2026-05-03 23:42:35	TleC
109	ANGLAIS	2	litteraire	\N	2026-05-03 23:42:35	2026-05-03 23:42:35	TleC
110	PHILOSOPHIE	2	litteraire	\N	2026-05-03 23:42:35	2026-05-03 23:42:35	TleC
111	HISTOIRE-GEOGRAPHIE	2	litteraire	\N	2026-05-03 23:42:35	2026-05-03 23:42:35	TleC
112	MATHEMATIQUES	6	scientifique	\N	2026-05-03 23:42:35	2026-05-03 23:42:35	TleC
113	PCT	5	scientifique	\N	2026-05-03 23:42:35	2026-05-03 23:42:35	TleC
114	SVT	2	scientifique	\N	2026-05-03 23:42:35	2026-05-03 23:42:35	TleC
115	EPS	1	Autres	\N	2026-05-03 23:42:35	2026-05-03 23:42:35	TleC
116	FRANÇAIS	2	litteraire	\N	2026-05-03 23:44:37	2026-05-03 23:44:37	TleD
117	ANGLAIS	2	litteraire	\N	2026-05-03 23:44:37	2026-05-03 23:44:37	TleD
118	PHILOSOPHIE	2	litteraire	\N	2026-05-03 23:44:37	2026-05-03 23:44:37	TleD
119	HISTOIRE-GEOGRAPHIE	2	litteraire	\N	2026-05-03 23:44:37	2026-05-03 23:44:37	TleD
120	MATHEMATIQUES	4	scientifique	\N	2026-05-03 23:44:37	2026-05-03 23:44:37	TleD
121	PCT	4	scientifique	\N	2026-05-03 23:44:37	2026-05-03 23:44:37	TleD
122	SVT	5	scientifique	\N	2026-05-03 23:44:37	2026-05-03 23:44:37	TleD
123	EPS	1	Autres	\N	2026-05-03 23:44:37	2026-05-03 23:44:37	TleD
19	Lecture	2	litteraire	\N	2026-03-21 11:41:43	2026-05-27 13:21:33	4eme
\.


--
-- Data for Name: media; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.media (id, galerie_id, fichier, type, titre, created_at, updated_at) FROM stdin;
3	2	medias/GV2VK4DqHqBnEtrtU0WM1XUPPIxjL6pk2bn0iwgr.png	image	YESSOUFOU Andilath	2026-05-12 23:15:42	2026-05-12 23:15:42
4	2	medias/OEBMNT4CLjVn792oxwmonxRhnLG957VJyMpQvSlx.png	image	AHOLODE Marie rénée	2026-05-12 23:16:38	2026-05-12 23:16:38
5	2	medias/1n9bHzxVkzW9JFrSbNEvDFKN4jTmW36onlM6YIVX.png	image	LADOKOU Amir Tchègoun	2026-05-12 23:17:27	2026-05-12 23:17:27
6	2	medias/wvBD5kGyxkQibhcjJLyrAZpNSjuMfTO58Arunmwv.png	image	KALU John	2026-05-12 23:18:25	2026-05-12 23:18:25
7	2	medias/vn3kObHyvAwiEVSQjFCZMNzWZ2cFJ7i88NxFvCmu.png	image	GBESSEHOUN Jean Jorès	2026-05-12 23:19:11	2026-05-12 23:19:11
8	2	medias/TYvgFOwuT9EJjmwwatiAcogOA2k2hoAhUrHTkMNC.png	image	GBAMIGBOLA Fadel Sourou	2026-05-12 23:20:16	2026-05-12 23:20:16
9	2	medias/Mh6F8nE5UkSrotmbNHTFGL2GDU3lcFAiCeWWWAvT.png	image	DIAKITE Mohamed Zoumana	2026-05-12 23:21:03	2026-05-12 23:21:03
10	1	medias/JMEAWw1um7lurYbszSjhr05RO3OjZyQ5qg9XkpCs.png	image	AGOKPINZIN Trinité	2026-05-12 23:22:00	2026-05-12 23:22:00
11	1	medias/R7260duQfWw1ntd7V9HcEpTzEefyk3gtcWE98pdT.png	image	SAGBOHAN Jean Eudes	2026-05-12 23:22:55	2026-05-12 23:22:55
12	1	medias/cwmQa1Cy4UfQohLg3DEn8JllOb9RUwBRCyPGVoI4.png	image	AYABA Amal	2026-05-12 23:23:32	2026-05-12 23:23:32
13	1	medias/2KCW2mlBAaLp6laj9ZCJDmHWvSxvPd5ZgtB75qsK.png	image	TAIWO Siddiqoth	2026-05-12 23:24:19	2026-05-12 23:24:19
14	3	medias/xBKAzEGoECYGfVdtSTKX7kZO8UcCq14AMPMohHHr.png	image	DIENE Fadil	2026-05-13 23:18:51	2026-05-13 23:18:51
15	3	medias/evonWClZqFeK3IjLRvNRNNWCIVj2tyGC9JKiG2Ee.png	image	ADEYEMI Précieuse	2026-05-13 23:20:20	2026-05-13 23:20:20
16	3	medias/aU8thDKuhVfyvxYO1DKGDFsxkSFKDwS6PF0GV3py.png	image	AYABA Akorede	2026-05-13 23:21:25	2026-05-13 23:21:25
17	3	medias/vO2GPLXPwqvLHWCYGxTVBVBkkISNRMstOkl5OCBa.png	image	BOUBACAR Mohamed	2026-05-13 23:22:26	2026-05-13 23:22:26
18	3	medias/4z5I2zwzKaW3VbuLSMr2ikYa4datn6ZuLW6J2yDl.png	image	GANDONOU Manassé	2026-05-13 23:22:58	2026-05-13 23:22:58
19	3	medias/6LzLGy3MA6S6LwWNWhU65PuLwiTF37F4wlhlmZFa.png	image	GNONLONFOUN Miracle	2026-05-13 23:23:48	2026-05-13 23:23:48
20	3	medias/sCW5ogM2dfqas4cFTjPt9HNPFIeEmWtVDKp9hGNY.png	image	KALU Samuel	2026-05-13 23:24:38	2026-05-13 23:24:38
21	3	medias/MH35BBwHjEzMJSPOHtFOSy2ONrbMuJHb1HXlW0Uq.png	image	SOUNOUVOU Sépphora	2026-05-13 23:25:23	2026-05-13 23:25:23
22	5	medias/n3MIY1fElwIMrPhTEfuPUfX7oCIOjeqIi5umjKQo.png	image	OHOUSSOU ulrich	2026-06-03 09:53:50	2026-06-03 09:53:50
23	5	medias/ZHEjeOJjwHuERtdhFPF26TwzLI9dkAWHV4nMmPiN.png	image	HOUNDEWAGNON Salim	2026-06-03 09:54:50	2026-06-03 09:54:50
24	5	medias/D9bHaOE61xu2NBnzAUwSCG9pySyxV2ciYdCkFWv7.png	image	SOUNOUVOU Josué	2026-06-03 09:55:26	2026-06-03 09:55:26
25	5	medias/08pp9F2mUezvDRj0z4uDeYm8niJEhgT9HmWbnsM2.png	image	GBAMIGBOLA Dorifène	2026-06-03 09:56:02	2026-06-03 09:56:02
26	5	medias/4VrrcadM30H5yJMKcW32p5QzYtxwA1EL49zm2zMC.png	image	KALU Dannielle	2026-06-03 09:56:44	2026-06-03 09:56:44
27	6	medias/PALKJuEe11qEWKDuXwKJja2ge6Qnx8GroIxEf3ZO.png	image	DEKA-JAMES Précieux	2026-06-03 09:57:44	2026-06-03 09:57:44
28	7	medias/cHhte50qmkfHQdilBc2sW378B5yBZYy0srKigldj.png	image	YESSOUFOU Kafilath	2026-06-03 09:58:28	2026-06-03 09:58:28
29	4	medias/xakQAt9DulleatW8E7H2MOFx7XEXp2VaSVh5I6gq.jpg	image	AGBOZINGBA Sedolo	2026-06-03 23:26:31	2026-06-03 23:26:31
30	4	medias/OkkPDU7XkafsbzEZLsQ4Ci32Hl4Bty5aB7W7WBHW.jpg	image	AHOUANSE Eudoxie	2026-06-03 23:27:13	2026-06-03 23:27:13
31	4	medias/QvWh0w7gGbRxOlCC5WTPaYqIvAUDklNCfqqaoifG.jpg	image	BONOU Emmanuel	2026-06-03 23:27:48	2026-06-03 23:27:48
32	4	medias/Jmjr9aY0L3YuLRrfvT1Z50zYf7V2knv29QYGqIjv.jpg	image	LADOKOU Fatihiyath	2026-06-03 23:28:57	2026-06-03 23:28:57
33	4	medias/uzygM4SQgvOPYceOB04TG52HmcTAMpsHzsAEanhm.jpg	image	OLAAFA Toholou	2026-06-03 23:29:55	2026-06-03 23:29:55
34	4	medias/4Wjm1tEvLIKgoaLenQCIkgY83u01F7UDiuHbif8p.jpg	image	TAIWO Sabiqath	2026-06-03 23:30:37	2026-06-03 23:30:37
35	4	medias/Srtbxe1bZW8i736DcabxdUqZu2ecr7h6cSDzz08f.jpg	image	OLAAFA Toholath	2026-06-03 23:31:20	2026-06-03 23:31:20
36	7	medias/IqEsnronYcIss2ToYcl6Ylwq7QrhHVeL0EEIsKw0.mp4	video	evenement	2026-07-11 22:43:31	2026-07-11 22:43:31
\.


--
-- Data for Name: message_parents; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.message_parents (id, eleve_id, paren_id, user_id, objet, message, type, lu, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_reset_tokens_table	1
3	2014_10_12_200000_add_two_factor_columns_to_users_table	1
4	2019_08_19_000000_create_failed_jobs_table	1
5	2019_12_14_000001_create_personal_access_tokens_table	1
6	2024_05_06_180805_create_sessions_table	1
7	2024_05_18_204949_create_roles_table	1
8	2024_05_19_170914_create_contacts_table	1
9	2024_05_25_210439_create_annees_table	1
10	2024_05_25_210507_create_classes_table	1
11	2024_06_12_001921_create_comptes_table	1
12	2024_06_12_003704_create_scolarites_table	1
13	2024_06_18_002545_create_epreuves_table	1
14	2024_06_18_191154_create_versescos_table	1
15	2024_06_28_232422_create_payscos_table	1
16	2024_07_06_235927_create_pmonis_table	1
17	2024_07_10_183341_create_fraistds_table	1
18	2024_07_13_152847_create_notecoms_table	1
19	2025_05_25_210450_create_trimestres_table	1
20	2025_07_03_100000_create_parens_table	1
21	2025_07_03_220952_create_eleves_table	1
22	2025_07_05_210526_create_inscriptions_table	1
23	2025_07_11_230637_create_enseignants_table	1
24	2025_07_12_014228_create_matieres_table	1
25	2025_07_12_014254_create_notes_table	1
26	2025_07_13_234701_create_classe_matiere_table	1
27	2025_07_15_175022_create_frais_table	1
28	2025_07_15_175243_create_paiements_table	1
29	2025_07_15_175327_create_echeances_table	1
30	2025_07_22_152159_create_conduites_table	1
31	2025_08_09_003255_add_annee_id_to_conduites_table	1
32	2025_08_09_185628_create_moyennes_table	1
33	2025_08_12_014309_create_bulletins_table	1
34	2025_08_29_233455_create_recettes_table	2
35	2025_08_29_233552_create_depenses_table	2
36	2025_08_30_161321_create_finances_table	3
37	2025_08_30_171011_create_passages_table	4
38	2025_09_04_013143_create_importation_notes_table	5
39	2025_09_25_203919_create_categories_table	6
40	2025_09_25_203956_create_transactions_table	6
41	2025_09_25_204110_create_comptes_table	7
42	2025_09_25_204136_create_budgets_table	7
43	2024_06_18_002545_create_eprs_table	8
44	2025_09_28_210729_create_epreuves_table	9
45	2025_10_01_203021_create_operations_table	10
46	2025_11_10_210224_create_tests_table	11
47	2025_11_13_212940_add_hash_to_tests_table	12
48	2025_12_08_000615_create_classe_annee_table	12
49	2025_12_08_000857_create_matiere_classe_table	12
50	2025_12_20_154246_create_annee_trimestres_table	13
51	2025_07_04_210526_create_inscriptions_table	5
52	2025_07_05_220952_create_eleves_table	5
53	2025_12_08_000615_create_annee_classe_table	14
54	2025_12_08_000857_create_classe_matiere_table	14
55	2025_12_28_162854_create_message_parents_table	15
56	2025_12_28_164632_create_notification_parents_table	15
57	2026_01_04_230320_create_spatie_tables_partial	16
58	2026_01_29_204444_create_classe_frais_table	17
59	2026_01_30_084424_create_annee_frais_table	18
60	2026_01_31_140807_create_td_sessions_table	19
61	2026_01_31_141003_create_td_participations_table	19
62	2026_01_31_141131_create_td_paiements_table	19
63	2026_02_06_215706_create_types_table	20
64	2026_02_06_215749_create_articles_table	20
65	2026_02_06_221223_create_mouvement_stocks_table	20
66	2026_02_11_184111_create_inscription_frais_table	21
67	2026_02_25_133334_create_paiement_details_table	22
68	2026_02_28_185840_create_classe_transitions_table	22
69	2026_03_18_141809_add_prenom_to_users_table	23
70	2026_03_19_135835_create_cycles_table	24
71	2026_03_19_144003_add_cycle_id_to_classes_table	24
72	2026_04_02_022000_create_examen_blancs_table	25
73	2026_04_02_022021_create_participant_examens_table	26
74	2026_04_02_022252_create_epreuves_table	27
75	2026_04_02_022391_create_note_examens_table	27
76	2026_04_02_022525_create_examen_classes_table	27
77	2026_04_03_004512_create_examen_blanc_classe_table	28
78	2026_04_10_202035_add_ordre_to_classes_table	29
79	2026_04_14_200120_add_photo_to_eleves_table	30
80	2026_04_26_225758_update_enseignants_table	31
81	2026_04_27_005714_add_classe_id_to_enseignants_table	32
82	2026_04_27_013235_drop_classe_id_from_enseignants_table	33
83	2026_04_27_013535_create_classe_enseignant_table	34
84	2026_04_27_013537_create_classe_enseignant_table	35
85	2026_04_27_013539_create_classe_enseignant_table	36
86	2026_05_03_230743_add_unique_constraint_to_matieres	37
87	2026_05_09_214858_create_annee_classe_frais_table	38
88	2026_05_11_003140_create_galeries_table	39
89	2026_05_11_003243_create_media_table	39
90	2026_05_13_001931_create_notifications_table	40
91	2026_05_23_082527_add_passage_auto_to_inscriptions_table	41
92	2026_05_23_230512_add_ancienne_classe_id_to_inscriptions_table	42
93	2026_05_25_000009_add_rang_to_classes_table	43
95	2025_07_05_310526_create_inscriptions_table	44
98	2026_06_12_214553_create_td_modes_paiements_table	45
105	2026_05_30_152739_add_missing_columns_to_moyennes_table	46
106	2026_06_12_214400_create_td_tarifs_table	46
107	2026_06_12_214447_create_td_seances_table	47
108	2026_06_12_214651_create_td_presences_table	47
109	2026_06_12_214730_create_td_paiements_table	47
110	2026_06_13_142057_modify_categorie_column_in_td_tarifs_table	47
111	2026_07_08_163846_create_investisseurs_table	48
112	2026_07_08_164352_create_investissements_table	48
113	2026_07_08_164928_create_versements_table	48
114	2026_07_08_164941_create_benefices_table	48
115	2026_07_08_165518_create_repartitions_table	48
116	2026_07_08_165540_create_paiements_benefices_table	48
117	2026_07_08_170749_create_retraits_capital_table	48
118	2026_07_08_170823_create_parametres_investissements_table	48
119	2026_07_10_164021_create_oloyes_table	49
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
1	App\\Models\\User	3
7	App\\Models\\User	57
4	App\\Models\\User	63
1	App\\Models\\User	37
1	App\\Models\\User	73
8	App\\Models\\User	34
6	App\\Models\\User	60
5	App\\Models\\User	61
8	App\\Models\\User	44
8	App\\Models\\User	33
8	App\\Models\\User	36
8	App\\Models\\User	35
8	App\\Models\\User	38
8	App\\Models\\User	39
8	App\\Models\\User	40
3	App\\Models\\User	58
2	App\\Models\\User	59
1	App\\Models\\User	74
\.


--
-- Data for Name: mouvement_stocks; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.mouvement_stocks (id, article_id, type, quantite, prix_unitaire, date_mouvement, motif, created_at, updated_at) FROM stdin;
1	1	entree	100	5000.00	2026-02-06	Stock initial	2026-02-07 00:12:34	2026-02-07 00:12:34
\.


--
-- Data for Name: moyennes; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.moyennes (id, trimestre_id, annee_id, moyenne_trimestrielle, moyenne_annuelle, created_at, updated_at, classe_id, rang_trimestre, rang_annuel, moyenne_scientifique, moyenne_litteraire, inscription_id, notes, note_conduite, appreciation_conduite, appreciation, total_eleves, plus_faible_moyenne, plus_forte_moyenne, moyenne_t1, moyenne_t2, moyenne_t3, decision) FROM stdin;
803	2	2	12.39	12.64	2026-03-21 18:07:28	2026-06-16 12:32:31	11	1	1	12.24	12.22	97	{"51":{"moyenne_interro":11.66,"devoir1":"9","devoir2":"10","moyenne_matiere":"10.22","appreciation":"Passable"},"52":{"moyenne_interro":18,"devoir1":"15","devoir2":"16","moyenne_matiere":"16.33","appreciation":"Tr\\u00e8s Bien"},"53":{"moyenne_interro":14,"devoir1":"13","devoir2":"7","moyenne_matiere":"11.33","appreciation":"Passable"},"54":{"moyenne_interro":16,"devoir1":"8","devoir2":"9","moyenne_matiere":"11","appreciation":"Passable"},"55":{"moyenne_interro":15.5,"devoir1":"11","devoir2":"8","moyenne_matiere":"11.5","appreciation":"Passable"},"56":{"moyenne_interro":15,"devoir1":"10","devoir2":"11","moyenne_matiere":"12","appreciation":"Assez Bien"},"57":{"moyenne_interro":14,"devoir1":"12","devoir2":"18","moyenne_matiere":"14.67","appreciation":"Bien"},"58":{"moyenne_interro":12,"devoir1":"13","devoir2":"11","moyenne_matiere":"12","appreciation":"Assez Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	1	12.39	12.39	12.72	12.39	12.81	passé
902	1	2	0.00	6.95	2026-03-21 21:42:16	2026-06-27 23:13:57	3	8ème	\N	0	0	99	[]	\N	\N	Élève Faible, Travail Insuffisant	8	10.46	17.89	0.00	10.51	10.35	redoublé
2266	1	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1er	\N	0	0	210	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2269	1	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1ère	\N	0	0	211	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2260	1	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1er	\N	0	0	208	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2263	1	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1ère	\N	0	0	209	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2267	2	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1er	\N	0	0	210	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2270	2	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1ère	\N	0	0	211	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2261	2	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1er	\N	0	0	208	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2264	2	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1ère	\N	0	0	209	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2268	3	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1er	1er	0	0	210	[]	\N	\N	-	4	\N	\N	0.00	0.00	0.00	redoublé
2271	3	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1ère	1ère	0	0	211	[]	\N	\N	-	4	\N	\N	0.00	0.00	0.00	redoublé
2262	3	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1er	1er	0	0	208	[]	\N	\N	-	4	\N	\N	0.00	0.00	0.00	redoublé
2265	3	5	0.00	0.00	2026-06-16 01:00:17	2026-06-28 21:48:21	2	1ère	1ère	0	0	209	[]	\N	\N	-	4	\N	\N	0.00	0.00	0.00	redoublé
669	1	2	12.23	11.62	2026-03-21 01:47:21	2026-06-27 23:13:57	3	3ème	\N	11.51	12.11	79	{"18":{"moyenne_interro":9.75,"devoir1":"10","devoir2":"11","moyenne_matiere":"10.25","appreciation":"Passable"},"19":{"moyenne_interro":5.5,"devoir1":"15","devoir2":"10","moyenne_matiere":"10.17","appreciation":"Passable"},"20":{"moyenne_interro":14.75,"devoir1":"19","devoir2":"8","moyenne_matiere":"13.92","appreciation":"Assez Bien"},"21":{"moyenne_interro":10.75,"devoir1":"8","devoir2":"12","moyenne_matiere":"10.25","appreciation":"Passable"},"22":{"moyenne_interro":15,"devoir1":"10","devoir2":"11","moyenne_matiere":"12","appreciation":"Assez Bien"},"23":{"moyenne_interro":13.75,"devoir1":"13","devoir2":"12","moyenne_matiere":"12.92","appreciation":"Assez Bien"},"24":{"moyenne_interro":15,"devoir1":"12","devoir2":"14","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"30":{"moyenne_interro":15.66,"devoir1":"17","devoir2":"14","moyenne_matiere":"15.55","appreciation":"Bien"},"17":{"moyenne_interro":10,"devoir1":"14","devoir2":"8","moyenne_matiere":"10.67","appreciation":"Passable"}}	\N	\N	Élève Correct, Travail Assez Bien	8	10.46	17.89	12.23	11.24	11.40	passé
2331	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	1er	0	0	216	[]	\N	\N	-	7	\N	\N	0.00	0.00	0.00	redoublé
663	3	2	17.91	17.92	2026-03-21 01:47:21	2026-06-27 23:13:57	3	1ère	1ère	18.81	17.73	77	{"17":{"moyenne_interro":20,"devoir1":"20","devoir2":"20","moyenne_matiere":"20","appreciation":"Excellent"},"18":{"moyenne_interro":19,"devoir1":"13","devoir2":"13","moyenne_matiere":"15","appreciation":"Bien"},"19":{"moyenne_interro":17.5,"devoir1":"14.25","devoir2":"18.5","moyenne_matiere":"16.75","appreciation":"Tr\\u00e8s Bien"},"20":{"moyenne_interro":20,"devoir1":"18","devoir2":"18","moyenne_matiere":"18.67","appreciation":"Excellent"},"21":{"moyenne_interro":20,"devoir1":"20","devoir2":"19","moyenne_matiere":"19.67","appreciation":"Excellent"},"22":{"moyenne_interro":19,"devoir1":"19","devoir2":"20","moyenne_matiere":"19.33","appreciation":"Excellent"},"23":{"moyenne_interro":19,"devoir1":"17","devoir2":"15","moyenne_matiere":"17","appreciation":"Tr\\u00e8s Bien"},"24":{"moyenne_interro":13,"devoir1":"15","devoir2":"15","moyenne_matiere":"14.33","appreciation":"Bien"},"30":{"moyenne_interro":16.66,"devoir1":"20","devoir2":"18","moyenne_matiere":"18.22","appreciation":"Excellent"}}	\N	\N	Élève Très Bon, Travail Très Bien	8	9.07	17.91	17.89	17.96	17.91	passé
576	1	2	10.44	10.46	2026-03-20 22:13:36	2026-06-28 21:47:10	1	3ème	\N	9.92	9.29	56	{"1":{"moyenne_interro":11,"devoir1":"4","devoir2":"17","moyenne_matiere":"10.67","appreciation":"Passable"},"2":{"moyenne_interro":9.5,"devoir1":"8","devoir2":"6","moyenne_matiere":"7.83","appreciation":"Faible"},"3":{"moyenne_interro":15.5,"devoir1":"5","devoir2":"8","moyenne_matiere":"9.5","appreciation":"Insuffisant"},"4":{"moyenne_interro":10.5,"devoir1":"11","devoir2":"6","moyenne_matiere":"9.17","appreciation":"Insuffisant"},"5":{"moyenne_interro":13.5,"devoir1":"5","devoir2":"10.5","moyenne_matiere":"9.67","appreciation":"Insuffisant"},"6":{"moyenne_interro":13.5,"devoir1":"10","devoir2":"6","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"7":{"moyenne_interro":9.75,"devoir1":"11","devoir2":"10","moyenne_matiere":"10.25","appreciation":"Passable"},"8":{"moyenne_interro":8,"devoir1":"9","devoir2":"10","moyenne_matiere":"9","appreciation":"Insuffisant"}}	\N	\N	Élève Moyen, Travail Passable	4	9.30	15.98	10.44	9.14	11.81	passé
2334	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	1er	0	0	217	[]	\N	\N	-	7	\N	\N	0.00	0.00	0.00	redoublé
2337	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1ère	1ère	0	0	218	[]	\N	\N	-	7	\N	\N	0.00	0.00	0.00	redoublé
601	2	2	11.51	11.88	2026-03-20 22:26:53	2026-06-27 13:06:13	2	5ème	\N	10	10.73	71	{"9":{"moyenne_interro":16.5,"devoir1":"14","devoir2":"11","moyenne_matiere":"13.83","appreciation":"Assez Bien"},"10":{"moyenne_interro":3,"devoir1":"10","devoir2":"10","moyenne_matiere":"7.67","appreciation":"Faible"},"11":{"moyenne_interro":5.75,"devoir1":"9","devoir2":"10","moyenne_matiere":"8.25","appreciation":"Insuffisant"},"12":{"moyenne_interro":14.5,"devoir1":"13","devoir2":"12","moyenne_matiere":"13.17","appreciation":"Assez Bien"},"13":{"moyenne_interro":13,"devoir1":"6","devoir2":"7","moyenne_matiere":"8.67","appreciation":"Insuffisant"},"14":{"moyenne_interro":8.5,"devoir1":"10","devoir2":"3","moyenne_matiere":"7.17","appreciation":"Faible"},"15":{"moyenne_interro":14.5,"devoir1":"13.5","devoir2":"14.5","moyenne_matiere":"14.17","appreciation":"Bien"},"16":{"moyenne_interro":12,"devoir1":"14","devoir2":"15","moyenne_matiere":"13.67","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	10.87	17.01	12.81	11.51	11.33	passé
617	2	2	11.06	11.34	2026-03-20 22:26:53	2026-06-27 13:06:13	2	6ème	\N	9.78	9.65	75	{"9":{"moyenne_interro":16,"devoir1":"11","devoir2":"10.5","moyenne_matiere":"12.5","appreciation":"Assez Bien"},"10":{"moyenne_interro":9,"devoir1":"7","devoir2":"8","moyenne_matiere":"8","appreciation":"Insuffisant"},"11":{"moyenne_interro":12.75,"devoir1":"8.5","devoir2":"6","moyenne_matiere":"9.08","appreciation":"Insuffisant"},"12":{"moyenne_interro":9,"devoir1":"8","devoir2":"10","moyenne_matiere":"9","appreciation":"Insuffisant"},"13":{"moyenne_interro":14.5,"devoir1":"9","devoir2":"8.5","moyenne_matiere":"10.67","appreciation":"Passable"},"14":{"moyenne_interro":9.5,"devoir1":"6","devoir2":"5","moyenne_matiere":"6.83","appreciation":"Faible"},"15":{"moyenne_interro":14.5,"devoir1":"11","devoir2":"10","moyenne_matiere":"11.83","appreciation":"Passable"},"16":{"moyenne_interro":13,"devoir1":"14","devoir2":"14","moyenne_matiere":"13.67","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	10.87	17.01	10.78	11.06	12.17	passé
613	2	2	10.87	11.10	2026-03-20 22:26:53	2026-06-27 13:06:13	2	7ème	\N	10.2	9.52	74	{"9":{"moyenne_interro":15.5,"devoir1":"10.25","devoir2":"10.5","moyenne_matiere":"12.08","appreciation":"Assez Bien"},"10":{"moyenne_interro":8.5,"devoir1":"7","devoir2":"8","moyenne_matiere":"7.83","appreciation":"Faible"},"11":{"moyenne_interro":6,"devoir1":"7","devoir2":"6.5","moyenne_matiere":"6.5","appreciation":"Faible"},"12":{"moyenne_interro":10,"devoir1":"14","devoir2":"11","moyenne_matiere":"11.67","appreciation":"Passable"},"13":{"moyenne_interro":14,"devoir1":"5","devoir2":"10","moyenne_matiere":"9.67","appreciation":"Insuffisant"},"14":{"moyenne_interro":8.25,"devoir1":"4","devoir2":"12.5","moyenne_matiere":"8.25","appreciation":"Insuffisant"},"15":{"moyenne_interro":12.5,"devoir1":"14.5","devoir2":"11","moyenne_matiere":"12.67","appreciation":"Assez Bien"},"16":{"moyenne_interro":12.5,"devoir1":"14","devoir2":"10","moyenne_matiere":"12.17","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	10.87	17.01	10.66	10.87	11.76	passé
729	1	2	12.21	12.74	2026-03-21 01:47:23	2026-06-16 23:30:41	9	2ème	\N	11.84	12.51	94	{"35":{"moyenne_interro":13.33,"devoir1":"13","devoir2":"10","moyenne_matiere":"12.11","appreciation":"Assez Bien"},"36":{"moyenne_interro":11.66,"devoir1":"15","devoir2":"16","moyenne_matiere":"14.22","appreciation":"Bien"},"38":{"moyenne_interro":17.5,"devoir1":"16","devoir2":"10","moyenne_matiere":"14.5","appreciation":"Bien"},"39":{"moyenne_interro":13.75,"devoir1":"14","devoir2":"14","moyenne_matiere":"13.92","appreciation":"Assez Bien"},"40":{"moyenne_interro":8,"devoir1":"10","devoir2":"7","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"41":{"moyenne_interro":13.33,"devoir1":"15.5","devoir2":"11","moyenne_matiere":"13.28","appreciation":"Assez Bien"},"42":{"moyenne_interro":10,"devoir1":"10","devoir2":"10","moyenne_matiere":"10","appreciation":"Passable"},"37":{"moyenne_interro":8,"devoir1":"9","devoir2":"13","moyenne_matiere":"10","appreciation":"Passable"}}	\N	\N	Élève Correct, Travail Assez Bien	5	10.06	12.52	12.21	12.40	13.60	passé
584	1	2	9.30	10.42	2026-03-20 22:13:36	2026-06-28 21:47:10	1	4ème	\N	9	7.67	58	{"1":{"moyenne_interro":8.5,"devoir1":"11","devoir2":"7.5","moyenne_matiere":"9","appreciation":"Insuffisant"},"2":{"moyenne_interro":8.75,"devoir1":"3","devoir2":"7","moyenne_matiere":"6.25","appreciation":"Faible"},"3":{"moyenne_interro":6.25,"devoir1":"4","devoir2":"2","moyenne_matiere":"4.08","appreciation":"Tr\\u00e8s Faible"},"4":{"moyenne_interro":12,"devoir1":"9","devoir2":"13","moyenne_matiere":"11.33","appreciation":"Passable"},"5":{"moyenne_interro":11.75,"devoir1":"9","devoir2":"4","moyenne_matiere":"8.25","appreciation":"Insuffisant"},"6":{"moyenne_interro":13,"devoir1":"8","devoir2":"5.5","moyenne_matiere":"8.83","appreciation":"Insuffisant"},"7":{"moyenne_interro":12.75,"devoir1":"9","devoir2":"8","moyenne_matiere":"9.92","appreciation":"Insuffisant"},"8":{"moyenne_interro":8,"devoir1":"6","devoir2":"10","moyenne_matiere":"8","appreciation":"Insuffisant"}}	\N	\N	Élève Faible, Travail Insuffisant	4	9.30	15.98	9.30	10.35	11.61	passé
588	1	2	15.98	15.90	2026-03-20 22:13:36	2026-06-28 21:47:10	1	1ère	\N	16.31	16.15	59	{"1":{"moyenne_interro":19,"devoir1":"19","devoir2":"18","moyenne_matiere":"18.67","appreciation":"Excellent"},"2":{"moyenne_interro":16.75,"devoir1":"11","devoir2":"11","moyenne_matiere":"12.92","appreciation":"Assez Bien"},"3":{"moyenne_interro":19,"devoir1":"16","devoir2":"15","moyenne_matiere":"16.67","appreciation":"Tr\\u00e8s Bien"},"4":{"moyenne_interro":19,"devoir1":"18","devoir2":"12","moyenne_matiere":"16.33","appreciation":"Tr\\u00e8s Bien"},"5":{"moyenne_interro":16.5,"devoir1":"14","devoir2":"18","moyenne_matiere":"16.17","appreciation":"Tr\\u00e8s Bien"},"6":{"moyenne_interro":15.75,"devoir1":"16","devoir2":"15","moyenne_matiere":"15.58","appreciation":"Bien"},"7":{"moyenne_interro":16,"devoir1":"19.5","devoir2":"16","moyenne_matiere":"17.17","appreciation":"Tr\\u00e8s Bien"},"8":{"moyenne_interro":12,"devoir1":"11","devoir2":"14","moyenne_matiere":"12.33","appreciation":"Assez Bien"}}	\N	\N	Élève Bon, Travail Bien	4	9.30	15.98	15.98	15.41	16.32	passé
585	2	2	10.35	10.42	2026-03-20 22:13:36	2026-06-28 21:47:10	1	3ème	\N	10.37	9.04	58	{"1":{"moyenne_interro":10,"devoir1":"7.5","devoir2":"16","moyenne_matiere":"11.17","appreciation":"Passable"},"2":{"moyenne_interro":3.5,"devoir1":"7","devoir2":"7","moyenne_matiere":"5.83","appreciation":"Tr\\u00e8s Faible"},"3":{"moyenne_interro":2.5,"devoir1":"5","devoir2":"13.5","moyenne_matiere":"7","appreciation":"Faible"},"4":{"moyenne_interro":16.5,"devoir1":"9","devoir2":"11","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"5":{"moyenne_interro":12.5,"devoir1":"6","devoir2":"10.5","moyenne_matiere":"9.67","appreciation":"Insuffisant"},"6":{"moyenne_interro":10.33,"devoir1":"10","devoir2":"11","moyenne_matiere":"10.44","appreciation":"Passable"},"7":{"moyenne_interro":16,"devoir1":"9","devoir2":"8","moyenne_matiere":"11","appreciation":"Passable"},"8":{"moyenne_interro":7.5,"devoir1":"9","devoir2":"10","moyenne_matiere":"8.83","appreciation":"Insuffisant"}}	\N	\N	Élève Moyen, Travail Passable	4	9.14	15.41	9.30	10.35	11.61	passé
596	1	2	12.74	13.70	2026-03-20 22:26:53	2026-06-27 13:06:13	2	4ème	\N	11.22	12.67	70	{"9":{"moyenne_interro":14,"devoir1":"15","devoir2":"13","moyenne_matiere":"14","appreciation":"Bien"},"10":{"moyenne_interro":11.5,"devoir1":"8","devoir2":"10","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"11":{"moyenne_interro":11.25,"devoir1":"10.25","devoir2":"13","moyenne_matiere":"11.5","appreciation":"Passable"},"12":{"moyenne_interro":15,"devoir1":"18","devoir2":"13","moyenne_matiere":"15.33","appreciation":"Bien"},"13":{"moyenne_interro":13.5,"devoir1":"7","devoir2":"13","moyenne_matiere":"11.17","appreciation":"Passable"},"14":{"moyenne_interro":14,"devoir1":"12.5","devoir2":"7","moyenne_matiere":"11.17","appreciation":"Passable"},"15":{"moyenne_interro":14,"devoir1":"8","devoir2":"12","moyenne_matiere":"11.33","appreciation":"Passable"},"16":{"moyenne_interro":13,"devoir1":"10","devoir2":"14","moyenne_matiere":"12.33","appreciation":"Assez Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	10.66	16.44	12.74	14.52	13.83	passé
589	2	2	15.41	15.90	2026-03-20 22:13:36	2026-06-28 21:47:10	1	1ère	\N	15.63	15.79	59	{"1":{"moyenne_interro":17.5,"devoir1":"18.5","devoir2":"17.5","moyenne_matiere":"17.83","appreciation":"Tr\\u00e8s Bien"},"2":{"moyenne_interro":17,"devoir1":"10","devoir2":"11","moyenne_matiere":"12.67","appreciation":"Assez Bien"},"3":{"moyenne_interro":16.5,"devoir1":"15.5","devoir2":"18.5","moyenne_matiere":"16.83","appreciation":"Tr\\u00e8s Bien"},"4":{"moyenne_interro":17.5,"devoir1":"15","devoir2":"15","moyenne_matiere":"15.83","appreciation":"Bien"},"5":{"moyenne_interro":18.5,"devoir1":"12.5","devoir2":"16","moyenne_matiere":"15.67","appreciation":"Bien"},"6":{"moyenne_interro":14.16,"devoir1":"11.5","devoir2":"16","moyenne_matiere":"13.89","appreciation":"Assez Bien"},"7":{"moyenne_interro":17,"devoir1":"15","devoir2":"20","moyenne_matiere":"17.33","appreciation":"Tr\\u00e8s Bien"},"8":{"moyenne_interro":9,"devoir1":"13","devoir2":"10","moyenne_matiere":"10.67","appreciation":"Passable"}}	\N	\N	Élève Bon, Travail Bien	4	9.14	15.41	15.98	15.41	16.32	passé
677	1	2	10.78	10.15	2026-03-21 01:47:22	2026-06-27 23:13:57	3	6ème	\N	8.53	11.48	81	{"18":{"moyenne_interro":8.75,"devoir1":"8","devoir2":"10","moyenne_matiere":"8.92","appreciation":"Insuffisant"},"19":{"moyenne_interro":7,"devoir1":"14","devoir2":"10.25","moyenne_matiere":"10.42","appreciation":"Passable"},"20":{"moyenne_interro":16,"devoir1":"13","devoir2":"10.5","moyenne_matiere":"13.17","appreciation":"Assez Bien"},"21":{"moyenne_interro":5,"devoir1":"7","devoir2":"5","moyenne_matiere":"5.67","appreciation":"Tr\\u00e8s Faible"},"22":{"moyenne_interro":13,"devoir1":"15","devoir2":"7","moyenne_matiere":"11.67","appreciation":"Passable"},"23":{"moyenne_interro":8.5,"devoir1":"15.5","devoir2":"5","moyenne_matiere":"9.67","appreciation":"Insuffisant"},"24":{"moyenne_interro":13,"devoir1":"13","devoir2":"14","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"30":{"moyenne_interro":15.66,"devoir1":"13","devoir2":"15","moyenne_matiere":"14.55","appreciation":"Bien"},"17":{"moyenne_interro":7,"devoir1":"14","devoir2":"10","moyenne_matiere":"10.33","appreciation":"Passable"}}	\N	\N	Élève Moyen, Travail Passable	8	10.46	17.89	10.78	10.59	9.07	passé
670	2	2	11.24	11.62	2026-03-21 01:47:21	2026-06-27 23:13:57	3	3ème	\N	9.07	11.94	79	{"17":{"moyenne_interro":15,"devoir1":"11.5","devoir2":"14","moyenne_matiere":"13.5","appreciation":"Assez Bien"},"18":{"moyenne_interro":10.5,"devoir1":"8","devoir2":"8","moyenne_matiere":"8.83","appreciation":"Insuffisant"},"19":{"moyenne_interro":10,"devoir1":"10","devoir2":"11.5","moyenne_matiere":"10.5","appreciation":"Passable"},"20":{"moyenne_interro":8,"devoir1":"7","devoir2":"13","moyenne_matiere":"9.33","appreciation":"Insuffisant"},"21":{"moyenne_interro":9,"devoir1":"8","devoir2":"6.5","moyenne_matiere":"7.83","appreciation":"Faible"},"22":{"moyenne_interro":13.5,"devoir1":"7","devoir2":"9","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"23":{"moyenne_interro":12.5,"devoir1":"7","devoir2":"11","moyenne_matiere":"10.17","appreciation":"Passable"},"24":{"moyenne_interro":10,"devoir1":"13","devoir2":"15","moyenne_matiere":"12.67","appreciation":"Assez Bien"},"30":{"moyenne_interro":17.66,"devoir1":"19","devoir2":"16","moyenne_matiere":"17.55","appreciation":"Tr\\u00e8s Bien"}}	\N	\N	Élève Moyen, Travail Passable	8	9.85	17.96	12.23	11.24	11.40	passé
666	2	2	11.03	10.63	2026-03-21 01:47:21	2026-06-27 23:13:57	3	4ème	\N	8.1	12.14	78	{"17":{"moyenne_interro":17.5,"devoir1":"8","devoir2":"8","moyenne_matiere":"11.17","appreciation":"Passable"},"18":{"moyenne_interro":16,"devoir1":"8","devoir2":"10","moyenne_matiere":"11.33","appreciation":"Passable"},"19":{"moyenne_interro":11,"devoir1":"12","devoir2":"11","moyenne_matiere":"11.33","appreciation":"Passable"},"20":{"moyenne_interro":11,"devoir1":"10","devoir2":"12","moyenne_matiere":"11","appreciation":"Passable"},"21":{"moyenne_interro":9,"devoir1":"3","devoir2":"3.5","moyenne_matiere":"5.17","appreciation":"Tr\\u00e8s Faible"},"22":{"moyenne_interro":13.5,"devoir1":"8","devoir2":"8","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"23":{"moyenne_interro":13,"devoir1":"10.25","devoir2":"9","moyenne_matiere":"10.75","appreciation":"Passable"},"24":{"moyenne_interro":12.5,"devoir1":"15","devoir2":"13","moyenne_matiere":"13.5","appreciation":"Assez Bien"},"30":{"moyenne_interro":16.66,"devoir1":"16","devoir2":"15","moyenne_matiere":"15.89","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	8	9.85	17.96	10.46	11.03	10.41	passé
678	2	2	10.59	10.15	2026-03-21 01:47:22	2026-06-27 23:13:57	3	5ème	\N	7.62	11.84	81	{"17":{"moyenne_interro":11,"devoir1":"10","devoir2":"9","moyenne_matiere":"10","appreciation":"Passable"},"18":{"moyenne_interro":12.5,"devoir1":"8","devoir2":"10","moyenne_matiere":"10.17","appreciation":"Passable"},"19":{"moyenne_interro":10.5,"devoir1":"13.5","devoir2":"11","moyenne_matiere":"11.67","appreciation":"Passable"},"20":{"moyenne_interro":13,"devoir1":"14","devoir2":"8","moyenne_matiere":"11.67","appreciation":"Passable"},"21":{"moyenne_interro":8,"devoir1":"5","devoir2":"6","moyenne_matiere":"6.33","appreciation":"Faible"},"22":{"moyenne_interro":10,"devoir1":"6","devoir2":"7","moyenne_matiere":"7.67","appreciation":"Faible"},"23":{"moyenne_interro":11.5,"devoir1":"8","devoir2":"9","moyenne_matiere":"9.5","appreciation":"Insuffisant"},"24":{"moyenne_interro":12.5,"devoir1":"15","devoir2":"7","moyenne_matiere":"11.5","appreciation":"Passable"},"30":{"moyenne_interro":17,"devoir1":"15","devoir2":"15","moyenne_matiere":"15.67","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	8	9.85	17.96	10.78	10.59	9.07	passé
697	1	2	11.67	12.35	2026-03-21 01:47:22	2026-06-19 21:56:31	4	4ème	\N	9.81	12.41	86	{"25":{"moyenne_interro":13.5,"devoir1":"11","devoir2":"9","moyenne_matiere":"11.17","appreciation":"Passable"},"26":{"moyenne_interro":13,"devoir1":"10","devoir2":"10","moyenne_matiere":"11","appreciation":"Passable"},"27":{"moyenne_interro":16,"devoir1":"14","devoir2":"9","moyenne_matiere":"13","appreciation":"Assez Bien"},"28":{"moyenne_interro":13.66,"devoir1":"7.5","devoir2":"10.5","moyenne_matiere":"10.55","appreciation":"Passable"},"29":{"moyenne_interro":18,"devoir1":"16","devoir2":"15","moyenne_matiere":"16.33","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":15,"devoir1":"9","devoir2":"6","moyenne_matiere":"10","appreciation":"Passable"},"32":{"moyenne_interro":6.25,"devoir1":"3","devoir2":"8","moyenne_matiere":"5.75","appreciation":"Tr\\u00e8s Faible"},"33":{"moyenne_interro":15.5,"devoir1":"14.25","devoir2":"11","moyenne_matiere":"13.58","appreciation":"Assez Bien"},"34":{"moyenne_interro":14,"devoir1":"12","devoir2":"16","moyenne_matiere":"14","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	9.29	13.36	11.67	12.95	12.44	passé
580	1	2	14.47	15.01	2026-03-20 22:13:36	2026-06-28 21:47:10	1	2ème	\N	14.05	14.52	57	{"1":{"moyenne_interro":19.5,"devoir1":"18","devoir2":"11","moyenne_matiere":"16.17","appreciation":"Tr\\u00e8s Bien"},"2":{"moyenne_interro":12.5,"devoir1":"10","devoir2":"10","moyenne_matiere":"10.83","appreciation":"Passable"},"3":{"moyenne_interro":16.75,"devoir1":"13.5","devoir2":"12","moyenne_matiere":"14.08","appreciation":"Bien"},"4":{"moyenne_interro":19,"devoir1":"18","devoir2":"14","moyenne_matiere":"17","appreciation":"Tr\\u00e8s Bien"},"5":{"moyenne_interro":16,"devoir1":"12","devoir2":"12","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"6":{"moyenne_interro":9.75,"devoir1":"16.5","devoir2":"12","moyenne_matiere":"12.75","appreciation":"Assez Bien"},"7":{"moyenne_interro":16.25,"devoir1":"17","devoir2":"15","moyenne_matiere":"16.08","appreciation":"Tr\\u00e8s Bien"},"8":{"moyenne_interro":14,"devoir1":"10","devoir2":"12","moyenne_matiere":"12","appreciation":"Assez Bien"}}	\N	\N	Élève Bon, Travail Bien	4	9.30	15.98	14.47	14.99	15.56	passé
578	3	2	11.81	10.46	2026-03-20 22:13:36	2026-06-28 21:47:10	1	3ème	3ème	10.7	10.46	56	{"1":{"moyenne_interro":7,"devoir1":"13","devoir2":"20","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"2":{"moyenne_interro":11.5,"devoir1":"7","devoir2":"7","moyenne_matiere":"8.5","appreciation":"Insuffisant"},"3":{"moyenne_interro":16.5,"devoir1":"5.5","devoir2":"4.5","moyenne_matiere":"8.83","appreciation":"Insuffisant"},"4":{"moyenne_interro":12.5,"devoir1":"12","devoir2":"9","moyenne_matiere":"11.17","appreciation":"Passable"},"5":{"moyenne_interro":13.5,"devoir1":"6","devoir2":"10","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"6":{"moyenne_interro":14.33,"devoir1":"10","devoir2":"10","moyenne_matiere":"11.44","appreciation":"Passable"},"7":{"moyenne_interro":13,"devoir1":"9.5","devoir2":"10","moyenne_matiere":"10.83","appreciation":"Passable"},"8":{"moyenne_interro":13,"devoir1":"15","devoir2":"15","moyenne_matiere":"14.33","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	4	11.61	16.32	10.44	9.14	11.81	passé
612	1	2	10.66	11.10	2026-03-20 22:26:53	2026-06-27 13:06:13	2	7ème	\N	8.72	10.11	74	{"9":{"moyenne_interro":12,"devoir1":"16","devoir2":"7.25","moyenne_matiere":"11.75","appreciation":"Passable"},"10":{"moyenne_interro":10,"devoir1":"8","devoir2":"7","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"11":{"moyenne_interro":6.75,"devoir1":"10","devoir2":"3.25","moyenne_matiere":"6.67","appreciation":"Faible"},"12":{"moyenne_interro":13,"devoir1":"17","devoir2":"11","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"13":{"moyenne_interro":11,"devoir1":"6","devoir2":"7","moyenne_matiere":"8","appreciation":"Insuffisant"},"14":{"moyenne_interro":9,"devoir1":"15","devoir2":"2","moyenne_matiere":"8.67","appreciation":"Insuffisant"},"15":{"moyenne_interro":9.5,"devoir1":"7","devoir2":"12","moyenne_matiere":"9.5","appreciation":"Insuffisant"},"16":{"moyenne_interro":11,"devoir1":"13","devoir2":"10","moyenne_matiere":"11.33","appreciation":"Passable"}}	\N	\N	Élève Moyen, Travail Passable	7	10.66	16.44	10.66	10.87	11.76	passé
903	2	2	10.51	6.95	2026-03-21 21:42:16	2026-06-27 23:13:57	3	7ème	\N	8.43	10.81	99	{"17":{"moyenne_interro":16.5,"devoir1":"9","devoir2":"8","moyenne_matiere":"11.17","appreciation":"Passable"},"18":{"moyenne_interro":5.5,"devoir1":"7","devoir2":"8","moyenne_matiere":"6.83","appreciation":"Faible"},"19":{"moyenne_interro":10.5,"devoir1":"10","devoir2":"9","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"20":{"moyenne_interro":11,"devoir1":"10","devoir2":"9","moyenne_matiere":"10","appreciation":"Passable"},"21":{"moyenne_interro":12,"devoir1":"3","devoir2":"4.5","moyenne_matiere":"6.5","appreciation":"Faible"},"22":{"moyenne_interro":11,"devoir1":"8","devoir2":"11","moyenne_matiere":"10","appreciation":"Passable"},"23":{"moyenne_interro":13,"devoir1":"9.25","devoir2":"7","moyenne_matiere":"9.75","appreciation":"Insuffisant"},"24":{"moyenne_interro":14.5,"devoir1":"15","devoir2":"17","moyenne_matiere":"15.5","appreciation":"Bien"},"30":{"moyenne_interro":17.66,"devoir1":"19","devoir2":"12","moyenne_matiere":"16.22","appreciation":"Tr\\u00e8s Bien"}}	\N	\N	Élève Moyen, Travail Passable	8	9.85	17.96	0.00	10.51	10.35	redoublé
581	2	2	14.99	15.01	2026-03-20 22:13:36	2026-06-28 21:47:10	1	2ème	\N	14.63	15.08	57	{"1":{"moyenne_interro":17,"devoir1":"15","devoir2":"17.5","moyenne_matiere":"16.5","appreciation":"Tr\\u00e8s Bien"},"2":{"moyenne_interro":14,"devoir1":"13","devoir2":"11","moyenne_matiere":"12.67","appreciation":"Assez Bien"},"3":{"moyenne_interro":13,"devoir1":"16.5","devoir2":"13.5","moyenne_matiere":"14.33","appreciation":"Bien"},"4":{"moyenne_interro":18.5,"devoir1":"15","devoir2":"17","moyenne_matiere":"16.83","appreciation":"Tr\\u00e8s Bien"},"5":{"moyenne_interro":18.5,"devoir1":"18","devoir2":"14","moyenne_matiere":"16.83","appreciation":"Tr\\u00e8s Bien"},"6":{"moyenne_interro":13.66,"devoir1":"11.5","devoir2":"9","moyenne_matiere":"11.39","appreciation":"Passable"},"7":{"moyenne_interro":13,"devoir1":"17","devoir2":"17","moyenne_matiere":"15.67","appreciation":"Bien"},"8":{"moyenne_interro":11,"devoir1":"14","devoir2":"13","moyenne_matiere":"12.67","appreciation":"Assez Bien"}}	\N	\N	Élève Bon, Travail Bien	4	9.14	15.41	14.47	14.99	15.56	passé
675	3	2	13.83	14.54	2026-03-21 01:47:21	2026-06-27 23:13:57	3	2ème	2ème	13.02	13.97	80	{"17":{"moyenne_interro":16.5,"devoir1":"15","devoir2":"14","moyenne_matiere":"15.17","appreciation":"Bien"},"18":{"moyenne_interro":14.5,"devoir1":"11","devoir2":"10","moyenne_matiere":"11.83","appreciation":"Passable"},"19":{"moyenne_interro":18.5,"devoir1":"13.5","devoir2":"13.25","moyenne_matiere":"15.08","appreciation":"Bien"},"20":{"moyenne_interro":12,"devoir1":"12","devoir2":"17","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"21":{"moyenne_interro":10.5,"devoir1":"12","devoir2":"10","moyenne_matiere":"10.83","appreciation":"Passable"},"22":{"moyenne_interro":18,"devoir1":"15","devoir2":"13","moyenne_matiere":"15.33","appreciation":"Bien"},"23":{"moyenne_interro":15,"devoir1":"10","devoir2":"17","moyenne_matiere":"14","appreciation":"Bien"},"24":{"moyenne_interro":15.5,"devoir1":"15","devoir2":"14","moyenne_matiere":"14.83","appreciation":"Bien"},"30":{"moyenne_interro":14.33,"devoir1":"15","devoir2":"13","moyenne_matiere":"14.11","appreciation":"Bien"}}	\N	\N	Élève Bon, Travail Bien	8	9.07	17.91	15.05	14.74	13.83	passé
804	3	2	12.81	12.64	2026-03-21 18:07:28	2026-06-16 12:32:31	11	1	1	12.57	14.67	97	{"51":{"moyenne_interro":14.5,"devoir1":"14","devoir2":"10","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"52":{"moyenne_interro":16,"devoir1":"18","devoir2":"19","moyenne_matiere":"17.67","appreciation":"Tr\\u00e8s Bien"},"53":{"moyenne_interro":15.5,"devoir1":"10","devoir2":"12","moyenne_matiere":"12.5","appreciation":"Assez Bien"},"54":{"moyenne_interro":17,"devoir1":"13","devoir2":"17","moyenne_matiere":"15.67","appreciation":"Bien"},"55":{"moyenne_interro":15.5,"devoir1":"15","devoir2":"8","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"56":{"moyenne_interro":13,"devoir1":"9","devoir2":"12","moyenne_matiere":"11.33","appreciation":"Passable"},"57":{"moyenne_interro":16,"devoir1":"14","devoir2":"15","moyenne_matiere":"15","appreciation":"Bien"},"58":{"moyenne_interro":8,"devoir1":"16","devoir2":"17","moyenne_matiere":"13.67","appreciation":"Assez Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	1	12.81	12.81	12.72	12.39	12.81	passé
671	3	2	11.40	11.62	2026-03-21 01:47:21	2026-06-27 23:13:57	3	3ème	3ème	11.93	10.07	79	{"17":{"moyenne_interro":16.5,"devoir1":"16","devoir2":"13","moyenne_matiere":"15.17","appreciation":"Bien"},"18":{"moyenne_interro":10.5,"devoir1":"10","devoir2":"8","moyenne_matiere":"9.5","appreciation":"Insuffisant"},"19":{"moyenne_interro":10,"devoir1":"10","devoir2":"11","moyenne_matiere":"10.33","appreciation":"Passable"},"20":{"moyenne_interro":7,"devoir1":"6","devoir2":"4","moyenne_matiere":"5.67","appreciation":"Tr\\u00e8s Faible"},"21":{"moyenne_interro":14.5,"devoir1":"12","devoir2":"11","moyenne_matiere":"12.5","appreciation":"Assez Bien"},"22":{"moyenne_interro":14,"devoir1":"14","devoir2":"9","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"23":{"moyenne_interro":13,"devoir1":"8","devoir2":"11","moyenne_matiere":"10.67","appreciation":"Passable"},"24":{"moyenne_interro":16,"devoir1":"15","devoir2":"15","moyenne_matiere":"15.33","appreciation":"Bien"},"30":{"moyenne_interro":11,"devoir1":"8","devoir2":"10","moyenne_matiere":"9.67","appreciation":"Insuffisant"}}	\N	\N	Élève Moyen, Travail Passable	8	9.07	17.91	12.23	11.24	11.40	passé
2272	1	5	0.00	0.00	2026-06-16 01:00:17	2026-06-16 01:00:19	4	1	1	0	0	219	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2274	3	5	0.00	0.00	2026-06-16 01:00:17	2026-06-16 01:00:19	4	3	1	0	0	219	[]	\N	\N	-	8	\N	\N	0.00	0.00	0.00	redoublé
2273	2	5	0.00	0.00	2026-06-16 01:00:17	2026-06-16 01:00:19	4	4	1	0	0	219	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2289	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	5	6	0	0	224	[]	\N	\N	-	8	\N	\N	0.00	0.00	0.00	redoublé
2275	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	3	2	0	0	220	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2277	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	4	2	0	0	220	[]	\N	\N	-	8	\N	\N	0.00	0.00	0.00	redoublé
2288	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	5	6	0	0	224	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2279	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	3	3	0	0	221	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2278	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	4	3	0	0	221	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2280	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	1	3	0	0	221	[]	\N	\N	-	8	\N	\N	0.00	0.00	0.00	redoublé
2291	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	8	7	0	0	225	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2281	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	5	4	0	0	222	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2276	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	2	2	0	0	220	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2282	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	1	4	0	0	222	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2292	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	8	7	0	0	225	[]	\N	\N	-	8	\N	\N	0.00	0.00	0.00	redoublé
2286	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	2	5	0	0	223	[]	\N	\N	-	8	\N	\N	0.00	0.00	0.00	redoublé
2290	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	8	7	0	0	225	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2284	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	2	5	0	0	223	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2294	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	6	8	0	0	226	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2295	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	6	8	0	0	226	[]	\N	\N	-	8	\N	\N	0.00	0.00	0.00	redoublé
2283	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	7	4	0	0	222	[]	\N	\N	-	8	\N	\N	0.00	0.00	0.00	redoublé
2287	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	6	6	0	0	224	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2285	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	7	5	0	0	223	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
687	3	2	11.11	10.89	2026-03-21 01:47:22	2026-06-27 23:13:57	3	4ème	4ème	10.86	10.39	83	{"17":{"moyenne_interro":12,"devoir1":"13","devoir2":"11","moyenne_matiere":"12","appreciation":"Assez Bien"},"18":{"moyenne_interro":12,"devoir1":"7","devoir2":"11","moyenne_matiere":"10","appreciation":"Passable"},"19":{"moyenne_interro":16,"devoir1":"8","devoir2":"9","moyenne_matiere":"11","appreciation":"Passable"},"20":{"moyenne_interro":6.5,"devoir1":"6","devoir2":"7","moyenne_matiere":"6.5","appreciation":"Faible"},"21":{"moyenne_interro":11,"devoir1":"14","devoir2":"10","moyenne_matiere":"11.67","appreciation":"Passable"},"22":{"moyenne_interro":15,"devoir1":"10","devoir2":"9","moyenne_matiere":"11.33","appreciation":"Passable"},"23":{"moyenne_interro":12.5,"devoir1":"8","devoir2":"7","moyenne_matiere":"9.17","appreciation":"Insuffisant"},"24":{"moyenne_interro":12.5,"devoir1":"14","devoir2":"16","moyenne_matiere":"14.17","appreciation":"Bien"},"30":{"moyenne_interro":15.33,"devoir1":"14","devoir2":"8","moyenne_matiere":"12.44","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	8	9.07	17.91	11.03	10.52	11.11	passé
667	3	2	10.41	10.63	2026-03-21 01:47:21	2026-06-27 23:13:57	3	5ème	5ème	8.91	10.25	78	{"17":{"moyenne_interro":15,"devoir1":"16","devoir2":"13","moyenne_matiere":"14.67","appreciation":"Bien"},"18":{"moyenne_interro":10.5,"devoir1":"7","devoir2":"6","moyenne_matiere":"7.83","appreciation":"Faible"},"19":{"moyenne_interro":10.5,"devoir1":"5.5","devoir2":"7.5","moyenne_matiere":"7.83","appreciation":"Faible"},"20":{"moyenne_interro":11.5,"devoir1":"8","devoir2":"10","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"21":{"moyenne_interro":9,"devoir1":"7","devoir2":"5","moyenne_matiere":"7","appreciation":"Faible"},"22":{"moyenne_interro":15,"devoir1":"11","devoir2":"9","moyenne_matiere":"11.67","appreciation":"Passable"},"23":{"moyenne_interro":14,"devoir1":"5","devoir2":"8","moyenne_matiere":"9","appreciation":"Insuffisant"},"24":{"moyenne_interro":15.5,"devoir1":"15","devoir2":"17","moyenne_matiere":"15.83","appreciation":"Bien"},"30":{"moyenne_interro":12.33,"devoir1":"7","devoir2":"14","moyenne_matiere":"11.11","appreciation":"Passable"}}	\N	\N	Élève Moyen, Travail Passable	8	9.07	17.91	10.46	11.03	10.41	passé
904	3	2	10.35	10.43	2026-03-21 21:42:16	2026-06-27 23:13:57	3	6ème	6ème	9.83	9.52	99	{"17":{"moyenne_interro":13,"devoir1":"13","devoir2":"14","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"18":{"moyenne_interro":12,"devoir1":"8","devoir2":"5","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"19":{"moyenne_interro":8,"devoir1":"5","devoir2":"6.25","moyenne_matiere":"6.42","appreciation":"Faible"},"20":{"moyenne_interro":12.5,"devoir1":"7","devoir2":"6","moyenne_matiere":"8.5","appreciation":"Insuffisant"},"21":{"moyenne_interro":12.5,"devoir1":"8","devoir2":"3","moyenne_matiere":"7.83","appreciation":"Faible"},"22":{"moyenne_interro":18,"devoir1":"14","devoir2":"10","moyenne_matiere":"14","appreciation":"Bien"},"23":{"moyenne_interro":11,"devoir1":"7","devoir2":"8","moyenne_matiere":"8.67","appreciation":"Insuffisant"},"24":{"moyenne_interro":14,"devoir1":"16","devoir2":"17","moyenne_matiere":"15.67","appreciation":"Bien"},"30":{"moyenne_interro":13,"devoir1":"10","devoir2":"10","moyenne_matiere":"11","appreciation":"Passable"}}	\N	\N	Élève Faible, Travail Insuffisant	8	9.07	17.91	0.00	10.51	10.35	redoublé
2318	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1ère	\N	0	0	212	[]	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2305	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	3	3	0	0	235	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2304	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	1	2	0	0	234	[]	\N	\N	-	6	\N	\N	0.00	0.00	0.00	redoublé
2309	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	2	4	0	0	236	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2311	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	6	5	0	0	237	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2307	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	3	3	0	0	235	[]	\N	\N	-	6	\N	\N	0.00	0.00	0.00	redoublé
2315	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	3	6	0	0	238	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2310	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	5	4	0	0	236	[]	\N	\N	-	6	\N	\N	0.00	0.00	0.00	redoublé
2302	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	5	2	0	0	234	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2316	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	4	6	0	0	238	[]	\N	\N	-	6	\N	\N	0.00	0.00	0.00	redoublé
2312	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	6	5	0	0	237	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2303	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	4	2	0	0	234	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2308	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	4	4	0	0	236	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2296	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	16	1	1	0	0	231	[]	\N	\N	Élève Faible, Travail Insuffisant	1	\N	\N	0.00	0.00	0.00	redoublé
2313	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	6	5	0	0	237	[]	\N	\N	-	6	\N	\N	0.00	0.00	0.00	redoublé
2306	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	1	3	0	0	235	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2314	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	1	6	0	0	238	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2297	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	16	1	1	0	0	231	[]	\N	\N	Élève Faible, Travail Insuffisant	1	\N	\N	0.00	0.00	0.00	redoublé
2298	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	16	1	1	0	0	231	[]	\N	\N	-	1	\N	\N	0.00	0.00	0.00	redoublé
2293	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	4	7	8	0	0	226	[]	\N	\N	Élève Faible, Travail Insuffisant	8	\N	\N	0.00	0.00	0.00	redoublé
2299	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	2	1	0	0	233	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
674	2	2	14.74	14.54	2026-03-21 01:47:21	2026-06-27 23:13:57	3	2ème	\N	13.19	15.49	80	{"17":{"moyenne_interro":18,"devoir1":"15","devoir2":"18","moyenne_matiere":"17","appreciation":"Tr\\u00e8s Bien"},"18":{"moyenne_interro":14.5,"devoir1":"10","devoir2":"11","moyenne_matiere":"11.83","appreciation":"Passable"},"19":{"moyenne_interro":15.5,"devoir1":"15","devoir2":"14.5","moyenne_matiere":"15","appreciation":"Bien"},"20":{"moyenne_interro":12.5,"devoir1":"18","devoir2":"16","moyenne_matiere":"15.5","appreciation":"Bien"},"21":{"moyenne_interro":18.5,"devoir1":"10","devoir2":"13","moyenne_matiere":"13.83","appreciation":"Assez Bien"},"22":{"moyenne_interro":14.75,"devoir1":"13","devoir2":"10","moyenne_matiere":"12.58","appreciation":"Assez Bien"},"23":{"moyenne_interro":13.5,"devoir1":"15","devoir2":"10","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"24":{"moyenne_interro":15.5,"devoir1":"14","devoir2":"15","moyenne_matiere":"14.83","appreciation":"Bien"},"30":{"moyenne_interro":17.33,"devoir1":"19","devoir2":"18","moyenne_matiere":"18.11","appreciation":"Excellent"}}	\N	\N	Élève Bon, Travail Bien	8	9.85	17.96	15.05	14.74	13.83	passé
683	3	2	10.15	10.31	2026-03-21 01:47:22	2026-06-27 23:13:57	3	7ème	7ème	8.74	10.24	82	{"17":{"moyenne_interro":15,"devoir1":"13","devoir2":"8.25","moyenne_matiere":"12.08","appreciation":"Assez Bien"},"18":{"moyenne_interro":12.5,"devoir1":"7","devoir2":"8","moyenne_matiere":"9.17","appreciation":"Insuffisant"},"19":{"moyenne_interro":10,"devoir1":"7.25","devoir2":"10","moyenne_matiere":"9.08","appreciation":"Insuffisant"},"20":{"moyenne_interro":11,"devoir1":"9","devoir2":"13","moyenne_matiere":"11","appreciation":"Passable"},"21":{"moyenne_interro":9.5,"devoir1":"10","devoir2":"7","moyenne_matiere":"8.83","appreciation":"Insuffisant"},"22":{"moyenne_interro":12,"devoir1":"8","devoir2":"7","moyenne_matiere":"9","appreciation":"Insuffisant"},"23":{"moyenne_interro":14,"devoir1":"5","devoir2":"6","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"24":{"moyenne_interro":14.5,"devoir1":"15","devoir2":"16","moyenne_matiere":"15.17","appreciation":"Bien"},"30":{"moyenne_interro":11.66,"devoir1":"10","devoir2":"8","moyenne_matiere":"9.89","appreciation":"Insuffisant"}}	\N	\N	Élève Moyen, Travail Passable	8	9.07	17.91	10.93	9.85	10.15	passé
679	3	2	9.07	10.15	2026-03-21 01:47:22	2026-06-27 23:13:57	3	8ème	8ème	7.33	9.26	81	{"17":{"moyenne_interro":14,"devoir1":"10","devoir2":"11","moyenne_matiere":"11.67","appreciation":"Passable"},"18":{"moyenne_interro":11,"devoir1":"8","devoir2":"8","moyenne_matiere":"9","appreciation":"Insuffisant"},"19":{"moyenne_interro":5.5,"devoir1":"8","devoir2":"5","moyenne_matiere":"6.17","appreciation":"Faible"},"20":{"moyenne_interro":12,"devoir1":"6","devoir2":"10","moyenne_matiere":"9.33","appreciation":"Insuffisant"},"21":{"moyenne_interro":9,"devoir1":"4","devoir2":"3","moyenne_matiere":"5.33","appreciation":"Tr\\u00e8s Faible"},"22":{"moyenne_interro":17,"devoir1":"7","devoir2":"6","moyenne_matiere":"10","appreciation":"Passable"},"24":{"moyenne_interro":11.5,"devoir1":"13","devoir2":"10","moyenne_matiere":"11.5","appreciation":"Passable"},"30":{"moyenne_interro":11.33,"devoir1":"11","devoir2":"8","moyenne_matiere":"10.11","appreciation":"Passable"},"23":{"moyenne_interro":11,"devoir1":"6","devoir2":"6","moyenne_matiere":"7.67","appreciation":"Faible"}}	\N	\N	Élève Moyen, Travail Passable	8	9.07	17.91	10.78	10.59	9.07	passé
2330	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	216	[]	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2324	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	214	[]	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2327	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	215	[]	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2320	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	213	{"17":{"moyenne_interro":null,"devoir1":null,"devoir2":null,"moyenne_matiere":null,"appreciation":"-"}}	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2338	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	1	1	0	0	227	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2340	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	1	1	0	0	227	[]	\N	\N	-	4	\N	\N	0.00	0.00	0.00	redoublé
2339	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	2	1	0	0	227	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2342	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	1	2	0	0	228	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2343	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	2	2	0	0	228	[]	\N	\N	-	4	\N	\N	0.00	0.00	0.00	redoublé
2336	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1ère	\N	0	0	218	[]	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2321	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	213	[]	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
1203	3	2	12.06	11.45	2026-05-08 22:33:38	2026-06-26 22:31:54	10	1er	1er	10.36	14.33	107	{"43":{"moyenne_interro":15,"devoir1":"10","devoir2":"12","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"44":{"moyenne_interro":18,"devoir1":"17","devoir2":"19","moyenne_matiere":"18","appreciation":"Excellent"},"46":{"moyenne_interro":16.5,"devoir1":"15","devoir2":"13","moyenne_matiere":"14.83","appreciation":"Bien"},"45":{"moyenne_interro":15.5,"devoir1":"8","devoir2":"13","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"47":{"moyenne_interro":7.5,"devoir1":"6","devoir2":"7","moyenne_matiere":"6.83","appreciation":"Faible"},"48":{"moyenne_interro":11,"devoir1":"8","devoir2":"9","moyenne_matiere":"9.33","appreciation":"Insuffisant"},"49":{"moyenne_interro":15,"devoir1":"13","devoir2":"14","moyenne_matiere":"14","appreciation":"Bien"},"50":{"moyenne_interro":null,"devoir1":null,"devoir2":null,"moyenne_matiere":null,"appreciation":"-"}}	\N	\N	Élève Moyen, Travail Passable	1	11.33	11.33	10.02	12.27	11.33	passé
2344	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	3	3	0	0	229	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2300	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	5	1	0	0	233	[]	\N	\N	Élève Faible, Travail Insuffisant	6	\N	\N	0.00	0.00	0.00	redoublé
2348	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	4	4	0	0	230	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2349	3	5	0.00	0.00	2026-06-16 01:00:19	2026-06-16 01:00:19	10	4	4	0	0	230	[]	\N	\N	-	4	\N	\N	0.00	0.00	0.00	redoublé
2347	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	4	4	0	0	230	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2301	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	9	2	1	0	0	233	[]	\N	\N	-	6	\N	\N	0.00	0.00	0.00	redoublé
2341	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	2	2	0	0	228	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2345	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	3	3	0	0	229	[]	\N	\N	Élève Faible, Travail Insuffisant	4	\N	\N	0.00	0.00	0.00	redoublé
2346	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-16 01:00:19	10	3	3	0	0	229	[]	\N	\N	-	4	\N	\N	0.00	0.00	0.00	redoublé
2333	2	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	217	[]	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2322	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	1er	0	0	213	[]	\N	\N	-	7	\N	\N	0.00	0.00	0.00	redoublé
2325	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	1er	0	0	214	[]	\N	\N	-	7	\N	\N	0.00	0.00	0.00	redoublé
2328	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	1er	0	0	215	[]	\N	\N	-	7	\N	\N	0.00	0.00	0.00	redoublé
2319	3	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1ère	1ère	0	0	212	[]	\N	\N	-	7	\N	\N	0.00	0.00	0.00	redoublé
681	1	2	10.93	10.31	2026-03-21 01:47:22	2026-06-27 23:13:57	3	5ème	\N	9.88	11	82	{"18":{"moyenne_interro":8,"devoir1":"10","devoir2":"10","moyenne_matiere":"9.33","appreciation":"Insuffisant"},"19":{"moyenne_interro":7,"devoir1":"15.25","devoir2":"10","moyenne_matiere":"10.75","appreciation":"Passable"},"20":{"moyenne_interro":15,"devoir1":"19.5","devoir2":"6","moyenne_matiere":"13.5","appreciation":"Assez Bien"},"21":{"moyenne_interro":8.5,"devoir1":"11","devoir2":"3","moyenne_matiere":"7.5","appreciation":"Faible"},"22":{"moyenne_interro":17.5,"devoir1":"13","devoir2":"14","moyenne_matiere":"14.83","appreciation":"Bien"},"23":{"moyenne_interro":11.5,"devoir1":"8","devoir2":"6","moyenne_matiere":"8.5","appreciation":"Insuffisant"},"24":{"moyenne_interro":13.5,"devoir1":"11","devoir2":"10","moyenne_matiere":"11.5","appreciation":"Passable"},"30":{"moyenne_interro":15,"devoir1":"14","devoir2":"6","moyenne_matiere":"11.67","appreciation":"Passable"},"17":{"moyenne_interro":9.5,"devoir1":"11.5","devoir2":"8.25","moyenne_matiere":"9.75","appreciation":"Insuffisant"}}	\N	\N	Élève Moyen, Travail Passable	8	10.46	17.89	10.93	9.85	10.15	passé
582	3	2	15.56	15.01	2026-03-20 22:13:36	2026-06-28 21:47:10	1	2ème	2ème	15.78	15.08	57	{"1":{"moyenne_interro":18,"devoir1":"14","devoir2":"15","moyenne_matiere":"15.67","appreciation":"Bien"},"2":{"moyenne_interro":18.5,"devoir1":"13","devoir2":"10","moyenne_matiere":"13.83","appreciation":"Assez Bien"},"3":{"moyenne_interro":16.5,"devoir1":"15","devoir2":"13.5","moyenne_matiere":"15","appreciation":"Bien"},"4":{"moyenne_interro":19.5,"devoir1":"17","devoir2":"11","moyenne_matiere":"15.83","appreciation":"Bien"},"5":{"moyenne_interro":18,"devoir1":"15","devoir2":"18","moyenne_matiere":"17","appreciation":"Tr\\u00e8s Bien"},"6":{"moyenne_interro":16,"devoir1":"14.5","devoir2":"15","moyenne_matiere":"15.17","appreciation":"Bien"},"7":{"moyenne_interro":17,"devoir1":"17.5","devoir2":"11","moyenne_matiere":"15.17","appreciation":"Bien"},"8":{"moyenne_interro":14,"devoir1":"13","devoir2":"16","moyenne_matiere":"14.33","appreciation":"Bien"}}	\N	\N	Élève Bon, Travail Bien	4	11.61	16.32	14.47	14.99	15.56	passé
2323	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	214	{"17":{"moyenne_interro":null,"devoir1":null,"devoir2":null,"moyenne_matiere":null,"appreciation":"-"}}	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2326	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	215	{"17":{"moyenne_interro":null,"devoir1":null,"devoir2":null,"moyenne_matiere":null,"appreciation":"-"}}	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2317	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1ère	\N	0	0	212	{"17":{"moyenne_interro":1.38,"devoir1":"0","devoir2":null,"moyenne_matiere":"0.69","appreciation":"M\\u00e9diocre"}}	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2335	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1ère	\N	0	0	218	{"17":{"moyenne_interro":null,"devoir1":null,"devoir2":null,"moyenne_matiere":null,"appreciation":"-"}}	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2332	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	217	{"17":{"moyenne_interro":null,"devoir1":null,"devoir2":null,"moyenne_matiere":null,"appreciation":"-"}}	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
2329	1	5	0.00	0.00	2026-06-16 01:00:18	2026-06-28 23:16:41	3	1er	\N	0	0	216	{"17":{"moyenne_interro":null,"devoir1":null,"devoir2":null,"moyenne_matiere":null,"appreciation":"-"}}	\N	\N	Élève Faible, Travail Insuffisant	7	\N	\N	0.00	0.00	0.00	redoublé
711	3	2	10.68	11.28	2026-03-21 01:47:22	2026-06-19 21:56:31	4	6ème	6ème	8.57	11.66	89	{"25":{"moyenne_interro":14.5,"devoir1":"15","devoir2":"13","moyenne_matiere":"14.17","appreciation":"Bien"},"26":{"moyenne_interro":12.5,"devoir1":"10","devoir2":"6","moyenne_matiere":"9.5","appreciation":"Insuffisant"},"27":{"moyenne_interro":14.5,"devoir1":"13","devoir2":"11","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"29":{"moyenne_interro":15.33,"devoir1":"10","devoir2":"14","moyenne_matiere":"13.11","appreciation":"Assez Bien"},"31":{"moyenne_interro":12.25,"devoir1":"6","devoir2":"4","moyenne_matiere":"7.42","appreciation":"Faible"},"32":{"moyenne_interro":11.5,"devoir1":"10","devoir2":"8","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"33":{"moyenne_interro":11.66,"devoir1":"11","devoir2":"4.5","moyenne_matiere":"9.05","appreciation":"Insuffisant"},"34":{"moyenne_interro":10,"devoir1":"12","devoir2":"12","moyenne_matiere":"11.33","appreciation":"Passable"},"28":{"moyenne_interro":16,"devoir1":"5.5","devoir2":"4.5","moyenne_matiere":"8.67","appreciation":"Insuffisant"}}	\N	\N	Élève Moyen, Travail Passable	7	9.64	12.86	11.42	11.75	10.68	passé
673	1	2	15.05	14.54	2026-03-21 01:47:21	2026-06-27 23:13:57	3	2ème	\N	14.55	15.15	80	{"18":{"moyenne_interro":12.75,"devoir1":"13","devoir2":"14","moyenne_matiere":"13.25","appreciation":"Assez Bien"},"19":{"moyenne_interro":16.5,"devoir1":"18","devoir2":"14","moyenne_matiere":"16.17","appreciation":"Tr\\u00e8s Bien"},"20":{"moyenne_interro":18.75,"devoir1":"20","devoir2":"10","moyenne_matiere":"16.25","appreciation":"Tr\\u00e8s Bien"},"21":{"moyenne_interro":12.5,"devoir1":"17","devoir2":"10","moyenne_matiere":"13.17","appreciation":"Assez Bien"},"22":{"moyenne_interro":17,"devoir1":"15","devoir2":"16","moyenne_matiere":"16","appreciation":"Tr\\u00e8s Bien"},"23":{"moyenne_interro":13,"devoir1":"16.5","devoir2":"16","moyenne_matiere":"15.17","appreciation":"Bien"},"24":{"moyenne_interro":17,"devoir1":"15","devoir2":"15","moyenne_matiere":"15.67","appreciation":"Bien"},"30":{"moyenne_interro":16.66,"devoir1":"16","devoir2":"15","moyenne_matiere":"15.89","appreciation":"Bien"},"17":{"moyenne_interro":12.5,"devoir1":"14","devoir2":"16","moyenne_matiere":"14.17","appreciation":"Bien"}}	\N	\N	Élève Bon, Travail Bien	8	10.46	17.89	15.05	14.74	13.83	passé
1201	1	2	10.02	11.21	2026-05-08 22:33:38	2026-06-26 22:31:54	10	1er	\N	8.09	12.4	107	{"43":{"moyenne_interro":16,"devoir1":"13","devoir2":"8","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"44":{"moyenne_interro":12.33,"devoir1":"18","devoir2":"17","moyenne_matiere":"15.78","appreciation":"Bien"},"46":{"moyenne_interro":10,"devoir1":"16","devoir2":"7","moyenne_matiere":"11","appreciation":"Passable"},"45":{"moyenne_interro":15.5,"devoir1":"9","devoir2":"7","moyenne_matiere":"10.5","appreciation":"Passable"},"47":{"moyenne_interro":7,"devoir1":"4","devoir2":"4","moyenne_matiere":"5","appreciation":"Tr\\u00e8s Faible"},"48":{"moyenne_interro":10.25,"devoir1":"6","devoir2":"7","moyenne_matiere":"7.75","appreciation":"Faible"},"49":{"moyenne_interro":15.5,"devoir1":"7","devoir2":"10","moyenne_matiere":"10.83","appreciation":"Passable"},"50":{"moyenne_interro":null,"devoir1":null,"devoir2":null,"moyenne_matiere":null,"appreciation":"-"}}	\N	\N	Élève Moyen, Travail Passable	1	10.02	10.02	10.02	12.27	11.33	passé
802	1	2	12.72	12.64	2026-03-21 18:07:28	2026-06-16 12:32:31	11	1	1	11.85	13.22	97	{"51":{"moyenne_interro":15.33,"devoir1":"13","devoir2":"9","moyenne_matiere":"12.44","appreciation":"Assez Bien"},"52":{"moyenne_interro":17.33,"devoir1":"18","devoir2":"17","moyenne_matiere":"17.44","appreciation":"Tr\\u00e8s Bien"},"53":{"moyenne_interro":15,"devoir1":"7","devoir2":"5","moyenne_matiere":"9","appreciation":"Insuffisant"},"54":{"moyenne_interro":10,"devoir1":"18","devoir2":"14","moyenne_matiere":"14","appreciation":"Bien"},"55":{"moyenne_interro":16,"devoir1":"14","devoir2":"9","moyenne_matiere":"13","appreciation":"Assez Bien"},"56":{"moyenne_interro":13.5,"devoir1":"9","devoir2":"7","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"57":{"moyenne_interro":18,"devoir1":"13","devoir2":"11","moyenne_matiere":"14","appreciation":"Bien"},"58":{"moyenne_interro":16,"devoir1":"14","devoir2":"18","moyenne_matiere":"16","appreciation":"Tr\\u00e8s Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	1	12.72	12.72	12.72	12.39	12.81	passé
577	2	2	9.14	10.46	2026-03-20 22:13:36	2026-06-28 21:47:10	1	4ème	\N	7.54	7.92	56	{"1":{"moyenne_interro":7.5,"devoir1":"6","devoir2":"10","moyenne_matiere":"7.83","appreciation":"Faible"},"2":{"moyenne_interro":5,"devoir1":"8","devoir2":"8","moyenne_matiere":"7","appreciation":"Faible"},"3":{"moyenne_interro":4.5,"devoir1":"5.5","devoir2":"11","moyenne_matiere":"7","appreciation":"Faible"},"4":{"moyenne_interro":16.5,"devoir1":"6","devoir2":"7","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"5":{"moyenne_interro":7.5,"devoir1":"6","devoir2":"6","moyenne_matiere":"6.5","appreciation":"Faible"},"6":{"moyenne_interro":4.33,"devoir1":"11","devoir2":"2.5","moyenne_matiere":"5.94","appreciation":"Tr\\u00e8s Faible"},"7":{"moyenne_interro":14.5,"devoir1":"9","devoir2":"7","moyenne_matiere":"10.17","appreciation":"Passable"},"8":{"moyenne_interro":8,"devoir1":"10","devoir2":"15","moyenne_matiere":"11","appreciation":"Passable"}}	\N	\N	Élève Faible, Travail Insuffisant	4	9.14	15.41	10.44	9.14	11.81	passé
586	3	2	11.61	10.42	2026-03-20 22:13:36	2026-06-28 21:47:10	1	4ème	4ème	10.91	9.98	58	{"1":{"moyenne_interro":8,"devoir1":"14","devoir2":"19","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"2":{"moyenne_interro":12.75,"devoir1":"6","devoir2":"7","moyenne_matiere":"8.58","appreciation":"Insuffisant"},"3":{"moyenne_interro":13.5,"devoir1":"1","devoir2":"8","moyenne_matiere":"7.5","appreciation":"Faible"},"4":{"moyenne_interro":14.5,"devoir1":"12","devoir2":"4","moyenne_matiere":"10.17","appreciation":"Passable"},"5":{"moyenne_interro":13,"devoir1":"12","devoir2":"9","moyenne_matiere":"11.33","appreciation":"Passable"},"6":{"moyenne_interro":10.66,"devoir1":"8.5","devoir2":"12","moyenne_matiere":"10.39","appreciation":"Passable"},"7":{"moyenne_interro":11,"devoir1":"11","devoir2":"11","moyenne_matiere":"11","appreciation":"Passable"},"8":{"moyenne_interro":11.5,"devoir1":"15","devoir2":"15","moyenne_matiere":"13.83","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	4	11.61	16.32	9.30	10.35	11.61	passé
622	3	2	16.02	15.90	2026-03-20 22:26:53	2026-06-27 13:06:13	2	2ème	2ème	16.5	15.5	76	{"9":{"moyenne_interro":17.5,"devoir1":"17","devoir2":"14","moyenne_matiere":"16.17","appreciation":"Tr\\u00e8s Bien"},"10":{"moyenne_interro":15.25,"devoir1":"11","devoir2":"13","moyenne_matiere":"13.08","appreciation":"Assez Bien"},"11":{"moyenne_interro":19,"devoir1":"14","devoir2":"18.75","moyenne_matiere":"17.25","appreciation":"Tr\\u00e8s Bien"},"12":{"moyenne_interro":12.5,"devoir1":"19","devoir2":"15","moyenne_matiere":"15.5","appreciation":"Bien"},"13":{"moyenne_interro":19,"devoir1":"17.5","devoir2":"15","moyenne_matiere":"17.17","appreciation":"Tr\\u00e8s Bien"},"14":{"moyenne_interro":18.5,"devoir1":"17","devoir2":"19.5","moyenne_matiere":"18.33","appreciation":"Excellent"},"15":{"moyenne_interro":12,"devoir1":"15","devoir2":"15","moyenne_matiere":"14","appreciation":"Bien"},"16":{"moyenne_interro":15,"devoir1":"13","devoir2":"16","moyenne_matiere":"14.67","appreciation":"Bien"}}	\N	\N	Élève Bon, Travail Bien	7	11.33	16.82	15.82	15.87	16.02	passé
714	2	2	13.07	13.10	2026-03-21 01:47:22	2026-06-19 21:56:31	4	2ème	\N	11.87	13.42	90	{"25":{"moyenne_interro":14,"devoir1":"9","devoir2":"10","moyenne_matiere":"11","appreciation":"Passable"},"26":{"moyenne_interro":12.5,"devoir1":"10","devoir2":"10","moyenne_matiere":"10.83","appreciation":"Passable"},"27":{"moyenne_interro":15.5,"devoir1":"16","devoir2":"13","moyenne_matiere":"14.83","appreciation":"Bien"},"28":{"moyenne_interro":14.5,"devoir1":"16.5","devoir2":"13","moyenne_matiere":"14.67","appreciation":"Bien"},"29":{"moyenne_interro":18.33,"devoir1":"19","devoir2":"10","moyenne_matiere":"15.78","appreciation":"Bien"},"31":{"moyenne_interro":18.5,"devoir1":"11.5","devoir2":"11","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"32":{"moyenne_interro":8.5,"devoir1":"14.5","devoir2":"7","moyenne_matiere":"10","appreciation":"Passable"},"33":{"moyenne_interro":15.66,"devoir1":"7.5","devoir2":"10","moyenne_matiere":"11.05","appreciation":"Passable"},"34":{"moyenne_interro":15,"devoir1":"14","devoir2":"13","moyenne_matiere":"14","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	8.78	13.11	13.36	13.07	12.86	passé
1202	2	2	12.27	11.21	2026-05-08 22:33:38	2026-06-26 22:31:54	10	1er	\N	11.26	13.46	107	{"43":{"moyenne_interro":12,"devoir1":"9","devoir2":"11","moyenne_matiere":"10.67","appreciation":"Passable"},"44":{"moyenne_interro":16,"devoir1":"19","devoir2":"17","moyenne_matiere":"17.33","appreciation":"Tr\\u00e8s Bien"},"46":{"moyenne_interro":14.5,"devoir1":"11","devoir2":"14","moyenne_matiere":"13.17","appreciation":"Assez Bien"},"45":{"moyenne_interro":15,"devoir1":"15","devoir2":"8","moyenne_matiere":"12.67","appreciation":"Assez Bien"},"47":{"moyenne_interro":10.25,"devoir1":"7","devoir2":"12","moyenne_matiere":"9.75","appreciation":"Insuffisant"},"48":{"moyenne_interro":10,"devoir1":"8","devoir2":"10","moyenne_matiere":"9.33","appreciation":"Insuffisant"},"49":{"moyenne_interro":13,"devoir1":"13","devoir2":"16","moyenne_matiere":"14","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	1	12.27	12.27	10.02	12.27	11.33	passé
722	2	2	9.15	9.61	2026-03-21 01:47:22	2026-06-16 23:30:41	9	5ème	\N	7.17	10.02	92	{"35":{"moyenne_interro":11.33,"devoir1":"6","devoir2":"11","moyenne_matiere":"9.44","appreciation":"Insuffisant"},"36":{"moyenne_interro":9,"devoir1":"10","devoir2":"11","moyenne_matiere":"10","appreciation":"Passable"},"37":{"moyenne_interro":14.5,"devoir1":"13","devoir2":"8","moyenne_matiere":"11.83","appreciation":"Passable"},"38":{"moyenne_interro":13,"devoir1":"3","devoir2":"5","moyenne_matiere":"7","appreciation":"Faible"},"39":{"moyenne_interro":10,"devoir1":"10.5","devoir2":"9.5","moyenne_matiere":"10","appreciation":"Passable"},"40":{"moyenne_interro":null,"devoir1":"3","devoir2":"5","moyenne_matiere":"4","appreciation":"Tr\\u00e8s Faible"},"41":{"moyenne_interro":6.5,"devoir1":"9","devoir2":"7","moyenne_matiere":"7.5","appreciation":"Faible"},"42":{"moyenne_interro":16,"devoir1":"15","devoir2":"14","moyenne_matiere":"15","appreciation":"Bien"}}	\N	\N	Élève Faible, Travail Insuffisant	5	9.15	12.40	10.25	9.15	9.42	redoublé
719	3	2	12.67	12.48	2026-03-21 01:47:22	2026-06-16 23:30:41	9	2ème	2ème	12.5	12.31	91	{"35":{"moyenne_interro":14.5,"devoir1":"10","devoir2":"10","moyenne_matiere":"11.5","appreciation":"Passable"},"36":{"moyenne_interro":17,"devoir1":"15","devoir2":"14","moyenne_matiere":"15.33","appreciation":"Bien"},"37":{"moyenne_interro":14.5,"devoir1":"6","devoir2":"11","moyenne_matiere":"10.5","appreciation":"Passable"},"38":{"moyenne_interro":13,"devoir1":"6","devoir2":"13","moyenne_matiere":"10.67","appreciation":"Passable"},"39":{"moyenne_interro":15.5,"devoir1":"12","devoir2":"13","moyenne_matiere":"13.5","appreciation":"Assez Bien"},"40":{"moyenne_interro":12,"devoir1":"9","devoir2":"12","moyenne_matiere":"11","appreciation":"Passable"},"41":{"moyenne_interro":15,"devoir1":"9","devoir2":"15","moyenne_matiere":"13","appreciation":"Assez Bien"},"42":{"moyenne_interro":12,"devoir1":"13","devoir2":"14","moyenne_matiere":"13","appreciation":"Assez Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	5	9.42	13.60	12.52	12.26	12.67	passé
616	1	2	10.78	11.34	2026-03-20 22:26:53	2026-06-27 13:06:13	2	6ème	\N	8.53	9.98	75	{"9":{"moyenne_interro":14.5,"devoir1":"7.5","devoir2":"15","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"10":{"moyenne_interro":12.5,"devoir1":"7","devoir2":"7","moyenne_matiere":"8.83","appreciation":"Insuffisant"},"11":{"moyenne_interro":7.5,"devoir1":"7.25","devoir2":"10","moyenne_matiere":"8.25","appreciation":"Insuffisant"},"12":{"moyenne_interro":9.5,"devoir1":"13","devoir2":"9","moyenne_matiere":"10.5","appreciation":"Passable"},"13":{"moyenne_interro":13,"devoir1":"5","devoir2":"10","moyenne_matiere":"9.33","appreciation":"Insuffisant"},"14":{"moyenne_interro":8,"devoir1":"10","devoir2":"7","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"15":{"moyenne_interro":10.75,"devoir1":"4","devoir2":"9","moyenne_matiere":"7.92","appreciation":"Faible"},"16":{"moyenne_interro":14.5,"devoir1":"14","devoir2":"12","moyenne_matiere":"13.5","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	10.66	16.44	10.78	11.06	12.17	passé
706	2	2	11.95	12.05	2026-03-21 01:47:22	2026-06-19 21:56:31	4	5ème	\N	11.12	11.92	88	{"25":{"moyenne_interro":13,"devoir1":"8.5","devoir2":"7","moyenne_matiere":"9.5","appreciation":"Insuffisant"},"26":{"moyenne_interro":11.5,"devoir1":"10","devoir2":"9","moyenne_matiere":"10.17","appreciation":"Passable"},"27":{"moyenne_interro":15,"devoir1":"12","devoir2":"9","moyenne_matiere":"12","appreciation":"Assez Bien"},"28":{"moyenne_interro":11,"devoir1":"11","devoir2":"15.5","moyenne_matiere":"12.5","appreciation":"Assez Bien"},"29":{"moyenne_interro":17.33,"devoir1":"16","devoir2":"13","moyenne_matiere":"15.44","appreciation":"Bien"},"31":{"moyenne_interro":12.5,"devoir1":"12","devoir2":"6","moyenne_matiere":"10.17","appreciation":"Passable"},"32":{"moyenne_interro":8.5,"devoir1":"17","devoir2":"7","moyenne_matiere":"10.83","appreciation":"Passable"},"33":{"moyenne_interro":15.5,"devoir1":"8.5","devoir2":"14.5","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"34":{"moyenne_interro":12,"devoir1":"12","devoir2":"15","moyenne_matiere":"13","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	8.78	13.11	11.53	11.95	12.68	passé
590	3	2	16.32	15.90	2026-03-20 22:13:36	2026-06-28 21:47:10	1	1ère	1ère	16.72	16.04	59	{"1":{"moyenne_interro":20,"devoir1":"14","devoir2":"16","moyenne_matiere":"16.67","appreciation":"Tr\\u00e8s Bien"},"2":{"moyenne_interro":15,"devoir1":"11","devoir2":"10","moyenne_matiere":"12","appreciation":"Assez Bien"},"3":{"moyenne_interro":19,"devoir1":"16.5","devoir2":"15.5","moyenne_matiere":"17","appreciation":"Tr\\u00e8s Bien"},"4":{"moyenne_interro":19.5,"devoir1":"18","devoir2":"18","moyenne_matiere":"18.5","appreciation":"Excellent"},"5":{"moyenne_interro":20,"devoir1":"17.5","devoir2":"19","moyenne_matiere":"18.83","appreciation":"Excellent"},"6":{"moyenne_interro":17,"devoir1":"12","devoir2":"18","moyenne_matiere":"15.67","appreciation":"Bien"},"7":{"moyenne_interro":17,"devoir1":"16","devoir2":"14","moyenne_matiere":"15.67","appreciation":"Bien"},"8":{"moyenne_interro":13.5,"devoir1":"16","devoir2":"14","moyenne_matiere":"14.5","appreciation":"Bien"}}	\N	\N	Élève Bon, Travail Bien	4	11.61	16.32	15.98	15.41	16.32	passé
734	2	2	10.98	11.20	2026-03-21 01:47:23	2026-06-16 23:30:41	9	4ème	\N	10.05	11.46	95	{"35":{"moyenne_interro":13.33,"devoir1":"5","devoir2":"11","moyenne_matiere":"9.78","appreciation":"Insuffisant"},"36":{"moyenne_interro":12.5,"devoir1":"14","devoir2":"14","moyenne_matiere":"13.5","appreciation":"Assez Bien"},"37":{"moyenne_interro":14,"devoir1":"12","devoir2":"10","moyenne_matiere":"12","appreciation":"Assez Bien"},"38":{"moyenne_interro":7,"devoir1":"9","devoir2":"8","moyenne_matiere":"8","appreciation":"Insuffisant"},"39":{"moyenne_interro":12.5,"devoir1":"7.5","devoir2":"12.5","moyenne_matiere":"10.83","appreciation":"Passable"},"40":{"moyenne_interro":11,"devoir1":"4","devoir2":"9","moyenne_matiere":"8","appreciation":"Insuffisant"},"41":{"moyenne_interro":14,"devoir1":"9","devoir2":"11","moyenne_matiere":"11.33","appreciation":"Passable"},"42":{"moyenne_interro":11,"devoir1":"11","devoir2":"12","moyenne_matiere":"11.33","appreciation":"Passable"}}	\N	\N	Élève Moyen, Travail Passable	5	9.15	12.40	11.29	10.98	11.33	passé
598	3	2	13.83	13.70	2026-03-20 22:26:53	2026-06-27 13:06:13	2	4ème	3ème	11.53	14.4	70	{"9":{"moyenne_interro":16,"devoir1":"16","devoir2":"13","moyenne_matiere":"15","appreciation":"Bien"},"10":{"moyenne_interro":10.5,"devoir1":"11","devoir2":"10","moyenne_matiere":"10.5","appreciation":"Passable"},"11":{"moyenne_interro":16.5,"devoir1":"14.25","devoir2":"16","moyenne_matiere":"15.58","appreciation":"Bien"},"12":{"moyenne_interro":16.5,"devoir1":"18","devoir2":"15","moyenne_matiere":"16.5","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":11,"devoir1":"15","devoir2":"9","moyenne_matiere":"11.67","appreciation":"Passable"},"14":{"moyenne_interro":16,"devoir1":"8","devoir2":"10.5","moyenne_matiere":"11.5","appreciation":"Passable"},"15":{"moyenne_interro":15.25,"devoir1":"8","devoir2":"11","moyenne_matiere":"11.42","appreciation":"Passable"},"16":{"moyenne_interro":13,"devoir1":"14","devoir2":"16","moyenne_matiere":"14.33","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	11.33	16.82	12.74	14.52	13.83	passé
610	3	2	13.96	13.63	2026-03-20 22:26:53	2026-06-27 13:06:13	2	3ème	4ème	12.64	13.4	73	{"9":{"moyenne_interro":17.5,"devoir1":"18.5","devoir2":"18","moyenne_matiere":"18","appreciation":"Excellent"},"10":{"moyenne_interro":3.75,"devoir1":"10","devoir2":"10","moyenne_matiere":"7.92","appreciation":"Faible"},"11":{"moyenne_interro":13,"devoir1":"8","devoir2":"15.5","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"12":{"moyenne_interro":17.5,"devoir1":"16","devoir2":"13","moyenne_matiere":"15.5","appreciation":"Bien"},"13":{"moyenne_interro":14,"devoir1":"12.5","devoir2":"9","moyenne_matiere":"11.83","appreciation":"Passable"},"14":{"moyenne_interro":17,"devoir1":"8","devoir2":"12","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"15":{"moyenne_interro":11.25,"devoir1":"15","devoir2":"15","moyenne_matiere":"13.75","appreciation":"Assez Bien"},"16":{"moyenne_interro":15.5,"devoir1":"16","devoir2":"17","moyenne_matiere":"16.17","appreciation":"Tr\\u00e8s Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	11.33	16.82	12.56	14.38	13.96	passé
602	3	2	11.33	11.88	2026-03-20 22:26:53	2026-06-27 13:06:13	2	7ème	5ème	10.17	10.9	71	{"9":{"moyenne_interro":11,"devoir1":"16","devoir2":"15","moyenne_matiere":"14","appreciation":"Bien"},"10":{"moyenne_interro":8.5,"devoir1":"8","devoir2":"8","moyenne_matiere":"8.17","appreciation":"Insuffisant"},"11":{"moyenne_interro":15.5,"devoir1":"6.25","devoir2":"10.5","moyenne_matiere":"10.75","appreciation":"Passable"},"12":{"moyenne_interro":12,"devoir1":"13","devoir2":"7","moyenne_matiere":"10.67","appreciation":"Passable"},"13":{"moyenne_interro":13,"devoir1":"9","devoir2":"4","moyenne_matiere":"8.67","appreciation":"Insuffisant"},"14":{"moyenne_interro":12,"devoir1":"12","devoir2":"12.5","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"15":{"moyenne_interro":12,"devoir1":"10","devoir2":"7","moyenne_matiere":"9.67","appreciation":"Insuffisant"},"16":{"moyenne_interro":15.5,"devoir1":"15","devoir2":"17","moyenne_matiere":"15.83","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	11.33	16.82	12.81	11.51	11.33	passé
618	3	2	12.17	11.34	2026-03-20 22:26:53	2026-06-27 13:06:13	2	5ème	6ème	10.89	11.21	75	{"9":{"moyenne_interro":12.5,"devoir1":"17.5","devoir2":"16","moyenne_matiere":"15.33","appreciation":"Bien"},"10":{"moyenne_interro":11,"devoir1":"6","devoir2":"8","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"11":{"moyenne_interro":15.5,"devoir1":"8.5","devoir2":"12.5","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"12":{"moyenne_interro":8,"devoir1":"7","devoir2":"12","moyenne_matiere":"9","appreciation":"Insuffisant"},"13":{"moyenne_interro":18,"devoir1":"10","devoir2":"10","moyenne_matiere":"12.67","appreciation":"Assez Bien"},"14":{"moyenne_interro":13,"devoir1":"12","devoir2":"9","moyenne_matiere":"11.33","appreciation":"Passable"},"15":{"moyenne_interro":10,"devoir1":"8","devoir2":"8","moyenne_matiere":"8.67","appreciation":"Insuffisant"},"16":{"moyenne_interro":13,"devoir1":"14","devoir2":"15","moyenne_matiere":"14","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	11.33	16.82	10.78	11.06	12.17	passé
604	1	2	16.44	16.76	2026-03-20 22:26:53	2026-06-27 13:06:13	2	1er	\N	16.05	16.08	72	{"9":{"moyenne_interro":16.5,"devoir1":"17.5","devoir2":"18","moyenne_matiere":"17.33","appreciation":"Tr\\u00e8s Bien"},"10":{"moyenne_interro":20,"devoir1":"10","devoir2":"12","moyenne_matiere":"14","appreciation":"Bien"},"11":{"moyenne_interro":18,"devoir1":"14","devoir2":"16","moyenne_matiere":"16","appreciation":"Tr\\u00e8s Bien"},"12":{"moyenne_interro":18,"devoir1":"19","devoir2":"14","moyenne_matiere":"17","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":15.5,"devoir1":"17.5","devoir2":"17.5","moyenne_matiere":"16.83","appreciation":"Tr\\u00e8s Bien"},"14":{"moyenne_interro":17.5,"devoir1":"18.5","devoir2":"16","moyenne_matiere":"17.33","appreciation":"Tr\\u00e8s Bien"},"15":{"moyenne_interro":16,"devoir1":"10","devoir2":"16","moyenne_matiere":"14","appreciation":"Bien"},"16":{"moyenne_interro":16.5,"devoir1":"18","devoir2":"18","moyenne_matiere":"17.5","appreciation":"Tr\\u00e8s Bien"}}	\N	\N	Élève Très Bon, Travail Très Bien	7	10.66	16.44	16.44	17.01	16.82	passé
717	1	2	12.52	12.48	2026-03-21 01:47:22	2026-06-16 23:30:41	9	1ère	\N	12.39	11.6	91	{"35":{"moyenne_interro":12,"devoir1":"10","devoir2":"10","moyenne_matiere":"10.67","appreciation":"Passable"},"36":{"moyenne_interro":10.66,"devoir1":"9","devoir2":"14","moyenne_matiere":"11.22","appreciation":"Passable"},"38":{"moyenne_interro":10.5,"devoir1":"11","devoir2":"15","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"39":{"moyenne_interro":17,"devoir1":"13","devoir2":"8","moyenne_matiere":"12.67","appreciation":"Assez Bien"},"40":{"moyenne_interro":11,"devoir1":"11","devoir2":"10","moyenne_matiere":"10.67","appreciation":"Passable"},"41":{"moyenne_interro":14,"devoir1":"12.5","devoir2":"15","moyenne_matiere":"13.83","appreciation":"Assez Bien"},"42":{"moyenne_interro":16,"devoir1":"13","devoir2":"18","moyenne_matiere":"15.67","appreciation":"Bien"},"37":{"moyenne_interro":13.5,"devoir1":"13","devoir2":"10","moyenne_matiere":"12.17","appreciation":"Assez Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	5	10.06	12.52	12.52	12.26	12.67	passé
733	1	2	11.29	11.20	2026-03-21 01:47:23	2026-06-16 23:30:41	9	3ème	\N	9.92	12.5	95	{"35":{"moyenne_interro":16,"devoir1":"11","devoir2":"12","moyenne_matiere":"13","appreciation":"Assez Bien"},"36":{"moyenne_interro":10,"devoir1":"16","devoir2":"16","moyenne_matiere":"14","appreciation":"Bien"},"38":{"moyenne_interro":11,"devoir1":"11","devoir2":"5","moyenne_matiere":"9","appreciation":"Insuffisant"},"39":{"moyenne_interro":16,"devoir1":"6.5","devoir2":"8","moyenne_matiere":"10.17","appreciation":"Passable"},"40":{"moyenne_interro":9.5,"devoir1":"8","devoir2":"10","moyenne_matiere":"9.17","appreciation":"Insuffisant"},"41":{"moyenne_interro":11,"devoir1":"8.25","devoir2":"12","moyenne_matiere":"10.42","appreciation":"Passable"},"42":{"moyenne_interro":15,"devoir1":"13","devoir2":"7","moyenne_matiere":"11.67","appreciation":"Passable"},"37":{"moyenne_interro":11.5,"devoir1":"13","devoir2":"13","moyenne_matiere":"12.5","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	5	10.06	12.52	11.29	10.98	11.33	passé
721	1	2	10.25	9.61	2026-03-21 01:47:22	2026-06-16 23:30:41	9	4ème	\N	8.28	12.3	92	{"35":{"moyenne_interro":10.66,"devoir1":"14","devoir2":"10","moyenne_matiere":"11.55","appreciation":"Passable"},"36":{"moyenne_interro":10.33,"devoir1":"14","devoir2":"8","moyenne_matiere":"10.78","appreciation":"Passable"},"38":{"moyenne_interro":11,"devoir1":"12","devoir2":"10","moyenne_matiere":"11","appreciation":"Passable"},"39":{"moyenne_interro":11.5,"devoir1":"11","devoir2":"6","moyenne_matiere":"9.5","appreciation":"Insuffisant"},"40":{"moyenne_interro":6,"devoir1":"6","devoir2":"4","moyenne_matiere":"5.33","appreciation":"Tr\\u00e8s Faible"},"41":{"moyenne_interro":11,"devoir1":"8","devoir2":"11","moyenne_matiere":"10","appreciation":"Passable"},"42":{"moyenne_interro":10,"devoir1":"10","devoir2":"10","moyenne_matiere":"10","appreciation":"Passable"},"37":{"moyenne_interro":13.5,"devoir1":"16","devoir2":"15","moyenne_matiere":"14.83","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	5	10.06	12.52	10.25	9.15	9.42	redoublé
725	1	2	10.06	11.19	2026-03-21 01:47:23	2026-06-16 23:30:41	9	5ème	\N	8.22	11.68	93	{"35":{"moyenne_interro":12.23,"devoir1":"12","devoir2":"8","moyenne_matiere":"10.74","appreciation":"Passable"},"36":{"moyenne_interro":11,"devoir1":"16","devoir2":"16","moyenne_matiere":"14.33","appreciation":"Bien"},"38":{"moyenne_interro":9,"devoir1":"10","devoir2":"11","moyenne_matiere":"10","appreciation":"Passable"},"39":{"moyenne_interro":8.5,"devoir1":"7","devoir2":"8","moyenne_matiere":"7.83","appreciation":"Faible"},"40":{"moyenne_interro":7.5,"devoir1":"8","devoir2":"11","moyenne_matiere":"8.83","appreciation":"Insuffisant"},"41":{"moyenne_interro":9,"devoir1":"8","devoir2":"7","moyenne_matiere":"8","appreciation":"Insuffisant"},"42":{"moyenne_interro":12,"devoir1":"11","devoir2":"10","moyenne_matiere":"11","appreciation":"Passable"},"37":{"moyenne_interro":12,"devoir1":"7","devoir2":"12","moyenne_matiere":"10.33","appreciation":"Passable"}}	\N	\N	Élève Moyen, Travail Passable	5	10.06	12.52	10.06	11.11	12.41	passé
730	2	2	12.40	12.74	2026-03-21 01:47:23	2026-06-16 23:30:41	9	1er	\N	12.33	11.57	94	{"35":{"moyenne_interro":13.33,"devoir1":"6","devoir2":"11","moyenne_matiere":"10.11","appreciation":"Passable"},"36":{"moyenne_interro":11,"devoir1":"15","devoir2":"16","moyenne_matiere":"14","appreciation":"Bien"},"37":{"moyenne_interro":12.5,"devoir1":"10","devoir2":"9","moyenne_matiere":"10.5","appreciation":"Passable"},"38":{"moyenne_interro":14,"devoir1":"7","devoir2":"10","moyenne_matiere":"10.33","appreciation":"Passable"},"39":{"moyenne_interro":15.5,"devoir1":"10","devoir2":"10.5","moyenne_matiere":"12","appreciation":"Assez Bien"},"40":{"moyenne_interro":15,"devoir1":"7","devoir2":"13","moyenne_matiere":"11.67","appreciation":"Passable"},"41":{"moyenne_interro":14,"devoir1":"16","devoir2":"10","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"42":{"moyenne_interro":13,"devoir1":"14","devoir2":"16","moyenne_matiere":"14.33","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	5	9.15	12.40	12.21	12.40	13.60	passé
718	2	2	12.26	12.48	2026-03-21 01:47:22	2026-06-16 23:30:41	9	2ème	\N	12.78	10.97	91	{"35":{"moyenne_interro":14,"devoir1":"10","devoir2":"8","moyenne_matiere":"10.67","appreciation":"Passable"},"36":{"moyenne_interro":14.5,"devoir1":"14","devoir2":"9","moyenne_matiere":"12.5","appreciation":"Assez Bien"},"37":{"moyenne_interro":11,"devoir1":"7","devoir2":"11","moyenne_matiere":"9.67","appreciation":"Insuffisant"},"38":{"moyenne_interro":12.5,"devoir1":"9","devoir2":"11","moyenne_matiere":"10.83","appreciation":"Passable"},"39":{"moyenne_interro":16.5,"devoir1":"11","devoir2":"13","moyenne_matiere":"13.5","appreciation":"Assez Bien"},"40":{"moyenne_interro":13.5,"devoir1":"12","devoir2":"13","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"41":{"moyenne_interro":15,"devoir1":"10","devoir2":"11","moyenne_matiere":"12","appreciation":"Assez Bien"},"42":{"moyenne_interro":12,"devoir1":"14","devoir2":"9","moyenne_matiere":"11.67","appreciation":"Passable"}}	\N	\N	Élève Correct, Travail Assez Bien	5	9.15	12.40	12.52	12.26	12.67	passé
726	2	2	11.11	11.19	2026-03-21 01:47:23	2026-06-16 23:30:41	9	3ème	\N	9.42	12.58	93	{"35":{"moyenne_interro":12.33,"devoir1":"11","devoir2":"6","moyenne_matiere":"9.78","appreciation":"Insuffisant"},"36":{"moyenne_interro":16.5,"devoir1":"16","devoir2":"14","moyenne_matiere":"15.5","appreciation":"Bien"},"37":{"moyenne_interro":13.5,"devoir1":"10","devoir2":"10","moyenne_matiere":"11.17","appreciation":"Passable"},"38":{"moyenne_interro":12,"devoir1":"11","devoir2":"14","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"39":{"moyenne_interro":8.75,"devoir1":"6.5","devoir2":"11","moyenne_matiere":"8.75","appreciation":"Insuffisant"},"40":{"moyenne_interro":10,"devoir1":"7","devoir2":"10","moyenne_matiere":"9","appreciation":"Insuffisant"},"41":{"moyenne_interro":13.5,"devoir1":"9","devoir2":"9","moyenne_matiere":"10.5","appreciation":"Passable"},"42":{"moyenne_interro":14,"devoir1":"14","devoir2":"10","moyenne_matiere":"12.67","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	5	9.15	12.40	10.06	11.11	12.41	passé
731	3	2	13.60	12.74	2026-03-21 01:47:23	2026-06-16 23:30:41	9	1er	1er	13.26	13.64	94	{"35":{"moyenne_interro":12.5,"devoir1":"9","devoir2":"15","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"36":{"moyenne_interro":19,"devoir1":"16","devoir2":"15","moyenne_matiere":"16.67","appreciation":"Tr\\u00e8s Bien"},"37":{"moyenne_interro":15,"devoir1":"9","devoir2":"11","moyenne_matiere":"11.67","appreciation":"Passable"},"38":{"moyenne_interro":14,"devoir1":"11","devoir2":"14","moyenne_matiere":"13","appreciation":"Assez Bien"},"39":{"moyenne_interro":16,"devoir1":"5","devoir2":"12.5","moyenne_matiere":"11.17","appreciation":"Passable"},"40":{"moyenne_interro":16,"devoir1":"12","devoir2":"9","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"41":{"moyenne_interro":17.33,"devoir1":"15.5","devoir2":"16","moyenne_matiere":"16.28","appreciation":"Tr\\u00e8s Bien"},"42":{"moyenne_interro":12,"devoir1":"16","devoir2":"14","moyenne_matiere":"14","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	5	9.42	13.60	12.21	12.40	13.60	passé
735	3	2	11.33	11.20	2026-03-21 01:47:23	2026-06-16 23:30:41	9	4ème	3ème	9.98	12.42	95	{"35":{"moyenne_interro":14.5,"devoir1":"10","devoir2":"7","moyenne_matiere":"10.5","appreciation":"Passable"},"36":{"moyenne_interro":15,"devoir1":"15","devoir2":"15","moyenne_matiere":"15","appreciation":"Bien"},"37":{"moyenne_interro":15,"devoir1":"8","devoir2":"11","moyenne_matiere":"11.33","appreciation":"Passable"},"38":{"moyenne_interro":12,"devoir1":"12","devoir2":"10","moyenne_matiere":"11.33","appreciation":"Passable"},"39":{"moyenne_interro":11.5,"devoir1":"13","devoir2":"11","moyenne_matiere":"11.83","appreciation":"Passable"},"40":{"moyenne_interro":8,"devoir1":"7","devoir2":"10","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"41":{"moyenne_interro":13.33,"devoir1":"7","devoir2":"9","moyenne_matiere":"9.78","appreciation":"Insuffisant"},"42":{"moyenne_interro":12,"devoir1":"11","devoir2":"14","moyenne_matiere":"12.33","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	5	9.42	13.60	11.29	10.98	11.33	passé
727	3	2	12.41	11.19	2026-03-21 01:47:23	2026-06-16 23:30:41	9	3ème	4ème	11.3	13.5	93	{"35":{"moyenne_interro":15.5,"devoir1":"10","devoir2":"10","moyenne_matiere":"11.83","appreciation":"Passable"},"36":{"moyenne_interro":15.5,"devoir1":"16","devoir2":"17","moyenne_matiere":"16.17","appreciation":"Tr\\u00e8s Bien"},"37":{"moyenne_interro":15,"devoir1":"10","devoir2":"11","moyenne_matiere":"12","appreciation":"Assez Bien"},"38":{"moyenne_interro":12.5,"devoir1":"12","devoir2":"14","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"39":{"moyenne_interro":14,"devoir1":"14","devoir2":"13","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"40":{"moyenne_interro":11,"devoir1":"9","devoir2":"11","moyenne_matiere":"10.33","appreciation":"Passable"},"41":{"moyenne_interro":13.66,"devoir1":"4","devoir2":"12","moyenne_matiere":"9.89","appreciation":"Insuffisant"},"42":{"moyenne_interro":11,"devoir1":"12","devoir2":"14","moyenne_matiere":"12.33","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	5	9.42	13.60	10.06	11.11	12.41	passé
723	3	2	9.42	9.61	2026-03-21 01:47:22	2026-06-16 23:30:41	9	5ème	5ème	7.06	11.28	92	{"35":{"moyenne_interro":13,"devoir1":"8","devoir2":"10","moyenne_matiere":"10.33","appreciation":"Passable"},"36":{"moyenne_interro":10.5,"devoir1":"10","devoir2":"12","moyenne_matiere":"10.83","appreciation":"Passable"},"37":{"moyenne_interro":15.5,"devoir1":"11","devoir2":"12","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"38":{"moyenne_interro":14,"devoir1":"6","devoir2":"10","moyenne_matiere":"10","appreciation":"Passable"},"39":{"moyenne_interro":11.5,"devoir1":"4.5","devoir2":"8.5","moyenne_matiere":"8.17","appreciation":"Insuffisant"},"40":{"moyenne_interro":8,"devoir1":"4","devoir2":"5","moyenne_matiere":"5.67","appreciation":"Tr\\u00e8s Faible"},"41":{"moyenne_interro":11,"devoir1":"5","devoir2":"6","moyenne_matiere":"7.33","appreciation":"Faible"},"42":{"moyenne_interro":13,"devoir1":"12","devoir2":"14","moyenne_matiere":"13","appreciation":"Assez Bien"}}	\N	\N	Élève Faible, Travail Insuffisant	5	9.42	13.60	10.25	9.15	9.42	redoublé
715	3	2	12.86	13.10	2026-03-21 01:47:22	2026-06-19 21:56:31	4	1ère	1ère	12.02	13.36	90	{"25":{"moyenne_interro":16.5,"devoir1":"11","devoir2":"14","moyenne_matiere":"13.83","appreciation":"Assez Bien"},"26":{"moyenne_interro":12,"devoir1":"13","devoir2":"10","moyenne_matiere":"11.67","appreciation":"Passable"},"27":{"moyenne_interro":14.5,"devoir1":"13","devoir2":"13","moyenne_matiere":"13.5","appreciation":"Assez Bien"},"29":{"moyenne_interro":16.33,"devoir1":"15","devoir2":"17","moyenne_matiere":"16.11","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":17.5,"devoir1":"11","devoir2":"11","moyenne_matiere":"13.17","appreciation":"Assez Bien"},"32":{"moyenne_interro":13,"devoir1":"10.5","devoir2":"12","moyenne_matiere":"11.83","appreciation":"Passable"},"33":{"moyenne_interro":11.5,"devoir1":"10.5","devoir2":"9.5","moyenne_matiere":"10.5","appreciation":"Passable"},"34":{"moyenne_interro":10,"devoir1":"13","devoir2":"12","moyenne_matiere":"11.67","appreciation":"Passable"},"28":{"moyenne_interro":18,"devoir1":"8","devoir2":"9","moyenne_matiere":"11.67","appreciation":"Passable"}}	\N	\N	Élève Correct, Travail Assez Bien	7	9.64	12.86	13.36	13.07	12.86	passé
606	3	2	16.82	16.76	2026-03-20 22:26:53	2026-06-27 13:06:13	2	1er	1er	17.11	16.69	72	{"9":{"moyenne_interro":17.5,"devoir1":"18.5","devoir2":"16","moyenne_matiere":"17.33","appreciation":"Tr\\u00e8s Bien"},"10":{"moyenne_interro":17.25,"devoir1":"11","devoir2":"14","moyenne_matiere":"14.08","appreciation":"Bien"},"11":{"moyenne_interro":19,"devoir1":"16","devoir2":"17.5","moyenne_matiere":"17.5","appreciation":"Tr\\u00e8s Bien"},"12":{"moyenne_interro":17.5,"devoir1":"18","devoir2":"18","moyenne_matiere":"17.83","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":17,"devoir1":"14","devoir2":"17","moyenne_matiere":"16","appreciation":"Tr\\u00e8s Bien"},"14":{"moyenne_interro":18.5,"devoir1":"18","devoir2":"15.5","moyenne_matiere":"17.33","appreciation":"Tr\\u00e8s Bien"},"15":{"moyenne_interro":15,"devoir1":"20","devoir2":"19","moyenne_matiere":"18","appreciation":"Excellent"},"16":{"moyenne_interro":15,"devoir1":"15","devoir2":"16","moyenne_matiere":"15.33","appreciation":"Bien"}}	\N	\N	Élève Très Bon, Travail Très Bien	7	11.33	16.82	16.44	17.01	16.82	passé
713	1	2	13.36	13.10	2026-03-21 01:47:22	2026-06-19 21:56:31	4	1ère	\N	11.26	14.48	90	{"25":{"moyenne_interro":15.5,"devoir1":"14","devoir2":"11.5","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"26":{"moyenne_interro":11.5,"devoir1":"11","devoir2":"13","moyenne_matiere":"11.83","appreciation":"Passable"},"27":{"moyenne_interro":17,"devoir1":"16","devoir2":"15","moyenne_matiere":"16","appreciation":"Tr\\u00e8s Bien"},"28":{"moyenne_interro":12,"devoir1":"14.5","devoir2":"12.5","moyenne_matiere":"13","appreciation":"Assez Bien"},"29":{"moyenne_interro":18.66,"devoir1":"17","devoir2":"18","moyenne_matiere":"17.89","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":17.25,"devoir1":"7","devoir2":"14.5","moyenne_matiere":"12.92","appreciation":"Assez Bien"},"32":{"moyenne_interro":7.75,"devoir1":"9","devoir2":"7","moyenne_matiere":"7.92","appreciation":"Faible"},"33":{"moyenne_interro":16.83,"devoir1":"9","devoir2":"10.5","moyenne_matiere":"12.11","appreciation":"Assez Bien"},"34":{"moyenne_interro":17,"devoir1":"11","devoir2":"18","moyenne_matiere":"15.33","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	9.29	13.36	13.36	13.07	12.86	passé
693	1	2	12.65	12.66	2026-03-21 01:47:22	2026-06-19 21:56:31	4	2ème	\N	10.52	13.53	85	{"25":{"moyenne_interro":11.5,"devoir1":"14","devoir2":"10.5","moyenne_matiere":"12","appreciation":"Assez Bien"},"26":{"moyenne_interro":13.5,"devoir1":"13","devoir2":"10","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"27":{"moyenne_interro":16.5,"devoir1":"17","devoir2":"9","moyenne_matiere":"14.17","appreciation":"Bien"},"28":{"moyenne_interro":12.66,"devoir1":"10","devoir2":"13","moyenne_matiere":"11.89","appreciation":"Passable"},"29":{"moyenne_interro":18.33,"devoir1":"18","devoir2":"16","moyenne_matiere":"17.44","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":15.5,"devoir1":"6","devoir2":"11","moyenne_matiere":"10.83","appreciation":"Passable"},"32":{"moyenne_interro":7.75,"devoir1":"8","devoir2":"10","moyenne_matiere":"8.58","appreciation":"Insuffisant"},"33":{"moyenne_interro":16.5,"devoir1":"10","devoir2":"9.5","moyenne_matiere":"12","appreciation":"Assez Bien"},"34":{"moyenne_interro":17,"devoir1":"14","devoir2":"18","moyenne_matiere":"16.33","appreciation":"Tr\\u00e8s Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	9.29	13.36	12.65	13.11	12.23	passé
689	1	2	11.73	11.83	2026-03-21 01:47:22	2026-06-19 21:56:31	4	3ème	\N	7.63	14.12	84	{"25":{"moyenne_interro":17,"devoir1":"19.5","devoir2":"13","moyenne_matiere":"16.5","appreciation":"Tr\\u00e8s Bien"},"26":{"moyenne_interro":13,"devoir1":"13","devoir2":"11","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"27":{"moyenne_interro":13,"devoir1":"14","devoir2":"10","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"28":{"moyenne_interro":11,"devoir1":"11.5","devoir2":"14.5","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"29":{"moyenne_interro":17.33,"devoir1":"18","devoir2":"16","moyenne_matiere":"17.11","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":9,"devoir1":"5","devoir2":"7","moyenne_matiere":"7","appreciation":"Faible"},"32":{"moyenne_interro":9.25,"devoir1":"6","devoir2":"7","moyenne_matiere":"7.42","appreciation":"Faible"},"33":{"moyenne_interro":7.83,"devoir1":"8.5","devoir2":"10","moyenne_matiere":"8.78","appreciation":"Insuffisant"},"34":{"moyenne_interro":12,"devoir1":"14","devoir2":"14","moyenne_matiere":"13.33","appreciation":"Assez Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	9.29	13.36	11.73	12.15	11.61	passé
705	1	2	11.53	12.05	2026-03-21 01:47:22	2026-06-19 21:56:31	4	5ème	\N	9.12	12.59	88	{"25":{"moyenne_interro":14,"devoir1":"18","devoir2":"9.5","moyenne_matiere":"13.83","appreciation":"Assez Bien"},"26":{"moyenne_interro":12.5,"devoir1":"10","devoir2":"10","moyenne_matiere":"10.83","appreciation":"Passable"},"27":{"moyenne_interro":13.5,"devoir1":"15","devoir2":"8","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"28":{"moyenne_interro":9.66,"devoir1":"6.5","devoir2":"13.5","moyenne_matiere":"9.89","appreciation":"Insuffisant"},"29":{"moyenne_interro":18.66,"devoir1":"16","devoir2":"14","moyenne_matiere":"16.22","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":14.25,"devoir1":"7","devoir2":"11","moyenne_matiere":"10.75","appreciation":"Passable"},"32":{"moyenne_interro":8.5,"devoir1":"6","devoir2":"7","moyenne_matiere":"7.17","appreciation":"Faible"},"33":{"moyenne_interro":14.83,"devoir1":"6","devoir2":"5","moyenne_matiere":"8.61","appreciation":"Insuffisant"},"34":{"moyenne_interro":13,"devoir1":"12","devoir2":"18","moyenne_matiere":"14.33","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	9.29	13.36	11.53	11.95	12.68	passé
709	1	2	11.42	11.28	2026-03-21 01:47:22	2026-06-19 21:56:31	4	6ème	\N	8.72	12.62	89	{"25":{"moyenne_interro":15.5,"devoir1":"17","devoir2":"8.5","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"26":{"moyenne_interro":9,"devoir1":"9","devoir2":"10","moyenne_matiere":"9.33","appreciation":"Insuffisant"},"27":{"moyenne_interro":15.5,"devoir1":"13","devoir2":"9","moyenne_matiere":"12.5","appreciation":"Assez Bien"},"28":{"moyenne_interro":10.33,"devoir1":"8.5","devoir2":"12","moyenne_matiere":"10.28","appreciation":"Passable"},"29":{"moyenne_interro":19,"devoir1":"19","devoir2":"14","moyenne_matiere":"17.33","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":17.75,"devoir1":"7","devoir2":"6.5","moyenne_matiere":"10.42","appreciation":"Passable"},"32":{"moyenne_interro":7.5,"devoir1":"8.5","devoir2":"4","moyenne_matiere":"6.67","appreciation":"Faible"},"33":{"moyenne_interro":13.66,"devoir1":"6.5","devoir2":"4.5","moyenne_matiere":"8.22","appreciation":"Insuffisant"},"34":{"moyenne_interro":15,"devoir1":"11","devoir2":"18","moyenne_matiere":"14.67","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	9.29	13.36	11.42	11.75	10.68	passé
701	1	2	9.29	9.24	2026-03-21 01:47:22	2026-06-19 21:56:31	4	7ème	\N	6.56	10.3	87	{"25":{"moyenne_interro":11,"devoir1":"15","devoir2":"6.5","moyenne_matiere":"10.83","appreciation":"Passable"},"26":{"moyenne_interro":10,"devoir1":"8","devoir2":"7","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"27":{"moyenne_interro":14.5,"devoir1":"11","devoir2":"6","moyenne_matiere":"10.5","appreciation":"Passable"},"28":{"moyenne_interro":8.33,"devoir1":"3","devoir2":"6.5","moyenne_matiere":"5.94","appreciation":"Tr\\u00e8s Faible"},"29":{"moyenne_interro":17.66,"devoir1":"18","devoir2":"12","moyenne_matiere":"15.89","appreciation":"Bien"},"31":{"moyenne_interro":7.5,"devoir1":"6","devoir2":"6","moyenne_matiere":"6.5","appreciation":"Faible"},"32":{"moyenne_interro":8.5,"devoir1":"6","devoir2":"4","moyenne_matiere":"6.17","appreciation":"Faible"},"33":{"moyenne_interro":10.66,"devoir1":"5.5","devoir2":"5","moyenne_matiere":"7.05","appreciation":"Faible"},"34":{"moyenne_interro":10,"devoir1":"10","devoir2":"18","moyenne_matiere":"12.67","appreciation":"Assez Bien"}}	\N	\N	Élève Faible, Travail Insuffisant	7	9.29	13.36	9.29	8.78	9.64	redoublé
614	3	2	11.76	11.10	2026-03-20 22:26:53	2026-06-27 13:06:13	2	6ème	7ème	10.75	10.19	74	{"9":{"moyenne_interro":14.5,"devoir1":"14","devoir2":"13","moyenne_matiere":"13.83","appreciation":"Assez Bien"},"10":{"moyenne_interro":8.5,"devoir1":"7","devoir2":"7","moyenne_matiere":"7.5","appreciation":"Faible"},"11":{"moyenne_interro":11,"devoir1":"9.25","devoir2":"10.5","moyenne_matiere":"10.25","appreciation":"Passable"},"12":{"moyenne_interro":7.5,"devoir1":"10","devoir2":"10","moyenne_matiere":"9.17","appreciation":"Insuffisant"},"13":{"moyenne_interro":12.5,"devoir1":"11.5","devoir2":"8","moyenne_matiere":"10.67","appreciation":"Passable"},"14":{"moyenne_interro":13,"devoir1":"10","devoir2":"16.5","moyenne_matiere":"13.17","appreciation":"Assez Bien"},"15":{"moyenne_interro":9.25,"devoir1":"7","devoir2":"9","moyenne_matiere":"8.42","appreciation":"Insuffisant"},"16":{"moyenne_interro":14.5,"devoir1":"15","devoir2":"15","moyenne_matiere":"14.83","appreciation":"Bien"}}	\N	\N	Élève Moyen, Travail Passable	7	11.33	16.82	10.66	10.87	11.76	passé
694	2	2	13.11	12.66	2026-03-21 01:47:22	2026-06-19 21:56:31	4	1ère	\N	12.75	12.86	85	{"25":{"moyenne_interro":10,"devoir1":"10","devoir2":"13","moyenne_matiere":"11","appreciation":"Passable"},"26":{"moyenne_interro":12,"devoir1":"10","devoir2":"10","moyenne_matiere":"10.67","appreciation":"Passable"},"27":{"moyenne_interro":14.5,"devoir1":"11","devoir2":"10","moyenne_matiere":"11.83","appreciation":"Passable"},"28":{"moyenne_interro":14,"devoir1":"16.5","devoir2":"13.5","moyenne_matiere":"14.67","appreciation":"Bien"},"29":{"moyenne_interro":17.33,"devoir1":"19","devoir2":"12","moyenne_matiere":"16.11","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":18.5,"devoir1":"12.5","devoir2":"11","moyenne_matiere":"14","appreciation":"Bien"},"32":{"moyenne_interro":8.5,"devoir1":"13.5","devoir2":"11","moyenne_matiere":"11","appreciation":"Passable"},"33":{"moyenne_interro":16.16,"devoir1":"5","devoir2":"16.75","moyenne_matiere":"12.64","appreciation":"Assez Bien"},"34":{"moyenne_interro":14,"devoir1":"12","devoir2":"17","moyenne_matiere":"14.33","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	8.78	13.11	12.65	13.11	12.23	passé
698	2	2	12.95	12.35	2026-03-21 01:47:22	2026-06-19 21:56:31	4	3ème	\N	12.42	12.97	86	{"25":{"moyenne_interro":15.75,"devoir1":"13","devoir2":"11","moyenne_matiere":"13.25","appreciation":"Assez Bien"},"26":{"moyenne_interro":12.5,"devoir1":"8","devoir2":"10","moyenne_matiere":"10.17","appreciation":"Passable"},"27":{"moyenne_interro":15.5,"devoir1":"11","devoir2":"10","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"28":{"moyenne_interro":15.5,"devoir1":"10","devoir2":"14","moyenne_matiere":"13.17","appreciation":"Assez Bien"},"29":{"moyenne_interro":17.33,"devoir1":"18","devoir2":"13","moyenne_matiere":"16.11","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":18.25,"devoir1":"8","devoir2":"9","moyenne_matiere":"11.75","appreciation":"Passable"},"32":{"moyenne_interro":9.5,"devoir1":"16","devoir2":"11","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"33":{"moyenne_interro":17.33,"devoir1":"8.5","devoir2":"15.25","moyenne_matiere":"13.69","appreciation":"Assez Bien"},"34":{"moyenne_interro":13,"devoir1":"12","devoir2":"12","moyenne_matiere":"12.33","appreciation":"Assez Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	8.78	13.11	11.67	12.95	12.44	passé
690	2	2	12.15	11.83	2026-03-21 01:47:22	2026-06-19 21:56:31	4	4ème	\N	9.76	13.15	84	{"25":{"moyenne_interro":15,"devoir1":"17","devoir2":"17","moyenne_matiere":"16.33","appreciation":"Tr\\u00e8s Bien"},"26":{"moyenne_interro":12,"devoir1":"11","devoir2":"10","moyenne_matiere":"11","appreciation":"Passable"},"27":{"moyenne_interro":13.5,"devoir1":"12","devoir2":"9","moyenne_matiere":"11.5","appreciation":"Passable"},"28":{"moyenne_interro":16,"devoir1":"8.5","devoir2":"14","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"29":{"moyenne_interro":17.33,"devoir1":"14","devoir2":"11","moyenne_matiere":"14.11","appreciation":"Bien"},"31":{"moyenne_interro":10.25,"devoir1":"7","devoir2":"10","moyenne_matiere":"9.08","appreciation":"Insuffisant"},"32":{"moyenne_interro":7.5,"devoir1":"6","devoir2":"6","moyenne_matiere":"6.5","appreciation":"Faible"},"33":{"moyenne_interro":15.16,"devoir1":"10","devoir2":"17","moyenne_matiere":"14.05","appreciation":"Bien"},"34":{"moyenne_interro":15,"devoir1":"14","devoir2":"13","moyenne_matiere":"14","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	8.78	13.11	11.73	12.15	11.61	passé
710	2	2	11.75	11.28	2026-03-21 01:47:22	2026-06-19 21:56:31	4	6ème	\N	11.53	11.48	89	{"25":{"moyenne_interro":12.75,"devoir1":"6.5","devoir2":"8","moyenne_matiere":"9.08","appreciation":"Insuffisant"},"26":{"moyenne_interro":12,"devoir1":"9","devoir2":"8","moyenne_matiere":"9.67","appreciation":"Insuffisant"},"27":{"moyenne_interro":12.5,"devoir1":"10","devoir2":"7","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"28":{"moyenne_interro":12.5,"devoir1":"11","devoir2":"13","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"29":{"moyenne_interro":17,"devoir1":"16","devoir2":"17","moyenne_matiere":"16.67","appreciation":"Tr\\u00e8s Bien"},"31":{"moyenne_interro":19,"devoir1":"10","devoir2":"10","moyenne_matiere":"13","appreciation":"Assez Bien"},"32":{"moyenne_interro":7.75,"devoir1":"12","devoir2":"5","moyenne_matiere":"8.25","appreciation":"Insuffisant"},"33":{"moyenne_interro":13.83,"devoir1":"10","devoir2":"14","moyenne_matiere":"12.61","appreciation":"Assez Bien"},"34":{"moyenne_interro":14,"devoir1":"12","devoir2":"6","moyenne_matiere":"10.67","appreciation":"Passable"}}	\N	\N	Élève Moyen, Travail Passable	7	8.78	13.11	11.42	11.75	10.68	passé
702	2	2	8.78	9.24	2026-03-21 01:47:22	2026-06-19 21:56:31	4	7ème	\N	6.19	9.55	87	{"25":{"moyenne_interro":10,"devoir1":"4","devoir2":"9","moyenne_matiere":"7.67","appreciation":"Faible"},"26":{"moyenne_interro":10.5,"devoir1":"7","devoir2":"7","moyenne_matiere":"8.17","appreciation":"Insuffisant"},"27":{"moyenne_interro":14,"devoir1":"8","devoir2":"7","moyenne_matiere":"9.67","appreciation":"Insuffisant"},"28":{"moyenne_interro":7,"devoir1":"7.5","devoir2":"10.5","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"29":{"moyenne_interro":16.66,"devoir1":"15","devoir2":"10","moyenne_matiere":"13.89","appreciation":"Assez Bien"},"31":{"moyenne_interro":10.75,"devoir1":"4","devoir2":"4","moyenne_matiere":"6.25","appreciation":"Faible"},"32":{"moyenne_interro":5.75,"devoir1":"8","devoir2":"5","moyenne_matiere":"6.25","appreciation":"Faible"},"33":{"moyenne_interro":9.83,"devoir1":"5","devoir2":"3.25","moyenne_matiere":"6.03","appreciation":"Faible"},"34":{"moyenne_interro":12,"devoir1":"13","devoir2":"8","moyenne_matiere":"11","appreciation":"Passable"}}	\N	\N	Élève Faible, Travail Insuffisant	7	8.78	13.11	9.29	8.78	9.64	redoublé
695	3	2	12.23	12.66	2026-03-21 01:47:22	2026-06-19 21:56:31	4	4ème	2ème	11.27	12.62	85	{"25":{"moyenne_interro":13.5,"devoir1":"9","devoir2":"10","moyenne_matiere":"10.83","appreciation":"Passable"},"26":{"moyenne_interro":12,"devoir1":"13","devoir2":"11","moyenne_matiere":"12","appreciation":"Assez Bien"},"27":{"moyenne_interro":13,"devoir1":"15","devoir2":"12","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"29":{"moyenne_interro":16.33,"devoir1":"10","devoir2":"17","moyenne_matiere":"14.44","appreciation":"Bien"},"31":{"moyenne_interro":20,"devoir1":"9","devoir2":"9","moyenne_matiere":"12.67","appreciation":"Assez Bien"},"32":{"moyenne_interro":13.5,"devoir1":"10","devoir2":"12","moyenne_matiere":"11.83","appreciation":"Passable"},"33":{"moyenne_interro":12.33,"devoir1":"6.5","devoir2":"7","moyenne_matiere":"8.61","appreciation":"Insuffisant"},"34":{"moyenne_interro":10,"devoir1":"15","devoir2":"12","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"28":{"moyenne_interro":15,"devoir1":"11.5","devoir2":"11","moyenne_matiere":"12.5","appreciation":"Assez Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	9.64	12.86	12.65	13.11	12.23	passé
699	3	2	12.44	12.35	2026-03-21 01:47:22	2026-06-19 21:56:31	4	3ème	3ème	11.74	12.81	86	{"25":{"moyenne_interro":14,"devoir1":"9","devoir2":"17","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"26":{"moyenne_interro":10.5,"devoir1":"13","devoir2":"10","moyenne_matiere":"11.17","appreciation":"Passable"},"27":{"moyenne_interro":13,"devoir1":"13","devoir2":"14","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"29":{"moyenne_interro":13.66,"devoir1":"10","devoir2":"14","moyenne_matiere":"12.55","appreciation":"Assez Bien"},"31":{"moyenne_interro":17.5,"devoir1":"9.5","devoir2":"11","moyenne_matiere":"12.67","appreciation":"Assez Bien"},"32":{"moyenne_interro":13,"devoir1":"11","devoir2":"11.5","moyenne_matiere":"11.83","appreciation":"Passable"},"33":{"moyenne_interro":13,"devoir1":"9.25","devoir2":"8.5","moyenne_matiere":"10.25","appreciation":"Passable"},"34":{"moyenne_interro":10,"devoir1":"14","devoir2":"12","moyenne_matiere":"12","appreciation":"Assez Bien"},"28":{"moyenne_interro":17.5,"devoir1":"10.5","devoir2":"13","moyenne_matiere":"13.67","appreciation":"Assez Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	9.64	12.86	11.67	12.95	12.44	passé
707	3	2	12.68	12.05	2026-03-21 01:47:22	2026-06-19 21:56:31	4	2ème	4ème	11.41	13.47	88	{"25":{"moyenne_interro":13,"devoir1":"15","devoir2":"13","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"26":{"moyenne_interro":13.5,"devoir1":"13","devoir2":"10","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"27":{"moyenne_interro":14.5,"devoir1":"13","devoir2":"13","moyenne_matiere":"13.5","appreciation":"Assez Bien"},"29":{"moyenne_interro":16,"devoir1":"15","devoir2":"16","moyenne_matiere":"15.67","appreciation":"Bien"},"31":{"moyenne_interro":16.25,"devoir1":"7","devoir2":"10","moyenne_matiere":"11.08","appreciation":"Passable"},"32":{"moyenne_interro":10.75,"devoir1":"10.5","devoir2":"11","moyenne_matiere":"10.75","appreciation":"Passable"},"33":{"moyenne_interro":11.66,"devoir1":"16","devoir2":"10","moyenne_matiere":"12.55","appreciation":"Assez Bien"},"34":{"moyenne_interro":10,"devoir1":"12","devoir2":"12","moyenne_matiere":"11.33","appreciation":"Passable"},"28":{"moyenne_interro":17,"devoir1":"8","devoir2":"12","moyenne_matiere":"12.33","appreciation":"Assez Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	9.64	12.86	11.53	11.95	12.68	passé
691	3	2	11.61	11.83	2026-03-21 01:47:22	2026-06-19 21:56:31	4	5ème	5ème	9.55	12.54	84	{"25":{"moyenne_interro":15.5,"devoir1":"18","devoir2":"14","moyenne_matiere":"15.83","appreciation":"Bien"},"26":{"moyenne_interro":11.5,"devoir1":"13","devoir2":"11","moyenne_matiere":"11.83","appreciation":"Passable"},"27":{"moyenne_interro":14.5,"devoir1":"15","devoir2":"9","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"29":{"moyenne_interro":12.66,"devoir1":"10","devoir2":"11","moyenne_matiere":"11.22","appreciation":"Passable"},"31":{"moyenne_interro":17.5,"devoir1":"4","devoir2":"9","moyenne_matiere":"10.17","appreciation":"Passable"},"32":{"moyenne_interro":10,"devoir1":"8","devoir2":"9","moyenne_matiere":"9","appreciation":"Insuffisant"},"33":{"moyenne_interro":10,"devoir1":"11","devoir2":"6.5","moyenne_matiere":"9.17","appreciation":"Insuffisant"},"34":{"moyenne_interro":10,"devoir1":"15","devoir2":"12","moyenne_matiere":"12.33","appreciation":"Assez Bien"},"28":{"moyenne_interro":17.5,"devoir1":"5","devoir2":"10.5","moyenne_matiere":"11","appreciation":"Passable"}}	\N	\N	Élève Moyen, Travail Passable	7	9.64	12.86	11.73	12.15	11.61	passé
703	3	2	9.64	9.24	2026-03-21 01:47:22	2026-06-19 21:56:31	4	7ème	7ème	7.63	10.31	87	{"25":{"moyenne_interro":13,"devoir1":"6","devoir2":"9","moyenne_matiere":"9.33","appreciation":"Insuffisant"},"26":{"moyenne_interro":10,"devoir1":"7","devoir2":"8","moyenne_matiere":"8.33","appreciation":"Insuffisant"},"27":{"moyenne_interro":14,"devoir1":"9","devoir2":"10","moyenne_matiere":"11","appreciation":"Passable"},"29":{"moyenne_interro":13.66,"devoir1":"14","devoir2":"14","moyenne_matiere":"13.89","appreciation":"Assez Bien"},"31":{"moyenne_interro":17.5,"devoir1":"4","devoir2":"7","moyenne_matiere":"9.5","appreciation":"Insuffisant"},"32":{"moyenne_interro":11.5,"devoir1":"6.5","devoir2":"6","moyenne_matiere":"8","appreciation":"Insuffisant"},"33":{"moyenne_interro":3.83,"devoir1":"3.5","devoir2":"6","moyenne_matiere":"4.44","appreciation":"Tr\\u00e8s Faible"},"34":{"moyenne_interro":10,"devoir1":"13","devoir2":"12","moyenne_matiere":"11.67","appreciation":"Passable"},"28":{"moyenne_interro":12.5,"devoir1":"4.5","devoir2":"10","moyenne_matiere":"9","appreciation":"Insuffisant"}}	\N	\N	Élève Faible, Travail Insuffisant	7	9.64	12.86	9.29	8.78	9.64	redoublé
620	1	2	15.82	15.90	2026-03-20 22:26:53	2026-06-27 13:06:13	2	2ème	\N	15.39	15.98	76	{"9":{"moyenne_interro":19.5,"devoir1":"19.25","devoir2":"19","moyenne_matiere":"19.25","appreciation":"Excellent"},"10":{"moyenne_interro":16.5,"devoir1":"10","devoir2":"11","moyenne_matiere":"12.5","appreciation":"Assez Bien"},"11":{"moyenne_interro":15.75,"devoir1":"12.25","devoir2":"15.5","moyenne_matiere":"14.5","appreciation":"Bien"},"12":{"moyenne_interro":20,"devoir1":"19","devoir2":"14","moyenne_matiere":"17.67","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":18,"devoir1":"15","devoir2":"17.5","moyenne_matiere":"16.83","appreciation":"Tr\\u00e8s Bien"},"14":{"moyenne_interro":14.75,"devoir1":"17","devoir2":"16","moyenne_matiere":"15.92","appreciation":"Bien"},"15":{"moyenne_interro":15.75,"devoir1":"14.5","devoir2":"10","moyenne_matiere":"13.42","appreciation":"Assez Bien"},"16":{"moyenne_interro":13,"devoir1":"14","devoir2":"16","moyenne_matiere":"14.33","appreciation":"Bien"}}	\N	\N	Élève Bon, Travail Bien	7	10.66	16.44	15.82	15.87	16.02	passé
600	1	2	12.81	11.88	2026-03-20 22:26:53	2026-06-27 13:06:13	2	3ème	\N	11.06	12.31	71	{"9":{"moyenne_interro":13.5,"devoir1":"18.25","devoir2":"15","moyenne_matiere":"15.58","appreciation":"Bien"},"10":{"moyenne_interro":8,"devoir1":"8","devoir2":"8","moyenne_matiere":"8","appreciation":"Insuffisant"},"11":{"moyenne_interro":6,"devoir1":"12","devoir2":"8","moyenne_matiere":"8.67","appreciation":"Insuffisant"},"12":{"moyenne_interro":16,"devoir1":"20","devoir2":"15","moyenne_matiere":"17","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":11,"devoir1":"9","devoir2":"10","moyenne_matiere":"10","appreciation":"Passable"},"14":{"moyenne_interro":13.5,"devoir1":"16","devoir2":"10","moyenne_matiere":"13.17","appreciation":"Assez Bien"},"15":{"moyenne_interro":11,"devoir1":"4","devoir2":"15","moyenne_matiere":"10","appreciation":"Passable"},"16":{"moyenne_interro":14.5,"devoir1":"14","devoir2":"16","moyenne_matiere":"14.83","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	10.66	16.44	12.81	11.51	11.33	passé
608	1	2	12.56	13.63	2026-03-20 22:26:53	2026-06-27 13:06:13	2	5ème	\N	11.81	11.02	73	{"9":{"moyenne_interro":16,"devoir1":"6.25","devoir2":"14","moyenne_matiere":"12.08","appreciation":"Assez Bien"},"10":{"moyenne_interro":9,"devoir1":"8","devoir2":"10","moyenne_matiere":"9","appreciation":"Insuffisant"},"11":{"moyenne_interro":5,"devoir1":"7","devoir2":"7","moyenne_matiere":"6.33","appreciation":"Faible"},"12":{"moyenne_interro":14,"devoir1":"19","devoir2":"17","moyenne_matiere":"16.67","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":12.5,"devoir1":"8","devoir2":"13.5","moyenne_matiere":"11.33","appreciation":"Passable"},"14":{"moyenne_interro":13.5,"devoir1":"12","devoir2":"11","moyenne_matiere":"12.17","appreciation":"Assez Bien"},"15":{"moyenne_interro":12.75,"devoir1":"10","devoir2":"13","moyenne_matiere":"11.92","appreciation":"Passable"},"16":{"moyenne_interro":15.5,"devoir1":"16","devoir2":"15","moyenne_matiere":"15.5","appreciation":"Bien"}}	\N	\N	Élève Correct, Travail Assez Bien	7	10.66	16.44	12.56	14.38	13.96	passé
605	2	2	17.01	16.76	2026-03-20 22:26:53	2026-06-27 13:06:13	2	1er	\N	17.44	16.73	72	{"9":{"moyenne_interro":18.5,"devoir1":"19.25","devoir2":"15","moyenne_matiere":"17.58","appreciation":"Tr\\u00e8s Bien"},"10":{"moyenne_interro":17.25,"devoir1":"14","devoir2":"13","moyenne_matiere":"14.75","appreciation":"Bien"},"11":{"moyenne_interro":19.5,"devoir1":"15.5","devoir2":"17.25","moyenne_matiere":"17.42","appreciation":"Tr\\u00e8s Bien"},"12":{"moyenne_interro":17.5,"devoir1":"17","devoir2":"17","moyenne_matiere":"17.17","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":20,"devoir1":"16","devoir2":"18.5","moyenne_matiere":"18.17","appreciation":"Excellent"},"14":{"moyenne_interro":17,"devoir1":"15","devoir2":"18.5","moyenne_matiere":"16.83","appreciation":"Tr\\u00e8s Bien"},"15":{"moyenne_interro":14.5,"devoir1":"18.5","devoir2":"19","moyenne_matiere":"17.33","appreciation":"Tr\\u00e8s Bien"},"16":{"moyenne_interro":16.5,"devoir1":"15","devoir2":"16","moyenne_matiere":"15.83","appreciation":"Bien"}}	\N	\N	Élève Très Bon, Travail Très Bien	7	10.87	17.01	16.44	17.01	16.82	passé
621	2	2	15.87	15.90	2026-03-20 22:26:53	2026-06-27 13:06:13	2	2ème	\N	17.22	15.33	76	{"9":{"moyenne_interro":18,"devoir1":"14","devoir2":"13","moyenne_matiere":"15","appreciation":"Bien"},"10":{"moyenne_interro":16,"devoir1":"11","devoir2":"13","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"11":{"moyenne_interro":19.25,"devoir1":"13","devoir2":"15.25","moyenne_matiere":"15.83","appreciation":"Bien"},"12":{"moyenne_interro":17.5,"devoir1":"18","devoir2":"16","moyenne_matiere":"17.17","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":20,"devoir1":"15.5","devoir2":"18.5","moyenne_matiere":"18","appreciation":"Excellent"},"14":{"moyenne_interro":19.5,"devoir1":"16","devoir2":"14","moyenne_matiere":"16.5","appreciation":"Tr\\u00e8s Bien"},"15":{"moyenne_interro":17,"devoir1":"16","devoir2":"18.5","moyenne_matiere":"17.17","appreciation":"Tr\\u00e8s Bien"},"16":{"moyenne_interro":12.5,"devoir1":"11","devoir2":"12","moyenne_matiere":"11.83","appreciation":"Passable"}}	\N	\N	Élève Bon, Travail Bien	7	10.87	17.01	15.82	15.87	16.02	passé
597	2	2	14.52	13.70	2026-03-20 22:26:53	2026-06-27 13:06:13	2	3ème	\N	13.67	14.54	70	{"9":{"moyenne_interro":12.5,"devoir1":"18","devoir2":"15","moyenne_matiere":"15.17","appreciation":"Bien"},"10":{"moyenne_interro":10.75,"devoir1":"11","devoir2":"10","moyenne_matiere":"10.58","appreciation":"Passable"},"11":{"moyenne_interro":16.5,"devoir1":"13","devoir2":"18.25","moyenne_matiere":"15.92","appreciation":"Bien"},"12":{"moyenne_interro":15.5,"devoir1":"16","devoir2":"18","moyenne_matiere":"16.5","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":18,"devoir1":"13","devoir2":"16","moyenne_matiere":"15.67","appreciation":"Bien"},"14":{"moyenne_interro":12.5,"devoir1":"8","devoir2":"12","moyenne_matiere":"10.83","appreciation":"Passable"},"15":{"moyenne_interro":15,"devoir1":"15.5","devoir2":"13","moyenne_matiere":"14.5","appreciation":"Bien"},"16":{"moyenne_interro":14.5,"devoir1":"14","devoir2":"12","moyenne_matiere":"13.5","appreciation":"Assez Bien"}}	\N	\N	Élève Bon, Travail Bien	7	10.87	17.01	12.74	14.52	13.83	passé
609	2	2	14.38	13.63	2026-03-20 22:26:53	2026-06-27 13:06:13	2	4ème	\N	13.72	13.69	73	{"9":{"moyenne_interro":18,"devoir1":"20","devoir2":"15","moyenne_matiere":"17.67","appreciation":"Tr\\u00e8s Bien"},"10":{"moyenne_interro":10.5,"devoir1":"10","devoir2":"10","moyenne_matiere":"10.17","appreciation":"Passable"},"11":{"moyenne_interro":10.75,"devoir1":"10","devoir2":"11.5","moyenne_matiere":"10.75","appreciation":"Passable"},"12":{"moyenne_interro":17.5,"devoir1":"16","devoir2":"15","moyenne_matiere":"16.17","appreciation":"Tr\\u00e8s Bien"},"13":{"moyenne_interro":13,"devoir1":"11","devoir2":"14.5","moyenne_matiere":"12.83","appreciation":"Assez Bien"},"14":{"moyenne_interro":13,"devoir1":"13","devoir2":"16.5","moyenne_matiere":"14.17","appreciation":"Bien"},"15":{"moyenne_interro":14.5,"devoir1":"13","devoir2":"15","moyenne_matiere":"14.17","appreciation":"Bien"},"16":{"moyenne_interro":15.5,"devoir1":"16","devoir2":"15","moyenne_matiere":"15.5","appreciation":"Bien"}}	\N	\N	Élève Bon, Travail Bien	7	10.87	17.01	12.56	14.38	13.96	passé
682	2	2	9.85	10.31	2026-03-21 01:47:22	2026-06-27 23:13:57	3	8ème	\N	7.39	10.85	82	{"17":{"moyenne_interro":15.5,"devoir1":"10","devoir2":"9","moyenne_matiere":"11.5","appreciation":"Passable"},"18":{"moyenne_interro":8,"devoir1":"7","devoir2":"7","moyenne_matiere":"7.33","appreciation":"Faible"},"19":{"moyenne_interro":11.5,"devoir1":"10.5","devoir2":"11.25","moyenne_matiere":"11.08","appreciation":"Passable"},"20":{"moyenne_interro":9,"devoir1":"6","devoir2":"11","moyenne_matiere":"8.67","appreciation":"Insuffisant"},"21":{"moyenne_interro":7.25,"devoir1":"3.5","devoir2":"2","moyenne_matiere":"4.25","appreciation":"Tr\\u00e8s Faible"},"22":{"moyenne_interro":11.5,"devoir1":"4","devoir2":"12","moyenne_matiere":"9.17","appreciation":"Insuffisant"},"23":{"moyenne_interro":13,"devoir1":"7","devoir2":"11","moyenne_matiere":"10.33","appreciation":"Passable"},"24":{"moyenne_interro":8.5,"devoir1":"10","devoir2":"11","moyenne_matiere":"9.83","appreciation":"Insuffisant"},"30":{"moyenne_interro":17,"devoir1":"16","devoir2":"14","moyenne_matiere":"15.67","appreciation":"Bien"}}	\N	\N	Élève Faible, Travail Insuffisant	8	9.85	17.96	10.93	9.85	10.15	passé
661	1	2	17.89	17.92	2026-03-21 01:47:21	2026-06-27 23:13:57	3	1ère	\N	18.27	18.06	77	{"18":{"moyenne_interro":19.5,"devoir1":"14","devoir2":"15","moyenne_matiere":"16.17","appreciation":"Tr\\u00e8s Bien"},"19":{"moyenne_interro":18.5,"devoir1":"17.5","devoir2":"15","moyenne_matiere":"17","appreciation":"Tr\\u00e8s Bien"},"20":{"moyenne_interro":19.5,"devoir1":"20","devoir2":"17.5","moyenne_matiere":"19","appreciation":"Excellent"},"21":{"moyenne_interro":20,"devoir1":"18","devoir2":"16","moyenne_matiere":"18","appreciation":"Excellent"},"22":{"moyenne_interro":18,"devoir1":"18","devoir2":"17","moyenne_matiere":"17.67","appreciation":"Tr\\u00e8s Bien"},"23":{"moyenne_interro":19.86,"devoir1":"20","devoir2":"18","moyenne_matiere":"19.29","appreciation":"Excellent"},"24":{"moyenne_interro":15.5,"devoir1":"14","devoir2":"14","moyenne_matiere":"14.5","appreciation":"Bien"},"30":{"moyenne_interro":18.33,"devoir1":"20","devoir2":"18","moyenne_matiere":"18.78","appreciation":"Excellent"},"17":{"moyenne_interro":20,"devoir1":"20","devoir2":"18","moyenne_matiere":"19.33","appreciation":"Excellent"}}	\N	\N	Élève Très Bon, Travail Très Bien	8	10.46	17.89	17.89	17.96	17.91	passé
685	1	2	11.03	10.89	2026-03-21 01:47:22	2026-06-27 23:13:57	3	4ème	\N	10.59	10.48	83	{"18":{"moyenne_interro":7.5,"devoir1":"8","devoir2":"8","moyenne_matiere":"7.83","appreciation":"Faible"},"19":{"moyenne_interro":4.5,"devoir1":"11.5","devoir2":"10","moyenne_matiere":"8.67","appreciation":"Insuffisant"},"20":{"moyenne_interro":10.75,"devoir1":"20","devoir2":"10","moyenne_matiere":"13.58","appreciation":"Assez Bien"},"21":{"moyenne_interro":9.87,"devoir1":null,"devoir2":"7","moyenne_matiere":"8.44","appreciation":"Insuffisant"},"22":{"moyenne_interro":13,"devoir1":null,"devoir2":"12","moyenne_matiere":"12.5","appreciation":"Assez Bien"},"23":{"moyenne_interro":14.75,"devoir1":"12","devoir2":"9","moyenne_matiere":"11.92","appreciation":"Passable"},"24":{"moyenne_interro":13,"devoir1":"14","devoir2":"14","moyenne_matiere":"13.67","appreciation":"Assez Bien"},"30":{"moyenne_interro":16.66,"devoir1":null,"devoir2":"12","moyenne_matiere":"14.33","appreciation":"Bien"},"17":{"moyenne_interro":8,"devoir1":null,"devoir2":"8","moyenne_matiere":"8","appreciation":"Insuffisant"}}	\N	\N	Élève Moyen, Travail Passable	8	10.46	17.89	11.03	10.52	11.11	passé
686	2	2	10.52	10.89	2026-03-21 01:47:22	2026-06-27 23:13:57	3	6ème	\N	9.54	10.18	83	{"17":{"moyenne_interro":12,"devoir1":"8","devoir2":"7","moyenne_matiere":"9","appreciation":"Insuffisant"},"18":{"moyenne_interro":11.5,"devoir1":"7","devoir2":"8","moyenne_matiere":"8.83","appreciation":"Insuffisant"},"19":{"moyenne_interro":8,"devoir1":"12.5","devoir2":"7.5","moyenne_matiere":"9.33","appreciation":"Insuffisant"},"20":{"moyenne_interro":9.5,"devoir1":"6","devoir2":"6","moyenne_matiere":"7.17","appreciation":"Faible"},"21":{"moyenne_interro":12.25,"devoir1":"6","devoir2":"6.5","moyenne_matiere":"8.25","appreciation":"Insuffisant"},"22":{"moyenne_interro":12.75,"devoir1":"8","devoir2":"8","moyenne_matiere":"9.58","appreciation":"Insuffisant"},"23":{"moyenne_interro":12,"devoir1":"8","devoir2":"14.25","moyenne_matiere":"11.42","appreciation":"Passable"},"24":{"moyenne_interro":11,"devoir1":"14","devoir2":"15","moyenne_matiere":"13.33","appreciation":"Assez Bien"},"30":{"moyenne_interro":17.66,"devoir1":"17","devoir2":"15","moyenne_matiere":"16.55","appreciation":"Tr\\u00e8s Bien"}}	\N	\N	Élève Moyen, Travail Passable	8	9.85	17.96	11.03	10.52	11.11	passé
665	1	2	10.46	10.63	2026-03-21 01:47:21	2026-06-27 23:13:57	3	7ème	\N	8.58	10.71	78	{"18":{"moyenne_interro":8.5,"devoir1":"8","devoir2":"8","moyenne_matiere":"8.17","appreciation":"Insuffisant"},"19":{"moyenne_interro":13.25,"devoir1":"10","devoir2":"8","moyenne_matiere":"10.42","appreciation":"Passable"},"20":{"moyenne_interro":16,"devoir1":"18.5","devoir2":"7.5","moyenne_matiere":"14","appreciation":"Bien"},"21":{"moyenne_interro":6.25,"devoir1":"4","devoir2":"6","moyenne_matiere":"5.42","appreciation":"Tr\\u00e8s Faible"},"22":{"moyenne_interro":16,"devoir1":"10","devoir2":"8","moyenne_matiere":"11.33","appreciation":"Passable"},"23":{"moyenne_interro":15.75,"devoir1":"11","devoir2":"5","moyenne_matiere":"10.58","appreciation":"Passable"},"24":{"moyenne_interro":13.5,"devoir1":"14","devoir2":"16","moyenne_matiere":"14.5","appreciation":"Bien"},"30":{"moyenne_interro":13.33,"devoir1":"18","devoir2":"7","moyenne_matiere":"12.78","appreciation":"Assez Bien"},"17":{"moyenne_interro":6,"devoir1":"10","devoir2":"8.5","moyenne_matiere":"8.17","appreciation":"Insuffisant"}}	\N	\N	Élève Moyen, Travail Passable	8	10.46	17.89	10.46	11.03	10.41	passé
662	2	2	17.96	17.92	2026-03-21 01:47:21	2026-06-27 23:13:57	3	1ère	\N	17.88	18.63	77	{"17":{"moyenne_interro":20,"devoir1":"20","devoir2":"20","moyenne_matiere":"20","appreciation":"Excellent"},"18":{"moyenne_interro":20,"devoir1":"15","devoir2":"14","moyenne_matiere":"16.33","appreciation":"Tr\\u00e8s Bien"},"19":{"moyenne_interro":18.5,"devoir1":"18","devoir2":"19","moyenne_matiere":"18.5","appreciation":"Excellent"},"20":{"moyenne_interro":20,"devoir1":"20","devoir2":"18","moyenne_matiere":"19.33","appreciation":"Excellent"},"21":{"moyenne_interro":19.5,"devoir1":"16.5","devoir2":"18.5","moyenne_matiere":"18.17","appreciation":"Excellent"},"22":{"moyenne_interro":17.5,"devoir1":"17","devoir2":"15","moyenne_matiere":"16.5","appreciation":"Tr\\u00e8s Bien"},"23":{"moyenne_interro":18,"devoir1":"19","devoir2":"19.5","moyenne_matiere":"18.83","appreciation":"Excellent"},"24":{"moyenne_interro":13,"devoir1":"13","devoir2":"9","moyenne_matiere":"11.67","appreciation":"Passable"},"30":{"moyenne_interro":18,"devoir1":"19","devoir2":"20","moyenne_matiere":"19","appreciation":"Excellent"}}	\N	\N	Élève Très Bon, Travail Très Bien	8	9.85	17.96	17.89	17.96	17.91	passé
\.


--
-- Data for Name: note_examens; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.note_examens (id, participant_id, note, created_at, updated_at, matiere_id) FROM stdin;
316	134	14.00	2026-05-21 16:55:45	2026-05-21 16:55:45	25
317	134	11.00	2026-05-21 16:55:45	2026-05-21 16:55:45	26
318	134	9.00	2026-05-21 16:55:45	2026-05-21 16:55:45	27
319	134	10.50	2026-05-21 16:55:45	2026-05-21 16:55:45	28
320	134	11.00	2026-05-21 16:55:45	2026-05-21 16:55:45	29
321	134	9.00	2026-05-21 16:55:45	2026-05-21 16:55:45	31
322	134	9.00	2026-05-21 16:55:45	2026-05-21 16:55:45	32
323	134	6.50	2026-05-21 16:55:45	2026-05-21 16:55:45	33
324	134	12.00	2026-05-21 16:55:45	2026-05-21 16:55:45	34
325	135	10.00	2026-05-21 16:55:45	2026-05-21 16:55:45	25
326	135	11.00	2026-05-21 16:55:45	2026-05-21 16:55:45	26
327	135	12.00	2026-05-21 16:55:45	2026-05-21 16:55:45	27
328	135	11.00	2026-05-21 16:55:45	2026-05-21 16:55:45	28
329	135	17.00	2026-05-21 16:55:45	2026-05-21 16:55:45	29
330	135	9.00	2026-05-21 16:55:45	2026-05-21 16:55:45	31
331	135	12.00	2026-05-21 16:55:45	2026-05-21 16:55:45	32
332	135	7.00	2026-05-21 16:55:45	2026-05-21 16:55:45	33
333	135	12.00	2026-05-21 16:55:45	2026-05-21 16:55:45	34
334	136	17.00	2026-05-21 16:55:45	2026-05-21 16:55:45	25
335	136	10.00	2026-05-21 16:55:45	2026-05-21 16:55:45	26
336	136	14.00	2026-05-21 16:55:45	2026-05-21 16:55:45	27
337	136	13.00	2026-05-21 16:55:46	2026-05-21 16:55:46	28
338	136	14.00	2026-05-21 16:55:46	2026-05-21 16:55:46	29
339	136	11.00	2026-05-21 16:55:46	2026-05-21 16:55:46	31
340	136	11.50	2026-05-21 16:55:46	2026-05-21 16:55:46	32
341	136	8.50	2026-05-21 16:55:46	2026-05-21 16:55:46	33
342	136	12.00	2026-05-21 16:55:46	2026-05-21 16:55:46	34
343	137	9.00	2026-05-21 16:55:46	2026-05-21 16:55:46	25
344	137	8.00	2026-05-21 16:55:46	2026-05-21 16:55:46	26
345	137	10.00	2026-05-21 16:55:46	2026-05-21 16:55:46	27
346	137	10.00	2026-05-21 16:55:46	2026-05-21 16:55:46	28
347	137	14.00	2026-05-21 16:55:46	2026-05-21 16:55:46	29
348	137	7.00	2026-05-21 16:55:46	2026-05-21 16:55:46	31
349	137	6.00	2026-05-21 16:55:46	2026-05-21 16:55:46	32
350	137	6.00	2026-05-21 16:55:46	2026-05-21 16:55:46	33
351	137	12.00	2026-05-21 16:55:46	2026-05-21 16:55:46	34
352	138	13.00	2026-05-21 16:55:46	2026-05-21 16:55:46	25
353	138	10.00	2026-05-21 16:55:46	2026-05-21 16:55:46	26
354	138	13.00	2026-05-21 16:55:46	2026-05-21 16:55:46	27
355	138	12.00	2026-05-21 16:55:46	2026-05-21 16:55:46	28
356	138	16.00	2026-05-21 16:55:46	2026-05-21 16:55:46	29
357	138	10.00	2026-05-21 16:55:46	2026-05-21 16:55:46	31
358	138	11.00	2026-05-21 16:55:46	2026-05-21 16:55:46	32
359	138	10.00	2026-05-21 16:55:46	2026-05-21 16:55:46	33
360	138	12.00	2026-05-21 16:55:46	2026-05-21 16:55:46	34
361	139	13.00	2026-05-21 16:55:46	2026-05-21 16:55:46	25
362	139	6.00	2026-05-21 16:55:46	2026-05-21 16:55:46	26
363	139	11.00	2026-05-21 16:55:46	2026-05-21 16:55:46	27
364	139	4.50	2026-05-21 16:55:46	2026-05-21 16:55:46	28
365	139	14.00	2026-05-21 16:55:46	2026-05-21 16:55:46	29
366	139	4.00	2026-05-21 16:55:46	2026-05-21 16:55:46	31
367	139	8.00	2026-05-21 16:55:46	2026-05-21 16:55:46	32
368	139	4.50	2026-05-21 16:55:46	2026-05-21 16:55:46	33
369	139	12.00	2026-05-21 16:55:46	2026-05-21 16:55:46	34
370	140	14.00	2026-05-21 16:55:46	2026-05-21 16:55:46	25
371	140	10.00	2026-05-21 16:55:46	2026-05-21 16:55:46	26
372	140	13.00	2026-05-21 16:55:46	2026-05-21 16:55:46	27
373	140	9.00	2026-05-21 16:55:46	2026-05-21 16:55:46	28
374	140	17.00	2026-05-21 16:55:46	2026-05-21 16:55:46	29
375	140	11.00	2026-05-21 16:55:46	2026-05-21 16:55:46	31
376	140	12.00	2026-05-21 16:55:46	2026-05-21 16:55:46	32
377	140	9.50	2026-05-21 16:55:46	2026-05-21 16:55:46	33
378	140	12.00	2026-05-21 16:55:46	2026-05-21 16:55:46	34
\.


--
-- Data for Name: notes; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.notes (id, classe_id, matiere_id, trimestre_id, annee_id, moyenne_interro, devoir1, devoir2, moyenne_matiere, appreciation, created_at, updated_at, inscription_id, interrogation1, interrogation2, interrogation3) FROM stdin;
972	3	17	1	2	12.5	14	16	14.17	Bien	2026-06-27 21:40:25	2026-06-27 21:40:25	80	\N	\N	\N
115	3	21	1	2	8.5	11	3	7.5	Faible	2026-03-21 13:37:39	2026-03-21 13:37:39	82	\N	\N	\N
799	3	22	3	2	18	15	13	15.33	Bien	2026-05-22 14:14:05	2026-05-22 14:14:05	80	\N	\N	\N
911	9	40	3	2	16	12	9	12.33	Assez bien	2026-05-22 14:17:43	2026-05-22 14:17:43	94	\N	\N	\N
955	3	17	1	2	10	14	8	10.67	Passable	2026-05-28 01:38:05	2026-06-27 13:33:45	79	\N	\N	\N
957	3	17	1	2	7	14	10	10.33	Passable	2026-05-28 01:38:05	2026-06-27 13:33:45	81	\N	\N	\N
1	1	1	1	2	11	4	17	10.67	Passable	2026-03-21 13:22:18	2026-03-21 13:22:18	56	\N	\N	\N
2	1	1	1	2	19.5	18	11	16.17	Très Bien	2026-03-21 13:22:18	2026-03-21 13:22:18	57	\N	\N	\N
3	1	1	1	2	8.5	11	7.5	9	Insuffisant	2026-03-21 13:22:18	2026-03-21 13:22:18	58	\N	\N	\N
4	1	1	1	2	19	19	18	18.67	Excellent	2026-03-21 13:22:18	2026-03-21 13:22:18	59	\N	\N	\N
5	1	2	1	2	9.5	8	6	7.83	Faible	2026-03-21 13:23:05	2026-03-21 13:23:05	56	\N	\N	\N
6	1	2	1	2	12.5	10	10	10.83	Passable	2026-03-21 13:23:05	2026-03-21 13:23:05	57	\N	\N	\N
7	1	2	1	2	8.75	3	7	6.25	Faible	2026-03-21 13:23:05	2026-03-21 13:23:05	58	\N	\N	\N
8	1	2	1	2	16.75	11	11	12.92	Assez Bien	2026-03-21 13:23:05	2026-03-21 13:23:05	59	\N	\N	\N
9	1	3	1	2	15.5	5	8	9.5	Insuffisant	2026-03-21 13:23:22	2026-03-21 13:23:22	56	\N	\N	\N
10	1	3	1	2	16.75	13.5	12	14.08	 Bien	2026-03-21 13:23:22	2026-03-21 13:23:22	57	\N	\N	\N
11	1	3	1	2	6.25	4	2	4.08	Très Faible	2026-03-21 13:23:23	2026-03-21 13:23:23	58	\N	\N	\N
12	1	3	1	2	19	16	15	16.67	Très Bien	2026-03-21 13:23:23	2026-03-21 13:23:23	59	\N	\N	\N
13	1	4	1	2	10.5	11	6	9.17	Insuffisant	2026-03-21 13:23:40	2026-03-21 13:23:40	56	\N	\N	\N
14	1	4	1	2	19	18	14	17	Très Bien	2026-03-21 13:23:40	2026-03-21 13:23:40	57	\N	\N	\N
15	1	4	1	2	12	9	13	11.33	Passable	2026-03-21 13:23:40	2026-03-21 13:23:40	58	\N	\N	\N
16	1	4	1	2	19	18	12	16.33	Très Bien	2026-03-21 13:23:40	2026-03-21 13:23:40	59	\N	\N	\N
17	1	5	1	2	13.5	5	10.5	9.67	Insuffisant	2026-03-21 13:23:56	2026-03-21 13:23:56	56	\N	\N	\N
18	1	5	1	2	16	12	12	13.33	Assez Bien	2026-03-21 13:23:56	2026-03-21 13:23:56	57	\N	\N	\N
19	1	5	1	2	11.75	9	4	8.25	Insuffisant	2026-03-21 13:23:56	2026-03-21 13:23:56	58	\N	\N	\N
20	1	5	1	2	16.5	14	18	16.17	Très Bien	2026-03-21 13:23:56	2026-03-21 13:23:56	59	\N	\N	\N
21	1	6	1	2	13.5	10	6	9.83	Insuffisant	2026-03-21 13:24:12	2026-03-21 13:24:12	56	\N	\N	\N
22	1	6	1	2	9.75	16.5	12	12.75	Assez Bien	2026-03-21 13:24:12	2026-03-21 13:24:12	57	\N	\N	\N
23	1	6	1	2	13	8	5.5	8.83	Insuffisant	2026-03-21 13:24:12	2026-03-21 13:24:12	58	\N	\N	\N
24	1	6	1	2	15.75	16	15	15.58	 Bien	2026-03-21 13:24:12	2026-03-21 13:24:12	59	\N	\N	\N
25	1	7	1	2	9.75	11	10	10.25	Passable	2026-03-21 13:24:26	2026-03-21 13:24:26	56	\N	\N	\N
26	1	7	1	2	16.25	17	15	16.08	Très Bien	2026-03-21 13:24:26	2026-03-21 13:24:26	57	\N	\N	\N
27	1	7	1	2	12.75	9	8	9.92	Insuffisant	2026-03-21 13:24:26	2026-03-21 13:24:26	58	\N	\N	\N
28	1	7	1	2	16	19.5	16	17.17	Très Bien	2026-03-21 13:24:26	2026-03-21 13:24:26	59	\N	\N	\N
29	1	8	1	2	8	9	10	9	Insuffisant	2026-03-21 13:24:42	2026-03-21 13:24:42	56	\N	\N	\N
30	1	8	1	2	14	10	12	12	Assez Bien	2026-03-21 13:24:42	2026-03-21 13:24:42	57	\N	\N	\N
31	1	8	1	2	8	6	10	8	Insuffisant	2026-03-21 13:24:42	2026-03-21 13:24:42	58	\N	\N	\N
32	1	8	1	2	12	11	14	12.33	Assez Bien	2026-03-21 13:24:42	2026-03-21 13:24:42	59	\N	\N	\N
33	2	9	1	2	14	15	13	14	 Bien	2026-03-21 13:27:07	2026-03-21 13:27:07	70	\N	\N	\N
34	2	9	1	2	13.5	18.25	15	15.58	 Bien	2026-03-21 13:27:07	2026-03-21 13:27:07	71	\N	\N	\N
35	2	9	1	2	16.5	17.5	18	17.33	Très Bien	2026-03-21 13:27:07	2026-03-21 13:27:07	72	\N	\N	\N
36	2	9	1	2	16	6.25	14	12.08	Assez Bien	2026-03-21 13:27:07	2026-03-21 13:27:07	73	\N	\N	\N
37	2	9	1	2	12	16	7.25	11.75	Passable	2026-03-21 13:27:07	2026-03-21 13:27:07	74	\N	\N	\N
38	2	9	1	2	14.5	7.5	15	12.33	Assez Bien	2026-03-21 13:27:07	2026-03-21 13:27:07	75	\N	\N	\N
39	2	9	1	2	19.5	19.25	19	19.25	Excellent	2026-03-21 13:27:07	2026-03-21 13:27:07	76	\N	\N	\N
40	2	10	1	2	11.5	8	10	9.83	Insuffisant	2026-03-21 13:27:24	2026-03-21 13:27:24	70	\N	\N	\N
41	2	10	1	2	8	8	8	8	Insuffisant	2026-03-21 13:27:24	2026-03-21 13:27:24	71	\N	\N	\N
42	2	10	1	2	20	10	12	14	 Bien	2026-03-21 13:27:24	2026-03-21 13:27:24	72	\N	\N	\N
43	2	10	1	2	9	8	10	9	Insuffisant	2026-03-21 13:27:24	2026-03-21 13:27:24	73	\N	\N	\N
44	2	10	1	2	10	8	7	8.33	Insuffisant	2026-03-21 13:27:24	2026-03-21 13:27:24	74	\N	\N	\N
45	2	10	1	2	12.5	7	7	8.83	Insuffisant	2026-03-21 13:27:24	2026-03-21 13:27:24	75	\N	\N	\N
46	2	10	1	2	16.5	10	11	12.5	Assez Bien	2026-03-21 13:27:24	2026-03-21 13:27:24	76	\N	\N	\N
47	2	11	1	2	11.25	10.25	13	11.5	Passable	2026-03-21 13:27:48	2026-03-21 13:27:48	70	\N	\N	\N
48	2	11	1	2	6	12	8	8.67	Insuffisant	2026-03-21 13:27:48	2026-03-21 13:27:48	71	\N	\N	\N
49	2	11	1	2	18	14	16	16	Très Bien	2026-03-21 13:27:48	2026-03-21 13:27:48	72	\N	\N	\N
50	2	11	1	2	5	7	7	6.33	Faible	2026-03-21 13:27:48	2026-03-21 13:27:48	73	\N	\N	\N
51	2	11	1	2	6.75	10	3.25	6.67	Faible	2026-03-21 13:27:48	2026-03-21 13:27:48	74	\N	\N	\N
52	2	11	1	2	7.5	7.25	10	8.25	Insuffisant	2026-03-21 13:27:48	2026-03-21 13:27:48	75	\N	\N	\N
53	2	11	1	2	15.75	12.25	15.5	14.5	 Bien	2026-03-21 13:27:48	2026-03-21 13:27:48	76	\N	\N	\N
54	2	12	1	2	15	18	13	15.33	 Bien	2026-03-21 13:28:07	2026-03-21 13:28:07	70	\N	\N	\N
55	2	12	1	2	16	20	15	17	Très Bien	2026-03-21 13:28:07	2026-03-21 13:28:07	71	\N	\N	\N
56	2	12	1	2	18	19	14	17	Très Bien	2026-03-21 13:28:07	2026-03-21 13:28:07	72	\N	\N	\N
57	2	12	1	2	14	19	17	16.67	Très Bien	2026-03-21 13:28:07	2026-03-21 13:28:07	73	\N	\N	\N
58	2	12	1	2	13	17	11	13.67	Assez Bien	2026-03-21 13:28:07	2026-03-21 13:28:07	74	\N	\N	\N
59	2	12	1	2	9.5	13	9	10.5	Passable	2026-03-21 13:28:07	2026-03-21 13:28:07	75	\N	\N	\N
60	2	12	1	2	20	19	14	17.67	Très Bien	2026-03-21 13:28:07	2026-03-21 13:28:07	76	\N	\N	\N
61	2	13	1	2	13.5	7	13	11.17	Passable	2026-03-21 13:28:24	2026-03-21 13:28:24	70	\N	\N	\N
62	2	13	1	2	11	9	10	10	Passable	2026-03-21 13:28:24	2026-03-21 13:28:24	71	\N	\N	\N
63	2	13	1	2	15.5	17.5	17.5	16.83	Très Bien	2026-03-21 13:28:24	2026-03-21 13:28:24	72	\N	\N	\N
64	2	13	1	2	12.5	8	13.5	11.33	Passable	2026-03-21 13:28:24	2026-03-21 13:28:24	73	\N	\N	\N
65	2	13	1	2	11	6	7	8	Insuffisant	2026-03-21 13:28:24	2026-03-21 13:28:24	74	\N	\N	\N
66	2	13	1	2	13	5	10	9.33	Insuffisant	2026-03-21 13:28:24	2026-03-21 13:28:24	75	\N	\N	\N
67	2	13	1	2	18	15	17.5	16.83	Très Bien	2026-03-21 13:28:24	2026-03-21 13:28:24	76	\N	\N	\N
68	2	14	1	2	14	12.5	7	11.17	Passable	2026-03-21 13:28:38	2026-03-21 13:28:38	70	\N	\N	\N
69	2	14	1	2	13.5	16	10	13.17	Assez Bien	2026-03-21 13:28:38	2026-03-21 13:28:38	71	\N	\N	\N
70	2	14	1	2	17.5	18.5	16	17.33	Très Bien	2026-03-21 13:28:38	2026-03-21 13:28:38	72	\N	\N	\N
71	2	14	1	2	13.5	12	11	12.17	Assez Bien	2026-03-21 13:28:38	2026-03-21 13:28:38	73	\N	\N	\N
72	2	14	1	2	9	15	2	8.67	Insuffisant	2026-03-21 13:28:38	2026-03-21 13:28:38	74	\N	\N	\N
73	2	14	1	2	8	10	7	8.33	Insuffisant	2026-03-21 13:28:38	2026-03-21 13:28:38	75	\N	\N	\N
74	2	14	1	2	14.75	17	16	15.92	 Bien	2026-03-21 13:28:38	2026-03-21 13:28:38	76	\N	\N	\N
75	2	15	1	2	14	8	12	11.33	Passable	2026-03-21 13:28:56	2026-03-21 13:28:56	70	\N	\N	\N
76	2	15	1	2	11	4	15	10	Passable	2026-03-21 13:28:56	2026-03-21 13:28:56	71	\N	\N	\N
77	2	15	1	2	16	10	16	14	 Bien	2026-03-21 13:28:56	2026-03-21 13:28:56	72	\N	\N	\N
78	2	15	1	2	12.75	10	13	11.92	Passable	2026-03-21 13:28:56	2026-03-21 13:28:56	73	\N	\N	\N
79	2	15	1	2	9.5	7	12	9.5	Insuffisant	2026-03-21 13:28:56	2026-03-21 13:28:56	74	\N	\N	\N
80	2	15	1	2	10.75	4	9	7.92	Faible	2026-03-21 13:28:56	2026-03-21 13:28:56	75	\N	\N	\N
81	2	15	1	2	15.75	14.5	10	13.42	Assez Bien	2026-03-21 13:28:56	2026-03-21 13:28:56	76	\N	\N	\N
82	2	16	1	2	13	10	14	12.33	Assez Bien	2026-03-21 13:29:12	2026-03-21 13:29:12	70	\N	\N	\N
83	2	16	1	2	14.5	14	16	14.83	 Bien	2026-03-21 13:29:12	2026-03-21 13:29:12	71	\N	\N	\N
84	2	16	1	2	16.5	18	18	17.5	Très Bien	2026-03-21 13:29:12	2026-03-21 13:29:12	72	\N	\N	\N
85	2	16	1	2	15.5	16	15	15.5	 Bien	2026-03-21 13:29:12	2026-03-21 13:29:12	73	\N	\N	\N
86	2	16	1	2	11	13	10	11.33	Passable	2026-03-21 13:29:12	2026-03-21 13:29:12	74	\N	\N	\N
87	2	16	1	2	14.5	14	12	13.5	Assez Bien	2026-03-21 13:29:12	2026-03-21 13:29:12	75	\N	\N	\N
88	2	16	1	2	13	14	16	14.33	 Bien	2026-03-21 13:29:12	2026-03-21 13:29:12	76	\N	\N	\N
89	3	18	1	2	19.5	14	15	16.17	Très Bien	2026-03-21 13:36:04	2026-03-21 13:36:04	77	\N	\N	\N
90	3	18	1	2	8.5	8	8	8.17	Insuffisant	2026-03-21 13:36:05	2026-03-21 13:36:05	78	\N	\N	\N
91	3	18	1	2	9.75	10	11	10.25	Passable	2026-03-21 13:36:05	2026-03-21 13:36:05	79	\N	\N	\N
92	3	18	1	2	12.75	13	14	13.25	Assez Bien	2026-03-21 13:36:05	2026-03-21 13:36:05	80	\N	\N	\N
93	3	18	1	2	8.75	8	10	8.92	Insuffisant	2026-03-21 13:36:05	2026-03-21 13:36:05	81	\N	\N	\N
94	3	18	1	2	8	10	10	9.33	Insuffisant	2026-03-21 13:36:05	2026-03-21 13:36:05	82	\N	\N	\N
95	3	18	1	2	7.5	8	8	7.83	Faible	2026-03-21 13:36:05	2026-03-21 13:36:05	83	\N	\N	\N
96	3	19	1	2	18.5	17.5	15	17	Très Bien	2026-03-21 13:36:35	2026-03-21 13:36:35	77	\N	\N	\N
97	3	19	1	2	13.25	10	8	10.42	Passable	2026-03-21 13:36:35	2026-03-21 13:36:35	78	\N	\N	\N
98	3	19	1	2	5.5	15	10	10.17	Passable	2026-03-21 13:36:35	2026-03-21 13:36:35	79	\N	\N	\N
99	3	19	1	2	16.5	18	14	16.17	Très Bien	2026-03-21 13:36:35	2026-03-21 13:36:35	80	\N	\N	\N
100	3	19	1	2	7	14	10.25	10.42	Passable	2026-03-21 13:36:35	2026-03-21 13:36:35	81	\N	\N	\N
101	3	19	1	2	7	15.25	10	10.75	Passable	2026-03-21 13:36:35	2026-03-21 13:36:35	82	\N	\N	\N
102	3	19	1	2	4.5	11.5	10	8.67	Insuffisant	2026-03-21 13:36:35	2026-03-21 13:36:35	83	\N	\N	\N
103	3	20	1	2	19.5	20	17.5	19	Excellent	2026-03-21 13:37:14	2026-03-21 13:37:14	77	\N	\N	\N
104	3	20	1	2	16	18.5	7.5	14	 Bien	2026-03-21 13:37:14	2026-03-21 13:37:14	78	\N	\N	\N
105	3	20	1	2	14.75	19	8	13.92	Assez Bien	2026-03-21 13:37:14	2026-03-21 13:37:14	79	\N	\N	\N
106	3	20	1	2	18.75	20	10	16.25	Très Bien	2026-03-21 13:37:14	2026-03-21 13:37:14	80	\N	\N	\N
107	3	20	1	2	16	13	10.5	13.17	Assez Bien	2026-03-21 13:37:14	2026-03-21 13:37:14	81	\N	\N	\N
108	3	20	1	2	15	19.5	6	13.5	Assez Bien	2026-03-21 13:37:14	2026-03-21 13:37:14	82	\N	\N	\N
109	3	20	1	2	10.75	20	10	13.58	Assez Bien	2026-03-21 13:37:14	2026-03-21 13:37:14	83	\N	\N	\N
110	3	21	1	2	20	18	16	18	Excellent	2026-03-21 13:37:39	2026-03-21 13:37:39	77	\N	\N	\N
111	3	21	1	2	6.25	4	6	5.42	Très Faible	2026-03-21 13:37:39	2026-03-21 13:37:39	78	\N	\N	\N
112	3	21	1	2	10.75	8	12	10.25	Passable	2026-03-21 13:37:39	2026-03-21 13:37:39	79	\N	\N	\N
113	3	21	1	2	12.5	17	10	13.17	Assez Bien	2026-03-21 13:37:39	2026-03-21 13:37:39	80	\N	\N	\N
114	3	21	1	2	5	7	5	5.67	Très Faible	2026-03-21 13:37:39	2026-03-21 13:37:39	81	\N	\N	\N
116	3	21	1	2	9.87	\N	7	8.44	Insuffisant	2026-03-21 13:37:39	2026-03-21 13:37:39	83	\N	\N	\N
117	3	22	1	2	18	18	17	17.67	Très Bien	2026-03-21 13:37:58	2026-03-21 13:37:58	77	\N	\N	\N
118	3	22	1	2	16	10	8	11.33	Passable	2026-03-21 13:37:58	2026-03-21 13:37:58	78	\N	\N	\N
119	3	22	1	2	15	10	11	12	Assez Bien	2026-03-21 13:37:58	2026-03-21 13:37:58	79	\N	\N	\N
120	3	22	1	2	17	15	16	16	Très Bien	2026-03-21 13:37:58	2026-03-21 13:37:58	80	\N	\N	\N
121	3	22	1	2	13	15	7	11.67	Passable	2026-03-21 13:37:58	2026-03-21 13:37:58	81	\N	\N	\N
122	3	22	1	2	17.5	13	14	14.83	 Bien	2026-03-21 13:37:58	2026-03-21 13:37:58	82	\N	\N	\N
123	3	22	1	2	13	\N	12	12.5	Assez Bien	2026-03-21 13:37:58	2026-03-21 13:37:58	83	\N	\N	\N
124	3	23	1	2	19.86	20	18	19.29	Excellent	2026-03-21 13:38:18	2026-03-21 13:38:18	77	\N	\N	\N
125	3	23	1	2	15.75	11	5	10.58	Passable	2026-03-21 13:38:18	2026-03-21 13:38:18	78	\N	\N	\N
126	3	23	1	2	13.75	13	12	12.92	Assez Bien	2026-03-21 13:38:18	2026-03-21 13:38:18	79	\N	\N	\N
127	3	23	1	2	13	16.5	16	15.17	 Bien	2026-03-21 13:38:18	2026-03-21 13:38:18	80	\N	\N	\N
128	3	23	1	2	8.5	15.5	5	9.67	Insuffisant	2026-03-21 13:38:18	2026-03-21 13:38:18	81	\N	\N	\N
129	3	23	1	2	11.5	8	6	8.5	Insuffisant	2026-03-21 13:38:18	2026-03-21 13:38:18	82	\N	\N	\N
130	3	23	1	2	14.75	12	9	11.92	Passable	2026-03-21 13:38:18	2026-03-21 13:38:18	83	\N	\N	\N
131	3	24	1	2	15.5	14	14	14.5	 Bien	2026-03-21 13:38:34	2026-03-21 13:38:34	77	\N	\N	\N
132	3	24	1	2	13.5	14	16	14.5	 Bien	2026-03-21 13:38:34	2026-03-21 13:38:34	78	\N	\N	\N
133	3	24	1	2	15	12	14	13.67	Assez Bien	2026-03-21 13:38:34	2026-03-21 13:38:34	79	\N	\N	\N
134	3	24	1	2	17	15	15	15.67	 Bien	2026-03-21 13:38:34	2026-03-21 13:38:34	80	\N	\N	\N
135	3	24	1	2	13	13	14	13.33	Assez Bien	2026-03-21 13:38:34	2026-03-21 13:38:34	81	\N	\N	\N
136	3	24	1	2	13.5	11	10	11.5	Passable	2026-03-21 13:38:34	2026-03-21 13:38:34	82	\N	\N	\N
137	3	24	1	2	13	14	14	13.67	Assez Bien	2026-03-21 13:38:34	2026-03-21 13:38:34	83	\N	\N	\N
138	3	30	1	2	18.33	20	18	18.78	Excellent	2026-03-21 13:38:49	2026-03-21 13:38:49	77	\N	\N	\N
139	3	30	1	2	13.33	18	7	12.78	Assez Bien	2026-03-21 13:38:49	2026-03-21 13:38:49	78	\N	\N	\N
140	3	30	1	2	15.66	17	14	15.55	 Bien	2026-03-21 13:38:49	2026-03-21 13:38:49	79	\N	\N	\N
141	3	30	1	2	16.66	16	15	15.89	 Bien	2026-03-21 13:38:49	2026-03-21 13:38:49	80	\N	\N	\N
142	3	30	1	2	15.66	13	15	14.55	 Bien	2026-03-21 13:38:49	2026-03-21 13:38:49	81	\N	\N	\N
143	3	30	1	2	15	14	6	11.67	Passable	2026-03-21 13:38:49	2026-03-21 13:38:49	82	\N	\N	\N
144	3	30	1	2	16.66	\N	12	14.33	 Bien	2026-03-21 13:38:49	2026-03-21 13:38:49	83	\N	\N	\N
145	4	25	1	2	17	19.5	13	16.5	Très Bien	2026-03-21 13:39:15	2026-03-21 13:39:15	84	\N	\N	\N
146	4	25	1	2	11.5	14	10.5	12	Assez Bien	2026-03-21 13:39:15	2026-03-21 13:39:15	85	\N	\N	\N
147	4	25	1	2	13.5	11	9	11.17	Passable	2026-03-21 13:39:15	2026-03-21 13:39:15	86	\N	\N	\N
148	4	25	1	2	11	15	6.5	10.83	Passable	2026-03-21 13:39:15	2026-03-21 13:39:15	87	\N	\N	\N
149	4	25	1	2	14	18	9.5	13.83	Assez Bien	2026-03-21 13:39:15	2026-03-21 13:39:15	88	\N	\N	\N
150	4	25	1	2	15.5	17	8.5	13.67	Assez Bien	2026-03-21 13:39:15	2026-03-21 13:39:15	89	\N	\N	\N
151	4	25	1	2	15.5	14	11.5	13.67	Assez Bien	2026-03-21 13:39:15	2026-03-21 13:39:15	90	\N	\N	\N
152	4	26	1	2	13	13	11	12.33	Assez Bien	2026-03-21 13:39:32	2026-03-21 13:39:32	84	\N	\N	\N
153	4	26	1	2	13.5	13	10	12.17	Assez Bien	2026-03-21 13:39:32	2026-03-21 13:39:32	85	\N	\N	\N
154	4	26	1	2	13	10	10	11	Passable	2026-03-21 13:39:32	2026-03-21 13:39:32	86	\N	\N	\N
155	4	26	1	2	10	8	7	8.33	Insuffisant	2026-03-21 13:39:32	2026-03-21 13:39:32	87	\N	\N	\N
156	4	26	1	2	12.5	10	10	10.83	Passable	2026-03-21 13:39:32	2026-03-21 13:39:32	88	\N	\N	\N
157	4	26	1	2	9	9	10	9.33	Insuffisant	2026-03-21 13:39:32	2026-03-21 13:39:32	89	\N	\N	\N
158	4	26	1	2	11.5	11	13	11.83	Passable	2026-03-21 13:39:32	2026-03-21 13:39:32	90	\N	\N	\N
159	4	27	1	2	13	14	10	12.33	Assez Bien	2026-03-21 13:39:49	2026-03-21 13:39:49	84	\N	\N	\N
160	4	27	1	2	16.5	17	9	14.17	 Bien	2026-03-21 13:39:49	2026-03-21 13:39:49	85	\N	\N	\N
161	4	27	1	2	16	14	9	13	Assez Bien	2026-03-21 13:39:49	2026-03-21 13:39:49	86	\N	\N	\N
162	4	27	1	2	14.5	11	6	10.5	Passable	2026-03-21 13:39:49	2026-03-21 13:39:49	87	\N	\N	\N
163	4	27	1	2	13.5	15	8	12.17	Assez Bien	2026-03-21 13:39:49	2026-03-21 13:39:49	88	\N	\N	\N
164	4	27	1	2	15.5	13	9	12.5	Assez Bien	2026-03-21 13:39:49	2026-03-21 13:39:49	89	\N	\N	\N
165	4	27	1	2	17	16	15	16	Très Bien	2026-03-21 13:39:49	2026-03-21 13:39:49	90	\N	\N	\N
166	4	28	1	2	11	11.5	14.5	12.33	Assez Bien	2026-03-21 13:40:07	2026-03-21 13:40:07	84	\N	\N	\N
167	4	28	1	2	12.66	10	13	11.89	Passable	2026-03-21 13:40:07	2026-03-21 13:40:07	85	\N	\N	\N
168	4	28	1	2	13.66	7.5	10.5	10.55	Passable	2026-03-21 13:40:07	2026-03-21 13:40:07	86	\N	\N	\N
169	4	28	1	2	8.33	3	6.5	5.94	Très Faible	2026-03-21 13:40:07	2026-03-21 13:40:07	87	\N	\N	\N
170	4	28	1	2	9.66	6.5	13.5	9.89	Insuffisant	2026-03-21 13:40:07	2026-03-21 13:40:07	88	\N	\N	\N
171	4	28	1	2	10.33	8.5	12	10.28	Passable	2026-03-21 13:40:07	2026-03-21 13:40:07	89	\N	\N	\N
172	4	28	1	2	12	14.5	12.5	13	Assez Bien	2026-03-21 13:40:07	2026-03-21 13:40:07	90	\N	\N	\N
173	4	29	1	2	17.33	18	16	17.11	Très Bien	2026-03-21 13:40:21	2026-03-21 13:40:21	84	\N	\N	\N
174	4	29	1	2	18.33	18	16	17.44	Très Bien	2026-03-21 13:40:21	2026-03-21 13:40:21	85	\N	\N	\N
175	4	29	1	2	18	16	15	16.33	Très Bien	2026-03-21 13:40:21	2026-03-21 13:40:21	86	\N	\N	\N
176	4	29	1	2	17.66	18	12	15.89	 Bien	2026-03-21 13:40:21	2026-03-21 13:40:21	87	\N	\N	\N
177	4	29	1	2	18.66	16	14	16.22	Très Bien	2026-03-21 13:40:21	2026-03-21 13:40:21	88	\N	\N	\N
178	4	29	1	2	19	19	14	17.33	Très Bien	2026-03-21 13:40:21	2026-03-21 13:40:21	89	\N	\N	\N
179	4	29	1	2	18.66	17	18	17.89	Très Bien	2026-03-21 13:40:21	2026-03-21 13:40:21	90	\N	\N	\N
180	4	31	1	2	9	5	7	7	Faible	2026-03-21 13:40:37	2026-03-21 13:40:37	84	\N	\N	\N
181	4	31	1	2	15.5	6	11	10.83	Passable	2026-03-21 13:40:37	2026-03-21 13:40:37	85	\N	\N	\N
182	4	31	1	2	15	9	6	10	Passable	2026-03-21 13:40:37	2026-03-21 13:40:37	86	\N	\N	\N
183	4	31	1	2	7.5	6	6	6.5	Faible	2026-03-21 13:40:37	2026-03-21 13:40:37	87	\N	\N	\N
184	4	31	1	2	14.25	7	11	10.75	Passable	2026-03-21 13:40:37	2026-03-21 13:40:37	88	\N	\N	\N
185	4	31	1	2	17.75	7	6.5	10.42	Passable	2026-03-21 13:40:37	2026-03-21 13:40:37	89	\N	\N	\N
186	4	31	1	2	17.25	7	14.5	12.92	Assez Bien	2026-03-21 13:40:37	2026-03-21 13:40:37	90	\N	\N	\N
187	4	32	1	2	9.25	6	7	7.42	Faible	2026-03-21 13:40:53	2026-03-21 13:40:53	84	\N	\N	\N
188	4	32	1	2	7.75	8	10	8.58	Insuffisant	2026-03-21 13:40:53	2026-03-21 13:40:53	85	\N	\N	\N
189	4	32	1	2	6.25	3	8	5.75	Très Faible	2026-03-21 13:40:53	2026-03-21 13:40:53	86	\N	\N	\N
190	4	32	1	2	8.5	6	4	6.17	Faible	2026-03-21 13:40:53	2026-03-21 13:40:53	87	\N	\N	\N
191	4	32	1	2	8.5	6	7	7.17	Faible	2026-03-21 13:40:53	2026-03-21 13:40:53	88	\N	\N	\N
192	4	32	1	2	7.5	8.5	4	6.67	Faible	2026-03-21 13:40:53	2026-03-21 13:40:53	89	\N	\N	\N
193	4	32	1	2	7.75	9	7	7.92	Faible	2026-03-21 13:40:53	2026-03-21 13:40:53	90	\N	\N	\N
194	4	33	1	2	7.83	8.5	10	8.78	Insuffisant	2026-03-21 13:41:07	2026-03-21 13:41:07	84	\N	\N	\N
195	4	33	1	2	16.5	10	9.5	12	Assez Bien	2026-03-21 13:41:07	2026-03-21 13:41:07	85	\N	\N	\N
196	4	33	1	2	15.5	14.25	11	13.58	Assez Bien	2026-03-21 13:41:07	2026-03-21 13:41:07	86	\N	\N	\N
197	4	33	1	2	10.66	5.5	5	7.05	Faible	2026-03-21 13:41:07	2026-03-21 13:41:07	87	\N	\N	\N
198	4	33	1	2	14.83	6	5	8.61	Insuffisant	2026-03-21 13:41:07	2026-03-21 13:41:07	88	\N	\N	\N
199	4	33	1	2	13.66	6.5	4.5	8.22	Insuffisant	2026-03-21 13:41:07	2026-03-21 13:41:07	89	\N	\N	\N
200	4	33	1	2	16.83	9	10.5	12.11	Assez Bien	2026-03-21 13:41:07	2026-03-21 13:41:07	90	\N	\N	\N
201	4	34	1	2	12	14	14	13.33	Assez Bien	2026-03-21 13:41:22	2026-03-21 13:41:22	84	\N	\N	\N
202	4	34	1	2	17	14	18	16.33	Très Bien	2026-03-21 13:41:22	2026-03-21 13:41:22	85	\N	\N	\N
203	4	34	1	2	14	12	16	14	 Bien	2026-03-21 13:41:22	2026-03-21 13:41:22	86	\N	\N	\N
204	4	34	1	2	10	10	18	12.67	Assez Bien	2026-03-21 13:41:22	2026-03-21 13:41:22	87	\N	\N	\N
205	4	34	1	2	13	12	18	14.33	 Bien	2026-03-21 13:41:22	2026-03-21 13:41:22	88	\N	\N	\N
206	4	34	1	2	15	11	18	14.67	 Bien	2026-03-21 13:41:22	2026-03-21 13:41:22	89	\N	\N	\N
207	4	34	1	2	17	11	18	15.33	 Bien	2026-03-21 13:41:22	2026-03-21 13:41:22	90	\N	\N	\N
208	9	35	1	2	12	10	10	10.67	Passable	2026-03-21 13:41:52	2026-03-21 13:41:52	91	\N	\N	\N
209	9	35	1	2	10.66	14	10	11.55	Passable	2026-03-21 13:41:52	2026-03-21 13:41:52	92	\N	\N	\N
210	9	35	1	2	12.23	12	8	10.74	Passable	2026-03-21 13:41:52	2026-03-21 13:41:52	93	\N	\N	\N
211	9	35	1	2	13.33	13	10	12.11	Assez Bien	2026-03-21 13:41:52	2026-03-21 13:41:52	94	\N	\N	\N
212	9	35	1	2	16	11	12	13	Assez Bien	2026-03-21 13:41:52	2026-03-21 13:41:52	95	\N	\N	\N
213	9	36	1	2	10.66	9	14	11.22	Passable	2026-03-21 13:42:09	2026-03-21 13:42:09	91	\N	\N	\N
214	9	36	1	2	10.33	14	8	10.78	Passable	2026-03-21 13:42:09	2026-03-21 13:42:09	92	\N	\N	\N
215	9	36	1	2	11	16	16	14.33	 Bien	2026-03-21 13:42:09	2026-03-21 13:42:09	93	\N	\N	\N
216	9	36	1	2	11.66	15	16	14.22	 Bien	2026-03-21 13:42:09	2026-03-21 13:42:09	94	\N	\N	\N
217	9	36	1	2	10	16	16	14	 Bien	2026-03-21 13:42:09	2026-03-21 13:42:09	95	\N	\N	\N
218	9	38	1	2	10.5	11	15	12.17	Assez Bien	2026-03-21 13:52:04	2026-03-21 13:52:04	91	\N	\N	\N
219	9	38	1	2	11	12	10	11	Passable	2026-03-21 13:52:04	2026-03-21 13:52:04	92	\N	\N	\N
220	9	38	1	2	9	10	11	10	Passable	2026-03-21 13:52:04	2026-03-21 13:52:04	93	\N	\N	\N
221	9	38	1	2	17.5	16	10	14.5	 Bien	2026-03-21 13:52:04	2026-03-21 13:52:04	94	\N	\N	\N
222	9	38	1	2	11	11	5	9	Insuffisant	2026-03-21 13:52:04	2026-03-21 13:52:04	95	\N	\N	\N
223	9	39	1	2	17	13	8	12.67	Assez Bien	2026-03-21 13:52:24	2026-03-21 13:52:24	91	\N	\N	\N
224	9	39	1	2	11.5	11	6	9.5	Insuffisant	2026-03-21 13:52:24	2026-03-21 13:52:24	92	\N	\N	\N
225	9	39	1	2	8.5	7	8	7.83	Faible	2026-03-21 13:52:24	2026-03-21 13:52:24	93	\N	\N	\N
226	9	39	1	2	13.75	14	14	13.92	Assez Bien	2026-03-21 13:52:24	2026-03-21 13:52:24	94	\N	\N	\N
227	9	39	1	2	16	6.5	8	10.17	Passable	2026-03-21 13:52:24	2026-03-21 13:52:24	95	\N	\N	\N
228	9	40	1	2	11	11	10	10.67	Passable	2026-03-21 13:52:55	2026-03-21 13:52:55	91	\N	\N	\N
229	9	40	1	2	6	6	4	5.33	Très Faible	2026-03-21 13:52:55	2026-03-21 13:52:55	92	\N	\N	\N
230	9	40	1	2	7.5	8	11	8.83	Insuffisant	2026-03-21 13:52:55	2026-03-21 13:52:55	93	\N	\N	\N
231	9	40	1	2	8	10	7	8.33	Insuffisant	2026-03-21 13:52:55	2026-03-21 13:52:55	94	\N	\N	\N
232	9	40	1	2	9.5	8	10	9.17	Insuffisant	2026-03-21 13:52:55	2026-03-21 13:52:55	95	\N	\N	\N
233	9	41	1	2	14	12.5	15	13.83	Assez Bien	2026-03-21 13:53:13	2026-03-21 13:53:13	91	\N	\N	\N
234	9	41	1	2	11	8	11	10	Passable	2026-03-21 13:53:13	2026-03-21 13:53:13	92	\N	\N	\N
235	9	41	1	2	9	8	7	8	Insuffisant	2026-03-21 13:53:13	2026-03-21 13:53:13	93	\N	\N	\N
236	9	41	1	2	13.33	15.5	11	13.28	Assez Bien	2026-03-21 13:53:13	2026-03-21 13:53:13	94	\N	\N	\N
237	9	41	1	2	11	8.25	12	10.42	Passable	2026-03-21 13:53:13	2026-03-21 13:53:13	95	\N	\N	\N
238	9	42	1	2	16	13	18	15.67	 Bien	2026-03-21 13:53:34	2026-03-21 13:53:34	91	\N	\N	\N
239	9	42	1	2	10	10	10	10	Passable	2026-03-21 13:53:34	2026-03-21 13:53:34	92	\N	\N	\N
240	9	42	1	2	12	11	10	11	Passable	2026-03-21 13:53:34	2026-03-21 13:53:34	93	\N	\N	\N
241	9	42	1	2	10	10	10	10	Passable	2026-03-21 13:53:34	2026-03-21 13:53:34	94	\N	\N	\N
242	9	42	1	2	15	13	7	11.67	Passable	2026-03-21 13:53:34	2026-03-21 13:53:34	95	\N	\N	\N
250	11	51	1	2	15.33	13	9	12.44	Assez Bien	2026-03-21 17:57:06	2026-03-21 17:57:06	97	\N	\N	\N
251	11	52	1	2	17.33	18	17	17.44	Très Bien	2026-03-21 17:57:27	2026-03-21 17:57:27	97	\N	\N	\N
252	11	53	1	2	15	7	5	9	Insuffisant	2026-03-21 17:59:07	2026-03-21 17:59:07	97	\N	\N	\N
253	11	54	1	2	10	18	14	14	 Bien	2026-03-21 17:59:27	2026-03-21 17:59:27	97	\N	\N	\N
254	11	55	1	2	16	14	9	13	Assez Bien	2026-03-21 18:00:00	2026-03-21 18:00:00	97	\N	\N	\N
255	11	56	1	2	13.5	9	7	9.83	Insuffisant	2026-03-21 18:00:19	2026-03-21 18:00:19	97	\N	\N	\N
256	11	57	1	2	18	13	11	14	 Bien	2026-03-21 18:00:37	2026-03-21 18:00:37	97	\N	\N	\N
257	11	58	1	2	16	14	18	16	Très Bien	2026-03-21 18:00:58	2026-03-21 18:00:58	97	\N	\N	\N
263	9	37	1	2	13.5	13	10	12.17	Assez Bien	2026-03-21 18:41:43	2026-03-21 18:41:43	91	\N	\N	\N
264	9	37	1	2	13.5	16	15	14.83	 Bien	2026-03-21 18:41:43	2026-03-21 18:41:43	92	\N	\N	\N
265	9	37	1	2	12	7	12	10.33	Passable	2026-03-21 18:41:43	2026-03-21 18:41:43	93	\N	\N	\N
266	9	37	1	2	8	9	13	10	Passable	2026-03-21 18:41:43	2026-03-21 18:41:43	94	\N	\N	\N
267	9	37	1	2	11.5	13	13	12.5	Assez Bien	2026-03-21 18:41:43	2026-03-21 18:41:43	95	\N	\N	\N
268	1	1	2	2	7.5	6	10	7.83	Faible	2026-03-21 21:14:15	2026-03-21 21:14:15	56	\N	\N	\N
269	1	1	2	2	17	15	17.5	16.5	Très Bien	2026-03-21 21:14:15	2026-03-21 21:14:15	57	\N	\N	\N
270	1	1	2	2	10	7.5	16	11.17	Passable	2026-03-21 21:14:15	2026-03-21 21:14:15	58	\N	\N	\N
271	1	1	2	2	17.5	18.5	17.5	17.83	Très Bien	2026-03-21 21:14:15	2026-03-21 21:14:15	59	\N	\N	\N
272	1	2	2	2	5	8	8	7	Faible	2026-03-21 21:14:39	2026-03-21 21:14:39	56	\N	\N	\N
273	1	2	2	2	14	13	11	12.67	Assez Bien	2026-03-21 21:14:39	2026-03-21 21:14:39	57	\N	\N	\N
274	1	2	2	2	3.5	7	7	5.83	Très Faible	2026-03-21 21:14:39	2026-03-21 21:14:39	58	\N	\N	\N
275	1	2	2	2	17	10	11	12.67	Assez Bien	2026-03-21 21:14:39	2026-03-21 21:14:39	59	\N	\N	\N
276	1	3	2	2	4.5	5.5	11	7	Faible	2026-03-21 21:14:56	2026-03-21 21:14:56	56	\N	\N	\N
277	1	3	2	2	13	16.5	13.5	14.33	 Bien	2026-03-21 21:14:56	2026-03-21 21:14:56	57	\N	\N	\N
278	1	3	2	2	2.5	5	13.5	7	Faible	2026-03-21 21:14:56	2026-03-21 21:14:56	58	\N	\N	\N
279	1	3	2	2	16.5	15.5	18.5	16.83	Très Bien	2026-03-21 21:14:56	2026-03-21 21:14:56	59	\N	\N	\N
280	1	4	2	2	16.5	6	7	9.83	Insuffisant	2026-03-21 21:15:13	2026-03-21 21:15:13	56	\N	\N	\N
281	1	4	2	2	18.5	15	17	16.83	Très Bien	2026-03-21 21:15:13	2026-03-21 21:15:13	57	\N	\N	\N
282	1	4	2	2	16.5	9	11	12.17	Assez Bien	2026-03-21 21:15:13	2026-03-21 21:15:13	58	\N	\N	\N
283	1	4	2	2	17.5	15	15	15.83	 Bien	2026-03-21 21:15:13	2026-03-21 21:15:13	59	\N	\N	\N
284	1	5	2	2	7.5	6	6	6.5	Faible	2026-03-21 21:15:38	2026-03-21 21:15:38	56	\N	\N	\N
285	1	5	2	2	18.5	18	14	16.83	Très Bien	2026-03-21 21:15:38	2026-03-21 21:15:38	57	\N	\N	\N
286	1	5	2	2	12.5	6	10.5	9.67	Insuffisant	2026-03-21 21:15:38	2026-03-21 21:15:38	58	\N	\N	\N
287	1	5	2	2	18.5	12.5	16	15.67	 Bien	2026-03-21 21:15:38	2026-03-21 21:15:38	59	\N	\N	\N
288	1	6	2	2	4.33	11	2.5	5.94	Très Faible	2026-03-21 21:17:03	2026-03-21 21:17:03	56	\N	\N	\N
289	1	6	2	2	13.66	11.5	9	11.39	Passable	2026-03-21 21:17:03	2026-03-21 21:17:03	57	\N	\N	\N
290	1	6	2	2	10.33	10	11	10.44	Passable	2026-03-21 21:17:03	2026-03-21 21:17:03	58	\N	\N	\N
291	1	6	2	2	14.16	11.5	16	13.89	Assez Bien	2026-03-21 21:17:03	2026-03-21 21:17:03	59	\N	\N	\N
292	1	7	2	2	14.5	9	7	10.17	Passable	2026-03-21 21:17:22	2026-03-21 21:17:22	56	\N	\N	\N
293	1	7	2	2	13	17	17	15.67	 Bien	2026-03-21 21:17:22	2026-03-21 21:17:22	57	\N	\N	\N
294	1	7	2	2	16	9	8	11	Passable	2026-03-21 21:17:22	2026-03-21 21:17:22	58	\N	\N	\N
295	1	7	2	2	17	15	20	17.33	Très Bien	2026-03-21 21:17:22	2026-03-21 21:17:22	59	\N	\N	\N
296	1	8	2	2	8	10	15	11	Passable	2026-03-21 21:17:40	2026-03-21 21:17:40	56	\N	\N	\N
297	1	8	2	2	11	14	13	12.67	Assez Bien	2026-03-21 21:17:40	2026-03-21 21:17:40	57	\N	\N	\N
298	1	8	2	2	7.5	9	10	8.83	Insuffisant	2026-03-21 21:17:40	2026-03-21 21:17:40	58	\N	\N	\N
299	1	8	2	2	9	13	10	10.67	Passable	2026-03-21 21:17:40	2026-03-21 21:17:40	59	\N	\N	\N
300	2	9	2	2	12.5	18	15	15.17	 Bien	2026-03-21 21:18:04	2026-03-21 21:18:04	70	\N	\N	\N
301	2	9	2	2	16.5	14	11	13.83	Assez Bien	2026-03-21 21:18:04	2026-03-21 21:18:04	71	\N	\N	\N
302	2	9	2	2	18.5	19.25	15	17.58	Très Bien	2026-03-21 21:18:04	2026-03-21 21:18:04	72	\N	\N	\N
303	2	9	2	2	18	20	15	17.67	Très Bien	2026-03-21 21:18:04	2026-03-21 21:18:04	73	\N	\N	\N
304	2	9	2	2	15.5	10.25	10.5	12.08	Assez Bien	2026-03-21 21:18:04	2026-03-21 21:18:04	74	\N	\N	\N
305	2	9	2	2	16	11	10.5	12.5	Assez Bien	2026-03-21 21:18:04	2026-03-21 21:18:04	75	\N	\N	\N
306	2	9	2	2	18	14	13	15	 Bien	2026-03-21 21:18:04	2026-03-21 21:18:04	76	\N	\N	\N
307	2	10	2	2	10.75	11	10	10.58	Passable	2026-03-21 21:18:26	2026-03-21 21:18:26	70	\N	\N	\N
308	2	10	2	2	3	10	10	7.67	Faible	2026-03-21 21:18:26	2026-03-21 21:18:26	71	\N	\N	\N
309	2	10	2	2	17.25	14	13	14.75	 Bien	2026-03-21 21:18:26	2026-03-21 21:18:26	72	\N	\N	\N
310	2	10	2	2	10.5	10	10	10.17	Passable	2026-03-21 21:18:26	2026-03-21 21:18:26	73	\N	\N	\N
311	2	10	2	2	8.5	7	8	7.83	Faible	2026-03-21 21:18:26	2026-03-21 21:18:26	74	\N	\N	\N
312	2	10	2	2	9	7	8	8	Insuffisant	2026-03-21 21:18:26	2026-03-21 21:18:26	75	\N	\N	\N
313	2	10	2	2	16	11	13	13.33	Assez Bien	2026-03-21 21:18:26	2026-03-21 21:18:26	76	\N	\N	\N
314	2	11	2	2	16.5	13	18.25	15.92	 Bien	2026-03-21 21:18:42	2026-03-21 21:18:42	70	\N	\N	\N
315	2	11	2	2	5.75	9	10	8.25	Insuffisant	2026-03-21 21:18:42	2026-03-21 21:18:42	71	\N	\N	\N
316	2	11	2	2	19.5	15.5	17.25	17.42	Très Bien	2026-03-21 21:18:42	2026-03-21 21:18:42	72	\N	\N	\N
317	2	11	2	2	10.75	10	11.5	10.75	Passable	2026-03-21 21:18:42	2026-03-21 21:18:42	73	\N	\N	\N
318	2	11	2	2	6	7	6.5	6.5	Faible	2026-03-21 21:18:42	2026-03-21 21:18:42	74	\N	\N	\N
319	2	11	2	2	12.75	8.5	6	9.08	Insuffisant	2026-03-21 21:18:42	2026-03-21 21:18:42	75	\N	\N	\N
320	2	11	2	2	19.25	13	15.25	15.83	 Bien	2026-03-21 21:18:42	2026-03-21 21:18:42	76	\N	\N	\N
321	2	12	2	2	15.5	16	18	16.5	Très Bien	2026-03-21 21:19:03	2026-03-21 21:19:03	70	\N	\N	\N
322	2	12	2	2	14.5	13	12	13.17	Assez Bien	2026-03-21 21:19:03	2026-03-21 21:19:03	71	\N	\N	\N
323	2	12	2	2	17.5	17	17	17.17	Très Bien	2026-03-21 21:19:03	2026-03-21 21:19:03	72	\N	\N	\N
324	2	12	2	2	17.5	16	15	16.17	Très Bien	2026-03-21 21:19:03	2026-03-21 21:19:03	73	\N	\N	\N
325	2	12	2	2	10	14	11	11.67	Passable	2026-03-21 21:19:03	2026-03-21 21:19:03	74	\N	\N	\N
326	2	12	2	2	9	8	10	9	Insuffisant	2026-03-21 21:19:03	2026-03-21 21:19:03	75	\N	\N	\N
327	2	12	2	2	17.5	18	16	17.17	Très Bien	2026-03-21 21:19:03	2026-03-21 21:19:03	76	\N	\N	\N
328	2	13	2	2	18	13	16	15.67	 Bien	2026-03-21 21:19:26	2026-03-21 21:19:26	70	\N	\N	\N
329	2	13	2	2	13	6	7	8.67	Insuffisant	2026-03-21 21:19:26	2026-03-21 21:19:26	71	\N	\N	\N
330	2	13	2	2	20	16	18.5	18.17	Excellent	2026-03-21 21:19:26	2026-03-21 21:19:26	72	\N	\N	\N
331	2	13	2	2	13	11	14.5	12.83	Assez Bien	2026-03-21 21:19:26	2026-03-21 21:19:26	73	\N	\N	\N
332	2	13	2	2	14	5	10	9.67	Insuffisant	2026-03-21 21:19:26	2026-03-21 21:19:26	74	\N	\N	\N
333	2	13	2	2	14.5	9	8.5	10.67	Passable	2026-03-21 21:19:26	2026-03-21 21:19:26	75	\N	\N	\N
334	2	13	2	2	20	15.5	18.5	18	Excellent	2026-03-21 21:19:26	2026-03-21 21:19:26	76	\N	\N	\N
335	2	14	2	2	12.5	8	12	10.83	Passable	2026-03-21 21:19:43	2026-03-21 21:19:43	70	\N	\N	\N
336	2	14	2	2	8.5	10	3	7.17	Faible	2026-03-21 21:19:43	2026-03-21 21:19:43	71	\N	\N	\N
337	2	14	2	2	17	15	18.5	16.83	Très Bien	2026-03-21 21:19:43	2026-03-21 21:19:43	72	\N	\N	\N
338	2	14	2	2	13	13	16.5	14.17	 Bien	2026-03-21 21:19:43	2026-03-21 21:19:43	73	\N	\N	\N
339	2	14	2	2	8.25	4	12.5	8.25	Insuffisant	2026-03-21 21:19:43	2026-03-21 21:19:43	74	\N	\N	\N
340	2	14	2	2	9.5	6	5	6.83	Faible	2026-03-21 21:19:43	2026-03-21 21:19:43	75	\N	\N	\N
341	2	14	2	2	19.5	16	14	16.5	Très Bien	2026-03-21 21:19:43	2026-03-21 21:19:43	76	\N	\N	\N
342	2	15	2	2	15	15.5	13	14.5	 Bien	2026-03-21 21:20:01	2026-03-21 21:20:01	70	\N	\N	\N
343	2	15	2	2	14.5	13.5	14.5	14.17	 Bien	2026-03-21 21:20:01	2026-03-21 21:20:01	71	\N	\N	\N
344	2	15	2	2	14.5	18.5	19	17.33	Très Bien	2026-03-21 21:20:01	2026-03-21 21:20:01	72	\N	\N	\N
345	2	15	2	2	14.5	13	15	14.17	 Bien	2026-03-21 21:20:01	2026-03-21 21:20:01	73	\N	\N	\N
346	2	15	2	2	12.5	14.5	11	12.67	Assez Bien	2026-03-21 21:20:01	2026-03-21 21:20:01	74	\N	\N	\N
347	2	15	2	2	14.5	11	10	11.83	Passable	2026-03-21 21:20:01	2026-03-21 21:20:01	75	\N	\N	\N
348	2	15	2	2	17	16	18.5	17.17	Très Bien	2026-03-21 21:20:01	2026-03-21 21:20:01	76	\N	\N	\N
349	2	16	2	2	14.5	14	12	13.5	Assez Bien	2026-03-21 21:22:26	2026-03-21 21:22:26	70	\N	\N	\N
350	2	16	2	2	12	14	15	13.67	Assez Bien	2026-03-21 21:22:26	2026-03-21 21:22:26	71	\N	\N	\N
351	2	16	2	2	16.5	15	16	15.83	 Bien	2026-03-21 21:22:26	2026-03-21 21:22:26	72	\N	\N	\N
352	2	16	2	2	15.5	16	15	15.5	 Bien	2026-03-21 21:22:26	2026-03-21 21:22:26	73	\N	\N	\N
353	2	16	2	2	12.5	14	10	12.17	Assez Bien	2026-03-21 21:22:26	2026-03-21 21:22:26	74	\N	\N	\N
354	2	16	2	2	13	14	14	13.67	Assez Bien	2026-03-21 21:22:26	2026-03-21 21:22:26	75	\N	\N	\N
355	2	16	2	2	12.5	11	12	11.83	Passable	2026-03-21 21:22:26	2026-03-21 21:22:26	76	\N	\N	\N
356	3	17	2	2	20	20	20	20	Excellent	2026-03-21 21:23:32	2026-03-21 21:23:32	77	\N	\N	\N
357	3	17	2	2	17.5	8	8	11.17	Passable	2026-03-21 21:23:32	2026-03-21 21:23:32	78	\N	\N	\N
358	3	17	2	2	15	11.5	14	13.5	Assez Bien	2026-03-21 21:23:32	2026-03-21 21:23:32	79	\N	\N	\N
359	3	17	2	2	16.5	9	8	11.17	Passable	2026-03-21 21:23:32	2026-03-21 21:23:32	99	\N	\N	\N
360	3	17	2	2	18	15	18	17	Très Bien	2026-03-21 21:23:32	2026-03-21 21:23:32	80	\N	\N	\N
361	3	17	2	2	11	10	9	10	Passable	2026-03-21 21:23:32	2026-03-21 21:23:32	81	\N	\N	\N
362	3	17	2	2	15.5	10	9	11.5	Passable	2026-03-21 21:23:32	2026-03-21 21:23:32	82	\N	\N	\N
363	3	17	2	2	12	8	7	9	Insuffisant	2026-03-21 21:23:32	2026-03-21 21:23:32	83	\N	\N	\N
364	3	18	2	2	20	15	14	16.33	Très Bien	2026-03-21 21:24:19	2026-03-21 21:24:19	77	\N	\N	\N
365	3	18	2	2	16	8	10	11.33	Passable	2026-03-21 21:24:19	2026-03-21 21:24:19	78	\N	\N	\N
366	3	18	2	2	10.5	8	8	8.83	Insuffisant	2026-03-21 21:24:19	2026-03-21 21:24:19	79	\N	\N	\N
367	3	18	2	2	5.5	7	8	6.83	Faible	2026-03-21 21:24:19	2026-03-21 21:24:19	99	\N	\N	\N
368	3	18	2	2	14.5	10	11	11.83	Passable	2026-03-21 21:24:19	2026-03-21 21:24:19	80	\N	\N	\N
369	3	18	2	2	12.5	8	10	10.17	Passable	2026-03-21 21:24:19	2026-03-21 21:24:19	81	\N	\N	\N
370	3	18	2	2	8	7	7	7.33	Faible	2026-03-21 21:24:19	2026-03-21 21:24:19	82	\N	\N	\N
371	3	18	2	2	11.5	7	8	8.83	Insuffisant	2026-03-21 21:24:19	2026-03-21 21:24:19	83	\N	\N	\N
372	3	19	2	2	18.5	18	19	18.5	Excellent	2026-03-21 21:24:37	2026-03-21 21:24:37	77	\N	\N	\N
373	3	19	2	2	11	12	11	11.33	Passable	2026-03-21 21:24:37	2026-03-21 21:24:37	78	\N	\N	\N
374	3	19	2	2	10	10	11.5	10.5	Passable	2026-03-21 21:24:37	2026-03-21 21:24:37	79	\N	\N	\N
375	3	19	2	2	10.5	10	9	9.83	Insuffisant	2026-03-21 21:24:37	2026-03-21 21:24:37	99	\N	\N	\N
376	3	19	2	2	15.5	15	14.5	15	 Bien	2026-03-21 21:24:37	2026-03-21 21:24:37	80	\N	\N	\N
377	3	19	2	2	10.5	13.5	11	11.67	Passable	2026-03-21 21:24:37	2026-03-21 21:24:37	81	\N	\N	\N
378	3	19	2	2	11.5	10.5	11.25	11.08	Passable	2026-03-21 21:24:37	2026-03-21 21:24:37	82	\N	\N	\N
379	3	19	2	2	8	12.5	7.5	9.33	Insuffisant	2026-03-21 21:24:37	2026-03-21 21:24:37	83	\N	\N	\N
380	3	20	2	2	20	20	18	19.33	Excellent	2026-03-21 21:24:53	2026-03-21 21:24:53	77	\N	\N	\N
381	3	20	2	2	11	10	12	11	Passable	2026-03-21 21:24:53	2026-03-21 21:24:53	78	\N	\N	\N
382	3	20	2	2	8	7	13	9.33	Insuffisant	2026-03-21 21:24:53	2026-03-21 21:24:53	79	\N	\N	\N
383	3	20	2	2	11	10	9	10	Passable	2026-03-21 21:24:53	2026-03-21 21:24:53	99	\N	\N	\N
384	3	20	2	2	12.5	18	16	15.5	 Bien	2026-03-21 21:24:53	2026-03-21 21:24:53	80	\N	\N	\N
385	3	20	2	2	13	14	8	11.67	Passable	2026-03-21 21:24:53	2026-03-21 21:24:53	81	\N	\N	\N
386	3	20	2	2	9	6	11	8.67	Insuffisant	2026-03-21 21:24:53	2026-03-21 21:24:53	82	\N	\N	\N
387	3	20	2	2	9.5	6	6	7.17	Faible	2026-03-21 21:24:53	2026-03-21 21:24:53	83	\N	\N	\N
388	3	21	2	2	19.5	16.5	18.5	18.17	Excellent	2026-03-21 21:25:13	2026-03-21 21:25:13	77	\N	\N	\N
389	3	21	2	2	9	3	3.5	5.17	Très Faible	2026-03-21 21:25:13	2026-03-21 21:25:13	78	\N	\N	\N
390	3	21	2	2	9	8	6.5	7.83	Faible	2026-03-21 21:25:13	2026-03-21 21:25:13	79	\N	\N	\N
391	3	21	2	2	12	3	4.5	6.5	Faible	2026-03-21 21:25:13	2026-03-21 21:25:13	99	\N	\N	\N
392	3	21	2	2	18.5	10	13	13.83	Assez Bien	2026-03-21 21:25:13	2026-03-21 21:25:13	80	\N	\N	\N
393	3	21	2	2	8	5	6	6.33	Faible	2026-03-21 21:25:13	2026-03-21 21:25:13	81	\N	\N	\N
394	3	21	2	2	7.25	3.5	2	4.25	Très Faible	2026-03-21 21:25:13	2026-03-21 21:25:13	82	\N	\N	\N
395	3	21	2	2	12.25	6	6.5	8.25	Insuffisant	2026-03-21 21:25:13	2026-03-21 21:25:13	83	\N	\N	\N
396	3	22	2	2	17.5	17	15	16.5	Très Bien	2026-03-21 21:25:33	2026-03-21 21:25:33	77	\N	\N	\N
397	3	22	2	2	13.5	8	8	9.83	Insuffisant	2026-03-21 21:25:33	2026-03-21 21:25:33	78	\N	\N	\N
398	3	22	2	2	13.5	7	9	9.83	Insuffisant	2026-03-21 21:25:33	2026-03-21 21:25:33	79	\N	\N	\N
399	3	22	2	2	11	8	11	10	Passable	2026-03-21 21:25:33	2026-03-21 21:25:33	99	\N	\N	\N
400	3	22	2	2	14.75	13	10	12.58	Assez Bien	2026-03-21 21:25:33	2026-03-21 21:25:33	80	\N	\N	\N
401	3	22	2	2	10	6	7	7.67	Faible	2026-03-21 21:25:33	2026-03-21 21:25:33	81	\N	\N	\N
402	3	22	2	2	11.5	4	12	9.17	Insuffisant	2026-03-21 21:25:33	2026-03-21 21:25:33	82	\N	\N	\N
403	3	22	2	2	12.75	8	8	9.58	Insuffisant	2026-03-21 21:25:33	2026-03-21 21:25:33	83	\N	\N	\N
404	3	23	2	2	18	19	19.5	18.83	Excellent	2026-03-21 21:25:49	2026-03-21 21:25:49	77	\N	\N	\N
405	3	23	2	2	13	10.25	9	10.75	Passable	2026-03-21 21:25:49	2026-03-21 21:25:49	78	\N	\N	\N
406	3	23	2	2	12.5	7	11	10.17	Passable	2026-03-21 21:25:49	2026-03-21 21:25:49	79	\N	\N	\N
407	3	23	2	2	13	9.25	7	9.75	Insuffisant	2026-03-21 21:25:49	2026-03-21 21:25:49	99	\N	\N	\N
408	3	23	2	2	13.5	15	10	12.83	Assez Bien	2026-03-21 21:25:49	2026-03-21 21:25:49	80	\N	\N	\N
409	3	23	2	2	11.5	8	9	9.5	Insuffisant	2026-03-21 21:25:49	2026-03-21 21:25:49	81	\N	\N	\N
410	3	23	2	2	13	7	11	10.33	Passable	2026-03-21 21:25:49	2026-03-21 21:25:49	82	\N	\N	\N
411	3	23	2	2	12	8	14.25	11.42	Passable	2026-03-21 21:25:49	2026-03-21 21:25:49	83	\N	\N	\N
412	3	24	2	2	13	13	9	11.67	Passable	2026-03-21 21:26:12	2026-03-21 21:26:12	77	\N	\N	\N
413	3	24	2	2	12.5	15	13	13.5	Assez Bien	2026-03-21 21:26:12	2026-03-21 21:26:12	78	\N	\N	\N
414	3	24	2	2	10	13	15	12.67	Assez Bien	2026-03-21 21:26:12	2026-03-21 21:26:12	79	\N	\N	\N
415	3	24	2	2	14.5	15	17	15.5	 Bien	2026-03-21 21:26:12	2026-03-21 21:26:12	99	\N	\N	\N
416	3	24	2	2	15.5	14	15	14.83	 Bien	2026-03-21 21:26:12	2026-03-21 21:26:12	80	\N	\N	\N
417	3	24	2	2	12.5	15	7	11.5	Passable	2026-03-21 21:26:12	2026-03-21 21:26:12	81	\N	\N	\N
418	3	24	2	2	8.5	10	11	9.83	Insuffisant	2026-03-21 21:26:12	2026-03-21 21:26:12	82	\N	\N	\N
419	3	24	2	2	11	14	15	13.33	Assez Bien	2026-03-21 21:26:12	2026-03-21 21:26:12	83	\N	\N	\N
420	3	30	2	2	18	19	20	19	Excellent	2026-03-21 21:26:31	2026-03-21 21:26:31	77	\N	\N	\N
421	3	30	2	2	16.66	16	15	15.89	 Bien	2026-03-21 21:26:31	2026-03-21 21:26:31	78	\N	\N	\N
422	3	30	2	2	17.66	19	16	17.55	Très Bien	2026-03-21 21:26:31	2026-03-21 21:26:31	79	\N	\N	\N
423	3	30	2	2	17.66	19	12	16.22	Très Bien	2026-03-21 21:26:32	2026-03-21 21:26:32	99	\N	\N	\N
424	3	30	2	2	17.33	19	18	18.11	Excellent	2026-03-21 21:26:32	2026-03-21 21:26:32	80	\N	\N	\N
425	3	30	2	2	17	15	15	15.67	 Bien	2026-03-21 21:26:32	2026-03-21 21:26:32	81	\N	\N	\N
426	3	30	2	2	17	16	14	15.67	 Bien	2026-03-21 21:26:32	2026-03-21 21:26:32	82	\N	\N	\N
427	3	30	2	2	17.66	17	15	16.55	Très Bien	2026-03-21 21:26:32	2026-03-21 21:26:32	83	\N	\N	\N
428	4	25	2	2	15	17	17	16.33	Très Bien	2026-03-21 21:30:12	2026-03-21 21:30:12	84	\N	\N	\N
429	4	25	2	2	10	10	13	11	Passable	2026-03-21 21:30:12	2026-03-21 21:30:12	85	\N	\N	\N
430	4	25	2	2	15.75	13	11	13.25	Assez Bien	2026-03-21 21:30:12	2026-03-21 21:30:12	86	\N	\N	\N
431	4	25	2	2	10	4	9	7.67	Faible	2026-03-21 21:30:12	2026-03-21 21:30:12	87	\N	\N	\N
432	4	25	2	2	13	8.5	7	9.5	Insuffisant	2026-03-21 21:30:12	2026-03-21 21:30:12	88	\N	\N	\N
433	4	25	2	2	12.75	6.5	8	9.08	Insuffisant	2026-03-21 21:30:12	2026-03-21 21:30:12	89	\N	\N	\N
434	4	25	2	2	14	9	10	11	Passable	2026-03-21 21:30:12	2026-03-21 21:30:12	90	\N	\N	\N
435	4	26	2	2	12	11	10	11	Passable	2026-03-21 21:30:33	2026-03-21 21:30:33	84	\N	\N	\N
436	4	26	2	2	12	10	10	10.67	Passable	2026-03-21 21:30:33	2026-03-21 21:30:33	85	\N	\N	\N
437	4	26	2	2	12.5	8	10	10.17	Passable	2026-03-21 21:30:33	2026-03-21 21:30:33	86	\N	\N	\N
438	4	26	2	2	10.5	7	7	8.17	Insuffisant	2026-03-21 21:30:33	2026-03-21 21:30:33	87	\N	\N	\N
439	4	26	2	2	11.5	10	9	10.17	Passable	2026-03-21 21:30:33	2026-03-21 21:30:33	88	\N	\N	\N
440	4	26	2	2	12	9	8	9.67	Insuffisant	2026-03-21 21:30:33	2026-03-21 21:30:33	89	\N	\N	\N
441	4	26	2	2	12.5	10	10	10.83	Passable	2026-03-21 21:30:33	2026-03-21 21:30:33	90	\N	\N	\N
442	4	27	2	2	13.5	12	9	11.5	Passable	2026-03-21 21:30:49	2026-03-21 21:30:49	84	\N	\N	\N
443	4	27	2	2	14.5	11	10	11.83	Passable	2026-03-21 21:30:49	2026-03-21 21:30:49	85	\N	\N	\N
444	4	27	2	2	15.5	11	10	12.17	Assez Bien	2026-03-21 21:30:49	2026-03-21 21:30:49	86	\N	\N	\N
445	4	27	2	2	14	8	7	9.67	Insuffisant	2026-03-21 21:30:49	2026-03-21 21:30:49	87	\N	\N	\N
446	4	27	2	2	15	12	9	12	Assez Bien	2026-03-21 21:30:49	2026-03-21 21:30:49	88	\N	\N	\N
447	4	27	2	2	12.5	10	7	9.83	Insuffisant	2026-03-21 21:30:49	2026-03-21 21:30:49	89	\N	\N	\N
448	4	27	2	2	15.5	16	13	14.83	 Bien	2026-03-21 21:30:49	2026-03-21 21:30:49	90	\N	\N	\N
449	4	28	2	2	16	8.5	14	12.83	Assez Bien	2026-03-21 21:31:09	2026-03-21 21:31:09	84	\N	\N	\N
450	4	28	2	2	14	16.5	13.5	14.67	 Bien	2026-03-21 21:31:09	2026-03-21 21:31:09	85	\N	\N	\N
451	4	28	2	2	15.5	10	14	13.17	Assez Bien	2026-03-21 21:31:09	2026-03-21 21:31:09	86	\N	\N	\N
452	4	28	2	2	7	7.5	10.5	8.33	Insuffisant	2026-03-21 21:31:09	2026-03-21 21:31:09	87	\N	\N	\N
453	4	28	2	2	11	11	15.5	12.5	Assez Bien	2026-03-21 21:31:09	2026-03-21 21:31:09	88	\N	\N	\N
454	4	28	2	2	12.5	11	13	12.17	Assez Bien	2026-03-21 21:31:09	2026-03-21 21:31:09	89	\N	\N	\N
455	4	28	2	2	14.5	16.5	13	14.67	 Bien	2026-03-21 21:31:09	2026-03-21 21:31:09	90	\N	\N	\N
456	4	29	2	2	17.33	14	11	14.11	 Bien	2026-03-21 21:31:26	2026-03-21 21:31:26	84	\N	\N	\N
457	4	29	2	2	17.33	19	12	16.11	Très Bien	2026-03-21 21:31:26	2026-03-21 21:31:26	85	\N	\N	\N
458	4	29	2	2	17.33	18	13	16.11	Très Bien	2026-03-21 21:31:26	2026-03-21 21:31:26	86	\N	\N	\N
459	4	29	2	2	16.66	15	10	13.89	Assez Bien	2026-03-21 21:31:26	2026-03-21 21:31:26	87	\N	\N	\N
460	4	29	2	2	17.33	16	13	15.44	 Bien	2026-03-21 21:31:26	2026-03-21 21:31:26	88	\N	\N	\N
461	4	29	2	2	17	16	17	16.67	Très Bien	2026-03-21 21:31:26	2026-03-21 21:31:26	89	\N	\N	\N
462	4	29	2	2	18.33	19	10	15.78	 Bien	2026-03-21 21:31:26	2026-03-21 21:31:26	90	\N	\N	\N
463	4	31	2	2	10.25	7	10	9.08	Insuffisant	2026-03-21 21:31:44	2026-03-21 21:31:44	84	\N	\N	\N
464	4	31	2	2	18.5	12.5	11	14	 Bien	2026-03-21 21:31:44	2026-03-21 21:31:44	85	\N	\N	\N
465	4	31	2	2	18.25	8	9	11.75	Passable	2026-03-21 21:31:44	2026-03-21 21:31:44	86	\N	\N	\N
466	4	31	2	2	10.75	4	4	6.25	Faible	2026-03-21 21:31:44	2026-03-21 21:31:44	87	\N	\N	\N
467	4	31	2	2	12.5	12	6	10.17	Passable	2026-03-21 21:31:44	2026-03-21 21:31:44	88	\N	\N	\N
468	4	31	2	2	19	10	10	13	Assez Bien	2026-03-21 21:31:44	2026-03-21 21:31:44	89	\N	\N	\N
469	4	31	2	2	18.5	11.5	11	13.67	Assez Bien	2026-03-21 21:31:44	2026-03-21 21:31:44	90	\N	\N	\N
470	4	32	2	2	7.5	6	6	6.5	Faible	2026-03-21 21:32:03	2026-03-21 21:32:03	84	\N	\N	\N
471	4	32	2	2	8.5	13.5	11	11	Passable	2026-03-21 21:32:03	2026-03-21 21:32:03	85	\N	\N	\N
472	4	32	2	2	9.5	16	11	12.17	Assez Bien	2026-03-21 21:32:03	2026-03-21 21:32:03	86	\N	\N	\N
473	4	32	2	2	5.75	8	5	6.25	Faible	2026-03-21 21:32:04	2026-03-21 21:32:04	87	\N	\N	\N
474	4	32	2	2	8.5	17	7	10.83	Passable	2026-03-21 21:32:04	2026-03-21 21:32:04	88	\N	\N	\N
475	4	32	2	2	7.75	12	5	8.25	Insuffisant	2026-03-21 21:32:04	2026-03-21 21:32:04	89	\N	\N	\N
476	4	32	2	2	8.5	14.5	7	10	Passable	2026-03-21 21:32:04	2026-03-21 21:32:04	90	\N	\N	\N
477	4	33	2	2	15.16	10	17	14.05	 Bien	2026-03-21 21:32:27	2026-03-21 21:32:27	84	\N	\N	\N
478	4	33	2	2	16.16	5	16.75	12.64	Assez Bien	2026-03-21 21:32:27	2026-03-21 21:32:27	85	\N	\N	\N
479	4	33	2	2	17.33	8.5	15.25	13.69	Assez Bien	2026-03-21 21:32:27	2026-03-21 21:32:27	86	\N	\N	\N
480	4	33	2	2	9.83	5	3.25	6.03	Faible	2026-03-21 21:32:27	2026-03-21 21:32:27	87	\N	\N	\N
481	4	33	2	2	15.5	8.5	14.5	12.83	Assez Bien	2026-03-21 21:32:27	2026-03-21 21:32:27	88	\N	\N	\N
482	4	33	2	2	13.83	10	14	12.61	Assez Bien	2026-03-21 21:32:27	2026-03-21 21:32:27	89	\N	\N	\N
483	4	33	2	2	15.66	7.5	10	11.05	Passable	2026-03-21 21:32:27	2026-03-21 21:32:27	90	\N	\N	\N
484	4	34	2	2	15	14	13	14	 Bien	2026-03-21 21:32:44	2026-03-21 21:32:44	84	\N	\N	\N
485	4	34	2	2	14	12	17	14.33	 Bien	2026-03-21 21:32:44	2026-03-21 21:32:44	85	\N	\N	\N
486	4	34	2	2	13	12	12	12.33	Assez Bien	2026-03-21 21:32:44	2026-03-21 21:32:44	86	\N	\N	\N
487	4	34	2	2	12	13	8	11	Passable	2026-03-21 21:32:44	2026-03-21 21:32:44	87	\N	\N	\N
488	4	34	2	2	12	12	15	13	Assez Bien	2026-03-21 21:32:44	2026-03-21 21:32:44	88	\N	\N	\N
489	4	34	2	2	14	12	6	10.67	Passable	2026-03-21 21:32:44	2026-03-21 21:32:44	89	\N	\N	\N
490	4	34	2	2	15	14	13	14	 Bien	2026-03-21 21:32:44	2026-03-21 21:32:44	90	\N	\N	\N
491	9	35	2	2	14	10	8	10.67	Passable	2026-03-21 21:33:19	2026-03-21 21:33:19	91	\N	\N	\N
492	9	35	2	2	11.33	6	11	9.44	Insuffisant	2026-03-21 21:33:19	2026-03-21 21:33:19	92	\N	\N	\N
493	9	35	2	2	12.33	11	6	9.78	Insuffisant	2026-03-21 21:33:19	2026-03-21 21:33:19	93	\N	\N	\N
494	9	35	2	2	13.33	6	11	10.11	Passable	2026-03-21 21:33:19	2026-03-21 21:33:19	94	\N	\N	\N
495	9	35	2	2	13.33	5	11	9.78	Insuffisant	2026-03-21 21:33:19	2026-03-21 21:33:19	95	\N	\N	\N
496	9	36	2	2	14.5	14	9	12.5	Assez Bien	2026-03-21 21:33:46	2026-03-21 21:33:46	91	\N	\N	\N
497	9	36	2	2	9	10	11	10	Passable	2026-03-21 21:33:46	2026-03-21 21:33:46	92	\N	\N	\N
498	9	36	2	2	16.5	16	14	15.5	 Bien	2026-03-21 21:33:46	2026-03-21 21:33:46	93	\N	\N	\N
499	9	36	2	2	11	15	16	14	 Bien	2026-03-21 21:33:46	2026-03-21 21:33:46	94	\N	\N	\N
500	9	36	2	2	12.5	14	14	13.5	Assez Bien	2026-03-21 21:33:46	2026-03-21 21:33:46	95	\N	\N	\N
501	9	37	2	2	11	7	11	9.67	Insuffisant	2026-03-21 21:34:12	2026-03-21 21:34:12	91	\N	\N	\N
502	9	37	2	2	14.5	13	8	11.83	Passable	2026-03-21 21:34:12	2026-03-21 21:34:12	92	\N	\N	\N
503	9	37	2	2	13.5	10	10	11.17	Passable	2026-03-21 21:34:12	2026-03-21 21:34:12	93	\N	\N	\N
504	9	37	2	2	12.5	10	9	10.5	Passable	2026-03-21 21:34:12	2026-03-21 21:34:12	94	\N	\N	\N
505	9	37	2	2	14	12	10	12	Assez Bien	2026-03-21 21:34:12	2026-03-21 21:34:12	95	\N	\N	\N
506	9	38	2	2	12.5	9	11	10.83	Passable	2026-03-21 21:34:31	2026-03-21 21:34:31	91	\N	\N	\N
507	9	38	2	2	13	3	5	7	Faible	2026-03-21 21:34:31	2026-03-21 21:34:31	92	\N	\N	\N
508	9	38	2	2	12	11	14	12.33	Assez Bien	2026-03-21 21:34:31	2026-03-21 21:34:31	93	\N	\N	\N
509	9	38	2	2	14	7	10	10.33	Passable	2026-03-21 21:34:31	2026-03-21 21:34:31	94	\N	\N	\N
510	9	38	2	2	7	9	8	8	Insuffisant	2026-03-21 21:34:31	2026-03-21 21:34:31	95	\N	\N	\N
511	9	39	2	2	16.5	11	13	13.5	Assez Bien	2026-03-21 21:34:52	2026-03-21 21:34:52	91	\N	\N	\N
512	9	39	2	2	10	10.5	9.5	10	Passable	2026-03-21 21:34:52	2026-03-21 21:34:52	92	\N	\N	\N
513	9	39	2	2	8.75	6.5	11	8.75	Insuffisant	2026-03-21 21:34:52	2026-03-21 21:34:52	93	\N	\N	\N
514	9	39	2	2	15.5	10	10.5	12	Assez Bien	2026-03-21 21:34:52	2026-03-21 21:34:52	94	\N	\N	\N
515	9	39	2	2	12.5	7.5	12.5	10.83	Passable	2026-03-21 21:34:52	2026-03-21 21:34:52	95	\N	\N	\N
516	9	40	2	2	13.5	12	13	12.83	Assez Bien	2026-03-21 21:35:17	2026-03-21 21:35:17	91	\N	\N	\N
517	9	40	2	2	\N	3	5	4	Très Faible	2026-03-21 21:35:17	2026-03-21 21:35:17	92	\N	\N	\N
518	9	40	2	2	10	7	10	9	Insuffisant	2026-03-21 21:35:17	2026-03-21 21:35:17	93	\N	\N	\N
519	9	40	2	2	15	7	13	11.67	Passable	2026-03-21 21:35:17	2026-03-21 21:35:17	94	\N	\N	\N
520	9	40	2	2	11	4	9	8	Insuffisant	2026-03-21 21:35:17	2026-03-21 21:35:17	95	\N	\N	\N
521	9	41	2	2	15	10	11	12	Assez Bien	2026-03-21 21:35:36	2026-03-21 21:35:36	91	\N	\N	\N
522	9	41	2	2	6.5	9	7	7.5	Faible	2026-03-21 21:35:36	2026-03-21 21:35:36	92	\N	\N	\N
523	9	41	2	2	13.5	9	9	10.5	Passable	2026-03-21 21:35:36	2026-03-21 21:35:36	93	\N	\N	\N
524	9	41	2	2	14	16	10	13.33	Assez Bien	2026-03-21 21:35:36	2026-03-21 21:35:36	94	\N	\N	\N
525	9	41	2	2	14	9	11	11.33	Passable	2026-03-21 21:35:36	2026-03-21 21:35:36	95	\N	\N	\N
526	9	42	2	2	12	14	9	11.67	Passable	2026-03-21 21:35:57	2026-03-21 21:35:57	91	\N	\N	\N
527	9	42	2	2	16	15	14	15	 Bien	2026-03-21 21:35:57	2026-03-21 21:35:57	92	\N	\N	\N
528	9	42	2	2	14	14	10	12.67	Assez Bien	2026-03-21 21:35:57	2026-03-21 21:35:57	93	\N	\N	\N
529	9	42	2	2	13	14	16	14.33	 Bien	2026-03-21 21:35:57	2026-03-21 21:35:57	94	\N	\N	\N
530	9	42	2	2	11	11	12	11.33	Passable	2026-03-21 21:35:57	2026-03-21 21:35:57	95	\N	\N	\N
538	11	51	2	2	11.66	9	10	10.22	Passable	2026-03-22 20:54:02	2026-03-22 20:54:02	97	\N	\N	\N
539	11	52	2	2	18	15	16	16.33	Très Bien	2026-03-22 20:54:19	2026-03-22 20:54:19	97	\N	\N	\N
540	11	53	2	2	14	13	7	11.33	Passable	2026-03-22 20:54:37	2026-03-22 20:54:37	97	\N	\N	\N
541	11	54	2	2	16	8	9	11	Passable	2026-03-22 20:54:59	2026-03-22 20:54:59	97	\N	\N	\N
542	11	55	2	2	15.5	11	8	11.5	Passable	2026-03-22 20:56:04	2026-03-22 20:56:04	97	\N	\N	\N
543	11	56	2	2	15	10	11	12	Assez Bien	2026-03-22 20:56:34	2026-03-22 20:56:34	97	\N	\N	\N
544	11	57	2	2	14	12	18	14.67	 Bien	2026-03-22 20:58:12	2026-03-22 20:58:12	97	\N	\N	\N
545	11	58	2	2	12	13	11	12	Assez Bien	2026-03-22 20:58:32	2026-03-22 20:58:32	97	\N	\N	\N
667	10	43	1	2	16	13	8	12.33	Assez bien	2026-05-08 22:05:20	2026-05-08 22:05:20	107	\N	\N	\N
668	10	44	1	2	12.33	18	17	15.78	Bien	2026-05-08 22:05:50	2026-05-08 22:05:50	107	\N	\N	\N
669	10	46	1	2	10	16	7	11	Passable	2026-05-08 22:06:26	2026-05-08 22:06:26	107	\N	\N	\N
670	10	45	1	2	15.5	9	7	10.5	Passable	2026-05-08 22:06:51	2026-05-08 22:06:51	107	\N	\N	\N
671	10	47	1	2	7	4	4	5	Très Faible	2026-05-08 22:07:31	2026-05-08 22:07:31	107	\N	\N	\N
672	10	48	1	2	10.25	6	7	7.75	Faible	2026-05-08 22:07:53	2026-05-08 22:07:53	107	\N	\N	\N
673	10	49	1	2	15.5	7	10	10.83	Passable	2026-05-08 22:08:15	2026-05-08 22:08:15	107	\N	\N	\N
674	10	50	1	2	\N	\N	\N	\N	\N	2026-05-08 22:08:52	2026-05-08 22:08:52	107	\N	\N	\N
675	1	1	3	2	7	13	20	13.33	Assez bien	2026-05-22 13:22:38	2026-05-22 13:22:38	56	\N	\N	\N
676	1	1	3	2	18	14	15	15.67	Bien	2026-05-22 13:22:38	2026-05-22 13:22:38	57	\N	\N	\N
677	1	1	3	2	8	14	19	13.67	Assez bien	2026-05-22 13:22:38	2026-05-22 13:22:38	58	\N	\N	\N
678	1	1	3	2	20	14	16	16.67	Très bien	2026-05-22 13:22:39	2026-05-22 13:22:39	59	\N	\N	\N
679	1	2	3	2	11.5	7	7	8.5	Insuffisant	2026-05-22 13:29:26	2026-05-22 13:29:26	56	\N	\N	\N
680	1	2	3	2	18.5	13	10	13.83	Assez bien	2026-05-22 13:29:26	2026-05-22 13:29:26	57	\N	\N	\N
681	1	2	3	2	12.75	6	7	8.58	Insuffisant	2026-05-22 13:29:26	2026-05-22 13:29:26	58	\N	\N	\N
682	1	2	3	2	15	11	10	12	Assez bien	2026-05-22 13:29:26	2026-05-22 13:29:26	59	\N	\N	\N
683	1	3	3	2	16.5	5.5	4.5	8.83	Insuffisant	2026-05-22 13:30:56	2026-05-22 13:30:56	56	\N	\N	\N
684	1	3	3	2	16.5	15	13.5	15	Bien	2026-05-22 13:30:56	2026-05-22 13:30:56	57	\N	\N	\N
685	1	3	3	2	13.5	1	8	7.5	Faible	2026-05-22 13:30:56	2026-05-22 13:30:56	58	\N	\N	\N
686	1	3	3	2	19	16.5	15.5	17	Très bien	2026-05-22 13:30:56	2026-05-22 13:30:56	59	\N	\N	\N
687	1	4	3	2	12.5	12	9	11.17	Passable	2026-05-22 13:32:15	2026-05-22 13:32:15	56	\N	\N	\N
688	1	4	3	2	19.5	17	11	15.83	Bien	2026-05-22 13:32:15	2026-05-22 13:32:15	57	\N	\N	\N
689	1	4	3	2	14.5	12	4	10.17	Passable	2026-05-22 13:32:15	2026-05-22 13:32:15	58	\N	\N	\N
690	1	4	3	2	19.5	18	18	18.5	Excellent	2026-05-22 13:32:15	2026-05-22 13:32:15	59	\N	\N	\N
691	1	5	3	2	13.5	6	10	9.83	Insuffisant	2026-05-22 13:32:46	2026-05-22 13:32:46	56	\N	\N	\N
692	1	5	3	2	18	15	18	17	Très bien	2026-05-22 13:32:46	2026-05-22 13:32:46	57	\N	\N	\N
693	1	5	3	2	13	12	9	11.33	Passable	2026-05-22 13:32:46	2026-05-22 13:32:46	58	\N	\N	\N
694	1	5	3	2	20	17.5	19	18.83	Excellent	2026-05-22 13:32:46	2026-05-22 13:32:46	59	\N	\N	\N
695	1	6	3	2	14.33	10	10	11.44	Passable	2026-05-22 13:33:15	2026-05-22 13:33:15	56	\N	\N	\N
696	1	6	3	2	16	14.5	15	15.17	Bien	2026-05-22 13:33:15	2026-05-22 13:33:15	57	\N	\N	\N
697	1	6	3	2	10.66	8.5	12	10.39	Passable	2026-05-22 13:33:15	2026-05-22 13:33:15	58	\N	\N	\N
698	1	6	3	2	17	12	18	15.67	Bien	2026-05-22 13:33:15	2026-05-22 13:33:15	59	\N	\N	\N
699	1	7	3	2	13	9.5	10	10.83	Passable	2026-05-22 13:39:04	2026-05-22 13:39:04	56	\N	\N	\N
700	1	7	3	2	17	17.5	11	15.17	Bien	2026-05-22 13:39:04	2026-05-22 13:39:04	57	\N	\N	\N
701	1	7	3	2	11	11	11	11	Passable	2026-05-22 13:39:04	2026-05-22 13:39:04	58	\N	\N	\N
702	1	7	3	2	17	16	14	15.67	Bien	2026-05-22 13:39:04	2026-05-22 13:39:04	59	\N	\N	\N
703	1	8	3	2	13	15	15	14.33	Bien	2026-05-22 13:39:52	2026-05-22 13:39:52	56	\N	\N	\N
704	1	8	3	2	14	13	16	14.33	Bien	2026-05-22 13:39:52	2026-05-22 13:39:52	57	\N	\N	\N
659	3	17	3	2	20	20	20	20	Excellent	2026-04-25 00:06:28	2026-05-27 14:10:10	77	\N	\N	\N
660	3	17	3	2	15	16	13	14.67	Bien	2026-04-25 00:06:28	2026-05-27 14:10:10	78	\N	\N	\N
661	3	17	3	2	16.5	16	13	15.17	Bien	2026-04-25 00:06:28	2026-05-27 14:10:10	79	\N	\N	\N
662	3	17	3	2	13	13	14	13.33	Assez bien	2026-04-25 00:06:28	2026-05-27 14:10:10	99	\N	\N	\N
663	3	17	3	2	16.5	15	14	15.17	Bien	2026-04-25 00:06:28	2026-05-27 14:10:10	80	\N	\N	\N
664	3	17	3	2	14	10	11	11.67	Passable	2026-04-25 00:06:28	2026-05-27 14:10:10	81	\N	\N	\N
665	3	17	3	2	15	13	8.25	12.08	Assez bien	2026-04-25 00:06:28	2026-05-27 14:10:10	82	\N	\N	\N
666	3	17	3	2	12	13	11	12	Assez bien	2026-04-25 00:06:28	2026-05-27 14:10:10	83	\N	\N	\N
705	1	8	3	2	11.5	15	15	13.83	Assez bien	2026-05-22 13:39:52	2026-05-22 13:39:52	58	\N	\N	\N
706	1	8	3	2	13.5	16	14	14.5	Bien	2026-05-22 13:39:52	2026-05-22 13:39:52	59	\N	\N	\N
707	2	9	3	2	16	16	13	15	Bien	2026-05-22 13:47:33	2026-05-22 13:47:33	70	\N	\N	\N
708	2	9	3	2	11	16	15	14	Bien	2026-05-22 13:47:33	2026-05-22 13:47:33	71	\N	\N	\N
709	2	9	3	2	17.5	18.5	16	17.33	Très bien	2026-05-22 13:47:33	2026-05-22 13:47:33	72	\N	\N	\N
710	2	9	3	2	17.5	18.5	18	18	Excellent	2026-05-22 13:47:33	2026-05-22 13:47:33	73	\N	\N	\N
711	2	9	3	2	14.5	14	13	13.83	Assez bien	2026-05-22 13:47:33	2026-05-22 13:47:33	74	\N	\N	\N
712	2	9	3	2	12.5	17.5	16	15.33	Bien	2026-05-22 13:47:33	2026-05-22 13:47:33	75	\N	\N	\N
713	2	9	3	2	17.5	17	14	16.17	Très bien	2026-05-22 13:47:33	2026-05-22 13:47:33	76	\N	\N	\N
714	2	10	3	2	10.5	11	10	10.5	Passable	2026-05-22 13:57:04	2026-05-22 13:57:04	70	\N	\N	\N
715	2	10	3	2	8.5	8	8	8.17	Insuffisant	2026-05-22 13:57:04	2026-05-22 13:57:04	71	\N	\N	\N
716	2	10	3	2	17.25	11	14	14.08	Bien	2026-05-22 13:57:04	2026-05-22 13:57:04	72	\N	\N	\N
717	2	10	3	2	3.75	10	10	7.92	Faible	2026-05-22 13:57:04	2026-05-22 13:57:04	73	\N	\N	\N
718	2	10	3	2	8.5	7	7	7.5	Faible	2026-05-22 13:57:04	2026-05-22 13:57:04	74	\N	\N	\N
719	2	10	3	2	11	6	8	8.33	Insuffisant	2026-05-22 13:57:04	2026-05-22 13:57:04	75	\N	\N	\N
720	2	10	3	2	15.25	11	13	13.08	Assez bien	2026-05-22 13:57:04	2026-05-22 13:57:04	76	\N	\N	\N
721	2	11	3	2	16.5	14.25	16	15.58	Bien	2026-05-22 13:58:11	2026-05-22 13:58:11	70	\N	\N	\N
722	2	11	3	2	15.5	6.25	10.5	10.75	Passable	2026-05-22 13:58:11	2026-05-22 13:58:11	71	\N	\N	\N
723	2	11	3	2	19	16	17.5	17.5	Très bien	2026-05-22 13:58:11	2026-05-22 13:58:11	72	\N	\N	\N
724	2	11	3	2	13	8	15.5	12.17	Assez bien	2026-05-22 13:58:11	2026-05-22 13:58:11	73	\N	\N	\N
725	2	11	3	2	11	9.25	10.5	10.25	Passable	2026-05-22 13:58:11	2026-05-22 13:58:11	74	\N	\N	\N
726	2	11	3	2	15.5	8.5	12.5	12.17	Assez bien	2026-05-22 13:58:11	2026-05-22 13:58:11	75	\N	\N	\N
727	2	11	3	2	19	14	18.75	17.25	Très bien	2026-05-22 13:58:11	2026-05-22 13:58:11	76	\N	\N	\N
728	2	12	3	2	16.5	18	15	16.5	Très bien	2026-05-22 14:01:45	2026-05-22 14:01:45	70	\N	\N	\N
729	2	12	3	2	12	13	7	10.67	Passable	2026-05-22 14:01:45	2026-05-22 14:01:45	71	\N	\N	\N
730	2	12	3	2	17.5	18	18	17.83	Très bien	2026-05-22 14:01:45	2026-05-22 14:01:45	72	\N	\N	\N
731	2	12	3	2	17.5	16	13	15.5	Bien	2026-05-22 14:01:45	2026-05-22 14:01:45	73	\N	\N	\N
732	2	12	3	2	7.5	10	10	9.17	Insuffisant	2026-05-22 14:01:45	2026-05-22 14:01:45	74	\N	\N	\N
733	2	12	3	2	8	7	12	9	Insuffisant	2026-05-22 14:01:45	2026-05-22 14:01:45	75	\N	\N	\N
734	2	12	3	2	12.5	19	15	15.5	Bien	2026-05-22 14:01:45	2026-05-22 14:01:45	76	\N	\N	\N
735	2	13	3	2	11	15	9	11.67	Passable	2026-05-22 14:02:10	2026-05-22 14:02:10	70	\N	\N	\N
736	2	13	3	2	13	9	4	8.67	Insuffisant	2026-05-22 14:02:10	2026-05-22 14:02:10	71	\N	\N	\N
737	2	13	3	2	17	14	17	16	Très bien	2026-05-22 14:02:10	2026-05-22 14:02:10	72	\N	\N	\N
738	2	13	3	2	14	12.5	9	11.83	Passable	2026-05-22 14:02:10	2026-05-22 14:02:10	73	\N	\N	\N
739	2	13	3	2	12.5	11.5	8	10.67	Passable	2026-05-22 14:02:10	2026-05-22 14:02:10	74	\N	\N	\N
740	2	13	3	2	18	10	10	12.67	Assez bien	2026-05-22 14:02:10	2026-05-22 14:02:10	75	\N	\N	\N
741	2	13	3	2	19	17.5	15	17.17	Très bien	2026-05-22 14:02:10	2026-05-22 14:02:10	76	\N	\N	\N
742	2	14	3	2	16	8	10.5	11.5	Passable	2026-05-22 14:02:29	2026-05-22 14:02:29	70	\N	\N	\N
743	2	14	3	2	12	12	12.5	12.17	Assez bien	2026-05-22 14:02:29	2026-05-22 14:02:29	71	\N	\N	\N
744	2	14	3	2	18.5	18	15.5	17.33	Très bien	2026-05-22 14:02:29	2026-05-22 14:02:29	72	\N	\N	\N
745	2	14	3	2	17	8	12	12.33	Assez bien	2026-05-22 14:02:29	2026-05-22 14:02:29	73	\N	\N	\N
746	2	14	3	2	13	10	16.5	13.17	Assez bien	2026-05-22 14:02:29	2026-05-22 14:02:29	74	\N	\N	\N
747	2	14	3	2	13	12	9	11.33	Passable	2026-05-22 14:02:29	2026-05-22 14:02:29	75	\N	\N	\N
748	2	14	3	2	18.5	17	19.5	18.33	Excellent	2026-05-22 14:02:29	2026-05-22 14:02:29	76	\N	\N	\N
749	2	15	3	2	15.25	8	11	11.42	Passable	2026-05-22 14:02:44	2026-05-22 14:02:44	70	\N	\N	\N
750	2	15	3	2	12	10	7	9.67	Insuffisant	2026-05-22 14:02:44	2026-05-22 14:02:44	71	\N	\N	\N
751	2	15	3	2	15	20	19	18	Excellent	2026-05-22 14:02:44	2026-05-22 14:02:44	72	\N	\N	\N
752	2	15	3	2	11.25	15	15	13.75	Assez bien	2026-05-22 14:02:44	2026-05-22 14:02:44	73	\N	\N	\N
753	2	15	3	2	9.25	7	9	8.42	Insuffisant	2026-05-22 14:02:44	2026-05-22 14:02:44	74	\N	\N	\N
754	2	15	3	2	10	8	8	8.67	Insuffisant	2026-05-22 14:02:44	2026-05-22 14:02:44	75	\N	\N	\N
755	2	15	3	2	12	15	15	14	Bien	2026-05-22 14:02:44	2026-05-22 14:02:44	76	\N	\N	\N
756	2	16	3	2	13	14	16	14.33	Bien	2026-05-22 14:02:56	2026-05-22 14:02:56	70	\N	\N	\N
757	2	16	3	2	15.5	15	17	15.83	Bien	2026-05-22 14:02:56	2026-05-22 14:02:56	71	\N	\N	\N
758	2	16	3	2	15	15	16	15.33	Bien	2026-05-22 14:02:56	2026-05-22 14:02:56	72	\N	\N	\N
759	2	16	3	2	15.5	16	17	16.17	Très bien	2026-05-22 14:02:56	2026-05-22 14:02:56	73	\N	\N	\N
760	2	16	3	2	14.5	15	15	14.83	Bien	2026-05-22 14:02:56	2026-05-22 14:02:56	74	\N	\N	\N
761	2	16	3	2	13	14	15	14	Bien	2026-05-22 14:02:56	2026-05-22 14:02:56	75	\N	\N	\N
762	2	16	3	2	15	13	16	14.67	Bien	2026-05-22 14:02:56	2026-05-22 14:02:56	76	\N	\N	\N
763	3	18	3	2	19	13	13	15	Bien	2026-05-22 14:13:19	2026-05-22 14:13:19	77	\N	\N	\N
764	3	18	3	2	10.5	7	6	7.83	Faible	2026-05-22 14:13:19	2026-05-22 14:13:19	78	\N	\N	\N
765	3	18	3	2	10.5	10	8	9.5	Insuffisant	2026-05-22 14:13:19	2026-05-22 14:13:19	79	\N	\N	\N
766	3	18	3	2	12	8	5	8.33	Insuffisant	2026-05-22 14:13:19	2026-05-22 14:13:19	99	\N	\N	\N
767	3	18	3	2	14.5	11	10	11.83	Passable	2026-05-22 14:13:19	2026-05-22 14:13:19	80	\N	\N	\N
768	3	18	3	2	11	8	8	9	Insuffisant	2026-05-22 14:13:19	2026-05-22 14:13:19	81	\N	\N	\N
769	3	18	3	2	12.5	7	8	9.17	Insuffisant	2026-05-22 14:13:19	2026-05-22 14:13:19	82	\N	\N	\N
770	3	18	3	2	12	7	11	10	Passable	2026-05-22 14:13:19	2026-05-22 14:13:19	83	\N	\N	\N
771	3	19	3	2	17.5	14.25	18.5	16.75	Très bien	2026-05-22 14:13:36	2026-05-22 14:13:36	77	\N	\N	\N
772	3	19	3	2	10.5	5.5	7.5	7.83	Faible	2026-05-22 14:13:36	2026-05-22 14:13:36	78	\N	\N	\N
773	3	19	3	2	10	10	11	10.33	Passable	2026-05-22 14:13:36	2026-05-22 14:13:36	79	\N	\N	\N
774	3	19	3	2	8	5	6.25	6.42	Faible	2026-05-22 14:13:36	2026-05-22 14:13:36	99	\N	\N	\N
775	3	19	3	2	18.5	13.5	13.25	15.08	Bien	2026-05-22 14:13:36	2026-05-22 14:13:36	80	\N	\N	\N
776	3	19	3	2	5.5	8	5	6.17	Faible	2026-05-22 14:13:36	2026-05-22 14:13:36	81	\N	\N	\N
777	3	19	3	2	10	7.25	10	9.08	Insuffisant	2026-05-22 14:13:36	2026-05-22 14:13:36	82	\N	\N	\N
778	3	19	3	2	16	8	9	11	Passable	2026-05-22 14:13:36	2026-05-22 14:13:36	83	\N	\N	\N
779	3	20	3	2	20	18	18	18.67	Excellent	2026-05-22 14:13:46	2026-05-22 14:13:46	77	\N	\N	\N
780	3	20	3	2	11.5	8	10	9.83	Insuffisant	2026-05-22 14:13:46	2026-05-22 14:13:46	78	\N	\N	\N
781	3	20	3	2	7	6	4	5.67	Très Faible	2026-05-22 14:13:46	2026-05-22 14:13:46	79	\N	\N	\N
782	3	20	3	2	12.5	7	6	8.5	Insuffisant	2026-05-22 14:13:46	2026-05-22 14:13:46	99	\N	\N	\N
783	3	20	3	2	12	12	17	13.67	Assez bien	2026-05-22 14:13:46	2026-05-22 14:13:46	80	\N	\N	\N
784	3	20	3	2	12	6	10	9.33	Insuffisant	2026-05-22 14:13:46	2026-05-22 14:13:46	81	\N	\N	\N
785	3	20	3	2	11	9	13	11	Passable	2026-05-22 14:13:46	2026-05-22 14:13:46	82	\N	\N	\N
786	3	20	3	2	6.5	6	7	6.5	Faible	2026-05-22 14:13:46	2026-05-22 14:13:46	83	\N	\N	\N
787	3	21	3	2	20	20	19	19.67	Excellent	2026-05-22 14:13:55	2026-05-22 14:13:55	77	\N	\N	\N
788	3	21	3	2	9	7	5	7	Faible	2026-05-22 14:13:55	2026-05-22 14:13:55	78	\N	\N	\N
789	3	21	3	2	14.5	12	11	12.5	Assez bien	2026-05-22 14:13:56	2026-05-22 14:13:56	79	\N	\N	\N
790	3	21	3	2	12.5	8	3	7.83	Faible	2026-05-22 14:13:56	2026-05-22 14:13:56	99	\N	\N	\N
791	3	21	3	2	10.5	12	10	10.83	Passable	2026-05-22 14:13:56	2026-05-22 14:13:56	80	\N	\N	\N
792	3	21	3	2	9	4	3	5.33	Très Faible	2026-05-22 14:13:56	2026-05-22 14:13:56	81	\N	\N	\N
793	3	21	3	2	9.5	10	7	8.83	Insuffisant	2026-05-22 14:13:56	2026-05-22 14:13:56	82	\N	\N	\N
794	3	21	3	2	11	14	10	11.67	Passable	2026-05-22 14:13:56	2026-05-22 14:13:56	83	\N	\N	\N
795	3	22	3	2	19	19	20	19.33	Excellent	2026-05-22 14:14:05	2026-05-22 14:14:05	77	\N	\N	\N
796	3	22	3	2	15	11	9	11.67	Passable	2026-05-22 14:14:05	2026-05-22 14:14:05	78	\N	\N	\N
797	3	22	3	2	14	14	9	12.33	Assez bien	2026-05-22 14:14:05	2026-05-22 14:14:05	79	\N	\N	\N
798	3	22	3	2	18	14	10	14	Bien	2026-05-22 14:14:05	2026-05-22 14:14:05	99	\N	\N	\N
800	3	22	3	2	17	7	6	10	Passable	2026-05-22 14:14:05	2026-05-22 14:14:05	81	\N	\N	\N
801	3	22	3	2	12	8	7	9	Insuffisant	2026-05-22 14:14:05	2026-05-22 14:14:05	82	\N	\N	\N
802	3	22	3	2	15	10	9	11.33	Passable	2026-05-22 14:14:05	2026-05-22 14:14:05	83	\N	\N	\N
803	3	23	3	2	19	17	15	17	Très bien	2026-05-22 14:14:15	2026-05-22 14:14:15	77	\N	\N	\N
804	3	23	3	2	14	5	8	9	Insuffisant	2026-05-22 14:14:15	2026-05-22 14:14:15	78	\N	\N	\N
805	3	23	3	2	13	8	11	10.67	Passable	2026-05-22 14:14:15	2026-05-22 14:14:15	79	\N	\N	\N
806	3	23	3	2	11	7	8	8.67	Insuffisant	2026-05-22 14:14:15	2026-05-22 14:14:15	99	\N	\N	\N
807	3	23	3	2	15	10	17	14	Bien	2026-05-22 14:14:15	2026-05-22 14:14:15	80	\N	\N	\N
811	3	24	3	2	13	15	15	14.33	Bien	2026-05-22 14:14:29	2026-05-22 14:14:29	77	\N	\N	\N
812	3	24	3	2	15.5	15	17	15.83	Bien	2026-05-22 14:14:29	2026-05-22 14:14:29	78	\N	\N	\N
813	3	24	3	2	16	15	15	15.33	Bien	2026-05-22 14:14:29	2026-05-22 14:14:29	79	\N	\N	\N
814	3	24	3	2	14	16	17	15.67	Bien	2026-05-22 14:14:29	2026-05-22 14:14:29	99	\N	\N	\N
815	3	24	3	2	15.5	15	14	14.83	Bien	2026-05-22 14:14:29	2026-05-22 14:14:29	80	\N	\N	\N
816	3	24	3	2	11.5	13	10	11.5	Passable	2026-05-22 14:14:29	2026-05-22 14:14:29	81	\N	\N	\N
809	3	23	3	2	14	5	6	8.33	Insuffisant	2026-05-22 14:14:15	2026-05-22 21:29:42	82	\N	\N	\N
810	3	23	3	2	12.5	8	7	9.17	Insuffisant	2026-05-22 14:14:15	2026-05-22 21:29:42	83	\N	\N	\N
817	3	24	3	2	14.5	15	16	15.17	Bien	2026-05-22 14:14:29	2026-05-22 14:14:29	82	\N	\N	\N
818	3	24	3	2	12.5	14	16	14.17	Bien	2026-05-22 14:14:29	2026-05-22 14:14:29	83	\N	\N	\N
819	3	30	3	2	16.66	20	18	18.22	Excellent	2026-05-22 14:14:38	2026-05-22 14:14:38	77	\N	\N	\N
820	3	30	3	2	12.33	7	14	11.11	Passable	2026-05-22 14:14:38	2026-05-22 14:14:38	78	\N	\N	\N
821	3	30	3	2	11	8	10	9.67	Insuffisant	2026-05-22 14:14:38	2026-05-22 14:14:38	79	\N	\N	\N
822	3	30	3	2	13	10	10	11	Passable	2026-05-22 14:14:38	2026-05-22 14:14:38	99	\N	\N	\N
823	3	30	3	2	14.33	15	13	14.11	Bien	2026-05-22 14:14:38	2026-05-22 14:14:38	80	\N	\N	\N
825	3	30	3	2	11.66	10	8	9.89	Insuffisant	2026-05-22 14:14:38	2026-05-22 14:14:38	82	\N	\N	\N
826	3	30	3	2	15.33	14	8	12.44	Assez bien	2026-05-22 14:14:38	2026-05-22 14:14:38	83	\N	\N	\N
827	4	25	3	2	15.5	18	14	15.83	Bien	2026-05-22 14:15:13	2026-05-22 14:15:13	84	\N	\N	\N
828	4	25	3	2	13.5	9	10	10.83	Passable	2026-05-22 14:15:13	2026-05-22 14:15:13	85	\N	\N	\N
829	4	25	3	2	14	9	17	13.33	Assez bien	2026-05-22 14:15:13	2026-05-22 14:15:13	86	\N	\N	\N
830	4	25	3	2	13	6	9	9.33	Insuffisant	2026-05-22 14:15:13	2026-05-22 14:15:13	87	\N	\N	\N
831	4	25	3	2	13	15	13	13.67	Assez bien	2026-05-22 14:15:13	2026-05-22 14:15:13	88	\N	\N	\N
832	4	25	3	2	14.5	15	13	14.17	Bien	2026-05-22 14:15:13	2026-05-22 14:15:13	89	\N	\N	\N
833	4	25	3	2	16.5	11	14	13.83	Assez bien	2026-05-22 14:15:13	2026-05-22 14:15:13	90	\N	\N	\N
834	4	26	3	2	11.5	13	11	11.83	Passable	2026-05-22 14:15:21	2026-05-22 14:15:21	84	\N	\N	\N
835	4	26	3	2	12	13	11	12	Assez bien	2026-05-22 14:15:21	2026-05-22 14:15:21	85	\N	\N	\N
836	4	26	3	2	10.5	13	10	11.17	Passable	2026-05-22 14:15:22	2026-05-22 14:15:22	86	\N	\N	\N
837	4	26	3	2	10	7	8	8.33	Insuffisant	2026-05-22 14:15:22	2026-05-22 14:15:22	87	\N	\N	\N
838	4	26	3	2	13.5	13	10	12.17	Assez bien	2026-05-22 14:15:22	2026-05-22 14:15:22	88	\N	\N	\N
839	4	26	3	2	12.5	10	6	9.5	Insuffisant	2026-05-22 14:15:22	2026-05-22 14:15:22	89	\N	\N	\N
840	4	26	3	2	12	13	10	11.67	Passable	2026-05-22 14:15:22	2026-05-22 14:15:22	90	\N	\N	\N
841	4	27	3	2	14.5	15	9	12.83	Assez bien	2026-05-22 14:15:30	2026-05-22 14:15:30	84	\N	\N	\N
842	4	27	3	2	13	15	12	13.33	Assez bien	2026-05-22 14:15:30	2026-05-22 14:15:30	85	\N	\N	\N
843	4	27	3	2	13	13	14	13.33	Assez bien	2026-05-22 14:15:30	2026-05-22 14:15:30	86	\N	\N	\N
844	4	27	3	2	14	9	10	11	Passable	2026-05-22 14:15:30	2026-05-22 14:15:30	87	\N	\N	\N
845	4	27	3	2	14.5	13	13	13.5	Assez bien	2026-05-22 14:15:30	2026-05-22 14:15:30	88	\N	\N	\N
846	4	27	3	2	14.5	13	11	12.83	Assez bien	2026-05-22 14:15:30	2026-05-22 14:15:30	89	\N	\N	\N
847	4	27	3	2	14.5	13	13	13.5	Assez bien	2026-05-22 14:15:30	2026-05-22 14:15:30	90	\N	\N	\N
848	4	29	3	2	12.66	10	11	11.22	Passable	2026-05-22 14:15:47	2026-05-22 14:15:47	84	\N	\N	\N
849	4	29	3	2	16.33	10	17	14.44	Bien	2026-05-22 14:15:47	2026-05-22 14:15:47	85	\N	\N	\N
850	4	29	3	2	13.66	10	14	12.55	Assez bien	2026-05-22 14:15:47	2026-05-22 14:15:47	86	\N	\N	\N
851	4	29	3	2	13.66	14	14	13.89	Assez bien	2026-05-22 14:15:47	2026-05-22 14:15:47	87	\N	\N	\N
852	4	29	3	2	16	15	16	15.67	Bien	2026-05-22 14:15:47	2026-05-22 14:15:47	88	\N	\N	\N
853	4	29	3	2	15.33	10	14	13.11	Assez bien	2026-05-22 14:15:47	2026-05-22 14:15:47	89	\N	\N	\N
854	4	29	3	2	16.33	15	17	16.11	Très bien	2026-05-22 14:15:47	2026-05-22 14:15:47	90	\N	\N	\N
855	4	31	3	2	17.5	4	9	10.17	Passable	2026-05-22 14:15:57	2026-05-22 14:15:57	84	\N	\N	\N
856	4	31	3	2	20	9	9	12.67	Assez bien	2026-05-22 14:15:57	2026-05-22 14:15:57	85	\N	\N	\N
857	4	31	3	2	17.5	9.5	11	12.67	Assez bien	2026-05-22 14:15:57	2026-05-22 14:15:57	86	\N	\N	\N
858	4	31	3	2	17.5	4	7	9.5	Insuffisant	2026-05-22 14:15:57	2026-05-22 14:15:57	87	\N	\N	\N
859	4	31	3	2	16.25	7	10	11.08	Passable	2026-05-22 14:15:57	2026-05-22 14:15:57	88	\N	\N	\N
860	4	31	3	2	12.25	6	4	7.42	Faible	2026-05-22 14:15:57	2026-05-22 14:15:57	89	\N	\N	\N
861	4	31	3	2	17.5	11	11	13.17	Assez bien	2026-05-22 14:15:57	2026-05-22 14:15:57	90	\N	\N	\N
862	4	32	3	2	10	8	9	9	Insuffisant	2026-05-22 14:16:06	2026-05-22 14:16:06	84	\N	\N	\N
863	4	32	3	2	13.5	10	12	11.83	Passable	2026-05-22 14:16:06	2026-05-22 14:16:06	85	\N	\N	\N
864	4	32	3	2	13	11	11.5	11.83	Passable	2026-05-22 14:16:06	2026-05-22 14:16:06	86	\N	\N	\N
865	4	32	3	2	11.5	6.5	6	8	Insuffisant	2026-05-22 14:16:06	2026-05-22 14:16:06	87	\N	\N	\N
866	4	32	3	2	10.75	10.5	11	10.75	Passable	2026-05-22 14:16:06	2026-05-22 14:16:06	88	\N	\N	\N
867	4	32	3	2	11.5	10	8	9.83	Insuffisant	2026-05-22 14:16:06	2026-05-22 14:16:06	89	\N	\N	\N
868	4	32	3	2	13	10.5	12	11.83	Passable	2026-05-22 14:16:06	2026-05-22 14:16:06	90	\N	\N	\N
869	4	33	3	2	10	11	6.5	9.17	Insuffisant	2026-05-22 14:16:17	2026-05-22 14:16:17	84	\N	\N	\N
870	4	33	3	2	12.33	6.5	7	8.61	Insuffisant	2026-05-22 14:16:17	2026-05-22 14:16:17	85	\N	\N	\N
871	4	33	3	2	13	9.25	8.5	10.25	Passable	2026-05-22 14:16:17	2026-05-22 14:16:17	86	\N	\N	\N
872	4	33	3	2	3.83	3.5	6	4.44	Très Faible	2026-05-22 14:16:17	2026-05-22 14:16:17	87	\N	\N	\N
873	4	33	3	2	11.66	16	10	12.55	Assez bien	2026-05-22 14:16:17	2026-05-22 14:16:17	88	\N	\N	\N
874	4	33	3	2	11.66	11	4.5	9.05	Insuffisant	2026-05-22 14:16:17	2026-05-22 14:16:17	89	\N	\N	\N
875	4	33	3	2	11.5	10.5	9.5	10.5	Passable	2026-05-22 14:16:17	2026-05-22 14:16:17	90	\N	\N	\N
876	4	34	3	2	10	15	12	12.33	Assez bien	2026-05-22 14:16:28	2026-05-22 14:16:28	84	\N	\N	\N
877	4	34	3	2	10	15	12	12.33	Assez bien	2026-05-22 14:16:28	2026-05-22 14:16:28	85	\N	\N	\N
878	4	34	3	2	10	14	12	12	Assez bien	2026-05-22 14:16:28	2026-05-22 14:16:28	86	\N	\N	\N
879	4	34	3	2	10	13	12	11.67	Passable	2026-05-22 14:16:28	2026-05-22 14:16:28	87	\N	\N	\N
880	4	34	3	2	10	12	12	11.33	Passable	2026-05-22 14:16:28	2026-05-22 14:16:28	88	\N	\N	\N
881	4	34	3	2	10	12	12	11.33	Passable	2026-05-22 14:16:28	2026-05-22 14:16:28	89	\N	\N	\N
882	4	34	3	2	10	13	12	11.67	Passable	2026-05-22 14:16:28	2026-05-22 14:16:28	90	\N	\N	\N
883	9	35	3	2	14.5	10	10	11.5	Passable	2026-05-22 14:16:53	2026-05-22 14:16:53	91	\N	\N	\N
884	9	35	3	2	13	8	10	10.33	Passable	2026-05-22 14:16:53	2026-05-22 14:16:53	92	\N	\N	\N
885	9	35	3	2	15.5	10	10	11.83	Passable	2026-05-22 14:16:53	2026-05-22 14:16:53	93	\N	\N	\N
886	9	35	3	2	12.5	9	15	12.17	Assez bien	2026-05-22 14:16:53	2026-05-22 14:16:53	94	\N	\N	\N
887	9	35	3	2	14.5	10	7	10.5	Passable	2026-05-22 14:16:53	2026-05-22 14:16:53	95	\N	\N	\N
888	9	36	3	2	17	15	14	15.33	Bien	2026-05-22 14:17:02	2026-05-22 14:17:02	91	\N	\N	\N
889	9	36	3	2	10.5	10	12	10.83	Passable	2026-05-22 14:17:02	2026-05-22 14:17:02	92	\N	\N	\N
890	9	36	3	2	15.5	16	17	16.17	Très bien	2026-05-22 14:17:02	2026-05-22 14:17:02	93	\N	\N	\N
891	9	36	3	2	19	16	15	16.67	Très bien	2026-05-22 14:17:02	2026-05-22 14:17:02	94	\N	\N	\N
892	9	36	3	2	15	15	15	15	Bien	2026-05-22 14:17:02	2026-05-22 14:17:02	95	\N	\N	\N
893	9	37	3	2	14.5	6	11	10.5	Passable	2026-05-22 14:17:12	2026-05-22 14:17:12	91	\N	\N	\N
894	9	37	3	2	15.5	11	12	12.83	Assez bien	2026-05-22 14:17:12	2026-05-22 14:17:12	92	\N	\N	\N
895	9	37	3	2	15	10	11	12	Assez bien	2026-05-22 14:17:12	2026-05-22 14:17:12	93	\N	\N	\N
896	9	37	3	2	15	9	11	11.67	Passable	2026-05-22 14:17:12	2026-05-22 14:17:12	94	\N	\N	\N
897	9	37	3	2	15	8	11	11.33	Passable	2026-05-22 14:17:12	2026-05-22 14:17:12	95	\N	\N	\N
898	9	38	3	2	13	6	13	10.67	Passable	2026-05-22 14:17:21	2026-05-22 14:17:21	91	\N	\N	\N
899	9	38	3	2	14	6	10	10	Passable	2026-05-22 14:17:21	2026-05-22 14:17:21	92	\N	\N	\N
900	9	38	3	2	12.5	12	14	12.83	Assez bien	2026-05-22 14:17:21	2026-05-22 14:17:21	93	\N	\N	\N
901	9	38	3	2	14	11	14	13	Assez bien	2026-05-22 14:17:21	2026-05-22 14:17:21	94	\N	\N	\N
902	9	38	3	2	12	12	10	11.33	Passable	2026-05-22 14:17:21	2026-05-22 14:17:21	95	\N	\N	\N
903	9	39	3	2	15.5	12	13	13.5	Assez bien	2026-05-22 14:17:31	2026-05-22 14:17:31	91	\N	\N	\N
904	9	39	3	2	11.5	4.5	8.5	8.17	Insuffisant	2026-05-22 14:17:31	2026-05-22 14:17:31	92	\N	\N	\N
905	9	39	3	2	14	14	13	13.67	Assez bien	2026-05-22 14:17:31	2026-05-22 14:17:31	93	\N	\N	\N
906	9	39	3	2	16	5	12.5	11.17	Passable	2026-05-22 14:17:31	2026-05-22 14:17:31	94	\N	\N	\N
907	9	39	3	2	11.5	13	11	11.83	Passable	2026-05-22 14:17:31	2026-05-22 14:17:31	95	\N	\N	\N
908	9	40	3	2	12	9	12	11	Passable	2026-05-22 14:17:43	2026-05-22 14:17:43	91	\N	\N	\N
909	9	40	3	2	8	4	5	5.67	Très Faible	2026-05-22 14:17:43	2026-05-22 14:17:43	92	\N	\N	\N
910	9	40	3	2	11	9	11	10.33	Passable	2026-05-22 14:17:43	2026-05-22 14:17:43	93	\N	\N	\N
912	9	40	3	2	8	7	10	8.33	Insuffisant	2026-05-22 14:17:43	2026-05-22 14:17:43	95	\N	\N	\N
913	9	41	3	2	15	9	15	13	Assez bien	2026-05-22 14:17:52	2026-05-22 14:17:52	91	\N	\N	\N
914	9	41	3	2	11	5	6	7.33	Faible	2026-05-22 14:17:52	2026-05-22 14:17:52	92	\N	\N	\N
915	9	41	3	2	13.66	4	12	9.89	Insuffisant	2026-05-22 14:17:52	2026-05-22 14:17:52	93	\N	\N	\N
916	9	41	3	2	17.33	15.5	16	16.28	Très bien	2026-05-22 14:17:52	2026-05-22 14:17:52	94	\N	\N	\N
917	9	41	3	2	13.33	7	9	9.78	Insuffisant	2026-05-22 14:17:52	2026-05-22 14:17:52	95	\N	\N	\N
918	9	42	3	2	12	13	14	13	Assez bien	2026-05-22 14:18:01	2026-05-22 14:18:01	91	\N	\N	\N
919	9	42	3	2	13	12	14	13	Assez bien	2026-05-22 14:18:01	2026-05-22 14:18:01	92	\N	\N	\N
920	9	42	3	2	11	12	14	12.33	Assez bien	2026-05-22 14:18:01	2026-05-22 14:18:01	93	\N	\N	\N
921	9	42	3	2	12	16	14	14	Bien	2026-05-22 14:18:01	2026-05-22 14:18:01	94	\N	\N	\N
922	9	42	3	2	12	11	14	12.33	Assez bien	2026-05-22 14:18:01	2026-05-22 14:18:01	95	\N	\N	\N
923	10	43	3	2	15	10	12	12.33	Assez bien	2026-05-22 17:46:46	2026-05-22 17:46:46	107	\N	\N	\N
924	10	44	3	2	18	17	19	18	Excellent	2026-05-22 17:47:24	2026-05-22 17:47:24	107	\N	\N	\N
925	10	46	3	2	16.5	15	13	14.83	Bien	2026-05-22 17:47:36	2026-05-22 17:47:36	107	\N	\N	\N
926	10	45	3	2	15.5	8	13	12.17	Assez bien	2026-05-22 17:48:44	2026-05-22 17:48:44	107	\N	\N	\N
927	10	47	3	2	7.5	6	7	6.83	Faible	2026-05-22 17:48:54	2026-05-22 17:48:54	107	\N	\N	\N
928	10	48	3	2	11	8	9	9.33	Insuffisant	2026-05-22 17:49:05	2026-05-22 17:49:05	107	\N	\N	\N
929	10	49	3	2	15	13	14	14	Bien	2026-05-22 17:49:19	2026-05-22 17:49:19	107	\N	\N	\N
930	10	50	3	2	\N	\N	\N	\N	\N	2026-05-22 17:49:28	2026-05-22 17:49:28	107	\N	\N	\N
931	11	51	3	2	14.5	14	10	12.83	Assez bien	2026-05-22 17:50:17	2026-05-22 17:50:17	97	\N	\N	\N
932	11	52	3	2	16	18	19	17.67	Très bien	2026-05-22 17:50:25	2026-05-22 17:50:25	97	\N	\N	\N
933	11	53	3	2	15.5	10	12	12.5	Assez bien	2026-05-22 17:50:36	2026-05-22 17:50:36	97	\N	\N	\N
934	11	54	3	2	17	13	17	15.67	Bien	2026-05-22 17:50:49	2026-05-22 17:50:49	97	\N	\N	\N
935	11	55	3	2	15.5	15	8	12.83	Assez bien	2026-05-22 17:51:00	2026-05-22 17:51:00	97	\N	\N	\N
936	11	56	3	2	13	9	12	11.33	Passable	2026-05-22 17:51:11	2026-05-22 17:51:11	97	\N	\N	\N
937	11	57	3	2	16	14	15	15	Bien	2026-05-22 17:51:21	2026-05-22 17:51:21	97	\N	\N	\N
938	11	58	3	2	8	16	17	13.67	Assez bien	2026-05-22 17:51:30	2026-05-22 17:51:30	97	\N	\N	\N
939	4	28	3	2	17.5	5	10.5	11	Passable	2026-05-22 20:35:37	2026-05-22 20:35:37	84	\N	\N	\N
940	4	28	3	2	15	11.5	11	12.5	Assez bien	2026-05-22 20:35:37	2026-05-22 20:35:37	85	\N	\N	\N
941	4	28	3	2	17.5	10.5	13	13.67	Assez bien	2026-05-22 20:35:37	2026-05-22 20:35:37	86	\N	\N	\N
942	4	28	3	2	12.5	4.5	10	9	Insuffisant	2026-05-22 20:35:37	2026-05-22 20:35:37	87	\N	\N	\N
943	4	28	3	2	17	8	12	12.33	Assez bien	2026-05-22 20:35:37	2026-05-22 20:35:37	88	\N	\N	\N
944	4	28	3	2	16	5.5	4.5	8.67	Insuffisant	2026-05-22 20:35:37	2026-05-22 20:35:37	89	\N	\N	\N
945	4	28	3	2	18	8	9	11.67	Passable	2026-05-22 20:35:37	2026-05-22 20:35:37	90	\N	\N	\N
824	3	30	3	2	11.33	11	8	10.11	Passable	2026-05-22 14:14:38	2026-05-22 21:25:46	81	\N	\N	\N
808	3	23	3	2	11	6	6	7.67	Faible	2026-05-22 14:14:15	2026-05-22 21:29:42	81	\N	\N	\N
946	10	43	2	2	12	9	11	10.67	Passable	2026-05-22 21:46:08	2026-05-22 21:46:08	107	\N	\N	\N
947	10	44	2	2	16	19	17	17.33	Très bien	2026-05-22 21:46:17	2026-05-22 21:46:17	107	\N	\N	\N
948	10	46	2	2	14.5	11	14	13.17	Assez bien	2026-05-22 21:46:27	2026-05-22 21:46:27	107	\N	\N	\N
949	10	45	2	2	15	15	8	12.67	Assez bien	2026-05-22 21:46:41	2026-05-22 21:46:41	107	\N	\N	\N
950	10	47	2	2	10.25	7	12	9.75	Insuffisant	2026-05-22 21:46:51	2026-05-22 21:46:51	107	\N	\N	\N
951	10	48	2	2	10	8	10	9.33	Insuffisant	2026-05-22 21:47:00	2026-05-22 21:47:00	107	\N	\N	\N
952	10	49	2	2	13	13	16	14	Bien	2026-05-22 21:47:10	2026-05-22 21:47:10	107	\N	\N	\N
958	3	17	1	2	9.5	11.5	8.25	9.75	Insuffisant	2026-05-28 01:38:05	2026-06-27 13:33:45	82	\N	\N	\N
959	3	17	1	2	8	\N	8	8	Insuffisant	2026-05-28 01:38:05	2026-06-27 13:33:45	83	\N	\N	\N
953	3	17	1	2	20	20	18	19.33	Excellent	2026-05-28 01:38:05	2026-06-27 13:33:45	77	\N	\N	\N
954	3	17	1	2	6	10	8.5	8.17	Insuffisant	2026-05-28 01:38:05	2026-06-27 13:33:45	78	\N	\N	\N
\.


--
-- Data for Name: notification_parents; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.notification_parents (id, paren_id, titre, contenu, lu, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.notifications (id, type, notifiable_type, notifiable_id, data, read_at, created_at, updated_at) FROM stdin;
6a550236-3715-4ca9-b6b2-71c8a2403dfd	App\\Notifications\\NouveauMessageContact	App\\Models\\User	37	{"message":"\\ud83d\\udce9 Nouveau message de contact","nom":"ADEYEMI Jean","email":"jean@gmail.com","id":6}	\N	2026-05-13 00:23:38	2026-05-13 00:23:38
7f963ff0-9f8d-4876-8bce-85dcbf3629ea	App\\Notifications\\NouveauMessageContact	App\\Models\\User	73	{"message":"\\ud83d\\udce9 Nouveau message de contact","nom":"ADEYEMI Jean","email":"jean@gmail.com","id":6}	\N	2026-05-13 00:23:38	2026-05-13 00:23:38
0943a218-7770-475a-a782-4e00c5522254	App\\Notifications\\NouveauMessageContact	App\\Models\\User	59	{"message":"\\ud83d\\udce9 Nouveau message de contact","nom":"ADEYEMI Jean","email":"jean@gmail.com","id":6}	\N	2026-05-13 00:23:38	2026-05-13 00:23:38
c1449777-1ed7-436e-9406-4c4229ba4b2b	App\\Notifications\\NouveauMessageContact	App\\Models\\User	74	{"message":"\\ud83d\\udce9 Nouveau message de contact","nom":"ADEYEMI Jean","email":"jean@gmail.com","id":6}	\N	2026-05-13 00:23:38	2026-05-13 00:23:38
\.


--
-- Data for Name: oloyes; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.oloyes (id, date, libelle, categorie, montant, beneficiaire, observation, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: operations; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.operations (id, date, libelle, montant, description, categorie, created_at, updated_at) FROM stdin;
1	2024-09-30	remboursement	50000.00	Remboursement des frais de confection des tables du menuisier HOUNKPONOU	dépense	2025-10-01 21:14:57	2025-10-01 21:14:57
2	2024-10-01	achat	20500.00	Achat et transport d'une porte pour le bureau	dépense	2025-10-01 21:17:27	2025-10-01 21:17:27
4	2024-10-10	réparation	2000.00	Installation de l'imprimante sur le PC	dépense	2025-10-01 21:20:50	2025-10-01 21:20:50
5	2024-10-19	dons	20000.00	Don pour Justin ESPOIR2000	dépense	2025-10-01 21:23:00	2025-10-01 21:23:00
7	2024-10-28	autres dépenses	1000.00	photocopie et Impression de devoir du primaire	dépense	2025-10-01 22:49:58	2025-10-01 22:49:58
8	2024-10-28	YESSOUFOU A. Affissou	2000.00	Somme reçue par M. YESSOUFOU A. Affissou pour ses propres besoins	dépense	2025-10-01 22:52:46	2025-10-01 22:52:46
9	2024-10-01	YESSOUFOU A. Affissou	20000.00	Somme reçue par M. YESSOUFOU A. Affissou pour ses propres besoins	dépense	2025-10-01 22:53:56	2025-10-01 22:53:56
10	2024-10-01	YESSOUFOU A. Affissou	14950.00	Somme reçue par M. YESSOUFOU A. Affissou pour ses propres besoins	dépense	2025-10-01 22:54:52	2025-10-01 22:54:52
11	2024-10-28	forfait	1000.00	Forfait Internet	dépense	2025-10-01 22:56:59	2025-10-01 22:56:59
12	2024-10-29	autres dépenses	1800.00	Photocopie pour devoir primaire	dépense	2025-10-01 22:59:17	2025-10-01 22:59:17
13	2024-10-29	autres dépenses	1000.00	Photocopie et impression pour devoir TleAB	dépense	2025-10-01 23:00:37	2025-10-01 23:00:37
14	2024-11-02	autres dépenses	11000.00	Confection des carnets de correspondance	dépense	2025-10-02 21:13:29	2025-10-02 21:13:29
15	2024-11-04	autres dépenses	2000.00	Déplacement successco	dépense	2025-10-02 21:14:54	2025-10-02 21:14:54
16	2024-11-07	forfait	1000.00	Forfait Internet	dépense	2025-10-02 21:16:22	2025-10-02 21:16:22
17	2024-11-07	autres dépenses	15000.00	Frais UP et rafrichaissement	dépense	2025-10-02 21:20:54	2025-10-02 21:20:54
18	2024-11-29	remboursement	20000.00	Remboursement FACAF	dépense	2025-10-02 21:23:29	2025-10-02 21:23:29
19	2024-12-02	remboursement	20000.00	Remboursement des frais de tableaux à Papa Caleb	dépense	2025-10-02 21:28:14	2025-10-02 21:28:14
21	2024-12-19	électricité	1000.00	Frais d'électricité de Claudio	dépense	2025-10-03 21:37:59	2025-10-03 21:37:59
22	2024-12-23	YESSOUFOU A. Affissou	20000.00	Somme reçue par M. YESSOUFOU A. Affissou pour ses propres bésoins	dépense	2025-10-03 21:40:41	2025-10-03 21:40:41
23	2024-12-24	YESSOUFOU A. Affissou	27000.00	Somme reçue par M. YESSOUFOU A. Affissou pour ses propres bésoins	dépense	2025-10-03 21:45:52	2025-10-03 21:45:52
24	2025-02-04	YESSOUFOU A. Affissou	20000.00	Somme reçue par M. YESSOUFOU A. Affissou pour payer une partie de la contribution de son fils WADOUD	dépense	2025-10-03 21:49:19	2025-10-03 21:49:19
25	2025-02-04	autres dépenses	150.00	Photocopie CP	dépense	2025-10-03 21:53:43	2025-10-03 21:53:43
26	2025-02-06	autres dépenses	10000.00	Les frais d'achat des craies de couleur et autres	dépense	2025-10-03 21:57:18	2025-10-03 21:57:18
27	2025-02-10	autres dépenses	10000.00	Casiers judiciaires AYENA, ANGLO ET KLOTOE	dépense	2025-10-03 22:01:34	2025-10-03 22:01:34
28	2025-02-10	autres dépenses	500.00	Photocopie	dépense	2025-10-03 22:02:48	2025-10-03 22:02:48
29	2025-02-11	YESSOUFOU A. Affissou	5000.00	Somme reçue par M. YESSOUFOU A. Affissou pour ses propres bésoins	dépense	2025-10-03 22:05:07	2025-10-03 22:05:07
30	2025-02-13	dons	20000.00	Dons à M. Justin pour le dossier de création-extension seconde CD	dépense	2025-10-03 22:08:05	2025-10-03 22:08:05
31	2025-02-13	autres dépenses	1500.00	Photocopie des épreuves du primaire	dépense	2025-10-03 22:10:16	2025-10-03 22:10:16
32	2025-02-18	forfait	1000.00	Forfait Internet	dépense	2025-10-03 22:12:28	2025-10-03 22:12:28
33	2025-02-18	autres dépenses	300.00	Photocopie	dépense	2025-10-03 22:13:32	2025-10-03 22:13:32
34	2025-02-20	dons	20000.00	Don à la commission de visite et de contrôle de site  de la DDESTFP	dépense	2025-10-03 22:17:10	2025-10-03 22:17:10
35	2025-02-21	autres dépenses	3650.00	Photocopie	dépense	2025-10-03 23:05:22	2025-10-03 23:05:22
36	2025-02-21	électricité	1725.00	Les frais d'électricité de payé à Claudio	dépense	2025-10-03 23:07:41	2025-10-03 23:07:41
37	2024-08-29	scolarités	35000.00	1ère tranche des scolarités de HONVO Marina L. D.	recette	2025-10-03 23:13:47	2025-10-03 23:13:47
38	2024-09-06	scolarités	143000.00	les scolarités	recette	2025-10-09 20:47:02	2025-10-09 20:47:02
39	2024-09-09	scolarités	40000.00	scolarités	recette	2025-10-09 20:49:04	2025-10-09 20:49:04
40	2024-09-10	scolarités	159000.00	scolarités	recette	2025-10-09 20:50:05	2025-10-09 20:50:05
41	2024-09-13	scolarités	20000.00	scolarités	recette	2025-10-09 20:58:08	2025-10-09 20:58:08
42	2024-09-16	scolarités	75000.00	scolarités	recette	2025-10-09 20:59:38	2025-10-09 20:59:38
43	2024-09-18	scolarités	30000.00	scolarités	recette	2025-10-09 21:00:27	2025-10-09 21:00:27
44	2025-08-22	salaires	357500.00	Salaire du maître TIDJANI Koudous durant l'année scolaire 2024-2025	dépense	2025-10-22 20:58:22	2025-10-22 20:58:22
45	2025-08-22	salaires	225000.00	Salaire du maître BOGNINOU J. Aurel durant l'année scolaire 2024-2025	dépense	2025-10-22 21:01:41	2025-10-22 21:01:41
46	2025-08-22	salaires	290000.00	Salaire de la maîtresse ADEFOULOU Justine  durant l'année scolaire 2024-2025	dépense	2025-10-22 21:04:03	2025-10-22 21:04:03
47	2025-08-22	salaires	48000.00	Salaire du professeur d'EPS GUEDOU CLAUDE pour l'année scolaire 2024-2025	dépense	2025-10-22 21:08:25	2025-10-22 21:08:25
48	2025-08-22	salaires	157500.00	Salaire du professeur de SVT SOGAN EMILE pour l'année scolaire 2024-2025	dépense	2025-10-22 21:09:57	2025-10-22 21:09:57
49	2025-08-22	salaires	71500.00	Salaire du professeur de SVT HOUNKPEVI JEAN pour l'année scolaire 2024-2025	dépense	2025-10-22 21:12:15	2025-10-22 21:12:15
50	2025-08-22	salaires	109000.00	Salaire du professeur d'HISTOIRE GEOGRAPHIE KOTIN JACQUES pour l'année scolaire 2024-2025	dépense	2025-10-22 21:16:32	2025-10-22 21:16:32
51	2025-08-22	salaires	100000.00	Salaire du professeur de FRANÇAIS SOSSOU GILLES pour l'année scolaire 2024-2025	dépense	2025-10-22 21:18:40	2025-10-22 21:18:40
52	2025-08-22	salaires	6000.00	RONFORCEMENT DE M. KPACHA SYLVAIN POUR BEPC 2025	dépense	2025-10-22 21:20:49	2025-10-22 21:20:49
53	2025-08-22	salaires	77000.00	Salaire de la professeure de SVT SAGBOHAN SANDRINE pour l'année scolaire 2024-2025	dépense	2025-10-22 21:22:20	2025-10-22 21:22:20
54	2025-08-22	salaires	75000.00	Salaire du professeur de PHILOSOPHIE AYENA PHILIPPE pour l'année scolaire 2024-2025	dépense	2025-10-22 21:23:40	2025-10-22 21:23:40
55	2025-08-22	salaires	67000.00	Salaire du professeur d'ESPAGNOL GOUDJO JOÊL pour l'année scolaire 2024-2025	dépense	2025-10-22 21:25:03	2025-10-22 21:25:03
56	2025-08-22	salaires	115000.00	Salaire du professeur de HISTOIRE GEOGRAPHIE ANGLO FRANCK pour l'année scolaire 2024-2025	dépense	2025-10-22 21:27:36	2025-10-22 21:27:36
57	2025-08-22	salaires	56000.00	Salaire du professeur d'ESPAGNOL AMAVEDA JUANITO pour l'année scolaire 2024-2025	dépense	2025-10-22 21:29:08	2025-10-22 21:29:08
58	2025-08-22	salaires	110000.00	Salaire du professeur de FRANÇAIS AGBOBATINKPO MICHEL pour l'année scolaire 2024-2025	dépense	2025-10-22 21:31:06	2025-10-22 21:31:06
59	2025-08-22	salaires	188000.00	Salaire du professeur d'ANGLAIS HODONOU-KIKI SALOMON pour l'année scolaire 2024-2025	dépense	2025-10-22 21:34:13	2025-10-22 21:34:13
60	2025-08-22	salaires	41000.00	Salaire du professeur de FRANÇAIS SALAKO PROSPER pour l'année scolaire 2024-2025	dépense	2025-10-22 21:36:42	2025-10-22 21:36:42
3	2024-10-09	achat	20500.00	Achat et transport d'une porte pour la toilette	dépense	2025-10-01 21:19:19	2025-10-25 23:44:28
61	2025-08-22	salaires	39000.00	Salaire du professeur de HISTOIRE GEOGRAPHIE FATONDJI ANGE pour l'année scolaire 2024-2025	dépense	2025-10-22 21:38:23	2025-10-22 21:38:23
62	2025-08-22	salaires	106000.00	Salaire du professeur d'ANGLAIS CODJA CHRISTIAN pour l'année scolaire 2024-2025	dépense	2025-10-22 21:39:39	2025-10-22 21:39:39
63	2025-08-22	salaires	34000.00	Salaire du professeur de SVT GBESSEMEHLAN ARNAUD pour l'année scolaire 2024-2025	dépense	2025-10-22 21:41:05	2025-10-22 21:41:05
64	2025-08-22	salaires	78000.00	Salaire du professeur de PCT GBAMIGBOLA FIRMIN pour l'année scolaire 2024-2025	dépense	2025-10-22 21:42:50	2025-10-22 21:42:50
65	2025-08-22	salaires	32000.00	Salaire du professeur de EPS KOSSOLOU HONORE pour l'année scolaire 2024-2025	dépense	2025-10-22 21:43:55	2025-10-22 21:43:55
66	2025-08-22	salaires	62000.00	Salaire du professeur de PCT YESSOUFOU FARID pour l'année scolaire 2024-2025	dépense	2025-10-22 21:45:14	2025-10-22 21:45:14
67	2024-09-20	scolarités	100000.00	Scolarités TleAB	recette	2025-10-22 22:06:28	2025-10-22 22:06:28
68	2024-09-23	scolarités	18000.00	scolarités TleAB	recette	2025-10-22 22:07:29	2025-10-22 22:07:29
69	2024-09-24	scolarités	45000.00	scolarités	recette	2025-10-22 22:10:01	2025-10-22 22:10:01
70	2024-09-30	scolarités	90000.00	scolarités	recette	2025-10-22 22:10:37	2025-10-22 22:10:37
71	2024-10-03	scolarités	112000.00	scolarités	recette	2025-10-22 22:11:33	2025-10-22 22:11:33
72	2024-10-11	scolarités	11500.00	scolarités	recette	2025-10-22 22:12:14	2025-10-22 22:12:14
73	2024-10-24	scolarités	10000.00	scolarités	recette	2025-10-22 22:14:17	2025-10-22 22:14:17
74	2024-10-25	scolarités	135000.00	scolarités	recette	2025-10-22 22:14:59	2025-10-22 22:14:59
75	2024-11-04	scolarités	120000.00	scolarités	recette	2025-10-22 22:16:28	2025-10-22 22:16:28
76	2024-11-11	scolarités	69000.00	scolarités	recette	2025-10-22 22:17:09	2025-10-22 22:17:09
77	2024-11-25	scolarités	60000.00	scolarités	recette	2025-10-22 22:18:18	2025-10-22 22:18:18
78	2024-11-28	scolarités	130000.00	scolarités	recette	2025-10-22 22:19:10	2025-10-22 22:19:10
79	2024-12-06	scolarités	102000.00	scolarités	recette	2025-10-22 22:19:47	2025-10-22 22:19:47
80	2024-12-11	scolarités	20000.00	scolarités	recette	2025-10-22 22:20:26	2025-10-22 22:20:26
81	2024-12-16	scolarités	151500.00	scolarités	recette	2025-10-22 22:27:53	2025-10-22 22:27:53
82	2025-02-03	scolarités	303500.00	scolarités	recette	2025-10-22 22:32:55	2025-10-22 22:32:55
83	2025-02-06	scolarités	50000.00	scolarités	recette	2025-10-22 22:33:56	2025-10-22 22:33:56
84	2025-02-10	scolarités	95000.00	scolarités	recette	2025-10-22 22:34:43	2025-10-22 22:34:43
85	2025-02-18	scolarités	69700.00	scolarités	recette	2025-10-22 22:35:36	2025-10-22 22:35:36
86	2025-03-10	scolarités	125000.00	scolarités	recette	2025-10-22 22:37:38	2025-10-22 22:37:38
87	2025-03-13	scolarités	42000.00	scolarités	recette	2025-10-22 22:38:16	2025-10-22 22:38:16
88	2025-03-28	scolarités	160000.00	scolarités	recette	2025-10-22 22:39:09	2025-10-22 22:39:09
89	2025-04-14	scolarités	110000.00	scolarités	recette	2025-10-22 22:41:34	2025-10-22 22:41:34
90	2025-04-29	scolarités	376000.00	scolarités	recette	2025-10-22 22:42:34	2025-10-22 22:42:34
92	2025-08-22	salaires	37000.00	Salaire du professeur d'économie HOUEHANOU JANVIER DE LA TleAB pour l'année scolaire 2024-2025	dépense	2025-10-25 14:40:40	2025-10-25 14:40:40
93	2025-08-22	salaires	36000.00	Salaire du professeur de HISTOIRE ET GEOGRAPHIE HOUNKOUINDO MARIUS DE LA TleAB pour l'année scolaire 2024-2025	dépense	2025-10-25 14:43:28	2025-10-25 14:43:28
94	2025-08-22	salaires	30000.00	Salaire du professeur d'ALLEMAND ADANDOSSOSSI EDMOND DE LA TleAB pour l'année scolaire 2024-2025	dépense	2025-10-25 14:45:14	2025-10-25 14:45:14
95	2025-08-22	salaires	12000.00	Salaire du professeur de SVT ADJAGNISSOUDE Bruno de la 2ndeCD pour l'année scolaire 2024-2025	dépense	2025-10-25 14:47:25	2025-10-25 14:47:25
96	2025-08-22	salaires	23000.00	Salaire du professeur de FRANÇCAIS LAKONON LANDRY DE LA TleAB pour l'année scolaire 2024-2025	dépense	2025-10-25 14:48:46	2025-10-25 14:48:46
97	2025-08-22	salaires	45000.00	Salaire du professeur de MATHS YESSOUFOU YASMOUD DE LA TleAB pour l'année scolaire 2024-2025	dépense	2025-10-25 14:50:10	2025-10-25 14:50:10
91	2025-08-22	scolarités	77000.00	scolarités	recette	2025-10-22 22:44:09	2025-10-25 15:22:49
98	2024-09-06	YESSOUFOU A. Affissou	5000.00	Somme reçue par M. YESSOUFOU A.\r\nAffissou pour ses propres bésoins	dépense	2025-10-25 23:23:14	2025-10-25 23:23:14
99	2024-09-06	ADEYEMI Kolawolé	5000.00	Somme reçue par M. ADEYEMI KOLAWOLE pour ses propres bésoins	dépense	2025-10-25 23:24:18	2025-10-25 23:24:18
100	2024-09-13	YESSOUFOU A. Affissou	2000.00	Somme reçue par M. YESSOUFOU A.\r\nAffissou pour ses propres bésoins	dépense	2025-10-25 23:25:46	2025-10-25 23:25:46
101	2024-09-13	ADEYEMI Kolawolé	2000.00	Somme reçue par M. ADEYEMI KOLAWOLE pour ses propres bésoins	dépense	2025-10-25 23:26:45	2025-10-25 23:26:45
102	2024-09-27	YESSOUFOU A. Affissou	3000.00	Somme reçue par M. YESSOUFOU A.\r\nAffissou pour ses propres bésoins	dépense	2025-10-25 23:28:37	2025-10-25 23:28:37
103	2024-10-04	YESSOUFOU A. Affissou	15000.00	Somme reçue par M. YESSOUFOU A.\r\nAffissou pour ses propres bésoins	dépense	2025-10-25 23:30:06	2025-10-25 23:30:06
104	2024-10-04	ADEYEMI Kolawolé	10000.00	Somme reçue par M. ADEYEMI KOLAWOLE pour ses propres bésoins	dépense	2025-10-25 23:31:00	2025-10-25 23:31:00
105	2025-08-25	autres dépenses	36650.00	craie, photocopies, séparation, impression	dépense	2025-10-30 23:50:01	2025-10-30 23:50:01
106	2025-08-26	autres dépenses	30050.00	rapport rentrée scolaire, photocopies, légalisation, dépôt rapport de rentrée, forfait	dépense	2025-10-30 23:52:39	2025-10-30 23:52:39
107	2025-08-26	autres dépenses	20350.00	photocopies, dossier ministère, déplacement, casiers judiciaires	dépense	2025-10-30 23:54:51	2025-10-30 23:54:51
\.


--
-- Data for Name: paiement_details; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.paiement_details (id, paiement_id, inscription_frais_id, montant_paye, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: paiements; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.paiements (id, inscription_id, frais_id, date_paiement, montant_verse, mode_paiement, numero_recu, created_at, updated_at, montant_total, reference) FROM stdin;
1	84	4	2026-02-26	30000.00	Espèces	REC-2026-AWXURA	2026-02-26 22:57:23	2026-02-26 22:57:23	90000.00	REF-FICEILSK
2	84	9	2026-02-26	5000.00	Espèces	REC-2026-AWXURA	2026-02-26 22:57:23	2026-02-26 22:57:23	5000.00	REF-GBKFLPJW
4	88	4	2026-03-03	90000.00	Espèces	REC-2026-N5RCIE	2026-03-03 13:11:58	2026-03-03 13:11:58	90000.00	REF-QKRH8X05
5	88	9	2026-03-03	5000.00	Espèces	REC-2026-N5RCIE	2026-03-03 13:11:58	2026-03-03 13:11:58	5000.00	REF-GMMWKBCQ
7	88	19	2026-03-03	2000.00	Espèces	REC-2026-N5RCIE	2026-03-03 13:11:58	2026-03-03 13:11:58	2000.00	REF-CXUDS8PC
9	89	4	2026-03-03	45000.00	Espèces	REC-2026-3IP9YU	2026-03-03 13:13:54	2026-03-03 13:13:54	90000.00	REF-RQGRN4U7
10	89	4	2026-03-03	20000.00	Espèces	REC-2026-CEY595	2026-03-03 13:16:03	2026-03-03 13:16:03	90000.00	REF-OOF12FTM
12	89	19	2026-03-03	2000.00	Espèces	REC-2026-CEY595	2026-03-03 13:16:03	2026-03-03 13:16:03	2000.00	REF-7FLU7UTU
15	84	4	2026-03-15	10000.00	Espèce	REC-20260315-0015	2026-03-15 00:25:39	2026-03-15 00:25:39	\N	\N
16	84	4	2026-03-15	5000.00	Espèces	REC-2026-X9VYZS	2026-03-15 00:29:20	2026-03-15 00:29:20	90000.00	REF-TVVQU8HQ
17	84	4	2026-03-16	15000.00	Mobile Money	REC-20260316-0017	2026-03-16 20:59:45	2026-03-16 20:59:45	\N	\N
18	71	2	2026-03-18	8000.00	Espèces	REC-2026-TQS6T0	2026-03-18 01:18:37	2026-03-18 01:18:37	80000.00	REF-VEKDCY7T
19	71	7	2026-03-18	2000.00	Espèces	REC-2026-TQS6T0	2026-03-18 01:18:37	2026-03-18 01:18:37	5000.00	REF-4I284GL2
21	71	17	2026-03-18	2000.00	Espèces	REC-2026-TQS6T0	2026-03-18 01:18:37	2026-03-18 01:18:37	2000.00	REF-G59BEX6S
25	71	2	2026-03-18	12000.00	Mobile Money	REC-2026-TIT4J7	2026-03-18 01:24:19	2026-03-18 01:24:19	80000.00	REF-HVB2SAVB
26	71	7	2026-03-18	3000.00	Mobile Money	REC-2026-TIT4J7	2026-03-18 01:24:19	2026-03-18 01:24:19	5000.00	REF-OVVH2WMP
30	99	3	2026-03-18	20000.00	Espèces	REC-2026-CSDP34	2026-03-18 01:29:23	2026-03-18 01:29:23	90000.00	REF-TPLT6UOP
31	99	8	2026-03-18	1000.00	Espèces	REC-2026-CSDP34	2026-03-18 01:29:23	2026-03-18 01:29:23	5000.00	REF-PWXN6R3B
33	70	2	2026-03-18	10000.00	Espèces	REC-2026-6SKIUS	2026-03-18 01:47:57	2026-03-18 01:47:57	80000.00	REF-ZFX4KSBT
34	70	7	2026-03-18	3000.00	Espèces	REC-2026-6SKIUS	2026-03-18 01:47:57	2026-03-18 01:47:57	5000.00	REF-LYGS0OTF
36	70	17	2026-03-18	2000.00	Espèces	REC-2026-6SKIUS	2026-03-18 01:47:57	2026-03-18 01:47:57	2000.00	REF-ML5FUP9L
38	73	2	2026-03-18	10000.00	Espèces	REC-2026-TTXFES	2026-03-18 01:55:12	2026-03-18 01:55:12	80000.00	REF-TVRWRLBG
39	73	7	2026-03-18	1000.00	Espèces	REC-2026-TTXFES	2026-03-18 01:55:12	2026-03-18 01:55:12	5000.00	REF-ZVVZPSSB
41	73	17	2026-03-18	1000.00	Espèces	REC-2026-TTXFES	2026-03-18 01:55:12	2026-03-18 01:55:12	2000.00	REF-0D964PJM
42	73	2	2026-03-18	10000.00	Espèces	REC-2026-SLV396	2026-03-18 01:59:36	2026-03-18 01:59:36	80000.00	REF-91NTZGZL
43	73	7	2026-03-18	1000.00	Espèces	REC-2026-SLV396	2026-03-18 01:59:36	2026-03-18 01:59:36	5000.00	REF-YOHV3THS
45	73	17	2026-03-18	1000.00	Espèces	REC-2026-SLV396	2026-03-18 01:59:36	2026-03-18 01:59:36	2000.00	REF-BYXUD7YH
49	56	1	2026-03-18	10000.00	Espèces	REC-2026-TV2FLM	2026-03-18 02:02:25	2026-03-18 02:02:25	80000.00	REF-7MOTCV5D
50	56	6	2026-03-18	1000.00	Espèces	REC-2026-TV2FLM	2026-03-18 02:02:25	2026-03-18 02:02:25	5000.00	REF-KZTZN9HF
52	56	16	2026-03-18	1000.00	Espèces	REC-2026-TV2FLM	2026-03-18 02:02:25	2026-03-18 02:02:25	2000.00	REF-WACI2CSC
53	56	1	2026-03-18	10000.00	Espèces	REC-2026-VH39AS	2026-03-18 02:06:50	2026-03-18 02:06:50	80000.00	REF-UQMZP1BR
54	56	6	2026-03-18	1000.00	Espèces	REC-2026-VH39AS	2026-03-18 02:06:50	2026-03-18 02:06:50	5000.00	REF-OUGGAWEZ
56	56	16	2026-03-18	1000.00	Espèces	REC-2026-VH39AS	2026-03-18 02:06:50	2026-03-18 02:06:50	2000.00	REF-IH432KSS
57	56	1	2026-03-18	10000.00	Espèces	REC-2026-QNOOL7	2026-03-18 13:22:29	2026-03-18 13:22:29	80000.00	REF-V6J2GNHX
58	94	5	2026-03-18	10000.00	Espèces	REC-2026-PZTTYS	2026-03-18 21:38:20	2026-03-18 21:38:20	110000.00	REF-YFO8NBEY
59	94	10	2026-03-18	3000.00	Espèces	REC-2026-PZTTYS	2026-03-18 21:38:20	2026-03-18 21:38:20	5000.00	REF-MJJS8SUQ
61	75	2	2026-03-18	10000.00	Espèces	REC-2026-IE5WA0	2026-03-18 21:51:21	2026-03-18 21:51:21	80000.00	REF-M6YSK8VG
62	75	7	2026-03-18	2000.00	Espèces	REC-2026-IE5WA0	2026-03-18 21:51:21	2026-03-18 21:51:21	5000.00	REF-DBNNVKD9
63	59	1	2026-03-18	80000.00	Espèces	REC-2026-FDFJ75	2026-03-18 22:22:28	2026-03-18 22:22:28	80000.00	REF-ZF0P6YFK
64	59	6	2026-03-18	5000.00	Espèces	REC-2026-FDFJ75	2026-03-18 22:22:28	2026-03-18 22:22:28	5000.00	REF-PGLZ3T0J
66	59	16	2026-03-18	2000.00	Espèces	REC-2026-FDFJ75	2026-03-18 22:22:28	2026-03-18 22:22:28	2000.00	REF-XMHINDTH
67	57	1	2026-03-22	25000.00	Espèce	REC-20260322-0067	2026-03-22 23:45:59	2026-03-22 23:45:59	\N	\N
68	82	3	2026-03-27	20000.00	Espèces	REC-2026-HUA7UF	2026-03-27 22:08:20	2026-03-27 22:08:20	90000.00	REF-XH4UMK39
69	82	8	2026-03-27	3000.00	Espèces	REC-2026-HUA7UF	2026-03-27 22:08:20	2026-03-27 22:08:20	5000.00	REF-C8BC5OSO
70	82	3	2026-03-27	10000.00	Espèces	REC-2026-DMP21P	2026-03-27 22:24:00	2026-03-27 22:24:00	90000.00	REF-QPX1HNSI
72	81	3	2026-03-27	13000.00	Espèces	REC-2026-K3B8D8	2026-03-27 22:32:17	2026-03-27 22:32:17	90000.00	REF-YT79GSR0
73	81	8	2026-03-27	2000.00	Espèces	REC-2026-K3B8D8	2026-03-27 22:32:17	2026-03-27 22:32:17	5000.00	REF-XSGXQ2B9
75	81	18	2026-03-27	1000.00	Espèces	REC-2026-K3B8D8	2026-03-27 22:32:17	2026-03-27 22:32:17	2000.00	REF-OB9JAXID
76	80	3	2026-03-27	20000.00	Espèces	REC-2026-WPEE7T	2026-03-27 22:43:50	2026-03-27 22:43:50	90000.00	REF-YVDYLLKA
77	80	8	2026-03-27	2000.00	Espèces	REC-2026-WPEE7T	2026-03-27 22:43:50	2026-03-27 22:43:50	5000.00	REF-W4ZMEWNW
79	80	18	2026-03-27	1000.00	Espèces	REC-2026-WPEE7T	2026-03-27 22:43:50	2026-03-27 22:43:50	2000.00	REF-OUUIRQZH
80	75	2	2026-03-27	20000.00	Espèce	REC-20260327-0080	2026-03-27 22:54:16	2026-03-27 22:54:16	\N	\N
81	74	2	2026-03-27	10000.00	Espèces	REC-2026-KFZJ1O	2026-03-27 23:42:55	2026-03-27 23:42:55	80000.00	REF-S0ZYPTDD
82	74	7	2026-03-27	1000.00	Espèces	REC-2026-KFZJ1O	2026-03-27 23:42:55	2026-03-27 23:42:55	5000.00	REF-G4T7GV9V
84	74	17	2026-03-27	1000.00	Espèces	REC-2026-KFZJ1O	2026-03-27 23:42:56	2026-03-27 23:42:56	2000.00	REF-JRMB86VW
85	90	4	2026-03-29	10000.00	Espèces	REC-2026-AWUUYM	2026-03-29 22:18:14	2026-03-29 22:18:14	90000.00	REF-SHNRMFPE
86	90	9	2026-03-29	1000.00	Espèces	REC-2026-AWUUYM	2026-03-29 22:18:15	2026-03-29 22:18:15	5000.00	REF-KEMB00MQ
88	90	19	2026-03-29	1000.00	Espèces	REC-2026-AWUUYM	2026-03-29 22:18:15	2026-03-29 22:18:15	2000.00	REF-AOJDLZXE
89	90	4	2026-03-29	10000.00	Espèces	REC-2026-N7QYT2	2026-03-29 22:44:37	2026-03-29 22:44:37	90000.00	REF-QLSTON8X
90	90	9	2026-03-29	1000.00	Espèces	REC-2026-N7QYT2	2026-03-29 22:44:37	2026-03-29 22:44:37	5000.00	REF-AHSBXUMJ
91	90	19	2026-03-29	1000.00	Espèces	REC-2026-N7QYT2	2026-03-29 22:44:37	2026-03-29 22:44:37	2000.00	REF-PP2W0NNO
93	90	4	2026-03-29	10000.00	Espèces	REC-2026-GMWPVA	2026-03-29 22:50:00	2026-03-29 22:50:00	90000.00	REF-7Y4SHIAS
94	90	9	2026-03-29	1000.00	Espèces	REC-2026-GMWPVA	2026-03-29 22:50:00	2026-03-29 22:50:00	5000.00	REF-5IK2AFKV
96	90	4	2026-03-29	10000.00	Espèces	REC-2026-NHA5D9	2026-03-29 23:11:47	2026-03-29 23:11:47	90000.00	REF-CUFABEN5
97	90	9	2026-03-29	1000.00	Espèces	REC-2026-NHA5D9	2026-03-29 23:11:47	2026-03-29 23:11:47	5000.00	REF-4CERSOSB
98	90	4	2026-03-29	10000.00	Espèces	REC-2026-ZXIYX8	2026-03-29 23:16:05	2026-03-29 23:16:05	90000.00	REF-J0XOPYR0
99	90	9	2026-03-29	1000.00	Espèces	REC-2026-ZXIYX8	2026-03-29 23:16:05	2026-03-29 23:16:05	5000.00	REF-XZKLIROR
101	87	4	2026-03-29	20000.00	Espèces	REC-2026-Q2JVO0	2026-03-29 23:33:17	2026-03-29 23:33:17	90000.00	REF-CVHHKLDH
102	87	9	2026-03-29	2000.00	Espèces	REC-2026-Q2JVO0	2026-03-29 23:33:17	2026-03-29 23:33:17	5000.00	REF-QEORD3DA
104	87	19	2026-03-29	1000.00	Espèces	REC-2026-Q2JVO0	2026-03-29 23:33:17	2026-03-29 23:33:17	2000.00	REF-3WCPGVLD
105	87	4	2026-03-29	10000.00	Espèces	REC-2026-R9IVDF	2026-03-29 23:51:36	2026-03-29 23:51:36	90000.00	REF-6Z2D0YA5
106	87	9	2026-03-29	1000.00	Espèces	REC-2026-R9IVDF	2026-03-29 23:51:36	2026-03-29 23:51:36	5000.00	REF-29RYJDTI
108	87	19	2026-03-29	1000.00	Espèces	REC-2026-R9IVDF	2026-03-29 23:51:36	2026-03-29 23:51:36	2000.00	REF-BHJBEXJI
112	79	3	2026-03-30	30000.00	Espèces	REC-2026-D01BTL	2026-03-30 00:05:04	2026-03-30 00:05:04	90000.00	REF-YKJIOVFL
113	79	8	2026-03-30	2000.00	Espèces	REC-2026-D01BTL	2026-03-30 00:05:04	2026-03-30 00:05:04	5000.00	REF-RBNP2CEX
115	79	18	2026-03-30	1000.00	Espèces	REC-2026-D01BTL	2026-03-30 00:05:04	2026-03-30 00:05:04	2000.00	REF-1IJEYLMG
116	78	3	2026-03-30	50000.00	Espèces	REC-2026-RTMXOV	2026-03-30 00:11:13	2026-03-30 00:11:13	90000.00	REF-RH2894VB
117	78	8	2026-03-30	5000.00	Espèces	REC-2026-RTMXOV	2026-03-30 00:11:13	2026-03-30 00:11:13	5000.00	REF-GQDOPVFR
119	78	18	2026-03-30	2000.00	Espèces	REC-2026-RTMXOV	2026-03-30 00:11:13	2026-03-30 00:11:13	2000.00	REF-J2BI5IHP
120	80	3	2026-03-30	20000.00	Espèces	REC-2026-CTTPID	2026-03-30 00:17:43	2026-03-30 00:17:43	90000.00	REF-EMQL21OP
121	80	8	2026-03-30	3000.00	Espèces	REC-2026-CTTPID	2026-03-30 00:17:43	2026-03-30 00:17:43	5000.00	REF-5ZQQMMF9
123	80	18	2026-03-30	1000.00	Espèces	REC-2026-CTTPID	2026-03-30 00:17:43	2026-03-30 00:17:43	2000.00	REF-QUSFZNPL
124	78	3	2026-04-08	20000.00	Espèce	REC-20260408-0124	2026-04-08 23:02:57	2026-04-08 23:02:57	\N	\N
125	74	2	2026-04-08	20000.00	Espèces	REC-2026-4SS4LH	2026-04-08 23:06:41	2026-04-08 23:06:41	80000.00	REF-MBLYSH0A
126	74	17	2026-04-08	1000.00	Espèces	REC-2026-4SS4LH	2026-04-08 23:06:41	2026-04-08 23:06:41	2000.00	REF-6PST9ZNX
127	74	7	2026-04-08	4000.00	Espèces	REC-2026-4SS4LH	2026-04-08 23:06:41	2026-04-08 23:06:41	5000.00	REF-GLZHXEUQ
130	92	5	2026-04-11	20000.00	Espèces	REC-2026-LI2G0L	2026-04-11 22:59:19	2026-04-11 22:59:19	110000.00	REF-AFDTEHXA
131	92	10	2026-04-11	5000.00	Espèces	REC-2026-LI2G0L	2026-04-11 22:59:19	2026-04-11 22:59:19	5000.00	REF-YRORFZQO
133	92	20	2026-04-11	2000.00	Espèces	REC-2026-LI2G0L	2026-04-11 22:59:19	2026-04-11 22:59:19	2000.00	REF-TQYLOGIK
134	85	4	2026-04-11	50000.00	Espèces	REC-2026-MHAX4O	2026-04-11 23:29:55	2026-04-11 23:29:55	90000.00	REF-SSSJTHRD
135	85	9	2026-04-11	5000.00	Espèces	REC-2026-MHAX4O	2026-04-11 23:29:55	2026-04-11 23:29:55	5000.00	REF-XGSSW0CB
137	85	19	2026-04-11	2000.00	Espèces	REC-2026-MHAX4O	2026-04-11 23:29:55	2026-04-11 23:29:55	2000.00	REF-VTY0XUQN
138	92	5	2026-04-11	40000.00	Espèces	REC-2026-IZJPUJ	2026-04-11 23:31:51	2026-04-11 23:31:51	110000.00	REF-GTIIDAZQ
139	92	5	2026-04-12	10000.00	Mobile Money	REC-2026-PXOHDZ	2026-04-12 16:41:00	2026-04-12 16:41:00	110000.00	REF-SNXNTRRM
140	92	5	2026-04-12	10000.00	Espèces	REC-2026-Z9DOVK	2026-04-12 16:51:02	2026-04-12 16:51:02	110000.00	REF-GXNLJUCY
141	93	10	2026-04-12	5000.00	Mobile Money	REC-2026-ADMGXM	2026-04-12 16:53:08	2026-04-12 16:53:08	5000.00	REF-CZSDSY3O
143	93	20	2026-04-12	2000.00	Mobile Money	REC-2026-ADMGXM	2026-04-12 16:53:08	2026-04-12 16:53:08	2000.00	REF-JEIJ6SXD
144	93	5	2026-04-12	20000.00	Espèces	REC-2026-IXIITG	2026-04-12 16:55:16	2026-04-12 16:55:16	110000.00	REF-DMKZ8ZNQ
145	93	5	2026-04-12	20000.00	Espèces	REC-2026-5W33OK	2026-04-12 17:04:11	2026-04-12 17:04:11	110000.00	REF-2GUNTXGF
146	93	5	2026-04-12	20000.00	Espèces	REC-2026-KVZ39G	2026-04-12 17:18:18	2026-04-12 17:18:18	110000.00	REF-CXEVN7MF
147	85	4	2026-04-12	10000.00	Espèces	REC-2026-QGDQMQ	2026-04-12 17:33:09	2026-04-12 17:33:09	90000.00	REF-BVRHPEWE
148	93	5	2026-04-12	10000.00	Espèces	REC-2026-GS5VD5	2026-04-12 17:35:45	2026-04-12 17:35:45	110000.00	REF-LR8N4QRH
149	91	10	2026-04-12	5000.00	Espèces	REC-2026-K4WUSC	2026-04-12 17:38:08	2026-04-12 17:38:08	5000.00	REF-LBTSAIJX
151	91	20	2026-04-12	2000.00	Espèces	REC-2026-K4WUSC	2026-04-12 17:38:08	2026-04-12 17:38:08	2000.00	REF-EDALABNC
152	91	5	2026-04-12	20000.00	Espèces	REC-2026-OQBWUU	2026-04-12 17:40:06	2026-04-12 17:40:06	110000.00	REF-TMXKXOHP
153	91	5	2026-04-12	10000.00	Espèces	REC-2026-VHAN19	2026-04-12 17:41:33	2026-04-12 17:41:33	110000.00	REF-BXK70W1C
154	91	5	2026-04-12	10000.00	Espèces	REC-2026-B8QAXQ	2026-04-12 19:19:15	2026-04-12 19:19:15	110000.00	REF-IEYSWUAS
155	91	5	2026-04-12	10000.00	Espèces	REC-2026-OHH89J	2026-04-12 19:29:40	2026-04-12 19:29:40	110000.00	REF-UDJCC9D7
156	86	4	2026-04-12	20000.00	Espèces	REC-2026-PWVSAT	2026-04-12 19:55:30	2026-04-12 19:55:30	90000.00	REF-YZEBVAVA
157	86	9	2026-04-12	5000.00	Espèces	REC-2026-PWVSAT	2026-04-12 19:55:30	2026-04-12 19:55:30	5000.00	REF-KSTU8W0E
159	86	19	2026-04-12	2000.00	Espèces	REC-2026-PWVSAT	2026-04-12 19:55:30	2026-04-12 19:55:30	2000.00	REF-Y9PQOO0O
160	82	8	2026-04-12	2000.00	Espèces	REC-2026-6PJKLN	2026-04-12 19:58:09	2026-04-12 19:58:09	5000.00	REF-MGKPPVI0
161	82	3	2026-04-12	60000.00	Espèces	REC-2026-6PJKLN	2026-04-12 19:58:09	2026-04-12 19:58:09	90000.00	REF-MZKRFQHT
162	82	18	2026-04-12	2000.00	Espèces	REC-2026-6PJKLN	2026-04-12 19:58:09	2026-04-12 19:58:09	2000.00	REF-KGTPJ50T
164	99	3	2026-04-12	10000.00	Espèces	REC-2026-NFUXKD	2026-04-12 19:59:51	2026-04-12 19:59:51	90000.00	REF-B8579HMU
165	99	8	2026-04-12	4000.00	Espèces	REC-2026-NFUXKD	2026-04-12 19:59:51	2026-04-12 19:59:51	5000.00	REF-L548B3HK
167	99	18	2026-04-12	2000.00	Espèces	REC-2026-NFUXKD	2026-04-12 19:59:51	2026-04-12 19:59:51	2000.00	REF-AJ8AGT0O
168	83	3	2026-04-12	20000.00	Espèces	REC-2026-BFJ84L	2026-04-12 20:03:21	2026-04-12 20:03:21	90000.00	REF-3GLU39IF
169	83	8	2026-04-12	5000.00	Espèces	REC-2026-BFJ84L	2026-04-12 20:03:21	2026-04-12 20:03:21	5000.00	REF-9IJG5E6K
171	83	18	2026-04-12	2000.00	Espèces	REC-2026-BFJ84L	2026-04-12 20:03:21	2026-04-12 20:03:21	2000.00	REF-JRSHGCFD
173	75	7	2026-04-12	3000.00	Espèces	REC-2026-QDWL6F	2026-04-12 20:06:07	2026-04-12 20:06:07	5000.00	REF-EDCKFXOS
174	75	2	2026-04-12	50000.00	Espèces	REC-2026-QDWL6F	2026-04-12 20:06:07	2026-04-12 20:06:07	80000.00	REF-LLLJ229S
175	75	17	2026-04-12	2000.00	Espèces	REC-2026-QDWL6F	2026-04-12 20:06:07	2026-04-12 20:06:07	2000.00	REF-O8KFGNWZ
176	79	3	2026-04-15	15000.00	Mobile Money	REC-20260415-0176	2026-04-15 19:07:26	2026-04-15 19:07:26	\N	\N
177	70	2	2026-04-25	30000.00	Espèces	REC-2026-DCCGIL	2026-04-25 21:59:38	2026-04-25 21:59:38	80000.00	REF-Z5YKYCX4
178	70	7	2026-04-25	2000.00	Espèces	REC-2026-DCCGIL	2026-04-25 21:59:38	2026-04-25 21:59:38	5000.00	REF-V2G9IA6D
180	72	2	2026-04-25	20000.00	Espèce	REC-20260425-0180	2026-04-25 22:09:34	2026-04-25 22:09:34	\N	\N
181	71	2	2026-04-30	20000.00	Espèces	REC-2026-HTZ2SI	2026-04-30 14:40:22	2026-04-30 14:40:22	80000.00	REF-J1GKQB7T
182	72	2	2026-04-30	10000.00	Espèces	REC-2026-FMSKEA	2026-04-30 14:42:24	2026-04-30 14:42:24	80000.00	REF-S6DE1JXY
183	89	4	2026-04-30	5000.00	Espèces	REC-2026-QQ9JY8	2026-04-30 14:50:05	2026-04-30 14:50:05	90000.00	REF-NT5GHX5G
184	90	4	2026-04-30	10000.00	Espèces	REC-2026-OMXL1D	2026-04-30 14:52:05	2026-04-30 14:52:05	90000.00	REF-SFRNAWGG
185	71	2	2026-04-30	10000.00	Espèces	REC-2026-U5OKPD	2026-04-30 14:54:37	2026-04-30 14:54:37	80000.00	REF-ULMAGAX7
186	74	2	2026-05-01	10000.00	Espèces	REC-2026-XKSJ3V	2026-05-01 10:25:30	2026-05-01 10:25:30	80000.00	REF-KR7JOKVV
187	74	2	2026-05-01	10000.00	Espèces	REC-2026-XWFL6H	2026-05-01 10:27:46	2026-05-01 10:27:46	80000.00	REF-CWW7GOWF
188	85	4	2026-05-10	3000.00	Espèces	REC-2026-ROLMSC	2026-05-10 20:23:01	2026-05-10 20:23:01	90000.00	REF-HAGVDHZO
189	79	3	2026-05-10	5000.00	Espèce	REC-20260510-0189	2026-05-10 20:59:23	2026-05-10 20:59:23	\N	\N
190	57	6	2026-05-10	5000.00	Espèce	REC-20260510-0190	2026-05-10 21:55:25	2026-05-10 21:55:25	\N	\N
191	99	3	2026-05-10	10000.00	Espèces	REC-2026-ULP0QL	2026-05-10 21:56:08	2026-05-10 21:56:08	90000.00	REF-DHSFDCZD
200	79	18	2026-06-02	1000.00	Espèces	REC-2026-EO4IDM	2026-06-02 13:40:26	2026-06-02 13:40:26	2000.00	REF-RJCILIVI
202	57	1	2026-06-04	15000.00	Espèces	LOT-OFA29SKD-20260604	2026-06-04 22:29:25	2026-06-04 22:29:25	\N	\N
203	57	16	2026-06-04	2000.00	Espèces	LOT-OFA29SKD-20260604	2026-06-04 22:29:25	2026-06-04 22:29:25	\N	\N
204	85	4	2026-06-04	7000.00	Mobile Money	LOT-XPTSHAQZ-20260604	2026-06-04 22:46:04	2026-06-04 22:46:04	\N	\N
205	56	1	2026-06-04	10000.00	Chèque	LOT-SM6FUNWO-20260604	2026-06-04 22:49:02	2026-06-04 22:49:02	\N	\N
206	56	6	2026-06-04	3000.00	Chèque	LOT-SM6FUNWO-20260604	2026-06-04 22:49:02	2026-06-04 22:49:02	\N	\N
207	58	6	2026-06-04	5000.00	Chèque	LOT-CVDBLTRM-20260604	2026-06-04 22:57:29	2026-06-04 22:57:29	\N	\N
209	58	16	2026-06-04	2000.00	Chèque	LOT-CVDBLTRM-20260604	2026-06-04 22:57:29	2026-06-04 22:57:29	\N	\N
210	58	1	2026-06-05	20000.00	Espèces	LOT-IIV9SKJ8-20260605	2026-06-05 10:27:59	2026-06-05 10:27:59	\N	\N
211	56	1	2026-06-05	10000.00	Espèces	LOT-POESMPEV-20260605	2026-06-05 10:29:10	2026-06-05 10:29:10	\N	\N
213	57	1	2026-06-08	10000.00	Espèces	REC-2026-KGA5SH	2026-06-08 17:57:46	2026-06-08 17:57:46	80000.00	REF-EJYQ5Z0O
214	70	2	2026-06-08	10000.00	Espèces	LOT-O3FDJ2ZE-20260608	2026-06-08 18:12:34	2026-06-08 18:12:34	\N	\N
215	79	8	2026-06-08	3000.00	Espèces	LOT-EQO8CFF3-20260608	2026-06-08 18:17:07	2026-06-08 18:17:07	\N	\N
216	79	3	2026-06-08	10000.00	Espèces	LOT-N2SDA3CA-20260608	2026-06-08 18:24:39	2026-06-08 18:24:39	\N	\N
217	86	4	2026-06-09	20000.00	Chèque	LOT-0MY8QWTG-20260609	2026-06-09 22:19:55	2026-06-09 22:19:55	\N	\N
218	89	9	2026-06-09	5000.00	Chèque	LOT-ZQRGXYZQ-20260609	2026-06-09 22:21:46	2026-06-09 22:21:46	\N	\N
219	80	3	2026-06-09	10000.00	Espèces	LOT-4WXQVWNS-20260609	2026-06-09 22:25:13	2026-06-09 22:25:13	\N	\N
220	99	3	2026-06-09	10000.00	Espèces	LOT-2KQDPOXK-20260609	2026-06-09 22:26:31	2026-06-09 22:26:31	\N	\N
221	73	2	2026-06-09	10000.00	Espèces	LOT-ODNIKQFP-20260609	2026-06-09 22:31:17	2026-06-09 22:31:17	\N	\N
222	73	7	2026-06-09	3000.00	Espèces	LOT-ODNIKQFP-20260609	2026-06-09 22:31:17	2026-06-09 22:31:17	\N	\N
223	92	5	2026-06-10	10000.00	Espèces	LOT-0HMEQK59-20260610	2026-06-10 23:54:45	2026-06-10 23:54:45	\N	\N
224	93	5	2026-06-10	10000.00	Espèces	LOT-4NYDDWHK-20260610	2026-06-10 23:56:16	2026-06-10 23:56:16	\N	\N
225	94	5	2026-06-10	30000.00	Espèces	LOT-2MA9JTYZ-20260610	2026-06-10 23:57:51	2026-06-10 23:57:51	\N	\N
226	94	10	2026-06-10	2000.00	Espèces	LOT-2MA9JTYZ-20260610	2026-06-10 23:57:51	2026-06-10 23:57:51	\N	\N
227	94	20	2026-06-10	2000.00	Espèces	LOT-2MA9JTYZ-20260610	2026-06-10 23:57:51	2026-06-10 23:57:51	\N	\N
228	95	5	2026-06-11	30000.00	Espèces	LOT-4H1LOIME-20260611	2026-06-11 00:01:07	2026-06-11 00:01:07	\N	\N
229	95	10	2026-06-11	5000.00	Espèces	LOT-4H1LOIME-20260611	2026-06-11 00:01:07	2026-06-11 00:01:07	\N	\N
230	95	20	2026-06-11	2000.00	Espèces	LOT-4H1LOIME-20260611	2026-06-11 00:01:07	2026-06-11 00:01:07	\N	\N
231	91	5	2026-06-11	10000.00	Espèces	LOT-E04VVQYE-20260611	2026-06-11 00:03:29	2026-06-11 00:03:29	\N	\N
232	71	2	2026-06-14	10000.00	Chèque	LOT-IK8MXB59-20260614	2026-06-14 21:11:42	2026-06-14 21:11:42	\N	\N
233	58	1	2026-06-19	10000.00	Espèces	LOT-S9HJQQMP-20260619	2026-06-19 23:47:18	2026-06-19 23:47:18	\N	\N
234	74	2	2026-06-29	12000.00	Chèque	REC-20260629-0234	2026-06-29 00:34:52	2026-06-29 00:34:52	\N	\N
235	71	2	2026-06-29	7000.00	Mobile Money	LOT-PNFHHRBF-20260629	2026-06-29 00:39:17	2026-06-29 00:39:17	\N	\N
236	70	2	2026-07-06	10000.00	Chèque	LOT-HBINVXDI-20260706	2026-07-06 10:55:40	2026-07-06 10:55:40	\N	\N
\.


--
-- Data for Name: paiements_benefices; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.paiements_benefices (id, repartition_id, date_paiement, montant, mode_paiement, reference, observation, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: parametres_investissements; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.parametres_investissements (id, cle, libelle, valeur, type, description, actif, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: parens; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.parens (id, nom_parent, prenom_parent, telephone_parent, adresse_parent, created_at, updated_at, user_id) FROM stdin;
39	GBAMIGBOLA	FIRMIN	197484776	cotonou	2026-02-02 22:41:41	2026-02-02 22:41:41	40
40	GBESSEHOUN	BORIS	149484340	cotonou	2026-02-02 22:41:41	2026-02-02 22:41:42	41
41	KALU	SIMEON	197615471	cotonou	2026-02-02 22:41:42	2026-02-02 22:41:42	42
42	LADOKOU	ALIOU	197081862	SEME PODJI	2026-02-02 22:41:42	2026-02-02 22:41:42	43
43	YESSOUFOU	Affissou	197189324	SEME PODJI	2026-02-02 22:41:42	2026-02-02 22:41:42	44
44	ADEYEMI	KOLAWOLE	197521637	COTONOU	2026-02-02 22:42:47	2026-02-02 22:42:47	45
45	BOUBACAR	BAKARI	145474846	COTONOU	2026-02-02 22:42:47	2026-02-02 22:42:47	46
46	CANDONOU	BENOIT	144785632	COTONOU	2026-02-02 22:42:47	2026-02-02 22:42:48	47
47	GNONLONFOUN	JOEL	166020699	COTONOU	2026-02-02 22:42:48	2026-02-02 22:42:48	48
48	SOUNOUVOU	GERMAIN	148882804	COTONOU	2026-02-02 22:42:48	2026-02-02 22:42:48	49
33	AGOKPINZIN	Edmon	166767618	Cotonou-Ayelawadje	2026-02-02 22:34:09	2026-02-02 22:34:09	33
34	AYABA	LATIF	144562312	Cotonou	2026-02-02 22:34:09	2026-02-02 22:34:10	34
35	SAGBOHAN	John	197476609	Cotonou	2026-02-02 22:34:10	2026-02-02 22:34:10	35
36	TAIWO	SADIKOU	197874170	COTONOU	2026-02-02 22:34:10	2026-02-02 22:34:10	36
37	AHOLODE	Jean	157238396	cotonou	2026-02-02 22:41:40	2026-02-02 22:41:41	38
38	DIAKITE	ADJARATOU	144525556	cotonou	2026-02-02 22:41:41	2026-02-02 22:41:41	39
53	HOUNDEWAGNON	GERARD	197122545	COTONOU	2026-02-03 20:36:04	2026-02-03 20:36:04	54
54	OHOUSSOU	PROSPER	144255635	COTONOU	2026-02-03 20:36:04	2026-02-03 20:36:05	55
49	AGBOZINGBA	Cossi	197070128	COTONOU	2026-02-03 20:34:16	2026-02-06 20:23:19	50
50	AHOUANSE	Antoine	197074018	COTONOU	2026-02-03 20:34:16	2026-02-06 20:23:37	51
51	BONOU	Joseph	196125472	COTONOU	2026-02-03 20:34:16	2026-02-06 20:23:48	52
52	OLAAFA	Nabil	166196100	COTONOU	2026-02-03 20:34:17	2026-02-06 20:23:59	53
\.


--
-- Data for Name: participant_examens; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.participant_examens (id, examen_blanc_id, inscription_id, numero_table, created_at, updated_at, moyenne) FROM stdin;
134	33	84	EB202605001	2026-05-14 13:13:13	2026-05-21 16:55:46	10.06
135	33	85	EB202605002	2026-05-14 13:13:13	2026-05-21 16:55:46	11.06
136	33	86	EB202605003	2026-05-14 13:13:13	2026-05-21 16:55:46	12.28
137	33	87	EB202605004	2026-05-14 13:13:13	2026-05-21 16:55:46	8.83
138	33	88	EB202605005	2026-05-14 13:13:13	2026-05-21 16:55:46	11.78
139	33	89	EB202605006	2026-05-14 13:13:13	2026-05-21 16:55:46	8.11
140	33	90	EB202605007	2026-05-14 13:13:13	2026-05-21 16:55:46	11.89
156	35	84	EB202606001	2026-06-26 23:28:07	2026-06-26 23:28:07	\N
157	35	85	EB202606002	2026-06-26 23:28:07	2026-06-26 23:28:07	\N
158	35	86	EB202606003	2026-06-26 23:28:07	2026-06-26 23:28:07	\N
159	35	87	EB202606004	2026-06-26 23:28:07	2026-06-26 23:28:07	\N
160	35	88	EB202606005	2026-06-26 23:28:07	2026-06-26 23:28:07	\N
161	35	89	EB202606006	2026-06-26 23:28:07	2026-06-26 23:28:07	\N
162	35	90	EB202606007	2026-06-26 23:28:07	2026-06-26 23:28:07	\N
\.


--
-- Data for Name: passages; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.passages (id, inscription_id, moyenne_id, classe_id, ancienne_classe_id, nouvelle_classe_id, annee_id, moyenne_annuelle, created_at, updated_at, decision) FROM stdin;
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
admin@gmail.com	$2y$12$m9yyZ.szMjfFUQd0SF46KusEJwXF7M/vrjinnP6UvLfOUuF26jLAm	2026-06-08 17:42:02
secre@gmail.com	$2y$12$6s3/3269UZ8KYoIHtGCCrOppFS95v6GCjRfZ1EOf07lBwRyyVJhZu	2026-06-08 21:58:48
kolatresor.adeyemi@gmail.com	$2y$12$dDeGW/ZNRHjRq04S8GJKne4vk94C.xHEe7XY7Uvoo1IDHYpnhKbBW	2026-06-08 22:09:07
direct@gmail.com	$2y$12$AMQ.SXxc/1Ow9P42zvvorOY/G/fE3PmCZgGW2Wh6a1tJ6Uw5sdc8q	2026-06-08 22:09:12
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
1	eleves.view	web	2026-01-08 08:47:03	2026-01-08 08:47:03
2	eleves.create	web	2026-01-08 08:47:03	2026-01-08 08:47:03
3	eleves.edit	web	2026-01-08 08:47:03	2026-01-08 08:47:03
4	eleves.delete	web	2026-01-08 08:47:03	2026-01-08 08:47:03
5	notes.import	web	2026-01-08 08:47:03	2026-01-08 08:47:03
6	notes.edit	web	2026-01-08 08:47:03	2026-01-08 08:47:03
7	notes.view	web	2026-01-08 08:47:03	2026-01-08 08:47:03
8	parents.import	web	2026-01-08 08:47:03	2026-01-08 08:47:03
9	parents.view	web	2026-01-08 08:47:03	2026-01-08 08:47:03
10	paiements.view	web	2026-01-08 08:47:03	2026-01-08 08:47:03
11	paiements.create	web	2026-01-08 08:47:03	2026-01-08 08:47:03
12	bulletins.view	web	2026-01-08 08:47:03	2026-01-08 08:47:03
13	bulletins.generate	web	2026-01-08 08:47:03	2026-01-08 08:47:03
14	users.manage	web	2026-01-08 08:47:03	2026-01-08 08:47:03
15	roles.manage	web	2026-01-08 08:47:03	2026-01-08 08:47:03
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
15	App\\Models\\User	34	auth_token	adab9df0091077a023efbbe3c0a316dffbc0ad1aa7c021fc5a16b72ceba5643e	["*"]	\N	\N	2026-06-03 22:21:14	2026-06-03 22:21:14
12	App\\Models\\User	34	auth_token	a29b8644d9b8c1463becef17272cb0efee947d839b00f58fe36de35c8eafcf28	["*"]	2026-06-03 22:31:01	\N	2026-06-03 21:39:04	2026-06-03 22:31:01
16	App\\Models\\User	34	auth_token	6024bea9bd34e8cf3b8f0b82b68464f68ecf65e29719e8a085dda5aa51c40a10	["*"]	\N	\N	2026-06-03 22:32:21	2026-06-03 22:32:21
1	App\\Models\\User	57	auth_token	e95d26c8c58ac787d17f9163e7fb4ef3dbcaec17c8584bb0ee2d179d2842df3c	["*"]	2026-05-17 17:10:38	\N	2026-05-17 14:33:29	2026-05-17 17:10:38
2	App\\Models\\User	57	auth_token	b6ceaed8dc8553f2c37315df41ab6413ab82b9b8e5bc4bf00980b159591b8811	["*"]	\N	\N	2026-06-02 21:50:45	2026-06-02 21:50:45
17	App\\Models\\User	34	auth_token	2f004f4c33490243aa4923e38834d5e4a7b4d6fcf15ed474aa456f2cafb95bee	["*"]	2026-06-03 22:34:59	\N	2026-06-03 22:32:50	2026-06-03 22:34:59
28	App\\Models\\User	44	auth_token	91710dae1faeffac84cccdc9158c15780444204e703eb8885a9cb28e52c7dd27	["*"]	2026-06-08 17:32:33	\N	2026-06-08 13:27:58	2026-06-08 17:32:33
5	App\\Models\\User	73	auth_token	92be2d930ec603cd8e0ab5d4a6af197d41618427734cb4ca36445484249f5e01	["*"]	2026-06-02 22:38:44	\N	2026-06-02 22:37:49	2026-06-02 22:38:44
6	App\\Models\\User	73	auth_token	eddd16fe69e04dfbcea99b853e28749d483469ee7415c2ccc521dcad6836dece	["*"]	2026-06-02 22:52:59	\N	2026-06-02 22:52:59	2026-06-02 22:52:59
19	App\\Models\\User	34	auth_token	9845e5fe94d11063727bb743f3c210dcd5528c749b2f432e1339974ac9fca225	["*"]	\N	\N	2026-06-03 23:08:32	2026-06-03 23:08:32
3	App\\Models\\User	73	auth_token	53c3c13e66b0feee2b6184763c5bdb28bd6861f737e31b1ebf517bbb8044d9e5	["*"]	2026-06-02 23:02:51	\N	2026-06-02 22:16:31	2026-06-02 23:02:51
11	App\\Models\\User	37	auth_token	b4232ec60934903364e6c0998cc6ff85f2be34c7e2d3b433ba541b1bdb645f0d	["*"]	2026-06-03 00:48:19	\N	2026-06-03 00:14:48	2026-06-03 00:48:19
7	App\\Models\\User	73	auth_token	0b1944fa0a3b4d7c72d88adbfd21ee81ccf8c28e90339f22440a95acf69d5b1d	["*"]	2026-06-02 23:08:00	\N	2026-06-02 23:04:41	2026-06-02 23:08:00
8	App\\Models\\User	73	auth_token	2ff2156d800afa854bf422518478f07e33945b7de10a9fdc9532d4738c606124	["*"]	2026-06-02 23:40:58	\N	2026-06-02 23:40:57	2026-06-02 23:40:58
20	App\\Models\\User	34	auth_token	7c70b60a1f8113fd364d28966064b8106331fce3fb85c3ba3aec9bf924aefa14	["*"]	2026-06-05 23:12:44	\N	2026-06-05 23:12:44	2026-06-05 23:12:44
9	App\\Models\\User	73	auth_token	ceb4a036e31b5b37e27720ee3e011f317ddc16fbb6eec78a9769b829eeddc48d	["*"]	2026-06-02 23:54:45	\N	2026-06-02 23:53:33	2026-06-02 23:54:45
21	App\\Models\\User	44	auth_token	8bed24159d623b3f027839e331421e25037c0491f8bc331a9791a36a7b9c49d0	["*"]	2026-06-06 10:10:07	\N	2026-06-06 10:10:07	2026-06-06 10:10:07
22	App\\Models\\User	44	auth_token	f1c5015f903c7ce12102c85bac2f94a84f0f9df55ca0d74e3e41629d434100f4	["*"]	2026-06-07 22:07:04	\N	2026-06-07 22:07:03	2026-06-07 22:07:04
23	App\\Models\\User	44	auth_token	672c27d7fc90de62fc01a773f57868756ff3330b9f4adbe8a8bc31321f9fd7b5	["*"]	2026-06-07 22:58:10	\N	2026-06-07 22:58:10	2026-06-07 22:58:10
24	App\\Models\\User	44	auth_token	283c38795614df416b150f3f19723446fd79b9472b091f720634e81b4b58afcc	["*"]	\N	\N	2026-06-08 13:10:23	2026-06-08 13:10:23
25	App\\Models\\User	44	auth_token	7fda3a487ee0d6e4f1514448e3482a3d5e559e5d9e632a3142845164c2048b0b	["*"]	\N	\N	2026-06-08 13:10:29	2026-06-08 13:10:29
26	App\\Models\\User	44	auth_token	61b85ac2dd52faadaf9ebbe82432474b46586ea907a18e0d4a8cee2e177546cd	["*"]	\N	\N	2026-06-08 13:10:50	2026-06-08 13:10:50
27	App\\Models\\User	73	auth_token	061f57c57cdd1257821deb1deae52dd8293ed0e55383c69264f9474a3e27c42d	["*"]	\N	\N	2026-06-08 13:11:57	2026-06-08 13:11:57
29	App\\Models\\User	34	auth_token	e22d4c03a19a5b5a7980949c2ecb03aa78a6ab6efed4008cfc6cd2b2113b4723	["*"]	2026-06-20 00:45:41	\N	2026-06-12 15:36:30	2026-06-20 00:45:41
\.


--
-- Data for Name: recettes; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.recettes (id, date_paiement, montant_verse, created_at, updated_at, paiement_id, inscription_id, mode_paiement, numero_recu) FROM stdin;
142	2026-02-26	30000.00	2026-02-26 22:57:23	2026-02-26 22:57:23	1	84	Espèces	REC-2026-AWXURA
143	2026-02-26	5000.00	2026-02-26 22:57:23	2026-02-26 22:57:23	2	84	Espèces	REC-2026-AWXURA
145	2026-03-03	90000.00	2026-03-03 13:11:58	2026-03-03 13:11:58	4	88	Espèces	REC-2026-N5RCIE
146	2026-03-03	5000.00	2026-03-03 13:11:58	2026-03-03 13:11:58	5	88	Espèces	REC-2026-N5RCIE
148	2026-03-03	2000.00	2026-03-03 13:11:58	2026-03-03 13:11:58	7	88	Espèces	REC-2026-N5RCIE
150	2026-03-03	45000.00	2026-03-03 13:13:54	2026-03-03 13:13:54	9	89	Espèces	REC-2026-3IP9YU
151	2026-03-03	20000.00	2026-03-03 13:16:03	2026-03-03 13:16:03	10	89	Espèces	REC-2026-CEY595
153	2026-03-03	2000.00	2026-03-03 13:16:03	2026-03-03 13:16:03	12	89	Espèces	REC-2026-CEY595
156	2026-03-15	10000.00	2026-03-15 00:25:39	2026-03-15 00:25:39	15	84	Espèce	REC-20260315-0015
157	2026-03-15	5000.00	2026-03-15 00:29:20	2026-03-15 00:29:20	16	84	Espèces	REC-2026-X9VYZS
158	2026-03-16	15000.00	2026-03-16 20:59:45	2026-03-16 20:59:45	17	84	Mobile Money	REC-20260316-0017
159	2026-03-18	8000.00	2026-03-18 01:18:37	2026-03-18 01:18:37	18	71	Espèces	REC-2026-TQS6T0
160	2026-03-18	2000.00	2026-03-18 01:18:37	2026-03-18 01:18:37	19	71	Espèces	REC-2026-TQS6T0
162	2026-03-18	2000.00	2026-03-18 01:18:37	2026-03-18 01:18:37	21	71	Espèces	REC-2026-TQS6T0
166	2026-03-18	12000.00	2026-03-18 01:24:19	2026-03-18 01:24:19	25	71	Mobile Money	REC-2026-TIT4J7
167	2026-03-18	3000.00	2026-03-18 01:24:19	2026-03-18 01:24:19	26	71	Mobile Money	REC-2026-TIT4J7
171	2026-03-18	20000.00	2026-03-18 01:29:23	2026-03-18 01:29:23	30	99	Espèces	REC-2026-CSDP34
172	2026-03-18	1000.00	2026-03-18 01:29:23	2026-03-18 01:29:23	31	99	Espèces	REC-2026-CSDP34
174	2026-03-18	10000.00	2026-03-18 01:47:57	2026-03-18 01:47:57	33	70	Espèces	REC-2026-6SKIUS
175	2026-03-18	3000.00	2026-03-18 01:47:57	2026-03-18 01:47:57	34	70	Espèces	REC-2026-6SKIUS
177	2026-03-18	2000.00	2026-03-18 01:47:57	2026-03-18 01:47:57	36	70	Espèces	REC-2026-6SKIUS
179	2026-03-18	10000.00	2026-03-18 01:55:12	2026-03-18 01:55:12	38	73	Espèces	REC-2026-TTXFES
180	2026-03-18	1000.00	2026-03-18 01:55:12	2026-03-18 01:55:12	39	73	Espèces	REC-2026-TTXFES
182	2026-03-18	1000.00	2026-03-18 01:55:12	2026-03-18 01:55:12	41	73	Espèces	REC-2026-TTXFES
183	2026-03-18	10000.00	2026-03-18 01:59:36	2026-03-18 01:59:36	42	73	Espèces	REC-2026-SLV396
184	2026-03-18	1000.00	2026-03-18 01:59:36	2026-03-18 01:59:36	43	73	Espèces	REC-2026-SLV396
186	2026-03-18	1000.00	2026-03-18 01:59:36	2026-03-18 01:59:36	45	73	Espèces	REC-2026-SLV396
190	2026-03-18	10000.00	2026-03-18 02:02:25	2026-03-18 02:02:25	49	56	Espèces	REC-2026-TV2FLM
191	2026-03-18	1000.00	2026-03-18 02:02:25	2026-03-18 02:02:25	50	56	Espèces	REC-2026-TV2FLM
193	2026-03-18	1000.00	2026-03-18 02:02:25	2026-03-18 02:02:25	52	56	Espèces	REC-2026-TV2FLM
194	2026-03-18	10000.00	2026-03-18 02:06:50	2026-03-18 02:06:50	53	56	Espèces	REC-2026-VH39AS
195	2026-03-18	1000.00	2026-03-18 02:06:50	2026-03-18 02:06:50	54	56	Espèces	REC-2026-VH39AS
197	2026-03-18	1000.00	2026-03-18 02:06:50	2026-03-18 02:06:50	56	56	Espèces	REC-2026-VH39AS
198	2026-03-18	10000.00	2026-03-18 13:22:29	2026-03-18 13:22:29	57	56	Espèces	REC-2026-QNOOL7
199	2026-03-18	10000.00	2026-03-18 21:38:20	2026-03-18 21:38:20	58	94	Espèces	REC-2026-PZTTYS
200	2026-03-18	3000.00	2026-03-18 21:38:20	2026-03-18 21:38:20	59	94	Espèces	REC-2026-PZTTYS
202	2026-03-18	10000.00	2026-03-18 21:51:21	2026-03-18 21:51:21	61	75	Espèces	REC-2026-IE5WA0
203	2026-03-18	2000.00	2026-03-18 21:51:21	2026-03-18 21:51:21	62	75	Espèces	REC-2026-IE5WA0
204	2026-03-18	80000.00	2026-03-18 22:22:28	2026-03-18 22:22:28	63	59	Espèces	REC-2026-FDFJ75
205	2026-03-18	5000.00	2026-03-18 22:22:28	2026-03-18 22:22:28	64	59	Espèces	REC-2026-FDFJ75
207	2026-03-18	2000.00	2026-03-18 22:22:28	2026-03-18 22:22:28	66	59	Espèces	REC-2026-FDFJ75
208	2026-03-22	25000.00	2026-03-22 23:45:59	2026-03-22 23:45:59	67	57	Espèce	REC-20260322-0067
209	2026-03-27	20000.00	2026-03-27 22:08:20	2026-03-27 22:08:20	68	82	Espèces	REC-2026-HUA7UF
210	2026-03-27	3000.00	2026-03-27 22:08:20	2026-03-27 22:08:20	69	82	Espèces	REC-2026-HUA7UF
211	2026-03-27	10000.00	2026-03-27 22:24:00	2026-03-27 22:24:00	70	82	Espèces	REC-2026-DMP21P
213	2026-03-27	13000.00	2026-03-27 22:32:17	2026-03-27 22:32:17	72	81	Espèces	REC-2026-K3B8D8
214	2026-03-27	2000.00	2026-03-27 22:32:17	2026-03-27 22:32:17	73	81	Espèces	REC-2026-K3B8D8
216	2026-03-27	1000.00	2026-03-27 22:32:17	2026-03-27 22:32:17	75	81	Espèces	REC-2026-K3B8D8
217	2026-03-27	20000.00	2026-03-27 22:43:50	2026-03-27 22:43:50	76	80	Espèces	REC-2026-WPEE7T
218	2026-03-27	2000.00	2026-03-27 22:43:50	2026-03-27 22:43:50	77	80	Espèces	REC-2026-WPEE7T
220	2026-03-27	1000.00	2026-03-27 22:43:50	2026-03-27 22:43:50	79	80	Espèces	REC-2026-WPEE7T
221	2026-03-27	20000.00	2026-03-27 22:54:16	2026-03-27 22:54:16	80	75	Espèce	REC-20260327-0080
222	2026-03-27	10000.00	2026-03-27 23:42:55	2026-03-27 23:42:55	81	74	Espèces	REC-2026-KFZJ1O
223	2026-03-27	1000.00	2026-03-27 23:42:55	2026-03-27 23:42:55	82	74	Espèces	REC-2026-KFZJ1O
225	2026-03-27	1000.00	2026-03-27 23:42:56	2026-03-27 23:42:56	84	74	Espèces	REC-2026-KFZJ1O
226	2026-03-29	10000.00	2026-03-29 22:18:14	2026-03-29 22:18:14	85	90	Espèces	REC-2026-AWUUYM
227	2026-03-29	1000.00	2026-03-29 22:18:15	2026-03-29 22:18:15	86	90	Espèces	REC-2026-AWUUYM
229	2026-03-29	1000.00	2026-03-29 22:18:15	2026-03-29 22:18:15	88	90	Espèces	REC-2026-AWUUYM
230	2026-03-29	10000.00	2026-03-29 22:44:37	2026-03-29 22:44:37	89	90	Espèces	REC-2026-N7QYT2
231	2026-03-29	1000.00	2026-03-29 22:44:37	2026-03-29 22:44:37	90	90	Espèces	REC-2026-N7QYT2
232	2026-03-29	1000.00	2026-03-29 22:44:37	2026-03-29 22:44:37	91	90	Espèces	REC-2026-N7QYT2
234	2026-03-29	10000.00	2026-03-29 22:50:00	2026-03-29 22:50:00	93	90	Espèces	REC-2026-GMWPVA
235	2026-03-29	1000.00	2026-03-29 22:50:00	2026-03-29 22:50:00	94	90	Espèces	REC-2026-GMWPVA
237	2026-03-29	10000.00	2026-03-29 23:11:47	2026-03-29 23:11:47	96	90	Espèces	REC-2026-NHA5D9
238	2026-03-29	1000.00	2026-03-29 23:11:47	2026-03-29 23:11:47	97	90	Espèces	REC-2026-NHA5D9
239	2026-03-29	10000.00	2026-03-29 23:16:05	2026-03-29 23:16:05	98	90	Espèces	REC-2026-ZXIYX8
240	2026-03-29	1000.00	2026-03-29 23:16:05	2026-03-29 23:16:05	99	90	Espèces	REC-2026-ZXIYX8
242	2026-03-29	20000.00	2026-03-29 23:33:17	2026-03-29 23:33:17	101	87	Espèces	REC-2026-Q2JVO0
243	2026-03-29	2000.00	2026-03-29 23:33:17	2026-03-29 23:33:17	102	87	Espèces	REC-2026-Q2JVO0
245	2026-03-29	1000.00	2026-03-29 23:33:18	2026-03-29 23:33:18	104	87	Espèces	REC-2026-Q2JVO0
246	2026-03-29	10000.00	2026-03-29 23:51:36	2026-03-29 23:51:36	105	87	Espèces	REC-2026-R9IVDF
247	2026-03-29	1000.00	2026-03-29 23:51:36	2026-03-29 23:51:36	106	87	Espèces	REC-2026-R9IVDF
249	2026-03-29	1000.00	2026-03-29 23:51:36	2026-03-29 23:51:36	108	87	Espèces	REC-2026-R9IVDF
253	2026-03-30	30000.00	2026-03-30 00:05:04	2026-03-30 00:05:04	112	79	Espèces	REC-2026-D01BTL
254	2026-03-30	2000.00	2026-03-30 00:05:04	2026-03-30 00:05:04	113	79	Espèces	REC-2026-D01BTL
256	2026-03-30	1000.00	2026-03-30 00:05:04	2026-03-30 00:05:04	115	79	Espèces	REC-2026-D01BTL
257	2026-03-30	50000.00	2026-03-30 00:11:13	2026-03-30 00:11:13	116	78	Espèces	REC-2026-RTMXOV
258	2026-03-30	5000.00	2026-03-30 00:11:13	2026-03-30 00:11:13	117	78	Espèces	REC-2026-RTMXOV
260	2026-03-30	2000.00	2026-03-30 00:11:13	2026-03-30 00:11:13	119	78	Espèces	REC-2026-RTMXOV
261	2026-03-30	20000.00	2026-03-30 00:17:43	2026-03-30 00:17:43	120	80	Espèces	REC-2026-CTTPID
262	2026-03-30	3000.00	2026-03-30 00:17:43	2026-03-30 00:17:43	121	80	Espèces	REC-2026-CTTPID
264	2026-03-30	1000.00	2026-03-30 00:17:43	2026-03-30 00:17:43	123	80	Espèces	REC-2026-CTTPID
265	2026-04-08	20000.00	2026-04-08 23:02:57	2026-04-08 23:02:57	124	78	Espèce	REC-20260408-0124
266	2026-04-08	20000.00	2026-04-08 23:06:41	2026-04-08 23:06:41	125	74	Espèces	REC-2026-4SS4LH
267	2026-04-08	1000.00	2026-04-08 23:06:41	2026-04-08 23:06:41	126	74	Espèces	REC-2026-4SS4LH
268	2026-04-08	4000.00	2026-04-08 23:06:41	2026-04-08 23:06:41	127	74	Espèces	REC-2026-4SS4LH
271	2026-04-11	20000.00	2026-04-11 22:59:19	2026-04-11 22:59:19	130	92	Espèces	REC-2026-LI2G0L
272	2026-04-11	5000.00	2026-04-11 22:59:19	2026-04-11 22:59:19	131	92	Espèces	REC-2026-LI2G0L
274	2026-04-11	2000.00	2026-04-11 22:59:19	2026-04-11 22:59:19	133	92	Espèces	REC-2026-LI2G0L
275	2026-04-11	50000.00	2026-04-11 23:29:55	2026-04-11 23:29:55	134	85	Espèces	REC-2026-MHAX4O
276	2026-04-11	5000.00	2026-04-11 23:29:55	2026-04-11 23:29:55	135	85	Espèces	REC-2026-MHAX4O
278	2026-04-11	2000.00	2026-04-11 23:29:55	2026-04-11 23:29:55	137	85	Espèces	REC-2026-MHAX4O
279	2026-04-11	40000.00	2026-04-11 23:31:51	2026-04-11 23:31:51	138	92	Espèces	REC-2026-IZJPUJ
280	2026-04-12	10000.00	2026-04-12 16:41:00	2026-04-12 16:41:00	139	92	Mobile Money	REC-2026-PXOHDZ
281	2026-04-12	10000.00	2026-04-12 16:51:02	2026-04-12 16:51:02	140	92	Espèces	REC-2026-Z9DOVK
282	2026-04-12	5000.00	2026-04-12 16:53:08	2026-04-12 16:53:08	141	93	Mobile Money	REC-2026-ADMGXM
284	2026-04-12	2000.00	2026-04-12 16:53:08	2026-04-12 16:53:08	143	93	Mobile Money	REC-2026-ADMGXM
285	2026-04-12	20000.00	2026-04-12 16:55:16	2026-04-12 16:55:16	144	93	Espèces	REC-2026-IXIITG
286	2026-04-12	20000.00	2026-04-12 17:04:11	2026-04-12 17:04:11	145	93	Espèces	REC-2026-5W33OK
287	2026-04-12	20000.00	2026-04-12 17:18:18	2026-04-12 17:18:18	146	93	Espèces	REC-2026-KVZ39G
288	2026-04-12	10000.00	2026-04-12 17:33:09	2026-04-12 17:33:09	147	85	Espèces	REC-2026-QGDQMQ
289	2026-04-12	10000.00	2026-04-12 17:35:45	2026-04-12 17:35:45	148	93	Espèces	REC-2026-GS5VD5
290	2026-04-12	5000.00	2026-04-12 17:38:08	2026-04-12 17:38:08	149	91	Espèces	REC-2026-K4WUSC
292	2026-04-12	2000.00	2026-04-12 17:38:08	2026-04-12 17:38:08	151	91	Espèces	REC-2026-K4WUSC
293	2026-04-12	20000.00	2026-04-12 17:40:06	2026-04-12 17:40:06	152	91	Espèces	REC-2026-OQBWUU
294	2026-04-12	10000.00	2026-04-12 17:41:33	2026-04-12 17:41:33	153	91	Espèces	REC-2026-VHAN19
295	2026-04-12	10000.00	2026-04-12 19:19:15	2026-04-12 19:19:15	154	91	Espèces	REC-2026-B8QAXQ
296	2026-04-12	10000.00	2026-04-12 19:29:40	2026-04-12 19:29:40	155	91	Espèces	REC-2026-OHH89J
297	2026-04-12	20000.00	2026-04-12 19:55:30	2026-04-12 19:55:30	156	86	Espèces	REC-2026-PWVSAT
298	2026-04-12	5000.00	2026-04-12 19:55:30	2026-04-12 19:55:30	157	86	Espèces	REC-2026-PWVSAT
300	2026-04-12	2000.00	2026-04-12 19:55:30	2026-04-12 19:55:30	159	86	Espèces	REC-2026-PWVSAT
301	2026-04-12	2000.00	2026-04-12 19:58:09	2026-04-12 19:58:09	160	82	Espèces	REC-2026-6PJKLN
302	2026-04-12	60000.00	2026-04-12 19:58:09	2026-04-12 19:58:09	161	82	Espèces	REC-2026-6PJKLN
303	2026-04-12	2000.00	2026-04-12 19:58:09	2026-04-12 19:58:09	162	82	Espèces	REC-2026-6PJKLN
305	2026-04-12	10000.00	2026-04-12 19:59:51	2026-04-12 19:59:51	164	99	Espèces	REC-2026-NFUXKD
306	2026-04-12	4000.00	2026-04-12 19:59:51	2026-04-12 19:59:51	165	99	Espèces	REC-2026-NFUXKD
308	2026-04-12	2000.00	2026-04-12 19:59:51	2026-04-12 19:59:51	167	99	Espèces	REC-2026-NFUXKD
309	2026-04-12	20000.00	2026-04-12 20:03:21	2026-04-12 20:03:21	168	83	Espèces	REC-2026-BFJ84L
310	2026-04-12	5000.00	2026-04-12 20:03:21	2026-04-12 20:03:21	169	83	Espèces	REC-2026-BFJ84L
312	2026-04-12	2000.00	2026-04-12 20:03:21	2026-04-12 20:03:21	171	83	Espèces	REC-2026-BFJ84L
314	2026-04-12	3000.00	2026-04-12 20:06:07	2026-04-12 20:06:07	173	75	Espèces	REC-2026-QDWL6F
315	2026-04-12	50000.00	2026-04-12 20:06:07	2026-04-12 20:06:07	174	75	Espèces	REC-2026-QDWL6F
316	2026-04-12	2000.00	2026-04-12 20:06:07	2026-04-12 20:06:07	175	75	Espèces	REC-2026-QDWL6F
317	2026-04-15	15000.00	2026-04-15 19:07:26	2026-04-15 19:07:26	176	79	Mobile Money	REC-20260415-0176
318	2026-04-25	30000.00	2026-04-25 21:59:38	2026-04-25 21:59:38	177	70	Espèces	REC-2026-DCCGIL
319	2026-04-25	2000.00	2026-04-25 21:59:38	2026-04-25 21:59:38	178	70	Espèces	REC-2026-DCCGIL
321	2026-04-25	20000.00	2026-04-25 22:09:34	2026-04-25 22:09:34	180	72	Espèce	REC-20260425-0180
322	2026-04-30	20000.00	2026-04-30 14:40:22	2026-04-30 14:40:22	181	71	Espèces	REC-2026-HTZ2SI
323	2026-04-30	10000.00	2026-04-30 14:42:24	2026-04-30 14:42:24	182	72	Espèces	REC-2026-FMSKEA
324	2026-04-30	5000.00	2026-04-30 14:50:05	2026-04-30 14:50:05	183	89	Espèces	REC-2026-QQ9JY8
325	2026-04-30	10000.00	2026-04-30 14:52:05	2026-04-30 14:52:05	184	90	Espèces	REC-2026-OMXL1D
326	2026-04-30	10000.00	2026-04-30 14:54:37	2026-04-30 14:54:37	185	71	Espèces	REC-2026-U5OKPD
327	2026-05-01	10000.00	2026-05-01 10:25:30	2026-05-01 10:25:30	186	74	Espèces	REC-2026-XKSJ3V
328	2026-05-01	10000.00	2026-05-01 10:27:46	2026-05-01 10:27:46	187	74	Espèces	REC-2026-XWFL6H
329	2026-05-10	3000.00	2026-05-10 20:23:01	2026-05-10 20:23:01	188	85	Espèces	REC-2026-ROLMSC
330	2026-05-10	5000.00	2026-05-10 20:59:23	2026-05-10 20:59:23	189	79	Espèce	REC-20260510-0189
331	2026-05-10	5000.00	2026-05-10 21:55:25	2026-05-10 21:55:25	190	57	Espèce	REC-20260510-0190
332	2026-05-10	10000.00	2026-05-10 21:56:08	2026-05-10 21:56:08	191	99	Espèces	REC-2026-ULP0QL
341	2026-06-02	1000.00	2026-06-02 13:40:26	2026-06-02 13:40:26	200	79	Espèces	REC-2026-EO4IDM
342	2026-06-08	10000.00	2026-06-08 17:57:46	2026-06-08 17:57:46	213	57	Espèces	REC-2026-KGA5SH
343	2026-06-29	12000.00	2026-06-29 00:34:52	2026-06-29 00:34:52	234	74	Chèque	REC-20260629-0234
344	2026-06-04	15000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	202	57	Espèces	LOT-OFA29SKD-20260604
345	2026-06-04	2000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	203	57	Espèces	LOT-OFA29SKD-20260604
346	2026-06-04	7000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	204	85	Mobile Money	LOT-XPTSHAQZ-20260604
347	2026-06-04	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	205	56	Chèque	LOT-SM6FUNWO-20260604
348	2026-06-04	3000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	206	56	Chèque	LOT-SM6FUNWO-20260604
349	2026-06-04	5000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	207	58	Chèque	LOT-CVDBLTRM-20260604
350	2026-06-04	2000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	209	58	Chèque	LOT-CVDBLTRM-20260604
351	2026-06-05	20000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	210	58	Espèces	LOT-IIV9SKJ8-20260605
352	2026-06-05	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	211	56	Espèces	LOT-POESMPEV-20260605
353	2026-06-08	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	214	70	Espèces	LOT-O3FDJ2ZE-20260608
354	2026-06-08	3000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	215	79	Espèces	LOT-EQO8CFF3-20260608
355	2026-06-08	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	216	79	Espèces	LOT-N2SDA3CA-20260608
356	2026-06-09	20000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	217	86	Chèque	LOT-0MY8QWTG-20260609
357	2026-06-09	5000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	218	89	Chèque	LOT-ZQRGXYZQ-20260609
358	2026-06-09	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	219	80	Espèces	LOT-4WXQVWNS-20260609
359	2026-06-09	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	220	99	Espèces	LOT-2KQDPOXK-20260609
360	2026-06-09	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	221	73	Espèces	LOT-ODNIKQFP-20260609
361	2026-06-09	3000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	222	73	Espèces	LOT-ODNIKQFP-20260609
362	2026-06-10	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	223	92	Espèces	LOT-0HMEQK59-20260610
363	2026-06-10	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	224	93	Espèces	LOT-4NYDDWHK-20260610
364	2026-06-10	30000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	225	94	Espèces	LOT-2MA9JTYZ-20260610
365	2026-06-10	2000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	226	94	Espèces	LOT-2MA9JTYZ-20260610
366	2026-06-10	2000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	227	94	Espèces	LOT-2MA9JTYZ-20260610
367	2026-06-11	30000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	228	95	Espèces	LOT-4H1LOIME-20260611
368	2026-06-11	5000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	229	95	Espèces	LOT-4H1LOIME-20260611
369	2026-06-11	2000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	230	95	Espèces	LOT-4H1LOIME-20260611
370	2026-06-11	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	231	91	Espèces	LOT-E04VVQYE-20260611
371	2026-06-14	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	232	71	Chèque	LOT-IK8MXB59-20260614
372	2026-06-19	10000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	233	58	Espèces	LOT-S9HJQQMP-20260619
373	2026-06-29	7000.00	2026-06-29 01:08:08	2026-06-29 01:08:08	235	71	Mobile Money	LOT-PNFHHRBF-20260629
374	2026-07-06	10000.00	2026-07-06 10:55:40	2026-07-06 10:55:40	236	70	Chèque	LOT-HBINVXDI-20260706
\.


--
-- Data for Name: repartitions; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.repartitions (id, benefice_id, investissement_id, pourcentage, montant, statut, observation, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: retraits_capital; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.retraits_capital (id, investissement_id, date_retrait, montant, mode_retrait, reference, motif, observation, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
1	1
2	1
3	1
4	1
5	1
6	1
7	1
8	1
9	1
10	1
11	1
12	1
13	1
14	1
15	1
1	2
7	2
12	2
13	2
10	2
1	3
7	3
6	3
7	4
6	4
1	5
10	6
11	6
1	7
2	7
8	7
12	8
7	8
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.roles (id, created_at, updated_at, name, guard_name) FROM stdin;
1	2026-01-08 08:47:03	2026-01-08 08:47:03	admin	web
2	2026-01-08 08:47:03	2026-01-08 08:47:03	directeur	web
3	2026-01-08 08:47:03	2026-01-08 08:47:03	censeur	web
4	2026-01-08 08:47:03	2026-01-08 08:47:03	enseignant	web
5	2026-01-08 08:47:03	2026-01-08 08:47:03	surveillant	web
6	2026-01-08 08:47:03	2026-01-08 08:47:03	comptable	web
7	2026-01-08 08:47:03	2026-01-08 08:47:03	secretaire	web
8	2026-01-08 08:47:03	2026-01-08 08:47:03	parent	web
\.


--
-- Data for Name: scolarites; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.scolarites (id, classe, inscription, montant, mpaye, reste, inscription_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
JiPmV2bReklGG7QGJawgYbNle8cdUWCo6NI09Bmm	37	10.41.91.122	Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:151.0) Gecko/20100101 Firefox/151.0	YTo0OntzOjY6Il90b2tlbiI7czo0MDoibkJUWUNtcFZjZTk4Q0xHcUk1dTBBSERNbk0zWHoyVGM4UzlRZWpsdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMC40MS45MS4yMjk6ODAwMC9ub3Rlcy9pbXBvcnQyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzc7fQ==	1783807689
XKNAKT8nWERv5OBPE4XUUwES2ZNagmL8urVNa28W	37	127.0.0.1	Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMkY4SE1aRjdlUnJETmNDaThwcEZ3STZnUlc1UVNoa1d1SERKSXlkaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC92ZXJzZW1lbnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzc7fQ==	1783810145
\.


--
-- Data for Name: td_modes_paiements; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.td_modes_paiements (id, eleve_id, annee_id, mode_paiement, created_at, updated_at) FROM stdin;
1	22	2	seance	2026-06-13 10:38:27	2026-06-13 10:38:27
2	23	2	seance	2026-06-13 10:38:27	2026-06-13 10:38:27
3	24	2	seance	2026-06-13 10:38:27	2026-06-13 10:38:27
4	45	2	seance	2026-06-13 10:38:27	2026-06-13 10:38:27
5	25	2	seance	2026-06-13 10:38:27	2026-06-13 10:38:27
6	26	2	seance	2026-06-13 10:38:27	2026-06-13 10:38:27
7	27	2	seance	2026-06-13 10:38:27	2026-06-13 10:38:27
8	28	2	seance	2026-06-13 10:38:27	2026-06-13 10:38:27
9	1	5	seance	2026-06-13 13:55:27	2026-06-13 13:55:27
10	2	5	seance	2026-06-13 13:55:27	2026-06-13 13:55:27
11	3	5	seance	2026-06-13 13:55:27	2026-06-13 13:55:27
12	4	5	seance	2026-06-13 13:55:27	2026-06-13 13:55:27
\.


--
-- Data for Name: td_paiements; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.td_paiements (id, eleve_id, annee_id, montant, date_paiement, reference, observation, created_at, updated_at) FROM stdin;
2	15	2	5000.00	2025-10-20	\N	\N	2026-06-14 19:01:52	2026-06-14 19:01:52
\.


--
-- Data for Name: td_presences; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.td_presences (id, td_seance_id, eleve_id, present, created_at, updated_at) FROM stdin;
8	6	15	t	2026-06-13 22:57:41	2026-06-13 22:57:41
9	6	16	t	2026-06-13 22:57:41	2026-06-13 23:08:36
10	6	17	t	2026-06-13 22:57:41	2026-06-13 23:08:36
11	6	18	t	2026-06-13 22:57:41	2026-06-13 23:08:36
12	6	19	t	2026-06-13 22:57:41	2026-06-13 23:08:36
13	6	20	t	2026-06-13 22:57:41	2026-06-13 23:08:36
14	6	21	t	2026-06-13 22:57:41	2026-06-13 23:08:36
22	19	1	t	2026-06-14 14:50:10	2026-06-14 14:50:10
23	19	2	t	2026-06-14 14:50:10	2026-06-14 14:50:10
24	19	3	t	2026-06-14 14:50:10	2026-06-14 14:50:10
25	19	4	t	2026-06-14 14:50:10	2026-06-14 14:50:10
26	20	15	t	2026-06-14 15:11:25	2026-06-14 15:11:25
27	20	16	t	2026-06-14 15:11:25	2026-06-14 15:11:25
28	20	17	t	2026-06-14 15:11:25	2026-06-14 15:11:25
29	20	18	t	2026-06-14 15:11:25	2026-06-14 15:11:25
30	20	19	t	2026-06-14 15:11:25	2026-06-14 15:11:25
31	20	20	t	2026-06-14 15:11:25	2026-06-14 15:11:25
32	20	21	f	2026-06-14 15:11:25	2026-06-14 15:11:25
33	16	15	t	2026-06-14 15:17:20	2026-06-14 15:17:20
34	16	16	t	2026-06-14 15:17:20	2026-06-14 15:17:20
35	16	17	t	2026-06-14 15:17:20	2026-06-14 15:17:20
36	16	18	t	2026-06-14 15:17:20	2026-06-14 15:17:20
37	16	19	t	2026-06-14 15:17:20	2026-06-14 15:17:20
38	16	20	f	2026-06-14 15:17:20	2026-06-14 15:17:20
39	16	21	f	2026-06-14 15:17:20	2026-06-14 15:17:20
\.


--
-- Data for Name: td_seances; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.td_seances (id, annee_id, classe_id, date, libelle, created_at, updated_at) FROM stdin;
6	2	2	2026-05-30	HG- CE	2026-06-13 22:53:24	2026-06-13 22:53:24
7	2	1	2025-10-04	PCT- LECTURE	2026-06-14 11:58:18	2026-06-14 11:58:18
8	2	2	2025-10-04	PCT - LECTURE	2026-06-14 11:59:06	2026-06-14 11:59:06
9	2	3	2025-10-04	PCT - LECTURE	2026-06-14 11:59:54	2026-06-14 11:59:54
10	2	4	2025-10-04	PCT - LECTURE	2026-06-14 12:00:37	2026-06-14 12:00:37
11	2	1	2025-10-11	MATHS - ANGLAIS	2026-06-14 12:01:37	2026-06-14 12:01:37
12	2	2	2025-10-11	MATHS - ANGLAIS	2026-06-14 12:16:32	2026-06-14 12:16:32
15	2	1	2025-10-18	CE - ANGLAIS	2026-06-14 12:19:01	2026-06-14 12:19:01
18	2	4	2025-10-18	CE - ANGLAIS	2026-06-14 12:20:58	2026-06-14 13:19:22
16	2	2	2025-10-18	CE - ANGLAIS	2026-06-14 12:19:40	2026-06-14 13:19:48
17	2	3	2025-10-18	CE - ANGLAIS	2026-06-14 12:20:24	2026-06-14 13:19:59
19	2	1	2025-10-25	PCT - HG	2026-06-14 13:50:21	2026-06-14 13:50:21
20	2	2	2025-10-25	PCT - HG	2026-06-14 13:54:19	2026-06-14 13:54:19
21	2	3	2025-10-25	PCT - HG	2026-06-14 13:55:28	2026-06-14 13:55:28
22	2	4	2025-10-14	PCT - HG	2026-06-14 13:55:57	2026-06-14 13:55:57
13	2	3	2025-10-11	MATHS -  ANGLAIS	2026-06-14 12:17:20	2026-06-14 14:05:02
14	2	4	2025-10-11	MATHS -  ANGLAIS	2026-06-14 12:18:07	2026-06-14 14:05:26
\.


--
-- Data for Name: td_tarifs; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.td_tarifs (id, annee_id, categorie, type, montant, created_at, updated_at) FROM stdin;
1	2	intermediaire	seance	1000.00	2026-06-13 21:50:50	2026-06-13 21:50:50
2	2	3eme	mois	5000.00	2026-06-13 21:51:16	2026-06-13 21:51:16
3	2	3eme	annee	40000.00	2026-06-13 21:51:32	2026-06-13 21:51:32
4	2	terminale	mois	8000.00	2026-06-13 21:51:49	2026-06-13 21:51:49
5	2	terminale	annee	64000.00	2026-06-13 21:52:04	2026-06-13 21:52:04
\.


--
-- Data for Name: tests; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.tests (id, titre, matiere_id, classe_id, annee_id, fichier, created_at, updated_at, trimestre_id, date, hash, type) FROM stdin;
69	Premier devoir du troisieme trimestre	1	12	2	tests/devoir1_tr3_ang_2ndeCD.pdf	2026-04-01 22:59:26	2026-04-01 22:59:26	3	2026-04-01	Yhuw0EP9jMGG	devoir1
70	Premier devoir du troisieme trimestre	1	9	2	tests/devoir1_tr3_ang_2ndeCD.pdf	2026-04-01 22:59:26	2026-04-01 22:59:26	3	2026-04-01	jZTGI6IlBVRk	devoir1
71	Premier devoir du troisieme trimestre	1	4	2	tests/devoir1_tr3_ang_3ème.odt	2026-04-01 22:59:26	2026-04-01 22:59:26	3	2026-04-01	St4suFvh65k1	devoir1
72	Premier devoir du troisieme trimestre	1	4	2	tests/devoir1_tr3_ang_3ème.pdf	2026-04-01 22:59:26	2026-04-01 22:59:26	3	2026-04-01	R4GZ5DKmsQ1y	devoir1
73	Premier devoir du troisieme trimestre	1	3	2	tests/devoir1_tr3_ang_4ème.odt	2026-04-01 22:59:26	2026-04-01 22:59:26	3	2026-04-01	MMcZZKpAp2NF	devoir1
74	Premier devoir du troisieme trimestre	1	3	2	tests/devoir1_tr3_ang_4ème.pdf	2026-04-01 22:59:26	2026-04-01 22:59:26	3	2026-04-01	ykYZRe4uANJV	devoir1
75	Premier devoir du troisieme trimestre	1	2	2	tests/devoir1_tr3_ang_5ème.odt	2026-04-01 22:59:26	2026-04-01 22:59:26	3	2026-04-01	VDGaZ8C6tCzi	devoir1
76	Premier devoir du troisieme trimestre	1	2	2	tests/devoir1_tr3_ang_5ème.pdf	2026-04-01 22:59:26	2026-04-01 22:59:26	3	2026-04-01	7s29uKyeIQih	devoir1
77	Premier devoir du troisieme trimestre	1	1	2	tests/devoir1_tr3_ang_6ème.odt	2026-04-01 22:59:26	2026-04-01 22:59:26	3	2026-04-01	5njsomEFzCm0	devoir1
78	Premier devoir du troisieme trimestre	29	4	2	tests/devoir1_tr3_espa_3eme.odt	2026-04-01 23:00:55	2026-04-01 23:00:55	3	2026-04-01	WYW4zXsn6Znt	devoir1
79	Premier devoir du troisieme trimestre	29	4	2	tests/devoir1_tr3_espa_3eme.pdf	2026-04-01 23:00:55	2026-04-01 23:00:55	3	2026-04-01	nq9SPPFw7VDy	devoir1
80	Premier devoir du troisieme trimestre	29	3	2	tests/devoir1_tr3_espa_4eme.odt	2026-04-01 23:00:55	2026-04-01 23:00:55	3	2026-04-01	SXBOFgCke1jf	devoir1
81	Premier devoir du troisieme trimestre	29	3	2	tests/devoir1_tr3_espa_4eme.pdf	2026-04-01 23:00:55	2026-04-01 23:00:55	3	2026-04-01	1tIsLzxGf3yI	devoir1
82	Premier devoir du troisieme trimestre	2	4	2	tests/devoir1_tr3_com_3eme.odt	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	utl63qihUQiz	devoir1
83	Premier devoir du troisieme trimestre	2	4	2	tests/devoir1_tr3_com_3eme.pdf	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	Z8etHodhyswX	devoir1
84	Premier devoir du troisieme trimestre	2	3	2	tests/devoir1_tr3_com_4eme.odt	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	IDMjEpRf5KyP	devoir1
85	Premier devoir du troisieme trimestre	2	3	2	tests/devoir1_tr3_com_4eme.pdf	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	mMT2EkHW2mQv	devoir1
86	Premier devoir du troisieme trimestre	2	2	2	tests/devoir1_tr3_com_5ème.odt	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	pc7f7Bqp76yz	devoir1
87	Premier devoir du troisieme trimestre	2	2	2	tests/devoir1_tr3_com_5ème.pdf	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	Pn5oiwuu6sqZ	devoir1
88	Premier devoir du troisieme trimestre	2	1	2	tests/devoir1_tr3_com_6ème.odt	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	kPCpLbbluJpw	devoir1
89	Premier devoir du troisieme trimestre	2	1	2	tests/devoir1_tr3_com_6ème.pdf	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	WUem66Evo8vM	devoir1
90	Premier devoir du troisieme trimestre	35	12	2	tests/devoir1_tr3_fran_2ndeCD.odt	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	aS2F7DHs73Dj	devoir1
91	Premier devoir du troisieme trimestre	35	9	2	tests/devoir1_tr3_fran_2ndeCD.odt	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	hmFpF93TK7Hr	devoir1
92	Premier devoir du troisieme trimestre	35	12	2	tests/devoir1_tr3_fran_2ndeCD.pdf	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	Z5kFhJWWl5Uq	devoir1
93	Premier devoir du troisieme trimestre	35	9	2	tests/devoir1_tr3_fran_2ndeCD.pdf	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	TPuMRFMOEkXz	devoir1
94	Premier devoir du troisieme trimestre	3	4	2	tests/devoir1_tr3_lect_3ème.odt	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	WdmpQehnllBV	devoir1
95	Premier devoir du troisieme trimestre	3	4	2	tests/devoir1_tr3_lect_3ème.pdf	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	ss3RuXxffqdz	devoir1
96	Premier devoir du troisieme trimestre	3	3	2	tests/devoir1_tr3_lect_4ème.odt	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	n8TTdZb4kj9m	devoir1
97	Premier devoir du troisieme trimestre	3	3	2	tests/devoir1_tr3_lect_4ème.pdf	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	5C9NiR6AHVQe	devoir1
98	Premier devoir du troisieme trimestre	3	2	2	tests/devoir1_tr3_lect_5ème.odt	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	MDACmPvlIdUd	devoir1
99	Premier devoir du troisieme trimestre	3	2	2	tests/devoir1_tr3_lect_5ème.pdf	2026-04-01 23:07:08	2026-04-01 23:07:08	3	2026-04-01	TDA9U3R8ionB	devoir1
100	Premier devoir du troisieme trimestre	35	11	2	tests/devoir1_tr3_fran_1ereCD.pdf	2026-04-02 01:38:16	2026-04-02 01:38:16	3	2026-04-02	rjo76ZrkimEU	devoir1
101	Premier devoir du troisieme trimestre	35	10	2	tests/devoir1_tr3_fran_1ereCD.pdf	2026-04-02 01:38:16	2026-04-02 01:38:16	3	2026-04-02	ppgcyXXXeEo1	devoir1
102	Premier devoir du troisieme trimestre	35	11	2	tests/devoir1_tr3_fran_1èreCD.odt	2026-04-02 01:38:16	2026-04-02 01:38:16	3	2026-04-02	Qar8MK0xbtmw	devoir1
103	Premier devoir du troisieme trimestre	35	10	2	tests/devoir1_tr3_fran_1èreCD.odt	2026-04-02 01:38:16	2026-04-02 01:38:16	3	2026-04-02	Jg188E7t7gkc	devoir1
104	Premier devoir du troisieme trimestre	4	11	2	tests/devoir1_tr3_hg_1ereCD.docx	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	kO02sd2U715i	devoir1
105	Premier devoir du troisieme trimestre	4	10	2	tests/devoir1_tr3_hg_1ereCD.docx	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	TZA9ycK7hT8R	devoir1
106	Premier devoir du troisieme trimestre	4	11	2	tests/devoir1_tr3_hg_1ereCD.pdf	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	dSdipTNM2ojs	devoir1
107	Premier devoir du troisieme trimestre	4	10	2	tests/devoir1_tr3_hg_1ereCD.pdf	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	lwVJq7ajN4pV	devoir1
108	Premier devoir du troisieme trimestre	4	12	2	tests/devoir1_tr3_hg_2ndeCD.docx	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	ygRByw6s399o	devoir1
109	Premier devoir du troisieme trimestre	4	9	2	tests/devoir1_tr3_hg_2ndeCD.docx	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	I8Uez8cCZe89	devoir1
110	Premier devoir du troisieme trimestre	4	12	2	tests/devoir1_tr3_hg_2ndeCD.pdf	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	We00OPm0nFfH	devoir1
111	Premier devoir du troisieme trimestre	4	9	2	tests/devoir1_tr3_hg_2ndeCD.pdf	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	7hrsUfhSYGCa	devoir1
112	Premier devoir du troisieme trimestre	4	4	2	tests/devoir1_tr3_hg_3eme.docx	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	yBmeEPzKYWaa	devoir1
113	Premier devoir du troisieme trimestre	4	4	2	tests/devoir1_tr3_hg_3eme.pdf	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	cHLTHqepc8i6	devoir1
114	Premier devoir du troisieme trimestre	4	3	2	tests/devoir1_tr3_hg_4eme.docx	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	x1TxyM6qeBfp	devoir1
115	Premier devoir du troisieme trimestre	4	3	2	tests/devoir1_tr3_hg_4eme.pdf	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	Gf3iyYHXTvVX	devoir1
116	Premier devoir du troisieme trimestre	4	2	2	tests/devoir1_tr3_hg_5eme.docx	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	lDnQbFUzXE7O	devoir1
117	Premier devoir du troisieme trimestre	4	2	2	tests/devoir1_tr3_hg_5eme.pdf	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	UHf3j0j7jTmJ	devoir1
118	Premier devoir du troisieme trimestre	4	1	2	tests/devoir1_tr3_hg_6eme.docx	2026-04-02 01:41:55	2026-04-02 01:41:55	2	2026-04-02	1mc6zQZKaEZC	devoir1
119	Premier devoir du troisieme trimestre	5	11	2	tests/devoir1_tr3_maths_1ereC.pdf	2026-04-02 01:42:54	2026-04-02 01:42:54	3	2026-04-02	q3eK6ixwvHu2	devoir1
120	Premier devoir du troisieme trimestre	5	10	2	tests/devoir1_tr3_maths_1ereD.pdf	2026-04-02 01:42:54	2026-04-02 01:42:54	3	2026-04-02	Pv8NsdAtfu0M	devoir1
121	Premier devoir du troisieme trimestre	5	9	2	tests/devoir1_tr3_maths_2ndeD.pdf	2026-04-02 01:42:54	2026-04-02 01:42:54	3	2026-04-02	q8HSVEaoikg4	devoir1
122	Premier devoir du troisieme trimestre	5	4	2	tests/devoir1_tr3_maths_3eme.pdf	2026-04-02 01:42:54	2026-04-02 01:42:54	3	2026-04-02	u1fmzQEu0cFK	devoir1
123	Premier devoir du troisieme trimestre	5	3	2	tests/devoir1_tr3_maths_4eme.pdf	2026-04-02 01:42:54	2026-04-02 01:42:54	3	2026-04-02	ICteYepARZ8S	devoir1
124	Premier devoir du troisieme trimestre	5	2	2	tests/devoir1_tr3_maths_5eme.pdf	2026-04-02 01:42:54	2026-04-02 01:42:54	3	2026-04-02	ZaAbenMDF2zh	devoir1
125	Premier devoir du troisieme trimestre	5	1	2	tests/devoir1_tr3_maths_6eme.pdf	2026-04-02 01:42:54	2026-04-02 01:42:54	3	2026-04-02	MgRUQrXSSkwt	devoir1
126	Premier devoir du troisieme trimestre	6	11	2	tests/devoir1_tr3_pct_1ereCD.odt	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	trBOeF8G2iAV	devoir1
127	Premier devoir du troisieme trimestre	6	10	2	tests/devoir1_tr3_pct_1ereCD.odt	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	UaZKKbeYnUwv	devoir1
128	Premier devoir du troisieme trimestre	6	9	2	tests/devoir1_tr3_pct_2ndeD.odt	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	6YAYvqoFgibK	devoir1
129	Premier devoir du troisieme trimestre	6	9	2	tests/devoir1_tr3_pct_2ndeD.pdf	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	Wq03Suu3oJPC	devoir1
130	Premier devoir du troisieme trimestre	6	4	2	tests/devoir1_tr3_pct_3eme.pdf	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	5fIIzP1CvedO	devoir1
131	Premier devoir du troisieme trimestre	6	3	2	tests/devoir1_tr3_pct_4eme.odt	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	FWI8uOa1hepl	devoir1
132	Premier devoir du troisieme trimestre	6	3	2	tests/devoir1_tr3_pct_4eme.pdf	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	tOVRtoxrwswQ	devoir1
133	Premier devoir du troisieme trimestre	6	2	2	tests/devoir1_tr3_pct_5eme_25-26.odt	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	TH5LSw7HFP1v	devoir1
134	Premier devoir du troisieme trimestre	6	2	2	tests/devoir1_tr3_pct_5eme_25-26.pdf	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	feWyEjqEAPOW	devoir1
135	Premier devoir du troisieme trimestre	6	1	2	tests/devoir1_tr3_pct_6eme.pdf	2026-04-02 01:44:51	2026-04-02 01:44:51	3	2026-04-02	414EWvTxaoxp	devoir1
136	Premier devoir du troisieme trimestre	37	11	2	tests/devoir1_tr3_philo_1ereCD.odt	2026-04-02 01:46:16	2026-04-02 01:46:16	3	2026-04-02	GUm77sUr3JUj	devoir1
137	Premier devoir du troisieme trimestre	37	10	2	tests/devoir1_tr3_philo_1ereCD.odt	2026-04-02 01:46:16	2026-04-02 01:46:16	3	2026-04-02	4rrVFQsNHc02	devoir1
138	Premier devoir du troisieme trimestre	37	11	2	tests/devoir1_tr3_philo_1ereCD.pdf	2026-04-02 01:46:16	2026-04-02 01:46:16	3	2026-04-02	5WykqHi5Fo5w	devoir1
139	Premier devoir du troisieme trimestre	37	10	2	tests/devoir1_tr3_philo_1ereCD.pdf	2026-04-02 01:46:16	2026-04-02 01:46:16	3	2026-04-02	ZNp9zrtrpjFo	devoir1
140	Premier devoir du troisieme trimestre	37	12	2	tests/devoir1_tr3_philo_2ndeCD.odt	2026-04-02 01:46:16	2026-04-02 01:46:16	3	2026-04-02	pJlMEMf2NfyF	devoir1
141	Premier devoir du troisieme trimestre	37	9	2	tests/devoir1_tr3_philo_2ndeCD.odt	2026-04-02 01:46:16	2026-04-02 01:46:16	3	2026-04-02	HsS4SPPIipjQ	devoir1
142	Premier devoir du troisieme trimestre	37	12	2	tests/devoir1_tr3_philo_2ndeCD.pdf	2026-04-02 01:46:16	2026-04-02 01:46:16	3	2026-04-02	RqTLLysdQaFQ	devoir1
143	Premier devoir du troisieme trimestre	37	9	2	tests/devoir1_tr3_philo_2ndeCD.pdf	2026-04-02 01:46:16	2026-04-02 01:46:16	3	2026-04-02	caSBvvjvoQVO	devoir1
144	Premier devoir du troisieme trimestre	7	9	2	tests/Devoir1_tr3_svt_2ndeD.docx	2026-04-02 01:48:06	2026-04-02 01:48:06	3	2026-04-02	DsZNAlpnBBHg	devoir1
145	Premier devoir du troisieme trimestre	7	9	2	tests/Devoir1_tr3_svt_2ndeD.pdf	2026-04-02 01:48:06	2026-04-02 01:48:06	3	2026-04-02	sy2QffA2aw8F	devoir1
146	Premier devoir du troisieme trimestre	7	4	2	tests/devoir1_tr3_svt_3eme.pdf	2026-04-02 01:48:06	2026-04-02 01:48:06	3	2026-04-02	f2Lau8VSYK6z	devoir1
147	Premier devoir du troisieme trimestre	7	3	2	tests/Devoir1_tr3_svt_4eme.docx	2026-04-02 01:48:06	2026-04-02 01:48:06	3	2026-04-02	1exsQX1UBKVh	devoir1
148	Premier devoir du troisieme trimestre	7	3	2	tests/Devoir1_tr3_svt_4eme.pdf	2026-04-02 01:48:06	2026-04-02 01:48:06	3	2026-04-02	tKwC7iYQjVyG	devoir1
149	Premier devoir du troisieme trimestre	7	2	2	tests/Devoir1_tr3_svt_5eme.docx	2026-04-02 01:48:06	2026-04-02 01:48:06	3	2026-04-02	5hhIuxC3BW0q	devoir1
150	Premier devoir du troisieme trimestre	7	2	2	tests/Devoir1_tr3_svt_5eme.pdf	2026-04-02 01:48:06	2026-04-02 01:48:06	3	2026-04-02	2lI3CTLGSNAY	devoir1
151	Premier devoir du troisieme trimestre	7	1	2	tests/Devoir1_tr3_svt_6eme.docx	2026-04-02 01:48:06	2026-04-02 01:48:06	3	2026-04-02	iYt1iwFqFx8f	devoir1
152	Premier devoir du troisieme trimestre	7	1	2	tests/Devoir1_tr3_svt_6eme.pdf	2026-04-02 01:48:06	2026-04-02 01:48:06	3	2026-04-02	OIQriheuKNv5	devoir1
153	Premier Devoir du premier Trimestre	1	12	2	tests/devoir1_ang_2ndeC_24-25.pdf	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	g1TiZfhnRHHG	devoir1
154	Premier Devoir du premier Trimestre	1	9	2	tests/devoir1_ang_2ndeD_24-25.odt	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	f4Znd43v892V	devoir1
155	Premier Devoir du premier Trimestre	1	4	2	tests/devoir1_ang_3ème_25-26.odt	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	GEdiXHsSTqR5	devoir1
156	Premier Devoir du premier Trimestre	1	4	2	tests/devoir1_ang_3ème_25-26.pdf	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	GE347SKRt2iz	devoir1
157	Premier Devoir du premier Trimestre	1	3	2	tests/devoir1_ang_4ème_25-26.odt	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	DjywtAEC9LEP	devoir1
158	Premier Devoir du premier Trimestre	1	3	2	tests/devoir1_ang_4ème_25-26.pdf	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	Tw08eJrdAAl6	devoir1
159	Premier Devoir du premier Trimestre	1	2	2	tests/devoir1_ang_5ème_25-26.odt	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	61qCzkNDikns	devoir1
160	Premier Devoir du premier Trimestre	1	2	2	tests/devoir1_ang_5ème_25-26.pdf	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	soFFUwNRmtJF	devoir1
161	Premier Devoir du premier Trimestre	1	1	2	tests/devoir1_ang_6ème_25-26.odt	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	gYRrRe65EJwi	devoir1
162	Premier Devoir du premier Trimestre	1	1	2	tests/devoir1_ang_6ème_25-26.pdf	2026-04-06 23:52:32	2026-04-06 23:52:32	1	2026-04-06	MJgUBE1tTCn0	devoir1
163	Premier Devoir du premier Trimestre	29	4	2	tests/devoir1_espa-3ème_25-26.odt	2026-04-07 00:06:28	2026-04-07 00:06:28	1	2026-04-06	WYJUGrZADDap	devoir1
164	Premier Devoir du premier Trimestre	29	4	2	tests/devoir1_espa-3ème_25-26.pdf	2026-04-07 00:06:28	2026-04-07 00:06:28	1	2026-04-06	DSDQoLdDb2l9	devoir1
165	Premier Devoir du premier Trimestre	29	3	2	tests/devoir1_espa-4ème_25-26.odt	2026-04-07 00:06:28	2026-04-07 00:06:28	1	2026-04-06	70naA3bm251V	devoir1
166	Premier Devoir du premier Trimestre	29	3	2	tests/devoir1_espa-4ème_25-26.pdf	2026-04-07 00:06:28	2026-04-07 00:06:28	1	2026-04-06	1jhOBL139qqX	devoir1
167	Premier Devoir du premier Trimestre	2	4	2	tests/devoir1_com_3ème_25-26.odt	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	NrCXq7s07PKP	devoir1
168	Premier Devoir du premier Trimestre	2	4	2	tests/devoir1_com_3ème_25-26.pdf	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	yvCH7Hpz8fQr	devoir1
169	Premier Devoir du premier Trimestre	2	3	2	tests/devoir1_com_4ème_25-26.odt	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	2l9zwmkuF4Dt	devoir1
170	Premier Devoir du premier Trimestre	2	3	2	tests/devoir1_com_4ème_25-26.pdf	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	nIo8AIEwtfrZ	devoir1
171	Premier Devoir du premier Trimestre	2	2	2	tests/devoir1_com_5ème_25-26.odt	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	5AUkiE5VLcq6	devoir1
172	Premier Devoir du premier Trimestre	2	2	2	tests/devoir1_com_5ème_25-26.pdf	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	z2hDO4kgky5C	devoir1
173	Premier Devoir du premier Trimestre	2	1	2	tests/devoir1_com_6ème_25-26.odt	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	HRqotMacHl2k	devoir1
174	Premier Devoir du premier Trimestre	2	1	2	tests/devoir1_com_6ème_25-26.pdf	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	g6btzStZeOOW	devoir1
175	Premier Devoir du premier Trimestre	3	4	2	tests/devoir1_lect_3ème_25-26.odt	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	3EcBGYofh1eS	devoir1
176	Premier Devoir du premier Trimestre	3	4	2	tests/devoir1_lect_3ème_25-26.pdf	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	Of3hRVDkmACa	devoir1
177	Premier Devoir du premier Trimestre	3	3	2	tests/devoir1_lect_4ème_25--26.odt	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	p47Go2IdQrBf	devoir1
178	Premier Devoir du premier Trimestre	3	3	2	tests/devoir1_lect_4ème_25--26.pdf	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	IhRzEWDQ7Six	devoir1
179	Premier Devoir du premier Trimestre	3	2	2	tests/devoir1_lect_5ème_25--26.odt	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	pJ5OaMViyFrI	devoir1
180	Premier Devoir du premier Trimestre	3	2	2	tests/devoir1_lect_5ème_25--26.pdf	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	d99oGx6YtjbZ	devoir1
181	Premier Devoir du premier Trimestre	3	1	2	tests/devoir1_lect_6ème_25-26.odt	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	P425kiAi9KLT	devoir1
182	Premier Devoir du premier Trimestre	3	1	2	tests/devoir1_lect_6ème_25-26.pdf	2026-04-07 00:14:48	2026-04-07 00:14:48	1	2026-04-07	NkbltcVia5V7	devoir1
183	Premier Devoir du premier Trimestre	4	11	2	tests/devoir1_1èreC_hg_25-26.pdf	2026-04-07 00:16:42	2026-04-07 00:16:42	1	2026-04-07	icSRWTWQpFAj	devoir1
184	Premier Devoir du premier Trimestre	4	10	2	tests/devoir1_1èreD_hg_25-26.odt	2026-04-07 00:16:42	2026-04-07 00:16:42	1	2026-04-07	dniNd871q5cv	devoir1
185	Premier Devoir du premier Trimestre	4	12	2	tests/devoir1_2ndeC_hg_25-26.odt	2026-04-07 00:16:42	2026-04-07 00:16:42	1	2026-04-07	YbSVi8vHvymb	devoir1
186	Premier Devoir du premier Trimestre	4	9	2	tests/devoir1_2ndeD_hg_25-26.pdf	2026-04-07 00:16:42	2026-04-07 00:16:42	1	2026-04-07	ZIq3AhT2TRfq	devoir1
187	Premier Devoir du premier Trimestre	4	2	2	tests/devoir1_5ème_hg_25-26.odt	2026-04-07 00:16:42	2026-04-07 00:16:42	1	2026-04-07	ucE3SPn7BNFr	devoir1
188	Premier Devoir du premier Trimestre	4	2	2	tests/devoir1_5ème_hg_25-26.pdf	2026-04-07 00:16:42	2026-04-07 00:16:42	1	2026-04-07	sAmEYvJ9PjyD	devoir1
189	Premier Devoir du premier Trimestre	4	1	2	tests/devoir1_6ème_hg_25-26.odt	2026-04-07 00:16:42	2026-04-07 00:16:42	1	2026-04-07	u7Wxqfgetzn7	devoir1
190	Premier Devoir du premier Trimestre	4	1	2	tests/devoir1_6ème_hg_25-26.pdf	2026-04-07 00:16:42	2026-04-07 00:16:42	1	2026-04-07	s999kBNK57Os	devoir1
191	Premier Devoir du premier Trimestre	4	3	2	tests/devoir1_hg_4eme_25-26.pdf	2026-04-07 00:16:42	2026-04-07 00:16:42	1	2026-04-07	jeANkMNTdTHI	devoir1
192	Premier Devoir du premier Trimestre	5	12	2	tests/devoir1_math_2ndeC_24-25.pdf	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	xusGofazTmnX	devoir1
193	Premier Devoir du premier Trimestre	5	9	2	tests/devoir1_math_2ndeD_24-25.odt	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	QceDwpm5NKIY	devoir1
194	Premier Devoir du premier Trimestre	5	4	2	tests/devoir1_math_3ème_24-25.odt	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	0OWeJvaZU7lz	devoir1
195	Premier Devoir du premier Trimestre	5	4	2	tests/devoir1_math_3ème_24-25.pdf	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	wrYWXnYWJFfJ	devoir1
196	Premier Devoir du premier Trimestre	5	3	2	tests/devoir1_math_4ème_24-25.odt	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	aA4Je4V7SKuj	devoir1
197	Premier Devoir du premier Trimestre	5	3	2	tests/devoir1_math_4ème_24-25.pdf	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	15txoiYOTv5H	devoir1
198	Premier Devoir du premier Trimestre	5	2	2	tests/devoir1_math_5ème_24-25.odt	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	ytbXCcUjcPV7	devoir1
199	Premier Devoir du premier Trimestre	5	2	2	tests/devoir1_math_5ème_24-25.pdf	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	JYoX1nb1ItTW	devoir1
200	Premier Devoir du premier Trimestre	5	2	2	tests/devoir1_math_5ème_24-25mod.odt	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	NNi1lEiCGdyh	devoir1
201	Premier Devoir du premier Trimestre	5	2	2	tests/devoir1_math_5ème_24-25mod.pdf	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	pqCJ79YNR5I0	devoir1
202	Premier Devoir du premier Trimestre	5	1	2	tests/devoir1_math_6ème_24-25.odt	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	549lpAv4LKjw	devoir1
203	Premier Devoir du premier Trimestre	5	1	2	tests/devoir1_math_6ème_24-25.pdf	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	07ZUDVV5QPcx	devoir1
204	Premier Devoir du premier Trimestre	5	1	2	tests/devoir1_math_6ème_24-25mod.odt	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	dQh3MHKnC7gg	devoir1
205	Premier Devoir du premier Trimestre	5	1	2	tests/devoir1_math_6ème_24-25mod.pdf	2026-04-07 00:17:54	2026-04-07 00:17:54	1	2026-04-07	7vRgcOiJX0ze	devoir1
206	Premier Devoir du premier Trimestre	6	11	2	tests/devoir1_PCT_1ereC_25-26.docx	2026-04-07 00:18:45	2026-04-07 00:18:45	1	2026-04-07	wGg8S5mO3fHv	devoir1
207	Premier Devoir du premier Trimestre	6	11	2	tests/devoir1_pct_1èreC_24-25.pdf	2026-04-07 00:18:45	2026-04-07 00:18:45	1	2026-04-07	aTF3FJN8PpDr	devoir1
208	Premier Devoir du premier Trimestre	6	10	2	tests/devoir1_PCT_1ereD_25-26.docx	2026-04-07 00:18:45	2026-04-07 00:18:45	1	2026-04-07	LZ9SzoQqO8Vs	devoir1
209	Premier Devoir du premier Trimestre	6	10	2	tests/devoir1_PCT_1ereD_25-26_mod.docx	2026-04-07 00:18:45	2026-04-07 00:18:45	1	2026-04-07	7OCIZDHy7p09	devoir1
210	Premier Devoir du premier Trimestre	6	10	2	tests/devoir1_PCT_1ereD_25-26_mod.pdf	2026-04-07 00:18:45	2026-04-07 00:18:45	1	2026-04-07	dB4H8G6dyvMy	devoir1
211	Premier Devoir du premier Trimestre	6	2	2	tests/devoir1_pct_5ème_25-26.odt	2026-04-07 00:18:45	2026-04-07 00:18:45	1	2026-04-07	dQ4xNmiQuJCf	devoir1
212	Premier Devoir du premier Trimestre	6	2	2	tests/devoir1_pct_5ème_25-26.pdf	2026-04-07 00:18:45	2026-04-07 00:18:45	1	2026-04-07	vJuBnmgrFmse	devoir1
213	Premier Devoir du premier Trimestre	37	11	2	tests/devoir1_philo_1èreC_25-26.pdf	2026-04-07 00:19:28	2026-04-07 00:19:28	1	2026-04-07	L9nS4Z1vXG9z	devoir1
214	Premier Devoir du premier Trimestre	37	10	2	tests/devoir1_philo_1èreD_25-26.odt	2026-04-07 00:19:28	2026-04-07 00:19:28	1	2026-04-07	EpALcCeNgbg2	devoir1
215	Premier Devoir du premier Trimestre	37	9	2	tests/devoir1_philo_2ndeD_25-26.odt	2026-04-07 00:19:28	2026-04-07 00:19:28	1	2026-04-07	KsOelNObz8zS	devoir1
216	Premier Devoir du premier Trimestre	37	9	2	tests/devoir1_philo_2ndeD_25-26.pdf	2026-04-07 00:19:28	2026-04-07 00:19:28	1	2026-04-07	MDV6KiZRq6UJ	devoir1
217	Premier Devoir du premier Trimestre	7	2	2	tests/devoir1_svt_5ème_25-26.odt	2026-04-07 00:20:26	2026-04-07 00:20:26	1	2026-04-07	5pqU9xzEWUWQ	devoir1
218	Premier Devoir du premier Trimestre	7	2	2	tests/devoir1_svt_5ème_25-26.pdf	2026-04-07 00:20:26	2026-04-07 00:20:26	1	2026-04-07	ghFhT6X3Qg90	devoir1
219	Premier Devoir du premier Trimestre	7	1	2	tests/devoir1_svt_6ème_25-26.odt	2026-04-07 00:20:26	2026-04-07 00:20:26	1	2026-04-07	2K9AXuCNZsDM	devoir1
220	Premier Devoir du premier Trimestre	7	1	2	tests/devoir1_svt_6ème_25-26.pdf	2026-04-07 00:20:26	2026-04-07 00:20:26	1	2026-04-07	MA59aERsXjcV	devoir1
221	Deuxieme devoir du Troisieme trimestre	100	11	2	tests/devoir2_tr3_fran_1ereCD.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	m8pwV8aOARze	devoir2
222	Deuxieme devoir du Troisieme trimestre	100	10	2	tests/devoir2_tr3_fran_1ereCD.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	7fXEnWq3xbF2	devoir2
223	Deuxieme devoir du Troisieme trimestre	100	11	2	tests/devoir2_tr3_fran_1ereCD.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	xygaQyvCD9bA	devoir2
224	Deuxieme devoir du Troisieme trimestre	100	10	2	tests/devoir2_tr3_fran_1ereCD.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	6Cm6AifRy1pJ	devoir2
225	Deuxieme devoir du Troisieme trimestre	100	9	2	tests/devoir2_tr3_fran_2ndeD.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	NXwsfNXy5ytL	devoir2
226	Deuxieme devoir du Troisieme trimestre	100	9	2	tests/devoir2_tr3_fran_2ndeD.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	m2oORiyFtMqZ	devoir2
227	Deuxieme devoir du Troisieme trimestre	2	2	2	tests/devoir2_tr3_com_5eme_25-26.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	n0RZKkni4xyx	devoir2
228	Deuxieme devoir du Troisieme trimestre	2	2	2	tests/devoir2_tr3_com_5eme_25-26.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	Q8jHwz47t8ho	devoir2
229	Deuxieme devoir du Troisieme trimestre	2	3	2	tests/devoir2_tr3_com_4eme_25-26.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	Kenl33XqNmBb	devoir2
230	Deuxieme devoir du Troisieme trimestre	2	3	2	tests/devoir2_tr3_com_4eme_25-26.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	tP2fi0YvFK4c	devoir2
231	Deuxieme devoir du Troisieme trimestre	2	1	2	tests/devoir2_tr3_com_6eme_25-26.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	xZ11U6Ax4ZhL	devoir2
232	Deuxieme devoir du Troisieme trimestre	2	1	2	tests/devoir2_tr3_com_6eme_25-26.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	dnqVThCFQkJ8	devoir2
233	Deuxieme devoir du Troisieme trimestre	3	3	2	tests/devoir2_tr3_lect_4ème_25-26.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	NxN2OVFQg4ie	devoir2
234	Deuxieme devoir du Troisieme trimestre	3	3	2	tests/devoir2_tr3_lect_4ème_25-26.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	niMpMgTxcfgs	devoir2
235	Deuxieme devoir du Troisieme trimestre	3	2	2	tests/devoir2_tr3_lect_5ème_25-26.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	SkRLnmSOr2zh	devoir2
236	Deuxieme devoir du Troisieme trimestre	3	2	2	tests/devoir2_tr3_lect_5ème_25-26.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	IgrTjZkITrcj	devoir2
237	Deuxieme devoir du Troisieme trimestre	3	1	2	tests/devoir2_tr3_lect_6ème_25-26.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	7CFItL2m0aNi	devoir2
238	Deuxieme devoir du Troisieme trimestre	2	4	2	tests/devoir2_tr3_com_3eme_25-26.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	TlRqJzrN4ZQN	devoir2
239	Deuxieme devoir du Troisieme trimestre	2	4	2	tests/devoir2_tr3_com_3eme_25-26.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	FOS6Ruhpdl4r	devoir2
240	Deuxieme devoir du Troisieme trimestre	3	4	2	tests/devoir2_tr3_lect_3ème_25-26.odt	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	gGhm7Xy6C7Zo	devoir2
241	Deuxieme devoir du Troisieme trimestre	3	4	2	tests/devoir2_tr3_lect_3ème_25-26.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	iolVABqPN8i1	devoir2
242	Deuxieme devoir du Troisieme trimestre	3	1	2	tests/devoir2_tr3_lect_6ème_25-26.pdf	2026-05-17 15:37:11	2026-05-17 15:37:11	3	2026-05-17	1oiJBrJiXYdc	devoir2
243	Deuxieme devoir du Troisieme trimestre	5	2	2	tests/devoir2_tr3_maths_5eme.pdf	2026-05-17 15:38:56	2026-05-17 15:38:56	3	2026-05-17	IvXZ2BGal3m2	devoir2
244	Deuxieme devoir du Troisieme trimestre	5	1	2	tests/devoir2_tr3_maths_6eme.pdf	2026-05-17 15:38:56	2026-05-17 15:38:56	3	2026-05-17	vyEofwv7aWap	devoir2
245	Deuxieme devoir du Troisieme trimestre	5	3	2	tests/devoir2_tr3_maths_4eme.pdf	2026-05-17 15:38:56	2026-05-17 15:38:56	3	2026-05-17	RtPcpHK8cWED	devoir2
246	Deuxieme devoir du Troisieme trimestre	5	9	2	tests/devoir2_tr3_maths_2ndeD.pdf	2026-05-17 15:38:56	2026-05-17 15:38:56	3	2026-05-17	3RrqfOayMzoO	devoir2
247	Deuxieme devoir du Troisieme trimestre	5	10	2	tests/devoir2_tr3_maths_1ereD.pdf	2026-05-17 15:38:56	2026-05-17 15:38:56	3	2026-05-17	VH50epwMFSk5	devoir2
248	Deuxieme devoir du Troisieme trimestre	5	11	2	tests/devoir2_tr3_maths_1ereC.pdf	2026-05-17 15:38:56	2026-05-17 15:38:56	3	2026-05-17	YSJZ0Y5KtThD	devoir2
249	Deuxieme devoir du Troisieme trimestre	5	4	2	tests/devoir2_tr3_math_3eme.odt	2026-05-17 15:38:56	2026-05-17 15:38:56	3	2026-05-17	QHJgrkJuMnYN	devoir2
250	Deuxieme devoir du Troisieme trimestre	5	4	2	tests/devoir2_tr3_math_3eme.pdf	2026-05-17 15:38:56	2026-05-17 15:38:56	3	2026-05-17	ojFd7Gnpc4H7	devoir2
251	Deuxieme devoir du Troisieme trimestre	7	3	2	tests/devoir2_tr3_svt_4eme_25-26.docx	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	KE9zIo2wa2b8	devoir2
252	Deuxieme devoir du Troisieme trimestre	7	3	2	tests/devoir2_tr3_svt_4eme_25-26.pdf	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	OCybjSB8mxK5	devoir2
253	Deuxieme devoir du Troisieme trimestre	7	9	2	tests/devoir2_tr3_svt_2ndeD_25-26.docx	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	kZOBpJPpcBqu	devoir2
254	Deuxieme devoir du Troisieme trimestre	7	9	2	tests/devoir2_tr3_svt_2ndeD_25-26.pdf	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	j7PN8r4Ray8Q	devoir2
255	Deuxieme devoir du Troisieme trimestre	7	2	2	tests/devoir2_tr3_svt_5eme_25-26.docx	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	17vTAxUoVmpM	devoir2
256	Deuxieme devoir du Troisieme trimestre	7	2	2	tests/devoir2_tr3_svt_5eme_25-26.pdf	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	3kpa3FvgukYX	devoir2
257	Deuxieme devoir du Troisieme trimestre	7	1	2	tests/devoir2_tr3_svt_6eme_25-26.docx	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	L4Udffsreg3Q	devoir2
258	Deuxieme devoir du Troisieme trimestre	7	1	2	tests/devoir2_tr3_svt_6eme_25-26.pdf	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	z0RJCHvKjyIM	devoir2
259	Deuxieme devoir du Troisieme trimestre	7	11	2	tests/devoir2_tr3_svt_1ereCD.odt	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	2Yf2eA5GpN87	devoir2
260	Deuxieme devoir du Troisieme trimestre	7	10	2	tests/devoir2_tr3_svt_1ereCD.odt	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	ptDyq9z1FnTp	devoir2
261	Deuxieme devoir du Troisieme trimestre	7	11	2	tests/devoir2_tr3_svt_1ereCD.pdf	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	EGDKyrdhWoDn	devoir2
262	Deuxieme devoir du Troisieme trimestre	7	10	2	tests/devoir2_tr3_svt_1ereCD.pdf	2026-05-17 15:40:42	2026-05-17 15:40:42	3	2026-05-17	44euMKzLKiHN	devoir2
263	Deuxieme devoir du Troisieme trimestre	6	2	2	tests/devoir2_tr3_pct_5ème_24-25.odt	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	1MeXdAKi2VLx	devoir2
264	Deuxieme devoir du Troisieme trimestre	6	2	2	tests/devoir2_tr3_pct_5ème_24-25.pdf	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	BvGKLs0FteQV	devoir2
265	Deuxieme devoir du Troisieme trimestre	6	1	2	tests/devoir2_tr3_pct_6ème_24-25.odt	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	b7dDs8OS4VdN	devoir2
266	Deuxieme devoir du Troisieme trimestre	6	1	2	tests/devoir2_tr3_pct_6ème_24-25.pdf	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	kG2cuNtObJBh	devoir2
267	Deuxieme devoir du Troisieme trimestre	6	4	2	tests/devoir2_tr3_pct_3eme.pdf	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	tm4aGbRitZ1Z	devoir2
268	Deuxieme devoir du Troisieme trimestre	6	12	2	tests/devoir2_tr3_pct_2ndeCD.odt	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	jvg5U683QiDS	devoir2
269	Deuxieme devoir du Troisieme trimestre	6	9	2	tests/devoir2_tr3_pct_2ndeCD.odt	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	yhxGC3Rp8REr	devoir2
270	Deuxieme devoir du Troisieme trimestre	6	12	2	tests/devoir2_tr3_pct_2ndeCD.pdf	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	J6Yly35zMN5c	devoir2
271	Deuxieme devoir du Troisieme trimestre	6	9	2	tests/devoir2_tr3_pct_2ndeCD.pdf	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	4z3FMhF0V4xL	devoir2
272	Deuxieme devoir du Troisieme trimestre	6	3	2	tests/devoir2_tr3_pct_4eme.odt	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	Cbb7UGONgViQ	devoir2
273	Deuxieme devoir du Troisieme trimestre	6	3	2	tests/devoir2_tr3_pct_4eme.pdf	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	ykcwIDCZnTgp	devoir2
274	Deuxieme devoir du Troisieme trimestre	6	11	2	tests/devoir2_tr3_pct_1ereCD_25-26.odt	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	QWouC0ZWkySX	devoir2
275	Deuxieme devoir du Troisieme trimestre	6	10	2	tests/devoir2_tr3_pct_1ereCD_25-26.odt	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	905EETi53oaa	devoir2
276	Deuxieme devoir du Troisieme trimestre	6	11	2	tests/devoir2_tr3_pct_1ereCD_25-26.pdf	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	AOeV40r1Qawr	devoir2
277	Deuxieme devoir du Troisieme trimestre	6	10	2	tests/devoir2_tr3_pct_1ereCD_25-26.pdf	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	R56wEGeLw8eJ	devoir2
278	Deuxieme devoir du Troisieme trimestre	6	4	2	tests/devoir2_tr3_pct_3eme.odt	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	c7pTcjKhJjtW	devoir2
279	Deuxieme devoir du Troisieme trimestre	6	4	2	tests/devoir2_tr3_pct_3eme.docx	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	eF2G3mLrLRbZ	devoir2
280	Deuxieme devoir du Troisieme trimestre	6	1	2	tests/devoir2_tr3_pct_6ème_24-25.docx	2026-05-17 15:42:17	2026-05-17 15:42:17	3	2026-05-17	TWePfzz5BmbM	devoir2
281	Deuxieme devoir du Troisieme trimestre	4	11	2	tests/devoir2_tr2_hg_1ereCD_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	VdJkkZoi3eQl	devoir2
282	Deuxieme devoir du Troisieme trimestre	4	10	2	tests/devoir2_tr2_hg_1ereCD_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	XxXMbcyth5Gg	devoir2
283	Deuxieme devoir du Troisieme trimestre	4	2	2	tests/devoir2_tr2_hg_5eme_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	eOrMdtlHvTl0	devoir2
284	Deuxieme devoir du Troisieme trimestre	4	12	2	tests/devoir2_tr2_hg_2ndeCD_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	CiRaKUkc8ZDf	devoir2
285	Deuxieme devoir du Troisieme trimestre	4	9	2	tests/devoir2_tr2_hg_2ndeCD_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	WWxhXMBNHAny	devoir2
286	Deuxieme devoir du Troisieme trimestre	4	2	2	tests/devoir2_tr3_hg_5eme_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	2UO58nq18YEb	devoir2
287	Deuxieme devoir du Troisieme trimestre	4	2	2	tests/devoir2_tr3_hg_5eme_25-26.pdf	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	2HSBQd4LHb9p	devoir2
288	Deuxieme devoir du Troisieme trimestre	4	1	2	tests/devoir2_tr2_hg_6eme_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	nyVXMqlT7JTb	devoir2
289	Deuxieme devoir du Troisieme trimestre	4	1	2	tests/devoir2_tr3_hg_6eme_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	MQiHLPqdFOeh	devoir2
290	Deuxieme devoir du Troisieme trimestre	4	1	2	tests/devoir2_tr3_hg_6eme_25-26.pdf	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	Hq5idL9N9H3O	devoir2
291	Deuxieme devoir du Troisieme trimestre	4	12	2	tests/devoir2_tr3_hg_2ndeCD_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	1A5kP0AcW1jE	devoir2
292	Deuxieme devoir du Troisieme trimestre	4	9	2	tests/devoir2_tr3_hg_2ndeCD_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	UTbYMFOXEMw8	devoir2
293	Deuxieme devoir du Troisieme trimestre	4	12	2	tests/devoir2_tr3_hg_2ndeCD_25-26.pdf	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	ppkJSLCOPZbK	devoir2
294	Deuxieme devoir du Troisieme trimestre	4	9	2	tests/devoir2_tr3_hg_2ndeCD_25-26.pdf	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	cImAbG8RrfPH	devoir2
295	Deuxieme devoir du Troisieme trimestre	4	11	2	tests/devoir2_tr3_hg_1ereCD_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	cD3dXudU3FP7	devoir2
296	Deuxieme devoir du Troisieme trimestre	4	10	2	tests/devoir2_tr3_hg_1ereCD_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	WlGptJzHQKb5	devoir2
297	Deuxieme devoir du Troisieme trimestre	4	11	2	tests/devoir2_tr3_hg_1ereCD_25-26.pdf	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	XOjEiFvUTcPt	devoir2
298	Deuxieme devoir du Troisieme trimestre	4	10	2	tests/devoir2_tr3_hg_1ereCD_25-26.pdf	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	1H4GDtAyE21W	devoir2
299	Deuxieme devoir du Troisieme trimestre	4	4	2	tests/devoir2_tr3_hg_3eme_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	Is4OOHW9jkHr	devoir2
300	Deuxieme devoir du Troisieme trimestre	4	4	2	tests/devoir2_tr3_hg_3eme_25-26.pdf	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	soCqDlNnD5VR	devoir2
301	Deuxieme devoir du Troisieme trimestre	4	3	2	tests/devoir2_tr3_hg_4eme_25-26.docx	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	fYM42EGKsgP2	devoir2
302	Deuxieme devoir du Troisieme trimestre	4	3	2	tests/devoir2_tr3_hg_4eme_25-26.pdf	2026-05-17 15:43:59	2026-05-17 15:43:59	3	2026-05-17	mH0nmABuR01u	devoir2
303	Deuxieme devoir du Troisieme trimestre	37	12	2	tests/devoir2_tr3_philo_2ndeCD_25-26.odt	2026-05-17 15:45:55	2026-05-17 15:45:55	3	2026-05-17	DelAHtIZVc5Y	devoir2
304	Deuxieme devoir du Troisieme trimestre	37	9	2	tests/devoir2_tr3_philo_2ndeCD_25-26.odt	2026-05-17 15:45:55	2026-05-17 15:45:55	3	2026-05-17	GmY27UdosMgf	devoir2
305	Deuxieme devoir du Troisieme trimestre	37	12	2	tests/devoir2_tr3_philo_2ndeCD_25-26.pdf	2026-05-17 15:45:55	2026-05-17 15:45:55	3	2026-05-17	0oB2ntaXwF8N	devoir2
306	Deuxieme devoir du Troisieme trimestre	37	9	2	tests/devoir2_tr3_philo_2ndeCD_25-26.pdf	2026-05-17 15:45:55	2026-05-17 15:45:55	3	2026-05-17	BDm93C0McCUB	devoir2
307	Deuxieme devoir du Troisieme trimestre	37	11	2	tests/devoir2_tr3_philo_1ereCD_25-26.odt	2026-05-17 15:45:55	2026-05-17 15:45:55	3	2026-05-17	TtEibxJM8DAC	devoir2
308	Deuxieme devoir du Troisieme trimestre	37	10	2	tests/devoir2_tr3_philo_1ereCD_25-26.odt	2026-05-17 15:45:55	2026-05-17 15:45:55	3	2026-05-17	e2kZgLxEncpd	devoir2
309	Deuxieme devoir du Troisieme trimestre	37	11	2	tests/devoir2_tr3_philo_1ereCD_25-26.pdf	2026-05-17 15:45:55	2026-05-17 15:45:55	3	2026-05-17	FlxSErvL5OOD	devoir2
310	Deuxieme devoir du Troisieme trimestre	37	10	2	tests/devoir2_tr3_philo_1ereCD_25-26.pdf	2026-05-17 15:45:55	2026-05-17 15:45:55	3	2026-05-17	WvEBmOMrXOYS	devoir2
311	Deuxieme devoir du Troisieme trimestre	29	4	2	tests/devoir2_tr3_espa_3eme_25-26.odt	2026-05-17 15:46:59	2026-05-17 15:46:59	3	2026-05-17	ymrMhxxTutYX	devoir2
312	Deuxieme devoir du Troisieme trimestre	29	4	2	tests/devoir2_tr3_espa_3eme_25-26.pdf	2026-05-17 15:46:59	2026-05-17 15:46:59	3	2026-05-17	Z3L4KXptxOKi	devoir2
313	Deuxieme devoir du Troisieme trimestre	29	3	2	tests/devoir2_tr3_espa_4eme_25-26.odt	2026-05-17 15:46:59	2026-05-17 15:46:59	3	2026-05-17	bKPRDwjkGohq	devoir2
314	Deuxieme devoir du Troisieme trimestre	29	3	2	tests/devoir2_tr3_espa_4eme_25-26.pdf	2026-05-17 15:46:59	2026-05-17 15:46:59	3	2026-05-17	dE3eSYOab4zA	devoir2
315	Deuxieme devoir du Troisieme trimestre	101	9	2	tests/devoir2_tr3_ang_2ndeD.docx	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	1Ps09aJszNS8	devoir2
316	Deuxieme devoir du Troisieme trimestre	101	11	2	tests/devoir2_tr3_ang_1ereCD.docx	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	MbYsqSFh7hIv	devoir2
317	Deuxieme devoir du Troisieme trimestre	101	10	2	tests/devoir2_tr3_ang_1ereCD.docx	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	XWMMlYxph0O3	devoir2
318	Deuxieme devoir du Troisieme trimestre	101	9	2	tests/devoir2_tr3_ang_2ndeD.pdf	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	wICbCLkhDhZI	devoir2
319	Deuxieme devoir du Troisieme trimestre	101	11	2	tests/devoir2_tr3_ang_1ereCD.pdf	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	FODb8ULBMepJ	devoir2
320	Deuxieme devoir du Troisieme trimestre	101	10	2	tests/devoir2_tr3_ang_1ereCD.pdf	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	ecSYNAavNF6l	devoir2
321	Deuxieme devoir du Troisieme trimestre	101	3	2	tests/devoir2_tr3_ang_4ème_25-26..odt	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	YaEeHur7s83b	devoir2
322	Deuxieme devoir du Troisieme trimestre	101	3	2	tests/devoir2_tr3_ang_4ème_25-26..pdf	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	KhvXB6WYV6im	devoir2
323	Deuxieme devoir du Troisieme trimestre	101	4	2	tests/devoir2_tr3_ang_3ème_25-26.odt	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	m0PGCljDoOLM	devoir2
324	Deuxieme devoir du Troisieme trimestre	101	4	2	tests/devoir2_tr3_ang_3ème_25-26.pdf	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	sodrWfjia8ed	devoir2
325	Deuxieme devoir du Troisieme trimestre	101	2	2	tests/devoir2_tr3_ang_5ème_25-26.odt	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	txRV94lxnh44	devoir2
326	Deuxieme devoir du Troisieme trimestre	101	2	2	tests/devoir2_tr3_ang_5ème_25-26.pdf	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	EC2gcN4sReB6	devoir2
327	Deuxieme devoir du Troisieme trimestre	101	1	2	tests/devoir2_tr3_ang_6ème_25-26.odt	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	c47m240vAbNK	devoir2
328	Deuxieme devoir du Troisieme trimestre	101	1	2	tests/devoir2_tr3_ang_6ème_25-26.pdf	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	Jo0uumEsLJHs	devoir2
329	Deuxieme devoir du Troisieme trimestre	101	4	2	tests/devoir2_tr3_ang_3ème_mod_25-26.odt	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	PtAXpB4IStjt	devoir2
330	Deuxieme devoir du Troisieme trimestre	101	4	2	tests/devoir2_tr3_ang_3ème_mod_25-26.pdf	2026-05-17 15:49:07	2026-05-17 15:49:07	3	2026-05-17	l5rq1xoMVdPt	devoir2
\.


--
-- Data for Name: transactions; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.transactions (id, date_transaction, type, categorie_id, compte_id, montant, mode_paiement, description, created_by, created_at, updated_at) FROM stdin;
1	2024-09-30	dépense	14	2	50000.00	\N	\N	\N	2025-10-01 18:55:08	2025-10-01 18:55:08
3	2024-10-01	dépense	15	2	20500.00	\N	\N	\N	2025-10-01 19:10:36	2025-10-01 19:10:36
4	2024-10-01	dépense	8	2	10000.00	\N	\N	\N	2025-10-01 19:11:52	2025-10-01 19:11:52
5	2024-10-01	dépense	8	2	14950.00	\N	\N	\N	2025-10-01 19:12:38	2025-10-01 19:12:38
6	2024-10-09	dépense	15	2	20500.00	\N	\N	\N	2025-10-01 19:13:45	2025-10-01 19:13:45
\.


--
-- Data for Name: trimestres; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.trimestres (id, nom, created_at, updated_at, ordre, periode) FROM stdin;
1	Premier Trimestre	2025-12-22 13:14:50	2025-12-22 13:14:50	1	octobre-décembre
2	Deuxième Trimestre	2025-12-22 13:14:50	2025-12-22 13:14:50	2	janvier-mars
3	Troisième Trimestre	2025-12-22 13:14:50	2025-12-22 13:14:50	3	avril-juin
\.


--
-- Data for Name: types; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.types (id, nom, description, created_at, updated_at) FROM stdin;
5	Tenue de Sport	\N	2026-02-06 23:48:57	2026-02-06 23:48:57
2	Uniformes	\N	2026-02-06 23:47:38	2026-02-07 00:06:14
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.users (id, nom, email, photo, email_verified_at, password, remember_token, current_team_id, profile_photo_path, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, telephone, prenom, role) FROM stdin;
73	JOSHUA	joshua@gmial.com	\N	\N	$2y$12$XOEMs0Cr6wmctsMz6T1JD.xpjmpYL.qA5gJ9p690mnzI8Wtzpnjm6	\N	\N	\N	2026-05-02 22:20:25	2026-06-02 22:15:58	\N	\N	\N	\N	BALOGOUN	admin
33	AGOKPINZIN Edmon	166767618@parent.local	\N	\N	$2y$12$zXyDGDTzH1Dwnc6Bp6EIiOHOmKvLH8eX16N.HYxmpdsuig0YI3sm.	\N	\N	\N	2026-02-02 22:34:09	2026-05-03 22:09:46	\N	\N	\N	166767618	\N	parent
36	TAIWO SADIKOU	197874170@parent.local	\N	\N	$2y$12$eQ5obvOg/EShXLAl0/6xEeIjCDM11siaqY/MJBCE4FxqqDjTl27/i	\N	\N	\N	2026-02-02 22:34:10	2026-05-03 22:10:24	\N	\N	\N	197874170	\N	parent
63	ENSEIGNANT	ensei@gmail.com	\N	\N	$2y$12$9RzwkN1iLBsXbb2uF8LP5uSRPXE07VEEBptar4LYD2aXdKDHVMYny	\N	\N	\N	2026-03-16 00:48:08	2026-05-02 21:37:18	\N	\N	\N	\N	ENSEIGNANT	enseignant
34	AYABA	ayaba@gmail.com	\N	\N	$2y$12$oGzj9yJEj/HC2sNMX8k4beNjpVMY8L56A2jLP/1pnCaQzUn6Jc0nK	\N	\N	\N	2026-02-02 22:34:10	2026-06-03 21:38:45	\N	\N	\N	144562312	Latif	parent
60	ADJOU	compt@gmail.com	\N	\N	$2y$12$cgU3WIUS5cn1KaKwbwh0MuVUR.LW0aaojLFwe45Glh.XeX11Df2LW	\N	\N	\N	2026-02-16 22:09:49	2026-05-02 21:49:25	\N	\N	\N	\N	Fadil	comptable
61	Sogan	surveil@gmail.com	\N	\N	$2y$12$9P8NMIvBWvRYGhgw5ofSRe08ofa5JESR90mXLm5cnKvrZWUN2eTPu	\N	\N	\N	2026-02-25 00:59:50	2026-05-02 21:51:56	\N	\N	\N	\N	Serge	surveillant
59	DIRECTEUR	direct@gmail.com	users/MGIPVcrv9vNTiDTGq5OoBNtbN00Lj4h62HmgGeVE.jpg	\N	$2y$12$itMvXgkYNRnBqx1hpLfTtehZVURwwFHOq.Z02scKF36vNqwa3hvrO	\N	\N	\N	2026-02-16 19:52:31	2026-05-10 22:33:30	\N	\N	\N	\N	koll	directeur
44	YESSOUFOU	affissou@gmail.com	users/2vr6LdKrdgg8j9qFeFGrWGZui7VQT4HWPurQX150.jpg	\N	$2y$12$kHo8ky3Ngc23Zm2P2QSYn.g4B4I0/AYymT81OHPBTaJRIvw3nzCXK	\N	\N	\N	2026-02-02 22:41:42	2026-05-03 14:04:35	\N	\N	\N	197189324	Affissou	parent
35	SAGBOHAN John	197476609@parent.local	\N	\N	$2y$12$Wrz8k84oqxg5nBoOVp7NpOck8CfOoQ1Ljxc9T0pgM7oAYepHllrTm	\N	\N	\N	2026-02-02 22:34:10	2026-05-03 22:11:06	\N	\N	\N	197476609	\N	parent
38	AHOLODE	157238396@parent.local	\N	\N	$2y$12$wFNh8ZE7cEZACBanyAfIcuCZ09bPbCXG2VSr8zATDVKXbV09DXiUq	\N	\N	\N	2026-02-02 22:41:41	2026-05-03 22:12:16	\N	\N	\N	157238396	Jean	parent
39	DIAKITE	144525556@parent.local	\N	\N	$2y$12$trG.RwTcP1d4PHrxhqfeEuJu3yBvxQNqVxn0uGIukkZgnbMH5iMCC	\N	\N	\N	2026-02-02 22:41:41	2026-05-03 22:13:36	\N	\N	\N	144525556	ADJARATOU	parent
40	GBAMIGBOLA	197484776@parent.local	\N	\N	$2y$12$xFReHZM24EB6SESFffPLBeRMF7WvzNRjvQX06dGaU5p3F8Vu7bZ2y	\N	\N	\N	2026-02-02 22:41:41	2026-05-03 23:49:37	\N	\N	\N	197484776	Firmin	parent
57	SECRETAIRE	secre@gmail.com	users/WC8vMLoLY2goWxru5tN8GS1hHJCo26d6BL3OmB4C.png	\N	$2y$12$0C9IbEiKF/aHTsyakrMANOqN/KYapQH9FBEHP.TxIVP/KPpzq42q.	\N	\N	\N	2026-02-03 22:29:59	2026-05-25 21:45:08	\N	\N	\N	\N	Kolawolé	secretaire
74	ADEYEMI	kolatresor.adeyemi@gmail.com	\N	\N	$2y$12$yhZtv3kXmn9d1G9J6rkkF.gpL3LSigkWbIE7hvwmcQro8.g9dTrZu	\N	\N	\N	2026-05-11 00:03:42	2026-05-12 22:14:56	\N	\N	\N	\N	KOLAWOLE	admin
58	Solo	cens@gmail.com	\N	\N	$2y$12$vL2nxklhg1a5t5qmjrBfbulQ0Zr8kG0oXxFRsu16OwHiUuuCfohLG	\N	\N	\N	2026-02-15 12:27:25	2026-05-10 19:30:21	\N	\N	\N	\N	Makinde	censeur
41	GBESSEHOUN BORIS	149484340@parent.local	\N	\N	$2y$12$68i7UU8bcJgmQ0/JQGF1M.phqyRSNM7Cv6N.I8EJJZqQ4kdubdybG	\N	\N	\N	2026-02-02 22:41:42	2026-02-02 22:41:42	\N	\N	\N	149484340	\N	\N
42	KALU SIMEON	197615471@parent.local	\N	\N	$2y$12$dTvaKrMAdZlbU6hHhlfHcegs/uSACua7ykl6TGqIR7F3zDwo0txTm	\N	\N	\N	2026-02-02 22:41:42	2026-02-02 22:41:42	\N	\N	\N	197615471	\N	\N
43	LADOKOU ALIOU	197081862@parent.local	\N	\N	$2y$12$nf35QyywFxbu.IUb3a9p5u3aiicViz9vAfhYS28QlQ4d7PlT/OEMK	\N	\N	\N	2026-02-02 22:41:42	2026-02-02 22:41:42	\N	\N	\N	197081862	\N	\N
45	ADEYEMI KOLAWOLE	197521637@parent.local	\N	\N	$2y$12$Y4IOMR9Zofk.sRfImwJVFuESfIx4A9IcqRwJwBrj.qwOx9LAWjcRa	\N	\N	\N	2026-02-02 22:42:47	2026-02-02 22:42:47	\N	\N	\N	197521637	\N	\N
46	BOUBACAR BAKARI	145474846@parent.local	\N	\N	$2y$12$zVclfaHwJhylbdsdi56TMuccExLsrflX//MjXeMgd3iw4PUkvWDe.	\N	\N	\N	2026-02-02 22:42:47	2026-02-02 22:42:47	\N	\N	\N	145474846	\N	\N
48	GNONLONFOUN JOEL	166020699@parent.local	\N	\N	$2y$12$Y.cTozImHvE1oZnOqDTM7ugewV.PDDTaIW06UmOvJ74.ZPvW2ATCG	\N	\N	\N	2026-02-02 22:42:48	2026-02-02 22:42:48	\N	\N	\N	166020699	\N	\N
49	SOUNOUVOU GERMAIN	148882804@parent.local	\N	\N	$2y$12$kXmvZcnOWDpJN28eW1gbNOhZ6LG/Bzexx5D9wYQwyeV2ln1wh5gaS	\N	\N	\N	2026-02-02 22:42:48	2026-02-02 22:42:48	\N	\N	\N	148882804	\N	\N
47	GANDONOU BENOIT	144785632@parent.local	\N	\N	$2y$12$3frndSpmm0LpcGfuiGvpKuM6netYomXDB4KwwEHK8adLc7/50zPVm	\N	\N	\N	2026-02-02 22:42:48	2026-02-02 22:50:17	\N	\N	\N	144785632	\N	\N
50	AGBOZINGBA Cossi	197070128@parent.local	\N	\N	$2y$12$AMT4gEW6GbLAnu2NKISlLuIHnpyGZtxBjodXeakLZlSFZW1n3oKHS	\N	\N	\N	2026-02-03 20:34:16	2026-02-03 20:34:16	\N	\N	\N	197070128	\N	\N
51	AHOUANSE Antoine	197074018@parent.local	\N	\N	$2y$12$A0GjdzkHqfG7Wqc4IDvyluT2FQ.T93LKzHaeMIIHlCXJ1ujGO8K.u	\N	\N	\N	2026-02-03 20:34:16	2026-02-03 20:34:16	\N	\N	\N	197074018	\N	\N
52	BONOU Joseph	196125472@parent.local	\N	\N	$2y$12$Agmx8zBEs8XopLS/UuZB2es70EyLaEi2s4R3WiHHrw21WXa6Dq80y	\N	\N	\N	2026-02-03 20:34:17	2026-02-03 20:34:17	\N	\N	\N	196125472	\N	\N
53	OLAAFA Nabil	166196100@parent.local	\N	\N	$2y$12$eBbppQNxg.leBZ33SN53GuMIRWAowVBtfG3yf6QftyQ.Ip/j7xtYu	\N	\N	\N	2026-02-03 20:34:17	2026-02-03 20:34:17	\N	\N	\N	166196100	\N	\N
54	HOUNDEWAGNON GERARD	197122545@parent.local	\N	\N	$2y$12$Cx/L6RYzhEMFwzbQ142LAOAv3VbP2a9RE/ccIarYeKCMvRgGIUAvO	\N	\N	\N	2026-02-03 20:36:04	2026-02-03 20:36:04	\N	\N	\N	197122545	\N	\N
55	OHOUSSOU PROSPER	144255635@parent.local	\N	\N	$2y$12$AnVQqea1pzYLnR6zx59naeydiNhRcb9O5bQVLJsKHn0qSxZIlBn4K	\N	\N	\N	2026-02-03 20:36:05	2026-02-03 20:36:05	\N	\N	\N	144255635	\N	\N
78	BONIFACE	bignon@gmail.com	\N	\N	$2y$12$yOvtPWRkznmw7XO8OU49VuiW/e/u6wAcp3k38yCyNDFyglasfMR.y	\N	\N	\N	2026-06-11 16:44:06	2026-06-11 16:44:06	\N	\N	\N	\N	Bignon	directeur
37	ADMIN	admin@gmail.com	users/sIxbsEqYiE2ZENOGAVo1GovNrEWDkstG2Yuznyb9.jpg	\N	$2y$12$lY0U1GcoiRIZbPbfn2lmUOzPsd8.Fe78BXrSgzkmWTxJ43XxovubO	ZjXGPsRnkhASjbdbZWVNRYiNqQYd1eswXaSw441JIkU17jf0Aa1e771Ma691	\N	\N	2026-02-02 22:37:01	2026-06-02 22:04:36	\N	\N	\N	\N	Joshua	admin
\.


--
-- Data for Name: versements; Type: TABLE DATA; Schema: public; Owner: adeyemi
--

COPY public.versements (id, investissement_id, date_versement, montant, mode_paiement, reference, observation, created_at, updated_at) FROM stdin;
1	10	2023-03-23	4250000.00	Espèces	signature	premier dépôt pour le bail la maison de M. MIDODJIHO Joseph (R+2) Abritant le complexe Scolaire le glorieux	2026-07-10 12:16:04	2026-07-10 12:16:04
2	11	2023-04-01	5750000.00	Espèces	Signature	Deuxième dépôt pour le bail  M. MIDODJIHO Joseph	2026-07-10 12:23:59	2026-07-10 12:23:59
6	10	2023-04-26	20000.00	Espèces	Signature	Dépôt fait au marçon dans le cadre des travaux de séparation	2026-07-10 12:40:56	2026-07-10 12:40:56
8	10	2023-06-20	280000.00	Espèces	Signature	Pour la confection des tables-bancs	2026-07-10 12:49:15	2026-07-10 12:49:15
9	11	2024-08-04	10000.00	Espèces	Signature	Pour la PUBLICITE donner crieur public M. SAGBOHAN	2026-07-10 12:52:39	2026-07-10 12:52:39
10	11	2023-08-05	30000.00	Espèces	Signature	Pour les travaux de séparation	2026-07-10 12:56:23	2026-07-10 12:56:23
11	11	2023-08-04	10000.00	Espèces	Signature	Pour la confection de la couverture du puits: SOUDEUR BENJAMIN	2026-07-10 13:02:51	2026-07-10 13:02:51
12	10	2023-08-04	180000.00	Espèces	Signature	Pour confection des tables-bancs et Déplacement , MENUISIER ADEWALE	2026-07-10 13:17:57	2026-07-10 13:17:57
13	10	2023-08-04	50000.00	Espèces	Signature	Pour M. AL AMIN RAIM	2026-07-10 13:21:40	2026-07-10 13:21:40
14	10	2023-08-04	5000.00	Espèces	Signature	Maîtresse CI et CP: AYONOU AIMEE	2026-07-10 13:24:35	2026-07-10 13:24:35
15	10	2023-08-08	5000.00	Espèces	Signature	Déplacement pour crédit d'accompagnement	2026-07-10 13:27:34	2026-07-10 13:27:34
16	11	2023-08-11	6000.00	Espèces	Signature	Photocopie des prospertus: Imprimaire Félicien	2026-07-10 20:14:41	2026-07-10 20:14:41
17	10	2023-08-11	8000.00	Espèces	Signature	Distribution des prospertus et aide marçon	2026-07-10 20:16:44	2026-07-10 20:16:44
7	10	2023-06-20	50000.00	Espèces	Signature	Frais de démarcheur du côté de M. YESSOUFOU GAFARI	2026-07-10 12:43:43	2026-07-10 20:20:59
18	10	2023-08-11	8750.00	Espèces	Signature	LES BOISSONS POUR L'OUVERTURE DU COMPLEXE	2026-07-10 20:24:53	2026-07-10 20:24:53
19	11	2023-08-11	50000.00	Espèces	Signature	Les frais de démarcheur de M. BERTRAND	2026-07-10 20:26:08	2026-07-10 20:26:08
20	11	2023-02-11	50000.00	Espèces	Signature	Complément des frais de confection des tables-bancs au menuisier ADEWALE	2026-07-10 20:29:11	2026-07-10 20:29:11
21	11	2023-08-11	12000.00	Espèces	Signature	Achat d'acide pour le nettoyage du batiment	2026-07-10 20:31:31	2026-07-10 20:31:31
22	11	2023-08-17	30000.00	Espèces	Signature	Confection de la porte pour le bureau	2026-07-10 20:34:18	2026-07-10 20:34:18
24	11	2023-08-22	97000.00	Espèces	Signature	Achat des uniformes	2026-07-10 20:38:35	2026-07-10 20:38:35
25	11	2023-08-25	5000.00	Espèces	Signature	Dépôt d'argent à M. AL AMIN RAIM	2026-07-10 20:40:23	2026-07-10 20:40:23
23	11	2023-08-21	70000.00	Espèces	Signature	Avance sur les frais de confection de 12 chaises et 3 tables de la maternelle	2026-07-10 20:36:57	2026-07-10 20:41:49
26	11	2023-08-25	20000.00	Espèces	Signature	Reste des frais de confection des 12 chaises et 3 tables de la maternelle	2026-07-10 20:43:19	2026-07-10 20:43:19
27	11	2023-08-25	20000.00	Espèces	Signature	Achat et fixation des tableaux au mur	2026-07-10 20:50:35	2026-07-10 20:50:35
28	11	2023-08-28	3000.00	Espèces	Signature	Manger des apprentis carroleur	2026-07-10 20:52:41	2026-07-10 20:52:41
29	11	2023-08-28	15000.00	Espèces	Signature	Avance pour les travaux de cimentage de la cours	2026-07-10 20:57:30	2026-07-10 20:57:30
31	11	2023-05-29	11000.00	Espèces	Signature	Achat de Ardoisine, manger des carroleur, impression et photocopies des évaluations	2026-07-10 21:04:44	2026-07-10 21:04:44
32	11	2023-08-29	30000.00	Espèces	Signature	Fixation tableau, cimentage de la cours et achat de quatre paquets de ciments	2026-07-10 21:08:18	2026-07-10 21:08:18
33	11	2023-08-30	100000.00	Espèces	Signature	Achat d'une tonne de ciments	2026-07-10 21:09:55	2026-07-10 21:09:55
34	10	2023-08-30	7000.00	Espèces	Signature	Déplacement de ciments et photocopie	2026-07-10 21:11:12	2026-07-10 21:11:12
35	10	2023-09-11	9000.00	Espèces	Signature	frais d'impression des reçus	2026-07-10 21:12:59	2026-07-10 21:12:59
36	10	2023-09-11	45000.00	Espèces	Signature	Frais de bail des autorisations de M. AM AMIN RAIM restant	2026-07-10 21:20:34	2026-07-10 21:20:34
37	10	2023-09-11	40000.00	Espèces	Signature	Achat de ciment	2026-07-10 21:22:45	2026-07-10 21:22:45
38	10	2023-09-11	20000.00	Espèces	Signature	Confection de barrières par le soudeur	2026-07-10 21:25:09	2026-07-10 21:25:09
39	10	2023-09-11	10000.00	Espèces	Signature	Main d'oeuvre marçon	2026-07-10 21:26:01	2026-07-10 21:26:01
40	10	2023-09-11	6000.00	Espèces	Signature	Nettoyage des nakos	2026-07-10 21:27:34	2026-07-10 21:27:34
41	10	2023-12-29	30000.00	Espèces	Signature	Remboursement du menuisier HOUNKPONOU	2026-07-10 21:29:01	2026-07-10 21:29:01
42	10	2026-07-10	10000.00	Espèces	Signature	remboursement du menuisier HOUNKPONOU	2026-07-10 21:29:56	2026-07-10 21:29:56
43	10	2024-03-28	50000.00	Espèces	Signature	Remboursement du menuisier HOUNKPONOU	2026-07-10 21:31:05	2026-07-10 21:31:05
44	10	2024-04-25	20000.00	Espèces	Signature	Remboursement du menuisier HOUNKPONOU	2026-07-10 21:32:03	2026-07-10 21:32:03
45	11	2023-11-10	1500000.00	Espèces	Signature	Complément des frais de bail de M. MIDODJIHO Joseph pour ELECTRICITE EAU et remboursement ALADJI	2026-07-10 21:36:42	2026-07-10 21:36:42
46	11	2024-12-11	43500.00	Espèces	Signature	Complément du salaire de novembre2024 des enseignants	2026-07-10 21:38:39	2026-07-10 21:38:39
47	11	2025-01-10	109000.00	Espèces	Signature	Sailaire de décembre2024 des enseignants	2026-07-10 21:40:31	2026-07-10 21:40:31
48	11	2025-02-13	200000.00	Espèces	Signature	Quittance pour CREATION DES CLASSES DE SECONDES CD	2026-07-10 21:42:12	2026-07-10 21:42:12
49	11	2025-04-02	300000.00	Espèces	Signature	REMBOURSEMENT DES PRÊTS DE FARID	2026-07-10 21:43:46	2026-07-10 21:43:46
50	11	2025-06-30	20000.00	Espèces	Signature	Paiement des redevances d'achat de tableaux chez M. AHOUADI AUGUSTIN	2026-07-10 21:46:20	2026-07-10 21:46:20
51	11	2025-07-01	30000.00	Espèces	Signature	AVANCE SUR SALAIRE DE AVRIL-MAI 2025 pour le maître Koudous TIDJANI	2026-07-10 21:48:55	2026-07-10 21:48:55
52	11	2026-06-16	1000000.00	Espèces	Signature	CONFECTION DE QUATRE-VINGT-DIX (90) TABLES-BANCS DU PRIMAIRE	2026-07-10 21:51:00	2026-07-10 21:51:00
53	11	2026-06-19	300000.00	Espèces	Signature	TRAVAUX DE BLOMBERI (WC URINOIRE) ET EVACUATION D'EAU	2026-07-10 21:52:47	2026-07-10 21:52:47
\.


--
-- Name: annee_classe_frais_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.annee_classe_frais_id_seq', 232, true);


--
-- Name: annee_trimestre_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.annee_trimestre_id_seq', 27, true);


--
-- Name: annees_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.annees_id_seq', 5, true);


--
-- Name: articles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.articles_id_seq', 1, true);


--
-- Name: benefices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.benefices_id_seq', 1, true);


--
-- Name: budgets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.budgets_id_seq', 3, true);


--
-- Name: bulletins_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.bulletins_id_seq', 1, false);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.categories_id_seq', 16, true);


--
-- Name: classe_annee_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.classe_annee_id_seq', 223, true);


--
-- Name: classe_enseignant_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.classe_enseignant_id_seq', 4, true);


--
-- Name: classe_transitions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.classe_transitions_id_seq', 12, true);


--
-- Name: classes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.classes_id_seq', 25, true);


--
-- Name: comptes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.comptes_id_seq', 3, true);


--
-- Name: conduites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.conduites_id_seq', 361, true);


--
-- Name: contacts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.contacts_id_seq', 6, true);


--
-- Name: cycles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.cycles_id_seq', 3, true);


--
-- Name: depenses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.depenses_id_seq', 9, true);


--
-- Name: echeances_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.echeances_id_seq', 329, true);


--
-- Name: eleves_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.eleves_id_seq', 61, true);


--
-- Name: enseignants_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.enseignants_id_seq', 6, true);


--
-- Name: epreuves_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.epreuves_id_seq', 1, false);


--
-- Name: examen_blanc_classe_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.examen_blanc_classe_id_seq', 10, true);


--
-- Name: examen_blancs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.examen_blancs_id_seq', 35, true);


--
-- Name: examen_classes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.examen_classes_id_seq', 12, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: finances_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.finances_id_seq', 1, false);


--
-- Name: frais_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.frais_id_seq', 119, true);


--
-- Name: galeries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.galeries_id_seq', 7, true);


--
-- Name: importation_notes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.importation_notes_id_seq', 1, true);


--
-- Name: importations_notes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.importations_notes_id_seq', 1, false);


--
-- Name: inscription_frais_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.inscription_frais_id_seq', 414, true);


--
-- Name: inscriptions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.inscriptions_id_seq', 240, true);


--
-- Name: investissements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.investissements_id_seq', 11, true);


--
-- Name: investisseurs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.investisseurs_id_seq', 2, true);


--
-- Name: matiere_classe_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.matiere_classe_id_seq', 268, true);


--
-- Name: matieres_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.matieres_id_seq', 123, true);


--
-- Name: media_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.media_id_seq', 36, true);


--
-- Name: messages_parents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.messages_parents_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.migrations_id_seq', 119, true);


--
-- Name: mouvement_stocks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.mouvement_stocks_id_seq', 1, true);


--
-- Name: moyennes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.moyennes_id_seq', 2352, true);


--
-- Name: note_examens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.note_examens_id_seq', 378, true);


--
-- Name: notes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.notes_id_seq', 976, true);


--
-- Name: notifications_parents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.notifications_parents_id_seq', 1, false);


--
-- Name: oloyes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.oloyes_id_seq', 1, false);


--
-- Name: operations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.operations_id_seq', 107, true);


--
-- Name: paiement_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.paiement_details_id_seq', 1, false);


--
-- Name: paiements_benefices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.paiements_benefices_id_seq', 1, false);


--
-- Name: paiements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.paiements_id_seq', 236, true);


--
-- Name: parametres_investissements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.parametres_investissements_id_seq', 1, false);


--
-- Name: parens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.parens_id_seq', 55, true);


--
-- Name: participant_examens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.participant_examens_id_seq', 162, true);


--
-- Name: passages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.passages_id_seq', 6, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.permissions_id_seq', 15, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 29, true);


--
-- Name: recettes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.recettes_id_seq', 374, true);


--
-- Name: repartitions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.repartitions_id_seq', 1, false);


--
-- Name: retraits_capital_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.retraits_capital_id_seq', 1, false);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.roles_id_seq', 8, true);


--
-- Name: scolarites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.scolarites_id_seq', 1, false);


--
-- Name: td_modes_paiements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.td_modes_paiements_id_seq', 12, true);


--
-- Name: td_paiements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.td_paiements_id_seq', 2, true);


--
-- Name: td_presences_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.td_presences_id_seq', 39, true);


--
-- Name: td_seances_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.td_seances_id_seq', 23, true);


--
-- Name: td_tarifs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.td_tarifs_id_seq', 5, true);


--
-- Name: tests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.tests_id_seq', 330, true);


--
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.transactions_id_seq', 6, true);


--
-- Name: trimestres_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.trimestres_id_seq', 3, true);


--
-- Name: types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.types_id_seq', 5, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.users_id_seq', 78, true);


--
-- Name: versements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.versements_id_seq', 53, true);


--
-- Name: annee_classe_frais annee_classe_frais_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe_frais
    ADD CONSTRAINT annee_classe_frais_pkey PRIMARY KEY (id);


--
-- Name: annee_trimestre annee_trimestre_annee_id_trimestre_id_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_trimestre
    ADD CONSTRAINT annee_trimestre_annee_id_trimestre_id_unique UNIQUE (annee_id, trimestre_id);


--
-- Name: annee_trimestre annee_trimestre_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_trimestre
    ADD CONSTRAINT annee_trimestre_pkey PRIMARY KEY (id);


--
-- Name: annees annees_nom_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annees
    ADD CONSTRAINT annees_nom_unique UNIQUE (nom);


--
-- Name: annees annees_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annees
    ADD CONSTRAINT annees_pkey PRIMARY KEY (id);


--
-- Name: articles articles_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.articles
    ADD CONSTRAINT articles_pkey PRIMARY KEY (id);


--
-- Name: articles articles_reference_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.articles
    ADD CONSTRAINT articles_reference_unique UNIQUE (reference);


--
-- Name: benefices benefices_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.benefices
    ADD CONSTRAINT benefices_pkey PRIMARY KEY (id);


--
-- Name: budgets budgets_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT budgets_pkey PRIMARY KEY (id);


--
-- Name: bulletins bulletins_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.bulletins
    ADD CONSTRAINT bulletins_pkey PRIMARY KEY (id);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: annee_classe classe_annee_classe_id_annee_id_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe
    ADD CONSTRAINT classe_annee_classe_id_annee_id_unique UNIQUE (classe_id, annee_id);


--
-- Name: annee_classe classe_annee_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe
    ADD CONSTRAINT classe_annee_pkey PRIMARY KEY (id);


--
-- Name: classe_enseignant classe_enseignant_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_enseignant
    ADD CONSTRAINT classe_enseignant_pkey PRIMARY KEY (id);


--
-- Name: classe_transitions classe_transitions_classe_id_classe_superieure_id_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_transitions
    ADD CONSTRAINT classe_transitions_classe_id_classe_superieure_id_unique UNIQUE (classe_id, classe_superieure_id);


--
-- Name: classe_transitions classe_transitions_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_transitions
    ADD CONSTRAINT classe_transitions_pkey PRIMARY KEY (id);


--
-- Name: classes classes_nom_niveau_key; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classes
    ADD CONSTRAINT classes_nom_niveau_key UNIQUE (nom, niveau);


--
-- Name: classes classes_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classes
    ADD CONSTRAINT classes_pkey PRIMARY KEY (id);


--
-- Name: comptes comptes_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.comptes
    ADD CONSTRAINT comptes_pkey PRIMARY KEY (id);


--
-- Name: conduites conduites_inscription_trimestre_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.conduites
    ADD CONSTRAINT conduites_inscription_trimestre_unique UNIQUE (inscription_id, trimestre_id);


--
-- Name: conduites conduites_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.conduites
    ADD CONSTRAINT conduites_pkey PRIMARY KEY (id);


--
-- Name: contacts contacts_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.contacts
    ADD CONSTRAINT contacts_pkey PRIMARY KEY (id);


--
-- Name: cycles cycles_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.cycles
    ADD CONSTRAINT cycles_pkey PRIMARY KEY (id);


--
-- Name: depenses depenses_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.depenses
    ADD CONSTRAINT depenses_pkey PRIMARY KEY (id);


--
-- Name: echeances echeances_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.echeances
    ADD CONSTRAINT echeances_pkey PRIMARY KEY (id);


--
-- Name: eleves eleves_numero_ordre_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.eleves
    ADD CONSTRAINT eleves_numero_ordre_unique UNIQUE (numero_ordre);


--
-- Name: eleves eleves_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.eleves
    ADD CONSTRAINT eleves_pkey PRIMARY KEY (id);


--
-- Name: enseignants enseignants_email_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.enseignants
    ADD CONSTRAINT enseignants_email_unique UNIQUE (email);


--
-- Name: enseignants enseignants_matricule_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.enseignants
    ADD CONSTRAINT enseignants_matricule_unique UNIQUE (matricule);


--
-- Name: enseignants enseignants_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.enseignants
    ADD CONSTRAINT enseignants_pkey PRIMARY KEY (id);


--
-- Name: epreuves epreuves_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.epreuves
    ADD CONSTRAINT epreuves_pkey PRIMARY KEY (id);


--
-- Name: examen_blanc_classe examen_blanc_classe_examen_blanc_id_classe_id_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blanc_classe
    ADD CONSTRAINT examen_blanc_classe_examen_blanc_id_classe_id_unique UNIQUE (examen_blanc_id, classe_id);


--
-- Name: examen_blanc_classe examen_blanc_classe_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blanc_classe
    ADD CONSTRAINT examen_blanc_classe_pkey PRIMARY KEY (id);


--
-- Name: examen_blancs examen_blancs_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blancs
    ADD CONSTRAINT examen_blancs_pkey PRIMARY KEY (id);


--
-- Name: examen_classes examen_classes_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_classes
    ADD CONSTRAINT examen_classes_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: finances finances_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.finances
    ADD CONSTRAINT finances_pkey PRIMARY KEY (id);


--
-- Name: frais frais_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.frais
    ADD CONSTRAINT frais_pkey PRIMARY KEY (id);


--
-- Name: galeries galeries_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.galeries
    ADD CONSTRAINT galeries_pkey PRIMARY KEY (id);


--
-- Name: importation_notes importation_notes_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importation_notes
    ADD CONSTRAINT importation_notes_pkey PRIMARY KEY (id);


--
-- Name: importations_notes importations_notes_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importations_notes
    ADD CONSTRAINT importations_notes_pkey PRIMARY KEY (id);


--
-- Name: inscription_frais inscription_frais_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscription_frais
    ADD CONSTRAINT inscription_frais_pkey PRIMARY KEY (id);


--
-- Name: inscriptions inscriptions_eleve_annee_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscriptions
    ADD CONSTRAINT inscriptions_eleve_annee_unique UNIQUE (eleve_id, annee_id);


--
-- Name: inscriptions inscriptions_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscriptions
    ADD CONSTRAINT inscriptions_pkey PRIMARY KEY (id);


--
-- Name: investissements investissements_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.investissements
    ADD CONSTRAINT investissements_pkey PRIMARY KEY (id);


--
-- Name: investisseurs investisseurs_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.investisseurs
    ADD CONSTRAINT investisseurs_pkey PRIMARY KEY (id);


--
-- Name: classe_matiere matiere_classe_classe_id_matiere_id_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_matiere
    ADD CONSTRAINT matiere_classe_classe_id_matiere_id_unique UNIQUE (classe_id, matiere_id);


--
-- Name: classe_matiere matiere_classe_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_matiere
    ADD CONSTRAINT matiere_classe_pkey PRIMARY KEY (id);


--
-- Name: matieres matieres_nom_niveau_key; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.matieres
    ADD CONSTRAINT matieres_nom_niveau_key UNIQUE (nom, niveau);


--
-- Name: matieres matieres_nom_niveau_type_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.matieres
    ADD CONSTRAINT matieres_nom_niveau_type_unique UNIQUE (nom, niveau, type);


--
-- Name: matieres matieres_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.matieres
    ADD CONSTRAINT matieres_pkey PRIMARY KEY (id);


--
-- Name: media media_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.media
    ADD CONSTRAINT media_pkey PRIMARY KEY (id);


--
-- Name: message_parents messages_parents_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.message_parents
    ADD CONSTRAINT messages_parents_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: mouvement_stocks mouvement_stocks_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.mouvement_stocks
    ADD CONSTRAINT mouvement_stocks_pkey PRIMARY KEY (id);


--
-- Name: moyennes moyennes_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.moyennes
    ADD CONSTRAINT moyennes_pkey PRIMARY KEY (id);


--
-- Name: note_examens note_examens_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.note_examens
    ADD CONSTRAINT note_examens_pkey PRIMARY KEY (id);


--
-- Name: notes note_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT note_unique UNIQUE (inscription_id, matiere_id, trimestre_id, annee_id);


--
-- Name: notes notes_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT notes_pkey PRIMARY KEY (id);


--
-- Name: notification_parents notifications_parents_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notification_parents
    ADD CONSTRAINT notifications_parents_pkey PRIMARY KEY (id);


--
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- Name: oloyes oloyes_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.oloyes
    ADD CONSTRAINT oloyes_pkey PRIMARY KEY (id);


--
-- Name: operations operations_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.operations
    ADD CONSTRAINT operations_pkey PRIMARY KEY (id);


--
-- Name: paiement_details paiement_details_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiement_details
    ADD CONSTRAINT paiement_details_pkey PRIMARY KEY (id);


--
-- Name: paiements_benefices paiements_benefices_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiements_benefices
    ADD CONSTRAINT paiements_benefices_pkey PRIMARY KEY (id);


--
-- Name: paiements paiements_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiements
    ADD CONSTRAINT paiements_pkey PRIMARY KEY (id);


--
-- Name: paiements paiements_reference_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiements
    ADD CONSTRAINT paiements_reference_unique UNIQUE (reference);


--
-- Name: parametres_investissements parametres_investissements_cle_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.parametres_investissements
    ADD CONSTRAINT parametres_investissements_cle_unique UNIQUE (cle);


--
-- Name: parametres_investissements parametres_investissements_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.parametres_investissements
    ADD CONSTRAINT parametres_investissements_pkey PRIMARY KEY (id);


--
-- Name: parens parens_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.parens
    ADD CONSTRAINT parens_pkey PRIMARY KEY (id);


--
-- Name: participant_examens participant_examens_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.participant_examens
    ADD CONSTRAINT participant_examens_pkey PRIMARY KEY (id);


--
-- Name: passages passages_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.passages
    ADD CONSTRAINT passages_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: recettes recettes_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.recettes
    ADD CONSTRAINT recettes_pkey PRIMARY KEY (id);


--
-- Name: repartitions repartitions_benefice_id_investissement_id_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.repartitions
    ADD CONSTRAINT repartitions_benefice_id_investissement_id_unique UNIQUE (benefice_id, investissement_id);


--
-- Name: repartitions repartitions_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.repartitions
    ADD CONSTRAINT repartitions_pkey PRIMARY KEY (id);


--
-- Name: retraits_capital retraits_capital_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.retraits_capital
    ADD CONSTRAINT retraits_capital_pkey PRIMARY KEY (id);


--
-- Name: roles roles_name_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_unique UNIQUE (name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: scolarites scolarites_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.scolarites
    ADD CONSTRAINT scolarites_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: td_modes_paiements td_modes_paiements_eleve_id_annee_id_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_modes_paiements
    ADD CONSTRAINT td_modes_paiements_eleve_id_annee_id_unique UNIQUE (eleve_id, annee_id);


--
-- Name: td_modes_paiements td_modes_paiements_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_modes_paiements
    ADD CONSTRAINT td_modes_paiements_pkey PRIMARY KEY (id);


--
-- Name: td_paiements td_paiements_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_paiements
    ADD CONSTRAINT td_paiements_pkey PRIMARY KEY (id);


--
-- Name: td_presences td_presences_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_presences
    ADD CONSTRAINT td_presences_pkey PRIMARY KEY (id);


--
-- Name: td_presences td_presences_td_seance_id_eleve_id_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_presences
    ADD CONSTRAINT td_presences_td_seance_id_eleve_id_unique UNIQUE (td_seance_id, eleve_id);


--
-- Name: td_seances td_seances_annee_classe_date_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_seances
    ADD CONSTRAINT td_seances_annee_classe_date_unique UNIQUE (annee_id, classe_id, date);


--
-- Name: td_seances td_seances_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_seances
    ADD CONSTRAINT td_seances_pkey PRIMARY KEY (id);


--
-- Name: td_tarifs td_tarifs_annee_id_categorie_type_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_tarifs
    ADD CONSTRAINT td_tarifs_annee_id_categorie_type_unique UNIQUE (annee_id, categorie, type);


--
-- Name: td_tarifs td_tarifs_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_tarifs
    ADD CONSTRAINT td_tarifs_pkey PRIMARY KEY (id);


--
-- Name: tests tests_hash_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.tests
    ADD CONSTRAINT tests_hash_unique UNIQUE (hash);


--
-- Name: tests tests_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.tests
    ADD CONSTRAINT tests_pkey PRIMARY KEY (id);


--
-- Name: transactions transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_pkey PRIMARY KEY (id);


--
-- Name: trimestres trimestres_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.trimestres
    ADD CONSTRAINT trimestres_pkey PRIMARY KEY (id);


--
-- Name: types types_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.types
    ADD CONSTRAINT types_pkey PRIMARY KEY (id);


--
-- Name: annee_classe_frais unique_annee_classe_frais; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe_frais
    ADD CONSTRAINT unique_annee_classe_frais UNIQUE (annee_id, classe_id, frais_id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_telephone_unique; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_telephone_unique UNIQUE (telephone);


--
-- Name: versements versements_pkey; Type: CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.versements
    ADD CONSTRAINT versements_pkey PRIMARY KEY (id);


--
-- Name: benefices_date_debut_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX benefices_date_debut_index ON public.benefices USING btree (date_debut);


--
-- Name: benefices_date_fin_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX benefices_date_fin_index ON public.benefices USING btree (date_fin);


--
-- Name: conduites_inscription_annee_idx; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX conduites_inscription_annee_idx ON public.conduites USING btree (inscription_id, annee_id);


--
-- Name: eleves_matricule_unique; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE UNIQUE INDEX eleves_matricule_unique ON public.eleves USING btree (matricule);


--
-- Name: inscription_frais_inscription_id_frais_id_annee_id_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX inscription_frais_inscription_id_frais_id_annee_id_index ON public.inscription_frais USING btree (inscription_id, frais_id, annee_id);


--
-- Name: inscription_frais_unique; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE UNIQUE INDEX inscription_frais_unique ON public.inscription_frais USING btree (inscription_id, frais_id, annee_id);


--
-- Name: investissements_date_investissement_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX investissements_date_investissement_index ON public.investissements USING btree (date_investissement);


--
-- Name: investissements_statut_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX investissements_statut_index ON public.investissements USING btree (statut);


--
-- Name: investisseurs_email_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX investisseurs_email_index ON public.investisseurs USING btree (email);


--
-- Name: investisseurs_nom_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX investisseurs_nom_index ON public.investisseurs USING btree (nom);


--
-- Name: investisseurs_telephone_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX investisseurs_telephone_index ON public.investisseurs USING btree (telephone);


--
-- Name: messages_parents_paren_id_lu_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX messages_parents_paren_id_lu_index ON public.message_parents USING btree (paren_id, lu);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- Name: notifications_parents_paren_id_lu_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX notifications_parents_paren_id_lu_index ON public.notification_parents USING btree (paren_id, lu);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: versements_date_versement_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX versements_date_versement_index ON public.versements USING btree (date_versement);


--
-- Name: versements_mode_paiement_index; Type: INDEX; Schema: public; Owner: adeyemi
--

CREATE INDEX versements_mode_paiement_index ON public.versements USING btree (mode_paiement);


--
-- Name: annee_classe_frais annee_classe_frais_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe_frais
    ADD CONSTRAINT annee_classe_frais_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: annee_classe_frais annee_classe_frais_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe_frais
    ADD CONSTRAINT annee_classe_frais_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: annee_classe_frais annee_classe_frais_frais_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe_frais
    ADD CONSTRAINT annee_classe_frais_frais_id_foreign FOREIGN KEY (frais_id) REFERENCES public.frais(id) ON DELETE CASCADE;


--
-- Name: annee_trimestre annee_trimestre_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_trimestre
    ADD CONSTRAINT annee_trimestre_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: annee_trimestre annee_trimestre_trimestre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_trimestre
    ADD CONSTRAINT annee_trimestre_trimestre_id_foreign FOREIGN KEY (trimestre_id) REFERENCES public.trimestres(id) ON DELETE CASCADE;


--
-- Name: articles articles_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.articles
    ADD CONSTRAINT articles_type_id_foreign FOREIGN KEY (type_id) REFERENCES public.types(id) ON DELETE CASCADE;


--
-- Name: bulletins bulletins_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.bulletins
    ADD CONSTRAINT bulletins_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: bulletins bulletins_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.bulletins
    ADD CONSTRAINT bulletins_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: bulletins bulletins_eleve_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.bulletins
    ADD CONSTRAINT bulletins_eleve_id_foreign FOREIGN KEY (eleve_id) REFERENCES public.eleves(id) ON DELETE CASCADE;


--
-- Name: bulletins bulletins_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.bulletins
    ADD CONSTRAINT bulletins_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: bulletins bulletins_trimestre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.bulletins
    ADD CONSTRAINT bulletins_trimestre_id_foreign FOREIGN KEY (trimestre_id) REFERENCES public.trimestres(id) ON DELETE CASCADE;


--
-- Name: annee_classe classe_annee_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe
    ADD CONSTRAINT classe_annee_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: annee_classe classe_annee_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.annee_classe
    ADD CONSTRAINT classe_annee_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: classe_enseignant classe_enseignant_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_enseignant
    ADD CONSTRAINT classe_enseignant_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: classe_enseignant classe_enseignant_enseignant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_enseignant
    ADD CONSTRAINT classe_enseignant_enseignant_id_foreign FOREIGN KEY (enseignant_id) REFERENCES public.enseignants(id) ON DELETE CASCADE;


--
-- Name: classe_transitions classe_transitions_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_transitions
    ADD CONSTRAINT classe_transitions_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: classe_transitions classe_transitions_classe_superieure_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_transitions
    ADD CONSTRAINT classe_transitions_classe_superieure_id_foreign FOREIGN KEY (classe_superieure_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: classes classes_cycle_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classes
    ADD CONSTRAINT classes_cycle_id_foreign FOREIGN KEY (cycle_id) REFERENCES public.cycles(id) ON DELETE CASCADE;


--
-- Name: conduites conduites_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.conduites
    ADD CONSTRAINT conduites_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: conduites conduites_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.conduites
    ADD CONSTRAINT conduites_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: conduites conduites_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.conduites
    ADD CONSTRAINT conduites_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: conduites conduites_trimestre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.conduites
    ADD CONSTRAINT conduites_trimestre_id_foreign FOREIGN KEY (trimestre_id) REFERENCES public.trimestres(id) ON DELETE CASCADE;


--
-- Name: echeances echeances_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.echeances
    ADD CONSTRAINT echeances_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: echeances echeances_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.echeances
    ADD CONSTRAINT echeances_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: echeances echeances_frais_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.echeances
    ADD CONSTRAINT echeances_frais_id_foreign FOREIGN KEY (frais_id) REFERENCES public.frais(id) ON DELETE CASCADE;


--
-- Name: eleves eleves_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.eleves
    ADD CONSTRAINT eleves_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: eleves eleves_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.eleves
    ADD CONSTRAINT eleves_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: eleves eleves_paren_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.eleves
    ADD CONSTRAINT eleves_paren_id_foreign FOREIGN KEY (paren_id) REFERENCES public.parens(id) ON DELETE CASCADE;


--
-- Name: enseignants enseignants_cycle_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.enseignants
    ADD CONSTRAINT enseignants_cycle_id_foreign FOREIGN KEY (cycle_id) REFERENCES public.cycles(id) ON DELETE SET NULL;


--
-- Name: enseignants enseignants_matiere_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.enseignants
    ADD CONSTRAINT enseignants_matiere_id_foreign FOREIGN KEY (matiere_id) REFERENCES public.matieres(id) ON DELETE SET NULL;


--
-- Name: epreuves epreuves_examen_blanc_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.epreuves
    ADD CONSTRAINT epreuves_examen_blanc_id_foreign FOREIGN KEY (examen_blanc_id) REFERENCES public.examen_blancs(id) ON DELETE CASCADE;


--
-- Name: epreuves epreuves_matiere_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.epreuves
    ADD CONSTRAINT epreuves_matiere_id_foreign FOREIGN KEY (matiere_id) REFERENCES public.matieres(id) ON DELETE CASCADE;


--
-- Name: examen_blanc_classe examen_blanc_classe_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blanc_classe
    ADD CONSTRAINT examen_blanc_classe_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: examen_blanc_classe examen_blanc_classe_examen_blanc_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blanc_classe
    ADD CONSTRAINT examen_blanc_classe_examen_blanc_id_foreign FOREIGN KEY (examen_blanc_id) REFERENCES public.examen_blancs(id) ON DELETE CASCADE;


--
-- Name: examen_blancs examen_blancs_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blancs
    ADD CONSTRAINT examen_blancs_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: examen_blancs examen_blancs_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blancs
    ADD CONSTRAINT examen_blancs_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: examen_blancs examen_blancs_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_blancs
    ADD CONSTRAINT examen_blancs_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: examen_classes examen_classes_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_classes
    ADD CONSTRAINT examen_classes_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: examen_classes examen_classes_examen_blanc_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.examen_classes
    ADD CONSTRAINT examen_classes_examen_blanc_id_foreign FOREIGN KEY (examen_blanc_id) REFERENCES public.examen_blancs(id) ON DELETE CASCADE;


--
-- Name: finances finances_depense_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.finances
    ADD CONSTRAINT finances_depense_id_foreign FOREIGN KEY (depense_id) REFERENCES public.depenses(id) ON DELETE CASCADE;


--
-- Name: finances finances_recette_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.finances
    ADD CONSTRAINT finances_recette_id_foreign FOREIGN KEY (recette_id) REFERENCES public.recettes(id) ON DELETE CASCADE;


--
-- Name: importations_notes fk_annee; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importations_notes
    ADD CONSTRAINT fk_annee FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: budgets fk_budgets_annees; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT fk_budgets_annees FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE SET NULL;


--
-- Name: budgets fk_budgets_categories; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT fk_budgets_categories FOREIGN KEY (categorie_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: importations_notes fk_classe; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importations_notes
    ADD CONSTRAINT fk_classe FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: importations_notes fk_matiere; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importations_notes
    ADD CONSTRAINT fk_matiere FOREIGN KEY (matiere_id) REFERENCES public.matieres(id) ON DELETE CASCADE;


--
-- Name: transactions fk_transactions_categories; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT fk_transactions_categories FOREIGN KEY (categorie_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: transactions fk_transactions_comptes; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT fk_transactions_comptes FOREIGN KEY (compte_id) REFERENCES public.comptes(id) ON DELETE CASCADE;


--
-- Name: transactions fk_transactions_users; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT fk_transactions_users FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: importations_notes fk_trimestre; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importations_notes
    ADD CONSTRAINT fk_trimestre FOREIGN KEY (trimestre_id) REFERENCES public.trimestres(id) ON DELETE CASCADE;


--
-- Name: importation_notes importation_notes_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importation_notes
    ADD CONSTRAINT importation_notes_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: importation_notes importation_notes_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importation_notes
    ADD CONSTRAINT importation_notes_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: importation_notes importation_notes_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importation_notes
    ADD CONSTRAINT importation_notes_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: importation_notes importation_notes_matiere_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importation_notes
    ADD CONSTRAINT importation_notes_matiere_id_foreign FOREIGN KEY (matiere_id) REFERENCES public.matieres(id) ON DELETE CASCADE;


--
-- Name: importation_notes importation_notes_trimestre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.importation_notes
    ADD CONSTRAINT importation_notes_trimestre_id_foreign FOREIGN KEY (trimestre_id) REFERENCES public.trimestres(id) ON DELETE CASCADE;


--
-- Name: inscription_frais inscription_frais_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscription_frais
    ADD CONSTRAINT inscription_frais_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: inscription_frais inscription_frais_frais_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscription_frais
    ADD CONSTRAINT inscription_frais_frais_id_foreign FOREIGN KEY (frais_id) REFERENCES public.frais(id) ON DELETE CASCADE;


--
-- Name: inscription_frais inscription_frais_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscription_frais
    ADD CONSTRAINT inscription_frais_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: inscriptions inscriptions_ancienne_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscriptions
    ADD CONSTRAINT inscriptions_ancienne_classe_id_foreign FOREIGN KEY (ancienne_classe_id) REFERENCES public.classes(id) ON DELETE SET NULL;


--
-- Name: inscriptions inscriptions_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscriptions
    ADD CONSTRAINT inscriptions_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: inscriptions inscriptions_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscriptions
    ADD CONSTRAINT inscriptions_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: inscriptions inscriptions_eleve_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.inscriptions
    ADD CONSTRAINT inscriptions_eleve_id_foreign FOREIGN KEY (eleve_id) REFERENCES public.eleves(id) ON DELETE CASCADE;


--
-- Name: investissements investissements_investisseur_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.investissements
    ADD CONSTRAINT investissements_investisseur_id_foreign FOREIGN KEY (investisseur_id) REFERENCES public.investisseurs(id) ON DELETE CASCADE;


--
-- Name: classe_matiere matiere_classe_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_matiere
    ADD CONSTRAINT matiere_classe_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: classe_matiere matiere_classe_matiere_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.classe_matiere
    ADD CONSTRAINT matiere_classe_matiere_id_foreign FOREIGN KEY (matiere_id) REFERENCES public.matieres(id) ON DELETE CASCADE;


--
-- Name: matieres matieres_enseignant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.matieres
    ADD CONSTRAINT matieres_enseignant_id_foreign FOREIGN KEY (enseignant_id) REFERENCES public.enseignants(id) ON DELETE SET NULL;


--
-- Name: media media_galerie_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.media
    ADD CONSTRAINT media_galerie_id_foreign FOREIGN KEY (galerie_id) REFERENCES public.galeries(id) ON DELETE CASCADE;


--
-- Name: message_parents messages_parents_eleve_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.message_parents
    ADD CONSTRAINT messages_parents_eleve_id_foreign FOREIGN KEY (eleve_id) REFERENCES public.eleves(id) ON DELETE CASCADE;


--
-- Name: message_parents messages_parents_paren_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.message_parents
    ADD CONSTRAINT messages_parents_paren_id_foreign FOREIGN KEY (paren_id) REFERENCES public.parens(id) ON DELETE CASCADE;


--
-- Name: message_parents messages_parents_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.message_parents
    ADD CONSTRAINT messages_parents_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: mouvement_stocks mouvement_stocks_article_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.mouvement_stocks
    ADD CONSTRAINT mouvement_stocks_article_id_foreign FOREIGN KEY (article_id) REFERENCES public.articles(id) ON DELETE CASCADE;


--
-- Name: moyennes moyennes_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.moyennes
    ADD CONSTRAINT moyennes_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: moyennes moyennes_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.moyennes
    ADD CONSTRAINT moyennes_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: moyennes moyennes_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.moyennes
    ADD CONSTRAINT moyennes_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: moyennes moyennes_trimestre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.moyennes
    ADD CONSTRAINT moyennes_trimestre_id_foreign FOREIGN KEY (trimestre_id) REFERENCES public.trimestres(id) ON DELETE CASCADE;


--
-- Name: note_examens note_examens_matiere_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.note_examens
    ADD CONSTRAINT note_examens_matiere_id_foreign FOREIGN KEY (matiere_id) REFERENCES public.matieres(id) ON DELETE CASCADE;


--
-- Name: note_examens note_examens_participant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.note_examens
    ADD CONSTRAINT note_examens_participant_id_foreign FOREIGN KEY (participant_id) REFERENCES public.participant_examens(id) ON DELETE CASCADE;


--
-- Name: notes notes_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT notes_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id);


--
-- Name: notes notes_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT notes_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: notes notes_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT notes_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: notes notes_matiere_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT notes_matiere_id_foreign FOREIGN KEY (matiere_id) REFERENCES public.matieres(id) ON DELETE CASCADE;


--
-- Name: notes notes_trimestre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notes
    ADD CONSTRAINT notes_trimestre_id_foreign FOREIGN KEY (trimestre_id) REFERENCES public.trimestres(id) ON DELETE CASCADE;


--
-- Name: notification_parents notifications_parents_paren_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.notification_parents
    ADD CONSTRAINT notifications_parents_paren_id_foreign FOREIGN KEY (paren_id) REFERENCES public.parens(id) ON DELETE CASCADE;


--
-- Name: paiement_details paiement_details_inscription_frais_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiement_details
    ADD CONSTRAINT paiement_details_inscription_frais_id_foreign FOREIGN KEY (inscription_frais_id) REFERENCES public.inscription_frais(id) ON DELETE CASCADE;


--
-- Name: paiement_details paiement_details_paiement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiement_details
    ADD CONSTRAINT paiement_details_paiement_id_foreign FOREIGN KEY (paiement_id) REFERENCES public.paiements(id) ON DELETE CASCADE;


--
-- Name: paiements_benefices paiements_benefices_repartition_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiements_benefices
    ADD CONSTRAINT paiements_benefices_repartition_id_foreign FOREIGN KEY (repartition_id) REFERENCES public.repartitions(id) ON DELETE CASCADE;


--
-- Name: paiements paiements_frais_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiements
    ADD CONSTRAINT paiements_frais_id_foreign FOREIGN KEY (frais_id) REFERENCES public.frais(id) ON DELETE CASCADE;


--
-- Name: paiements paiements_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.paiements
    ADD CONSTRAINT paiements_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: parens parens_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.parens
    ADD CONSTRAINT parens_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: participant_examens participant_examens_examen_blanc_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.participant_examens
    ADD CONSTRAINT participant_examens_examen_blanc_id_foreign FOREIGN KEY (examen_blanc_id) REFERENCES public.examen_blancs(id) ON DELETE CASCADE;


--
-- Name: participant_examens participant_examens_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.participant_examens
    ADD CONSTRAINT participant_examens_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: passages passages_ancienne_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.passages
    ADD CONSTRAINT passages_ancienne_classe_id_foreign FOREIGN KEY (ancienne_classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: passages passages_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.passages
    ADD CONSTRAINT passages_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: passages passages_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.passages
    ADD CONSTRAINT passages_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: passages passages_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.passages
    ADD CONSTRAINT passages_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: passages passages_moyenne_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.passages
    ADD CONSTRAINT passages_moyenne_id_foreign FOREIGN KEY (moyenne_id) REFERENCES public.moyennes(id) ON DELETE CASCADE;


--
-- Name: passages passages_nouvelle_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.passages
    ADD CONSTRAINT passages_nouvelle_classe_id_foreign FOREIGN KEY (nouvelle_classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: recettes recettes_inscription_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.recettes
    ADD CONSTRAINT recettes_inscription_id_foreign FOREIGN KEY (inscription_id) REFERENCES public.inscriptions(id) ON DELETE CASCADE;


--
-- Name: recettes recettes_paiement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.recettes
    ADD CONSTRAINT recettes_paiement_id_foreign FOREIGN KEY (paiement_id) REFERENCES public.paiements(id) ON DELETE CASCADE;


--
-- Name: repartitions repartitions_benefice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.repartitions
    ADD CONSTRAINT repartitions_benefice_id_foreign FOREIGN KEY (benefice_id) REFERENCES public.benefices(id) ON DELETE CASCADE;


--
-- Name: repartitions repartitions_investissement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.repartitions
    ADD CONSTRAINT repartitions_investissement_id_foreign FOREIGN KEY (investissement_id) REFERENCES public.investissements(id) ON DELETE CASCADE;


--
-- Name: retraits_capital retraits_capital_investissement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.retraits_capital
    ADD CONSTRAINT retraits_capital_investissement_id_foreign FOREIGN KEY (investissement_id) REFERENCES public.investissements(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: td_modes_paiements td_modes_paiements_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_modes_paiements
    ADD CONSTRAINT td_modes_paiements_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: td_modes_paiements td_modes_paiements_eleve_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_modes_paiements
    ADD CONSTRAINT td_modes_paiements_eleve_id_foreign FOREIGN KEY (eleve_id) REFERENCES public.eleves(id) ON DELETE CASCADE;


--
-- Name: td_paiements td_paiements_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_paiements
    ADD CONSTRAINT td_paiements_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: td_paiements td_paiements_eleve_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_paiements
    ADD CONSTRAINT td_paiements_eleve_id_foreign FOREIGN KEY (eleve_id) REFERENCES public.eleves(id) ON DELETE CASCADE;


--
-- Name: td_presences td_presences_eleve_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_presences
    ADD CONSTRAINT td_presences_eleve_id_foreign FOREIGN KEY (eleve_id) REFERENCES public.eleves(id) ON DELETE CASCADE;


--
-- Name: td_presences td_presences_td_seance_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_presences
    ADD CONSTRAINT td_presences_td_seance_id_foreign FOREIGN KEY (td_seance_id) REFERENCES public.td_seances(id) ON DELETE CASCADE;


--
-- Name: td_seances td_seances_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_seances
    ADD CONSTRAINT td_seances_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: td_tarifs td_tarifs_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.td_tarifs
    ADD CONSTRAINT td_tarifs_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: tests tests_annee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.tests
    ADD CONSTRAINT tests_annee_id_foreign FOREIGN KEY (annee_id) REFERENCES public.annees(id) ON DELETE CASCADE;


--
-- Name: tests tests_classe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.tests
    ADD CONSTRAINT tests_classe_id_foreign FOREIGN KEY (classe_id) REFERENCES public.classes(id) ON DELETE CASCADE;


--
-- Name: tests tests_matiere_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.tests
    ADD CONSTRAINT tests_matiere_id_foreign FOREIGN KEY (matiere_id) REFERENCES public.matieres(id) ON DELETE CASCADE;


--
-- Name: tests tests_trimestre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.tests
    ADD CONSTRAINT tests_trimestre_id_foreign FOREIGN KEY (trimestre_id) REFERENCES public.trimestres(id) ON DELETE CASCADE;


--
-- Name: versements versements_investissement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: adeyemi
--

ALTER TABLE ONLY public.versements
    ADD CONSTRAINT versements_investissement_id_foreign FOREIGN KEY (investissement_id) REFERENCES public.investissements(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict eH0NKH2YkP43p71yogBnutii5SOAIWkGiEe3zUQhec26oAt8OUdsOfFRXOQcWfu

