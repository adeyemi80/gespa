<?php

if (!function_exists('normalizeText')) {
    function normalizeText($text)
    {
        return str_replace("’", "'", $text); // remplace l’apostrophe typographique par la simple
    }
}
