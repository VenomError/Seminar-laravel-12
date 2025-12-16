<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use App\Repository\AttendenceRepository;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = (int) $this->command->ask("Jumlah Peserta ?", 100);
        $registrations = Registration::inRandomOrder()->take($count)->get();
        $repo = new AttendenceRepository();

        $this->command->withProgressBar($registrations, function ($registration) use ($repo) {
            try {
                $repo->create(
                    Carbon::parse(fake()->dateTime()),
                    $registration->user,
                    $registration
                );
            } catch (\Throwable $th) {
                $this->command->newLine()->error($th->getMessage());
            }
        });
    }
}
