@props(['route', 'label'])

<div class="d-flex justify-content-end mb-2">
    <a href="{{ $route }}" class="btn btn-primary">
        {{ $label }} +
    </a>
</div>
