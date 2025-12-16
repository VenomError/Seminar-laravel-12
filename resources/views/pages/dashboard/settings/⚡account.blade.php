<?php

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

new class extends Component {

    public User $user;

    public $name;
    public $email;
    public $phone;

    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $this->user = auth()->user();

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'phone' => 'nullable|string|max:20',

            // password opsional
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ];
    }

    public function updateProfile()
    {
        $this->validate();

        // kalau ganti password → validasi password lama
        if ($this->password) {
            if (!Hash::check($this->current_password, $this->user->password)) {
                $this->addError('current_password', 'Password lama tidak sesuai');
                return;
            }

            $this->user->password = Hash::make($this->password);
        }

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        sweetalert('Profil berhasil diperbarui');
        $this->reset('current_password', 'password', 'password_confirmation');
    }
};?>

<div>
    <x-page-title title="Pengaturan Akun" />

    <div class="row">

        {{-- PROFILE --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Informasi Akun</h5>

                    <form wire:submit.prevent="updateProfile">
                        <x-input label="Nama" model="name" parentClass="mb-3" />
                        <x-input label="Email" model="email" type="email" parentClass="mb-3" />
                        <x-input label="No. Telepon" model="phone" parentClass="mb-3" />

                        <button class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Simpan Perubahan</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- PASSWORD --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Ubah Password</h5>

                    <form wire:submit.prevent="updateProfile">
                        <x-input label="Password Lama" model="current_password" type="password" parentClass="mb-3" />

                        <x-input label="Password Baru" model="password" type="password" parentClass="mb-3" />

                        <x-input label="Konfirmasi Password" model="password_confirmation" type="password"
                            parentClass="mb-3" />

                        <button class="btn btn-warning" wire:loading.attr="disabled">
                            <span wire:loading.remove>Update Password</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>