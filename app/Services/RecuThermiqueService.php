<?php

namespace App\Services;

class RecuThermiqueService
{
    protected int $columns = 32;

    private function line(): string
    {
        return str_repeat('-', $this->columns);
    }

    private function center(string $text): string
    {
        $text = mb_substr($text, 0, $this->columns);

        $length = mb_strlen($text);

        if ($length >= $this->columns) {
            return $text;
        }

        $left = intdiv(
            $this->columns - $length,
            2
        );

        return str_repeat(' ', $left) . $text;
    }

    private function money($amount): string
    {
        return number_format(
            (float) $amount,
            0,
            ',',
            ' '
        ) . ' F';
    }

   public function generate(array $data): string
{
    $text = '';

    $text .= $this->center('COLLEGE LE GLORIEUX') . "\n";
    $text .= $this->center('GESPA') . "\n";
    $text .= $this->center('RECU DE PAIEMENT') . "\n";

    $text .= $this->line() . "\n";

    $text .= 'N° Recu : ' . ($data['numero'] ?? '-') . "\n";

    $text .= 'Date    : ' . ($data['date'] ?? '-') . "\n";

    $text .= $this->line() . "\n";

    $text .= "ELEVE\n";

    $text .= ($data['eleve'] ?? '-') . "\n";

    $text .= 'Matricule : ' .
        ($data['matricule'] ?? '-') . "\n";

    $text .= 'Classe    : ' .
        ($data['classe'] ?? '-') . "\n";

    $text .= 'Annee     : ' .
        ($data['annee'] ?? '-') . "\n";

    $text .= $this->line() . "\n";

    $text .= "DETAIL DU PAIEMENT\n";
    $text .= $this->line() . "\n";

    foreach ($data['details'] ?? [] as $detail) {

        $text .= ($detail['nom'] ?? 'Frais') . "\n";

        $text .= str_pad(
            $this->money($detail['montant'] ?? 0),
            $this->columns,
            ' ',
            STR_PAD_LEFT
        ) . "\n";
    }

    $text .= $this->line() . "\n";

    $text .= "TOTAL RECU\n";

    $text .= $this->center(
        $this->money($data['total_recu'] ?? 0)
    ) . "\n";

    $text .= $this->line() . "\n";

    $text .= 'Total paye : ' .
        $this->money($data['total_paye'] ?? 0) . "\n";

    $text .= 'Total frais : ' .
        $this->money($data['total_frais'] ?? 0) . "\n";

    $text .= 'Reste : ' .
        $this->money($data['reste'] ?? 0) . "\n";

    $text .= 'Statut : ' .
        ($data['statut'] ?? '-') . "\n";

    $text .= 'Mode : ' .
        ($data['mode_paiement'] ?? '-') . "\n";

    $text .= $this->line() . "\n";

    $text .= $this->center(
        'Merci pour votre paiement.'
    ) . "\n";

    $text .= $this->center(
        'COLLEGE LE GLORIEUX'
    ) . "\n";

    $text .= "\n\n\n";

    /*
    |--------------------------------------------------------------------------
    | Conversion UTF-8 → CP850
    |--------------------------------------------------------------------------
    */

    $encoded = iconv(
        'UTF-8',
        'CP850//TRANSLIT',
        $text
    );

    if ($encoded === false) {
        throw new \RuntimeException(
            'Impossible de convertir le ticket en CP850.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Commandes ESC/POS
    |--------------------------------------------------------------------------
    */

    return
        "\x1B\x40" .       // Initialisation
        "\x1B\x74\x02" .   // CP850
        $encoded .
        "\x1D\x56\x00";    // Coupe papier
}
}