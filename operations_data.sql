--
-- PostgreSQL database dump
--

\restrict zK60BbrrX1mTxWkyn0AxAIQacZVBXSbjC9aUOtOsTpDYYtfkfgWjkU4YhWt5VBI

-- Dumped from database version 16.14 (Ubuntu 16.14-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.14 (Ubuntu 16.14-0ubuntu0.24.04.1)

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
128	2026-03-14	scolarités	50000.00	scolaité	recette	2026-07-13 10:34:42	2026-07-13 10:34:42
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
108	2026-03-05	scolarités	65000.00	scolarité	recette	2026-07-13 10:06:30	2026-07-13 10:06:30
109	2026-02-12	scolarités	60000.00	scolarité	recette	2026-07-13 10:09:53	2026-07-13 10:09:53
110	2026-04-22	scolarités	65000.00	scolarité	recette	2026-07-13 10:11:21	2026-07-13 10:11:21
111	2026-05-04	scolarités	42000.00	scolarité	recette	2026-07-13 10:13:48	2026-07-13 10:13:48
112	2026-03-02	scolarités	50000.00	scolarité	recette	2026-07-13 10:15:08	2026-07-13 10:15:08
113	2026-03-19	scolarités	60000.00	scolarité	recette	2026-07-13 10:16:15	2026-07-13 10:16:15
114	2026-04-01	scolarités	65000.00	scolarité	recette	2026-07-13 10:17:09	2026-07-13 10:17:09
115	2026-04-27	scolarités	65000.00	scolarité	recette	2026-07-13 10:18:08	2026-07-13 10:18:08
116	2026-12-09	scolarités	20000.00	scolarité	recette	2026-07-13 10:19:20	2026-07-13 10:19:20
117	2026-06-03	scolarités	60000.00	scolarité	recette	2026-07-13 10:21:49	2026-07-13 10:21:49
118	2026-03-30	scolarités	60000.00	scolarité	recette	2026-07-13 10:22:38	2026-07-13 10:22:38
119	2026-02-03	scolarités	60000.00	scolarité	recette	2026-07-13 10:23:56	2026-07-13 10:23:56
120	2026-04-17	scolarités	20000.00	scolarité	recette	2026-07-13 10:24:37	2026-07-13 10:24:37
121	2026-06-03	scolarités	60000.00	scolarité	recette	2026-07-13 10:25:20	2026-07-13 10:25:20
122	2026-02-18	scolarités	60000.00	scolarité	recette	2026-07-13 10:26:35	2026-07-13 10:26:35
123	2025-12-08	scolarités	25000.00	scolarité	recette	2026-07-13 10:27:49	2026-07-13 10:27:49
124	2025-09-11	scolarités	60000.00	scolarité	recette	2026-07-13 10:29:29	2026-07-13 10:29:29
127	2026-04-28	scolarités	55000.00	scolarité	recette	2026-07-13 10:33:26	2026-07-13 10:33:26
126	2026-05-14	scolarités	55000.00	scolarité	recette	2026-07-13 10:32:07	2026-07-13 12:48:58
129	2026-05-28	scolarités	60000.00	scolarité	recette	2026-07-13 10:35:59	2026-07-13 10:35:59
130	2026-05-28	scolarités	60000.00	scolarité	recette	2026-07-13 10:36:52	2026-07-13 10:36:52
131	2026-11-24	scolarités	40000.00	scolarité	recette	2026-07-13 10:37:41	2026-07-13 10:37:41
132	2026-04-23	scolarités	60000.00	scolarité	recette	2026-07-13 10:39:15	2026-07-13 10:39:15
133	2026-04-28	scolarités	55000.00	scolarité	recette	2026-07-13 10:44:24	2026-07-13 10:44:24
134	2026-04-22	scolarités	12500.00	scolarité	recette	2026-07-13 10:45:07	2026-07-13 10:45:07
135	2026-01-27	scolarités	60000.00	scolarité	recette	2026-07-13 10:46:26	2026-07-13 10:46:26
136	2025-09-16	scolarités	55000.00	scolarité	recette	2026-07-13 10:48:27	2026-07-13 10:48:27
137	2025-12-04	scolarités	38000.00	scolarité	recette	2026-07-13 10:49:26	2026-07-13 10:49:26
138	2026-05-04	scolarités	65000.00	scolarité	recette	2026-07-13 10:49:59	2026-07-13 10:49:59
139	2026-05-18	scolarités	45000.00	scolarité	recette	2026-07-13 10:51:59	2026-07-13 10:51:59
140	2026-02-10	scolarités	65000.00	scolarité	recette	2026-07-13 10:52:52	2026-07-13 10:52:52
141	2026-09-16	scolarités	65000.00	scolarité	recette	2026-07-13 10:54:24	2026-07-13 10:54:24
142	2025-02-19	scolarités	50000.00	scolarité	recette	2026-07-13 10:55:53	2026-07-13 10:55:53
143	2026-06-03	scolarités	80000.00	scolarité	recette	2026-07-13 10:56:42	2026-07-13 10:56:42
144	2026-04-17	scolarités	70000.00	scolarité	recette	2026-07-13 10:57:32	2026-07-13 10:57:32
145	2026-05-05	scolarités	60000.00	scolarité	recette	2026-07-13 10:58:27	2026-07-13 10:58:27
146	2026-03-16	scolarités	2000.00	scolarité	recette	2026-07-13 10:59:13	2026-07-13 10:59:13
147	2026-01-13	scolarités	80000.00	scolarité	recette	2026-07-13 10:59:59	2026-07-13 10:59:59
148	2025-10-07	scolarités	80000.00	scolarité	recette	2026-07-13 11:01:19	2026-07-13 11:01:19
149	2026-03-02	scolarités	50000.00	scolarité	recette	2026-07-13 11:02:41	2026-07-13 11:02:41
150	2026-04-30	scolarités	80000.00	scolarité	recette	2026-07-13 11:03:19	2026-07-13 11:03:19
151	2026-01-31	scolarités	90000.00	scolarité	recette	2026-07-13 11:04:28	2026-07-13 11:04:28
152	2026-06-03	scolarités	90000.00	scolarité	recette	2026-07-13 11:05:23	2026-07-13 11:05:23
153	2026-03-27	scolarités	90000.00	scolarité	recette	2026-07-13 11:06:10	2026-07-13 11:06:10
154	2026-04-20	scolarités	65700.00	scolarité	recette	2026-07-13 11:07:17	2026-07-13 11:07:17
155	2026-05-06	scolarités	90000.00	scolarité	recette	2026-07-13 11:08:19	2026-07-13 11:08:19
156	2026-04-20	scolarités	70000.00	scolarité	recette	2026-07-13 11:09:21	2026-07-13 11:09:21
157	2026-03-20	scolarités	90000.00	scolarité	recette	2026-07-13 11:10:29	2026-07-13 11:10:29
158	2026-05-08	scolarités	90000.00	scolarité	recette	2026-07-13 11:20:25	2026-07-13 11:20:25
159	2026-09-15	scolarités	90000.00	scolarité	recette	2026-07-13 11:21:24	2026-07-13 11:21:24
160	2026-03-02	scolarités	60000.00	scolarité	recette	2026-07-13 11:22:36	2026-07-13 11:22:36
161	2026-08-27	scolarités	85000.00	scolarité	recette	2026-07-13 11:23:16	2026-07-13 11:23:16
162	2026-01-12	scolarités	85000.00	scolarité	recette	2026-07-13 11:23:52	2026-07-13 11:23:52
163	2026-05-05	scolarités	90000.00	scolarité	recette	2026-07-13 11:25:15	2026-07-13 11:25:15
164	2026-05-13	scolarités	110000.00	scolarité	recette	2026-07-13 11:26:29	2026-07-13 11:26:29
165	2026-04-01	scolarités	90000.00	scolarité	recette	2026-07-13 11:28:03	2026-07-13 11:28:03
166	2026-05-06	scolarités	110000.00	scolarité	recette	2026-07-13 11:28:45	2026-07-13 11:28:45
167	2026-04-16	scolarités	90000.00	scolarité	recette	2026-07-13 11:29:58	2026-07-13 11:29:58
168	2026-01-05	scolarités	120000.00	scolarité	recette	2026-07-13 11:31:00	2026-07-13 11:31:00
169	2025-09-15	scolarités	13500.00	scolarité	recette	2026-07-13 11:32:17	2026-07-13 11:32:17
170	2026-07-06	scolarités	80000.00	scolarité	recette	2026-07-13 11:34:31	2026-07-13 11:34:31
202	2025-09-09	autres dépenses	67500.00	peinture maternelle	dépense	2026-07-13 12:25:42	2026-07-13 12:25:42
203	2025-09-09	autres dépenses	2400.00	Essence	dépense	2026-07-13 12:26:40	2026-07-13 12:26:40
204	2025-09-09	autres dépenses	15000.00	Main d'oeuvre peinture	dépense	2026-07-13 12:28:09	2026-07-13 12:28:09
205	2025-09-12	autres dépenses	10000.00	Menuisier	dépense	2026-07-13 12:29:02	2026-07-13 12:29:02
206	2025-09-23	autres dépenses	10000.00	Remerciement Mr Justin	dépense	2026-07-13 12:31:14	2026-07-13 12:31:14
207	2025-11-07	autres dépenses	500.00	Déplacement pour la circonscription	dépense	2026-07-13 12:34:04	2026-07-13 12:34:04
208	2025-11-12	autres dépenses	5000.00	Crédit SBEE	dépense	2026-07-13 12:35:21	2026-07-13 12:35:21
209	2026-11-20	autres dépenses	1000.00	Papier ram	dépense	2026-07-13 12:36:15	2026-07-13 12:36:15
210	2025-11-25	autres dépenses	2000.00	Achat de castagnette	dépense	2026-07-13 12:37:14	2026-07-13 12:37:14
211	2025-12-28	autres dépenses	2000.00	Credit SBEE	dépense	2026-07-13 12:40:11	2026-07-13 12:40:11
212	2025-12-08	autres dépenses	5000.00	Cedit SBEE	dépense	2026-07-13 12:41:08	2026-07-13 12:41:08
213	2025-12-11	autres dépenses	1000.00	Synthèse RUP	dépense	2026-07-13 12:42:22	2026-07-13 12:42:22
214	2026-01-20	autres dépenses	5000.00	Cedit compteur	dépense	2026-07-13 12:44:41	2026-07-13 12:44:41
125	2026-05-15	scolarités	55000.00	scolarité	recette	2026-07-13 10:30:39	2026-07-13 12:45:55
215	2026-01-27	autres dépenses	10000.00	Gravie de décoration	dépense	2026-07-13 12:47:12	2026-07-13 12:47:12
216	2026-01-29	autres dépenses	4000.00	Photocopie de l'UP maternelle	dépense	2026-07-13 12:49:06	2026-07-13 12:49:06
217	2026-01-29	autres dépenses	5000.00	Crédit compteur	dépense	2026-07-13 12:50:26	2026-07-13 12:50:26
218	2026-02-06	photocopie	650.00	Photocopies envoyés par la RUP	dépense	2026-07-13 12:51:53	2026-07-13 12:51:53
219	2026-02-17	électricité	5000.00	Crédit compteur	dépense	2026-07-13 12:53:04	2026-07-13 12:53:04
220	2026-02-17	autres dépenses	1000.00	Deplacement mr OLAAFA	dépense	2026-07-13 12:54:14	2026-07-13 12:54:14
221	2026-02-26	électricité	8800.00	Payement de la facture SBEE	dépense	2026-07-13 12:55:53	2026-07-13 12:55:53
222	2026-03-09	autres dépenses	1000.00	Achat d'un seau d'eau	dépense	2026-07-13 12:59:30	2026-07-13 12:59:30
223	2026-03-12	autres dépenses	5000.00	tableau pour cs le glorieux	dépense	2026-07-13 13:00:40	2026-07-13 13:00:40
224	2026-03-12	autres dépenses	1200.00	Photocopies et impression chez Amirath	dépense	2026-07-13 13:01:37	2026-07-13 13:01:37
225	2026-02-13	autres dépenses	300.00	Balaie pour la classe	dépense	2026-07-13 13:03:09	2026-07-13 13:03:09
226	2026-03-16	photocopie	900.00	Photocopies devoir du primaire envoyéés par la RUP	dépense	2026-07-13 13:04:46	2026-07-13 13:04:46
227	2026-03-24	photocopie	2000.00	Bulletins du deuxième trimestre plus enveloppe	dépense	2026-07-13 13:06:17	2026-07-13 13:06:17
228	2026-03-26	photocopie	400.00	synthèse des évaluation RUP	dépense	2026-07-13 13:07:26	2026-07-13 13:07:26
229	2026-03-26	forfait	500.00	Forfait internet censeur pour l'importation	dépense	2026-07-13 13:08:58	2026-07-13 13:08:58
230	2026-03-27	photocopie	600.00	Impression à couleur pour les coins de vie de la maternelle	dépense	2026-07-13 13:10:11	2026-07-13 13:10:11
231	2026-03-30	autres dépenses	4000.00	Sandwich pour l'UP	dépense	2026-07-13 13:11:39	2026-07-13 13:11:39
232	2026-03-30	autres dépenses	500.00	Glace et papier torchon pour l'UP	dépense	2026-07-13 13:13:17	2026-07-13 13:13:17
233	2026-03-30	autres dépenses	8900.00	Boissons pour l'UP	dépense	2026-07-13 13:14:04	2026-07-13 13:14:04
234	2026-03-30	autres dépenses	3000.00	Sandwich de maman Toholou pour l'UP	dépense	2026-07-13 13:15:09	2026-07-13 13:15:09
235	2026-03-30	autres dépenses	3000.00	Pain pour l'UP	dépense	2026-07-13 13:16:38	2026-07-13 13:16:38
236	2026-03-30	autres dépenses	500.00	Zem aller et retour avec achat du sacpour le pain de l'UP	dépense	2026-07-13 13:17:48	2026-07-13 13:17:48
237	2026-04-01	autres dépenses	5000.00	Fête de JIF-JIH la RUP	dépense	2026-07-13 13:19:25	2026-07-13 13:19:25
238	2026-04-01	forfait	500.00	Forfait internet pour cenceur	dépense	2026-07-13 13:20:41	2026-07-13 13:20:41
239	2026-04-01	autres dépenses	14000.00	Achat d'encre dans la photocopieuse	dépense	2026-07-13 13:21:54	2026-07-13 13:21:54
240	2026-04-07	réparation	10000.00	Réparation de la clé du portail	dépense	2026-07-13 13:23:03	2026-07-13 13:23:03
241	2026-04-28	autres dépenses	500.00	Achat de multiprise	dépense	2026-07-13 13:25:36	2026-07-13 13:25:36
242	2026-04-28	autres dépenses	1000.00	Zem qui a amené la caisse	dépense	2026-07-13 13:26:49	2026-07-13 13:26:49
243	2026-04-28	autres dépenses	500.00	retrait des frais de scolarite des kingnidé	dépense	2026-07-13 13:28:58	2026-07-13 13:28:58
244	2026-04-23	électricité	5000.00	Achat de crédit compteur	dépense	2026-07-13 13:30:18	2026-07-13 13:30:18
245	2026-04-30	autres dépenses	300.00	Achat de chiffon	dépense	2026-07-13 13:31:06	2026-07-13 13:31:06
246	2026-04-30	autres dépenses	1500.00	Soudeur de la plaque du complexe scolaire le glorieux	dépense	2026-07-13 13:32:39	2026-07-13 13:32:39
247	2026-05-04	autres dépenses	200.00	Forfait internet pour les epreuves d'évaluation du collège	dépense	2026-07-13 13:34:09	2026-07-13 13:34:09
248	2026-05-06	autres dépenses	1000.00	Déplacement censeur et directeur	dépense	2026-07-13 13:36:01	2026-07-13 13:36:01
249	2026-05-08	réparation	26500.00	Evacuation d'eau de la dalle	dépense	2026-07-13 13:37:56	2026-07-13 13:37:56
250	2026-05-28	autres dépenses	7500.00	Participation des enseignants pour le soutient du deuil d'un enseignant RUP	dépense	2026-07-13 13:39:36	2026-07-13 13:39:36
251	2026-05-29	autres dépenses	6000.00	Bachotage anglais	dépense	2026-07-13 13:40:31	2026-07-13 13:40:31
252	2026-05-29	autres dépenses	3000.00	Payement des frais des photos de noel	dépense	2026-07-13 13:43:05	2026-07-13 13:43:05
253	2026-06-03	électricité	5000.00	Crédit compteur	dépense	2026-07-13 13:43:59	2026-07-13 13:43:59
254	2026-06-03	autres dépenses	2000.00	Location de l'echelle pour la plaque du collège	dépense	2026-07-13 13:44:58	2026-07-13 13:44:58
255	2026-06-03	autres dépenses	3000.00	Installation de la plaque	dépense	2026-07-13 13:45:39	2026-07-13 13:45:39
256	2025-11-14	ADEYEMI Kolawolé	10000.00	Prêt chez la secrétaire	dépense	2026-07-13 13:49:00	2026-07-13 13:49:00
257	2026-10-13	YESSOUFOU A. Affissou	27000.00	Prêt dans la scolarité	dépense	2026-07-13 13:51:52	2026-07-13 13:51:52
258	2025-09-09	autres dépenses	20000.00	Payement à FACAF pour les fournitures de l'école	dépense	2026-07-13 13:54:03	2026-07-13 13:54:03
259	2026-07-02	autres dépenses	49000.00	salaire mr GUEDOU	dépense	2026-07-13 14:02:48	2026-07-13 14:02:48
260	2026-05-29	autres dépenses	365000.00	Salaire de justine	dépense	2026-07-13 14:05:06	2026-07-13 14:05:06
261	2026-05-29	autres dépenses	240000.00	salaire de AUREL	dépense	2026-07-13 14:07:32	2026-07-13 14:07:32
262	2026-04-16	autres dépenses	61000.00	SALAIRE DE Mr GOUDJO	dépense	2026-07-13 14:09:17	2026-07-13 14:09:17
264	2026-05-18	autres dépenses	360000.00	Salaire du maitre KOUDOUS	dépense	2026-07-13 14:13:09	2026-07-13 14:13:09
265	2026-05-21	autres dépenses	54000.00	Salaire de mr HOUNKPEVI	dépense	2026-07-13 14:14:41	2026-07-13 14:14:41
266	2026-05-12	autres dépenses	106000.00	Salaire de mr GBAMIGBOLA	dépense	2026-07-13 14:16:11	2026-07-13 14:16:11
267	2026-05-04	autres dépenses	156000.00	Salaire du mr ADEYEMI	dépense	2026-07-13 14:17:28	2026-07-13 14:17:28
268	2026-05-11	autres dépenses	65000.00	Salaire du mr KIKI	dépense	2026-07-13 14:19:09	2026-07-13 14:19:09
269	2026-05-11	autres dépenses	224000.00	Salaire du mr OLAAFA	dépense	2026-07-13 14:21:08	2026-07-13 14:21:08
270	2026-05-08	autres dépenses	190000.00	Salaire du maitre AKADIRI	dépense	2026-07-13 14:22:42	2026-07-13 14:22:42
271	2026-04-29	autres dépenses	67000.00	Salaire du mr SALAKO	dépense	2026-07-13 14:24:21	2026-07-13 14:24:21
272	2026-04-30	autres dépenses	99000.00	Salaire du mr ANGLO	dépense	2026-07-13 14:25:49	2026-07-13 14:25:49
273	2026-03-05	autres dépenses	33000.00	Salaire du mr AYENA	dépense	2026-07-13 14:27:34	2026-07-13 14:27:34
274	2026-04-28	autres dépenses	123000.00	Salaire du mr CODJA	dépense	2026-07-13 14:30:37	2026-07-13 14:30:37
275	2026-04-27	autres dépenses	32000.00	Salaire du mr KOSSOLOU	dépense	2026-07-13 14:32:03	2026-07-13 14:32:03
276	2026-04-20	autres dépenses	141000.00	Salaire du mr AGBOBATINKPO	dépense	2026-07-13 14:34:32	2026-07-13 14:34:32
277	2026-04-24	autres dépenses	109000.00	Salaire du mr FARID	dépense	2026-07-13 14:36:02	2026-07-13 14:36:02
278	2026-04-23	autres dépenses	129000.00	Salaire du mr SOGAN	dépense	2026-07-13 14:37:29	2026-07-13 14:37:29
279	2026-04-22	autres dépenses	51000.00	Salaire du mr FATONDJI	dépense	2026-07-13 14:39:07	2026-07-13 14:39:07
280	2026-04-20	autres dépenses	105000.00	Salaire du mr KOTIN	dépense	2026-07-13 14:40:21	2026-07-13 14:40:21
281	2026-04-20	autres dépenses	55000.00	Salaire du mr SOSSOU	dépense	2026-07-13 14:42:03	2026-07-13 14:42:03
282	2026-04-20	autres dépenses	72000.00	Salaire du mr AGOSSOU	dépense	2026-07-13 14:43:37	2026-07-13 14:43:37
283	2026-04-20	autres dépenses	108000.00	Salaire du mr MEHOUENOU	dépense	2026-07-13 14:45:13	2026-07-13 14:45:13
284	2025-10-31	autres dépenses	100000.00	Montant déposé dans le compte épargne puis utiliser pour la réalisation des tables et bancs	dépense	2026-07-16 09:09:46	2026-07-16 09:09:46
285	2025-12-19	autres dépenses	12750.00	complément pour payer le DJ de la fête de Noël	dépense	2026-07-16 09:13:44	2026-07-16 09:13:44
286	2025-10-24	autres dépenses	139550.00	Dépenses effectuées de surplus dans le compte des uniformes	dépense	2026-07-16 09:17:04	2026-07-16 09:17:04
287	2026-04-29	autres dépenses	305000.00	Salaire de monsieur YESSOUFOU	dépense	2026-07-16 10:33:51	2026-07-16 10:33:51
\.


--
-- Name: operations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: adeyemi
--

SELECT pg_catalog.setval('public.operations_id_seq', 287, true);


--
-- PostgreSQL database dump complete
--

\unrestrict zK60BbrrX1mTxWkyn0AxAIQacZVBXSbjC9aUOtOsTpDYYtfkfgWjkU4YhWt5VBI

