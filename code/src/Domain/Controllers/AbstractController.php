<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;

use Geekbrains\Application1\Domain\Controllers\Controller;

use Geekbrains\Application1\Domain\Models\User;



class AbstractController extends Controller
{
    protected array $actionsPermissions = [];
// реализовать получение роли в юзере
    public function getUserRoles(): array
    {
        return User::getUserRoles();
    }
    public function getActionsPermissions(string $methodName): ?array
    {
        return isset($this->actionsPermissions[$methodName]) ?
            $this->actionsPermissions[$methodName] : null;
    }
}
