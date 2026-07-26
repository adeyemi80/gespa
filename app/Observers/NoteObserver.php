<?php
// app/Observers/NoteObserver.php

namespace App\Observers;

use App\Models\Note;
use App\Events\NotesUpdated;

class NoteObserver
{
    public function saved(Note $note): void
    {
        // Recalculer moyenne_matiere dès qu'une note change
        $moyenneMatiere = collect([
            $note->moyenne_interro,
            $note->devoir1,
            $note->devoir2,
        ])->filter(fn($v) => $v !== null);

        if ($moyenneMatiere->isNotEmpty()) {
            $note->moyenne_matiere = round($moyenneMatiere->avg(), 2);
            $note->saveQuietly(); // évite une boucle infinie
        }

        // 🔧 CORRECTIF : déclencher le recalcul de la moyenne générale de l'élève
        event(new NotesUpdated(
            $note->inscription_id,
            $note->inscription->annee_id,
            $note->inscription->classe_id
        ));
    }
}
