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

    /**
     * Generates a random password.
     *
     * @param int $passwordLength The length of the generated password. Default is 12.
     * @return string The randomly generated password.
     */
    function generatePassword($passwordLength = 12)
    {
        // Define a character set consisting of uppercase letters, lowercase letters, and digits.
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));

        // Calculate the length of the character set array.
        $length = count($chars) - 1;

        // Choose a random uppercase letter as the first character of the password.
        $pass[0] = $chars[mt_rand(0, 25)];

        // Determine a random index to insert a number character.
        $numIndex = mt_rand(1, $passwordLength - 1);

        // Generate the remaining characters of the password.
        for ($i = 1; $i < $passwordLength; $i++) {
            // If the current index matches the chosen index for the number character, use a special character instead.
            $pass[$i] = $chars[($i == $numIndex) ? mt_rand(52, 61) : mt_rand(0, $length)];
        }

        // Convert the character array into a string and return the generated password.
        return implode('', $pass);
    }
}
