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
    }

    public function isConnected()
    {
        if (isset($_SESSION['id'])) {
            return TRUE;
        } else {
            throw new Exception("401 : Vous n'êtes pas connecté !");
        }
    }

    public function isAdmin()
    {
        if ($_SESSION['status'] === "boss") {
            return TRUE;
        } else {
            throw new Exception("403 : Vous n'avez pas les droits pour accéder à cette page");
        }
    }

    public function destroySession(): void
    {
        session_unset();
        session_destroy();
    }
}
