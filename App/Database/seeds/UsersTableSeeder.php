<?php

class UsersTableSeeder extends Seeder
{
    //проверка на наличие
    //default
    public function seed(): void
    {
        $admin = new User();
        $admin->login = 'seeder_admin';
        $admin->password = password_hash(1234, PASSWORD_BCRYPT);
        $admin->full_name = 'Test admin from seeder';
        $admin->birthday = date('Y-m-d');
        $admin->email = 'seeder_admin@1.ru';
        $admin->role = Authorization::ROLE_ADMIN;

        $admin->save();
    }
}