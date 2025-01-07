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
                $item['user_birthday_timestamp']
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
        if (
            isset($_GET['name']) && !empty($_GET['name']) &&
            isset($_GET['lastname']) && !empty($_GET['lastname']) &&
            isset($_GET['birthday']) && !empty($_GET['birthday'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function setParamsFromRequestData(): void
    {
        $this->userName = $_GET['name'];
        $this->userLastName = $_GET['lastname'];
        $this->setBirthdayFromString($_GET['birthday']);
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
}
