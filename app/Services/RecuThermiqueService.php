<?php

namespace App\Services;

class RecuThermiqueService
{
    /*
    |--------------------------------------------------------------------------
    | Largeur du reçu
    |--------------------------------------------------------------------------
    |
    | 58 mm = généralement 384 points sur une imprimante thermique.
    |
    */

    protected int $columns = 32;

    protected int $imageWidth = 384;


    /*
    |--------------------------------------------------------------------------
    | Ligne séparatrice
    |--------------------------------------------------------------------------
    */

    private function line(): string
    {
        return str_repeat('-', $this->columns);
    }


    /*
    |--------------------------------------------------------------------------
    | Centrer un texte
    |--------------------------------------------------------------------------
    */

    private function center(string $text): string
    {
        $text = mb_substr(
            $text,
            0,
            $this->columns
        );

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


    /*
    |--------------------------------------------------------------------------
    | Format monétaire
    |--------------------------------------------------------------------------
    */

    private function money($amount): string
    {
        return number_format(
            (float) $amount,
            0,
            ',',
            ' '
        ) . ' F';
    }


    /*
    |--------------------------------------------------------------------------
    | IMAGE → ESC/POS
    |--------------------------------------------------------------------------
    |
    | Transforme une image PNG/JPG en image monochrome compatible
    | avec l'imprimante thermique.
    |
    */

    private function imageToEscPos(string $imagePath): string
    {
        if (!file_exists($imagePath)) {

            throw new \RuntimeException(
                "Image d'en-tête introuvable : {$imagePath}"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier GD
        |--------------------------------------------------------------------------
        */

        if (!function_exists('imagecreatefrompng')) {

            throw new \RuntimeException(
                'L’extension PHP GD est nécessaire pour imprimer l’image.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Charger l'image
        |--------------------------------------------------------------------------
        */

        $source = @imagecreatefrompng($imagePath);

        if (!$source) {

            /*
            | Si ce n'est pas un PNG, essayer JPEG.
            */

            $source = @imagecreatefromjpeg($imagePath);
        }

        if (!$source) {

            throw new \RuntimeException(
                "Impossible de charger l'image : {$imagePath}"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Dimensions originales
        |--------------------------------------------------------------------------
        */

        $originalWidth =
            imagesx($source);

        $originalHeight =
            imagesy($source);


        /*
        |--------------------------------------------------------------------------
        | Calculer la nouvelle hauteur
        |--------------------------------------------------------------------------
        */

        $newWidth =
            min(
                $this->imageWidth,
                $originalWidth
            );

        $newHeight =
            (int) round(
                $originalHeight
                *
                ($newWidth / $originalWidth)
            );


        /*
        |--------------------------------------------------------------------------
        | Nouvelle image
        |--------------------------------------------------------------------------
        */

        $image = imagecreatetruecolor(
            $newWidth,
            $newHeight
        );


        /*
        |--------------------------------------------------------------------------
        | Fond blanc
        |--------------------------------------------------------------------------
        */

        $white = imagecolorallocate(
            $image,
            255,
            255,
            255
        );

        imagefill(
            $image,
            0,
            0,
            $white
        );


        /*
        |--------------------------------------------------------------------------
        | Redimensionnement
        |--------------------------------------------------------------------------
        */

        imagecopyresampled(
            $image,
            $source,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $originalWidth,
            $originalHeight
        );


        imagedestroy($source);


        /*
        |--------------------------------------------------------------------------
        | Nombre d'octets par ligne
        |--------------------------------------------------------------------------
        */

        $bytesPerRow =
            (int) ceil(
                $newWidth / 8
            );


        /*
        |--------------------------------------------------------------------------
        | Données bitmap
        |--------------------------------------------------------------------------
        */

        $bitmap = '';


        for (
            $y = 0;
            $y < $newHeight;
            $y++
        ) {

            for (
                $xByte = 0;
                $xByte < $bytesPerRow;
                $xByte++
            ) {

                $byte = 0;


                for (
                    $bit = 0;
                    $bit < 8;
                    $bit++
                ) {

                    $x =
                        ($xByte * 8)
                        + $bit;


                    /*
                    | En dehors de l'image
                    */

                    if ($x >= $newWidth) {
                        continue;
                    }


                    /*
                    | Couleur du pixel
                    */

                    $rgb =
                        imagecolorat(
                            $image,
                            $x,
                            $y
                        );


                    $r =
                        ($rgb >> 16) & 0xFF;

                    $g =
                        ($rgb >> 8) & 0xFF;

                    $b =
                        $rgb & 0xFF;


                    /*
                    |--------------------------------------------------------------------------
                    | Conversion en niveaux de gris
                    |--------------------------------------------------------------------------
                    */

                    $gray =
                        (0.299 * $r)
                        +
                        (0.587 * $g)
                        +
                        (0.114 * $b);


                    /*
                    |--------------------------------------------------------------------------
                    | Seuillage noir/blanc
                    |--------------------------------------------------------------------------
                    |
                    | Plus la valeur est basse, plus le pixel est noir.
                    |
                    */

                    if ($gray < 180) {

                        /*
                        | Pixel noir
                        */

                        $byte |=
                            (1 << (7 - $bit));
                    }
                }


                $bitmap .= chr($byte);
            }
        }


        imagedestroy($image);


        /*
        |--------------------------------------------------------------------------
        | Commande ESC/POS : GS v 0
        |--------------------------------------------------------------------------
        |
        | 1D 76 30 00
        |
        | largeur en octets
        | hauteur en pixels
        |
        */

        $xL =
            $bytesPerRow & 0xFF;

        $xH =
            ($bytesPerRow >> 8) & 0xFF;

        $yL =
            $newHeight & 0xFF;

        $yH =
            ($newHeight >> 8) & 0xFF;


        return
            "\x1D\x76\x30\x00"
            .
            chr($xL)
            .
            chr($xH)
            .
            chr($yL)
            .
            chr($yH)
            .
            $bitmap
            .
            "\n";
    }


    private function qrCode(string $data): string
{
    $cmd = '';

    // Modèle QR : modèle 2
    $cmd .= "\x1D\x28\x6B"
        . "\x04\x00"
        . "\x31\x41\x32\x00";

    // Taille du QR : 6
    $cmd .= "\x1D\x28\x6B"
        . "\x03\x00"
        . "\x31\x43\x06";

    // Correction d'erreur : niveau M
    $cmd .= "\x1D\x28\x6B"
        . "\x03\x00"
        . "\x31\x45\x31";

    // Données QR
    $length = strlen($data) + 3;

    $pL = $length & 0xFF;
    $pH = ($length >> 8) & 0xFF;

    $cmd .= "\x1D\x28\x6B"
        . chr($pL)
        . chr($pH)
        . "\x31\x50\x30"
        . $data;

    // Impression du QR
    $cmd .= "\x1D\x28\x6B"
        . "\x03\x00"
        . "\x31\x51\x30";

    return $cmd;
}

    /*
    |--------------------------------------------------------------------------
    | Génération du reçu
    |--------------------------------------------------------------------------
    */

    public function generate(array $data): string
    {
        /*
        |--------------------------------------------------------------------------
        | Image d'en-tête
        |--------------------------------------------------------------------------
        */

        $imagePath =
            public_path(
                'images/recu/header-glorieux.png'
            );


        /*
        |--------------------------------------------------------------------------
        | Initialisation ESC/POS
        |--------------------------------------------------------------------------
        */

        $ticket =
            "\x1B\x40";


        /*
        |--------------------------------------------------------------------------
        | Code page CP850
        |--------------------------------------------------------------------------
        */

        $ticket .=
            "\x1B\x74\x02";


        /*
        |--------------------------------------------------------------------------
        | En-tête image
        |--------------------------------------------------------------------------
        */

        if (file_exists($imagePath)) {

            try {

                $ticket .=
                    $this->imageToEscPos(
                        $imagePath
                    );

            } catch (\Throwable $e) {

                /*
                |--------------------------------------------------------------------------
                | Si l'image échoue, le reçu continue quand même.
                |--------------------------------------------------------------------------
                */

                \Log::warning(
                    'Impossible d’imprimer l’en-tête du reçu.',
                    [
                        'image' =>
                            $imagePath,

                        'error' =>
                            $e->getMessage(),
                    ]
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Titre du reçu
        |--------------------------------------------------------------------------
        */

        $text = '';


        $text .=
            $this->center(
                'RECU DE PAIEMENT'
            )
            . "\n";


        $text .=
            $this->line()
            . "\n";


        /*
        |--------------------------------------------------------------------------
        | Numéro et date
        |--------------------------------------------------------------------------
        */

        $text .=
            'N° Recu : '
            .
            ($data['numero'] ?? '-')
            . "\n";


        $text .=
            'Date    : '
            .
            ($data['date'] ?? '-')
            . "\n";


        $text .=
            $this->line()
            . "\n";


        /*
        |--------------------------------------------------------------------------
        | Élève
        |--------------------------------------------------------------------------
        */

        $text .=
            "ELEVE\n";


        $text .=
            ($data['eleve'] ?? '-')
            . "\n";


        $text .=
            'Matricule : '
            .
            ($data['matricule'] ?? '-')
            . "\n";


        $text .=
            'Classe    : '
            .
            ($data['classe'] ?? '-')
            . "\n";


        $text .=
            'Annee     : '
            .
            ($data['annee'] ?? '-')
            . "\n";


        $text .=
            $this->line()
            . "\n";


        /*
        |--------------------------------------------------------------------------
        | Détails paiement
        |--------------------------------------------------------------------------
        */

        $text .=
            "DETAIL DU PAIEMENT\n";


        $text .=
            $this->line()
            . "\n";


        foreach (
            $data['details'] ?? []
            as $detail
        ) {

            $text .=
                ($detail['nom'] ?? 'Frais')
                . "\n";


            $text .=
                str_pad(
                    $this->money(
                        $detail['montant'] ?? 0
                    ),
                    $this->columns,
                    ' ',
                    STR_PAD_LEFT
                )
                . "\n";
        }


        $text .=
            $this->line()
            . "\n";


        /*
        |--------------------------------------------------------------------------
        | Total reçu
        |--------------------------------------------------------------------------
        */

        $text .=
            "TOTAL RECU\n";


        $text .=
            $this->center(
                $this->money(
                    $data['total_recu'] ?? 0
                )
            )
            . "\n";


        $text .=
            $this->line()
            . "\n";


        /*
        |--------------------------------------------------------------------------
        | Résumé
        |--------------------------------------------------------------------------
        */

        $text .=
            'Total paye : '
            .
            $this->money(
                $data['total_paye'] ?? 0
            )
            . "\n";


        $text .=
            'Total frais : '
            .
            $this->money(
                $data['total_frais'] ?? 0
            )
            . "\n";


        $text .=
            'Reste : '
            .
            $this->money(
                $data['reste'] ?? 0
            )
            . "\n";


        $text .=
            'Statut : '
            .
            ($data['statut'] ?? '-')
            . "\n";


        $text .=
            'Mode : '
            .
            ($data['mode_paiement'] ?? '-')
            . "\n";


        /*
        |--------------------------------------------------------------------------
        | Utilisateur ayant enregistré le paiement
        |--------------------------------------------------------------------------
        |
        | Le nom provient du paiement enregistré en base de données.
        | Ainsi, lors d'une réimpression, on affiche toujours l'utilisateur
        | qui a réellement enregistré le paiement et non celui qui réimprime.
        |
        */

        // Nom + prénom de l'utilisateur ayant enregistré le paiement.
        // Le composant peut fournir directement "utilisateur".
        // On accepte aussi "user_nom" + "user_prenom" si ces champs sont fournis.
        $nomUtilisateur = trim(
            (string) ($data['user_nom'] ?? '')
        );

        $prenomUtilisateur = trim(
            (string) ($data['user_prenom'] ?? '')
        );

        if ($nomUtilisateur !== '' || $prenomUtilisateur !== '') {
            $utilisateur = trim(
                $nomUtilisateur . ' ' . $prenomUtilisateur
            );
        } else {
            $utilisateur = trim(
                (string) ($data['utilisateur'] ?? $data['enregistre_par'] ?? '')
            );
        }

        if ($utilisateur === '') {
            $utilisateur = '-';
        }

        $text .=
            'Enregistre par : '
            .
            $utilisateur
            . "\n";


        $text .=
            $this->line()
            . "\n";


        /*
        |--------------------------------------------------------------------------
        | Pied de reçu
        |--------------------------------------------------------------------------
        */

        $text .= $this->line() . "\n";

$text .= "\n";
$text .= $this->center('SCANNEZ POUR VERIFIER') . "\n\n";

// Données contenues dans le QR
$qrData = $data['qr_data'] ?? ($data['numero'] ?? '-');

$text .= $this->qrCode($qrData);

$text .= "\n\n";

$text .= $this->center('Merci pour votre paiement.') . "\n";
$text .= $this->center('COMPLEXE SCOLAIRE LE GLORIEUX') . "\n";

$text .= "\n\n\n";


        /*
        |--------------------------------------------------------------------------
        | Conversion UTF-8 → CP850
        |--------------------------------------------------------------------------
        */

        $encoded =
            iconv(
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
        | Ajouter le texte au ticket
        |--------------------------------------------------------------------------
        */

        $ticket .=
            $encoded;


        /*
        |--------------------------------------------------------------------------
        | Coupe papier
        |--------------------------------------------------------------------------
        */

        $ticket .=
            "\x1D\x56\x00";


        return $ticket;
    }
}