
<?php
// Cette page renvoie sur le routeur
require_once "config/Autoloader.php";
Autoloader::Autoload();

use config\Routing;

$route = new Routing();
$route->get();
