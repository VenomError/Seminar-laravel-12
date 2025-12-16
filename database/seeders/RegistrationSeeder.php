<?php

namespace Database\Seeders;

use App\Enum\RegistrationStatus;
use App\Models\Seminar;
use App\Models\User;
use App\Repository\RegistrationRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = (int) $this->command->ask("Jumlah Peserta ?", 50);
        $participant = User::participant()->take($count)->get();
        $repo = new RegistrationRepository();
        $this->command->withProgressBar($participant, function ($user) use ($repo) {
            try {
                $repo->create(
                    $user,
                    Seminar::inRandomOrder()->first(),
                    RegistrationStatus::tryFrom(fake()->randomElement(RegistrationStatus::values()))
                );
            } catch (\Throwable $th) {
                $this->command->error($th->getMessage());
            }
        });
    }
}
