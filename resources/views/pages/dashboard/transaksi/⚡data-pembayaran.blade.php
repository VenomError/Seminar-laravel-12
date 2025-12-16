<?php

use App\Models\Payment;
use App\Enum\PaymentStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Computed;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public $perPage = 10;
    public $search;
    public $status;

    public function updatedSearch($value)
    {
        $this->search = rtrim($value);
        $this->search = ltrim($value);
    }

    #[Computed]
    public function payments()
    {
        $query = Payment::with([
            'registration.user',
            'registration.seminar',
            'verifier'
        ]);

        $query->when(
            $this->status,
            fn($q) => $q->where('status', $this->status)
        );

        $query->when($this->search, function ($q) {
            $search = trim($this->search);

            $q->whereHas(
                'registration',
                fn($q) =>
                $q->where('ticket_code', 'like', "%{$search}%")
            )
                ->orWhereHas(
                    'registration.user',
                    fn($q) =>
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                )
                ->orWhereHas(
                    'registration.seminar',
                    fn($q) =>
                    $q->where('title', 'like', "%{$search}%")
                );
        });

        return $query
            ->latest()
            ->paginate($this->perPage);
    }

    public function resetFilter()
    {
        $this->reset('search', 'status');
    }
};?>

<div>
    <x-page-title title="Data Pembayaran" />

    {{-- FILTER --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
        <div class="search-set">
            <input type="search" class="form-control form-control-sm" placeholder="Search ticket / user / seminar..."
                wire:model.live="search">
        </div>

        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" wire:model.live="status">
                <option value="">All Status</option>
                @foreach (PaymentStatus::values() as $status)
                    <option value="{{ $status }}">{{ str($status)->title() }}</option>
                @endforeach
            </select>

            <button class="btn btn-outline-danger btn-sm" wire:click="resetFilter">
                Reset
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card" id="table-payment">
        <div class="card-body table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ticket</th>
                        <th>User</th>
                        <th>Seminar</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Verifier</th>
                        <th>Bukti</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->payments() as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>

                            <td class="fw-semibold text-nowrap">
                                {{ $payment->registration?->ticket_code }}
                            </td>

                            <td class="text-nowrap">
                                <div class="fw-semibold">
                                    {{ $payment->registration?->user?->name }}
                                </div>
                                <small class="text-muted">
                                    {{ $payment->registration?->user?->email }}
                                </small>
                            </td>

                            <td class="text-nowrap">
                                {{ $payment->registration?->seminar?->title }}
                            </td>

                            <td class="text-nowrap">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $payment->status->color() }}">
                                    {{ str($payment->status->value)->title() }}
                                </span>
                            </td>

                            <td class="text-nowrap">
                                {{ $payment->verifier?->name ?? '-' }}
                            </td>

                            <td>
                                @if ($payment->proof_path)
                                    <a href="{{ Storage::url($payment->proof_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('dashboard.transaksi.data-pembayaran.detail', ['payment' => $payment->id]) }}"
                                        class="link-reset fs-18 p-1">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $this->payments()->links(data: ['scrollTo' => '#table-payment']) }}
            </div>
        </div>
    </div>
</div>