<?php

namespace Geekbrains\Application1\Domain\Models;

class User
{
    private string $userName;
    private ?int $userBirthday;

    private static string $storageAddress = '/storage/birthdays.txt';

    public function __construct(string $name, int $birthday = null)
    {
        $this->userName = $name;
        $this->userBirthday = $birthday;
    }

    public function setName(string $userName): void
    {
        $this->userName = $userName;
    }
    public function getUserName(): string
    {
        return $this->userName;
    }
    public function getUserBirthday(): int
    {
        return $this->userBirthday;
    }
    public function setBirthdayFromString(string $birthdayString): void
    {
        $this->userBirthday = strtotime($birthdayString);
    }
    public static function getAllUsersFromStorage(): array|false
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;
        if (file_exists($address) && is_readable($address)) {
            $file = fopen($address, "r");
            $users = [];
            while (!feof($file)) {
                $userString = fgets($file);
                if(strlen($userString) == 0){
                    break;
                }
                $userArray = explode(",", $userString);

                $user = new User(
                    $userArray[0]
                );
                $user->setBirthdayFromString($userArray[1]);
                $users[] = $user;
            }
            fclose($file);
            return $users;
        } else {
            return false;
        }
    }
    public static function addUserFromRequest()
    {

        $userN = $_GET['name'] ?? '';
        $userBirthdayString = $_GET['birthday'] ?? '';
        $userB = strtotime($userBirthdayString);

        $address = $_SERVER['DOCUMENT_ROOT'] . User::$storageAddress;
        $file = fopen($address, "a");
        $userString =$userN . ', ' . $userBirthdayString. PHP_EOL;
        fwrite($file, $userString);
        fclose($file);
      //  $newUser = new User($userN, $userB);
        $users = User::getAllUsersFromStorage();
       // $users[] = $newUser;
        return $users;
    }
}