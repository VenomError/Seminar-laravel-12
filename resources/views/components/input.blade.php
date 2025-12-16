@props([
    'label' => '',
    'model',
    'parentClass' => 'mb-3'
])
<div class="{{ $parentClass }}">
    <label for="input-{{ str($model)->replace('.', '-') }}" class="form-label">{{ str($label)->title() }}</label>
    <input 
    {{ $attributes->merge([
    'wire:model' => $model,
    'type' => 'text'
]) }}
    class="form-control" id="input-{{ str($model)->replace('.', '-') }}">
    @error($model)
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>