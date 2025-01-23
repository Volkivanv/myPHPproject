<?php

namespace Geekbrains\Application1\Domain\Models;

use Geekbrains\Application1\Infrastructure\Storage;
use Geekbrains\Application1\Application\Application;

class User
{
    private ?int $idUser;
    private ?string $userName;

    private ?string $userLastName;

    private ?int $userBirthday;

    private static string $storageAddress = '/storage/birthdays.txt';

    public function __construct(string $name = null, string $lastName = null, int $birthday = null, int $id_user = null)
    {
        $this->userName = $name;
        $this->userLastName = $lastName;
        $this->userBirthday = $birthday;
        $this->idUser = $id_user;
    }

    public function setUserId(int $id_user): void
    {
        $this->idUser = $id_user;
    }

    public function getUserId(): ?int
    {
        return $this->idUser;
    }
    public function setName(string $userName): void
    {
        $this->userName = $userName;
    }
    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setLastName(string $userLastName): void
    {
        $this->userLastName = $userLastName;
    }
    public function getUserLastName(): string
    {
        return $this->userLastName;
    }
    public function getUserBirthday(): int
    {
        return $this->userBirthday;
    }
    public function setBirthdayFromString(string $birthdayString): void
    {
        $this->userBirthday = strtotime($birthdayString);
    }
    // public static function getAllUsersFromStorage(): array|false
    // {
    //     $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;
    //     if (file_exists($address) && is_readable($address)) {
    //         $file = fopen($address, "r");
    //         $users = [];
    //         while (!feof($file)) {
    //             $userString = fgets($file);
    //             if (strlen($userString) == 0) {
    //                 break;
    //             }
    //             $userArray = explode(",", $userString);

    //             $user = new User(
    //                 $userArray[0]
    //             );
    //             $user->setBirthdayFromString($userArray[1]);
    //             $users[] = $user;
    //         }
    //         fclose($file);
    //         return $users;
    //     } else {
    //         return false;
    //     }
    // }

    public static function getUserFromStorageById(int $id)
    {
        $sql = "SELECT * FROM users WHERE id_user = :id";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['id' => $id]);
        $result = $handler->fetch();
        return  new User(
            $result['user_name'],
            $result['user_lastname'],
            $result['user_birthday_timestamp'],
            $result['id_user'],
        );
    }

    public static function getAllUsersFromStorage(): array
    {
        $sql = "SELECT * FROM users";
        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute();
        $result = $handler->fetchAll();
        $users = [];
        foreach ($result as $item) {
            $user = new User(
                $item['user_name'],
                $item['user_lastname'],
                $item['user_birthday_timestamp'],
                $item['id_user'],
            );
            $users[] = $user;
        }
        return $users;
    }

    public static function addUserFromRequest()
    {

        $userN = $_GET['name'] ?? '';
        $userBirthdayString = $_GET['birthday'] ?? '';
        $userB = strtotime($userBirthdayString);

        $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;
        $file = fopen($address, "a");
        $userString = $userN . ', ' . $userBirthdayString . PHP_EOL;
        fwrite($file, $userString);
        fclose($file);
        //  $newUser = new User($userN, $userB);
        $users = User::getAllUsersFromStorage();
        // $users[] = $newUser;
        return $users;
    }

    public static function validateRequestData(): bool
    {
        $result = true;

        if (!(
            isset($_POST['name']) && !empty($_POST['name']) &&
            isset($_POST['lastname']) && !empty($_POST['lastname']) &&
            isset($_POST['birthday']) && !empty($_POST['birthday'])
        )) {
            $result = false;
        }

        if (!preg_match('/^(\d{2}-\d{2}-\d{4})$/', $_POST['birthday'])) {
            return false;
        }

        if (
            !isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] != $_POST['csrf_token']
        ) {
            $result = false;
        }
        return $result;
    }

    public function setParamsFromRequestData(): void
    {
        $this->userName = htmlspecialchars($_POST['name']);
        $this->userLastName = htmlspecialchars($_POST['lastname']);
        $this->setBirthdayFromString($_POST['birthday']);
    }

    public function saveToStorage()
    {
        $storage = new Storage();
        $sql = "INSERT INTO users(user_name, user_lastname,
        user_birthday_timestamp) VALUES (:user_name, :user_lastname, :user_birthday)";
        $handler = $storage->get()->prepare($sql);
        $handler->execute([
            'user_name' => $this->userName,
            'user_lastname' => $this->userLastName,
            'user_birthday' => $this->userBirthday
        ]);
    }

    public static function exists(int $id): bool
    {
        $sql = "SELECT count(id_user) as user_count FROM users WHERE id_user = :id_user";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute([
            'id_user' => $id
        ]);

        $result = $handler->fetchAll();

        if (count($result) > 0 && $result[0]['user_count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUser(array $userDataArray): void
    {
        $sql = "UPDATE users SET ";

        $counter = 0;
        foreach ($userDataArray as $key => $value) {
            $sql .= $key . " = :" . $key;

            if ($counter != count($userDataArray) - 1) {
                $sql .= ",";
            }

            $counter++;
        }

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute($userDataArray);
    }

    // public static function deleteFromStorage(int $user_id): void
    // {
    //     $sql = "DELETE FROM users WHERE id_user = :id_user ";

    //     $handler = Application::$storage->get()->prepare($sql);
    //     $handler->execute(['id_user' => $user_id]);
    // }

    public static function deleteFromStorage(int $user_id): void
    {
        $sql = "DELETE users, payments FROM users INNER JOIN payments on users.id_user = payments.user_id WHERE users.id_user = :id_user";



        $handler = Application::$storage->get()->prepare($sql);

        $handler->execute(['id_user' => $user_id]);
        // var_dump($sql);
        // die();
    }

    
}
