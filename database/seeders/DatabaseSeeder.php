<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'topem colombia',
            'email' => 'topem@correo.com',
            'nit' => '111555999',
            'password' => Hash::make('password')
        ]);

        User::create([
            'name' => 'david pelaez',
            'email' => 'david@correo.com',
            'nit' => '222333444',
            'password' => Hash::make('password')
        ]);
    }
}
