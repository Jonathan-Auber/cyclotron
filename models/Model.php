<?php

namespace models;

use PDO;
use utils\Session;

abstract class Model
{
    protected PDO $pdo;
    protected Session $session;
    protected string $table;

    public function __construct()
    {
        $this->pdo = \config\Database::getpdo();
        $this->session = new Session();
    }

    public function find(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch();
    }

    public function findAll()
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table}");
        $query->execute();
        return $query->fetchAll();
    }
}
