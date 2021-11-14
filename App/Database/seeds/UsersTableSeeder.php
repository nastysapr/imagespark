<?php

//namespace App\Database\seeds;

use App\Models\User;
use App\Service\Authorization;

class UsersTableSeeder
{
    //проверка на наличие
    //default
    public function seed(int $count): void
    {
        /**
         * Проверка, есть ли админ, если нет -- создаем
         */
        $admin = new User();

        if (empty($admin->findAll(0, 0, 'seeder_admin'))) {
            $admin->login = 'seeder_admin';
            $admin->password = password_hash(1234, PASSWORD_BCRYPT);
            $admin->full_name = 'Test admin from seeder';
            $admin->birthday = date('Y-m-d');
            $admin->email = 'seeder_admin@1.ru';
            $admin->role = Authorization::ROLE_ADMIN;

            $admin->save();
        }

        for ($i = 0; $i < $count; $i++) {
            $user = new User();
            $user->login = 'user' . $i;
            $user->password = password_hash(1234, PASSWORD_BCRYPT);
            $user->full_name = 'Test user from seeder';
            $user->birthday = date('Y-m-d');
            $user->email = $user->login . '@1.ru';
            $user->role = Authorization::ROLE_USER;

            $user->save();
        }
    }
}