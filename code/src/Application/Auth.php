<?php

namespace Geekbrains\Application1\Application;

class Auth
{
    public static function getPasswordHash(string $rawPassword): string
    {
        return password_hash($_GET['pass_string'], PASSWORD_BCRYPT);
    }


    public function proceedAuth(string $login, string $password): bool
    {
        $sql = "SELECT id_user, user_name, user_lastname, password_hash FROM
    users WHERE login = :login";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['login' => $login]);
        $result = $handler->fetchAll();

        if (!empty($result) && password_verify(
            $password,
            $result[0]['password_hash']
        )) {
            $_SESSION['auth']['user_name'] = $result[0]['user_name'];
            $_SESSION['auth']['user_lastname'] = $result[0]['user_lastname'];
            $_SESSION['auth']['id_user'] = $result[0]['id_user'];
            //fingerprint
            return true;
        } else {
            return false;
        }
    }

    public function cookieAuth(): bool
    {
        if (isset($_COOKIE['random_bytes'])) {
            $id = $_COOKIE['id_user'];

            $sql = "SELECT id_user, user_name, user_lastname, random_bytes FROM
    users WHERE id_user = :id_user";
            $handler = Application::$storage->get()->prepare($sql);
            $handler->execute(['id_user' => $id]);
            $result = $handler->fetchAll();

            if (!empty($result) && ($_COOKIE['random_bytes'] == $result[0]['random_bytes'])) {
                $_SESSION['auth']['user_name'] = $result[0]['user_name'];
                $_SESSION['auth']['user_lastname'] = $result[0]['user_lastname'];
                $_SESSION['auth']['id_user'] = $result[0]['id_user'];
                //fingerprint
                var_dump('мы автоматически авторизовались');
                return true;
            } else {
                var_dump('мы автоматически не авторизовались');
                return false;
            }
        } else {
            return false;
        }
    }

    public function suitToRegex($password)
    {
        $pattern = '/^(?=.*\d)(?=.*[A-Za-z])(?=.*[^\s\w\d])(^\S{8,16})$/';
        if (preg_match($pattern, $password)) {
            echo 1;
        } else {
            echo 0;
        }
    }
}
