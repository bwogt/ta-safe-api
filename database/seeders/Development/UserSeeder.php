<?php

namespace Database\Seeders\Development;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local')) {
            $users = $this->loadUsersFile();

            foreach ($users as $rawUser) {
                $this->createUser($rawUser);
            }
        }
    }

    private function loadUsersFile(): mixed
    {
        $json = File::get(database_path('data/development/users.json'));

        return json_decode($json);
    }

    private function createUser($rawUser): void
    {
        User::updateOrCreate([
            'name' => $rawUser->name,
            'email' => $rawUser->email,
            'cpf' => $rawUser->cpf,
            'phone' => $rawUser->phone,
            'password' => $rawUser->password,
        ]);
    }
}
