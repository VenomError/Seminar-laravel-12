<?php

use App\Enum\SeminarStatus;
use App\Models\Seminar;
use App\Repository\SeminarRepository;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;
    public $title;
    public $thumbnail;
    public $description;
    public $location;
    public $date_start;
    public $quota;
    public $price = 0;
    #[Session]
    public $status = 'draft';

    #[Session]
    public $created_by;

    public function mount()
    {
        $this->created_by = auth()->user();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'date_start' => 'required|date',
            'quota' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'status' => ['required', Rule::in(SeminarStatus::values())],
        ];
    }

    public function submit()
    {
        $this->validate();

        try {
            $repo = new SeminarRepository();
            if ($this->thumbnail) {
                $this->thumbnail = $this->thumbnail->store('seminar', 'public');
            }
            $data = $this->except('created_by');
            $seminar = $repo->create($data, $this->created_by);

            if ($seminar) {
                sweetalert("Seminar berhasil ditambahkan");
                $this->resetExcept('created_by', 'status');
            }
        } catch (\Throwable $th) {
            throw new \Exception("Gagal menambahkan seminar, {$th->getMessage()}");
        }
    }

    public function exception($e, $stopPropagation)
    {
        sweetalert($e->getMessage(), 'error');
        $stopPropagation();
    }
};?>

<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="form row" wire:submit="submit()">

                        <x-input label="Judul Seminar" parentClass="mb-3 col-lg-12" model="title"
                            placeholder="Input Judul Seminar" />

                        <x-input label="Thumbnail Seminar" parentClass="mb-3 col-lg-12" model="thumbnail" type="file"
                            accept="image/*" />

                        <x-select label="Status" parentClass="mb-3" model="status">
                            <option>-- Select Status --</option>
                            @foreach (SeminarStatus::values() as $status)
                                <option value="{{ $status }}">{{ str($status)->title() }}</option>
                            @endforeach
                        </x-select>

                        <x-input label="Lokasi" parentClass="mb-3 col-lg-6" model="location"
                            placeholder="Input Lokasi" />

                        <x-input label="Tanggal Mulai" parentClass="mb-3 col-lg-6" type="datetime-local"
                            model="date_start" />

                        <x-input label="Quota Peserta" parentClass="mb-3 col-lg-6" type="number" model="quota"
                            placeholder="Input Quota" />

                        <x-input label="Harga" parentClass="mb-3 col-lg-6" type="number" model="price"
                            placeholder="0 = Gratis" />

                        <div class="mb-3 col-lg-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" rows="4" wire:model.defer="description"
                                placeholder="Deskripsi seminar"></textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading.remove>Tambah Seminar</span>
                                <span wire:loading>Loading...</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>