<?php

namespace Database\Seeders;

use App\Enum\SeminarStatus;
use App\Models\User;
use App\Repository\SeminarRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeminarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = (int) $this->command->ask('Jumlah Seminar', 10);
        $repo = new SeminarRepository();
        $this->command->withProgressBar(range(1, $count), function () use ($repo) {
            try {
                $data = [
                    'title' => fake()->words(5, true),
                    'description' => fake()->paragraph(),
                    'location' => fake()->address(),
                    'date_start' => fake()->dateTime(),
                    'quota' => fake()->numberBetween(40, 500),
                    'price' => fake()->numberBetween(100000, 1000000),
                    'status' => fake()->randomElement(SeminarStatus::values()),
                ];
                $creator = User::admin()->inRandomOrder()->first();
                $repo->create($data, $creator);
            } catch (\Throwable $th) {
                //throw $th;
            }
        });
    }
}
