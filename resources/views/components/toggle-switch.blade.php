<!-- resources/views/components/toggle-switch.blade.php -->
<div class="form-check form-switch">
    <input type="checkbox" class="form-check-input toggle-switch" data-id="{{ $id }}" id="toggleSwitch{{ $id }}" {{ $status ? 'checked' : '' }}>
    <label class="form-check-label" for="toggleSwitch{{ $id }}">
        {{ $status ? __('On') : __('Off') }}
    </label>
</div>
