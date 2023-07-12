<?php

namespace utils;

class MyFunctions
{

    public function convertMonth(int $monthNumber)
    {
        switch ($monthNumber) {
            case 1:
                return $result["month"] = "Janvier";
            case 2:
                return $result["month"] = "Février";
            case 3:
                return $result["month"] = "Mars";
            case 4:
                return $result["month"] = "Avril";
            case 5:
                return $result["month"] = "Mai";
            case 6:
                return $result["month"] = "Juin";
            case 7:
                return $result["month"] = "Juillet";
            case 8:
                return $result["month"] = "Aout";
            case 9:
                return $result["month"] = "Septembre";
            case 10:
                return $result["month"] = "Octobre";
            case 11:
                return $result["month"] = "Novembre";
            case 12:
                return $result["month"] = "Décembre";
        }
    }

    function generatePassword($passwordLength = 12)
    {
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9)); // Tableau de caractères lettres/chiffres.
        // var_dump($chars); // Debug : affiche la liste des caractères avec les index.

        $length = count($chars) - 1; // Index maximum du tableau de caractères.

        // Note : $pass un est un array qui est créé à la volée en remplissant sa première cellule (ligne en dessous).
        $pass[0] = $chars[mt_rand(0, 25)]; // Première lettre en majuscule (0-25 = index des caractères en majuscule dans $chars).
        $numIndex = mt_rand(1, $passwordLength - 1); // Définir un index dans le reste du mot de passe qui contiendra un chiffre.

        for ($i = 1; $i < $passwordLength; $i++) { // Pour le reste des lettres.
            // Ajouter à la position $i : Si index courrant = $numIndex => chiffre (index 52-61), sinon lettre/chiffre aléatoire.
            $pass[$i] = $chars[($i == $numIndex) ? mt_rand(52, 61) : mt_rand(0, $length)];
        }

        return implode('', $pass); // Retourne le tout sous forme de chaine de caractères (string).
    }
}
