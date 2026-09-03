@php
  $sectionColor = Statamic\View\Blade\value($section_color ?? 'yellow');
  $sectionSlug = Str::slug($header);
  $sectionTextColorClass = config("theme.colors.text_dark.{$sectionColor}", config('theme.colors.text.yellow'));
  $sectionTextDarkHoverColorClass = config("theme.colors.text_dark_hover.{$sectionColor}", config('theme.colors.text_dark_hover.yellow'));
  $sectionTextLightHoverColorClass = config("theme.colors.text_light_hover.{$sectionColor}", config('theme.colors.text_light_hover.yellow'));
  $sectionBackgroundDarkColorClass = config("theme.colors.background_dark.{$sectionColor}", config('theme.colors.background_dark.yellow'));
  $sectionBackgroundHoverColorClass = config("theme.colors.background_dark_hover.{$sectionColor}", config('theme.colors.background_dark_hover.yellow'));
  $sectionBorderDarkColorClass = config("theme.colors.border_dark.{$sectionColor}", config('theme.colors.border_dark.yellow'));
@endphp

<x-content-section
  :title="$header"
  :description="$description ?? null"
  id="{{ $sectionSlug }}"
  class="{{ $sectionTextColorClass }}"
  x-data
  x-intersect:enter="$store.active_section = '{{ $sectionSlug }}'"
>
  <livewire:meetings
    :background-dark-color-class="$sectionBackgroundDarkColorClass"
    :background-hover-color-class="$sectionBackgroundHoverColorClass"
    :border-dark-color-class="$sectionBorderDarkColorClass"
    :text-dark-hover-color-class="$sectionTextDarkHoverColorClass"
    :text-light-hover-color-class="$sectionTextLightHoverColorClass"
  />
</x-content-section>
