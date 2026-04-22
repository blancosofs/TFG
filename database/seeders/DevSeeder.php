<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
       public function run(): void
{
    \App\Models\User::create([
        'name' => 'Administrador',
        'email' => 'administrador@edunoly.com',
        'password' => \Illuminate\Support\Facades\Hash::make('admin1234'),
        'activo' => true,
    ]);
}

}
