<?php

namespace utils;

use Exception;

class Session
{
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function setSession(int $userId, string $userStatus): void
    {
        $_SESSION['id'] = $userId;
        $_SESSION['status'] = $userStatus;
        $_SESSION['flash_message'] = null;
    }

    public function setFlashMessage($message)
    {
        $_SESSION['flash_message'] = $message;
    }

    public function isConnected()
    {
        if (isset($_SESSION['id'])) {
            return TRUE;
        } else {
            $this->setFlashMessage("Vous n'êtes pas connecté !");
        }
    }

    public function isAdmin()
    {
        if ($_SESSION['status'] === "boss") {
            return TRUE;
        } else {
            $this->setFlashMessage("Vous n'avez pas les droit pour accéder à cette page !");
        }
    }

    public function destroySession(): void
    {
        session_unset();
        session_destroy();
    }
}
