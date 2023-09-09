<?php

namespace config;

use controllers\UsersController;
use utils\Render;
use Exception;

class Routing
{
    public function get()
    {
        // Trying the following code block
        try {
            // If there is a value for 'controller' in the URL, then...
            if (isset($_GET['controller'])) {

                // Protecting against code injections
                $url = htmlspecialchars($_GET['controller']); // e.g : https://pj/users/post

                // Splitting the URL into an array using the explode method
                $newUrl = explode("/", $url); // [user, post]
                $controllerName = "controllers\\" . ucfirst($newUrl[0] . "Controller"); // "UsersController"

                // If there is a second value in the URL, then...
                if (isset($newUrl[1])) {
                    $methodName = strtolower($newUrl[1]); // "post"

                    // Checking if the method exists in the controller
                    if (!method_exists($controllerName, $methodName)) {
                        throw new Exception("404 : Cette page n'existe pas");
                    }

                    $controller = new $controllerName(); // new UsersController.php
                    if (isset($newUrl[2])) {
                        $id = intval($newUrl[2]);
                        if ($id === null) {
                            throw new Exception("404 : Cette page n'existe pas");
                        }
                        $controller->$methodName($id); // $controller->post(4);
                    } else {
                        $controller->$methodName(); // $controller->post();
                    }
                } else {

                    // If a parameter is missing, the page does not exist, throwing an error to be caught in the catch block
                    throw new Exception("404 : Cette page n'existe pas");
                }
            } // If there is no value for 'controller', then display the home page
            else {
                $index = new UsersController();
                $index->index();
            }
        } // If there's an error somewhere, it will be caught in the catch block
        catch (Exception $e) {

            // Retrieving the error message to store in $errorMessage
            $errorMessage = $e->getMessage();

            // Splitting the message to extract the error code and description separately
            $parts = explode(': ', $errorMessage);
            $errorCode = $parts[0];
            $errorDescription = $parts[1];
            $pageTitle = "Page d'erreur";

            // Displaying the error on the error page
            Render::render("error", compact("errorCode", "errorDescription", "pageTitle"));
        }
    }
}
