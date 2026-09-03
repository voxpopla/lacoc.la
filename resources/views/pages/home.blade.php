<x-split-layout :sections="$content ?? []">
  @php
    $heroData = $hero ?? [];
    $heroHeader = data_get($heroData, 'header');
    $mobileImage = data_get($heroData, 'mobile_image');
    $desktopImage = data_get($heroData, 'desktop_image');
    $fallbackImage = $desktopImage ?: $mobileImage;

    $imageUrl = fn ($image) => is_object($image) && method_exists($image, 'url')
      ? $image->url()
      : data_get($image, 'url');

    $imageAlt = fn ($image) => is_object($image) && method_exists($image, 'get')
      ? $image->get('alt', $heroHeader ?? config('app.name'))
      : data_get($image, 'alt', $heroHeader ?? config('app.name'));
  @endphp

  @if($heroHeader || $fallbackImage)
    <section class="flex flex-col h-[calc(100vh-8rem)] p-4 pb-12 sm:pb-16 sm:pt-8 lg:px-9 lg:h-[calc(100vh-2.25rem)]" x-data x-intersect:enter="$store.active_section = ''">
      @if($heroHeader)
        <h1 class="mb-6 sm:mb-12 font-alpina text-5xl leading-none sm:text-[54px]">
          {{ $heroHeader }}
        </h1>
      @endif

      @if($desktopImage && $mobileImage)
        <div class="flex-1 min-h-0">
          <picture>
            <source
              media="(max-width: 640px)"
              srcset="{{ $imageUrl($mobileImage) }}"
            >
            <source
              media="(min-width: 641px)"
              srcset="{{ $imageUrl($desktopImage) }}"
              sizes="(max-width: 1024px) 100vw, 1024px"
            >
            <img src="{{ $imageUrl($desktopImage) }}" alt="{{ $imageAlt($desktopImage) }}" class="w-full h-full object-cover">
          </picture>
        </div>
      @elseif($fallbackImage)
        <div class="flex-1 min-h-0">
          <img src="{{ $imageUrl($fallbackImage) }}" alt="{{ $imageAlt($fallbackImage) }}" class="w-full h-full object-cover">
        </div>
      @endif
    </section>
  @endif

  <div class="space-y-22">
    @foreach ($content ?? [] as $section)
      @continue(isset($section['enabled']) && ! $section['enabled'])

      @php
        $sectionType = Statamic\View\Blade\value($section['type'] ?? null);
      @endphp

      @includeIf("content.{$sectionType}", [...$section])
    @endforeach
  </div>
</x-split-layout>
