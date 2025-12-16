<?php

use App\Enum\UserRole;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;
    public $perPage = 10;
    public $role;
    public $search;
    #[Computed]
    public function accounts()
    {
        $query = User::query();
        $query->when($this->role, fn($q) => $q->whereRole($this->role));

        $query->when($this->search, function ($q) {
            $search = $this->search;

            $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        });
        $query->orderBy('created_at', 'desc');
        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function roles()
    {
        return UserRole::cases();
    }

    public function resetFilter()
    {
        $this->reset('role', 'search');
    }

    // delete
    public function confirmDelete($id)
    {
        sweetalert()
            ->showDenyButton()
            ->backdrop(true)
            ->option('confirmButtonText', 'Yes, delete it!')
            ->option('denyButtonText', 'Cancel')
            ->option('user_id', $id)
            ->warning("Yakin Hapus Akun Ini ?");
    }

    #[On('sweetalert:confirmed')]
    public function deleteUser(array $payload)
    {
        $id = $payload['envelope']['options']['user_id'];
        $user = User::find($id);
        if (!$user) {
            sweetalert()
                ->showConfirmButton(false)
                ->timer(1000)
                ->options([
                    'title' => 'Gagal'
                ])
                ->error("Akun tidak ditemukan");
        }
        if ($user->delete()) {
            sweetalert()
                ->showConfirmButton(false)
                ->timer(1000)
                ->options([
                    'title' => 'Gagal'
                ])
                ->success("Akun Berhasil di Hapus");
        }
    }
};
?>

<div>
    <x-page-title title="Manajemen Pengguna">
        <a href="{{ route('dashboard.master-data.account-add') }}" class="btn btn-primary ms-2 fs-13 btn-md"><i
                class="ti ti-plus me-1"></i>Tambah Akun</a>
    </x-page-title>
    {{-- filter --}}
    <div class=" d-flex align-items-center justify-content-between flex-wrap">
        <div>
            <div class="search-set mb-3">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="table-search d-flex align-items-center mb-0">
                        <div class="search-input">
                            <a href="javascript:void(0);" class="btn-searchset"></a>
                            <div id="DataTables_Table_0_filter" class="dataTables_filter"><label>
                                    <input type="search" class="form-control form-control-sm" placeholder="Search"
                                        aria-controls="DataTables_Table_0" wire:model.live="search"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex table-dropdown mb-3 right-content align-items-center flex-wrap row-gap-3">
            <div class="dropdown">
                <a href="javascript:void(0);"
                    class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                    data-bs-toggle="dropdown">
                    <span class="me-1">Role : </span> {{ str($role ?? 'All')->title() }}
                </a>
                <ul class="dropdown-menu  dropdown-menu-end p-2">
                    <li>
                        <button wire:click="set('role' , null)" class="dropdown-item rounded-1">All</a>
                    </li>
                    @foreach ($this->roles() as $role)
                        <li>
                            <button wire:click="set('role' , '{{ $role->value }}')"
                                class="dropdown-item rounded-1">{{ str($role->value)->title() }}</button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <button class="btn btn-outline-danger btn-sm mx-2" wire:click="resetFilter()">Reset</button>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12">
            <div class="card" id="table-account">
                <div class="card-header">
                    <h4 class="card-title">Account</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->accounts() as $user)
                                    <tr>
                                        <th scope="row">{{ $user->id }}</th>
                                        <td>
                                            <h6 class="mb-0 fs-14 fw-semibold text-nowrap">{{ $user->name }}</h6>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 fs-14 fw-semibold text-nowrap">{{ $user->email }}</h6>
                                        </td>

                                        <td><span
                                                class="badge badge-boxed  badge-outline-{{ $user->role->color() }}">{{ $user->role->value }}</span>
                                        </td>

                                        <td><span class="fw-semibold text-nowrap">{{ $user->phone }}</span></td>
                                        <td>
                                            <h6 class="mb-0 fs-14 fw-semibold text-nowrap">
                                                {{ $user->created_at->format('Y-m-d H:i') }}
                                            </h6>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('dashboard.master-data.account-edit', ['user' => $user]) }}"
                                                    class="link-reset fs-18 p-1"> <i class="ti ti-pencil"></i></a>
                                                <a wire:click="confirmDelete({{ $user->id }})"
                                                    class="link-reset fs-18 p-1  " style="cursor: pointer;">
                                                    <i class="ti ti-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $this->accounts()->links(data: ['scrollTo' => '#table-account']) }}
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->

    </div>
</div>