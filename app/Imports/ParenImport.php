<?php

namespace App\Imports;

use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Paren;
use App\Models\User;
use App\Models\Classe;
use App\Models\Annee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParenImport implements ToCollection, WithHeadingRow
{
    public int $classe_id;
    public int $annee_id;

    public array $valides = [];
    public array $erreurs = [];

    protected ?Classe $classe = null;
    protected ?Annee $annee = null;

    public function __construct(int $classe_id, int $annee_id)
    {
        $this->classe_id = $classe_id;
        $this->annee_id  = $annee_id;

        $this->classe = Classe::find($classe_id);
        $this->annee  = Annee::find($annee_id);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $ligne = $index + 2; // Numéro réel Excel

            $matricule        = trim((string) ($row['matricule'] ?? ''));
            $nom_parent       = trim((string) ($row['nom_parent'] ?? ''));
            $prenom_parent    = trim((string) ($row['prenom_parent'] ?? ''));
            $telephone_parent = preg_replace('/[^0-9]/', '', (string) ($row['telephone_parent'] ?? ''));
            $adresse_parent   = trim((string) ($row['adresse_parent'] ?? ''));

            $errors = [];

            // ================= VALIDATIONS =================
            if ($matricule === '') $errors[] = 'Matricule obligatoire';
            if ($nom_parent === '') $errors[] = 'Nom du parent obligatoire';
            if ($telephone_parent === '' || strlen($telephone_parent) < 8) $errors[] = 'Téléphone invalide';
            if (!$this->classe) $errors[] = "Classe sélectionnée inexistante ou supprimée";
            if (!$this->annee) $errors[] = "Année scolaire sélectionnée inexistante ou supprimée";

            $eleve = Eleve::where('matricule', $matricule)->first();
            if (!$eleve) $errors[] = 'Élève introuvable';

            // ================= INSCRIPTION =================
            $inscriptionReelle = null;
            if ($eleve) {
                $inscriptionReelle = Inscription::with('classe', 'annee')
                    ->where('eleve_id', $eleve->id)
                    ->first();

                if (!$inscriptionReelle) {
                    $errors[] = 'Élève non inscrit pour aucune année scolaire';
                } else {
                    if ($this->classe && $inscriptionReelle->classe_id !== $this->classe->id) {
                        $errors[] = sprintf(
                            "Classe incorrecte : sélectionnée (%s), élève inscrit en (%s)",
                            $this->classe->nom,
                            optional($inscriptionReelle->classe)->nom ?? 'inconnue'
                        );
                    }

                    if ($this->annee && $inscriptionReelle->annee_id !== $this->annee->id) {
                        $errors[] = sprintf(
                            "Année scolaire incorrecte : sélectionnée (%s), élève inscrit en (%s)",
                            $this->annee->nom,
                            optional($inscriptionReelle->annee)->nom ?? 'inconnue'
                        );
                    }
                }
            }

            // ================= ENREGISTRE LES ERREURS =================
            if (!empty($errors)) {
                $this->erreurs[] = [
                    'ligne'      => $ligne,
                    'matricule'  => $matricule,
                    'classe_id'  => $this->classe_id,
                    'annee_id'   => $this->annee_id,
                    'messages'   => $errors,
                ];
                continue;
            }

            // ================= DONNÉES VALIDES =================
            $this->valides[] = [
                'ligne'            => $ligne,
                'matricule'        => $matricule,
                'nom_parent'       => $nom_parent,
                'prenom_parent'    => $prenom_parent,
                'telephone_parent' => $telephone_parent,
                'adresse_parent'   => $adresse_parent,
                'classe_id'        => $this->classe_id,
                'annee_id'         => $this->annee_id,
            ];
        }
    }

    /**
     * Crée ou récupère un parent existant
     */
    public function creerOuTrouverParent(string $nom, ?string $prenom, string $telephone, ?string $adresse): Paren
    {
        $nom = trim($nom);
        if ($nom === '') {
            throw new \Exception("Nom parent obligatoire pour le téléphone {$telephone}");
        }

        $parent = Paren::where('telephone_parent', $telephone)->first();
        if ($parent) return $parent;

        // Créer utilisateur
        $user = User::create([
            'name' => $nom.' '.$prenom,
            'email' => $telephone.'@parent.gespa.bj',
            'password' => Hash::make('parent2026'),
            'role' => 'parent',
            'telephone' => $telephone,
            'must_change_password' => true,
        ]);

        // Créer parent
        $parent = Paren::create([
            'nom_parent'       => $nom,
            'prenom_parent'    => $prenom ?: null,
            'telephone_parent' => $telephone,
            'adresse_parent'   => $adresse ?: null,
            'user_id'          => $user->id,
        ]);

        Log::info('Parent créé', ['id' => $parent->id, 'telephone' => $telephone]);
        return $parent;
    }
}
