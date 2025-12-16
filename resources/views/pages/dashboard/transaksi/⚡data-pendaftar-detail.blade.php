<?php

use App\Models\Registration;
use Livewire\Component;

new class extends Component {

    public Registration $registration;

    public function mount(Registration $registration)
    {
        $this->registration = $registration->load([
            'user',
            'seminar',
            'payment.verifier',
            'attendence.scanner',
        ]);
    }
};
?>
<div>
    <x-page-title title="Detail Registrasi Seminar" />

    <div class="row">

        {{-- REGISTRATION --}}
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-header fw-semibold">Informasi Registrasi</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Ticket Code</th>
                            <td>{{ $registration->ticket_code }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $registration->status->color() }}">
                                    {{ str($registration->status->value)->title() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Registered At</th>
                            <td>{{ $registration->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- USER --}}
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-header fw-semibold">Data Peserta</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Nama</th>
                            <td>{{ $registration->user?->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $registration->user?->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $registration->user?->phone ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- SEMINAR --}}
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header fw-semibold">Data Seminar</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="20%">Judul</th>
                            <td>{{ $registration->seminar?->title }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $registration->seminar?->location }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ $registration->seminar?->date_start?->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- PAYMENT --}}
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-header fw-semibold">Pembayaran</div>
                <div class="card-body">
                    @if ($registration->payment)
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Amount</th>
                                <td>Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-success">
                                        {{ str($registration->payment->status->value)->title() }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Verified By</th>
                                <td>{{ $registration->payment->verifier?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Bukti</th>
                                <td>
                                    @if ($registration->payment?->proof_path)
                                        <a href="{{ Storage::url($registration->payment->proof_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            Lihat Bukti
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @else
                        <span class="badge bg-secondary">Belum ada pembayaran</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ATTENDENCE --}}
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-header fw-semibold">Absensi</div>
                <div class="card-body">
                    @if ($registration->attendence)
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Scanned At</th>
                                <td>{{ $registration->attendence->scanned_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Scanned By</th>
                                <td>{{ $registration->attendence->scanner?->name }}</td>
                            </tr>
                        </table>
                        <span class="badge bg-primary">Hadir</span>
                    @else
                        <span class="badge bg-light text-dark">Belum Hadir</span>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>