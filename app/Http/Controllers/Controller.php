<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function getUserId(): int
    {
        // For a single-user application without authentication, create or get the first user.
        $user = User::firstOrCreate(
            ['email' => 'default@example.com'],
            ['name' => 'Default User', 'password' => bcrypt('password')]
        );

        return $user->id;
    }
}
