@php
  $description = $meeting->augmentedValue('description')->value();
  $date = $meeting->augmentedValue('date')->value();
  $featureLink = $meeting->augmentedValue('feature_link')->value() ?? [];
  $links = $meeting->augmentedValue('links')->value() ?? [];
  $featureName = $this->fieldValue($featureLink, 'name');
  $featureUrl = $this->linkUrl($featureLink);
@endphp

<article class="border-t border-current py-5">
  <h3 class="text-2xl leading-none sm:text-3xl">
    {{ $meeting->get('title') }}
  </h3>

  @if($date)
    <div class="mt-6 font-spectral text-lg leading-none sm:text-xl">
      {{ $this->formatMeetingDate($date) }}
    </div>
  @endif

  @if($description)
    <div class="mt-6 font-spectral text-lg leading-tight sm:text-xl [&_p]:mb-4 [&_p:last-child]:mb-0">
      {!! $description !!}
    </div>
  @endif

  @if(count($links))
    <div class="mt-7 flex flex-wrap gap-x-6 gap-y-3 font-spectral text-lg uppercase leading-none sm:text-xl">
      @foreach($links as $link)
        @php
          $linkName = $this->fieldValue($link, 'name');
          $linkUrl = $this->linkUrl($link);
        @endphp

        @if($linkName && $linkUrl)
          <a
            href="{{ $linkUrl }}"
            class="underline transition {{ $textLightHoverColorClass }}"
            @if($this->linkTarget($link)) target="{{ $this->linkTarget($link) }}" @endif
            @if($this->linkRel($link)) rel="{{ $this->linkRel($link) }}" @endif
          >
            {{ $linkName }}
          </a>
        @elseif($linkName)
          <span>{{ $linkName }}</span>
        @endif
      @endforeach
    </div>
  @endif

  @if($featureName && $featureUrl)
    <a
      href="{{ $featureUrl }}"
      class="mt-7 block border p-4 text-center font-spectral text-lg uppercase leading-none underline text-brand-white transition sm:text-xl {{ $backgroundDarkColorClass }} {{ $borderDarkColorClass }} hover:bg-brand-white {{ $textDarkHoverColorClass }}"
      @if($this->linkTarget($featureLink)) target="{{ $this->linkTarget($featureLink) }}" @endif
      @if($this->linkRel($featureLink)) rel="{{ $this->linkRel($featureLink) }}" @endif
    >
      {{ $featureName }}
    </a>
  @endif
</article>
