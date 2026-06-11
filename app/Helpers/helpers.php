
<?php
if (!function_exists('appreciation')) {
    function appreciation($moyenne)
    {
        if ($moyenne >= 16) return 'Très bien';
        if ($moyenne >= 14) return 'Bien';
        if ($moyenne >= 12) return 'Assez bien';
        if ($moyenne >= 10) return 'Passable';
        return 'Insuffisant';
    }

}

if (!function_exists('appreciationConduite')) {
    function appreciationConduite($note)
    {
        if ($note >= 18) return 'Exemplaire';
        if ($note >= 15) return 'Très bonne conduite';
        if ($note >= 12) return 'Bonne conduite';
        if ($note >= 8)  return 'À améliorer';
        return 'Mauvaise conduite';
    }
}


if (!function_exists('appreciationGenerale')) {
    function appreciationGenerale($moyenne)
    {
        if ($moyenne >= 16) {
            return "FÉLICITATIONS";
        } elseif ($moyenne >= 14) {
            return "TABLEAU D'HONNEUR";
        } elseif ($moyenne >= 12) {
            return "ENCOURAGEMENT";
        } elseif ($moyenne >= 10) {
            return "PASSABLE";
        } else {
            return "INSUFFISANT";
        }
    }
}

