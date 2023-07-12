<?php

namespace models;

use utils\MyFunctions;
use utils\Render;

class UsersRepository extends Model
{
    protected string $table = "users";

    protected MyFunctions $function;

    public function __construct()
    {
        parent::__construct();
        $this->function = new MyFunctions();
    }

    private function findAccount(string $email)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $query->execute([
            'email' => $email
        ]);
        return $query->fetch();
    }

    public function login()
    {
        if (isset($_POST['account'], $_POST['password']) && !empty($_POST)) {
            $email = htmlspecialchars(trim($_POST['account']));
            $password = htmlspecialchars(trim($_POST['password']));
            $result = $this->findAccount($email);

            if ($email === $result['email']) {
                if (password_verify($password, $result['password'])) {
                    $this->session->setSession($result['id'], $result['username'], $result['status']);
                    return TRUE;
                } else {
                    $this->session->setFlashMessage("Votre mot de passe est erroné !");
                }
            } else {
                $this->session->setFlashMessage("Ce nom d'utilisateur n'existe pas !");
            }
        }
    }

    public function logout(): void
    {
        $this->session->destroySession();
    }



    public function insertEmployee()
    {
        if (isset($_POST["username"])) {
            if (isset($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $email = htmlspecialchars(trim($_POST["email"]));
                $mailExist = $this->findAccount($email);
                if (!$mailExist) {
                    $username = htmlspecialchars(trim($_POST["username"]));
                    $status = htmlspecialchars(trim(intval($_POST["employee_status"])));
                    if ($status >= 1 && $status <= 2) {
                        $password = $this->function->generatePassword();
                        $this->session->setFlashMessage("Bravo");
                        $securePassword = password_hash($password, PASSWORD_DEFAULT);
                        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET email = :email, username = :username, password = :password, status = :status");
                        $query->execute([
                            "email" => $email,
                            "username" => $username,
                            "password" => $securePassword,
                            "status" => $status,
                        ]);

                        $this->session->setFlashMessage("Nouvel employé ajouté, veuillez noter le mot de passe : " . $password . " !");

                        // Envoyer un mail
                        // $phpmailer = new PHPMailer();
                        // $phpmailer->isSMTP();
                        // $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
                        // $phpmailer->SMTPAuth = true;
                        // $phpmailer->Port = 2525;
                        // $phpmailer->Username = '5921e62712de38';
                        // $phpmailer->Password = 'd05529bcedef41';
                        // Envoyer un mail

                    } else {
                        $this->session->setFlashMessage("Veuillez sélectionner un statut pour votre employé !");
                        Render::render("employeesManagement");
                    }
                } else {
                    $this->session->setFlashMessage("Cette adresse email existe déjà !");
                    Render::render("employeesManagement");
                }
            } else {
                $this->session->setFlashMessage("Veuillez saisir une adresse email valide !");
                Render::render("employeesManagement");
            }
        } else {
            $this->session->setFlashMessage("Veuillez saisir un nom d'utilisateur !");
            Render::render("employeesManagement");
        }
    }

    public function deleteEmployee(int $employeeId): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :employeeId");
        $query->execute([
            "employeeId" => $employeeId
        ]);
        $this->session->setFlashMessage("L'utilisateur à bien été supprimer !");
    }
}
