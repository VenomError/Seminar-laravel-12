<?php

namespace Database\Seeders;

use App\Enum\PaymentStatus;
use App\Models\Registration;
use App\Repository\PaymentRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = (int) $this->command->ask("Jumlah Peserta ?", 100);
        $registrations = Registration::inRandomOrder()->take($count)->get();
        $repo = new PaymentRepository();

        $this->command->withProgressBar($registrations, function ($registration) use ($repo) {
            try {
                $data = [
                    'amount' => $registration->seminar->price,
                    'proof_path' => 'payment/bukti-pembayaran.png',
                    'status' => fake()->randomElement(PaymentStatus::values()),
                ];
                $repo->create($data, $registration);
            } catch (\Throwable $th) {
                $this->command->newLine()->error($th->getMessage());
            }
        });
    }
}
