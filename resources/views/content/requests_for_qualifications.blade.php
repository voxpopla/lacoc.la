@php
  $sectionColor = Statamic\View\Blade\value($section_color ?? 'yellow');
  $sectionSlug = Str::slug($header);
  $sectionTextColorClass = config("theme.colors.text_dark.{$sectionColor}", config('theme.colors.text.yellow'));
  $sectionTextLightHoverColorClass = config("theme.colors.text_light_hover.{$sectionColor}", config('theme.colors.text_light_hover.yellow'));
  $sectionBackgroundHoverColorClass = config("theme.colors.background_dark_hover.{$sectionColor}", config('theme.colors.background_dark_hover.yellow'));
@endphp

<x-content-section
  :title="$header"
  :description="$description ?? null"
  id="{{ $sectionSlug }}"
  class="{{ $sectionTextColorClass }}"
  x-data
  x-intersect:enter="$store.active_section = '{{ $sectionSlug }}'"
>
  <livewire:request-for-qualifications
    :text-light-hover-color-class="$sectionTextLightHoverColorClass"
    :background-hover-color-class="$sectionBackgroundHoverColorClass"
  />
</x-content-section>
