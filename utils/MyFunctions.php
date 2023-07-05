<?php 

namespace utils;

class MyFunctions {

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
}

