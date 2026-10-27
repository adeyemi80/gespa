<?php

namespace App\Livewire;

use App\Exports\EnseignantsModeleExport;
use App\Models\Cycle;
use App\Models\Enseignant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportEnseignants extends Component
{
    use WithFileUploads;

    public $fichier = null;
    public $preview = [];
    public $headers = [];
    public $erreurs = [];

    /**
     * Lecture et validation du fichier Excel.
     */
    public function updatedFichier(): void
    {
        $this->reset([
            'preview',
            'headers',
            'erreurs',
        ]);

        if (!$this->fichier) {
            return;
        }

        try {
            $this->validate([
                'fichier' => [
                    'required',
                    'file',
                    'mimes:xlsx,xls,csv',
                    'max:10240',
                ],
            ]);

            $feuilles = Excel::toArray([], $this->fichier);
            $rows = $feuilles[0] ?? [];

            if (empty($rows)) {
                $this->erreurs[] = 'Le fichier est vide.';
                return;
            }

            $this->headers = array_map(
                fn ($header) => trim((string) $header),
                $rows[0] ?? []
            );

            unset($rows[0]);

            foreach ($rows as $row) {
                $ligneVide = true;

                foreach ($row as $cellule) {
                    if (trim((string) $cellule) !== '') {
                        $ligneVide = false;
                        break;
                    }
                }

                if (!$ligneVide) {
                    $this->preview[] = array_values($row);
                }
            }

            $this->preview = array_values($this->preview);

            if (empty($this->preview)) {
                $this->erreurs[] =
                    'Aucune donnée à importer dans le fichier.';

                return;
            }

            $this->validerDonnees();

        } catch (\Throwable $e) {
            Log::error(
                'Erreur lecture import enseignants',
                ['exception' => $e]
            );

            $this->erreurs[] =
                'Erreur lors de la lecture du fichier Excel.';
        }
    }

    /**
     * Normaliser un texte pour faciliter les recherches.
     */
    private function normaliserTexte($texte): string
    {
        $texte = trim((string) $texte);

        if ($texte === '') {
            return '';
        }

        $texte = mb_strtolower($texte, 'UTF-8');

        $texte = strtr($texte, [
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ä' => 'a',
            'ã' => 'a',
            'å' => 'a',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'ö' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'ý' => 'y',
            'ÿ' => 'y',
            'œ' => 'oe',
            'æ' => 'ae',
        ]);

        $texte = preg_replace('/[^a-z0-9]+/u', ' ', $texte);
        $texte = preg_replace('/\s+/', ' ', $texte);

        return trim($texte);
    }

    /**
     * Rechercher un cycle sans tenir compte
     * de la casse ni des accents.
     */
    private function trouverCycle(?string $nom): ?Cycle
    {
        $nomNormalise = $this->normaliserTexte($nom);

        if ($nomNormalise === '') {
            return null;
        }

        foreach (Cycle::all() as $cycle) {
            if (
                $this->normaliserTexte($cycle->nom)
                === $nomNormalise
            ) {
                return $cycle;
            }
        }

        return null;
    }

    /**
     * Générer un matricule unique.
     */
    private function genererMatricule(): string
    {
        do {
            $prochainId = ((int) Enseignant::max('id')) + 1;

            $matricule = 'ENS-' . str_pad(
                (string) $prochainId,
                4,
                '0',
                STR_PAD_LEFT
            );

            if (
                Enseignant::where('matricule', $matricule)->exists()
            ) {
                $matricule = 'ENS-' . strtoupper(
                    substr(md5(uniqid('', true)), 0, 8)
                );
            }
        } while (
            Enseignant::where('matricule', $matricule)->exists()
        );

        return $matricule;
    }

    /**
     * Ajouter une erreur de validation.
     */
    private function ajouterErreur(
        int $ligne,
        string $message
    ): void {
        $this->erreurs[] = "Ligne {$ligne} : {$message}";
    }

    /**
     * Valider les données avant importation.
     *
     * Colonnes attendues :
     * 0  nom
     * 1  prenom
     * 2  date_naissance
     * 3  sexe
     * 4  adresse
     * 5  telephone
     * 6  email
     * 7  specialite
     * 8  grade
     * 9  date_embauche
     * 10 statut
     * 11 cycle
     *
     * Les colonnes 12 (matières) et 13 (classes) peuvent
     * toujours exister dans le fichier, mais sont ignorées.
     */
    private function validerDonnees(): void
    {
        $emailsRencontres = [];

        foreach ($this->preview as $index => $row) {
            $ligne = $index + 2;

            $nom = trim((string) ($row[0] ?? ''));
            $prenom = trim((string) ($row[1] ?? ''));

            $sexe = strtoupper(
                trim((string) ($row[3] ?? ''))
            );

            $email = trim(
                str_replace(',', '.', (string) ($row[6] ?? ''))
            );

            $email = $email !== ''
                ? strtolower($email)
                : null;

            $specialite = trim((string) ($row[7] ?? ''));

            // Le cycle reste conservé.
            $cycleNom = trim((string) ($row[11] ?? ''));

            if ($nom === '') {
                $this->ajouterErreur(
                    $ligne,
                    'nom obligatoire'
                );
            }

            if ($prenom === '') {
                $this->ajouterErreur(
                    $ligne,
                    'prénom obligatoire'
                );
            }

            if (!in_array($sexe, ['M', 'F'], true)) {
                $this->ajouterErreur(
                    $ligne,
                    'sexe doit être M ou F'
                );
            }

            if ($specialite === '') {
                $this->ajouterErreur(
                    $ligne,
                    'spécialité obligatoire'
                );
            }

            if ($email !== null) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->ajouterErreur(
                        $ligne,
                        "email invalide ({$email})"
                    );
                } elseif (
                    Enseignant::where('email', $email)->exists()
                ) {
                    $this->ajouterErreur(
                        $ligne,
                        "email déjà utilisé ({$email})"
                    );
                } elseif (isset($emailsRencontres[$email])) {
                    $this->ajouterErreur(
                        $ligne,
                        "email présent plusieurs fois dans le fichier ({$email})"
                    );
                } else {
                    $emailsRencontres[$email] = true;
                }
            }

            // Le cycle reste obligatoire.
            if ($cycleNom === '') {
                $this->ajouterErreur(
                    $ligne,
                    'cycle obligatoire'
                );
            } elseif (!$this->trouverCycle($cycleNom)) {
                $this->ajouterErreur(
                    $ligne,
                    "cycle introuvable ({$cycleNom})"
                );
            }

            // Vérification des dates.
            foreach ([2, 9] as $indexDate) {
                $valeurDate = $row[$indexDate] ?? null;

                if (
                    $valeurDate !== null
                    && trim((string) $valeurDate) !== ''
                    && !$this->dateValide($valeurDate)
                ) {
                    $libelle = $indexDate === 2
                        ? 'date de naissance'
                        : "date d'embauche";

                    $this->ajouterErreur(
                        $ligne,
                        "{$libelle} invalide"
                    );
                }
            }
        }
    }

    /**
     * Vérifier une date Excel ou texte.
     */
    private function dateValide($date): bool
    {
        if ($date === null || trim((string) $date) === '') {
            return true;
        }

        try {
            if (is_numeric($date)) {
                Date::excelToDateTimeObject($date);
                return true;
            }

            $dateTexte = trim((string) $date);

            foreach (
                ['d/m/Y', 'd-m-Y', 'd.m.Y', 'Y-m-d'] as $format
            ) {
                $objet = \DateTime::createFromFormat(
                    '!' . $format,
                    $dateTexte
                );

                $erreurs = \DateTime::getLastErrors();

                if (
                    $objet !== false
                    && (
                        $erreurs === false
                        || (
                            $erreurs['warning_count'] === 0
                            && $erreurs['error_count'] === 0
                        )
                    )
                    && $objet->format($format) === $dateTexte
                ) {
                    return true;
                }
            }

            return false;

        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Convertir une date au format Y-m-d.
     */
    private function convertirDate($date): ?string
    {
        if ($date === null || trim((string) $date) === '') {
            return null;
        }

        try {
            if (is_numeric($date)) {
                return Date::excelToDateTimeObject($date)
                    ->format('Y-m-d');
            }

            $dateTexte = trim((string) $date);

            foreach (
                ['d/m/Y', 'd-m-Y', 'd.m.Y', 'Y-m-d'] as $format
            ) {
                $objet = \DateTime::createFromFormat(
                    '!' . $format,
                    $dateTexte
                );

                $erreurs = \DateTime::getLastErrors();

                if (
                    $objet !== false
                    && (
                        $erreurs === false
                        || (
                            $erreurs['warning_count'] === 0
                            && $erreurs['error_count'] === 0
                        )
                    )
                    && $objet->format($format) === $dateTexte
                ) {
                    return $objet->format('Y-m-d');
                }
            }

            return null;

        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Importer les enseignants en conservant uniquement
     * leur affectation de cycle.
     *
     * Aucune affectation de classe ou de matière n'est créée.
     * Aucune insertion dans la table enseignements n'est faite.
     */
    public function importer()
    {
        if (!empty($this->erreurs)) {
            session()->flash(
                'error',
                'Corrigez les erreurs affichées avant de lancer l’import.'
            );

            return;
        }

        if (empty($this->preview)) {
            session()->flash(
                'error',
                'Aucune donnée à importer.'
            );

            return;
        }

        $enseignantsImportes = 0;

        try {
            DB::transaction(function () use (
                &$enseignantsImportes
            ) {
                foreach ($this->preview as $index => $row) {
                    $ligne = $index + 2;

                    $nom = trim((string) ($row[0] ?? ''));
                    $prenom = trim((string) ($row[1] ?? ''));

                    $dateNaissance = $this->convertirDate(
                        $row[2] ?? null
                    );

                    $sexe = strtoupper(
                        trim((string) ($row[3] ?? ''))
                    );

                    $adresse = trim(
                        (string) ($row[4] ?? '')
                    );

                    $telephone = trim(
                        (string) ($row[5] ?? '')
                    );

                    $email = trim(
                        str_replace(
                            ',',
                            '.',
                            (string) ($row[6] ?? '')
                        )
                    );

                    $email = $email !== ''
                        ? strtolower($email)
                        : null;

                    $specialite = trim(
                        (string) ($row[7] ?? '')
                    );

                    $grade = trim(
                        (string) ($row[8] ?? '')
                    );

                    $dateEmbauche = $this->convertirDate(
                        $row[9] ?? null
                    );

                    $statut = trim(
                        (string) ($row[10] ?? '')
                    );

                    $statut = $statut !== ''
                        ? $statut
                        : 'actif';

                    // Résolution du cycle.
                    $cycleNom = trim(
                        (string) ($row[11] ?? '')
                    );

                    $cycle = $this->trouverCycle($cycleNom);

                    if (!$cycle) {
                        throw new \RuntimeException(
                            "Cycle introuvable à la ligne {$ligne} : {$cycleNom}"
                        );
                    }

                    /*
                     * Création de l'enseignant.
                     *
                     * cycle_id est conservé.
                     * Les colonnes des classes et matières
                     * ne sont pas utilisées.
                     */
                    $enseignant = Enseignant::create([
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'date_naissance' => $dateNaissance,
                        'sexe' => $sexe,
                        'adresse' => $adresse !== ''
                            ? $adresse
                            : null,
                        'telephone' => $telephone !== ''
                            ? $telephone
                            : null,
                        'email' => $email,
                        'matricule' => $this->genererMatricule(),
                        'specialite' => $specialite !== ''
                            ? $specialite
                            : null,
                        'grade' => $grade !== ''
                            ? $grade
                            : null,
                        'date_embauche' => $dateEmbauche,
                        'statut' => $statut,

                        // Conservation de l'affectation du cycle.
                        'cycle_id' => $cycle->id,
                    ]);

                    $enseignantsImportes++;
                }
            });

            session()->flash(
                'success',
                "Importation terminée avec succès : "
                . "{$enseignantsImportes} enseignant(s) créé(s). "
                . "Les cycles ont été conservés. "
                . "Aucune classe ni matière n'a été affectée."
            );

            $this->reset([
                'fichier',
                'preview',
                'headers',
                'erreurs',
            ]);

        } catch (\Throwable $e) {
            Log::error(
                'Erreur importation enseignants',
                [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]
            );

            session()->flash(
                'error',
                "L'importation a échoué : " . $e->getMessage()
            );
        }
    }

    /**
     * Télécharger le modèle Excel.
     */
    public function telechargerModele()
    {
        return Excel::download(
            new EnseignantsModeleExport(),
            'modele_import_enseignants.xlsx'
        );
    }

    /**
     * Afficher la vue Livewire.
     */
    public function render()
    {
        return view('livewire.import-enseignants');
    }
}