<?php

namespace models;

use utils\MyFunctions;

class UsersRepository extends Model
{
    protected string $table = "users";

    protected MyFunctions $function;

    public function __construct()
    {
        parent::__construct();
        $this->function = new MyFunctions();
    }

    public function login()
    {
        if (isset($_POST['username'], $_POST['password']) && !empty($_POST)) {
            $username = htmlspecialchars(trim($_POST['username']));
            $password = htmlspecialchars(trim($_POST['password']));
            $result = $this->findAccount($username);

            if ($username === $result['username']) {
                if (password_verify($password, $result['password'])) {
                    $this->session->setSession($result['id'], $result['status']);
                    return TRUE;
                } else {
                    $this->session->setFlashMessage("Votre mot de passe est erroné !");
                }
            } else {
                $this->session->setFlashMessage("Ce nom d'utilisateur n'existe pas !");
            }
        }
    }

    private function findAccount(string $username)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE username = :username");
        $query->execute([
            'username' => $username
        ]);
        return $query->fetch();
    }

    public function logout(): void
    {
        $this->session->destroySession();
    }

    public function insertEmployee()
    {
        if (isset($_POST["username"], $_POST["user_password"])) {
            if (intval($_POST["employee_status"]) >= 1 && intval($_POST["employee_status"]) <= 4) {
            } else {
                $this->session->setFlashMessage("Veuillez séléctionner un status pour votre employé !");
            }
        } else {
            $this->session->setFlashMessage("Une erreur s'est produite, veuillez recommencer !");
        }
    }

    public function updateEmployee()
    {
    }
}
