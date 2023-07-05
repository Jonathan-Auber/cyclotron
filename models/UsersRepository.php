<?php

namespace models;

use Exception;
use utils\Session;
use utils\MyFunctions;

class UsersRepository extends Model
{
    protected string $table = "users";
    protected Session $session;
    protected MyFunctions $function;

    public function __construct()
    {
        parent::__construct();
        $this->session = new Session();
        $this->function = new MyFunctions();
    }

    public function login()
    {
        if (isset($_POST['username'], $_POST['password']) && !empty($_POST)) {
            $username = htmlspecialchars(trim($_POST['username']));
            $password = htmlspecialchars(trim($_POST['password']));
            $result = $this->findAccount($username);

            if ($username === $result['username'] && password_verify($password, $result['password'])) {
                $this->session->setSession($result['id'], $result['status']);
                return TRUE;
            } else {
                throw new Exception("403 : Votre nom d'utilisateur ou votre mot de passe est erroné !");
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
}
