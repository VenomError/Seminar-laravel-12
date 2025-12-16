<?php

use App\Models\Seminar;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {

    public Seminar $seminar;

    public function mount(Seminar $seminar)
    {
        $this->seminar = $seminar->load([
            'creator',
            'registrations.user',
            'registrations.payment.verifier',
            'registrations.attendence.scanner',
        ]);
    }

    #[Computed]
    public function registrations()
    {
        return $this->seminar->registrations;
    }

    #[Computed]
    public function registeredCount()
    {
        return $this->seminar->registrations->count();
    }
};?>
<div>
    <x-page-title title="Detail Seminar" />

    {{-- SEMINAR INFO --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Informasi Seminar</h5>

            <div class="row">
                <div class="col-lg-8">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="200">Judul</th>
                            <td>{{ $seminar->title }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $seminar->status->color() }}">
                                    {{ str($seminar->status->value)->title() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $seminar->location }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ $seminar->date_start->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>
                                @if ($seminar->isPaid())
                                    Rp {{ number_format($seminar->price, 0, ',', '.') }}
                                @else
                                    <span class="badge bg-success">Gratis</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Quota</th>
                            <td>
                                {{ $this->registeredCount() }} / {{ $seminar->quota }}
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $seminar->creator?->name }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-lg-4">
                    @if ($seminar->thumbnail)
                        <img src="{{ Storage::url($seminar->thumbnail) }}" class="img-fluid rounded"
                            alt="Thumbnail Seminar">
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- DESKRIPSI --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-2">Deskripsi</h5>
            <p class="mb-0 text-muted">
                {{ $seminar->description ?? '-' }}
            </p>
        </div>
    </div>

    {{-- REGISTRATIONS --}}
    <div class="card">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Peserta Terdaftar</h5>

            <table class="table">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->registrations() as $registration)
                        <tr>
                            <td class="fw-semibold">
                                {{ $registration->ticket_code }}
                            </td>

                            <td>
                                <div class="fw-semibold">{{ $registration->user?->name }}</div>
                                <small class="text-muted">{{ $registration->user?->email }}</small>
                            </td>

                            <td>
                                <span class="badge bg-{{ $registration->status->color() }}">
                                    {{ str($registration->status->value)->title() }}
                                </span>
                            </td>

                            <td>
                                @if ($registration->payment)
                                    <span class="badge bg-success">Paid</span><br>
                                    <small>
                                        Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}
                                    </small><br>
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
                                    <span class="badge bg-primary">Present</span><br>
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
                                Belum ada peserta
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>