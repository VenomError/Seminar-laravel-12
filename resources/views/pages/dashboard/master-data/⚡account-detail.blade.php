<?php

use App\Enum\UserRole;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user->load([
            'registrations.seminar',
            'registrations.payment.verifier',
            'registrations.attendence.scanner',
            'createdSeminars',
        ]);
    }

    #[Computed]
    public function registrations()
    {
        return $this->user->registrations;
    }

    #[Computed]
    public function createdSeminars()
    {
        return $this->user->createdSeminars;
    }
};?>
<div>
    <x-page-title title="Detail User" />

    {{-- USER INFO --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Informasi User</h5>

            <table class="table table-borderless mb-0">
                <tr>
                    <th width="200">Nama</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $user->phone }}</td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>
                        <span class="badge bg-info">
                            {{ str($user->role->value)->title() }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Registered At</th>
                    <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- SEMINAR CREATED (ADMIN / COMMITTEE) --}}
    @if ($user->role !== \App\Enum\UserRole::PARTICIPANT)
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Seminar Dibuat</h5>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Quota</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->createdSeminars() as $seminar)
                            <tr>
                                <td>{{ $seminar->title }}</td>
                                <td>
                                    <span class="badge bg-{{ $seminar->status->color() }}">
                                        {{ str($seminar->status->value)->title() }}
                                    </span>
                                </td>
                                <td>{{ $seminar->quota }}</td>
                                <td>{{ $seminar->date_start->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Tidak ada seminar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- REGISTRATIONS --}}
    <div class="card">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Riwayat Registrasi Seminar</h5>

            <table class="table">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Seminar</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->registrations() as $registration)
                        <tr>
                            <td class="fw-semibold">{{ $registration->ticket_code }}</td>

                            <td>{{ $registration->seminar?->title }}</td>

                            <td>
                                <span class="badge bg-{{ $registration->status->color() }}">
                                    {{ str($registration->status->value)->title() }}
                                </span>
                            </td>

                            <td>
                                @if ($registration->payment)
                                    <span class="badge bg-success">Paid</span>
                                    <br>
                                    <small>
                                        Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}
                                    </small>
                                    <br>
                                    <a href="{{ Storage::url($registration->payment->proof_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary mt-1">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Unpaid</span>
                                @endif
                            </td>

                            <td>
                                @if ($registration->attendence)
                                    <span class="badge bg-primary">Present</span>
                                    <br>
                                    <small>
                                        {{ $registration->attendence->scanned_at->format('d M Y H:i') }}
                                    </small>
                                @else
                                    <span class="badge bg-light text-dark">Absent</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Belum ada registrasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>