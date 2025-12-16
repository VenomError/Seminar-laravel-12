<?php

use App\Models\User;
use App\Models\Seminar;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Attendence;
use App\Enum\PaymentStatus;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {

    #[Computed]
    public function totalUsers()
    {
        return User::count();
    }

    #[Computed]
    public function totalSeminars()
    {
        return Seminar::count();
    }

    #[Computed]
    public function totalRegistrations()
    {
        return Registration::count();
    }

    #[Computed]
    public function totalIncome()
    {
        return Payment::where('status', PaymentStatus::VERIFIED)->sum('amount');
    }

    #[Computed]
    public function latestPayments()
    {
        return Payment::with('registration.user')
            ->latest()
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function todayAttendances()
    {
        return Attendence::whereDate('scanned_at', now())
            ->count();
    }
};?>
<div>
    <x-page-title title="Dashboard Admin" />

    {{-- STAT CARDS --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>Total User</h6>
                    <h3 class="fw-bold">{{ $this->totalUsers() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>Total Seminar</h6>
                    <h3 class="fw-bold">{{ $this->totalSeminars() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>Total Pendaftaran</h6>
                    <h3 class="fw-bold">{{ $this->totalRegistrations() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>Total Pendapatan</h6>
                    <h3 class="fw-bold">
                        Rp {{ number_format($this->totalIncome(), 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 2 --}}
    <div class="row">

        {{-- TRANSAKSI TERBARU --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Transaksi Terbaru</h5>

                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->latestPayments() as $payment)
                                <tr>
                                    <td>{{ $payment->registration?->ticket_code }}</td>
                                    <td>{{ $payment->registration?->user?->name }}</td>
                                    <td>
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status->color() }}">
                                            {{ str($payment->status->value)->title() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada transaksi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ATTENDANCE TODAY --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Absensi Hari Ini</h5>

                    <div class="text-center">
                        <h1 class="fw-bold">{{ $this->todayAttendances() }}</h1>
                        <p class="text-muted">Peserta hadir hari ini</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>