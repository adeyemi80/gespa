<?php

/**
 * créer un seeder: php artisan make:seeder ClasseTransitionsSeeder
 * 
 * lancer un seeder: php artisan db:seed --class=ClasseTransitionsSeeder
 * 
 * Base de Données: créer un utilisateur: sudo su postgres puis: createUser NomUtilisateur
 *  et pour créer la Base de données: createDB NomBaseDeDonnées -O NomUtilisateur
 * 
 * Pour travailler sur la base de Données d' un projet: monter sur le projet(cd nom du projet) 
 * et psql NomBaseDeDonnées
 * 
 * changer une clée étrangère et sa contrainte: 
 * ALTER TABLE passages RENAME COLUMN eleve_id TO inscription_id;
 * ALTER TABLE passages DROP CONSTRAINT passages_eleve_id_foreign;
 * ALTER TABLE passages ADD CONSTRAINT passages_inscription_id_foreign
 * FOREIGN KEY (inscription_id) REFERENCES inscriptions(id) ON DELETE CASCADE;
 * 
 * Changer le numéro d'une clée étrangère non liée: UPDATE annees SET classe_id = 5 WHERE classe_id = 12;
 * Ramener le compteur d'une table vidée au départ:  ALTER SEQUENCE inscription_frais_id_seq RESTART WITH 1;


 * 
 * 
 * 
 * 
 * 
 *
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */