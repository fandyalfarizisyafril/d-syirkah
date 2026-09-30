@props(['name', 'label', 'value' => '', 'type' => 'text', 'options' => [], 'required' => false, 'rows' => 4])
@php
    $key = trim(str_replace(['][', '[', ']'], ['.', '.', ''], $name), '.');
    $id = 'field-'.str_replace('.', '-', $key);
    $current = old($key, $value);
@endphp
<div class="a-field">
    <label for="{{ $id }}">{{ $label }}@if($required)<span aria-hidden="true"> *</span>@endif</label>
    @if($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}" @required($required) {{ $attributes }}>{{ $current }}</textarea>
    @elseif($type === 'select')
        <select name="{{ $name }}" id="{{ $id }}" @required($required) {{ $attributes }}>@foreach($options as $optionValue => $optionLabel)<option value="{{ $optionValue }}" @selected((string)$current === (string)$optionValue)>{{ $optionLabel }}</option>@endforeach</select>
    @else
        <input name="{{ $name }}" id="{{ $id }}" type="{{ $type }}" @if(!in_array($type, ['password', 'file'])) value="{{ $current }}" @endif @required($required) {{ $attributes }}>
    @endif
</div>
