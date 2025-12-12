<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;

new
    #[Layout('layouts::auth')]
    class extends Component {
    public $name;
    public $email;
    public $phone;
    public $password;

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required',
        ]);

        $user = new User();
        $user->fill($this->all());
        if ($user->save()) {
            sweetalert("Berhasil Registrasi Akun");
            return $this->redirectRoute('login');
        } else {
            sweetalert("Gagal Registrasi Akun", "error");
        }
    }
};
?>

<div>
    <div class="row">
        <div class="col-lg-6 p-0">
            <div
                class="login-backgrounds d-lg-flex align-items-center justify-content-center d-none flex-wrap p-4 position-relative h-100 z-0">
                <img src="assets/img/auth/reg-illustration-img.png" alt="reg-illustration-img" class="img-fluid img1">
            </div>
        </div> <!-- end row-->

        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="row justify-content-center align-items-center overflow-auto flex-wrap vh-100 py-3">
                <div class="col-md-8 mx-auto">
                    <form wire:submit="submit()" class="d-flex justify-content-center align-items-center">
                        <div class="d-flex flex-column justify-content-lg-center p-4 p-lg-0 pb-0 flex-fill">
                            <div class=" mx-auto mb-4 text-center">
                                <img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
                            </div>
                            <div class="card border-1 p-lg-3 shadow-md rounded-3 mb-4">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <h5 class="mb-1 fs-20 fw-bold">Register</h5>
                                        <p class="mb-0">Please enter your details to create account
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-user fs-14 text-dark"></i>
                                            </span>
                                            <input type="text" wire:model="name"
                                                class="form-control border-start-0 ps-0"
                                                placeholder="Masukkan Nama Lengkap">
                                        </div>
                                        @error('name')
                                            <small class="text-danger mx-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Telepon</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-phone fs-14 text-dark"></i>
                                            </span>
                                            <input type="text" wire:model="phone"
                                                class="form-control border-start-0 ps-0"
                                                placeholder="Masukkan Nomor Telepon">
                                        </div>
                                        @error('phone')
                                            <small class="text-danger mx-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-mail fs-14 text-dark"></i>
                                            </span>
                                            <input type="email" wire:model="email"
                                                class="form-control border-start-0 ps-0"
                                                placeholder="Masukkan Alamat Email">
                                        </div>
                                        @error('email')
                                            <small class="text-danger mx-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-key fs-14 text-dark"></i>
                                            </span>
                                            <input type="password" wire:model="password"
                                                class="form-control border-start-0 ps-0"
                                                placeholder="Masukkan Password">
                                        </div>
                                        @error('password')
                                            <small class="text-danger mx-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <button type="submit" class="btn bg-primary text-white w-100">Register</button>
                                    </div>
                                    <div class="text-center">
                                        <h6 class="fw-normal fs-14 text-dark mb-0">Sudah memiliki akun ?
                                            <a href="{{ route('login') }}" class="hover-a">Login </a>
                                        </h6>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div>
                    </form>
                    <p class="text-dark text-center">Copyright &copy; 2025 - Preclinic.</p>
                </div> <!-- end row-->
            </div>
        </div>
    </div>
</div>