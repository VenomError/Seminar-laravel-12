<?php

use App\Enum\SeminarStatus;
use App\Models\Seminar;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

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
    public function seminars()
    {
        $query = Seminar::with('creator');
        $query->withCount('registrations');
        $query->when(
            $this->status,
            fn($q) =>
            $q->where('status', $this->status)
        );

        $query->when($this->search, function ($q) {
            $search = $this->search;
            $q->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        });

        return $query
            ->orderBy('date_start', 'desc')
            ->paginate($this->perPage);
    }

    public function resetFilter()
    {
        $this->reset('search', 'status');
    }

    // Delete
    public function confirmDelete($id)
    {
        sweetalert()
            ->showDenyButton()
            ->backdrop(true)
            ->option('confirmButtonText', 'Yes, delete it!')
            ->option('denyButtonText', 'Cancel')
            ->option('seminar_id', $id)
            ->warning("Yakin hapus seminar ini?");
    }

    #[On('sweetalert:confirmed')]
    public function deleteSeminar(array $payload)
    {
        $id = $payload['envelope']['options']['seminar_id'];
        $seminar = Seminar::find($id);

        if (!$seminar) {
            sweetalert()
                ->showConfirmButton(false)
                ->timer(1000)
                ->error("Seminar tidak ditemukan");
            return;
        }

        $seminar->delete();

        sweetalert()
            ->showConfirmButton(false)
            ->timer(1000)
            ->success("Seminar berhasil dihapus");
    }
};
?>
<div>
    <x-page-title title="Manajemen Seminar">
        <a href="{{ route('dashboard.master-data.seminar-create') }}" class="btn btn-primary ms-2 fs-13 btn-md">
            <i class="ti ti-plus me-1"></i>Tambah Seminar
        </a>
    </x-page-title>

    {{-- Filter --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div class="search-set mb-3">
            <input type="search" class="form-control form-control-sm" placeholder="Search seminar..."
                wire:model.live="search">
        </div>

        <div class="d-flex gap-2 mb-3">
            <select class="form-select form-select-sm" wire:model.live="status">
                <option value="">All Status</option>
                @foreach (SeminarStatus::values() as $status)
                    <option value="{{ $status }}">{{ str($status)->title() }}</option>
                @endforeach
            </select>

            <button class="btn btn-outline-danger btn-sm" wire:click="resetFilter">
                Reset
            </button>
        </div>
    </div>

    <div class="card" id="table-seminar">
        <div class="card-body table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Price</th>
                        <th>Quota</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->seminars() as $seminar)
                        <tr>
                            <td>{{ $seminar->id }}</td>
                            <td class="fw-semibold text-nowrap">{{ $seminar->title }}</td>
                            <td>
                                <span class="badge bg-{{ $seminar->status->color() }} ">
                                    {{ $seminar->status }}
                                </span>
                            </td>
                            <td class="text-nowrap">{{ $seminar->location }}</td>
                            <td><span class="text-nowrap">{{ $seminar->date_start->format('Y-m-d H:i') }}</span></td>
                            <td class="text-nowrap">
                                @if ($seminar->isPaid())
                                    Rp {{ number_format($seminar->price, 0, ',', '.') }}
                                @else
                                    <span class="badge bg-success">Free</span>
                                @endif
                            </td>
                            <td>{{ $seminar->quota }}/{{ $seminar->registrations_count }}</td>
                            <td class="text-nowrap">{{ $seminar->creator?->name }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('dashboard.master-data.seminar-detail', ['seminar' => $seminar->id]) }}"
                                        class="link-reset fs-18 p-1">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.master-data.seminar-edit', ['seminar' => $seminar->id]) }}"
                                        class="link-reset fs-18 p-1">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <a wire:click="confirmDelete({{ $seminar->id }})" class="link-reset fs-18 p-1"
                                        style="cursor:pointer">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $this->seminars()->links(data: ['scrollTo' => '#table-seminar']) }}
            </div>
        </div>
    </div>
</div>