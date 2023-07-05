<?php

namespace config;

use PDO;

class Database
{
    private static $instance = NULL;
    public static function getPdo(): PDO
    {
        // if (self::$instance === NULL) {
        //     self::$instance = new PDO('mysql:host=mysql-jonathan-auber.alwaysdata.net;dbname=jonathan-auber_pedale-joyeuse;charset=utf8', '304530', 'the_net_warrior', [
        //         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        //     ]);
        if (self::$instance === NULL) {
            self::$instance = new PDO('mysql:host=localhost;dbname=pedale_joyeuse;charset=utf8', 'root', 'root', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }

        return self::$instance;
    }
}
