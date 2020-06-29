<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\UserRole;
use Phinx\Seed\AbstractSeed;

class Users extends AbstractSeed
{
    public function run(): void
    {
        $role = new UserRole();
        $role->title = 'Admin';
        $role->scope = ['users'];
        $role->save();

        $user = new User();
        $user->id = $role->id;
        $user->role_id = 1;
        $user->username = 'admin';
        $user->password = 'admin';
        $user->save();
    }
}
