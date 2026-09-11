<input
    id="{{ $id }}"
    type="{{ $input_type ?? 'text' }}"
    name="{{ $name }}"
    value="{{ $value ?? '' }}"
    @if (isset($placeholder)) placeholder="{{ $placeholder }}" @endif
    @if (isset($character_limit)) maxlength="{{ $character_limit }}" @endif
    @if (isset($autocomplete)) autocomplete="{{ $autocomplete }}" @endif
    @if (isset($js_driver)) {!! $js_attributes !!} @endif
    @required(in_array('required', $validate ?? []))
    @if (($js_driver ?? null) === 'alpine_precognition')
        x-bind:aria-invalid="form.invalid('{{ $handle }}').toString()"
        aria-describedby="{{ $id }}-error"
    @elseif ($error)
        aria-invalid="true" aria-describedby="{{ $id }}-error"
    @elseif ($instructions)
        aria-describedby="{{ $id }}-instructions"
    @endif
>
