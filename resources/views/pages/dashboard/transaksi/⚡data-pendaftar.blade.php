<?php

use App\Enum\RegistrationStatus;
use App\Models\Registration;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public $perPage = 10;
    public $search;
    public $status;
    public $payment;
    public $attendance;

    public function updatedSearch($value)
    {
        $this->search = rtrim($value);
        $this->search = ltrim($value);
    }

    #[Computed]
    public function registrations()
    {
        $query = Registration::with([
            'user',
            'seminar',
            'payment',
            'attendence',
        ]);

        $query->when(
            $this->status,
            fn($q) =>
            $q->where('status', $this->status)
        );

        // 🔹 Filter payment
        $query->when($this->payment, function ($q) {
            if ($this->payment === 'paid') {
                $q->whereHas('payment');
            }

            if ($this->payment === 'unpaid') {
                $q->whereDoesntHave('payment');
            }
        });

        // 🔹 Filter attendance
        $query->when($this->attendance, function ($q) {
            if ($this->attendance === 'present') {
                $q->whereHas('attendence');
            }

            if ($this->attendance === 'absent') {
                $q->whereDoesntHave('attendence');
            }
        });


        $query->when($this->search, function ($q) {
            $search = $this->search;
            $q->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                    ->orWhereHas(
                        'user',
                        fn($q) =>
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                    )
                    ->orWhereHas(
                        'seminar',
                        fn($q) =>
                        $q->where('title', 'like', "%{$search}%")
                    );
            });
        });

        return $query
            ->latest()
            ->paginate($this->perPage);
    }

    public function resetFilter()
    {
        $this->reset('search', 'status', 'payment', 'attendance');
    }
};?>
<div>
    <x-page-title title="Data Registrasi Seminar" />

    {{-- Filter --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
        <div class="search-set">
            <input type="search" class="form-control form-control-sm" placeholder="Search ticket / user / seminar..."
                wire:model.live="search">
        </div>

        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" wire:model.live="status">
                <option value="">All Status</option>
                @foreach (RegistrationStatus::values() as $status)
                    <option value="{{ $status }}">{{ str($status)->title() }}</option>
                @endforeach
            </select>

            {{-- Payment --}}
            <select class="form-select form-select-sm" wire:model.live="payment">
                <option value="">All Payment</option>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
            </select>

            {{-- Attendance --}}
            <select class="form-select form-select-sm" wire:model.live="attendance">
                <option value="">All Attendance</option>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
            </select>

            <button class="btn btn-outline-danger btn-sm" wire:click="resetFilter">
                Reset
            </button>
        </div>
    </div>

    <div class="card" id="table-registration">
        <div class="card-body table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ticket</th>
                        <th>User</th>
                        <th>Seminar</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Attendance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->registrations() as $registration)
                        <tr>
                            <td>{{ $registration->id }}</td>

                            <td class="fw-semibold text-nowrap">
                                {{ $registration->ticket_code }}
                            </td>

                            <td class="text-nowrap">
                                <div class="fw-semibold">{{ $registration->user?->name }}</div>
                                <small class="text-muted">{{ $registration->user?->email }}</small>
                            </td>

                            <td class="text-nowrap">
                                {{ $registration->seminar?->title }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $registration->status->color() }}">
                                    {{ str($registration->status->value)->title() }}
                                </span>
                            </td>

                            <td>
                                @if ($registration->payment)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-secondary">Unpaid</span>
                                @endif
                            </td>

                            <td>
                                @if ($registration->attendence)
                                    <span class="badge bg-primary">Present</span>
                                @else
                                    <span class="badge bg-light text-dark">Absent</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('dashboard.transaksi.data-pendaftar.detail', ['registration' => $registration->id]) }}"
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
                {{ $this->registrations()->links(data: ['scrollTo' => '#table-registration']) }}
            </div>
        </div>
    </div>
</div>