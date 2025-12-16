<?php

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Session;
use Livewire\Component;

new class extends Component {
    public $name;
    public $phone;
    public $email;
    public $password;

    #[Session]
    public $role;

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => ['required', Rule::in(UserRole::values())],
            'phone' => 'required'
        ];
    }

    public function submit()
    {
        $this->validate();

        try {
            $user = new User();
            $user->fill($this->all());
            if ($user->save()) {
                sweetalert("Akun Berhasil di Tambahkan");
                $this->resetExcept('role');
            }
        } catch (\Throwable $th) {
            throw new \Exception("Gagal Menambahkan Akun , {$th->getMessage()}");

        }
    }

    public function exception($e, $stopPropagation)
    {
        sweetalert($e->getMessage(), 'error');
        $stopPropagation();
    }
};
?>

<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="form row" wire:submit="submit()">
                        <x-select label="Role" parentClass="mb-3" model="role">
                            <option>-- Select Role --</option>
                            @foreach (UserRole::cases() as $role)
                                <option value="{{ $role->value }}">{{ $role->label() }}</option>
                            @endforeach
                        </x-select>
                        <x-input label="Name" parentClass="mb-3 col-lg-6" type="text" model="name"
                            placeholder="Input Name" />
                        <x-input label="Email" parentClass="mb-3 col-lg-6" type="email" model="email"
                            placeholder="Input Email" />
                        <x-input label="Phone" parentClass="mb-3 col-lg-6" type="tel" model="phone"
                            placeholder="Input Phone" />
                        <x-input label="Password" parentClass="mb-3 col-lg-6" type="password" model="password"
                            placeholder="Input Password" />

                        <div class="d-flex  justify-content-end align-items-center">
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading.remove>Add Account</span>
                                <span wire:loading>Loading...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>