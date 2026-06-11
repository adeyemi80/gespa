<?php

namespace App\Services;

class TestFileParser
{
    /**
     * Analyse le nom du fichier et renvoie type, classes et matière
     */
    public function parseFilename(string $filename): array
    {
        // 🔥 Nom sans extension
        $name = strtolower(pathinfo($filename, PATHINFO_FILENAME));

        // 🔥 NORMALISATION DES ACCENTS
        $name = str_replace(
            ['é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'],
            ['e', 'e', 'e', 'e', 'a', 'a', 'i', 'i', 'o', 'u', 'u'],
            $name
        );

        // 🔥 Découpage
        $parts = preg_split('/[_\-]+/', $name);

        $type = null;
        $classes = [];
        $matiere = null;

        // 🔥 Types
        $types = [
            'devoir1', 'devoir2',
            'interrogation1', 'interrogation2', 'interrogation3',
            'examen'
        ];

        // 🔥 Mapping classes COMPLET
        $mappingClasses = [
            '6eme' => '6ème',
            '5eme' => '5ème',
            '4eme' => '4ème',
            '3eme' => '3ème',

            '2ndea' => '2ndeA',
            '2ndeb' => '2ndeB',
            '2ndec' => '2ndeC',
            '2nded' => '2ndeD',

            '1erea' => '1èreA',
            '1ereb' => '1èreB',
            '1erec' => '1èreC',
            '1ered' => '1èreD',

            'tlea' => 'TleA',
            'tleb' => 'TleB',
            'tlec' => 'TleC',
            'tled' => 'TleD',
        ];

        // 🔥 Matières
        $matieres = [
            'math' => 'Mathématiques',
            'maths' => 'Mathématiques',
            'fr' => 'Français',
            'fran' => 'Français',
             'fran' => 'FRANÇAIS',
            'francais' => 'Français',
            'ang' => 'Anglais',
             'ang' => 'ANGLAIS',
            'anglais' => 'Anglais',
            'pct' => 'PCT',
            'svt' => 'SVT',
            'hg' => 'Histoire-Géographie',
            //'hg' => 'HISTOIRE-GEOGRAPHIE',
            'hist_geo' => 'Histoire-Géographie',
            'eco' => 'Économie',
            'espa' => 'Espagnol',
             'espa' => 'ESPAGNOL',
            'lec' => 'Lecture',
             'lect' => 'Lecture',
             //'lect' => 'LECTURE',
            'com' => 'Communication écrite',
             //'com' => 'COMMUNICATION ECRITE',
            'ce' => 'Communication écrite',
            'philo' => 'Philosophie',
            'philosophie' => 'Philosophie',
            'philosophie' => 'PHILOSOPHIE', // sécurité
            'eps' => 'EPS',
        ];

        foreach ($parts as $part) {

            // 🔹 TYPE
            foreach ($types as $t) {
                if (str_starts_with($part, $t)) {
                    $type = $t;
                    break;
                }
            }

            // 🔹 CLASSES MULTIPLES (2ndeCD, 1ereCD, tleAB...)
            if (preg_match('/^(2nde|1ere|tle)([a-d]{1,2})$/i', $part, $m)) {

                $prefix = strtolower($m[1]);
                $suffixes = str_split(strtoupper($m[2]));

                foreach ($suffixes as $s) {
                    $code = strtolower($prefix . $s);

                    if (isset($mappingClasses[$code])) {
                        $classes[] = $mappingClasses[$code];
                    }
                }

            } else {
                // 🔹 CLASSE SIMPLE
                $code = strtolower($part);

                if (isset($mappingClasses[$code])) {
                    $classes[] = $mappingClasses[$code];
                }
            }

            // 🔹 MATIÈRE
            if (array_key_exists($part, $matieres)) {
                $matiere = $matieres[$part];
            }
        }

        // 🔥 SUPPRIMER DOUBLONS
        $classes = array_values(array_unique($classes));

        // 🔥 DEBUG SI ERREUR
        if (!$type || empty($classes) || !$matiere) {
            logger()->warning('Fichier invalide', [
                'filename' => $filename,
                'type' => $type,
                'classes' => $classes,
                'matiere' => $matiere,
                'parts' => $parts
            ]);
        }

        return [
            'type' => $type,
            'classes' => $classes,
            'matiere_name' => $matiere,
        ];
    }
}