@php
  $selectedForm = Statamic\View\Blade\value($form ?? null);
  $formHandle = is_object($selectedForm) ? $selectedForm->handle() : $selectedForm;
  $sectionColor = Statamic\View\Blade\value($section_color ?? 'red');
  $sectionSlug = Str::slug($header);
  $sectionTextColorClass = config("theme.colors.text_dark.{$sectionColor}", config('theme.colors.text_dark.red'));
  $sectionBackgroundColorClass = config("theme.colors.background.{$sectionColor}", config('theme.colors.background.red'));
@endphp

<x-content-section
  :title="$header"
  id="{{ $sectionSlug }}"
  class="{{ $sectionTextColorClass }}"
  x-data
  x-intersect:enter="$store.active_section = '{{ $sectionSlug }}'"
>
  @if($formHandle)
    <statamic:form:create
      :in="$formHandle"
      class="mt-8"
      js="alpine_precognition:form:signupForm"
      x-on:submit.prevent="submit"
      x-bind:aria-busy="form.processing.toString()"
    >
      <fieldset class="grid min-w-0 grid-cols-1 gap-6 sm:grid-cols-2" x-bind:disabled="form.processing">
        @foreach($fields as $field)
          <div class="{{ in_array($field['handle'], ['first_name', 'last_name']) ? '' : 'sm:col-span-2' }}">
            <label for="{{ $field['id'] }}" class="mb-2 block text-lg">
              {{ $field['display'] }}@if(in_array('required', $field['validate'] ?? [])) <span aria-hidden="true">*</span><span class="sr-only"> (required)</span>@endif
            </label>
            <div class="[&_input]:w-full [&_input]:border [&_input]:border-current [&_input]:bg-transparent [&_input]:px-4 [&_input]:py-3 [&_input]:text-brand-black [&_input]:outline-offset-4 [&_textarea]:w-full [&_textarea]:border [&_textarea]:p-3 [&_select]:w-full [&_select]:border [&_select]:p-3">
              {!! $field['field'] !!}
            </div>
            <p
              id="{{ $field['id'] }}-error"
              class="mt-2 text-sm text-red-dark"
              x-cloak
              x-show="form.invalid('{{ $field['handle'] }}')"
              x-text="form.errors['{{ $field['handle'] }}']"
            ></p>
            @if($field['error'])
              <noscript><p class="mt-2 text-sm text-red-dark">{{ $field['error'] }}</p></noscript>
            @endif
          </div>
        @endforeach

        @if($honeypot)
          <input type="text" name="{{ $honeypot }}" x-model="form.{{ $honeypot }}" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
        @endif

        <div class="sm:col-span-2">
          <button type="submit" class="{{ $sectionBackgroundColorClass }} border border-current px-8 py-3 text-lg text-brand-black transition-opacity hover:opacity-80 focus-visible:outline-2 focus-visible:outline-offset-4 disabled:cursor-wait disabled:opacity-60" x-bind:disabled="form.processing">
            <span x-show="! form.processing">Sign up</span>
            <span x-cloak x-show="form.processing">Submitting…</span>
          </button>
        </div>
      </fieldset>

      <p class="mt-6 text-lg" x-cloak x-show="submitted" role="status">Thank you for signing up for updates.</p>
      <p class="mt-6 text-red-dark" x-cloak x-show="submissionError" x-text="submissionError" role="alert"></p>
      @if($success)
        <p class="mt-6 text-lg" role="status">{{ $success }}</p>
      @endif
    </statamic:form:create>
  @endif
</x-content-section>
