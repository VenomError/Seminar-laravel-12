<?php

namespace Database\Seeders;

use App\Repository\UserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = (int) $this->command->ask("How many users do you want to create?");
        $role = $this->command->choice("Select a role", ["committee", "participant"]);
        $repo = new UserRepository();
        $this->command->withProgressBar(range(1, $count), function ($i) use ($role, $repo) {
            try {
                $repo->create([
                    'name' => fake()->name(),
                    'email' => fake()->unique()->email(),
                    'password' => 'password',
                    'role' => $role,
                    'phone' => fake()->phoneNumber
                ]);
            } catch (\Exception $err) {
                $this->command->newLine()->error($err->getMessage());
            }
        });
    }
}
