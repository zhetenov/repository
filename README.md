# Laravel Repository Pattern

[![Issues](https://img.shields.io/github/issues/zhetenov/repository)](https://github.com/zhetenov/repository/issues)
[![Forks](https://img.shields.io/github/forks/zhetenov/repository)](https://github.com/zhetenov/repository/network/members)
[![Stars](https://img.shields.io/github/stars/zhetenov/repository)](https://github.com/zhetenov/repository/stargazers)
[![License](https://img.shields.io/github/license/zhetenov/repository)](https://github.com/zhetenov/repository/blob/master/LICENSE)

This package gives you an easy way to create repository in laravel by using command.

## Installation

In your terminal:
```shell
composer require zhetenov/repository
```

## Using

By using this command you can easily create repository:
```bash
php artisan make:repository User
```

You will see that there is a folder named Repositories and in this folder has User folder and it has 2 files UserRepository and User interface:
```shell
$ tree -a app
app
├── Console
├── Exceptions
├── Http
├── Providers
├── Repositories
│   └── User
│       ├── UserInterface.php
│       └── UserRepository.php
└── User.php
```

### Repository

To begin, we need to return path of model in `getModelClass` method. And you can start working with repository pattern.
For example to get all users we created method `getAll`. To start we called method startConditions which return copy of our User model(QueryBuilder). After that we can write own queryBuilder.   
```bash
<?php

namespace app\Repositories\User;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Zhetenov\Repository\BaseRepository;

class UserRepository extends BaseRepository implements UserInterface
{
    /**
     * Returns current model.
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        return User::class;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this
            ->startConditions()
            ->all();
    }
}

```
