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

    /**
     * Retrieve a record from the database based on the provided ID.
     *
     * This function fetches a record from the database table associated with the given model,
     * using the provided ID as a lookup key.
     *
     * @param int $id The ID of the record to retrieve.
     * @return array|false Returns an array representing the retrieved record, or false if not found.
     */
    public function find(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch();
    }

    /**
     * Retrieve all records from the database table associated with the model.
     *
     * This function fetches all records available in the database table associated with the model.
     *
     * @return array Returns an array containing all retrieved records.
     */
    public function findAll()
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table}");
        $query->execute();
        return $query->fetchAll();
    }
}
