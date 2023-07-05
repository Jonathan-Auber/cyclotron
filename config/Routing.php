<?php

namespace config;

use controllers\UsersController;
use utils\Render;
use Exception;

class Routing
{
    public function get()
    {
        // On essaie le bloc de code qui suit
        try {
            // Si il y a une valeur pour controller dans l'url alors ..
            if (isset($_GET['controller'])) {
                // On se protège des injections de code
                $url = htmlspecialchars($_GET['controller']); // Ex pour : https://pj/users/post
                // On répartit l'url dans un tableau à l'aide de la méthode explode
                $newUrl = explode("/", $url); // [user, post]
                $controllerName = "controllers\\" . ucfirst($newUrl[0] . "Controller"); // UsersController
                // Si il y a une deuxième valeur dans l'url alors ..
                if (isset($newUrl[1])) {
                    $methodName = strtolower($newUrl[1]); // post
                    $controller = new $controllerName(); // new UsersController.php
                    if (isset($newUrl[2])) {
                        $id = $newUrl[2];
                        $controller->$methodName(intval($id)); // $controller->post(4);
                    } else {
                        $controller->$methodName(); // $controller->post();
                    }
                } else {
                    // S'il manque un paramètre, la page n'existe pas, on renvois une erreur qui sera récupérée dans le bloc catch
                    throw new Exception("404 : Cette page n'existe pas");
                }
            } // S'il n'y a pas de valeur pour controller, alors on affiche la page d'accueil
            else {
                $index = new UsersController();
                $index->index();
            }
        } // Si il y une erreur quelque part, on la récupère dans le bloc catch
        catch (Exception $e) {
            // On récupére le message d'erreur pour le stocker dans $errorMessage
            $errorMessage = $e->getMessage();
            // On divise le message pour récupérer le code erreur et la description à part
            $parts = explode(': ', $errorMessage);
            $errorCode = $parts[0];
            $errorDescription = $parts[1];
            $pageTitle = "Page d'erreur";
            // On affiche l'erreur sur la page d'erreur
            Render::render("error", compact("errorCode", "errorDescription", "pageTitle"));
        }
    }
}
