@props(['name', 'label', 'value' => false])
@php($key = trim(str_replace(['][', '[', ']'], ['.', '.', ''], $name), '.'))
<label class="a-toggle"><input type="hidden" name="{{ $name }}" value="0"><input type="checkbox" name="{{ $name }}" value="1" @checked(old($key, $value))><span>{{ $label }}</span></label>
