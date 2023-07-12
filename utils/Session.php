<?php

namespace utils;

class Session
{
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function setSession(int $userId, string $username, string $userStatus): void
    {
        $_SESSION['id'] = $userId;
        $_SESSION['username'] = $username;
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
            header('Location: /cyclotron');
        }
    }

    public function isAdmin()
    {
        if ($_SESSION['status'] === "1") {
            return TRUE;
        } else {
            header('Location: /cyclotron');
        }
    }

    public function isSeller()
    {
        if ($_SESSION["status"] === "2") {
            return TRUE;
        } else {
            header("Location: /cyclotron");
        }
    }

    public function destroySession(): void
    {
        session_unset();
        session_destroy();
    }
}
