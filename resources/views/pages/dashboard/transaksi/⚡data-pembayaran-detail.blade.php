<?php

use App\Models\Payment;
use Livewire\Component;

new class extends Component {

    public Payment $payment;

    public function mount(Payment $payment)
    {
        $this->payment = $payment->load([
            'registration.user',
            'registration.seminar',
            'verifier'
        ]);
    }
};?>
<div>
    <x-page-title title="Detail Pembayaran" />

    {{-- PAYMENT INFO --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Informasi Pembayaran</h5>

            <table class="table table-borderless mb-0">
                <tr>
                    <th width="200">ID Payment</th>
                    <td>{{ $payment->id }}</td>
                </tr>
                <tr>
                    <th>Ticket Code</th>
                    <td class="fw-semibold">
                        {{ $payment->registration?->ticket_code }}
                    </td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-{{ $payment->status->color() }}">
                            {{ str($payment->status->value)->title() }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Diverifikasi Oleh</th>
                    <td>
                        {{ $payment->verifier?->name ?? '-' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- USER & SEMINAR --}}
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Peserta</h5>

                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="150">Nama</th>
                            <td>{{ $payment->registration?->user?->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $payment->registration?->user?->email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $payment->registration?->user?->phone }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Seminar</h5>

                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="150">Judul</th>
                            <td>{{ $payment->registration?->seminar?->title }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $payment->registration?->seminar?->location }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>
                                {{ $payment->registration?->seminar?->date_start?->format('d M Y H:i') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>
                                @if ($payment->registration?->seminar?->isPaid())
                                    Rp {{ number_format($payment->registration->seminar->price, 0, ',', '.') }}
                                @else
                                    <span class="badge bg-success">Gratis</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- BUKTI PEMBAYARAN --}}
    <div class="card">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Bukti Pembayaran</h5>

            @if ($payment->proof_path)
                <div class="d-flex flex-column gap-2 justify-content-center align-items-center ">
                    @if (Str::endsWith($payment->proof_path, ['jpg', 'jpeg', 'png', 'webp']))
                        <img src="{{ Storage::url($payment->proof_path) }}" class="img-fluid rounded border"
                            style="max-width: 400px">
                    @endif
                    <a href="{{ Storage::url($payment->proof_path) }}" target="_blank"
                        class="btn btn-outline-primary w-fit">
                        Lihat Bukti Pembayaran
                    </a>
                </div>
            @else
                <span class="text-muted">Tidak ada bukti pembayaran</span>
            @endif
        </div>
    </div>

</div>