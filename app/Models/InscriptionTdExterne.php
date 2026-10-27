<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InscriptionTdExterne extends Model
{
    use HasFactory;

    protected $table = 'inscriptions_td_externes';

    protected $fillable = [
        'eleve_externe_td_id',
        'annee_id',
        'classe_id',
        'frais_id',
        'date_inscription',
        'montant_total',
        'statut',
        'observations',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'montant_total' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Élève externe concerné.
     */
    public function eleve(): BelongsTo
    {
        return $this->belongsTo(
            EleveExterneTd::class,
            'eleve_externe_td_id'
        );
    }

    /**
     * Année scolaire.
     */
    public function annee(): BelongsTo
    {
        return $this->belongsTo(
            Annee::class,
            'annee_id'
        );
    }

    /**
     * Classe dans laquelle l'élève suit les TD.
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(
            Classe::class,
            'classe_id'
        );
    }

    /**
     * Frais correspondant au tarif des TD.
     */
    public function frais(): BelongsTo
    {
        return $this->belongsTo(
            Frais::class,
            'frais_id'
        );
    }

    /**
     * Paiements de TD enregistrés dans la table paiement_achats.
     *
     * Cette relation suppose que le champ
     * inscription_td_externe_id sera ajouté à paiement_achats.
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(
            PaiementAchat::class,
            'inscription_td_externe_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Montants financiers
    |--------------------------------------------------------------------------
    */

    /**
     * Total des paiements enregistrés.
     *
     * Les paiements annulés ou rejetés doivent être exclus
     * si ton application dispose de tels statuts.
     */
    public function getTotalPayeAttribute(): float
    {
        return round(
            (float) $this->paiements()
                ->sum('montant_verse'),
            2
        );
    }

    /**
     * Solde restant à payer.
     */
    public function getResteAPayerAttribute(): float
    {
        return round(
            max(
                0,
                (float) $this->montant_total - $this->total_paye
            ),
            2
        );
    }

    /**
     * Pourcentage payé.
     */
    public function getPourcentagePayeAttribute(): float
    {
        $montantTotal = (float) $this->montant_total;

        if ($montantTotal <= 0) {
            return 0;
        }

        return round(
            min(100, ($this->total_paye / $montantTotal) * 100),
            2
        );
    }

    /**
     * Statut financier calculé à partir des paiements.
     */
    public function getStatutPaiementAttribute(): string
    {
        $total = (float) $this->montant_total;
        $paye = $this->total_paye;

        if ($paye <= 0) {
            return 'impaye';
        }

        if ($paye >= $total) {
            return 'solde';
        }

        return 'partiel';
    }

    /*
    |--------------------------------------------------------------------------
    | Méthodes métier
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifier si l'inscription est soldée.
     */
    public function estSoldee(): bool
    {
        return $this->reste_a_payer <= 0;
    }

    /**
     * Enregistrer un paiement de TD dans paiement_achats.
     *
     * IMPORTANT :
     * Cette méthode suppose que paiement_achats possède un champ
     * montant_verse et que les autres champs obligatoires de cette
     * table ont des valeurs par défaut ou sont renseignés ailleurs.
     *
     * Elle ne crée volontairement pas de recette : la création de
     * la recette et du reçu doit suivre le circuit de caisse existant.
     */
   public function enregistrerVersement(
    float $montant,
    array $attributsSupplementaires = []
): \App\Models\PaiementAchat {
    if ($montant <= 0) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'montant' => 'Le montant du versement doit être supérieur à zéro.',
        ]);
    }

    return \Illuminate\Support\Facades\DB::transaction(
        function () use ($montant, $attributsSupplementaires) {

            $inscription = static::query()
                ->whereKey($this->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $totalPaye = (float) $inscription->paiements()
                ->sum('montant');

            $reste = round(
                (float) $inscription->montant_total - $totalPaye,
                2
            );

            if ($reste <= 0) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'montant' => 'Cette inscription aux TD est déjà soldée.',
                ]);
            }

            if ($montant > $reste) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'montant' => sprintf(
                        'Le versement dépasse le solde restant de %s FCFA.',
                        number_format($reste, 0, ',', ' ')
                    ),
                ]);
            }

            $donnees = array_merge(
                $attributsSupplementaires,
                [
                    'inscription_td_externe_id' => $inscription->id,
                    'inscription_id' => null,
                    'nom' => $inscription->eleve->nom,
                    'prenom' => $inscription->eleve->prenom,
                    'frais_id' => $inscription->frais_id,
                    'categorie_recette_id' => 17,
                    'montant' => round($montant, 2),
                ]
            );

            return $inscription->paiements()->create($donnees);
        }
    );
}
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Inscriptions d'une année scolaire.
     */
    public function scopePourAnnee($query, int $anneeId)
    {
        return $query->where('annee_id', $anneeId);
    }

    /**
     * Inscriptions d'une classe.
     */
    public function scopePourClasse($query, int $classeId)
    {
        return $query->where('classe_id', $classeId);
    }

    /**
     * Inscriptions actives.
     */
    public function scopeActives($query)
    {
        return $query->where('statut', 'active');
    }

    /**
     * Inscriptions d'un élève externe.
     */
    public function scopePourEleve($query, int $eleveExterneId)
    {
        return $query->where(
            'eleve_externe_td_id',
            $eleveExterneId
        );
    }
}