<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_piece',
        'type_depense_id',
        'annee_id',
        'libelle',
        'description',
        'montant',
        'date_depense',
        'beneficiaire',
        'mode_paiement',
        'reference_paiement',
        'statut',
        'motif_rejet',
        'cree_par',
        'valide_par',
        'valide_le',
        'cycle_id',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_depense' => 'date',
        'valide_le' => 'datetime',
    ];

    public function typeDepense(): BelongsTo
    {
        return $this->belongsTo(TypeDepense::class, 'type_depense_id');
    }

    public function annee(): BelongsTo
    {
        return $this->belongsTo(Annee::class, 'annee_id');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function piecesJustificatives(): HasMany
    {
        return $this->hasMany(PieceJustificative::class, 'depense_id');
    }

    public function scopeValidees($query)
    {
        return $query->whereIn('statut', ['validee', 'payee']);
    }

    public function scopePourAnnee($query, $anneeId)
    {
        return $query->where('annee_id', $anneeId);
    }
    public function cycle(): BelongsTo
{
    return $this->belongsTo(Cycle::class);
}
}