<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

new
    #[Layout('layouts::auth')]
    class extends Component {
    public $email;
    public $password;
    public $remember = false;

    public function submit()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return redirect()->route('dashboard');
        }
        $this->reset('password');
        sweetalert()->error('Email atau Password Salah', title: 'Login Gagal');
    }
};
?>

<div>
    <div class="row">
        <div class="col-lg-6 p-0">
            <div
                class="login-backgrounds d-lg-flex align-items-center justify-content-center d-none flex-wrap p-4 position-relative h-100 z-0">
                <img src="assets/img/icons/log-illustration-img-01.png" alt="log-illustration-img-01"
                    class="img-fluid img1">
            </div>
        </div> <!-- end row-->

        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="row justify-content-center align-items-center overflow-auto flex-wrap vh-100 py-3">
                <div class="col-md-8 mx-auto">
                    <form wire:submit.prevent="submit()" class="d-flex justify-content-center align-items-center">
                        <div class="d-flex flex-column justify-content-lg-center p-4 p-lg-0 pb-0 flex-fill">
                            <div class=" mx-auto mb-4 text-center">
                                <img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
                            </div>
                            <div class="card border-1 p-lg-3 shadow-md rounded-3 mb-4">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <h5 class="mb-1 fs-20 fw-bold">Sign In</h5>
                                        <p class="mb-0">Please enter below details to access the dashboard
                                        </p>
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
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-check-md mb-0">
                                                <input class="form-check-input" id="remember_me" wire:model="remember"
                                                    type="checkbox">
                                                <label for="remember_me"
                                                    class="form-check-label mt-0 text-dark">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <button type="submit" class="btn bg-primary text-white w-100">Login</button>
                                    </div>
                                    <div class="text-center">
                                        <h6 class="fw-normal fs-14 text-dark mb-0">Don’t have an account
                                            yet?
                                            <a href="{{ route('register') }}" class="hover-a">
                                                Register</a>
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