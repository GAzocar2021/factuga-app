<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'Administrador',
                'email' => 'admin@factugaap.com',
                'is_admin' => true,
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Cliente 1',
                'email' => 'cliente1@factugaap.com',
                'password' => bcrypt('90123456'),
            ],
            [
                'name' => 'Cliente 2',
                'email' => 'cliente2@factugaap.com',
                'password' => bcrypt('78901234'),
            ],
            [
                'name' => 'Cliente 3',
                'email' => 'cliente3@factugaap.com',
                'password' => bcrypt('56789012'),
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
