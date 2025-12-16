<?php

use App\Models\Attendence;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Computed;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public $perPage = 10;
    public $search;
    public $seminarId;
    public $date;

    public function updatedSearch($value)
    {
        $this->search = rtrim($value);
        $this->search = ltrim($value);
    }
    #[Computed]
    public function attendences()
    {
        $query = Attendence::with([
            'registration.user',
            'registration.seminar',
            'scanner'
        ]);

        // filter seminar
        $query->when(
            $this->seminarId,
            fn($q) =>
            $q->whereHas(
                'registration',
                fn($q) =>
                $q->where('seminar_id', $this->seminarId)
            )
        );

        // filter tanggal scan
        $query->when(
            $this->date,
            fn($q) =>
            $q->whereDate('scanned_at', $this->date)
        );

        // search
        $query->when($this->search, function ($q) {
            $search = trim($this->search);

            $q->whereHas(
                'registration',
                fn($q) =>
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
                    )
            );
        });

        return $query
            ->latest('scanned_at')
            ->paginate($this->perPage);
    }

    public function resetFilter()
    {
        $this->reset('search', 'seminarId', 'date');
    }
};?>
<div>
    <x-page-title title="Laporan Riwayat Absensi" />

    {{-- FILTER --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap mb-3 gap-2">
        <input type="search" class="form-control form-control-sm w-auto" placeholder="Search ticket / user / seminar..."
            wire:model.live="search">

        <div class="d-flex gap-2">
            <input type="date" class="form-control form-control-sm" wire:model.live="date">

            <button class="btn btn-outline-danger btn-sm" wire:click="resetFilter">
                Reset
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card" id="table-attendance">
        <div class="card-body table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ticket</th>
                        <th>Peserta</th>
                        <th>Seminar</th>
                        <th>Waktu Scan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->attendences() as $attendance)
                        <tr>
                            <td>{{ $attendance->id }}</td>

                            <td class="fw-semibold text-nowrap">
                                {{ $attendance->registration?->ticket_code }}
                            </td>

                            <td class="text-nowrap">
                                <div class="fw-semibold">
                                    {{ $attendance->registration?->user?->name }}
                                </div>
                                <small class="text-muted">
                                    {{ $attendance->registration?->user?->email }}
                                </small>
                            </td>

                            <td class="text-nowrap">
                                {{ $attendance->registration?->seminar?->title }}
                            </td>

                            <td class="text-nowrap">
                                {{ $attendance->scanned_at?->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Data absensi belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $this->attendences()->links(data: ['scrollTo' => '#table-attendance']) }}
            </div>
        </div>
    </div>
</div>