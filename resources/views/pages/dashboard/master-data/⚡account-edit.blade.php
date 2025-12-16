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

    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $this->user->name;
        $this->phone = $this->user->phone;
        $this->email = $this->user->email;
        $this->role = $this->user->role->value;
    }

    public $role;

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable',
            'role' => ['required', Rule::in(UserRole::values())],
            'phone' => 'required'
        ];
    }

    public function submit()
    {
        $this->validate();

        try {
            $this->user->fill($this->except('password'));
            if ($this->password) {
                $this->user->password = $this->password;
            }
            if ($this->user->save()) {
                sweetalert("Akun Berhasil di Update");
            }
        } catch (\Throwable $th) {
            throw new \Exception("Gagal Update Akun , {$th->getMessage()}");

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
                        <x-input label="New Password (optional)" parentClass="mb-3 col-lg-6" type="password"
                            model="password" placeholder="Input New Password" />

                        <div class="d-flex  justify-content-end align-items-center">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>Simpan</span>
                                <span wire:loading>Loading...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>