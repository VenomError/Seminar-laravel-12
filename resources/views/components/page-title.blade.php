@props(['title' => ''])
<div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
    <div class="flex-grow-1">
        <h4 class="fw-bold mb-0">
            {{ str($title)->title() }}
            {{ $header ?? '' }}
        </h4>
    </div>
    <div class="text-end d-flex">
        {{ $slot }}
    </div>
</div>