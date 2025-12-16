<?php

namespace Database\Seeders;

use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        try {
            $data = [
                'name' => fake()->name,
                'email' => $this->command->ask('Email', 'admin@gmail.com'),
                'password' => $this->command->ask('Password', 'password'),
                'phone' => fake()->phoneNumber
            ];
            $repo = new UserRepository();
            $repo->createAdmin($data);
            $this->command->newLine()->info('Admin Berhasil di Tambah');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
