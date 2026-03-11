<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Leonardo Firme',
            'email' => 'leonardo@email.com',
            'password' => Hash::make('Sucesso-748013'),
        ]);
    }
}