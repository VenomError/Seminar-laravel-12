@props([
    'label' => '',
    'model',
    'parentClass' => 'mb-3'
])

<div class="{{ $parentClass }}">
    <label for="input-{{ str($model)->replace('.', '-') }}" class="form-label">
        {{ str($label)->title() }}
    </label>

    <select
        {{ $attributes->merge([
            'wire:model' => $model
        ]) }}
        class="form-select"
        id="input-{{ str($model)->replace('.', '-') }}"
    >
        {{ $slot }}
    </select>

    @error($model)
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
